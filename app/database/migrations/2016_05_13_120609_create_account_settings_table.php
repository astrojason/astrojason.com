<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('settings', function($table) {
			$table->increments('id');
			$table->integer('user_id')->references('id')->on('users');
			$table->boolean('books')->default(true);
      $table->boolean('games')->default(true);
      $table->boolean('movies')->default(true);
      $table->boolean('songs')->default(true);
			$table->timestamps();
		});
    Schema::create('dashboard_categories', function($table) {
      $table->increments('id');
      $table->integer('user_id')->references('id')->on('users');
      $table->string('category', 100);
			$table->boolean('randomize')->default(true);
			$table->integer('num_items')->default(0);
			$table->boolean('track')->default(false);
      $table->integer('position');
      $table->unique(['user_id', 'position']);
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
    Schema::drop('dashboard_categories');
		Schema::drop('settings');
	}

}
