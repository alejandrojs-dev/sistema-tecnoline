<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEDashboardsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('e_dashboards', function (Blueprint $table) {
      $table->id('dashboard_id');
      $table->string('name', 50);
      $table->text('description');
      $table->boolean('is_slide')->default(0);
      $table->boolean('is_row_data')->default(0);
      $table->boolean('is_in_carousel')->default(0);
      $table->string('component_name', 20)->nullable();
      $table->boolean('active')->default(1);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('e_dashboards');
  }
}
