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
    Schema::create('movies', function($table)
    {
      $table->increments('id');
      $table->integer('user_id');
      $table->string('title', 320);
      $table->integer('rating_order');
      $table->timestamps();
    });
    Schema::create('movie_datewatched', function($table)
    {
      $table->increments('id');
      $table->integer('movie_id');
      $table->timestamp('date_watched');
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
