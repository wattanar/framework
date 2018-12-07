<?php

function xss($string = '') {
  return htmlspecialchars($string);
}

function userCan($cap_slug) {
  $userApi = new \App\User\UserAPI;
  return $userApi->userCan($cap_slug);
};

function getUserData() {
  $auth = new \App\Auth\AuthAPI;
  return $auth->verifyToken();
}

function getSidebarMenu($head = "") {
  $menu = new \App\Menu\MenuController; 
  return $menu->generateMenuHTML($head);
}