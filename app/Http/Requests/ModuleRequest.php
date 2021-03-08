<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModuleRequest extends FormRequest
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
    $module_rules = [];

    if ($this->module) {
      $module_rules['name'] = 'required|unique:c_modules,name,' . $this->module->id;
    } else {
      $module_rules['name'] = 'required';
    }

    $module_rules['slug'] = 'required';
    $module_rules['path'] = 'required';
    $module_rules['order'] = 'required';

    return $module_rules;
  }

  public function messages()
  {
    return [
      'name.required' => 'El nombre del módulo es obligatorio.',
      'name.unique' => 'El nombre del módulo ya se encuentra en existencia.',
      'slug.required' => 'El slug del módulo es obligatorio.',
      'path.required' => 'La ruta del módulo es obligatoria.',
      'order.required' => 'El order del módulo es obligatorio.',
    ];
  }
}
