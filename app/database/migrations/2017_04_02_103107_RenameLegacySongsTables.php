<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameLegacySongsTables extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::rename('songs', 'songs_legacy');
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::rename('songs_legacy', 'songs');
  }

}
