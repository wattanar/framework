<?php

namespace Core;

class Validate
{
  public static function clean($value) {
    if (!isset($value)) return null;
    if (trim($value) === '') return null;
    if ($value === null) return null;
    return htmlspecialchars(trim($value));
  }
}