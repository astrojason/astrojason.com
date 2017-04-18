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
 * Date: 4/14/17
 * Time: 9:30 AM
 */

namespace Book;

use Eloquent;

/**
 * Book\Status
 *
 * @property integer $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Book\Status whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Book\Status whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Book\Status whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Book\Status whereUpdatedAt($value)
 */
class Status extends Eloquent {

  protected $table = 'statuses';

}