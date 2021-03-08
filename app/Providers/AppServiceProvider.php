<?php

namespace App\Providers;

use App\Http\Controllers\Orders\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboards\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Processes\ProcessController;
use App\Services\AuthService;
use App\Services\UserService;
use App\Services\DepartmentService;
use App\Services\ModuleService;
use App\Services\PermissionService;
use App\Services\Orders\OrderService;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\IBaseService;
use App\Services\Processes\ProcessService;
use App\Services\Dashboards\DashboardService;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    $this->app->bind(UserController::class, function ($app) {
      return new UserController(new UserService());
    });

    $this->app->bind(AuthController::class, function ($app) {
      return new AuthController(new AuthService());
    });

    $this->app->bind(DepartmentController::class, function ($app) {
      return new DepartmentController(new DepartmentService());
    });

    $this->app->bind(ModuleController::class, function ($app) {
      return new ModuleController(new ModuleService());
    });

    $this->app->bind(PermissionController::class, function ($app) {
      return new PermissionController(new PermissionService());
    });

    $this->app->bind(OrderController::class, function ($app) {
      return new OrderController(new OrderService());
    });

    $this->app->bind(ProcessController::class, function ($app) {
      return new ProcessController(new ProcessService());
    });

    $this->app->bind(DashboardController::class, function ($app) {
      return new DashboardController(new DashboardService());
    });

    $this->app->bind(IBaseService::class, OrderService::class);

    $auth_service = resolve(AuthController::class);
    $user_service = resolve(UserController::class);
    $department_service = resolve(DepartmentController::class);
    $module_service = resolve(ModuleController::class);
    $permission_service = resolve(PermissionController::class);
    $order_service = resolve(OrderController::class);
    $process_service = resolve(ProcessController::class);
    $dashboard_service = resolve(DashboardController::class);
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    //
  }
}
