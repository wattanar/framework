<?php

namespace Core;

class Validate
{
  public static function clean($value) {
    return htmlspecialchars(trim($value));
  }
}