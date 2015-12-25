<?php
namespace Jleagle\Steam\Application\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  protected $table = 'categories';
  protected $fillable = ['id', 'description'];

  public function apps()
  {
    return $this->belongsToMany('Jleagle\Steam\Application\Models\App');
  }
}
