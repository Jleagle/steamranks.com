<?php
namespace Jleagle\Steam\Application\Views;

use Jleagle\HtmlBuilder\Tags\Forms\Input;
use Jleagle\HtmlBuilder\Tags\Label;
use Jleagle\Steam\Application\Structs\AppRowStruct;

class AppListView extends AbstractView
{
  protected $_apps;
  protected $_count;
  protected $_search;
  protected $_min;
  protected $_types;

  /**
   * @param AppRowStruct[] $apps
   * @param int            $count
   * @param string         $search
   * @param int            $min
   * @param array          $types
   */
  public function __construct(array $apps, $count, $search, $min, array $types)
  {
    $this->_apps = $apps;
    $this->_count = $count;
    $this->_search = $search;
    $this->_min = $min;
    $this->_types = $types;
  }

  /**
   * @return AppRowStruct[]
   */
  public function getApps()
  {
    return $this->_apps;
  }

  public function getCount()
  {
    return $this->_count;
  }

  /**
   * @return string
   */
  public function getSearch()
  {
    return $this->_search;
  }

  public function getTitle()
  {
    if($this->getSearch())
    {
      return 'Search Games';
    }
    else
    {
      return 'Latest Games';
    }
  }

  /**
   * @return int
   */
  public function getMin()
  {
    return $this->_min;
  }

  /**
   * @return array
   */
  public function getTypes()
  {
    return array_unique($this->_types);
  }

  public function getCheckBoxes()
  {
    return null;
    $return = [];

    foreach($this->getTypes() as $checkBox)
    {
      $input = new Input(Input::TYPE_CHECKBOX);

      $label = new Label($input);
      $label->addClass('checkbox-inline');

      $return[] =$label;
    }

    return $return;

  }
}
