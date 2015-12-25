<?php
namespace Jleagle\Steam\Application\Cache\App;

use Illuminate\Database\Capsule\Manager;
use Jleagle\Steam\Application\Cache\AbstractCache;
use Jleagle\Steam\Application\Structs\AppRowStruct;

class AppsLatestCache extends AbstractCache
{
  /**
   * @return string
   */
  protected function _getStruct()
  {
    return AppRowStruct::class;
  }

  /**
   * @return string
   */
  protected function _getKey()
  {
    return 'apps-latest';
  }

  /**
   * @return int
   */
  protected function _getSeconds()
  {
    return static::TIME_WEEK; // Gets cleared by the cron nightly anyway..
  }

  /**
   * @return array
   */
  protected function _getData()
  {
    return Manager::table('apps')
      ->orderBy('id', 'desc')
      ->limit(50);
  }
}
