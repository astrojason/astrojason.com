<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Song
 *
 * @property integer $id 
 * @property integer $user_id 
 * @property string $title 
 * @property string $artist 
 * @property string $location 
 * @property boolean $learned 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property integer $times_recommended 
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Song whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Song whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Song whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Song whereArtist($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Song whereLocation($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Song whereLearned($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Song whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Song whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Song whereTimesRecommended($value)
 */
class Song extends Eloquent {

  protected $table = 'songs_legacy';

}