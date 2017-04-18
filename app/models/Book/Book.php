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
 * Time: 9:21 AM
 */

namespace Book;

use Eloquent;

/**
 * Book\Book
 *
 * @property integer $id
 * @property string $title
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\User[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\Book\Author[] $authors
 * @property-read \Illuminate\Database\Eloquent\Collection|\Book\Category[] $categories
 * @property-read \Illuminate\Database\Eloquent\Collection|\Book\Series[] $series
 * @method static \Illuminate\Database\Query\Builder|\Book\Book whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Book\Book whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Book\Book whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Book\Book whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Book\Read[] $read
 */
class Book extends Eloquent {

  protected $fillable = ['title'];

  public function users() {
    return $this->belongsToMany('User', 'user_book');
  }

  public function authors() {
    return $this->hasMany('Book\Author', 'book_author');
  }

  public function categories() {
    return $this->hasMany('Book\Category', 'book_category');
  }

  public function series() {
    return $this->hasMany('Book\Series', 'book_series');
  }

  public function read() {
    return $this->hasMany('Book\Read');
  }

}