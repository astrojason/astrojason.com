<?php

/**
 * Settings
 *
 * @property integer $id
 * @property integer $user_id
 * @property boolean $books
 * @property boolean $games
 * @property boolean $movies
 * @property boolean $songs
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Settings whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Settings whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Settings whereBooks($value)
 * @method static \Illuminate\Database\Query\Builder|\Settings whereGames($value)
 * @method static \Illuminate\Database\Query\Builder|\Settings whereMovies($value)
 * @method static \Illuminate\Database\Query\Builder|\Settings whereSongs($value)
 * @method static \Illuminate\Database\Query\Builder|\Settings whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Settings whereUpdatedAt($value)
 */
class Settings extends \Eloquent {
	protected $fillable = [];
}