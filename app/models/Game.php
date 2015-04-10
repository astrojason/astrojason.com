<?php

/**
 * Game
 *
 * @property integer $id 
 * @property integer $user_id 
 * @property string $title 
 * @property string $platform 
 * @property boolean $completed 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @method static \Illuminate\Database\Query\Builder|\Game whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Game whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Game whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Game wherePlatform($value)
 * @method static \Illuminate\Database\Query\Builder|\Game whereCompleted($value)
 * @method static \Illuminate\Database\Query\Builder|\Game whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Game whereUpdatedAt($value)
 */
class Game extends Eloquent {

  public function __construct() {
    $this->user_id = Auth::user()->id;
  }

}
