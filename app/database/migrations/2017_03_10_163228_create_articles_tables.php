<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTables extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('article_categories', function($table) {
      $table->increments('id');
      $table->string('name', 255);
      $table->timestamps();
    });

    Schema::create('articles', function($table) {
      $table->increments('id');
      $table->string('title', 255);
      $table->text('url');
      $table->integer('user_id')->references('id')->on('users');
      $table->timestamps();
    });

    Schema::create('article_category', function($table) {
      $table->increments('id');
      $table->integer('article_id')->references('id')->on('articles');
      $table->integer('category_id')->references('id')->on('article_categories');
      $table->timestamps();
    });

    Schema::create('articles_read', function($table) {
      $table->increments('id');
      $table->integer('article_id')->references('id')->on('articles');
      $table->timestamps();
    });

    Schema::create('articles_recommended', function($table) {
      $table->increments('id');
      $table->integer('article_id')->references('id')->on('articles');
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
    Schema::drop('article_categories');
    Schema::drop('articles');
    Schema::drop('article_category');
    Schema::drop('articles_read');
    Schema::drop('articles_recommended');
  }

}
