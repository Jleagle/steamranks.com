<?php
namespace Jleagle\Steam\Application\Cache\Slack;

use Frlnc\Slack\Core\Commander;
use Frlnc\Slack\Http\CurlInteractor;
use Frlnc\Slack\Http\SlackResponseFactory;
use Jleagle\Steam\Application\Cache\AbstractCache;
use Jleagle\Steam\Application\Structs\SlackUserRowStruct;
use Packaged\Helpers\Arrays;

class SlackUserListCache extends AbstractCache
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
    return SlackUserRowStruct::class;
  }

  /**
   * @return string
   */
  protected function _getKey()
  {
    return 'slack-users';
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

    $x = $commander->execute(
      'users.list',
      [
        'presence' => 1
      ]
    );

    return Arrays::value($x->getBody(), 'members', []);
  }

  /**
   * @return SlackUserRowStruct[]
   */
  public function retrieve()
  {
    return parent::retrieve();
  }
}
