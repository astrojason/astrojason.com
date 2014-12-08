<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
    Schema::create('roles', function($table) {
      $table->increments('id');
      $table->string('name', 255);
      $table->timestamps();
    });
    Schema::create('users_roles', function($table) {
      $table->increments('id');
      $table->integer('user_id')->references('id')->on('users');
      $table->integer('role_id')->references('id')->on('roles');
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
    Schema::drop('roles');
    Schema::drop('users_roles');
	}

}
