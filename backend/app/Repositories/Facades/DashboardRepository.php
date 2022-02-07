<?php 
namespace App\Repositories\Facades;

use Illuminate\Support\Facades\Facade;

class DashboardRepository extends Facade {
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor() { return new \App\Repositories\DashboardRepository; }
}
