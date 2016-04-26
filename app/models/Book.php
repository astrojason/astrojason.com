<?php

/**
 * Book
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
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Book whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Book whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Book whereGoodreadsId($value)
 * @method static \Illuminate\Database\Query\Builder|\Book whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Book whereAuthorFname($value)
 * @method static \Illuminate\Database\Query\Builder|\Book whereAuthorLname($value)
 * @method static \Illuminate\Database\Query\Builder|\Book whereCategory($value)
 * @method static \Illuminate\Database\Query\Builder|\Book whereSeries($value)
 * @method static \Illuminate\Database\Query\Builder|\Book whereSeriesOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\Book whereIsRead($value)
 * @method static \Illuminate\Database\Query\Builder|\Book whereOwned($value)
 * @method static \Illuminate\Database\Query\Builder|\Book whereTimesLoaded($value)
 * @method static \Illuminate\Database\Query\Builder|\Book whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Book whereUpdatedAt($value)
 * @property integer $times_recommended
 * @method static \Illuminate\Database\Query\Builder|\Book whereTimesRecommended($value)
 */

namespace App\Models;

use Eloquent;

class Book extends Eloquent {

}
