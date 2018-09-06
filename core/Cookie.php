<?php

namespace Core;

class Cookie
{
  public static function setCookie($key, $value, int $time = 0,  $path = "/") {
    setcookie($key, $value, $time, $path, null, null, true);
    return;
  }
}