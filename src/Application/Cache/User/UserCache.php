<?php
namespace Jleagle\Steam\Application\Cache\User;

use Illuminate\Database\Capsule\Manager;
use Jleagle\Steam\Application\Cache\AbstractCache;
use Jleagle\Steam\Application\Structs\UserStruct;

class UserCache extends AbstractCache
{
  protected $_userId;
  protected $_isSingleStruct = true;

  public function __construct($userId)
  {
    $this->_userId = $userId;
  }

  /**
   * @return string|null
   */
  protected function _getStruct()
  {
    return UserStruct::class;
  }

  /**
   * @return string
   */
  protected function _getKey()
  {
    return 'user-' . $this->_userId;
  }

  /**
   * @return int
   */
  protected function _getSeconds()
  {
    return static::TIME_DAY; // Keep as day so the ranks update
  }

  /**
   * @return array
   */
  protected function _getData()
  {
    return Manager::table('users')
      ->where('id', '=', $this->_userId)
      ->first();
  }
}
