<?php
namespace Jleagle\Steam\Application\Views;

class UserNotFound extends AbstractView
{
  protected $_search;

  function __construct($search)
  {
    $this->_search = $search;
  }

  public function getSearch()
  {
    return $this->_search;
  }
}
