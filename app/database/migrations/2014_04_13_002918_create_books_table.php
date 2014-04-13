<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
    Schema::create('books', function($table)
    {
      $table->increments('id');
      $table->integer('user_id');
      $table->integer('goodreads_id');
      $table->string('title', 8000);
      $table->string('author_fname', 255);
      $table->string('author_lname', 255);
      $table->string('category', 255);
      $table->string('series', 320);
      $table->integer('seriesorder');
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
    Schema::drop('books');
	}

}
