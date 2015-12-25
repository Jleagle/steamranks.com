<?php
namespace Jleagle\Steam\Application\Cache\App;

use Illuminate\Database\Capsule\Manager;
use Jleagle\Steam\Application\Cache\AbstractCache;

class AppsCountCache extends AbstractCache
{
  /**
   * @return null
   */
  protected function _getStruct()
  {
    return null;
  }

  /**
   * @return string
   */
  protected function _getKey()
  {
    return 'apps-count';
  }

  /**
   * @return int
   */
  protected function _getSeconds()
  {
    return static::TIME_DAY;
  }

  /**
   * @return int
   */
  protected function _getData()
  {
    return $users = Manager::table('apps')->count();
  }
}
