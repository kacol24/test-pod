<?php

namespace App\Http\Controllers;

use App\Models\Order\Order as OrderModel;
use App\Models\Order\OrderShipping;
use App\Models\Order\OrderPlatform;
use App\Models\Order\OrderDetail;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use App\Models\PrismLog;
use App\Models\StorePlatform;
use Illuminate\Support\Facades\DB;
use App\Models\Product\ProductSku;
use App\Models\Product\ProductImage;
use App\Repositories\Facades\Order;
use Auth;

class PrismController extends Controller
{
    public function pickup(Request $request) {
        $log = PrismLog::create(array(
            'type' => 'pickup',
            'request' => json_encode($request->all())
        ));

        $order = OrderModel::where('order_no', $request->order_no)->first();
        if($order) {
            Order::pickup($order);
            $log->response = 'Success pickup order.';
        }else {
            $log->response = 'Order not found.';
        }
        $log->save();    
        echo $log->response;
    }

    public function awb(Request $request) {
        $log = PrismLog::create(array(
            'type' => 'awb',
            'request' => json_encode($request->all())
        ));

        $order = OrderModel::where('order_no', $request->order_no)->first();
        if($order) {
            $order->shipping->awb = $request->awb;
            $order->shipping->save();
            Order::awb($order);
            $log->response = 'Success submit awb.';
        }else {
            $log->response = 'Order not found.';
        }
        $log->save();    
        echo $log->response;
    }

    public function label(Request $request) {
        $log = PrismLog::create(array(
            'type' => 'label',
            'request' => json_encode($request->all())
        ));

        $order = OrderModel::where('order_no', $request->order_no)->first();
        if($order) {
            $log->response = Order::label($order);
        }else {
            $log->response = 'Order not found.';
        }
        $log->save();    
        return $log->response;
    }
}
