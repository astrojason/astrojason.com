<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Update201404151151Table extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
    DB::update("ALTER TABLE books ALTER COLUMN author_fname DROP NOT NULL");
    DB::update("ALTER TABLE users ADD COLUMN remember_token VARCHAR(320)");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
