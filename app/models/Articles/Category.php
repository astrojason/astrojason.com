<?php

namespace Articles;

use Eloquent;

/**
 * Articles\Category
 *
 * @property integer $id 
 * @property string $name 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Articles\Article[] $articles 
 * @method static \Illuminate\Database\Query\Builder|\Articles\Category whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Articles\Category whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Articles\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Articles\Category whereUpdatedAt($value)
 */
class Category extends Eloquent {
  protected $fillable = ['name'];
  protected $table = 'article_categories';

  public function articles() {
    return $this->belongsToMany('Articles\Article', 'article_category');
  }
}