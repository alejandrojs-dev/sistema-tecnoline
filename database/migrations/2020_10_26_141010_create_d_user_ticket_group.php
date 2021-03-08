<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDUserTicketGroup extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('d_user_ticket_group', function (Blueprint $table) {
      $table->foreignId('id_ticket_group')->unsigned();
      $table->foreignId('id_user')->unsigned();
      $table
        ->foreign('id_ticket_group')
        ->references('id_ticket_group')
        ->on('c_ticket_groups')
        ->onDelete('cascade');
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
    Schema::dropIfExists('d_user_ticket_group');
  }
}
