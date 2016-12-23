<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
    Schema::create('accounts', function($table) {
      $table->increments('id');
      $table->integer('user_id')->references('id')->on('users');
      $table->string('name', 255);
      $table->decimal('limit', 13, 2);
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
    Schema::drop('accounts');
	}

}
