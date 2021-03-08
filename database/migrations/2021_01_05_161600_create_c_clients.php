<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCClients extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('c_clients', function (Blueprint $table) {
      $table->id('client_id');
      $table->integer('user_id')->unsigned();
      $table->string('client_name', 150);
      $table->string('company', 250);
      $table->string('email', 100);
      $table->string('cellphone', 50);
      $table->integer('agent_id')->unsigned();
      $table->boolean('level')->default(1);
      $table->string('user_img', 150);
      $table->boolean('is_partner')->default(0);
      $table->boolean('is_line')->default(0);
      $table->boolean('is_invoice')->default(0);
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
    Schema::dropIfExists('c_clients');
  }
}
