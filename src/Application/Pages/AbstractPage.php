<?php
namespace Jleagle\Steam\Application\Pages;

use Jleagle\HtmlBuilder\Tags\Div;

abstract class AbstractPage
{
  abstract protected function _render();

  function __toString()
  {
    return (string)new Div($this->_render());
  }
}
