<?php

/**
 * DashboardCategory
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $category
 * @property boolean $randomize
 * @property integer $num_items
 * @property boolean $track
 * @property integer $position
 * @method static \Illuminate\Database\Query\Builder|\DashboardCategory whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\DashboardCategory whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\DashboardCategory whereCategory($value)
 * @method static \Illuminate\Database\Query\Builder|\DashboardCategory whereRandomize($value)
 * @method static \Illuminate\Database\Query\Builder|\DashboardCategory whereNumItems($value)
 * @method static \Illuminate\Database\Query\Builder|\DashboardCategory whereTrack($value)
 * @method static \Illuminate\Database\Query\Builder|\DashboardCategory wherePosition($value)
 */
class DashboardCategory extends \Eloquent {
	protected $table = 'dashboard_categories';

	public $timestamps = false;
}