<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
    $user_rules = [];

    if ($this->user) {
      $user_rules['username'] = 'required|unique:c_users,username,' . $this->user->id;
      $user_rules['email'] = 'required|email|unique:d_user_detail,email,' . $this->user->id;
    } else {
      $user_rules['username'] = 'required';
      $user_rules['email'] = 'required';
    }

    $user_rules['password'] = 'required';
    $user_rules['shortName'] = 'required|string';
    $user_rules['fullName'] = 'required|string';
    $user_rules['admissionDate'] = 'required|date';
    $user_rules['extension'] = 'required';
    $user_rules['active'] = 'required|boolean';
    $user_rules['idDepartment'] = 'required|integer';
    $user_rules['permissions'] = 'required|array';

    return $user_rules;
  }

  public function messages()
  {
    return [
      'username.required' => 'El nombre de usuario es obligatorio.',
      'username.unique' => 'El nombre de usuario ya se encuentra en uso.',
      'password.required' => 'La contraseña es obligatoria.',
      'shortName.required' => 'El nombre corto es obligatorio.',
      'shortName.string' => 'El nombre corto no puede contener números.',
      'fullName.required' => 'El nombre completo es obligatorio.',
      'fullName.string' => 'El nombre completo no puede contener números.',
      'admissionDate.required' => 'La fecha de admisión es obligatoria.',
      'admissionDate.date' => 'La fecha no tiene un formato válido.',
      'extension.required' => 'La extensión es obligatoria.',
      'email.required' => 'El correo es obligatorio.',
      'email.email' => 'El correo no tiene un formato válido.',
      'idDepartment.required' => 'Debe asignar un departamento para el usuario.',
      'permissions.required' => 'Debe asignar al menos un permiso al usuario.',
    ];
  }
}
