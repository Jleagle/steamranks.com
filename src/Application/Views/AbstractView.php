<?php
namespace Jleagle\Steam\Application\Views;

use Cubex\View\TemplatedViewModel;

abstract class AbstractView extends TemplatedViewModel
{
  use ViewTrait;

  public function getOrdinal($number)
  {
    $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
    if((($number % 100) >= 11) && (($number % 100) <= 13))
    {
      return $number . 'th';
    }
    else
    {
      return $number . $ends[$number % 10];
    }
  }

  public function getAd()
  {
    return <<<AD

<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-5468884371420326"
     data-ad-slot="3323975697"
     data-ad-format="auto"></ins>
<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>

AD;
  }
}
