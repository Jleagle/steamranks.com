<?php
namespace Jleagle\Steam\Application\Controllers;

use Jleagle\Steam\Application\Cache\App\AppsCountCache;
use Jleagle\Steam\Application\Cache\App\AppsLatestCache;
use Jleagle\Steam\Application\Cache\App\AppsSearchCache;
use Jleagle\Steam\Application\Models\App;
use Jleagle\Steam\Application\Views\AppListView;
use Jleagle\Steam\Application\Views\AppNotFoundView;
use Jleagle\Steam\Application\Views\AppView;
use Jleagle\SteamClient\Api\SteamStore;
use Jleagle\SteamClient\Exceptions\SteamAppNotFoundException;
use Packaged\Helpers\Arrays;
use Packaged\Helpers\Objects;

class AppsController extends AbstractController
{
  public function getRoutes()
  {
    return [
      ''       => 'all',
      ':appId' => 'view'
    ];
  }

  public function all()
  {
    $minCharacters = 3;

    $search = $this->_getRequest()->query->get('search');

    if($search && strlen($search) >= $minCharacters)
    {
      $cache = new AppsSearchCache($search);
      $apps = $cache->retrieve();

      // Highlight
      $types = [];
      foreach($apps as $k => $app)
      {
        $apps[$k]->name = preg_replace(
          '/(' . preg_quote($search) . ')/i',
          '<b>$0</b>',
          $apps[$k]->name
        );
        $types[] = $app->type;
      }
    }
    else
    {
      $cache = new AppsLatestCache();
      $apps = $cache->retrieve();
      $search = null;
      $types = [];
    }

    $cache = new AppsCountCache();
    $count = $cache->retrieve();

    $this->layout()->setData('title', 'Apps');

    return new AppListView($apps, $count, $search, $minCharacters, $types);
  }

  public function view($appId)
  {
    $app = App::firstOrNew(['id' => $appId]);

    // Update database
    if(
      !$app->exists
      || $app->updated_at < date('Y-m-d G:i:s', strtotime('-7 days'))
      || $this->_getRequest()->query->has('u')
    )
    {
      $api = new SteamStore($this->_getApi());

      try
      {
        $response = $api->appDetails($appId);
      }
      catch(SteamAppNotFoundException $e)
      {
        return new AppNotFoundView();
      }

      $app->type = $response->type;
      $app->name = $response->name;
      $app->required_age = $response->required_age;
      $app->is_free = $response->is_free;
      $app->detailed_description = $response->detailed_description;
      $app->about_the_game = $response->about_the_game;
      $app->supported_languages = $response->supported_languages;
      $app->header_image = $this->removeQueryString($response->header_image);
      $app->website = $response->website;

      $app->release_date = json_encode(
        Objects::property($response, 'release_date', [])
      );
      $app->support_info = json_encode(
        Objects::property($response, 'support_info', [])
      );
      $app->metacritic = json_encode(
        Objects::property($response, 'metacritic', [])
      );

      $app->categories = json_encode(
        Objects::property($response, 'categories', [])
      );
      $app->genres = json_encode(
        Objects::property($response, 'genres', [])
      );
      $app->screenshots = json_encode(
        Objects::property($response, 'screenshots', [])
      );
      $app->achievements = json_encode(
        Objects::property($response, 'achievements', [])
      );
      $app->movies = json_encode(
        Objects::property($response, 'movies', [])
      );

      // Categories
      $ids = [];
      if(is_array($response->categories))
      {
        $ids = Arrays::ipull($response->categories, 'id');
      }
      $app->categories()->sync($ids);

      // Genres
      $ids = [];
      if(is_array($response->genres))
      {
        $ids = Arrays::ipull($response->genres, 'id');
      }
      $app->genres()->sync($ids);

      // Save
      $app->save();
    }

    $this->layout()->setData('title', $app->name);

    return new AppView($app);
  }
}
