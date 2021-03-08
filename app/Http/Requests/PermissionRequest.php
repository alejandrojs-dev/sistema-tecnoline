<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
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
    $permission_rules = [];

    if ($this->permission) {
      $permission_rules['name'] = 'required|unique:c_permissions,name,' . $this->permission->id;
    } else {
      $permission_rules['name'] = 'required';
    }

    $permission_rules['active'] = 'boolean';
    return $permission_rules;
  }

  public function messages()
  {
    return [
      'name.required' => 'El nombre del permiso es obligatorio',
      'name.unique' => 'El nombre del permiso ya está en uso',
    ];
  }
}
