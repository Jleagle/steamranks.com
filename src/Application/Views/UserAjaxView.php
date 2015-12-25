<?php
namespace Jleagle\Steam\Application\Views;

use Jleagle\HtmlBuilder\Tags\Img;
use Jleagle\Steam\Application\Structs\UserRowStruct;

class UserAjaxView extends AbstractView
{
  protected $_users;
  protected $_col;
  protected $_page;

  public function __construct($users, $col, $page)
  {
    $this->_users = $users;
    $this->_col = $col;
    $this->_page = $page;
  }

  /**
   * @return UserRowStruct[]
   */
  public function getUsers()
  {
    return $this->_users;
  }

  /**
   * @return int
   */
  public function getPage()
  {
    return $this->_page;
  }

  public function getCol(UserRowStruct $user)
  {
    return $user->{$this->_col};
  }

  public function getAvatar(UserRowStruct $user)
  {
    if($user->avatar_medium)
    {
      return new Img($user->avatar_medium);
    }
    elseif($user->avatar_large)
    {
      return new Img($user->avatar_large);
    }
    return null;
  }
}
