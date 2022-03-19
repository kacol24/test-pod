<?php

namespace App\Repositories;

use DB, Mail;
use App\Models\Order\Order as OrderModel;
use App\Services\Facades\Shopee as ShopeeService;
use App\Services\Facades\Tokopedia as TokopediaService;
use App\Repositories\Facades\Product;
use App\Jobs\CapacityUpdated;

class Order {
  function accept($order) {
    if($order->platform('tokopedia')) {
      TokopediaService::acceptOrder($order->platform('tokopedia')->platform_order_id);
    }
    foreach($order->details as $detail) {
      if($detail->product->master_product_id) {
        $mastersku = $detail->product->mastersku($detail->sku->option_detail_key1, $detail->sku->option_detail_key2);
        $mastersku->stock -= $detail->quantity;
        $mastersku->save();
        $capacity = $detail->product->masterproduct->capacity;
        $capacity->capacity -= $detail->quantity;
        $capacity->save();
        CapacityUpdated::dispatch($capacity->id);
      }else {
        $detail->sku->stock -= $detail->quantity;
        $detail->sku->save();
        Product::updateStock($detail->product);
      }
    }
    $order->store->balance -= $order->final_amount;
    $order->store->save();
  }

  function reject($order) {
    if($order->platform('tokopedia')) {
      TokopediaService::rejectOrder(array(
        'reason_code' => 1,
        'reason' => 'out of stock'
      ), $order->platform('tokopedia')->platform_order_id);
    }
  }

  function label($order) {
    if($order->platform('tokopedia')) {
      return TokopediaService::shippingLabel($order->platform('tokopedia')->platform_order_id);
    }
  }

  function pickup($order) {
    if($order->platform('tokopedia')) {
      TokopediaService::requestPickup(array(
        'order_id' => (int) $order->platform('tokopedia')->platform_order_id,
        'shop_id' => (int) $order->store->platform('tokopedia')->platform_store_id
      ));
    }
  }

  function awb($order) {
    if($order->platform('tokopedia')) {
      $data = array(
        'order_status' => 500,
        'shipping_ref_num' => $order->shipping->awb
      );
      TokopediaService::confirmShipping($data, (int) $order->platform('tokopedia')->platform_order_id);
    }
  }

  function verify($order) {
    $checkStock = true;
    foreach($order->details as $detail) {
      if($detail->sku->stock($detail->product) < $detail->quantity) {
        $checkStock = false;
      }
    }
    if($checkStock) {
      if($order->store->balance >= $order->final_amount) {
        $this->accept($order);
      }
    }else {
      $this->reject($order);
    }
  }
}