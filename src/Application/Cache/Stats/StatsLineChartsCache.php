<?php
namespace Jleagle\Steam\Application\Cache\Stats;

use Illuminate\Database\Capsule\Manager;
use Jleagle\Steam\Application\Cache\AbstractCache;
use Jleagle\Steam\Application\Structs\StatsLineChartRowStruct;

class StatsLineChartsCache extends AbstractCache
{
  protected $_column;

  public function __construct($column)
  {
    $this->_column = $column;
  }

  /**
   * @return string|null
   */
  protected function _getStruct()
  {
    return StatsLineChartRowStruct::class;
  }

  /**
   * @return string
   */
  protected function _getKey()
  {
    return 'stats-levels-' . $this->_column;
  }

  /**
   * @return int
   */
  protected function _getSeconds()
  {
    return static::TIME_WEEK;
  }

  /**
   * @return mixed
   */
  protected function _getData()
  {
    return Manager::table('users')
      ->select(
        Manager::raw(
          'count(' . $this->_column . ') as count, ' . $this->_column . ' as value'
        )
      )
      //->where($this->_column, '>', 0)
      ->orderBy($this->_column, 'asc')
      ->groupBy($this->_column);
  }
}
