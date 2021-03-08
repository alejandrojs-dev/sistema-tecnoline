<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTicketGroupIdToDUserDetailTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('d_user_detail', function (Blueprint $table) {
      // $table->foreignId('id_ticket_group')->unsigned()->after('id_department');
      // $table->foreign('id_ticket_group')
      //   ->references('id_ticket_group')
      //   ->on('c_ticket_groups');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('d_user_detail', function (Blueprint $table) {
      //$table->dropColumn('id_ticket_group');
    });
  }
}
