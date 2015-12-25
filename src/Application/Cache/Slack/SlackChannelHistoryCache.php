<?php
namespace Jleagle\Steam\Application\Cache\Slack;

use Frlnc\Slack\Core\Commander;
use Frlnc\Slack\Http\CurlInteractor;
use Frlnc\Slack\Http\SlackResponseFactory;
use Jleagle\Steam\Application\Cache\AbstractCache;
use Jleagle\Steam\Application\Structs\SlackHistoryRowStruct;
use Packaged\Helpers\Arrays;

class SlackChannelHistoryCache extends AbstractCache
{
  protected $_token;
  protected $_channelId;

  public function __construct($token, $channelId)
  {
    $this->_token = $token;
    $this->_channelId = $channelId;
  }

  /**
   * @return string|null
   */
  protected function _getStruct()
  {
    return SlackHistoryRowStruct::class;
  }

  /**
   * @return string
   */
  protected function _getKey()
  {
    return 'slack-history-' . $this->_channelId;
  }

  /**
   * @return int
   */
  protected function _getSeconds()
  {
    return static::TIME_MINUTE * 10;
  }

  /**
   * @return mixed
   */
  protected function _getData()
  {
    $interactor = new CurlInteractor();
    $interactor->setResponseFactory(new SlackResponseFactory());

    $commander = new Commander($this->_token, $interactor);

    $x = $commander->execute(
      'channels.history',
      [
        'channel'   => $this->_channelId,
        'inclusive' => 1,
        'count'     => 100
      ]
    );

    return Arrays::value($x->getBody(), 'messages', []);
  }
}
