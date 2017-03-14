<?php

namespace Articles;

use Eloquent;

/**
 * Articles\Read
 *
 * @property integer $id
 * @property integer $article_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Articles\Article $article
 * @method static \Illuminate\Database\Query\Builder|\Articles\Read whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Articles\Read whereArticleId($value)
 * @method static \Illuminate\Database\Query\Builder|\Articles\Read whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Articles\Read whereUpdatedAt($value)
 */
class Read extends Eloquent {
  protected $fillable = [
    'article_id'
  ];
  protected $table = 'articles_read';

  public function article() {
    return $this->hasOne('Articles\Article');
  }
}