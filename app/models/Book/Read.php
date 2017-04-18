<?php

namespace Book;

use Eloquent;

/**
 * Book\Read
 *
 * @property integer $id
 * @property integer $book_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Book\Book $book
 * @method static \Illuminate\Database\Query\Builder|\Book\Read whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Book\Read whereBookId($value)
 * @method static \Illuminate\Database\Query\Builder|\Book\Read whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Book\Read whereUpdatedAt($value)
 * @property integer $user_id
 * @method static \Illuminate\Database\Query\Builder|\Book\Read whereUserId($value)
 */
class Read extends Eloquent {
  protected $fillable = [
    'book_id'
  ];
  protected $table = 'book_read';

  public function book() {
    return $this->hasOne('Book\Book');
  }
}