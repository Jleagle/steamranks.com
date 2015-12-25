<?php
namespace Jleagle\Steam\Application\Controllers;

use Jleagle\Steam\Application\Helpers\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginController extends AbstractController
{
  public function getRoutes()
  {
    return [
      '' => 'login'
    ];
  }

  public function login()
  {
    $openId = new \LightOpenID($this->_getRequest()->getUri());

    if(!$openId->mode)
    {
      $openId->identity = 'https://steamcommunity.com/openid';
      return RedirectResponse::create($openId->authUrl());
    }
    else
    {
      if($openId->validate())
      {
        $id = basename($openId->identity);

        Session::set(Session::USER_ID, $id);

        return RedirectResponse::create('/users/' . $id);
      }
      return 'error';
    }
  }
}
