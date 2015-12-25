<?php
namespace Jleagle\Steam\Application\Views;

use Frlnc\Slack\Http\SlackResponse;
use Jleagle\Steam\Application\Structs\SlackChannelRowStruct;
use Packaged\Helpers\Arrays;

class CommunityView extends AbstractView
{
  protected $_users;
  protected $_channels;
  protected $_history;
  protected $_response;

  public function __construct(
    array $users,
    array $channels,
    array $history,
    SlackResponse $response = null
  )
  {
    $this->_users = $users;
    $this->_channels = $channels;
    $this->_history = $history;
    $this->_response = $response;
  }

  public function getUsers()
  {
    return $this->_users;
  }

  /**
   * @return SlackChannelRowStruct[]
   */
  public function getChannel()
  {
    return $this->_channels;
  }

  public function getHistory()
  {
    return $this->_history;
  }

  /**
   * @return array
   */
  public function getResponse()
  {
    return $this->_response ? $this->_response->getBody() : [];
  }

  public function isSuccess()
  {
    $success = Arrays::value($this->getResponse(), 'ok', false);

    if($success)
    {
      setcookie('panel-community', 0, strtotime('+28 days'), '/community');
    }

    return $success;
  }

  public function getClass()
  {
    return $this->isSuccess() ? 'success' : 'danger';
  }

  public function getMessage()
  {
    if($this->isSuccess())
    {
      return 'Invitation sent';
    }
    else
    {
      $error = Arrays::value($this->getResponse(), 'error', 'Error');
      return $this->_decodeErrorMessage($error);
    }
  }

  protected function _decodeErrorMessage($error)
  {
    switch($error)
    {
      case 'invalid_email':
        return 'Invalid email address';
      case 'already_in_team':
        return 'User has already accepted an invitation';
      case 'already_invited':
        return 'User has already been sent an invitation';
      default:
        return $error;
    }
  }
}
