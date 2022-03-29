<?php 
namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

class Prism extends Facade {
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor() { return new \App\Services\Prism; }
}
