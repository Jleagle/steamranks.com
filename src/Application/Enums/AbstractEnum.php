<?php
namespace Jleagle\Steam\Application\Enums;

abstract class AbstractEnum
{
  public static function getValues()
  {
    $oClass = new \ReflectionClass(get_called_class());
    return array_values($oClass->getConstants());
  }

  public static function getKeyedValues()
  {
    $return = [];
    foreach(static::getValues() as $value)
    {
      $return[$value] = static::getDisplayValue($value);
    }
    return $return;
  }

  public static function isValid($value)
  {
    return in_array($value, static::getValues());
  }

  public static function getDisplayValue($value)
  {
    return ucwords($value);
  }
}
