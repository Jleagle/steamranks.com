<?php
namespace Jleagle\Steam\Application\Structs;

class UserRowStruct extends AbstractStruct
{
  public $id;
  public $name;
  public $level;
  public $games;
  public $badges;
  public $time;
  public $friends;
  public $avatar_medium;
  public $avatar_large;
  public $country;

  // Ranks
  public $rank_level;
  public $rank_games;
  public $rank_badges;
  public $rank_time;
  public $rank_friends;
}
