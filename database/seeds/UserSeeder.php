<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $password = bcrypt('shades1');

    $users = [
      [
        'username' => 'oscar.cortes',
        'password' => $password,
        'isActive' => 1,
      ],
      [
        'username' => 'kevin.alejandre',
        'password' => $password,
        'isActive' => 1,
      ],
      [
        'username' => 'alejandro.loera',
        'password' => $password,
        'isActive' => 1,
      ],
    ];

    foreach ($users as $user) {
      User::create($user);
    }
  }
}
