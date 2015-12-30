<?php
namespace Jleagle\Steam\Application\Controllers;

use Jleagle\Steam\Application\Views\ExperienceView;

class ExperienceController extends AbstractController
{
  public function getRoutes()
  {
    return [
      ''       => 'levels',
      ':level' => 'levels',
    ];
  }

  public function levels($level = null)
  {
    $this->layout()->setData('title', 'Experience Chart (XP)');

    return new ExperienceView($level);
  }
}
