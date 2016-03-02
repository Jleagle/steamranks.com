<?php
namespace Jleagle\Steam\Application\Views;

use Jleagle\HtmlBuilder\Tags\A;
use Jleagle\HtmlBuilder\Tags\Div;
use Jleagle\HtmlBuilder\Tags\Tables\Td;
use Jleagle\HtmlBuilder\Tags\Tables\Th;
use Jleagle\HtmlBuilder\Tags\Tables\Tr;
use Jleagle\Steam\Application\Enums\SortFieldEnum;
use Jleagle\Steam\Application\Models\User;
use Jleagle\Steam\Application\Structs\UserXpStruct;
use Packaged\Helpers\Arrays;

class UserView extends AbstractView
{
  protected $_user;
  protected $_usersCount;

  public function __construct(User $user, $usersCount)
  {
    $user = $user->toArray();

    $user['games_json'] = json_decode($user['games_json'], true);
    //$user['games_json'] = array_filter($user['games_json']);
    if($user['games_json'])
    {
      arsort($user['games_json']);
    }
    else
    {
      $user['games_json'] = [];
    }

    // todo, handle when games_json is empty

    $user['friends_json'] = json_decode($user['friends_json'], true);

    $this->_user = $user;
    $this->_usersCount = $usersCount;
  }

  /**
   * @return array
   */
  public function getUser()
  {
    return $this->_user;
  }

  /**
   * @return int
   */
  public function getUsersCount()
  {
    return $this->_usersCount;
  }

  public function getGamesPlayed()
  {
    $games = $this->getUser()['games_json'];
    return number_format(count(array_filter($games)));
  }

  public function getXpBar()
  {
    $xp = UserXpStruct::fromModel($this->_user);

    $width = max($xp->fromLastLevelPercent, 10);

    $div = new Div();
    $div->addClass('progress-bar progress-bar-striped');
    $div->setAttribute('style', 'width:' . $width . '%');
    $div->setContent(
      'Level ' . $this->_user['level'] . ' (' . $xp->fromLastLevelPercent . '%)'
    );

    $a = new A('/experience/' . $this->getUser()['level'], $div);
    $a->setAttributes(
      [
        'class'          => 'progress',
        'data-toggle'    => 'tooltip',
        'data-placement' => 'bottom',
        'title'          => number_format($xp->startOfLevel) . 'XP',
        'style'          => 'display: block;',
      ]
    );

    return $a;
  }

  public function getValueRow()
  {
    $cols = [
      new Th('Value')
    ];

    foreach(SortFieldEnum::all() as $col)
    {
      $value = Arrays::value($this->getUser(), $col, 0);
      $formatted = number_format($value);

      switch($col)
      {
        case SortFieldEnum::LEVEL:
          $val = new A('/experience/' . $value, $formatted);
          break;
        case SortFieldEnum::TIME:
          $val = $this->getMinutes($value * 60);
          break;
        default:
          $val = $formatted;
          break;
      }

      $cols[] = new Td($val);
    }

    return new Tr($cols);
  }

  public function getRankRow()
  {
    $cols = [
      new Th('Rank')
    ];

    foreach(SortFieldEnum::all() as $col)
    {
      $link = 'Soon';
      $rank = $this->getUser()['rank_' . $col];
      if($rank)
      {
        $link = new A(
          '/users/' . $col . '/' . ceil($rank / 50),
          $this->getOrdinal($this->getUser()['rank_' . $col])
        );
      }

      $cols[] = new Td($link);
    }

    return new Tr($cols);
  }

  public function getPercentileRow()
  {
    $cols = [
      new Th('Percentile')
    ];

    foreach(SortFieldEnum::all() as $col)
    {
      $rank = $this->getUser()['rank_' . $col];
      $percent = ($rank / $this->getUsersCount()) * 100;

      $cols[] = new Td('Top ' . round($percent + 1) . '%');
    }

    return new Tr($cols);
  }
}
