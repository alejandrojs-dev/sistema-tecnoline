<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCShippingAddresses extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create("c_shipping_addresses", function (Blueprint $table) {
      $table->id("shipping_address_id");
      $table->integer("client_id");
      $table->string("identifier", 250);
      $table->string("contact_name", 250);
      $table->integer("postal_code");
      $table->string("street", 250);
      $table->string("internal_number", 50);
      $table->string("external_number", 50)->nullable();
      $table->string("suburb", 250);
      $table->string("city", 100);
      $table->string("state", 100);
      $table->string("telephone", 20);
      $table->string("short_reference", 250);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists("c_shipping_addresses");
  }
}
