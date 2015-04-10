<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSongsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('songs', function($table) {
			$table->increments('id');
			$table->integer('user_id')->references('id')->on('users');
			$table->string('title', 255);
			$table->string('artist', 255);
			$table->string('location', 255);
			$table->boolean('learned')->default(false);
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
		//
	}

}
