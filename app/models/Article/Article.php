<?php

namespace Article;

use Eloquent;

/**
 * Article\Article
 *
 * @property integer $id
 * @property string $title
 * @property string $url
 * @property integer $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Article\Category[] $categories
 * @property-read \Illuminate\Database\Eloquent\Collection|\Article\Read[] $read
 * @property-read \Illuminate\Database\Eloquent\Collection|\Article\Recommended[] $recommended
 * @method static \Illuminate\Database\Query\Builder|\Article\Article whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Article\Article whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Article\Article whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\Article\Article whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Article\Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Article\Article whereUpdatedAt($value)
 */
class Article extends Eloquent {

  public $times_recommended;

  protected $fillable = [
    'user_id',
    'title',
    'url'
  ];

  public $justAdded = false;

  public function categories() {
    return $this->belongsToMany('Article\Category', 'article_category');
  }

  public function read() {
    return $this->hasMany('Article\Read');
  }

  public function recommended() {
    return $this->hasMany('Article\Recommended');
  }
}