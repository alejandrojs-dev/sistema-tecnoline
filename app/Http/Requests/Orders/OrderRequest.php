<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    $order_rules = [];

    $order_rules['clientId'] = 'required';
    $order_rules['clientReference'] = 'required';
    $order_rules['clientAccountNumber'] = 'required';
    $order_rules['agentId'] = 'required';
    //$order_rules['orderStatusId'] = 'required';
    $order_rules['payMethodId'] = 'required';
    $order_rules['cfdiId'] = 'required';
    $order_rules['deliveryTypeId'] = 'required';
    $order_rules['deliveryServiceId'] = 'required';
    $order_rules['shippingAddressId'] = 'required';
    $order_rules['factoryId'] = 'required';
    //$order_rules['comments'] = 'required';

    return $order_rules;
  }

  public function messages()
  {
    return [
      'clientId.required' => 'Seleccione el cliente',
      'clienteReference.required' => 'Establezca una referencia al pedido',
      'clientAccountNumber.required' => 'El número de cuenta del cliente es obligatorio',
      'agentId.required' => 'Seleccione el agente',
      //'orderStatusId.required' => 'Asigne un agente al pedido',
      'payMethodId.required' => 'Seleccione el método de pago',
      'cfdiId.required' => 'Seleccione el cfdi',
      'deliveryTypeId.required' => 'Seleccione el tipo de entrega',
      'deliveryServiceId.required' => 'Seleccione la fletera',
      'shippingAddressId.required' => 'Seleccione la dirección de envío',
      'factoryId.required' => 'Selecciona la fábrica',
      'agentId.required' => 'Asigne un agente al pedido',
      //'comments.required' => 'Los comentarios son obligatorios',
    ];
  }
}
