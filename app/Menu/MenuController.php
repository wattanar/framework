<?php

namespace App\Menu;

use Core\Render;
use Core\Helper;
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
    return $response->withJson(["data" => $this->menu->getMenu()]);
  }

  public function createMenu($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

    $create = $this->menu->createMenu(
      Helper::clean($parsedBody['menu_link']),
      Helper::clean($parsedBody['menu_name'])
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
      Helper::clean($parsedBody['id']),
      Helper::clean($parsedBody['menu_link']),
      Helper::clean($parsedBody['menu_name']),
      Helper::clean($parsedBody['menu_position']),
      Helper::clean($parsedBody['menu_parent']),
      Helper::clean($parsedBody['menu_order']),
      Helper::clean($parsedBody['menu_status'])
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

  public function generateMenuHTML($head = ""): string {
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

    $menu_generated .= '<ul class="sidebar-menu" data-widget="tree">';

    if ($head !== "") {
      $menu_generated .= '<li class="header">' . xss($head) . '</li>';
    }

    foreach ($menu as $v) {
      if ( count($v['sub']) === 0 ) {
        $menu_generated .= '
          <li>
            <a href="' . $v['link'] . '">
              <i class="fa fa-circle-o"></i> 
              <span>' . $v['name'] . '</span>
            </a>
          </li>';
      } else {
        $menu_generated .= '
          <li class="treeview">
            <a href="#">
              <i class="fa fa-circle-o"></i> 
              <span> ' . $v['name'] . ' </span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
            </a>';
        $menu_generated .= '<ul class="treeview-menu">';
        foreach ($v['sub'] as $v2) {
          if ( count($v2['sub']) === 0 ) {
            $menu_generated .= '
              <li>
                <a href="' . $v2['link'] . '">
                  <span>' . $v2['name'] . '</span>
                </a>
              </li>';
          } else {
            $menu_generated .= '
              <li class="treeview">
                <a href="#">
                  <span> ' . $v2['name'] . ' </span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>';
            $menu_generated .= '<ul class="treeview-menu">';
            foreach ($v2['sub'] as $v3) {
              $menu_generated .= '
                <li>
                  <a href="' . $v3['link'] . '">
                    <span>' . $v3['name'] . '</span>
                  </a>
                </li>';
            }
            $menu_generated .= '</ul></li>';
          }
        }
        $menu_generated .= '</ul></li>';
      }
    }
    $menu_generated .= '</ul>';

    return $menu_generated;
  }

  public function generateMenu() {
    
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
      <li class="treeview">
        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
          <span>' . $m['name'] . '</span>
        </a>';

      if ( count($m['sub']) !== 0 ) {
        
        $menu_generated .= '<ul class="treeview-menu">';
        
        foreach ($m['sub'] as $sub1) {
          
          $menu_generated .= '<li>';

          if ( count($sub1['sub']) !== 0 ) {

            $menu_generated .= '
              <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                ' . $sub1['name'] . '
                <b class="caret"></b></a>';

                $menu_generated .= '<ul class="treeview-menu">';

                foreach ($sub1['sub'] as $sub2) { 

                  $menu_generated .= '<li>';

                  if ( count($sub2['sub']) !== 0 ) {

                    $menu_generated .= '
                      <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                        ' . $sub2['name'] . '
                        <b class="caret"></b></a>';

                    $menu_generated .= '<ul class="treeview-menu">';

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
          <ul class="treeview-menu">
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
      Helper::clean($parsedBody['id'])
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

  public function updateCapabilities($request, $response, $args) {
    
    $parsedBody = $request->getParsedBody();
    if ( Helper::clean($parsedBody['cap_id']) === null ) {
      return $response->withJson([
        'result' => false,
        'message' => 'Please select capabilities!'
      ]);
    }
    $update = $this->menu->updateCapabilities(
      Helper::clean($parsedBody['menu_id']),
      Helper::clean($parsedBody['cap_id'])
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
}