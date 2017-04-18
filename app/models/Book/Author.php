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
 * Time: 9:22 AM
 */

namespace Book;

use Eloquent;

/**
 * Book\Author
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Book\Book[] $books
 * @method static \Illuminate\Database\Query\Builder|\Book\Author whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Book\Author whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\Book\Author whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\Book\Author whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Book\Author whereUpdatedAt($value)
 */
class Author extends Eloquent {

  protected $fillable = [
    'first_name',
    'last_name'
  ];

  public function books() {
    return $this->belongsToMany('Book\Book', 'book_author');
  }
}