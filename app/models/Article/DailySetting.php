<?php
/**
 *         _----_    _________       /\
 *        /      \  /         \/\ __///
 *       (        \/          / > /   \
 *        \        |      --/_>_/    /
 *          \_ ____|          \ /\ _/
 *            /               ///        __\
 *           (               // \       /  \\
 *            \      \     ///    \    /    \\
 *             (      \   //       \  /\  _  \\
 *              \   ___|///    _    \/  \/ \__)\
 *               ( / _ //\    ( \       /
 *                /_ /// /     \ \ _   /
 *                (__)  ) \_    \   --~
 *                ///--/    \____\
 *               //        __)    \
 *             ///        (________)
 *  _________///          ===========
 * //|_____|///
 *
 * Created by PhpStorm.
 * User: jsylvester
 * Date: 4/7/17
 * Time: 3:27 PM
 */

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
 * @method static \Illuminate\Database\Query\Builder|\Article\DailySetting whereAllowRead($value)
 */

class DailySetting extends Eloquent {

  protected $fillable = [
    'user_id',
    'category_id',
    'number',
    'allow_read'
  ];

  protected $table = 'daily_articles_settings';

  public function category(){
    return $this->hasOne('Article\Category');
  }
}