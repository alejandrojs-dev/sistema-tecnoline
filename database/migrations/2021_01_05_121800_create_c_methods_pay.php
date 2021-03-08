<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCMethodsPay extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create("c_pay_methods", function (Blueprint $table) {
      $table->id("pay_method_id");
      $table->string("pay_method", 50);
      $table->boolean("is_account")->default(0);
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
    Schema::dropIfExists("c_pay_methods");
  }
}
