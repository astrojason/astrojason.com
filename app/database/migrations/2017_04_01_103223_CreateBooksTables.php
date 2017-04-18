<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTables extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('books', function(Blueprint $table){
      $table->increments('id');
      $table->string('title', 255);
      $table->timestamps();
    });
    Schema::create('authors', function(Blueprint $table){
      $table->increments('id');
      $table->string('first_name', 255)->nullable();
      $table->string('last_name', 255)->nullable();
      $table->timestamps();
    });
    Schema::create('series', function(Blueprint $table){
      $table->increments('id');
      $table->string('name', 255);
      $table->timestamps();
    });
    Schema::create('book_categories', function(Blueprint $table){
      $table->increments('id');
      $table->integer('user_id')->references('id')->on('users');
      $table->string('name', 255);
      $table->timestamps();
    });
    Schema::create('statuses', function(Blueprint $table){
      $table->increments('id');
      $table->string('name', 255);
      $table->timestamps();
    });
    Schema::create('user_book', function(Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id')->references('id')->on('users');
      $table->integer('book_id')->references('id')->on('books');
      $table->timestamps();
    });
    Schema::create('book_author', function(Blueprint $table){
      $table->increments('id');
      $table->integer('author_id')->references('id')->on('authors');
      $table->integer('book_id')->references('id')->on('books');
      $table->timestamps();
    });
    Schema::create('book_series', function(Blueprint $table){
      $table->increments('id');
      $table->integer('series_id')->references('id')->on('series');
      $table->integer('book_id')->references('id')->on('books');
      $table->integer('number');
      $table->timestamps();
    });
    Schema::create('book_category', function(Blueprint $table){
      $table->increments('id');
      $table->integer('category_id')->references('id')->on('book_categories');
      $table->integer('book_id')->references('id')->on('books');
      $table->timestamps();
    });
    Schema::create('book_status', function(Blueprint $table){
      $table->increments('id');
      $table->integer('status_id')->references('id')->on('statuses');
      $table->integer('book_id')->references('id')->on('books');
      $table->timestamps();
    });
    Schema::create('book_read', function(Blueprint $table){
      $table->increments('id');
      $table->integer('user_id')->references('id')->on('users');
      $table->integer('book_id')->references('id')->on('books');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::drop('books');
    Schema::drop('authors');
    Schema::drop('series');
    Schema::drop('book_categories');
    Schema::drop('statuses');
    Schema::drop('user_book');
    Schema::drop('book_author');
    Schema::drop('book_series');
    Schema::drop('book_category');
    Schema::drop('book_status');
    Schema::drop('book_read');
  }

}
