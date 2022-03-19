<?php

namespace App\Repositories;

use DB, Mail;
use App\Repositories\Facades\Tokopedia;
use App\Repositories\Facades\Shopee;

class Product {
  function publish($product) {
    if($product->platform('tokopedia')) {
      Tokopedia::publish($product);
    }
    if($product->platform('shopee')) {
      Shopee::publish($product);
    }
  }

  function unpublish($product) {
    if($product->platform('tokopedia')) {
      Tokopedia::unpublish($product);
    }
    if($product->platform('shopee')) {
      Shopee::unpublish($product);
    }
  }

  function delete($product) {
    if($product->platform('tokopedia')) {
      Tokopedia::delete($product);
    }
    if($product->platform('shopee')) {
      Shopee::delete($product);
    }
  }

  function updateStock($product) {
    if($product->platform('tokopedia')) {
      Tokopedia::updateStock($product);
    }
    if($product->platform('shopee')) {
      Shopee::updateStock($product);
    }
  }
}