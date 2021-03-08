<?php

use Illuminate\Database\Seeder;
use App\Models\Process;

class ProcessSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    try {
      $perfileria = new Process();
      $perfileria->process = 'Perfilería';
      $perfileria->next_process_id = 2;
      $perfileria->is_active = 1;
      $perfileria->save();

      $cut = new Process();
      $cut->process = 'Corte';
      $cut->next_process_id = 3;
      $cut->is_active = 1;
      $cut->save();

      $assembling = new Process();
      $assembling->process = 'Armado';
      $assembling->next_process_id = 4;
      $assembling->is_active = 1;
      $assembling->save();

      $leveling = new Process();
      $leveling->process = 'Nivelación';
      $leveling->next_process_id = 5;
      $leveling->is_active = 1;
      $leveling->save();

      $packaging = new Process();
      $packaging->process = 'Empaquetado';
      $packaging->next_process_id = 5;
      $packaging->is_active = 1;
      $packaging->save();

      $shipment = new Process();
      $shipment->process = 'Embarque';
      $shipment->next_process_id = 0;
      $shipment->is_active = 1;
      $shipment->save();
    } catch (Exception $e) {
      echo $e->getMessage();
    }
  }
}
