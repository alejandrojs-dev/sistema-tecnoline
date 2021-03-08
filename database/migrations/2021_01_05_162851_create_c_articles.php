<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCArticles extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('c_articles', function (Blueprint $table) {
      $table->id('article_id');
      $table->string('article_key', 150)->nullable();
      $table->string('warehouse_key', 150)->nullable();
      $table->string('article', 250);
      $table->string('model', 150);
      $table->integer('product_id')->unsigned();
      $table->integer('channels')->unsigned();
      $table->double('fabric_discount')->nullable();
      $table->double('min_width')->nullable();
      $table->double('max_width')->nullable();
      $table->double('min_height')->nullable();
      $table->double('max_height')->nullable();
      $table->boolean('inverted')->default(0);
      $table->double('inverted_width');
      $table->double('inverted_height');
      $table->boolean('inverted_gar')->default(1);
      $table->double('roll_height')->nullable();
      $table->boolean('heat_seal')->default(0);
      $table->string('unity', 50)->nullable();
      $table->double('cost')->nullable();
      $table->double('price')->nullable();
      $table->boolean('is_stockout')->default(0);
      $table->date('stockout_date')->nullable();
      $table->boolean('stockout_op1')->default(0);
      $table->boolean('stockout_op2')->default(0);
      $table->boolean('stockout_op3')->default(0);
      $table->boolean('is_active')->default(1);
      $table->boolean('is_partner')->default(0);
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
    Schema::dropIfExists('c_articles');
  }
}
