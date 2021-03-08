<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCCounterweights extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create("c_counterweights", function (Blueprint $table) {
      $table->id("counterweight_id");
      $table->string("counterweight", 50);
      $table->integer("product_id");
      $table->boolean("heat_seal")->default(0);
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
    Schema::dropIfExists("c_counterweights");
  }
}
