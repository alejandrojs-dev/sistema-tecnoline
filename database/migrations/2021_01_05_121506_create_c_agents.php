<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCAgents extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create("c_agents", function (Blueprint $table) {
      $table->id("agent_id");
      $table->string("agent", 150);
      $table->string("name", 250);
      $table->string("full_name", 255);
      $table->string("position", 150);
      $table->integer("counter_account");
      $table->string("counter_agent", 150)->nullable();
      $table->integer("old_agent_id");
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
    Schema::dropIfExists("c_agents");
  }
}
