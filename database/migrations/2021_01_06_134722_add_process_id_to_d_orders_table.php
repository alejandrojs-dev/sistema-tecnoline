<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProcessIdToDOrdersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('d_orders', function (Blueprint $table) {
      $table
        ->foreignId('process_id')
        ->unsigned()
        ->after('relation_id');
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
    Schema::table('d_orders', function (Blueprint $table) {
      $table->dropForeign('d_orders_process_id_foreign');
      $table->dropColumn('process_id');
    });
  }
}
