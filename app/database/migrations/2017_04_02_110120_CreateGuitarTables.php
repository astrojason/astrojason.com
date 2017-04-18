<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuitarTables extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('songs', function (Blueprint $table) {
      $table->increments('id');
      $table->string('title', 255);
      $table->integer('rhythm')->nullable();
      $table->integer('solo')->nullable();
      $table->integer('singing')->nullable();
      $table->timestamps();
    });
    Schema::create('artists', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name');
      $table->timestamps();
    });
    Schema::create('song_artist', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('song_id')->references('id')->on('songs');
      $table->integer('artist_id')->references('id')->on('artists');
      $table->timestamps();
    });
    Schema::create('song_categories', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name', 255);
      $table->timestamps();
    });
    Schema::create('song_practiced', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('song_id')->references('id')->on('songs');
      $table->integer('user_id')->references('id')->on('users');
      $table->timestamps();
    });
    Schema::create('song_category', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('song_id')->references('id')->on('songs');
      $table->integer('user_id')->references('id')->on('users');
      $table->integer('category_id')->references('id')->on('song_categories');
      $table->timestamps();
    });
    Schema::create('user_song', function(Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id')->references('id')->on('users');
      $table->integer('song_id')->references('id')->on('songs');
      $table->timestamps();
    });
    Schema::create('practices', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id')->references('id')->on('users');
      $table->string('name', 255);
      $table->timestamps();
    });
    Schema::create('practice_categories', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name', 255);
      $table->timestamps();
    });
    Schema::create('practice_category', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('practice_id')->references('id')->on('practices');
      $table->integer('category_id')->references('id')->on('practice_categories');
      $table->timestamps();
    });
    Schema::create('practice_practiced', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('practice_id')->references('id')->on('practices');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::drop('songs');
    Schema::drop('artists');
    Schema::drop('song_artist');
    Schema::drop('song_categories');
    Schema::drop('song_practiced');
    Schema::drop('song_category');
    Schema::drop('user_song');
    Schema::drop('practices');
    Schema::drop('practice_categories');
    Schema::drop('practice_category');
    Schema::drop('practice_practiced');
  }

}
