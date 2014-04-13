<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
    Schema::create('links', function($table)
    {
      $table->increments('id');
      $table->integer('instapaper_id');
      $table->integer('user_id');
      $table->string('name', 500);
      $table->string('link', 4000);
      $table->string('category', 255);
      $table->boolean('read');
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
    Schema::drop('links');
	}

}
