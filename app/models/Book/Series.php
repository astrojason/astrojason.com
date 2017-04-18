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
 * Book\Series
 *
 * @property integer $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Book\Book[] $books
 * @method static \Illuminate\Database\Query\Builder|\Book\Series whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Book\Series whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Book\Series whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Book\Series whereUpdatedAt($value)
 */
class Series extends Eloquent {

  protected $fillable = [
    'name'
  ];

  public function books() {
    return $this->belongsToMany('Book\Book', 'book_series');
  }

}