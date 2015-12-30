<?php
namespace Jleagle\Steam\Application\Controllers;

use Jleagle\Steam\Application\Views\ContactView;

class ContactController extends AbstractController
{
  public function getRoutes()
  {
    return [
      '' => 'contact'
    ];
  }

  public function contact()
  {
    $this->layout()->setData('title', 'Contact');

    return new ContactView();
  }
}
