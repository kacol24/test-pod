<?php 
namespace App\Library\Facades;

use Illuminate\Support\Facades\Facade;

class Shopee extends Facade {
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor() { return new \App\Library\Shopee; }
}
