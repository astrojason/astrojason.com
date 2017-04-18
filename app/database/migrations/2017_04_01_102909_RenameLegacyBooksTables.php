<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameLegacyBooksTables extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::rename('books', 'books_legacy');
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::rename('books_legacy', 'books');
  }

}
