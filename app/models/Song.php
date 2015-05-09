<?php


/**
 * Song
 *
 * @property integer $id 
 * @property integer $user_id 
 * @property string $title 
 * @property string $artist 
 * @property string $location 
 * @property boolean $learned 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @method static \Illuminate\Database\Query\Builder|\Song whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Song whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Song whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Song whereArtist($value)
 * @method static \Illuminate\Database\Query\Builder|\Song whereLocation($value)
 * @method static \Illuminate\Database\Query\Builder|\Song whereLearned($value)
 * @method static \Illuminate\Database\Query\Builder|\Song whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Song whereUpdatedAt($value)
 */
class Song extends Eloquent {

}