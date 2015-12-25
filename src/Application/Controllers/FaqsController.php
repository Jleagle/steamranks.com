<?php
namespace Jleagle\Steam\Application\Controllers;

use Jleagle\Steam\Application\Views\FaqsView;

class FaqsController extends AbstractController
{
  public function getRoutes()
  {
    return [
      '' => 'faqs',
    ];
  }

  public function faqs()
  {
    return new FaqsView();
  }
}
