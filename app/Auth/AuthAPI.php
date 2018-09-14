<?php

namespace App\Auth;

use Core\JWT;
use Core\Render;
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

  public function accessPage($cap_slug) {
    
    $user_data = self::verifyToken();

    if ( $user_data['result'] === false ) {
      return [
        'result' => false,
        'message' => 'Please login!'
      ];
    }

    $checkCap = self::checkCapByRole(
      $user_data['payload']['user_data']->role, 
      $cap_slug
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
      WHERE C.cap_slug = ?
      AND P.role_id = ?",
      [
        $cap_slug,
        $user_role
      ]
    );
  }
}