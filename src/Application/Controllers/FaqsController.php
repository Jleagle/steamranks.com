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
    $this->layout()->setData('title', 'FAQs');

    return new FaqsView();
  }
}
