<?php

namespace App\Repositories;

use DB, Mail;
use App\Models\Order\Order as OrderModel;
use App\Services\Facades\Shopee as ShopeeService;
use App\Services\Facades\Tokopedia as TokopediaService;
use App\Repositories\Facades\Product;
use App\Jobs\CapacityUpdated;
use App\Services\WalletService;

class Order {
  function accept($order) {
    DB::beginTransaction();
    try {
      foreach($order->details as $detail) {
        if($detail->product->master_product_id) {
          $mastersku = $detail->product->mastersku($detail->sku->option_detail_key1, $detail->sku->option_detail_key2);
          if($mastersku->stock >= $detail->quantity) {
            $mastersku->stock -= $detail->quantity;
            $mastersku->save();
          }else {
            throw new \Exception('Insufficient stock');
          }   
          $capacity = $detail->product->masterproduct->capacity;
          if($capacity->capacity>=$detail->quantity) {
            $capacity->capacity -= $detail->quantity;
            $capacity->save();  
            CapacityUpdated::dispatch($capacity->id);
          }else {
            throw new \Exception('Insufficient stock');
          }
        }else {
          if($detail->sku->stock >= $detail->quantity) {
            $detail->sku->stock -= $detail->quantity;
            $detail->sku->save();
            Product::updateStock($detail->product);
          }else {
            throw new \Exception('Insufficient stock');
          }
        }
      }
      $wallet = new WalletService($order->store_id);
      $wallet->order($order->final_amount, $order->id);
      DB::commit();
      if($order->platform('tokopedia')) {
        TokopediaService::acceptOrder($order->platform('tokopedia')->platform_order_id);
      }
      $this->updateStatus($order,2);
    } catch (\Exception $e) {
      DB::rollback();
      throw new \Exception($e->getMessage());
    }
  }

  function updateStatus($order, $status) {
    if($status == 3 && $order->status_id == 2) {
      $wallet = new WalletService($order->store->id);
      $wallet->refund($order->final_amount, $order->id);
    }else if($status == 7 && $order->status_id !=7) {
      $this->giveCommission($order);
    }
    $order->status_id = $status;
    $order->save();
  }

  function giveCommission($order) {
    $fee = 2;
    DB::beginTransaction();
    try {
      $referral = $order->store->referral;
      if($referral && strtotime($referral->expired_at)>=time()) {
        $commission = round($order->final_amount*2/100);
        $referral->total_commission += $commission;
        $referral->save();
        $wallet = new WalletService($referral->ref_id);
        $wallet->commission($commission, $order->id);
      }
      DB::commit();
    } catch (\Exception $e) {
      DB::rollback();
      throw new \Exception($e->getMessage());
    }
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