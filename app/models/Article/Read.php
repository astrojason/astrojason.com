<?php

namespace Article;

use Eloquent;

/**
 * Article\Read
 *
 * @property integer $id
 * @property integer $article_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Article\Article $article
 * @method static \Illuminate\Database\Query\Builder|\Article\Read whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Article\Read whereArticleId($value)
 * @method static \Illuminate\Database\Query\Builder|\Article\Read whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Article\Read whereUpdatedAt($value)
 */
class Read extends Eloquent {
  protected $fillable = [
    'article_id'
  ];
  protected $table = 'articles_read';

  public function article() {
    return $this->hasOne('Article\Article');
  }
}