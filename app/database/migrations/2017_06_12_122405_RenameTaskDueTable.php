<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameTaskDueTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::rename('task_due', 'task_dues');
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::rename('task_dues', 'task_due');
  }

}
