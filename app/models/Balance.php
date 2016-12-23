<?php

/**
 * Balance
 *
 * @property integer $id 
 * @property integer $account_id 
 * @property float $amount 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @method static \Illuminate\Database\Query\Builder|\Balance whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Balance whereAccountId($value)
 * @method static \Illuminate\Database\Query\Builder|\Balance whereAmount($value)
 * @method static \Illuminate\Database\Query\Builder|\Balance whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Balance whereUpdatedAt($value)
 */
class Balance extends Eloquent {
	protected $fillable = [];

  public function account() {
    return $this->belongsTo('Account');
  }
}