<?php
namespace Jleagle\Steam\Application\Controllers;

use Jleagle\Steam\Application\Cache\User\UserListCache;
use Jleagle\Steam\Application\Enums\SortFieldEnum;
use Jleagle\Steam\Application\Views\ApiView;

class ApiController extends AbstractController
{
  public function getRoutes()
  {
    return [
      'level'   => 'level',
      'games'   => 'games',
      'badges'  => 'badges',
      'time'    => 'timex',
      'friends' => 'friends',
      ''        => 'api',
    ];
  }

  public function api()
  {
    // Title
    $this->layout()->setData('title', 'API');

    return new ApiView($this->_getRequest()->path());
  }

  public function level()
  {
    $cache = new UserListCache(SortFieldEnum::LEVEL);
    return $cache->retrieve();
  }

  public function games()
  {
    $cache = new UserListCache(SortFieldEnum::GAMES);
    return $cache->retrieve();
  }

  public function badges()
  {
    $cache = new UserListCache(SortFieldEnum::BADGES);
    return $cache->retrieve();
  }

  public function timex()
  {
    $cache = new UserListCache(SortFieldEnum::TIME);
    return $cache->retrieve();
  }

  public function friends()
  {
    $cache = new UserListCache(SortFieldEnum::FRIENDS);
    return $cache->retrieve();
  }
}
