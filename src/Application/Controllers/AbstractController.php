<?php
namespace Jleagle\Steam\Application\Controllers;

use Cubex\View\LayoutController;
use google\appengine\api\taskqueue\PushQueue;
use google\appengine\api\taskqueue\PushTask;
use google\appengine\api\taskqueue\TaskAlreadyExistsException;
use Illuminate\Database\Capsule\Manager;
use Jleagle\Steam\Application\Cache\User\UsersCountCache;
use Jleagle\Steam\Application\Enums\SortFieldEnum;
use Jleagle\Steam\Application\Models\User;
use Jleagle\SteamClient\Api\SteamPlayerService;
use Jleagle\SteamClient\Api\SteamUser;
use Jleagle\SteamClient\Exceptions\SteamException;
use Jleagle\SteamClient\Responses\FriendResponse;

abstract class AbstractController extends LayoutController
{
  protected function _getApi()
  {
    return $this->getConfigItem('steam', 'api_key');
  }

  protected function _getUsersCount()
  {
    $cache = new UsersCountCache();
    return $cache->retrieve();
  }

  protected function removeQueryString($url)
  {
    return preg_replace('/\?.*/', '', $url);
  }

  protected function _updateUser(User $user, $scanFriends = false)
  {
    if($this->_isBot())
    {
      return $user;
    }

    $week = $user->updated_at
      < date('Y-m-d G:i:s', strtotime('-7 days'));
    $month = $user->updated_at_friends
      < date('Y-m-d G:i:s', strtotime('-28 days'));

    if($week || $month)
    {
      // Update ranks
      foreach(SortFieldEnum::all() as $item)
      {
        $r = 'rank_' . $item;
        $user->{$r} = Manager::table($r)
          ->select('id')
          ->where('user_id', '=', $user->id)
          ->pluck('user_id');
      }

      // Steam API stuff
      $playerService = new SteamPlayerService($this->_getApi());
      $userService = new SteamUser($this->_getApi());

      // User details
      $details = $userService->getPlayerSummary($user->id);

      $user->name = $details->personaname;
      $user->profile = $details->profileurl;
      $user->avatar_large = $details->avatarfull;
      $user->avatar_medium = $details->avatarmedium;
      $user->real_name = $details->realname;
      $user->time_on_steam = $details->timecreated;
      $user->country = $details->loccountrycode;

      // Games
      $games = $playerService->getOwnedGames($user->id);

      $json = [];
      foreach($games as $game)
      {
        $json[$game->appId] = $game->playtimeForever;
      }

      $user->games = count($games);
      $user->games_json = json_encode($json);
      $user->time = array_sum($json);

      // level
      $level = $playerService->getSteamLevel($user->id);
      $user->level = $level->level;

      // Badges
      $badges = $playerService->getBadges($user->id);

      $user->badges = count($badges->badges);
      $user->xp = $badges->playerXp;
      $user->xp_needed = $badges->playerXpNeededToLevelUp;
      $user->xp_current = $badges->playerXpNeededCurrentLevel;

      $xp = 0;
      foreach($badges->badges as $badge)
      {
        $xp += $badge->xp;
      }
      $user->xp_from_badges = $xp;

      // Friends
      try
      {
        $friends = $userService->getFriendList($user->id);
        $user->friends = count($friends);
        $user->friends_json = json_encode($friends);

        if($scanFriends && $month)
        {
          $this->_queueFriends($friends);
        }
      }
      catch(SteamException $e)
      {
      }
      $user->updated_at_friends = date('Y-m-d G:i:s');

      // Save
      $user->save();
    }

    return $user;
  }

  /**
   * @param FriendResponse[] $friends
   */
  protected function _queueFriends(array $friends)
  {
    $chunks = array_chunk($friends, 5);

    foreach($chunks as $friends)
    {
      $tasks = [];
      foreach($friends as $friend)
      {
        $tasks[] = new PushTask(
          '/cron/update-user',
          ['user' => $friend->steamId],
          ['name' => 'a' . $friend->steamId]
        );
      }
      try
      {
        $queue = new PushQueue('update-user');
        $queue->addTasks($tasks);
      }
      catch(TaskAlreadyExistsException $e)
      {
      }
    }
  }

  protected function _isBot()
  {
    $agent = $this->_getRequest()->userAgent();

    if($agent && preg_match('/bot|crawl|slurp|spider/i', $agent))
    {
      return true;
    }
    return false;
  }
}
