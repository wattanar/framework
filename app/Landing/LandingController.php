<?php

namespace App\Landing;

use Core\Render;
use App\User\UserAPI;

class LandingController
{
  public function __construct() {
    $this->user = new UserAPI;
  }

  public function demo($request, $response, $args) {
    return Render::View('pages/demo/demo');
  }
}