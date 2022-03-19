<?php

namespace App\Repositories;

use DB, Mail;
use App\Repositories\Facades\Tokopedia;
use App\Repositories\Facades\Shopee;

class Product {
  function updateStock($product) {
    if($product->platform('tokopedia')) {
      Tokopedia::updateStock($product);
    }
    if($product->platform('shopee')) {
      Shopee::updateStock($product);
    }
  }
}