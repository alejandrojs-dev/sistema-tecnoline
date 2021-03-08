<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCAwningTypes extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('c_awning_types', function (Blueprint $table) {
      $table->id('awning_type_id');
      $table->string('type', 50);
      $table->boolean('is_client')->default(1);
      $table->boolean('is_active')->default(1);
      $table->timestamp('date_create');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('c_awning_types');
  }
}
