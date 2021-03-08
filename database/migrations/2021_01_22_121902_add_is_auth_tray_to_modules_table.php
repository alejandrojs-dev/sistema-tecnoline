<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsAuthTrayToModulesTable extends Migration
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
        ->boolean('is_auth_tray')
        ->after('is_tray')
        ->default(0);
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
      $table->dropColumn('is_auth_tray');
    });
  }
}
