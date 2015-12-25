<?php
namespace Jleagle\Steam\Application\Views;

use Jleagle\HtmlBuilder\Tags\A;
use Jleagle\HtmlBuilder\Tags\Tables\Table;
use Jleagle\HtmlBuilder\Tags\Tables\Td;
use Jleagle\HtmlBuilder\Tags\Tables\Tr;
use Jleagle\Steam\Application\Models\App;
use Packaged\Helpers\Arrays;

class AppView extends AbstractView
{
  protected $_game;

  public function __construct(App $game)
  {
    $this->_game = $game;
  }

  /**
   * @return App
   */
  public function getApp()
  {
    return $this->_game;
  }

  public function getLinks()
  {
    $id = $this->getApp()->id;

    $links = [];

    $links['Steam Community'] = 'https://steamcommunity.com/app/' . $id;
    $links['Steam Store'] = 'http://store.steampowered.com/app/' . $id;
    $links['Official Website'] = $this->getApp()->website;

    $arr = $this->getApp()->support_info ?: '[]';
    $arr = json_decode($arr, true);
    $links['Support Site'] = Arrays::value($arr, 'url');

    $arr = $this->getApp()->metacritic ?: '[]';
    $arr = json_decode($arr, true);
    $links['Metacritic'] = Arrays::value($arr, 'url');

    $return = [];
    foreach($links as $k => $v)
    {
      if($v)
      {
        $return[] = new Tr(new Td(new A($v, $k)));
      }
    }

    return (new Table())->setContent($return)->addClass('table table-hover');
  }
}
