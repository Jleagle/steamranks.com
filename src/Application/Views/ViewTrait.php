<?php
namespace Jleagle\Steam\Application\Views;

use Jleagle\HtmlBuilder\Tags\Img;
use Jleagle\Steam\Application\Enums\CountryEnum;

trait ViewTrait
{
  function getMinutes($secs, $small = false)
  {
    // specifically handle zero
    if($secs == 0)
    {
      return '0 minutes';
    }

    // https://gist.github.com/erickpatrick/3039081
    $units = [
      'year'   => 52 * 7 * 24 * 60 * 60,
      //'week'   => 7 * 24 * 60 * 60,
      'day'    => 24 * 60 * 60,
      'hour'   => 60 * 60,
      'minute' => 60,
    ];

    if($small)
    {
      unset($units['minute'], $units['hour']);
    }

    $s = [];
    foreach($units as $name => $divisor)
    {
      if($quot = intval($secs / $divisor))
      {
        $s[] = $quot . ' ' . $name . (abs($quot) > 1 ? 's' : '');
        $secs -= $quot * $divisor;
      }
    }

    return implode($s, ', ');
  }

  public function getCountryFlag($code)
  {
    $country = strtolower($code);
    if($country)
    {
      $path = '/img/flags/' . $country . '.png';

      if($path)
      {
        $country = CountryEnum::getCountry($country);
        $img = new Img($path, $country);
        $img->setAttributes(
          [
            'data-toggle'    => 'tooltip',
            'data-placement' => 'right',
            'title'          => $country,
            'class'          => 'flag',
          ]
        );
        return $img;
      }
      error_log('Image file for country: ' . $code . ' does not exist.');
    }

    return null;
  }
}
