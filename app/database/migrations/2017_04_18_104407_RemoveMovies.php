<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveMovies extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::drop('movies');
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
      //
  }

}
