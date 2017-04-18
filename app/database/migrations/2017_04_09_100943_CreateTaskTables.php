<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskTables extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('task_projects', function(Blueprint $table){
      $table->increments('id');
      $table->integer('user_id')->references('id')->on('users');
      $table->string('name', 255);

      $table->timestamps();
    });
    Schema::create('tasks', function(Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id')->references('id')->on('users');
      $table->string('name', 255);
      $table->string('frequency', 255)->default('once');
      $table->boolean('chained')->default(false);
      $table->boolean('cycle_subtasks')->default(false);
      $table->integer('subtasks_to_show')->nulllable();
      $table->integer('project_id')->references('id')->on('task_projects')->nullable();
      $table->timestamps();
    });
    Schema::create('task_due', function(Blueprint $table){
      $table->increments('id');
      $table->integer('task_id')->references('id')->on('tasks');
      $table->dateTime('due');
      $table->boolean('completed')->default(false);
      $table->timestamps();
    });
    Schema::create('task_subtasks', function(Blueprint $table){
      $table->increments('id');
      $table->integer('task_id')->references('id')->on('tasks');
      $table->integer('parent_task_id')->references('id')->on('tasks');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::drop('task_projects');
    Schema::drop('tasks');
    Schema::drop('task_due');
  }

}
