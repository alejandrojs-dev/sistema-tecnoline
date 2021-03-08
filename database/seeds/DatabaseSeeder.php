<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    $this->call([
      DepartmentSeeder::class,
      ProcessSeeder::class,
      ModuleSeeder::class,
      TicketGroupSeeder::class,
      RolesPermissionsSeeder::class,
    ]);
  }
}
