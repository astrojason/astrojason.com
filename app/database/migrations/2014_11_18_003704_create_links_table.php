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
    Schema::create('links', function($table) {
      $table->increments('id');
      $table->string('name', 20);
      $table->string('link', 20);
      $table->string('category', 20);
      $table->boolean('read');
      $table->integer('instapaper_id');
      $table->integer('user_id')->references('id')->on('users');
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
