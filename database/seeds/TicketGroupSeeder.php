<?php

use App\Models\TicketGroup;
use Illuminate\Database\Seeder;

class TicketGroupSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $IT_ticket_group = new TicketGroup();
    $IT_ticket_group->name = 'IT';
    $IT_ticket_group->save();

    $SD_ticket_group = new TicketGroup();
    $SD_ticket_group->name = 'SD';
    $SD_ticket_group->save();

    $SAC_ticket_group = new TicketGroup();
    $SAC_ticket_group->name = 'SAC';
    $SAC_ticket_group->save();

    $Admin_ticket_group = new TicketGroup();
    $Admin_ticket_group->name = 'Admin';
    $Admin_ticket_group->save();

    $RH_ticket_group = new TicketGroup();
    $RH_ticket_group->name = 'RH';
    $RH_ticket_group->save();
  }
}
