<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Book
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $goodreads_id
 * @property string $title
 * @property string $author_fname
 * @property string $author_lname
 * @property string $category
 * @property string $series
 * @property integer $series_order
 * @property boolean $is_read
 * @property boolean $owned
 * @property integer $times_recommended
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Book whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Book whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Book whereGoodreadsId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Book whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Book whereAuthorFname($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Book whereAuthorLname($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Book whereCategory($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Book whereSeries($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Book whereSeriesOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Book whereIsRead($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Book whereOwned($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Book whereTimesRecommended($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Book whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Book whereUpdatedAt($value)
 */
class Book extends Eloquent {
  protected $table = 'books_legacy';
}
