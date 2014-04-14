<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBooksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
    DB::update("ALTER TABLE books ALTER COLUMN goodreads_id DROP NOT NULL");
    DB::update("ALTER TABLE books ALTER COLUMN author_lname DROP NOT NULL");
    DB::update("ALTER TABLE books ALTER COLUMN author_lname DROP NOT NULL");
    DB::update("ALTER TABLE books ALTER COLUMN series DROP NOT NULL");
    DB::update("ALTER TABLE books ALTER COLUMN seriesorder SET DEFAULT 0");
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
