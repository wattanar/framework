<?php

namespace App\Auth;

use Core\JWT;
use Core\Database;

class AuthAPI 
{
  public function __construct() {
    $this->db = Database::connect();
  }

  public function verifyToken() {

    if ( !isset($_COOKIE[TOKEN_NAME]) )  {
      return [
				'result' => false,
				'message' => 'token invalid!',
				'payload' => []
			];
    }

    return JWT::verifyToken($_COOKIE[TOKEN_NAME]);
  }

  public function accessPage($role_id, $cap_id = null) {

    if ($cap_id === null) {
      $currentUrl = APP_ROOT . $_SERVER['REQUEST_URI'];

      if ($currentUrl === '') $currentUrl = '/';

      $getCapByMenuLink = Database::rows(
        $this->db,
        "SELECT TOP 1 menu_capabilities 
        FROM web_menus
        WHERE menu_link = ?",
        [
          str_replace('//', '/', trim($currentUrl)) 
        ]
      );

      if ( count($getCapByMenuLink) === 0 ) {
        return [
          'result' => false,
          'message' => 'Not passed!'
        ];
      }

      $cap_id = $getCapByMenuLink[0]['menu_capabilities'];
    }
    
    $checkCap = self::checkCapByRole(
      $role_id, 
      $cap_id
    );

    if ($checkCap === true) {
      return [
        'result' => true,
        'message' => 'Passed!'
      ];
    } else {
      return [
        'result' => false,
        'message' => 'Not passed!'
      ];
    }
  }

  public function checkCapByRole($user_role, $cap_slug) {
    return Database::hasRows(
      $this->db,
      "SELECT 
      P.cap_id
      FROM web_permissions P
      LEFT JOIN web_capabilities C
      ON P.cap_id = C.id
      WHERE C.id = ?
      AND P.role_id = ?",
      [
        $cap_slug,
        $user_role
      ]
    );
  }
}