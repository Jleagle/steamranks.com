<?php
namespace Jleagle\Steam\Application\Enums;

class AppTypeEnum extends AbstractEnum
{
  const VIDEO = 'video';
  const GAME = 'game';
  const DLC = 'dlc';

  public static function getDisplayValue($value)
  {
    switch($value)
    {
      case static::VIDEO:
        return 'Video';
      case static::GAME:
        return 'Game';
      case static::DLC:
        return 'Game DLC';
    }

    return parent::getDisplayValue($value);
  }
}
