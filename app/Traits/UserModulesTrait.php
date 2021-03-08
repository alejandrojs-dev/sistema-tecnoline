<?php

namespace App\Traits;

use App\Models\Module;
use Tymon\JWTAuth\Facades\JWTAuth;

trait UserModulesTrait
{
  public function getUserModulesAttribute()
  {
    return $this->getModules();
  }

  public function getModules()
  {
    return $this->buildModules();
  }

  public function buildModules()
  {
    $modules_array = [];
    $parent_modules = Module::parent()
      ->orderBy('order')
      ->get();

    foreach ($parent_modules as $module) {
      if ($module->has('submodules') && $module->submodules->count() > 0) {
        $add = false;
        $submodules_tmp = [];
        $submodules = $module
          ->submodules()
          ->orderBy('order')
          ->get();

        $submodules_tmp = $this->recursivityOverSubModules($submodules, $module);

        if (count($submodules_tmp) > 0) {
          $add = true;
        }

        if ($add) {
          $modules_array[] = [
            'id' => $module->id,
            'name' => $module->name,
            'slug' => $module->slug,
            'path' => $module->path,
            'icon' => $module->icon,
            'active' => $module->active,
            'order' => $module->order,
            'submodules' => $submodules_tmp,
          ];
        }
      }
    }
    return $modules_array;
  }

  public function recursivityOverSubModules($submodules, $parent_module, $grand_parent_module = null)
  {
    $submodules_array = [];
    $submodules_tmp_r = [];
    $user = JWTAuth::user();

    foreach ($submodules as $submodule) {
      $slug = strpos($submodule->slug, '-') !== false ? str_replace('-', ' ', $submodule->slug) : $submodule->slug;
      if ($user && $user->hasPermissionTo($slug)) {
        $submodules_tmp_r = [
          'id' => $submodule->id,
          'name' => $submodule->name,
          'slug' => $submodule->slug,
          'path' =>
            $grand_parent_module !== null
              ? $grand_parent_module->path . $parent_module->path . $submodule->path
              : $parent_module->path . $submodule->path,
          'icon' => $submodule->icon,
          'active' => $submodule->active,
          'order' => $submodule->order,
          'isTray' => $submodule->is_tray,
          'isAuthTray' => $submodule->is_auth_tray,
          'process_id' => $submodule->process_id,
        ];
        if ($submodule->has('submodules') && $submodule->submodules->count() > 0) {
          $child_submodules = $this->recursivityOverSubModules($submodule->submodules, $submodule, $parent_module);
          $submodules_tmp_r['childSubmodules'] = $child_submodules;
        }
        $submodules_array[] = $submodules_tmp_r;
      }
    }
    return $submodules_array;
  }
}
