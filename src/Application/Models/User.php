<?php
namespace Jleagle\Steam\Application\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int   $id
 * @property mixed $badges
 * @property mixed $games
 * @property mixed $games_json
 * @property mixed $time
 * @property mixed $time_on_steam
 * @property mixed $friends
 * @property mixed $friends_json
 * @property mixed $level
 * @property mixed $avatar_large
 * @property mixed $avatar_medium
 * @property mixed $profile
 * @property mixed $name
 * @property mixed $real_name
 * @property mixed $country
 * @property mixed $xp
 * @property mixed $xp_needed
 * @property mixed $xp_current
 * @property mixed $xp_from_badges
 * @property mixed $created_at
 * @property mixed $updated_at
 * @property mixed $updated_at_friends
 *
 * @property int   $rank_level
 * @property int   $rank_games
 * @property int   $rank_badges
 * @property int   $rank_time
 * @property int   $rank_friends
 */
class User extends Model
{
  protected $table = 'users';
  protected $fillable = ['id'];
}
