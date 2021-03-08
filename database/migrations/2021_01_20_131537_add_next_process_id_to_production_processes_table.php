<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNextProcessIdToProductionProcessesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('c_production_processes', function (Blueprint $table) {
      $table
        ->integer('next_process_id')
        ->unsigned()
        ->after('process');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('c_production_processes', function (Blueprint $table) {
      $table->dropColumn('next_process_id');
    });
  }
}
