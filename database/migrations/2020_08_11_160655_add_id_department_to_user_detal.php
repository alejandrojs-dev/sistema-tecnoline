<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdDepartmentToUserDetal extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('d_user_detail', function (Blueprint $table) {
      $table
        ->foreignId('id_department')
        ->unsigned()
        ->after('id_user');
      $table
        ->foreign('id_department')
        ->references('id_department')
        ->on('c_departments');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('d_user_detail', function (Blueprint $table) {
      $table->dropForeign('d_user_detail_id_department_foreign');
      $table->dropColumn('id_department');
    });
  }
}
