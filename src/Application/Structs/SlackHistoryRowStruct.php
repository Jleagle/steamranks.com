<?php
namespace Jleagle\Steam\Application\Structs;

class SlackHistoryRowStruct extends AbstractStruct
{
  public $user;
  public $type;
  public $subtype;
  public $hidden;
  public $text;
  public $ts;
  public $is_starred;
}
