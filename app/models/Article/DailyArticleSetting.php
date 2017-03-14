<?php

namespace Article;

use Eloquent;

/**
 * Article\DailySetting
 *
 * @property integer $id 
 * @property integer $user_id 
 * @property integer $category_id 
 * @property integer $number
 * @property boolean $allow_read
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property-read \Article\Category $category
 * @method static \Illuminate\Database\Query\Builder|\Article\DailySetting whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Article\DailySetting whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Article\DailySetting whereCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\Article\DailySetting whereNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\Article\DailySetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Article\DailySetting whereUpdatedAt($value)
 */
class DailySetting extends Eloquent {
  protected $fillable = [];

  protected $table = 'daily_articles_settings';

  public function category(){
    return $this->hasOne('Article\Category');
  }
}