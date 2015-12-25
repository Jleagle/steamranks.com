<?php
namespace Jleagle\Steam\Application\Controllers;

use Illuminate\Database\Capsule\Manager;
use Jleagle\Steam\Application\Cache\App\AppsLatestCache;
use Jleagle\Steam\Application\Models\User;
use Jleagle\SteamClient\Api\SteamApps;
use Packaged\Helpers\Objects;

class CronController extends AbstractController
{
  public function getRoutes()
  {
    return [
      'update-user'        => 'updateUser',
      'update-ranks/:sort' => 'updateRanks',
      'update-apps'        => 'updateApps',
    ];
  }

  public function updateUser()
  {
    $user = User::firstOrNew(
      [
        'id' => $this->_getRequest()->request->get('user')
      ]
    );

    $this->_updateUser($user, false);

    return 'Stub updated.';
  }

  public function updateRanks($sort)
  {
    $start = time();

    $table = 'rank_' . $sort;

    Manager::table($table)->truncate();
    Manager::statement(
      'INSERT INTO ' . $table . '  (user_id) SELECT id FROM users ORDER BY ' . $sort . ' DESC, id ASC LIMIT 100000;'
    );

    $end = time() - $start;

    error_log('CRON Update Ranks - ' . $end . ' seconds');

    exit;
  }

  public function updateApps()
  {
    $apps = new SteamApps($this->_getApi());
    $apps = $apps->getAppList();

    $new = Objects::ppull($apps, 'name', 'appid');

    $current = Manager::table('apps')->lists('id');

    $diff = array_diff(array_keys($new), $current);

    $apps = [];
    foreach($diff as $appId)
    {
      $apps[] = [
        'id'         => $appId,
        'name'       => $new[$appId],
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
      ];
    }

    Manager::table('apps')->insert($apps);

    // Clear cache
    $cache = new AppsLatestCache();
    $cache->remove();

    error_log('Apps updated');
    die('Apps updated');
  }
}
