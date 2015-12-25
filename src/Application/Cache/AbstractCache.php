<?php
namespace Jleagle\Steam\Application\Cache;

  // clear cache by wildcard
// http://stackoverflow.com/questions/1595904/memcache-and-wildcards

use Illuminate\Database\Query\Builder;

abstract class AbstractCache
{
  const TIME_MINUTE = 60;
  const TIME_HOUR = 3600;
  const TIME_DAY = 86400;
  const TIME_WEEK = 604800;
  const TIME_MONTH = 2419200; // 4 Weeks

  protected $_isSingleStruct = false;

  /**
   * @return string|null
   */
  abstract protected function _getStruct();

  /**
   * @return string
   */
  abstract protected function _getKey();

  /**
   * @return int
   */
  abstract protected function _getSeconds();

  /**
   * @return mixed
   */
  abstract protected function _getData();

  /**
   * @return mixed
   */
  public function retrieve()
  {
    $memcache = new \Memcached;
    $cached = $memcache->get($this->_getKey());

    if($cached)
    {
      return $cached;
    }
    else
    {
      $start = microtime(true);

      $data = $this->_getData();

      if($data instanceof Builder)
      {
        error_log($data->toSql());
        $data = $data->get();
      }

      $end = microtime(true) - $start;
      error_log($this->_getKey() . ' took ' . round($end, 3) . ' seconds');

      if($this->_getStruct())
      {
        $data = $this->_makeStruct($data);
      }

      if($data)
      {
        $memcache->set($this->_getKey(), $data, $this->_getSeconds());
      }

      return $data;
    }
  }

  /**
   * @param int $when
   *
   * @return $this
   */
  public function remove($when = 0)
  {
    $memcache = new \Memcached;
    $memcache->delete($this->_getKey(), $when);

    return $this;
  }

  /**
   * @param array $data
   *
   * @return array
   */
  protected function _makeStruct(array $data)
  {
    if($this->_isSingleStruct)
    {
      $data = call_user_func($this->_getStruct() . '::make', $data);
    }
    else
    {
      foreach($data as $k => $v)
      {
        $data[$k] = call_user_func($this->_getStruct() . '::make', $v);
      }
    }
    return $data;
  }
}
