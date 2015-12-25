<?php
namespace Jleagle\Steam\Application\Controllers;

use Jleagle\Steam\Application\Views\DonateView;

class DonateController extends AbstractController
{
  public function getRoutes()
  {
    return [
      '' => 'donate'
    ];
  }

  public function donate()
  {
    return new DonateView();
  }
}
