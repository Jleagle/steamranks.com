<?php
namespace Jleagle\Steam\Application;

use Cubex\Kernel\ApplicationKernel;
use Jleagle\Steam\Application\Controllers\ApiController;
use Jleagle\Steam\Application\Controllers\AppsController;
use Jleagle\Steam\Application\Controllers\CommunityController;
use Jleagle\Steam\Application\Controllers\CronController;
use Jleagle\Steam\Application\Controllers\FaqsController;
use Jleagle\Steam\Application\Controllers\ExperienceController;
use Jleagle\Steam\Application\Controllers\LoginController;
use Jleagle\Steam\Application\Controllers\StatsController;
use Jleagle\Steam\Application\Controllers\UsersController;

class Application extends ApplicationKernel
{
  public function getRoutes()
  {
    return [
      ''           => '/users',
      'api'        => ApiController::class,
      'apps'       => AppsController::class,
      'community'  => CommunityController::class,
      'cron'       => CronController::class,
      'experience' => ExperienceController::class,
      'faqs'       => FaqsController::class,
      'login'      => LoginController::class,
      'users'      => UsersController::class,
      'stats'      => StatsController::class,
    ];
  }
}
