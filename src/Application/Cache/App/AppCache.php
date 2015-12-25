<?php
namespace Jleagle\Steam\Application\Cache\App;

use Illuminate\Database\Capsule\Manager;
use Jleagle\Steam\Application\Cache\AbstractCache;
use Jleagle\Steam\Application\Structs\AppRowStruct;

class AppCache extends AbstractCache
{
  protected $_appId;
  protected $_isSingleStruct = true;

  public function __construct($appId)
  {
    $this->_appId = $appId;
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
    return 'app-' . $this->_appId;
  }

  /**
   * @return int
   */
  protected function _getSeconds()
  {
    return static::TIME_WEEK;
  }

  /**
   * @return array
   */
  protected function _getData()
  {
    return Manager::table('apps')
      ->where('id', '=', $this->_appId)
      ->first();
  }
}
