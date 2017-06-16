<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSubtaskLogic extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
      Schema::drop('task_subtasks');
      Schema::table('tasks', function(Blueprint $table) {
        $table->boolean('parent_task_id')->nulllable();
      });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
      Schema::create('task_subtasks', function(Blueprint $table){
        $table->increments('id');
        $table->integer('task_id')->references('id')->on('tasks');
        $table->integer('parent_task_id')->references('id')->on('tasks');
        $table->timestamps();
      });
      Schema::table('accounts', function($table) {
        $table->dropColumn('parent_task_id');
      });
	}

}
