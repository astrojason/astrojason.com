<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
    Schema::create('games', function($table)
    {
      $table->increments('id');
      $table->integer('user_id');
      $table->string('title', 320);
      $table->string('platform', 320);
      $table->boolean('completed');
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
    Schema::drop('games');
	}

}
