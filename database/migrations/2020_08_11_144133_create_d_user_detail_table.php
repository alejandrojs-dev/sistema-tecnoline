<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDUserDetailTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('d_user_detail', function (Blueprint $table) {
      $table->id();
      $table->string('short_name', 50);
      $table->string('full_name', 50);
      $table->date('admission_date');
      $table->string('extension', 10);
      $table->string('email', 100)->unique();
      $table->boolean('active')->default(1);
      $table->foreignId('id_user')->unsigned();
      $table->timestamps();

      $table
        ->foreign('id_user')
        ->references('id')
        ->on('c_users')
        ->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('d_user_detail');
  }
}
