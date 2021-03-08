<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCChecksTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('c_checks', function (Blueprint $table) {
      $table->id('check_id');
      $table->text('check_description');
      $table->boolean('is_required')->default(1);
      $table->boolean('is_active')->default(1);
      $table->foreignId('process_id')->unsigned();

      $table
        ->foreign('process_id')
        ->references('process_id')
        ->on('c_production_processes');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('c_checks');
  }
}
