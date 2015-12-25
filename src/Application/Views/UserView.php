<?php
namespace Jleagle\Steam\Application\Views;

use Jleagle\Steam\Application\Models\User;

class UserView extends AbstractView
{
  protected $_user;
  protected $_users;

  public function __construct(User $user, $users)
  {
    $user = $user->toArray();

    $user['games_json'] = json_decode($user['games_json'], true);
    //$user['games_json'] = array_filter($user['games_json']);
    if($user['games_json'])
    {
      arsort($user['games_json']);
    }
    else
    {
      $user['games_json'] = [];
    }

    // todo, handle when games_json is empty

    $user['friends_json'] = json_decode($user['friends_json'], true);

    $this->_user = $user;
    $this->_users = $users;
  }

  /**
   * @return array
   */
  public function getUser()
  {
    return $this->_user;
  }

  /**
   * @return int
   */
  public function getUsers()
  {
    return $this->_users;
  }

  public function getRankLine($column)
  {
    $rank = $this->getUser()[$column];

    $percent = ($rank / $this->getUsers()) * 100;

    return $this->getOrdinal($rank) . ' - Top ' . round($percent) . '%';
  }
}
