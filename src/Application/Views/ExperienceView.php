<?php
namespace Jleagle\Steam\Application\Views;

use Jleagle\Steam\Application\Structs\LevelRowStruct;

class ExperienceView extends AbstractView
{
  protected $_level;

  /**
   * @param int $level
   */
  function __construct($level)
  {
    $this->_level = $level;
  }

  /**
   * @return int
   */
  public function getLevel()
  {
    return $this->_level;
  }

  /**
   * @return LevelRowStruct[]
   */
  public function getTableData()
  {
    // Get XP
    $xp = 100;
    $rows = [];

    $rows[] = LevelRowStruct::make(
      [
        'level' => 0,
        'start' => 0,
      ]
    );

    foreach(range(1, 1001) as $level)
    {
      $diff = ceil(($level + 1) / 10);
      $diff = $diff * 100;

      $rows[] = LevelRowStruct::make(
        [
          'level' => $level,
          'start' => $xp,
        ]
      );

      $xp = $xp + $diff;
    }

    // Get extra columns
    /** @var LevelRowStruct[] $rows */
    /** @var LevelRowStruct $nextRow */
    /** @var LevelRowStruct $thisRow */
    foreach($rows as $k => $row)
    {
      if(isset($rows[$k + 1]))
      {
        $nextRow = $rows[$k + 1];
        $thisRow = $rows[$k];

        $rows[$k]->diff = $nextRow->start - $thisRow->start;
        $rows[$k]->end = $nextRow->start - 1;
      }
    }

    $slice = array_slice($rows, 0, 1000);
    return array_chunk($slice, 10);
  }

  public function getRowClass(LevelRowStruct $row)
  {

    if($row->level == $this->getLevel())
    {
      if($row->level > 0)
      {
        return 'success scrollTo';
      }
    }

    return '';
  }
}
