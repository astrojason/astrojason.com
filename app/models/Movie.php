<?php


/**
 * Movie
 *
 * @property integer $id 
 * @property integer $user_id 
 * @property string $title 
 * @property integer $rating_order 
 * @property integer $times_watched 
 * @property boolean $is_watched 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $date_watched 
 * @method static \Illuminate\Database\Query\Builder|\Movie whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Movie whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Movie whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Movie whereRatingOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\Movie whereTimesWatched($value)
 * @method static \Illuminate\Database\Query\Builder|\Movie whereIsWatched($value)
 * @method static \Illuminate\Database\Query\Builder|\Movie whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Movie whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Movie whereDateWatched($value)
 */
class Movie extends Eloquent {

}