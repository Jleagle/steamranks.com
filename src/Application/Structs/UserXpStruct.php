<?php
namespace Jleagle\Steam\Application\Structs;

class UserXpStruct extends AbstractStruct
{
  public $currentXp = 0;

  public $toNextLevel = 100;
  public $fromLastLevel = 0;
  public $fromLastLevelPercent = 0;

  public $startOfLevel = 0;
  public $endOfLevel = 99;

  public $levelRange;

  /**
   * @param array $user
   *
   * @return UserXpStruct
   */
  public static function fromModel(array $user)
  {
    $struct = new self();

    if($user['xp'])
    {
      $struct->currentXp = $user['xp'];
      $struct->toNextLevel = $user['xp_needed'];
      $struct->startOfLevel = $user['xp_current'];
      $struct->endOfLevel = $struct->currentXp + $struct->toNextLevel;
      $struct->levelRange = $struct->endOfLevel - $struct->startOfLevel;
      $struct->fromLastLevel = $struct->levelRange - $struct->toNextLevel;
      $struct->fromLastLevelPercent = ($struct->fromLastLevel / $struct->levelRange) * 100;
    }

    return $struct;
  }
}
