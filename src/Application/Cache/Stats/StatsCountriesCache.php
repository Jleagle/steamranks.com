<?php
namespace Jleagle\Steam\Application\Cache\Stats;

use Illuminate\Database\Capsule\Manager;
use Jleagle\Steam\Application\Cache\AbstractCache;
use Jleagle\Steam\Application\Structs\StatsCountryRowStruct;

class StatsCountriesCache extends AbstractCache
{
  /**
   * @return string|null
   */
  protected function _getStruct()
  {
    return StatsCountryRowStruct::class;
  }

  /**
   * @return string
   */
  protected function _getKey()
  {
    return 'stats-countries';
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
      ->select(Manager::raw('count(country) as count, country'))
      ->where('country', '<>', '')
      ->groupBy('country');
  }
}
