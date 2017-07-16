<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteCreditTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::drop('accounts');
		Schema::drop('balances');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::create('accounts', function($table) {
      $table->increments('id');
      $table->integer('user_id')->references('id')->on('users');
      $table->string('name', 255);
      $table->decimal('limit', 13, 2);
			$table->boolean('active')->default(true);
      $table->timestamps();
    });
		Schema::create('balances', function($table) {
      $table->increments('id');
      $table->integer('account_id')->references('id')->on('accounts');
      $table->decimal('amount', 13, 2);
      $table->timestamps();
    });
	}

}
