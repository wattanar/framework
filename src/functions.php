<?php

function userCan($cap_slug) {
  $userApi = new App\User\UserAPI;
  return $userApi->userCan($cap_slug);
};