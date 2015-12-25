<?php
namespace Jleagle\Steam\Application\Cache\App;

use Illuminate\Database\Capsule\Manager;
use Jleagle\Steam\Application\Cache\AbstractCache;
use Jleagle\Steam\Application\Structs\AppRowStruct;

class AppsSearchCache extends AbstractCache
{
  protected $_search;

  public function __construct($search)
  {
    $this->_search = $search;
  }

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
    return 'apps-search-' . md5($this->_search);
  }

  /**
   * @return int
   */
  protected function _getSeconds()
  {
    return static::TIME_DAY;
  }

  /**
   * @return array
   */
  protected function _getData()
  {
    return Manager::table('apps')
      ->select('id', 'type', 'name', 'created_at')
      ->orderBy('id', 'desc')
      ->where('name', 'LIKE', '%' . $this->_search . '%');
  }

  /**
   * @return AppRowStruct[]
   */
  public function retrieve()
  {
    return parent::retrieve();
  }
}
