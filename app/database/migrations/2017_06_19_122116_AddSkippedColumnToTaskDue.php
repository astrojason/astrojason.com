<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSkippedColumnToTaskDue extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	 public function up() {
     Schema::table('task_dues', function($table) {
       $table->boolean('skipped')->default(false);
     });
 	}

 	/**
 	 * Reverse the migrations.
 	 *
 	 * @return void
 	 */
 	public function down() {
     Schema::table('task_dues', function($table) {
       $table->dropColumn('skipped');
     });
 	}

}
