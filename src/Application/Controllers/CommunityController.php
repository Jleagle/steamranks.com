<?php
namespace Jleagle\Steam\Application\Controllers;

use Frlnc\Slack\Core\Commander;
use Frlnc\Slack\Http\CurlInteractor;
use Frlnc\Slack\Http\SlackResponse;
use Frlnc\Slack\Http\SlackResponseFactory;
use Jleagle\Steam\Application\Cache\Slack\SlackChannelHistoryCache;
use Jleagle\Steam\Application\Cache\Slack\SlackChannelListCache;
use Jleagle\Steam\Application\Cache\Slack\SlackUserListCache;
use Jleagle\Steam\Application\Views\CommunityView;

class CommunityController extends AbstractController
{
  public function getRoutes()
  {
    return [
      '' => 'slack',
    ];
  }

  public function slack(SlackResponse $response = null)
  {
    $token = $this->getConfigItem('slack', 'token');

    $cache = new SlackUserListCache($token);
    $users = [];
    foreach($cache->retrieve() as $item)
    {
      $users[$item->id] = $item;
    }

    $cache = new SlackChannelListCache($token);
    $channels = $cache->retrieve();

    $history = [];
    foreach($channels as $channel)
    {
      $cache = new SlackChannelHistoryCache($token, $channel->id);
      $history[$channel->id] = $cache->retrieve();
    }

    $this->layout()->setData('title', 'Community');

    return new CommunityView($users, $channels, $history, $response);
  }

  public function postSlack()
  {
    $request = $this->_getRequest()->request;

    $interactor = new CurlInteractor();
    $interactor->setResponseFactory(new SlackResponseFactory());

    $commander = new Commander(
      $this->getConfigItem('slack', 'token'),
      $interactor
    );

    $response = $commander->execute(
      'users.admin.invite',
      [
        'first_name' => $request->get('first_name'),
        'last_name'  => $request->get('last_name'),
        'email'      => $request->get('email'),
        'set_active' => 'true',
        '_attempts'  => 1,
      ]
    );

    return $this->slack($response);
  }
}
