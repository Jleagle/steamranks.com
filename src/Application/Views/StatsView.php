<?php
namespace Jleagle\Steam\Application\Views;

use Jleagle\Steam\Application\Structs\StatsCountryRowStruct;
use Jleagle\Steam\Application\Structs\StatsLineChartRowStruct;

class StatsView extends AbstractView
{
  protected $_countries;
  protected $_levels;

  /**
   * @param StatsCountryRowStruct[]   $countries
   * @param StatsLineChartRowStruct[] $levels
   */
  function __construct(array $countries, array $levels)
  {
    $this->_countries = $countries;
    $this->_levels = $levels;
  }

  public function getCountryData()
  {
    $data = [];
    $data[] = ['Country', 'Count'];
    foreach($this->_countries as $level)
    {
      $data[] = [$level->country, $level->count];
    }
    return json_encode($data);
  }

  public function getLevelsData()
  {
    $data = [];
    foreach($this->_levels as $item)
    {
      $data[] = [$item->value, $item->count];
    }
    return json_encode($data);
  }
}
