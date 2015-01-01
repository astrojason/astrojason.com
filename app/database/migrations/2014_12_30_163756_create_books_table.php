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
		Schema::create('books', function($table) {
			$table->increments('id');
			$table->integer('user_id')->references('id')->on('users');
			$table->integer('goodreads_id')->nullable();
			$table->string('title', 255);
			$table->string('author_fname', 255)->nullable();
			$table->string('author_lname', 255)->nullable();
			$table->string('category', 100);
			$table->string('series', 100)->nullable();
			$table->integer('series_order')->default(0);
			$table->boolean('is_read')->default(false);
			$table->boolean('owned')->default(false);
			$table->integer('times_loaded')->default(0);
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
