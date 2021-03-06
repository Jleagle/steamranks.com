<?php
namespace Jleagle\Steam\Application\Controllers;

use Jleagle\Steam\Application\Views\HomeView;

class HomeController extends AbstractController
{
  public function getRoutes()
  {
    return [
      '' => 'home'
    ];
  }

  public function home()
  {
    $this->layout()->setData('title', 'Home');

    return new HomeView();
  }
}
