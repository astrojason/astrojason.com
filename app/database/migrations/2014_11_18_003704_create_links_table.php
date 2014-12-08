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
      $table->string('name', 255);
      $table->text('link');
      $table->string('category', 100);
      $table->boolean('is_read')->default(false);
      $table->integer('instapaper_id')->nullable();
      $table->integer('user_id')->references('id')->on('users');
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
    Schema::drop('links');
  }

}
