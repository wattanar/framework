<?php

namespace App\User;

use Core\Database;
use Core\JWT;
use Core\Response;
use App\Menu\MenuAPI;

class UserAPI
{
  private $db = null;
  
  public function __construct() {
    $this->db = Database::connect();
    $this->menu = new MenuAPI;
  }

  public function userAuth($user_login, $user_pass) {

    if ( self::isUserExists($user_login) === false ) {
      return [
        'result' => false,
        'message' =>  'User not found!'
      ];
    }

    $pass_hash = Database::rows(
      $this->db, 
      "SELECT user_pass 
      FROM web_users
      WHERE user_login = ?",
      [
        \htmlspecialchars($user_login)
      ]
    );

    if ( password_verify($user_pass, $pass_hash[0]['user_pass']) === false ) {
      return [
        'result' => false,
        'message' => 'Password incorrect!'
      ];
    } else {
      return [
        'result' => true,
        'message' => 'Passed!'
      ];
    }
  }

  public function isUserExists($user_login) {
    return Database::hasRows(
      $this->db,
      "SELECT user_login 
      FROM web_users
      WHERE user_login = ?",
      [
        \htmlspecialchars($user_login)
      ]
    );
  }

  public function verifyToken() {

    if ( !isset($_COOKIE[TOKEN_NAME]) )  {
      return [
				'result' => false,
				'message' => 'token invalid',
				'payload' => []
			];
    }

    return JWT::validateToken($_COOKIE[TOKEN_NAME]);
  }

  public function updatePassword($username, $new_pass) {

    if ( trim($new_pass) === '' ) {
      return false;
    }

    $pass_salt = password_hash($new_pass, PASSWORD_DEFAULT);

    $update = Database::query(
      $this->db,
      "UPDATE web_users
      SET user_pass = ?
      WHERE user_login = ?",
      [
        $pass_salt,
        $username
      ]
    );

    if ($update) {
      return true;
    } else {
      return false;
    }
  }

  public function getUserInfo($user_login) {
    return Database::rows(
      $this->db,
      "SELECT 
      user_login, 
      user_email, 
      user_status,
      user_firstname,
      user_lastname,
      user_registered,
      user_role
      FROM web_users
      WHERE user_login = ?",
      [
        htmlspecialchars($user_login)
      ]
    );
  }

  public function updateProfile($user_login, $user_data) {
    $update = Database::query(
      $this->db,
      "UPDATE web_users
      SET user_email = ?,
      user_firstname = ?,
      user_lastname = ?
      WHERE user_login = ?",
      [
        $user_data['email'],
        $user_data['firstname'],
        $user_data['lastname'],
        $user_login
      ]
    );

    if ($update) {
      return true;
    } else {
      return false;
    }
  }

  public function getRoles() {

    return Database::rows(
      $this->db,
      "SELECT 
      id,
      role_name,
      role_status
      FROM web_roles"
    );
  }

  public function getRolesActive() {

    return Database::rows(
      $this->db,
      "SELECT 
      id,
      role_name,
      role_status
      FROM web_roles
      WHERE role_status = 1"
    );
  }

  public function getCapabilities() {
    return Database::rows(
      $this->db,
      "SELECT 
      id,
      cap_slug,
      cap_name,
      cap_status
      FROM web_capabilities"
    );
  }

  public function getCapabilitiesActive() {
    return Database::rows(
      $this->db,
      "SELECT 
      id,
      cap_slug,
      cap_name,
      cap_status
      FROM web_capabilities
      WHERE cap_status = 1"
    );
  }

  public function createRoles($name) {

    $create = Database::query(
      $this->db,
      "INSERT INTO web_roles(
        role_name,
        role_status
      ) VALUES(?, ?)",
      [
        $name,
        1
      ]
    );

    if ($create) {
      return [
        'result' => true,
        'message' => 'Create role successful!'
      ];
    } else {
      return [
        'result' => false,
        'message' => 'Create role failed!'
      ];
    }
  }

  public function createCapabilities($slug, $name) {

    $checkSlug = Database::hasRows(
      $this->db,
      "SELECT cap_slug
      FROM web_capabilities
      WHERE cap_slug = ?",
      [
        $slug
      ]
    );

    if ( $checkSlug === true ) {
      return [
        'result' => false,
        'message' => 'Role slug is already exists!'
      ];
    }

    $create = Database::query(
      $this->db,
      "INSERT INTO web_capabilities(
        cap_slug,
        cap_name,
        cap_status
      ) VALUES(?, ?, ?)",
      [
        $slug,
        $name,
        1
      ]
    );

    if ($create) {
      return [
        'result' => true,
        'message' => 'Create Capabilities successful!'
      ];
    } else {
      return [
        'result' => false,
        'message' => 'Create Capabilities failed!'
      ];
    }
  }

  public function editRoles($id, $name, $status) {

    if ((string)$status === 'true') {
      $_status = 1;
    }  else {
      $_status = 0;
    }

    $update = Database::query(
      $this->db,
      "UPDATE web_roles
      SET role_name = ?,
      role_status = ?
      WHERE id = ?",
      [
        $name,
        $_status,
        $id
      ]
    );

    if ($update) {
      return [
        'result' => true,
        'message' => 'Update role successful!'
      ];;
    } else {
      return [
        'result' => false,
        'message' => 'Update role failed!'
      ];
    }
  }

  public function editCapabilities($id, $slug, $name, $status) {

    $checkSlug = Database::hasRows(
      $this->db,
      "SELECT cap_slug
      FROM web_capabilities
      WHERE cap_slug = ?
      AND id <> ?",
      [
        $slug,
        $id
      ]
    );

    if ( $checkSlug === true ) {
      return [
        'result' => false,
        'message' => 'Role slug is already exists!'
      ];
    }

    if ((string)$status === 'true') {
      $_status = 1;
    }  else {
      $_status = 0;
    }

    $update = Database::query(
      $this->db,
      "UPDATE web_capabilities
      SET cap_slug = ?,
      cap_name = ?,
      cap_status = ?
      WHERE id = ?",
      [
        $slug,
        $name,
        $_status,
        $id
      ]
    );

    if ($update) {
      return [
        'result' => true,
        'message' => 'Update Capabilities successful!'
      ];;
    } else {
      return [
        'result' => false,
        'message' => 'Update Capabilities failed!'
      ];
    }
  }

  public function getAllUsers() {
    return Database::rows(
      $this->db,
      "SELECT 
      W.id,
      W.user_login,
      W.user_email,
      W.user_registered,
      W.user_firstname,
      W.user_lastname,
      R.id AS role_id,
      R.role_name AS user_role,
      W.user_status
      FROM web_users W 
      LEFT JOIN web_roles R 
      ON R.id = W.user_role"
    );
  }

  public function editUsers($id, $user_email, $user_status, $user_firstname, $user_lastname) {

    if ($user_status === 'true') {
      $s = 1;
    } else {
      $s = 0;
    }

    $update = Database::query(
      $this->db,
      "UPDATE web_users
      SET user_email = ?,
      user_status = ?,
      user_firstname = ?,
      user_lastname = ?
      WHERE id = ?",
      [
        $user_email,
        $s,
        $user_firstname,
        $user_lastname,
        $id
      ]
    );

    if ($update) {
      return Response::result(true, 'Update successful!');
    } else {
      return Response::result(false, 'Update Failed!');
    }
  }

  public function updateRoles($user_id, $role_id) {
    $update = Database::query(
      $this->db,
      "UPDATE web_users
      SET user_role = ?
      WHERE id = ?",
      [
        $role_id,
        $user_id
      ]
    );

    if ($update) {
      return Response::result(true, 'Update successful!');
    } else {
      return Response::result(false, 'Update Failed!');
    }
  }

  public function createUser($user_login, $user_password) {

    $pass_salt = password_hash($user_password, PASSWORD_DEFAULT);

    $checkUser = sqlsrv_has_rows(sqlsrv_query(
      $this->db,
      "SELECT user_login 
      FROM web_users
      WHERE user_login = ?",
      [
        $user_login
      ]
    ));

    if ( $checkUser === true ) {
      return [
        'result' => false,
        'message' => "This " . $user_login . " has already exists."
      ];
    }

    $createUser = sqlsrv_query(
      $this->db,
      "INSERT INTO web_users(
        user_login,
        user_pass,
        user_registered,
        user_status
      ) VALUES(?, ?, ?, ?)",
      [
        strtolower($user_login),
        $pass_salt,
        date('Y-m-d H:i:s'),
        1
      ]
    );

    if ( $createUser ) {
      return [
        'result' => true,
        'message' => 'Create user successful!'
      ];
    } else {
      return [
        'result' => false,
        'message' => 'Create user failed!'
      ];
    }
  }

  public function updateCapabilities($role_id, $cap_id) : array{
    
    if ( count($cap_id) === 0 ) {
      return [
        'result' => false,
        'message' => 'Capabilities not found!'
      ];
    }

    $delete = Database::query(
      $this->db,
      "DELETE FROM web_permissions
      WHERE role_id = ?",
      [
        $role_id
      ]
    );

    foreach ($cap_id as $cap) {

      $has = Database::hasRows(
        $this->db,
        "SELECT id
        FROM web_permissions
        WHERE role_id = ?
        AND cap_id = ?",
        [
          $role_id,
          $cap
        ]
      );

      if ( $has === false) {
        $insert = Database::query(
          $this->db,
          "INSERT INTO web_permissions(
            role_id,
            cap_id
          ) VALUES(?, ?) ",
          [
            $role_id,
            $cap
          ]
        );
      }
    }

    return [
      'result' => true,
      'message' => 'Update successful!!'
    ];
  }

  public function getCapabilitiesByRoles($role_id) : array {
    $cap = Database::rows(
      $this->db,
      "SELECT 
      C.id AS cap_id,
      C.cap_name,
      CASE 
        WHEN P.cap_id IS NULL OR P.cap_id = '' THEN 0
        ELSE 1
      END AS selected
      FROM web_capabilities C
      LEFT JOIN web_permissions P
      ON P.cap_id = C.id
      AND P.role_id = ?",
      [
        $role_id
      ]
    );

    if (count($cap) !== 0) {
      return $cap;
    } else {
      return [];
    }
  }

  public function userCan($cap_slug) {
    $auth = self::verifyToken();

    if ( $auth['result'] === false ) {
      return false;
    }

    return Database::hasRows(
      $this->db,
      "SELECT P.id 
      FROM web_permission P
      LEFT JOIN web_capabilities C
      ON C.cap_id = P.cap_id
      WHERE C.cap_slug = ?
      AND P.role_id = ?",
      [
        $cap_slug,
        $auth['payload']['user_data']->role
      ]
    );
  }

  public function deleteCapabilities($cap_id) {

    $isRoleUsing = Database::hasRows(
      $this->db,
      "SELECT P.role_id
      FROM web_permissions P
      WHERE P.cap_id = ?",
      [
        $cap_id
      ]
    );

    if ( $isRoleUsing === true ) {
      return [
        'result' => false,
        'message' => 'Capability in use!'
      ];
    }

    $deleteCap = Database::query(
      $this->db,
      "DELETE FROM web_capabilities
      WHERE id = ?",
      [
        $cap_id
      ]
    );

    if ( $deleteCap ) {
      return [
        'result' => true,
        'message' => 'Capability deleted!'
      ];
    } else {
      return [
        'result' => false,
        'message' => 'Capability unable to delete!'
      ];
    }
  }

  public function deleteRoles($role_id) {

    $isRoleUsing = Database::hasRows(
      $this->db,
      "SELECT U.user_role
      FROM web_users U
      WHERE U.user_role = ?",
      [
        $role_id
      ]
    );

    if ( $isRoleUsing === true ) {
      return [
        'result' => false,
        'message' => 'Role in use!'
      ];
    }

    $deleteRole = Database::query(
      $this->db,
      "DELETE FROM web_roles
      WHERE id = ?",
      [
        $role_id
      ]
    );

    if ( $deleteRole ) {
      return [
        'result' => true,
        'message' => 'Role deleted!'
      ];
    } else {
      return [
        'result' => false,
        'message' => 'Role unable to delete!'
      ];
    }
  }
}