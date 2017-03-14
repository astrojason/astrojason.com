<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyArticlesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('daily_articles_settings', function($table){
      $table->increments('id');
      $table->integer('user_id')->references('id')->on('users');
      $table->integer('category_id')->nullable();
      $table->integer('number');
      $table->boolean('allow_read')->default(false);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::drop('daily_articles_settings');
  }

}
