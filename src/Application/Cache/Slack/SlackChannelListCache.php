<?php
namespace Jleagle\Steam\Application\Cache\Slack;

use Frlnc\Slack\Core\Commander;
use Frlnc\Slack\Http\CurlInteractor;
use Frlnc\Slack\Http\SlackResponseFactory;
use Jleagle\Steam\Application\Cache\AbstractCache;
use Jleagle\Steam\Application\Structs\SlackChannelRowStruct;
use Packaged\Helpers\Arrays;

class SlackChannelListCache extends AbstractCache
{
  protected $_token;

  public function __construct($token)
  {
    $this->_token = $token;
  }

  /**
   * @return string|null
   */
  protected function _getStruct()
  {
    return SlackChannelRowStruct::class;
  }

  /**
   * @return string
   */
  protected function _getKey()
  {
    return 'slack-channels';
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
    $interactor = new CurlInteractor();
    $interactor->setResponseFactory(new SlackResponseFactory());

    $commander = new Commander($this->_token, $interactor);

    $x = $commander->execute('channels.list');

    return Arrays::value($x->getBody(), 'channels', []);
  }

  /**
   * @return SlackChannelRowStruct[]
   */
  public function retrieve()
  {
    return parent::retrieve();
  }
}
