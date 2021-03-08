<?php

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $departments = [
      [
        'name' => 'Desarrollo de software',
        'abbreviation' => 'SD',
        'icon' => 'mdi-monitor',
        'active' => 1,
      ],
      [
        'name' => 'Tecnologías de la información',
        'abbreviation' => 'IT',
        'icon' => 'mdi-access-point',
        'active' => 1,
      ],
      [
        'name' => 'Servicio a clientes',
        'abbreviation' => 'SAC',
        'icon' => 'mdi-account-group',
        'active' => 1,
      ],
      [
        'name' => 'Almacén',
        'abbreviation' => 'ALMN',
        'icon' => 'mdi-warehouse',
        'active' => 1,
      ],
      [
        'name' => 'Producción',
        'abbreviation' => 'PDN',
        'icon' => 'mdi-hammer',
        'active' => 1,
      ],
      [
        'name' => 'Oficinas',
        'abbreviation' => 'OFNS',
        'icon' => 'mdi-office-building',
        'active' => 1,
      ],
      [
        'name' => 'Shades',
        'abbreviation' => 'SHD',
        'icon' => 'mdi-account-tie',
        'active' => 1,
      ],
      [
        'name' => 'Tecnoline',
        'abbreviation' => 'TNL',
        'icon' => 'mdi-account-tie',
        'active' => 1,
      ],
    ];

    foreach ($departments as $department) {
      Department::create($department);
    }
  }
}
