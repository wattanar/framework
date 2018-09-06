<?php

namespace Core;

class Response
{
  public static function result($result, $message, $extra = []) {
    return [
      'result' => $result,
      'message' => $message,
      'extra' => $extra
    ];
  }
}