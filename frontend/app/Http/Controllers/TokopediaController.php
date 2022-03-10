<?php

namespace App\Http\Controllers;

use App\Models\Order\Order;
use App\Models\Order\OrderShipping;
use App\Models\Order\OrderPlatform;
use App\Models\Order\OrderDetail;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\TokopediaLog;
use App\Models\StorePlatform;
use Illuminate\Support\Facades\DB;
use App\Models\Product\ProductSku;
use App\Models\Product\ProductImage;

class TokopediaController extends Controller
{
    public function order(Request $request) {
        $log = TokopediaLog::create(array(
            'type' => 'webhook_order',
            'request' => json_encode($request->all())
        ));

        $store = StorePlatform::where('platform','tokopedia')->where('platform_store_id', $request->shop_id)->first();

        if($store) {
            $orderPlatform = OrderPlatform::where('platform','tokopedia')->where('platform_order_id', $request->order_id)->first();
            if(!$orderPlatform) {
                DB::beginTransaction();
                try {
                    $order = Order::create(array(
                        'store_id' => $store->store_id,
                        'order_no' => $request->invoice_ref_num,
                        'total_amount' => $request->amt['ttl_product_price'],
                        'shipping_fee' => $request->amt['shipping_cost'],
                        'insurance_fee' => $request->amt['insurance_cost'],
                        'discount_voucher' => $request->amt['voucher_amount'],
                        'pay_with_point' => $request->amt['toppoints_amount'],
                        'final_amount' =>$request->amt['ttl_amount'],
                        'status_id' => 2,
                        'payment_method' => 'Saldo'
                    ));

                    OrderPlatform::create(array(
                        'order_id' => $order->id,
                        'platform' => 'tokopedia',
                        'platform_order_id' => $request->order_id
                    ));

                    
                    OrderShipping::create(array(
                        'order_id' => $order->id,
                        'name' => ($request->recipient['name']) ? $request->recipient['name'] : "NULL",
                        'phone' => ($request->recipient['phone']) ? $request->recipient['phone'] : "NULL",
                        'address' => ($request->recipient['address']['address_full']) ? $request->recipient['address']['address_full'] : "NULL",
                        'country' => ($request->recipient['address']['country']) ? $request->recipient['address']['country'] : "NULL",
                        'province' => ($request->recipient['address']['province']) ? $request->recipient['address']['province'] : "NULL",
                        'city' => ($request->recipient['address']['city']) ? $request->recipient['address']['city'] : "NULL",
                        'district' => ($request->recipient['address']['district']) ? $request->recipient['address']['district'] : "NULL",
                        'postal_code' => ($request->recipient['address']['postal_code']) ? $request->recipient['address']['postal_code'] : "NULL",
                        'shipping_code' => ($request->logistics['shipping_agency']) ? $request->logistics['shipping_agency'] : "NULL",
                        'shipping_type' => ($request->logistics['service_type']) ? $request->logistics['service_type'] : "NULL",
                    ));

                    foreach($request->products as $platform_product) {
                        $sku = ProductSku::where('sku_code', $platform_product['sku'])->first();
                        $description = '';
                        if(isset($sku->option_detail_key1)) {
                            $description .= $sku->option_detail1->option->title." : ".$sku->option_detail1->title;
                        }
                        
                        if(isset($sku->option_detail_key2)) {
                            $description .= "<br/>".$sku->option_detail2->option->title." : ".$sku->option_detail2->title;
                        }
                        
                        OrderDetail::create(array(
                          'order_id' => $order->id,
                          'product_id' => $sku->product_id,
                          'sku_id' => $sku->id,
                          'sku_code' => $sku->sku_code,
                          'image' => ProductImage::where('product_id',$sku->product_id)->orderBy('order_weight','asc')->pluck('image')->first(),
                          'title' => $platform_product['name'],
                          'description' => $description,
                          'weight' => $sku->weight,
                          'width' => $sku->width,
                          'length' => $sku->length,
                          'height' => $sku->height,
                          'price' => $platform_product['price'],
                          'quantity' => $platform_product['quantity'],
                        ));
                    }
                    DB::commit();

                    $log->response = 'Success create order: '.$order->id;
                }catch(\Exception $e){
                    DB::rollback();
                    $log->response = 'Error created order';
                }
            }else {
                $log->response = 'Order already exists.';    
            }
        }else {
            $log->response = 'Shop id not found.';
        }
        $log->save();    
        echo $log->response;
    }

    public function status(Request $request) {
        $log = TokopediaLog::create(array(
            'type' => 'webhook_status',
            'request' => json_encode($request->all())
        ));

        $order = Order::join('order_platforms','order_platforms.order_id','=','orders.id')->where('platform','tokopedia')->where('platform_order_id', $request->order_id)->first();

        if($order) {
            if(in_array($request->order_status, array(3,5,6,10,15))) {
                $order->status_id = 3; #canceled
            }else if($request->order_status == 400) {
                $order->status_id = 4; #in prgoress
            }else if($request->order_status == 500) {
                $order->status_id = 5; #undershipment
            }else if($request->order_status == 600) {
                $order->status_id = 6; #delivered
            }else if($request->order_status == 700) {
                $order->status_id = 7; #finished
            }
            $order->save();
            $log->response = 'Success update order status.';
        }else {
            $log->response = 'Order id not found.';
        }
        $log->save();
        echo $log->response;
    }
}
