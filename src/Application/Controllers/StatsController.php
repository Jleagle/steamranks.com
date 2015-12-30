<?php
namespace Jleagle\Steam\Application\Controllers;

use Jleagle\Steam\Application\Cache\Stats\StatsCountriesCache;
use Jleagle\Steam\Application\Cache\Stats\StatsLineChartsCache;
use Jleagle\Steam\Application\Enums\SortFieldEnum;
use Jleagle\Steam\Application\Enums\UserColsEnum;
use Jleagle\Steam\Application\Views\StatsView;

class StatsController extends AbstractController
{
  public function getRoutes()
  {
    return [
      '' => 'stats',
    ];
  }

  public function stats()
  {
    $this->layout()->setData('title', 'Stats');

    $cache = new StatsCountriesCache();
    $countries = $cache->retrieve();

    $cache = new StatsLineChartsCache(SortFieldEnum::LEVEL);
    $chart = $cache->retrieve();

    return new StatsView($countries, $chart);
  }
}
