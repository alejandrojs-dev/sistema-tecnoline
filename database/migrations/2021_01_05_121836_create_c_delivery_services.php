<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCDeliveryServices extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create("c_delivery_services", function (Blueprint $table) {
      $table->id("delivery_service_id");
      $table->string("delivery_service", 50);
      $table->integer("delivery_order");
      $table->boolean("is_active")->default(1);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists("c_delivery_services");
  }
}
