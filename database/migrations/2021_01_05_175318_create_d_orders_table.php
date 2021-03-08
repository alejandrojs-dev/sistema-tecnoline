<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDOrdersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('d_orders', function (Blueprint $table) {
      $table->id('order_detail_id');
      $table->foreignId('order_id')->unsigned();
      $table->integer('item_id')->unsigned();
      $table->foreignId('product_id')->unsigned();
      $table->string('operation', 50);
      $table->string('fall', 20);
      $table->foreignId('counterweight_id')->unsigned();
      $table->integer('quantity')->unsigned();
      $table->string('chain', 15);
      $table->foreignId('article_id')->unsigned();
      $table->string('control', 20);
      $table->double('width');
      $table->double('height');
      $table->foreignId('unity_id')->unsigned();
      $table->double('price');
      $table->double('height_chain');
      $table->boolean('inverted')->default(0);
      $table->double('discount_1');
      $table->double('discount_2');
      $table->double('discount_3');
      $table->foreignId('components_color_id')->unsigned();
      $table->foreignId('cassette_color_id')->unsigned();
      $table->text('area');
      $table->text('comments');
      $table->foreignId('awning_type_id')->unsigned();
      $table->integer('bracket')->unsigned();
      $table->integer('cassette')->unsigned();
      $table->integer('tie')->unsigned();
      $table->integer('close')->unsigned();
      $table->integer('relation_id')->unsigned();
      $table->timestamps();

      $table
        ->foreign('order_id')
        ->references('order_id')
        ->on('c_orders')
        ->onDelete('cascade');
      $table
        ->foreign('product_id')
        ->references('product_id')
        ->on('c_products');
      $table
        ->foreign('counterweight_id')
        ->references('counterweight_id')
        ->on('c_counterweights');
      $table
        ->foreign('article_id')
        ->references('article_id')
        ->on('c_articles');
      $table
        ->foreign('components_color_id')
        ->references('color_id')
        ->on('c_colors');
      $table
        ->foreign('cassette_color_id')
        ->references('color_id')
        ->on('c_colors');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('d_orders');
  }
}
