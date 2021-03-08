<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('c_orders', function (Blueprint $table) {
      $table->id('order_id');
      $table->foreignId('client_id')->unsigned();
      $table->string('client_reference', 100);
      $table->string('client_account_number', 50);
      $table->foreignId('agent_id')->unsigned();
      $table->foreignId('order_status_id')->unsigned();
      $table->foreignId('pay_method_id')->unsigned();
      $table->foreignId('cfdi_id')->unsigned();
      $table->foreignId('delivery_type_id')->unsigned();
      $table->foreignId('shipment_type_id')->unsigned();
      $table
        ->foreignId('delivery_service_id')
        ->unsigned()
        ->nullable();
      $table
        ->foreignId('shipping_address_id')
        ->unsigned()
        ->nullable();
      $table
        ->foreignId('invoice_id')
        ->unsigned()
        ->nullable();
      $table->foreignId('factory_id')->unsigned();
      $table
        ->foreignId('quotation_id')
        ->unsigned()
        ->nullable();
      $table->text('comments');
      $table->timestamps();

      $table
        ->foreign('client_id')
        ->references('client_id')
        ->on('c_clients');
      $table
        ->foreign('agent_id')
        ->references('agent_id')
        ->on('c_agents');
      $table
        ->foreign('order_status_id')
        ->references('order_status_id')
        ->on('c_order_status');
      $table
        ->foreign('pay_method_id')
        ->references('pay_method_id')
        ->on('c_pay_methods');
      $table
        ->foreign('cfdi_id')
        ->references('cfdi_id')
        ->on('c_cfdi');
      $table
        ->foreign('delivery_type_id')
        ->references('delivery_type_id')
        ->on('c_delivery_types');
      $table
        ->foreign('shipment_type_id')
        ->references('shipment_type_id')
        ->on('c_shipment_types');
      $table
        ->foreign('delivery_service_id')
        ->references('delivery_service_id')
        ->on('c_delivery_services');
      $table
        ->foreign('shipping_address_id')
        ->references('shipping_address_id')
        ->on('c_shipping_addresses');
      $table
        ->foreign('factory_id')
        ->references('factory_id')
        ->on('c_factories');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('c_orders');
  }
}
