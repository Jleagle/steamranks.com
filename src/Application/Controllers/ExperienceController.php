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
    return new ExperienceView($level);
  }
}
