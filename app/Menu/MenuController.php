<?php

namespace App\Menu;

use Core\Render;
use Core\Validate;
use App\Menu\MenuAPI;

class MenuController 
{
  private $menu = null;

  public function __construct() {
    $this->menu = new MenuAPI;
  }

  public function index($request, $response, $args) {
    return Render::View('pages/menu/menu');
  }

  public function getMenu($request, $response, $args) {
    return $response->withJson($this->menu->getMenu());
  }

  public function createMenu($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

    $create = $this->menu->createMenu(
      Validate::clean($parsedBody['menu_link']),
      Validate::clean($parsedBody['menu_name'])
    );

    if ($create['result'] === false) {
      return $response->withJson([
        'result' => false,
        'message' => $create['message']
      ]);
    } else {
      return $response->withJson([
        'result' => true,
        'message' => $create['message']
      ]);
    }
  }

  public function editMenu($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

    $update = $this->menu->editMenu(
      Validate::clean($parsedBody['id']),
      Validate::clean($parsedBody['menu_link']),
      Validate::clean($parsedBody['menu_name']),
      Validate::clean($parsedBody['menu_position']),
      Validate::clean($parsedBody['menu_parent']),
      Validate::clean($parsedBody['menu_order']),
      Validate::clean($parsedBody['menu_status'])
    );

    if ($update['result'] === false) {
      return $response->withJson([
        'result' => false,
        'message' => $update['message']
      ]);
    } else {
      return $response->withJson([
        'result' => true,
        'message' => $update['message']
      ]);
    }
  }

  public function generateMenuHTML() : string {
    $roots = $this->menu->generateMenu('root');

    $menu = [];

    foreach ($roots as $v) {
      $menu[] = [
        'id' => $v['id'],
        'link' => $v['menu_link'],
        'name' => $v['menu_name'],
        'sub' => $this->menu->generateMenu('sub', $v['id'])
      ];
    }

    $menu_generated = '';

    $menu_generated = self::getMenuChild($menu);

    return $menu_generated;
  }

  public function getMenuChild($menu) : string {

  }

  public function generateMenu() : string {
    
    $roots = $this->menu->generateMenu('root');

    $menu = [];

    foreach ($roots as $v) {
      $menu[] = [
        'id' => $v['id'],
        'link' => $v['menu_link'],
        'name' => $v['menu_name'],
        'sub' => $this->menu->generateMenu('sub', $v['id'])
      ];
    }

    $menu_generated = '';

    foreach( $menu as $m ) {

      $menu_generated .= '
      <li>
        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
          ' . $m['name'] . '
          <b class="caret"></b>
        </a>';

      if (count($m['sub']) !== 0) {
        
        $menu_generated .= '<ul class="dropdown-menu">';
        
        foreach ($m['sub'] as $sub1) {
          
          $menu_generated .= '<li>';

          if ( count($sub1['sub']) !== 0 ) {

            $menu_generated .= '
              <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                ' . $sub1['name'] . '
                <b class="caret"></b></a>';

                $menu_generated .= '<ul class="dropdown-menu">';

                foreach ($sub1['sub'] as $sub2) { 

                  $menu_generated .= '<li>';
                  if ( count($sub2['sub']) !== 0 ) {

                    $menu_generated .= '
                      <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                        ' . $sub2['name'] . '
                        <b class="caret"></b></a>';

                    $menu_generated .= '<ul class="dropdown-menu">';

                    foreach ($sub2['sub'] as $sub3) { 

                      $menu_generated .= '<li>';
                      $menu_generated .= '<a href="' . $sub3['link'] . '"> ' . $sub3['name'] . ' </a>';
                      $menu_generated .= '</li>';
                    }

                    $menu_generated .= '</ul>';

                  } else {

                    $menu_generated .= '<a href="' . $sub2['link'] . '"> ' . $sub2['name'] . ' </a>';
                  
                  }
                  
                  $menu_generated .= '</li>';
                  
                }
                
                $menu_generated .= '</ul>';

          } else {
            
            $menu_generated .= '<a href="' . $sub1['link'] . '"> ' . $sub1['name'] . ' </a>';

          }
          
          $menu_generated .= '</li>';

        }
        
        $menu_generated .= '</ul>';
      
      } else {

        $menu_generated .= '
          <ul class="dropdown-menu">
            <li>
              <a href="javascript:void(0)">-- no menu --</a>
            </li>
          </ul>';
      } 

      $menu_generated .= '</li>';
    }

    return $menu_generated;
  }

  public function deleteMenu($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

    $delete = $this->menu->deleteMenu(
      Validate::clean($parsedBody['id'])
    );

    if ($delete['result'] === false) {
      return $response->withJson([
        'result' => false,
        'message' => $delete['message']
      ]);
    } else {
      return $response->withJson([
        'result' => true,
        'message' => $delete['message']
      ]);
    }
  }
}