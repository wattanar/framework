<?php

namespace Core;

class Helper
{
  public static function clean($value) {
    return htmlspecialchars(trim($value));
  }
}