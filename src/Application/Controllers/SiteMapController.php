<?php
namespace Jleagle\Steam\Application\Controllers;

use Cubex\Kernel\ControllerKernel;
use Jleagle\Steam\Application\Enums\SortFieldEnum;
use Jleagle\Steam\Application\Models\User;

class SiteMapController extends ControllerKernel
{
  public function getRoutes()
  {
    return [
      '' => 'sitemap',
    ];
  }

  public function sitemap()
  {
    // todo, cache this!
    $path = $this->_getRequest()->urlSprintf('%p%d.%t') . '/users/';

    $return = [];

    foreach(SortFieldEnum::all() as $item)
    {
      $x = User
        ::orderBy($item, 'desc')
        ->select('id')
        ->limit(1000)
        ->get();

      foreach($x as $user)
      {
        $return[] = $path . $user->id;
      }
    }

    return implode("\n\r", $return);
  }
}
