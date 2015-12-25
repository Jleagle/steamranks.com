<?php
namespace Jleagle\Steam\Application\Structs;

class AbstractStruct
{
  public static function make($data = [])
  {
    $self = new static;

    foreach($data as $k => $v)
    {
      if(property_exists($self, $k))
      {
        $self->{$k} = $v;
      }
    }

    return $self;
  }
}
