<?php
namespace Jleagle\Steam\Application\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property mixed $type
 * @property mixed $name
 * @property mixed $required_age
 * @property mixed $is_free
 * @property mixed $detailed_description
 * @property mixed $about_the_game
 * @property mixed $supported_languages
 * @property mixed $header_image
 * @property mixed $website
 * @property mixed $pc_requirements
 * @property mixed $mac_requirements
 * @property mixed $linux_requirements
 * @property mixed $developers
 * @property mixed $publishers
 * @property mixed $price_overview
 * @property mixed $created_at
 * @property mixed $updated_at
 *
 * @property string $categories
 * @property string $genres
 * @property string $screenshots
 * @property string $achievements
 * @property string $movies
 */
class App extends Model
{
  protected $table = 'apps';
  protected $fillable = ['id'];

  public function categories()
  {
    return $this->belongsToMany('Jleagle\Steam\Application\Models\Category');
  }

  public function genres()
  {
    return $this->belongsToMany('Jleagle\Steam\Application\Models\Genre');
  }
}
