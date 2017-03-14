<?php

namespace Articles;

use Eloquent;

/**
 * Articles\Recommended
 *
 * @property integer $id
 * @property integer $article_id
 * @property integer $user_id
 * @property boolean $postpone
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Articles\Article $article
 * @method static \Illuminate\Database\Query\Builder|\Articles\Recommended whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Articles\Recommended whereArticleId($value)
 * @method static \Illuminate\Database\Query\Builder|\Articles\Recommended whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Articles\Recommended whereUpdatedAt($value)
 */
class Recommended extends Eloquent {
  protected $fillable = [
    'article_id',
    'user_id'
  ];
  protected $table = 'articles_recommended';

  public function article() {
    return $this->hasOne('Articles\Article', 'id', 'article_id');
  }
}