<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveDashboardSettings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
      Schema::drop('dashboard_categories');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		//
	}

}
