<?php
namespace Jleagle\Steam\Application\Views;

class UserListView extends AbstractView
{
  protected $_count;
  protected $_page;
  protected $_order;

  public function __construct($count, $page, $order)
  {
    $this->_count = $count;
    $this->_page = $page;
    $this->_order = $order;
  }


  public function getCount()
  {
    return $this->_count;
  }

  public function getPage()
  {
    return $this->_page;
  }

  public function getOrder()
  {
    return $this->_order;
  }
}
