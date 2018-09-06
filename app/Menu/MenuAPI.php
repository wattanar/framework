<?php

namespace App\Menu;

use Core\Database;

class MenuAPI
{
  private $db = null;

  public function __construct() {
    $this->db = Database::connect();
  }

  public function getMenu() {
    return Database::rows(
      $this->db,
      "SELECT 
      M.id,
      M.menu_link,
      M.menu_name,
      M.menu_position,
      M.menu_parent,
      M.menu_order,
      M.menu_status,
      C.cap_name,
      C.id AS cap_id
      FROM web_menus M
      LEFT JOIN web_capabilities C
      ON C.id = M.menu_capabilities
      ORDER BY id ASC"
    );
  }

  public function createMenu($link, $name) {
    $create = Database::query(
      $this->db,
      "INSERT INTO web_menus(
        menu_link,
        menu_name,
        menu_position,
        menu_parent,
        menu_order,
        menu_status
      ) VALUES(?, ?, ?, ?, ?, ?)",
      [
        $link,
        $name,
        0,
        0,
        0,
        1
      ]
    );

    if ( $create ) {
      return [
        'result' => true,
        'message' => 'Create successful!'
      ];
    } else {
      return [
        'result' => false,
        'message' => 'Create Failed!'
      ];
    }
  }

  public function editMenu($id, $link, $name, $position, $parent, $order, $status) {

    if ($status === 'true') {
      $s_ = 1;
    } else {
      $s_ = 0;
    }

    $update = Database::query(
      $this->db,
      "UPDATE web_menus 
        SET menu_link = ?,
        menu_name = ?,
        menu_position = ?,
        menu_parent = ?,
        menu_order = ?,
        menu_status = ?
        WHERE id = ?",
      [
        $link,
        $name,
        $position,
        $parent,
        $order,
        $s_,
        $id
      ]
    );

    if ( $update ) {
      return [
        'result' => true,
        'message' => 'Update successful!'
      ];
    } else {
      return [
        'result' => false,
        'message' => 'Update Failed!'
      ];
    }
  }

  public function generateMenu(string $type, int $menu_id = 0) : array {

    switch ($type) {
      case 'root':
        return Database::rows(
          $this->db,
          "SELECT
          M.id,
          M.menu_link,
          M.menu_name,
          M.menu_position
          FROM web_menus M
          WHERE M.menu_status = 1
          AND M.menu_parent = 0
          AND M.menu_order <> 0
          ORDER BY M.menu_position, M.menu_order ASC"
        );
        break;

      case 'sub':
        
        $menu = [];

        $rows =  Database::rows(
          $this->db,
          "SELECT
          M.id,
          M.menu_link,
          M.menu_name,
          M.menu_parent
          FROM web_menus M
          WHERE M.menu_status = 1
          AND M.menu_parent <> 0
          AND M.menu_parent = ?
          ORDER BY M.menu_order ASC",
          [
            $menu_id
          ]
        );

        foreach ($rows as $row) {
          $menu[] = [
            'id' => $row['id'],
            'link' => $row['menu_link'],
            'name' => $row['menu_name'],
            'sub' => self::generateMenu('sub', $row['id'])
          ];
        }

        return $menu;
        break;
      
      default:
        return [];
        break;
    }
  }

  public function updateCapabilities($menu_id, $cap_id) {

    $update = Database::query(
      $this->db,
      "UPDATE web_menus
      SET menu_capabilities = ?
      WHERE id = ?",
      [
        $cap_id,
        $menu_id
      ]
    );

    if ( $update ) {
      return [
        'result' => true,
        'message' => 'Update successful!'
      ];
    } else {
      return [
        'result' => false,
        'message' => 'Update Failed!'
      ];
    }
  }

  public function allow($uri, $cap_id) : array {
    
    $isExists = Database::hasRows(
      $this->db,
      "SELECT menu_link
      FROM web_menus
      WHERE menu_link = ?
      AND menu_status <> 0",
      [
        $uri
      ]
    );

    if ( $isExists === false ) {
      return [
        'result' => false,
        'message' => 'Uri not found.'
      ];
    }

    $menu_ = Database::rows(
      $this->db,
      "SELECT
      menu_link,
      menu_capabilities,
      menu_status
      FROM web_menus"
    );

    if ( (int)$menu_[0]['menu_capabilities'] === 0 ) {
      return [
        'result' => true,
        'message' => 'Uri not set capability!'
      ];
    }

    if ( in_array($menu_[0]['menu_capabilities'], $cap_id) === true ) {
      return [
        'result' => true,
        'message' => 'Uri matched!'
      ];
    } else {
      return [
        'result' => false,
        'message' => 'Uri not match!'
      ];
    }
  }
}