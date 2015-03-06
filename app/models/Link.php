<?php

/**
 * Link
 *
 * @property integer $id
 * @property string $name
 * @property string $link
 * @property string $category
 * @property boolean $is_read
 * @property integer $instapaper_id
 * @property integer $user_id
 * @property integer $times_loaded
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $times_read
 * @method static \Illuminate\Database\Query\Builder|\Link whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Link whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Link whereLink($value)
 * @method static \Illuminate\Database\Query\Builder|\Link whereCategory($value)
 * @method static \Illuminate\Database\Query\Builder|\Link whereIsRead($value)
 * @method static \Illuminate\Database\Query\Builder|\Link whereInstapaperId($value)
 * @method static \Illuminate\Database\Query\Builder|\Link whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Link whereTimesLoaded($value)
 * @method static \Illuminate\Database\Query\Builder|\Link whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Link whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Link whereTimesRead($value)
 */
class Link extends Eloquent {
  public function __construct() {
    $this->user_id = Auth::user()->id;
    $this->is_read = false;
  }
}
