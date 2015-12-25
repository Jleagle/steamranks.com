<?php
namespace Jleagle\Steam\Application\Pages;

use Jleagle\HtmlBuilder\Tags\Headings\H1;
use Jleagle\HtmlBuilder\Tags\Headings\H3;
use Jleagle\HtmlBuilder\Tags\P;

class FaqsPage extends AbstractPage
{
  protected function _render()
  {
    return [
      new H1('FAQs'),
      new H3('Why are some of my stats zero?'),
      new P(''),
      new H3('How cna I help out?'),
      new P('Paypal'),
      new H3('How does Steam Ranks gather user data?'),
      new P(
        'When a users profile is viewed, we uses the Steam API to retrieve the user information. We then record all the users friends to add them to the ranking list.'
      ),
    ];
  }
}
