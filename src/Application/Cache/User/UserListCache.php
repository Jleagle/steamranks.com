<?php
namespace Jleagle\Steam\Application\Cache\User;

use Illuminate\Database\Capsule\Manager;
use Jleagle\Steam\Application\Cache\AbstractCache;
use Jleagle\Steam\Application\Structs\UserRowStruct;

class UserListCache extends AbstractCache
{
  protected $_sort;
  protected $_page;

  public function __construct($sort, $page = 1)
  {
    $this->_sort = $sort;
    $this->_page = $page;
  }

  /**
   * @return string|null
   */
  protected function _getStruct()
  {
    return UserRowStruct::class;
  }

  /**
   * @return string
   */
  protected function _getKey()
  {
    return 'list-users-' . $this->_sort . '-' . $this->_page;
  }

  /**
   * @return int
   */
  protected function _getSeconds()
  {
    return static::TIME_DAY;
  }

  /**
   * @return mixed
   */
  protected function _getData()
  {
    $perPage = 50;

    $users = Manager
      ::table('rank_' . $this->_sort)
      ->skip($perPage * ($this->_page - 1))
      ->take($perPage)
      ->orderBy('id', 'asc')
      ->lists('user_id');

    return Manager::table('users')
      ->select(
        'id',
        'name',
        'level',
        'games',
        'badges',
        'time',
        'friends',
        'avatar_medium',
        'avatar_large',
        'country',
        $this->_sort
      )
      ->orderBy($this->_sort, 'desc')
      ->orderBy('id', 'asc')
      ->whereIn('id', $users);
  }

  /**
   * @return UserRowStruct[]
   */
  public function retrieve()
  {
    return parent::retrieve();
  }
}
