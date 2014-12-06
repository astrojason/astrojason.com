<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRoles extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
    Schema::create(
      'roles',
      function (Blueprint $table) {
        $table->increments('id');
        $table->string('name');
      }
    );
    Schema::create(
      'users_roles',
      function (Blueprint $table) {
        $table->integer('user_id');
        $table->integer('role_id');
      }
    );
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
