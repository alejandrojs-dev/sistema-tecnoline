<?php

use Illuminate\Database\Seeder;
use App\Models\Module;

class ModuleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    try {
      //Tickets
      $tickets_module = new Module();
      $tickets_module->name = 'Tickets';
      $tickets_module->slug = 'tickets';
      $tickets_module->path = '/tickets';
      $tickets_module->order = 1;
      $tickets_module->icon = 'mdi-ticket';
      $tickets_module->parent_id = 0;
      $tickets_module->save();

      $create_ticket_submodule = new Module();
      $create_ticket_submodule->name = 'Crear ticket';
      $create_ticket_submodule->slug = 'crear-ticket';
      $create_ticket_submodule->path = '/crear-ticket';
      $create_ticket_submodule->order = 1;
      $create_ticket_submodule->parent_id = $tickets_module->id;
      $create_ticket_submodule->icon = 'mdi-plus-circle';
      $create_ticket_submodule->save();

      $tickets_tray_submodule = new Module();
      $tickets_tray_submodule->name = 'Bandeja de tickets';
      $tickets_tray_submodule->slug = 'bandeja-de-tickets';
      $tickets_tray_submodule->path = '/bandeja';
      $tickets_tray_submodule->order = 2;
      $tickets_tray_submodule->parent_id = $tickets_module->id;
      $tickets_tray_submodule->icon = 'mdi-tray-full';
      $tickets_tray_submodule->is_tray = 1;
      $tickets_tray_submodule->save();

      $auth_tickets_tray_submodule = new Module();
      $auth_tickets_tray_submodule->name = 'Tickets por autorizar';
      $auth_tickets_tray_submodule->slug = 'tickets-por-autorizar';
      $auth_tickets_tray_submodule->path = '/por-autorizar';
      $auth_tickets_tray_submodule->order = 3;
      $auth_tickets_tray_submodule->parent_id = $tickets_module->id;
      $auth_tickets_tray_submodule->icon = 'mdi-check-bold';
      $auth_tickets_tray_submodule->is_auth_tray = 1;
      $auth_tickets_tray_submodule->save();

      $tools_tickets_submodule = new Module();
      $tools_tickets_submodule->name = 'Herramientas';
      $tools_tickets_submodule->slug = 'herramientas';
      $tools_tickets_submodule->path = '/herramientas';
      $tools_tickets_submodule->order = 4;
      $tools_tickets_submodule->parent_id = $tickets_module->id;
      $tools_tickets_submodule->icon = 'mdi-tools';
      $tools_tickets_submodule->save();

      $create_ticket_group_submodule = new Module();
      $create_ticket_group_submodule->name = 'Crear grupo de ticket';
      $create_ticket_group_submodule->slug = 'crear-grupo-de-ticket';
      $create_ticket_group_submodule->path = '/crear-grupo-de-ticket';
      $create_ticket_group_submodule->order = 5;
      $create_ticket_group_submodule->parent_id = $tools_tickets_submodule->id;
      $create_ticket_group_submodule->icon = 'mdi-plus-circle';
      $create_ticket_group_submodule->save();

      $see_ticket_groups_submodule = new Module();
      $see_ticket_groups_submodule->name = 'Ver grupos de ticket';
      $see_ticket_groups_submodule->slug = 'ver-grupos-de-ticket';
      $see_ticket_groups_submodule->path = '/ver-grupos-de-ticket';
      $see_ticket_groups_submodule->order = 6;
      $see_ticket_groups_submodule->parent_id = $tools_tickets_submodule->id;
      $see_ticket_groups_submodule->icon = 'mdi-eye';
      $see_ticket_groups_submodule->save();

      $create_ticket_subgroup_submodule = new Module();
      $create_ticket_subgroup_submodule->name = 'Crear subgrupo de ticket';
      $create_ticket_subgroup_submodule->slug = 'crear-subgrupo-de-ticket';
      $create_ticket_subgroup_submodule->path = '/crear-subgrupo-de-ticket';
      $create_ticket_subgroup_submodule->order = 7;
      $create_ticket_subgroup_submodule->parent_id = $tools_tickets_submodule->id;
      $create_ticket_subgroup_submodule->icon = 'mdi-plus-circle';
      $create_ticket_subgroup_submodule->save();

      $see_ticket_subgroups_submodule = new Module();
      $see_ticket_subgroups_submodule->name = 'Ver subgrupos de ticket';
      $see_ticket_subgroups_submodule->slug = 'ver-subgrupos-de-ticket';
      $see_ticket_subgroups_submodule->path = '/ver-subgrupos-de-ticket';
      $see_ticket_subgroups_submodule->order = 8;
      $see_ticket_subgroups_submodule->parent_id = $tools_tickets_submodule->id;
      $see_ticket_subgroups_submodule->icon = 'mdi-eye';
      $see_ticket_subgroups_submodule->save();

      //Production
      $production_module = new Module();
      $production_module->name = 'Producción';
      $production_module->slug = 'produccion';
      $production_module->path = '/produccion';
      $production_module->order = 2;
      $production_module->icon = 'mdi-hammer';
      $production_module->parent_id = 0;
      $production_module->save();

      $processes_submodule = new Module();
      $processes_submodule->name = 'Procesos';
      $processes_submodule->slug = 'procesos';
      $processes_submodule->path = '/procesos';
      $processes_submodule->order = 1;
      $processes_submodule->parent_id = $production_module->id;
      $processes_submodule->icon = 'mdi-cogs';
      $processes_submodule->save();

      $processes_information_submodule = new Module();
      $processes_information_submodule->name = 'Información de pedidos';
      $processes_information_submodule->slug = 'informacion-de-pedidos';
      $processes_information_submodule->path = '/informacion-de-pedidos';
      $processes_information_submodule->order = 1;
      $processes_information_submodule->parent_id = $processes_submodule->id;
      $processes_information_submodule->icon = 'mdi-cogs';
      $processes_information_submodule->save();

      $perfileria_submodule = new Module();
      $perfileria_submodule->name = 'Perfilería';
      $perfileria_submodule->slug = 'perfileria';
      $perfileria_submodule->path = '/perfileria';
      $perfileria_submodule->order = 1;
      $perfileria_submodule->parent_id = $processes_submodule->id;
      $perfileria_submodule->process_id = 1;
      $perfileria_submodule->icon = 'mdi-box-cutter';
      $perfileria_submodule->save();

      $cut_submodule = new Module();
      $cut_submodule->name = 'Corte';
      $cut_submodule->slug = 'corte';
      $cut_submodule->path = '/corte';
      $cut_submodule->order = 2;
      $cut_submodule->parent_id = $processes_submodule->id;
      $cut_submodule->process_id = 2;
      $cut_submodule->icon = 'mdi-scissors-cutting';
      $cut_submodule->save();

      $assembling_submodule = new Module();
      $assembling_submodule->name = 'Armado';
      $assembling_submodule->slug = 'armado';
      $assembling_submodule->path = '/armado';
      $assembling_submodule->order = 3;
      $assembling_submodule->parent_id = $processes_submodule->id;
      $assembling_submodule->process_id = 3;
      $assembling_submodule->icon = 'mdi-cube';
      $assembling_submodule->save();

      $leveling_submodule = new Module();
      $leveling_submodule->name = 'Nivelación';
      $leveling_submodule->slug = 'nivelacion';
      $leveling_submodule->path = '/nivelacion';
      $leveling_submodule->order = 4;
      $leveling_submodule->parent_id = $processes_submodule->id;
      $leveling_submodule->process_id = 4;
      $leveling_submodule->icon = 'mdi-pencil-ruler';
      $leveling_submodule->save();

      $packaging_submodule = new Module();
      $packaging_submodule->name = 'Empaquetado';
      $packaging_submodule->slug = 'empaquetado';
      $packaging_submodule->path = '/empaquetado';
      $packaging_submodule->order = 6;
      $packaging_submodule->parent_id = $processes_submodule->id;
      $packaging_submodule->process_id = 5;
      $packaging_submodule->icon = 'mdi-package-variant';
      $packaging_submodule->save();

      $shipment_submodule = new Module();
      $shipment_submodule->name = 'Embarque';
      $shipment_submodule->slug = 'embarque';
      $shipment_submodule->path = '/embarque';
      $shipment_submodule->order = 7;
      $shipment_submodule->parent_id = $processes_submodule->id;
      $shipment_submodule->process_id = 6;
      $shipment_submodule->icon = 'mdi-dump-truck';
      $shipment_submodule->save();

      $tools_production_submodule = new Module();
      $tools_production_submodule->name = 'Herramientas Producción';
      $tools_production_submodule->slug = 'herramientas-produccion';
      $tools_production_submodule->path = '/herramientas';
      $tools_production_submodule->order = 8;
      $tools_production_submodule->parent_id = $processes_submodule->id;
      $tools_production_submodule->icon = 'mdi-tools';
      $tools_production_submodule->save();

      $dashboard_submodule = new Module();
      $dashboard_submodule->name = 'Dashboard';
      $dashboard_submodule->slug = 'dashboard';
      $dashboard_submodule->path = '/dashboard';
      $dashboard_submodule->order = 9;
      $dashboard_submodule->parent_id = $processes_submodule->id;
      $dashboard_submodule->icon = 'mdi-view-dashboard';
      $dashboard_submodule->save();

      //Business Intelligence
      $business_intelligence_module = new Module();
      $business_intelligence_module->name = 'Business Intelligence';
      $business_intelligence_module->slug = 'business-intelligence';
      $business_intelligence_module->path = '/business-intelligence';
      $business_intelligence_module->order = 3;
      $business_intelligence_module->parent_id = 0;
      $business_intelligence_module->icon = 'mdi-file-chart';
      $business_intelligence_module->save();

      $my_dashboards_submodule = new Module();
      $my_dashboards_submodule->name = 'Mis dashboards';
      $my_dashboards_submodule->slug = 'mis-dashboards';
      $my_dashboards_submodule->path = '/mis-dashboards';
      $my_dashboards_submodule->order = 1;
      $my_dashboards_submodule->parent_id = $business_intelligence_module->id;
      $my_dashboards_submodule->icon = 'mdi-view-dashboard';
      $my_dashboards_submodule->save();

      $dashboard_admin_module = new Module();
      $dashboard_admin_module->name = 'Administración de dashboards';
      $dashboard_admin_module->slug = 'administracion-de-dashboards';
      $dashboard_admin_module->path = '/administracion-de-dashboards';
      $dashboard_admin_module->order = 2;
      $dashboard_admin_module->parent_id = $business_intelligence_module->id;
      $dashboard_admin_module->icon = 'mdi-briefcase';
      $dashboard_admin_module->save();
    } catch (Exception $exception) {
      echo $exception->getMessage();
    }
  }
}
