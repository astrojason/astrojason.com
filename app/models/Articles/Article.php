<?php

namespace Articles;

use Eloquent;

/**
 * Articles\Article
 *
 * @property integer $id 
 * @property string $title 
 * @property string $url 
 * @property integer $user_id 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Articles\Category[] $categories 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Articles\Read[] $read 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Articles\Recommended[] $recommended 
 * @method static \Illuminate\Database\Query\Builder|\Articles\Article whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Articles\Article whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Articles\Article whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\Articles\Article whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Articles\Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Articles\Article whereUpdatedAt($value)
 */
class Article extends Eloquent {
  protected $fillable = [];

  public function categories() {
    return $this->belongsToMany('Articles\Category', 'article_category');
  }

  public function read() {
    return $this->hasMany('Articles\Read');
  }

  public function recommended() {
    return $this->hasMany('Articles\Recommended');
  }
}