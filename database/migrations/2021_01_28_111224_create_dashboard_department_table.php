<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDashboardDepartmentTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('d_dashboard_department', function (Blueprint $table) {
      $table->id();
      $table->foreignId('dashboard_id')->unsigned();
      $table->foreignId('department_id')->unsigned();
      $table
        ->foreign('dashboard_id')
        ->references('dashboard_id')
        ->on('e_dashboards')
        ->onDelete('cascade');
      $table
        ->foreign('department_id')
        ->references('id_department')
        ->on('c_departments')
        ->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('d_dashboard_department');
  }
}
