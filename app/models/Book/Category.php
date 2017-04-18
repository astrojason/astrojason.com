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
 * Date: 4/14/17
 * Time: 9:23 AM
 */

namespace Book;

use Eloquent;

/**
 * Book\Category
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Book\Book[] $books
 * @method static \Illuminate\Database\Query\Builder|\Book\Category whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Book\Category whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Book\Category whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Book\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Book\Category whereUpdatedAt($value)
 */
class Category extends Eloquent {

  protected $table = 'book_categories';

  protected $fillable = [
    'user_id',
    'name'
  ];

  public function books() {
    return $this->belongsToMany('Book\Book', 'book_category');
  }

}