<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;
use App\Models\UserDetail;
use Carbon\Carbon;

class RolesPermissionsSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    try {
      app()[PermissionRegistrar::class]->forgetCachedPermissions();

      $password = 'shades1';
      //Tickets permissions
      $create_ticket_permission = Permission::create([
        'name' => 'crear ticket',
      ]);
      $tickets_tray_permission = Permission::create([
        'name' => 'bandeja de tickets',
      ]);
      $auth_tickets_tray_permission = Permission::create([
        'name' => 'tickets por autorizar',
      ]);
      $tools_ticket_permission = Permission::create(['name' => 'herramientas']);
      $see_ticket_groups_permission = Permission::create([
        'name' => 'ver grupos de ticket',
      ]);
      $create_ticket_group_permission = Permission::create([
        'name' => 'crear grupo de ticket',
      ]);
      $see_ticket_subgroups_permission = Permission::create([
        'name' => 'ver subgrupos de ticket',
      ]);
      $create_ticket_subgroup_permission = Permission::create([
        'name' => 'crear subgrupo de ticket',
      ]);

      //Business Intelligence permissions
      $my_dashboards_permission = Permission::create(['name' => 'mis dashboards']);
      $dashboard_admin_permission = Permission::create(['name' => 'administracion de dashboards']);

      //Roles iniciales
      $oscar_role = Role::create(['name' => 'oscar.cortes']);
      //$oscar_role->givePermissionTo($billing_permission);
      $oscar_role->givePermissionTo($create_ticket_permission);
      $oscar_role->givePermissionTo($tickets_tray_permission);
      $oscar_role->givePermissionTo($auth_tickets_tray_permission);
      $oscar_role->givePermissionTo($tools_ticket_permission);
      $oscar_role->givePermissionTo($see_ticket_groups_permission);
      $oscar_role->givePermissionTo($create_ticket_group_permission);
      $oscar_role->givePermissionTo($see_ticket_subgroups_permission);
      $oscar_role->givePermissionTo($create_ticket_subgroup_permission);
      $oscar_role->givePermissionTo($my_dashboards_permission);
      $oscar_role->givePermissionTo($dashboard_admin_permission);

      $alejandro_role = Role::create(['name' => 'alejandro.loera']);
      //$alejandro_role->givePermissionTo($billing_permission);
      $alejandro_role->givePermissionTo($create_ticket_permission);
      $alejandro_role->givePermissionTo($tickets_tray_permission);
      $alejandro_role->givePermissionTo($auth_tickets_tray_permission);
      $alejandro_role->givePermissionTo($tools_ticket_permission);
      $alejandro_role->givePermissionTo($see_ticket_groups_permission);
      $alejandro_role->givePermissionTo($create_ticket_group_permission);
      $alejandro_role->givePermissionTo($see_ticket_subgroups_permission);
      $alejandro_role->givePermissionTo($create_ticket_subgroup_permission);
      $alejandro_role->givePermissionTo($my_dashboards_permission);
      $alejandro_role->givePermissionTo($dashboard_admin_permission);

      $daniel_role = Role::create(['name' => 'daniel.jimenez']);
      //$daniel_role->givePermissionTo($billing_permission);
      $daniel_role->givePermissionTo($create_ticket_permission);
      $daniel_role->givePermissionTo($tickets_tray_permission);
      $daniel_role->givePermissionTo($auth_tickets_tray_permission);
      $daniel_role->givePermissionTo($tools_ticket_permission);
      $daniel_role->givePermissionTo($see_ticket_groups_permission);
      $daniel_role->givePermissionTo($create_ticket_group_permission);
      $daniel_role->givePermissionTo($see_ticket_subgroups_permission);
      $daniel_role->givePermissionTo($create_ticket_subgroup_permission);
      $daniel_role->givePermissionTo($my_dashboards_permission);
      $daniel_role->givePermissionTo($dashboard_admin_permission);

      $oscar_user = new User();
      $oscar_user->username = 'oscar.cortes';
      $oscar_user->password = $password;
      $oscar_user->has_permission_tickets = 1;
      $oscar_user->save();
      $oscar_user->assignRole($oscar_role);
      $oscar_user->ticketGroups()->attach([1, 2, 3]);

      $oscar_user_detail = new UserDetail();
      $oscar_user_detail->short_name = 'Oscar Cortes';
      $oscar_user_detail->full_name = 'Oscar Eduardo Cortes';
      $oscar_user_detail->admission_date = Carbon::now();
      $oscar_user_detail->extension = '115';
      $oscar_user_detail->email = 'o.cortes@tecnolinemexico.com';
      $oscar_user_detail->active = 1;
      $oscar_user_detail->id_user = $oscar_user->id;
      $oscar_user_detail->id_department = 1;
      $oscar_user_detail->save();

      $alejandro_user = new User();
      $alejandro_user->username = 'alejandro.loera';
      $alejandro_user->password = $password;
      $alejandro_user->has_permission_tickets = 1;
      $alejandro_user->save();
      $alejandro_user->assignRole($alejandro_role);
      $alejandro_user->ticketGroups()->attach([2, 3]);

      $alejandro_user_detail = new UserDetail();
      $alejandro_user_detail->short_name = 'Alejandro Loera';
      $alejandro_user_detail->full_name = 'Israel Alejandro Loera Pérez';
      $alejandro_user_detail->admission_date = Carbon::now();
      $alejandro_user_detail->extension = '133';
      $alejandro_user_detail->email = 'a.loera@tecnolinemexico.com';
      $alejandro_user_detail->active = 1;
      $alejandro_user_detail->id_user = $alejandro_user->id;
      $alejandro_user_detail->id_department = 1;
      $alejandro_user_detail->save();

      $daniel_user = new User();
      $daniel_user->username = 'daniel.jimenez';
      $daniel_user->password = $password;
      $daniel_user->has_permission_tickets = 1;
      $daniel_user->save();
      $daniel_user->assignRole($daniel_role);
      $daniel_user->ticketGroups()->attach(1);

      $daniel_user_detail = new UserDetail();
      $daniel_user_detail->short_name = 'Daniel Jimenez';
      $daniel_user_detail->full_name = 'José Daniel Jiménez Aguilar';
      $daniel_user_detail->admission_date = Carbon::now();
      $daniel_user_detail->extension = '129';
      $daniel_user_detail->email = 'daniel.jimenez@tecnolinemexico.com';
      $daniel_user_detail->active = 1;
      $daniel_user_detail->id_user = $daniel_user->id;
      $daniel_user_detail->id_department = 2;
      $daniel_user_detail->save();

      //Production permissions
      $processes_permission = Permission::create(['name' => 'procesos']);
      $perfileria_permission = Permission::create(['name' => 'perfileria']);
      $cut_permission = Permission::create(['name' => 'corte']);
      $assembling_permission = Permission::create(['name' => 'armado']);
      $leveling_permission = Permission::create(['name' => 'nivelacion']);
      $packaging_permission = Permission::create(['name' => 'empaquetado']);
      $shipment_permission = Permission::create(['name' => 'embarque']);
      $tools_production_permission = Permission::create([
        'name' => 'herramientas produccion',
      ]);
      $dashboard_permission = Permission::create(['name' => 'dashboard']);
      $processes_information_permission = Permission::create([
        'name' => 'informacion de pedidos',
      ]);

      //Production roles
      $user_1_role = Role::create(['name' => 'usuario1']);
      $user_2_role = Role::create(['name' => 'usuario2']);
      $user_3_role = Role::create(['name' => 'usuario3']);
      $user_4_role = Role::create(['name' => 'usuario4']);
      $user_5_role = Role::create(['name' => 'usuario5']);
      $user_6_role = Role::create(['name' => 'usuario6']);
      $rafael_role = Role::create(['name' => 'rafael.camacho']);

      $user_1_role->givePermissionTo($processes_permission);
      $user_1_role->givePermissionTo($perfileria_permission);

      $user_2_role->givePermissionTo($processes_permission);
      $user_2_role->givePermissionTo($cut_permission);

      $user_3_role->givePermissionTo($processes_permission);
      $user_3_role->givePermissionTo($assembling_permission);

      $user_4_role->givePermissionTo($processes_permission);
      $user_4_role->givePermissionTo($leveling_permission);

      $user_5_role->givePermissionTo($processes_permission);
      $user_5_role->givePermissionTo($packaging_permission);

      $user_6_role->givePermissionTo($processes_permission);
      $user_6_role->givePermissionTo($shipment_permission);

      $rafael_role->givePermissionTo($create_ticket_permission);
      $rafael_role->givePermissionTo($tickets_tray_permission);
      $rafael_role->givePermissionTo($processes_permission);
      $rafael_role->givePermissionTo($perfileria_permission);
      $rafael_role->givePermissionTo($cut_permission);
      $rafael_role->givePermissionTo($assembling_permission);
      $rafael_role->givePermissionTo($leveling_permission);
      $rafael_role->givePermissionTo($packaging_permission);
      $rafael_role->givePermissionTo($shipment_permission);
      $rafael_role->givePermissionTo($tools_production_permission);
      $rafael_role->givePermissionTo($dashboard_permission);
      $rafael_role->givePermissionTo($processes_information_permission);
      $rafael_role->givePermissionTo($my_dashboards_permission);

      $user_1 = new User();
      $user_1->username = 'usuario1';
      $user_1->password = 'usuario1';
      $user_1->save();
      $user_1->assignRole($user_1_role);

      $user_1_detail = new UserDetail();
      $user_1_detail->short_name = 'Usuario 1';
      $user_1_detail->full_name = 'Usuario 1';
      $user_1_detail->admission_date = Carbon::now();
      $user_1_detail->extension = '000';
      $user_1_detail->email = 'usuario1@tecnolinemexico.com';
      $user_1_detail->active = 1;
      $user_1_detail->id_user = $user_1->id;
      $user_1_detail->id_department = 5;
      $user_1_detail->save();

      $user_2 = new User();
      $user_2->username = 'usuario2';
      $user_2->password = 'usuario2';
      $user_2->save();
      $user_2->assignRole($user_2_role);

      $user_2_detail = new UserDetail();
      $user_2_detail->short_name = 'Usuario 2';
      $user_2_detail->full_name = 'Usuario 2';
      $user_2_detail->admission_date = Carbon::now();
      $user_2_detail->extension = '000';
      $user_2_detail->email = 'usuario2@tecnolinemexico.com';
      $user_2_detail->active = 1;
      $user_2_detail->id_user = $user_2->id;
      $user_2_detail->id_department = 5;
      $user_2_detail->save();

      $user_3 = new User();
      $user_3->username = 'usuario3';
      $user_3->password = 'usuario3';
      $user_3->save();
      $user_3->assignRole($user_3_role);

      $user_3_detail = new UserDetail();
      $user_3_detail->short_name = 'Usuario 3';
      $user_3_detail->full_name = 'Usuario 3';
      $user_3_detail->admission_date = Carbon::now();
      $user_3_detail->extension = '000';
      $user_3_detail->email = 'usuario3@tecnolinemexico.com';
      $user_3_detail->active = 1;
      $user_3_detail->id_user = $user_3->id;
      $user_3_detail->id_department = 5;
      $user_3_detail->save();

      $user_4 = new User();
      $user_4->username = 'usuario4';
      $user_4->password = 'usuario4';
      $user_4->save();
      $user_4->assignRole($user_4_role);

      $user_4_detail = new UserDetail();
      $user_4_detail->short_name = 'Usuario 4';
      $user_4_detail->full_name = 'Usuario 4';
      $user_4_detail->admission_date = Carbon::now();
      $user_4_detail->extension = '000';
      $user_4_detail->email = 'usuario4@tecnolinemexico.com';
      $user_4_detail->active = 1;
      $user_4_detail->id_user = $user_4->id;
      $user_4_detail->id_department = 5;
      $user_4_detail->save();

      $user_5 = new User();
      $user_5->username = 'usuario5';
      $user_5->password = 'usuario5';
      $user_5->save();
      $user_5->assignRole($user_5_role);

      $user_5_detail = new UserDetail();
      $user_5_detail->short_name = 'Usuario 5';
      $user_5_detail->full_name = 'Usuario 5';
      $user_5_detail->admission_date = Carbon::now();
      $user_5_detail->extension = '000';
      $user_5_detail->email = 'usuario5@tecnolinemexico.com';
      $user_5_detail->active = 1;
      $user_5_detail->id_user = $user_5->id;
      $user_5_detail->id_department = 5;
      $user_5_detail->save();

      $user_6 = new User();
      $user_6->username = 'usuario6';
      $user_6->password = 'usuario6';
      $user_6->save();
      $user_6->assignRole($user_6_role);

      $user_6_detail = new UserDetail();
      $user_6_detail->short_name = 'Usuario 6';
      $user_6_detail->full_name = 'Usuario 6';
      $user_6_detail->admission_date = Carbon::now();
      $user_6_detail->extension = '000';
      $user_6_detail->email = 'usuario6@tecnolinemexico.com';
      $user_6_detail->active = 1;
      $user_6_detail->id_user = $user_6->id;
      $user_6_detail->id_department = 5;
      $user_6_detail->save();

      $rafael_user = new User();
      $rafael_user->username = 'rafael.camacho';
      $rafael_user->password = 'jbtprod';
      $rafael_user->has_permission_tickets = 1;
      $rafael_user->save();
      $rafael_user->assignRole($rafael_role);

      $rafael_user_detail = new UserDetail();
      $rafael_user_detail->short_name = 'Rafael Camacho';
      $rafael_user_detail->full_name = 'Rafael Alejandro Camacho Torreblanca';
      $rafael_user_detail->admission_date = Carbon::now();
      $rafael_user_detail->extension = '000';
      $rafael_user_detail->email = 'operaciones@tecnolinemexico.com';
      $rafael_user_detail->active = 1;
      $rafael_user_detail->id_user = $rafael_user->id;
      $rafael_user_detail->id_department = 5;
      $rafael_user_detail->save();
    } catch (Exception $exception) {
      echo $exception->getMessage();
    }
  }
}
