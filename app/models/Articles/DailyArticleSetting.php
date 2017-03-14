<?php

namespace Articles;

use Eloquent;

/**
 * Articles\DailySetting
 *
 * @property integer $id 
 * @property integer $user_id 
 * @property integer $category_id 
 * @property integer $number
 * @property boolean $allow_read
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property-read \Articles\Category $category 
 * @method static \Illuminate\Database\Query\Builder|\Articles\DailySetting whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Articles\DailySetting whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Articles\DailySetting whereCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\Articles\DailySetting whereNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\Articles\DailySetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Articles\DailySetting whereUpdatedAt($value)
 */
class DailySetting extends Eloquent {
  protected $fillable = [];

  protected $table = 'daily_articles_settings';

  public function category(){
    return $this->hasOne('Articles\Category');
  }
}