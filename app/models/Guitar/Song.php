<?php
/**
 *         _----_    _________       /\
 *        /      \  /         \/\ __///
 *       (        \/          / > /   \
 *        \        |      --/_>_/    /
 *          \_ ____|          \ /\ _/
 *            /               ///        __\
 *           (               // \       /  \\
 *            \      \     ///    \    /    \\
 *             (      \   //       \  /\  _  \\
 *              \   ___|///    _    \/  \/ \__)\
 *               ( / _ //\    ( \       /
 *                /_ /// /     \ \ _   /
 *                (__)  ) \_    \   --~
 *                ///--/    \____\
 *               //        __)    \
 *             ///        (________)
 *  _________///          ===========
 * //|_____|///
 *
 * Created by PhpStorm.
 * User: jsylvester
 * Date: 4/17/17
 * Time: 5:21 PM
 */

namespace Guitar;

use Eloquent;

/**
 * Guitar\Song
 *
 * @property integer $id
 * @property string $title
 * @property integer $rhythm
 * @property integer $solo
 * @property integer $singing
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Guitar\Song whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Guitar\Song whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Guitar\Song whereRhythm($value)
 * @method static \Illuminate\Database\Query\Builder|\Guitar\Song whereSolo($value)
 * @method static \Illuminate\Database\Query\Builder|\Guitar\Song whereSinging($value)
 * @method static \Illuminate\Database\Query\Builder|\Guitar\Song whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Guitar\Song whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\User[] $users
 * @property-read \Guitar\Artist $artist
 * @property-read \Illuminate\Database\Eloquent\Collection|\Guitar\Category[] $categories
 */
class Song extends Eloquent {

  protected $fillable = ['title'];

  public function users() {
    return $this->belongsToMany('User', 'user_song');
  }

  public function artist() {
    return $this->hasOne('Guitar\Artist');
  }

  public function categories() {
    return $this->belongsToMany('Guitar\Category', 'song_category');
  }

}