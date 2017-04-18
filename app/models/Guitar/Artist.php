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
 * Date: 4/18/17
 * Time: 9:46 AM
 */

namespace Guitar;

use Eloquent;

/**
 * Guitar\Artist
 *
 * @property integer $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Guitar\Song[] $songs
 * @method static \Illuminate\Database\Query\Builder|\Guitar\Artist whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Guitar\Artist whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Guitar\Artist whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Guitar\Artist whereUpdatedAt($value)
 */
class Artist extends Eloquent {

  protected $fillable = ['name'];

  public function songs() {
    return $this->belongsToMany('Guitar\Song', 'song_artist');
  }

}