<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoviesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('movies', function($table) {
			$table->increments('id');
			$table->integer('user_id')->references('id')->on('users');
			$table->string('title', 255);
			$table->integer('rating_order')->default(0);
			$table->integer('times_watched')->default(0);
			$table->boolean('is_watched')->default(false);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('movies');
	}

}
