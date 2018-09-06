<?php

namespace Core;

class Flash
{
  public static function addMessage($status, $message) {
    
    $_SESSION['flash_status'] = '';
    $_SESSION['flash_message'] = '';
    $_SESSION['flash_status'] = htmlspecialchars($status);
    $_SESSION['flash_message'] = htmlspecialchars($message);

    return;
  }

  public static function getMessage($type = '') {

    if ($type === 'status') {
      if ( isset($_SESSION['flash_status'])) {
        $_status = $_SESSION['flash_status'];
        $_SESSION['flash_status'] = '';
        return $_status;
      } else {
        return '';
      }
    } else if ($type === 'message') {
      if ( isset($_SESSION['flash_message'])) {
        $_message = $_SESSION['flash_message'];
        $_SESSION['flash_message'] = '';
        return $_message;
      } else {
        return '';
      }
    } else {
      return '';
    }
  }
}