<?php
namespace Jleagle\Steam\Application\Enums;

class SortFieldEnum
{
  const LEVEL = 'level';
  const GAMES = 'games';
  const BADGES = 'badges';
  const TIME = 'time';
  const FRIENDS = 'friends';

  public static function all()
  {
    return [
      self::LEVEL,
      self::GAMES,
      self::BADGES,
      self::FRIENDS,
      self::TIME,
    ];
  }
}
