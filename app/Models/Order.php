<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrderDetail;
use App\Models\Client;
use App\Models\Agent;
use App\Models\OrderStatus;
use App\Models\PayMethod;
use App\Models\Cfdi;
use App\Models\DeliveryType;
use App\Models\ShipmentType;
use App\Models\DeliveryService;
use App\Models\ShippingAddress;
use App\Models\Factory;

class Order extends Model
{
  protected $table = 'c_orders';
  protected $primaryKey = 'order_id';

  //Relations
  public function details()
  {
    return $this->hasMany(OrderDetail::class, 'order_id');
  }

  public function client()
  {
    return $this->belongsTo(Client::class, 'client_id');
  }

  public function agent()
  {
    return $this->belongsTo(Agent::class, 'agent_id');
  }

  public function status()
  {
    return $this->hasOne(OrderStatus::class, 'order_status_id');
  }

  public function pay_method()
  {
    return $this->hasOne(PayMethod::class, 'pay_method_id');
  }

  public function cfdi()
  {
    return $this->hasOne(Cfdi::class, 'cfdi_id');
  }

  public function delivery_type()
  {
    return $this->hasOne(DeliveryType::class, 'delivery_type_id');
  }

  public function shipment_type()
  {
    return $this->hasOne(ShipmentType::class, 'shipment_type_id');
  }

  public function delivery_service()
  {
    return $this->hasOne(DeliveryService::class, 'delivery_service_id');
  }

  public function shipping_address()
  {
    return $this->hasOne(ShippingAddress::class, 'shipping_address_id');
  }

  public function factory()
  {
    return $this->belongsTo(Factory::class, 'factory_id');
  }

  //Accesors
  public function getOrderDetailAttribute()
  {
    return $this->details()->get();
  }
}
