<?php

namespace Core;

use \Slim\Csrf\Guard;

class CSRF 
{
  public static function generate() {
    $csrf = new Guard;
    $csrf->validateStorage();

    $name = $csrf->getTokenNameKey();
    $value = $csrf->getTokenValueKey();
    $pair = $csrf->generateToken();

    return [
    	'name' => $name,
    	'value' => $value,
    	'key' => $pair
    ];
  }
}