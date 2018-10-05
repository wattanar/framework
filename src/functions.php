<?php

use App\Auth\AuthAPI;

function userCan($cap_slug) {
  $token = (new AuthAPI)->verifyToken();
}