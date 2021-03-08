<?php

namespace App\Http\Requests\Dashboards;

use Illuminate\Foundation\Http\FormRequest;

class DashboardRequest extends FormRequest
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
    $dashboard_rules = [];
    $dashboard_data = $this->all();
    $key_exists = array_key_exists('dashboard_id', $dashboard_data);

    if ($key_exists) {
      $dashboard_rules['name'] = 'required|unique:e_dashboards,name,' . $this->dashboard->dashboard_id;
    } else {
      $dashboard_rules['name'] = 'required';
    }

    $dashboard_rules['description'] = 'required';
    $dashboard_rules['allowedDepartments'] = 'required';

    return $dashboard_rules;
  }

  public function messages()
  {
    return [
      'name.required' => 'El nombre es obligatorio',
      'name.unique' => 'El nombre ya se encuentra en uso',
      'description.required' => 'La descripción es obligatoria',
      'allowedDepartments.required' => 'Debe asignar al menos un departamento al dashboard',
    ];
  }
}
