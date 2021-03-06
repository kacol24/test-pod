<?php

namespace App\Http\Controllers;

use App\Models\Order\Order as OrderModel;
use App\Models\Order\OrderShipping;
use App\Models\Order\OrderPlatform;
use App\Models\Order\OrderDetail;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use App\Models\ShopeeLog;
use App\Models\StorePlatform;
use Illuminate\Support\Facades\DB;
use App\Models\Product\ProductSku;
use App\Models\Product\ProductImage;
use App\Repositories\Facades\Order;

class ShopeeController extends Controller
{
    public function index(Request $request) {
        $log = ShopeeLog::create(array(
            'type' => 'webhook',
            'request' => json_encode($request->all())
        ));

        // $store = StorePlatform::where('platform','shopee')->where('platform_store_id', $request->shop_id)->first();

        // if($store) {
        //     $orderPlatform = OrderPlatform::where('platform','shopee')->where('platform_order_id', $request->order_id)->first();
        //     if(!$orderPlatform) {
        //         DB::beginTransaction();
        //         try {
        //             $total = 0;
        //             foreach($request->products as $platform_product) {
        //                 $sku = ProductSku::where('sku_code', $platform_product['sku'])->first();
        //                 $total += $sku->cost($sku->product);
        //             }
        //             $order = OrderModel::create(array(
        //                 'store_id' => $store->store_id,
        //                 'order_no' => $request->invoice_ref_num,
        //                 'total_amount' => $total,
        //                 'shipping_fee' => 0,
        //                 'insurance_fee' => 0,
        //                 'discount_voucher' => 0,
        //                 'pay_with_point' => 0,
        //                 'final_amount' => $total,
        //                 'status_id' => 1,
        //                 'payment_method' => 'Saldo'
        //             ));

        //             OrderPlatform::create(array(
        //                 'order_id' => $order->id,
        //                 'platform' => 'shopee',
        //                 'platform_order_id' => $request->order_id
        //             ));
                    
        //             OrderShipping::create(array(
        //                 'order_id' => $order->id,
        //                 'name' => ($request->recipient['name']) ? $request->recipient['name'] : "NULL",
        //                 'phone' => ($request->recipient['phone']) ? $request->recipient['phone'] : "NULL",
        //                 'address' => ($request->recipient['address']['address_full']) ? $request->recipient['address']['address_full'] : "NULL",
        //                 'country' => ($request->recipient['address']['country']) ? $request->recipient['address']['country'] : "NULL",
        //                 'province' => ($request->recipient['address']['province']) ? $request->recipient['address']['province'] : "NULL",
        //                 'city' => ($request->recipient['address']['city']) ? $request->recipient['address']['city'] : "NULL",
        //                 'district' => ($request->recipient['address']['district']) ? $request->recipient['address']['district'] : "NULL",
        //                 'postal_code' => ($request->recipient['address']['postal_code']) ? $request->recipient['address']['postal_code'] : "NULL",
        //                 'shipping_code' => ($request->logistics['shipping_agency']) ? $request->logistics['shipping_agency'] : "NULL",
        //                 'shipping_type' => ($request->logistics['service_type']) ? $request->logistics['service_type'] : "NULL",
        //             ));

        //             foreach($request->products as $platform_product) {
        //                 $sku = ProductSku::where('sku_code', $platform_product['sku'])->first();
        //                 $description = '';
        //                 if(isset($sku->option_detail_key1)) {
        //                     $description .= $sku->option_detail1->option->title." : ".$sku->option_detail1->title;
        //                 }
                        
        //                 if(isset($sku->option_detail_key2)) {
        //                     $description .= "<br/>".$sku->option_detail2->option->title." : ".$sku->option_detail2->title;
        //                 }
                        
        //                 OrderDetail::create(array(
        //                   'order_id' => $order->id,
        //                   'product_id' => $sku->product_id,
        //                   'sku_id' => $sku->id,
        //                   'sku_code' => $sku->sku_code,
        //                   'image' => ProductImage::where('product_id',$sku->product_id)->orderBy('order_weight','asc')->pluck('image')->first(),
        //                   'title' => $platform_product['name'],
        //                   'description' => $description,
        //                   'weight' => $sku->weight,
        //                   'width' => $sku->width,
        //                   'length' => $sku->length,
        //                   'height' => $sku->height,
        //                   'price' => $sku->cost($sku->product),
        //                   'quantity' => $platform_product['quantity'],
        //                 ));
        //             }

        //             Order::verify($order);
        //             DB::commit();
        //             $log->response = 'Success create order: '.$order->id;
        //         }catch(\Exception $e){
        //             DB::rollback();
        //             $log->response = $e->getMessage();
        //         }
        //     }else {
        //         $log->response = 'Order already exists.';    
        //     }
        // }else {
        //     $log->response = 'Shop id not found.';
        // }
        // $log->save();    
        // echo $log->response;
    }
}
