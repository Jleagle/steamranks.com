<?php
namespace Jleagle\Steam\Application\Views;

class ApiView extends AbstractView
{
  protected $_path;

  public function __construct($path)
  {
    $this->_path = $path;
  }

  public function getpath()
  {
    return $this->_path;
  }
}
