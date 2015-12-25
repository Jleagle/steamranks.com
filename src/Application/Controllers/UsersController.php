<?php
namespace Jleagle\Steam\Application\Controllers;

use Cubex\Responses\Error404Response;
use Jleagle\Steam\Application\Cache\User\UserListCache;
use Jleagle\Steam\Application\Cache\User\UsersCountCache;
use Jleagle\Steam\Application\Enums\SortFieldEnum;
use Jleagle\Steam\Application\Models\User;
use Jleagle\Steam\Application\Views\UserAjaxView;
use Jleagle\Steam\Application\Views\UserListView;
use Jleagle\Steam\Application\Views\UserNotFound;
use Jleagle\Steam\Application\Views\UserView;
use Jleagle\SteamClient\Api\SteamUser;
use Jleagle\SteamClient\Exceptions\SteamUserNotFoundException;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UsersController extends AbstractController
{
  public function getRoutes()
  {
    return [
      'ajax'   => 'ajax',
      'id'     => 'id',
      ':x@num' => 'view',
      ':x/:y'  => 'users',
      ':x'     => 'users',
      ''       => 'users',
    ];
  }

  public function users($order = 'level', $page = 1)
  {
    $cache = new UsersCountCache();
    $count = $cache->retrieve();

    switch($order)
    {
      case 'games':
      case 'badges':
      case 'friends':
      case 'time':
      case 'level':
        return new UserListView($count, $page, $order);
      default:
        return new Error404Response();
    }
  }

  public function ajax()
  {
    $this->disableLayout();

    $query = $this->_getRequest()->query;

    $sort = $query->get('s', 'level');
    $page = $query->get('p', 1);

    switch($sort)
    {
      case SortFieldEnum::GAMES:
      case SortFieldEnum::BADGES:
      case SortFieldEnum::FRIENDS:
      case SortFieldEnum::TIME:
      case SortFieldEnum::LEVEL:
        $col = $sort;
        break;
      default:
        return new Error404Response();
    }

    $cache = new UserListCache($col, $page);
    $users = $cache->retrieve();

    return new UserAjaxView($users, $col, $page);
  }

  public function view($playerId)
  {
    $user = User::firstOrNew(['id' => $playerId]);

    $this->_updateUser($user, true);

    // Get total users
    $cache = new UsersCountCache();
    $users = $cache->retrieve();

    return new UserView($user, $users);
  }

  public function id()
  {
    $search = $this->_getRequest()->request->get('search');

    if(!$search)
    {
      return new UserNotFound($search);
    }

    // Steam ID
    if(preg_match('/^STEAM_/', $search))
    {
      $parts = explode(':', $search);
      $id = bcadd(
        bcadd(bcmul($parts[2], '2'), '76561197960265728'),
        $parts[1]
      );
    }
    // User ID
    elseif(is_numeric($search) && strlen($search) == 8)
    {
      $id = bcadd($search, '76561197960265728');
    }
    // Community ID
    elseif(is_numeric($search) && strlen($search) == 17)
    {
      $id = $search;
    }
    // Vanity URL
    else
    {
      $steam = new SteamUser($this->_getApi());
      try
      {
        $id = $steam->resolveVanityUrl(basename($search))->steamId;
      }
      catch(SteamUserNotFoundException $e)
      {
        return new UserNotFound($search);
      }
    }

    return RedirectResponse::create('/users/' . $id);
  }
}
