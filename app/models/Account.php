<?php

/**
 * Account
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property float $limit
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Account whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Account whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Account whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Account whereLimit($value)
 * @method static \Illuminate\Database\Query\Builder|\Account whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Account whereUpdatedAt($value)
 * @property boolean $active
 * @property-read \Illuminate\Database\Eloquent\Collection|\Balance[] $balances
 * @method static \Illuminate\Database\Query\Builder|\Account whereActive($value)
 */
class Account extends Eloquent {
	protected $fillable = [];

  public function balances() {
    return $this->hasMany('Balance');
  }
}