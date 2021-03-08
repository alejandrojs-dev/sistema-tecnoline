<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
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
    $department_rules = [];

    if ($this->department) {
      $department_rules['name'] = 'required|unique:c_departments,name,' . $this->department->id_department;
    } else {
      $department_rules['name'] = 'required';
    }

    return $department_rules;
  }

  public function messages()
  {
    return [
      'name.required' => 'El nombre del departamento es obligatorio',
      'name.unique' => 'El nombre del departamento ya está en uso',
    ];
  }
}
