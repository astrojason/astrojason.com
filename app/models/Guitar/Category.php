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
 * Time: 9:47 AM
 */

namespace Guitar;

use Eloquent;

/**
 * Guitar\Category
 *
 * @property integer $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Guitar\Song[] $songs
 * @method static \Illuminate\Database\Query\Builder|\Guitar\Category whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Guitar\Category whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Guitar\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Guitar\Category whereUpdatedAt($value)
 */
class Category extends Eloquent {

  protected $table = 'song_categories';

  protected $fillable = ['name'];

  public function songs() {
    return $this->belongsToMany('Guitar\Song', 'song_category');
  }
}