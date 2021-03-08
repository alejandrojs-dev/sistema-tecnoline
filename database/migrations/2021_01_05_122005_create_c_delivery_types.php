<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCDeliveryTypes extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('c_delivery_types', function (Blueprint $table) {
      $table->id('delivery_type_id');
      $table->string('delivery_type', 10);
      $table->boolean('is_shipment')->default(0);
      $table->boolean('is_direction')->default(0);
      $table->boolean('is_active')->default(1);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('c_delivery_types');
  }
}
