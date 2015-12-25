<?php
namespace Jleagle\Steam\Application\Helpers;

use Packaged\Helpers\Arrays;

class Session
{
  const COOKIES = 'cookies';
  const USER_ID = 'user_id';

  public static function set($key, $value)
  {
    $_SESSION[$key] = $value;
  }

  public static function exists($key)
  {
    return isset($_SESSION[$key]);
  }

  public static function delete($key)
  {
    unset($_SESSION[$key]);
  }

  public static function get($key, $default = null)
  {
    return Arrays::value($_SESSION, $key, $default);
  }
}
