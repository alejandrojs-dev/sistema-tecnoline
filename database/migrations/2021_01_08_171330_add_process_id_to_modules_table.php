<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProcessIdToModulesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('c_modules', function (Blueprint $table) {
      $table
        ->foreignId('process_id')
        ->unsigned()
        ->nullable()
        ->after('is_tray');
      $table
        ->foreign('process_id')
        ->references('process_id')
        ->on('c_production_processes');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('c_modules', function (Blueprint $table) {
      $table->dropForeign('c_modules_process_id_foreign');
      $table->dropColumn('process_id');
    });
  }
}
