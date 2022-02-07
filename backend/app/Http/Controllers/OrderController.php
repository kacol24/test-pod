<?php 

namespace Core\Http\Backend;

use Validator;
use Core\Mail\Invoice;
use Theme, View, Form;
use Core\Http\Controller;
use Illuminate\Http\Request;
use Mail, DB, PDF, App, Excel, JNE, Cache, Session;
use Core\Export;
use Core\Models\Order\Order;
use Core\Models\Order\OrderChannel;
use Core\Models\Order\OrderStatus;
use Core\Models\Order\OrderLog;
use Core\Models\Order\OrderNote;
use Core\Models\Order\OrderShipping;
use Core\Repositories\Facades\OrderRepository;
use Core\Repositories\Facades\ProductRepository;
use Core\Repositories\Facades\Shipping;
use Core\Repositories\Facades\CartRepository;
use Core\Http\Backend\Resources\Order as OrderResource;
use Core\Models\Setting\Outlet;

class OrderController extends Controller {
  public function index(Request $request) {
    $end_date = date('d-m-Y');
    $start_date = date('d-m-Y',strtotime($end_date."-1 month"));
    if($request->has('start_date'))
      $start_date = date('d-m-Y',strtotime($request->input('start_date')));
    if($request->has('end_date'))
      $end_date = date('d-m-Y',strtotime($request->input('end_date')));
    $data = array(
      'page_title' => 'Order Lists',
      'active' => 'order',
      'order_status' => OrderStatus::all(),
      'outlets' => Outlet::all(),
      'start_date' => $start_date,
      'end_date' => $end_date
    );

    return $this->view('backend/order/list', $data);
  }

  public function datatable(Request $request) {
    $search = $request->search;
    $sorting = 'updated_at';
    $outlet_id = $request->outlet_id;
    $order = $request->order;
    $start_date = ($request->start_date!="") ? date("Y-m-d", strtotime($request->start_date)) : "";
    $end_date = ($request->end_date!="") ? date("Y-m-d", strtotime($request->end_date)) : "";
    $status = $request->status;

    if($request->has('sort')) {
      $sorting = $request->sort;
    }

    return OrderResource::collection(Order::withBilling()->withStatus()->when(! empty($search), function ($query) use ($search) {
      return $query->where('order_no', 'like', "%{$search}%")->orWhere('order_billings.name', 'like', "%{$search}%")->orWhere('order_billings.email', 'like', "%{$search}%");
    })->when(! empty($status), function ($query) use ($status) {
      return $query->where('status_id', $status);
    })->when(! empty($start_date), function ($query) use ($start_date) {
      return $query->whereRaw('date(orders.created_at) >= ?', $start_date);
    })->when(! empty($end_date), function ($query) use ($end_date) {
      return $query->whereRaw('date(orders.created_at) <= ?', $end_date);
    })->when((! empty($outlet_id) && $outlet_id!=0), function ($query) use ($outlet_id) {
        return $query->where('outlet_id', $outlet_id);
    })->where('store_id', session('store')->id)->orderBy($sorting, $order)->select('orders.*','order_billings.name','order_billings.email','order_status.status_name','order_billings.phone','order_billings.city')->paginate($request->limit));
  }

  public function create() {
    session(['shipping' => null]);
    session(['shipping_method' => null]);
    $data = array(
      'countries' => Shipping::getCountries(),
      'channels' => OrderChannel::orderBy('name','asc')->get(),
      'page_title' => 'Add Order',
      'active' => 'order',
    );

    return $this->view('backend/order/add', $data);
  }

  public function store(Request $request) {
    $request->flash();

    $validator = Validator::make($request->all(), [
      'billing_name' => 'required',
      'billing_email' => 'required',
      'billing_phone' => 'required',
      'billing_address' => 'required',
      'billing_state' => 'required',
      'billing_city' => 'required',
      'billing_subdistrict' => 'required',
      'billing_postcode' => 'required',
      'shipping_name' => 'required',
      'shipping_email' => 'required',
      'shipping_phone' => 'required',
      'shipping_address' => 'required',
      'shipping_state' => 'required',
      'shipping_city' => 'required',
      'shipping_subdistrict' => 'required',
      'shipping_postcode' => 'required'
    ]);

    if ($validator->fails()) {
      return redirect()->route('order.add')->withErrors($validator)->withInput();
    }
    $input = $request->all();
    $carts = CartRepository::getCart();
    session(['shipping_insurance' => $input['shipping_insurance']]);
    session(['shipping' => array('shipping_country' => $input['shipping_country'], 'shipping_city' => $input['shipping_city'], 'shipping_subdistrict' => $input['shipping_subdistrict'], 'shipping_postcode' => $input['shipping_postcode'], 'shipping_geolabel' => null)]);
    session(['shipping_method' => Shipping::findCourier($input['shipping_method'], array(
      'origin_postcode' => session('store')->postcode,
      'origin_longitude' => session('store')->longitude,
      'origin_latitude' => session('store')->latitude,
      'destination_postcode' => $input['shipping_postcode'],
      'destination_longitude' => null,
      'destination_latitude' => null,
      'weight' => $carts->realfinalweight
    ))]);
    $order = OrderRepository::preCreate($input);
    $order->cut_stock = 1;
    $order->save();

    return redirect()->route('order.edit', $order->id);
  }

  public function edit($id) {
    $data = array(
      'page_title' => 'Edit Order',
      'active' => 'order',
      'order' => Order::where('id', $id)->where('store_id', session('store')->id)->first(),
      'logs' => OrderLog::where('order_id',$id)->orderBy('created_at','desc')->get(),
      'notes' => OrderNote::where('order_id',$id)->get()
    );

    return $this->view('backend/order/edit', $data);
  }

  public function addNote(Request $request, $id) {
    $input = $request->all();
    $input['order_id'] = $id;
    $input['admin_id'] = session('admin')->id;
    OrderNote::create($input);
    return redirect()->route('order.edit',['id' => $id])->with('status', 'Success add new note');
  }

  public function getProduct(Request $request) {
    $products = ProductRepository::getSearch($request->get('query'));
    $return = array();
    foreach($products as $product) {
      $return[] = array('id' => $product->id, 'name' => $product->value);
    }
    return $return;
  }

  public function getOption(Request $request) {
    return json_encode(ProductRepository::getProductOptions($request->input('product_id')));
  }

  public function addToCart(Request $request) {
    $sku = ProductRepository::getSku($request->input('product_id'), $request->input('option_1'), $request->input('option_2'));
    return json_encode(CartRepository::addToCart($sku, $request->input('qty'), Session::getId()));
  }

  public function getCart() {
    $data = array(
      'carts' => CartRepository::getCart()
    );

    return $this->view('backend/order/cart', $data);
  }

  public function submitCode(Request $request) {
    return json_encode(CartRepository::submitCode($request->input('coupon_code'), Session::getId()));
  } 

  public function sendInvoice(Request $request, $id) {
    $order = Order::where('id', $id)->where('store_id', session('store')->id)->first();
    Mail::to($request->input('email'))->send(new Invoice($order));
    return redirect()->route('order.edit',['id' => $id])->with('status', 'Success resend invoice');
  }

  public function updateAirway(Request $request, $id) {
    $order = Order::where('id', $id)->where('store_id', session('store')->id)->first();
    insert_admin_log("Merubah resi ".$order->order_no." dari \"".$order->shipping->tracking_number."\" menjadi \"".$request->input('airway')."\"");
    OrderShipping::where('order_id',$id)->update(array('tracking_number' => $request->input('airway')));

    OrderRepository::updateStatus($order, 3);
    return redirect()->route('order.edit',['id' => $id])->with('status', 'Success update airway bill number'); 
  }

  public function updateStatus(Request $request, $id) {
    $order = Order::where('id', $id)->where('store_id', session('store')->id)->first();
    OrderRepository::updateStatus($order, $request->input('status'));
    return redirect()->route('order.edit',['id' => $id])->with('status', 'Success update order status'); 
  }

  public function updateQty(Request $request) {
    return json_encode(CartRepository::updateQty($request->input('detail_id'),$request->input('qty')));
  }

  public function deleteItem(Request $request) {
    return json_encode(CartRepository::deleteItem($request->input('detail_id')));
  }

  public function updateTotalPay(Request $request, $id) {
    $order = Order::where('id', $id)->where('store_id', session('store')->id)->first();
    if($order->total_pay != $request->total_pay) {
      $this->insertLog($id, 'Total pembayaran di update dari IDR '.number_format($order->total_pay,0,",",".").' menjadi IDR '.number_format($request->total_pay,0,",","."), 'Total paid has been updated from IDR '.number_format($order->total_pay,0,",",".").' to IDR '.number_format($request->total_pay,0,",","."), session('admin')->id);  
      $order->total_pay = $request->total_pay;
      $order->save();
    }
    return redirect()->route('order.edit',['id' => $id])->with('status', 'Success update total paid'); 
  }

  public function addItem(Request $request, $id) {
    $message = OrderRepository::addItem($request->input('product_id'),$request->input('option_1'),$request->input('option_2'),$request->input('qty'),$id);

    return redirect()->route('order.edit',['id' => $id])->with('status', $message); 
  }

  public function editItem(Request $request, $id, $detail_id) {
    $message = OrderRepository::editItem($id, $detail_id, $request->input('quantity'), $request->input('price'), $request->input('discount'));

    return redirect()->route('order.edit',['id' => $id])->with('status', $message); 
  }

  public function export(Request $request) {
    if($request->outlet_id) {
      $outlet = Outlet::find($request->outlet_id)->title;
    }else {
      $outlet = 'All';
    }
    $start_date = ($request->start_date!="") ? date("Y-m-d", strtotime($request->start_date)) : "";
    $end_date = ($request->end_date!="") ? date("Y-m-d", strtotime($request->end_date)) : "";
    $data = array();
    $data[] = array('Order No','Customer Name', 'Email', 'Currency', 'Exchange Rate', 'Payment Method', 'Status', 'Channel' , 'Placed Date', 'Product Name', 'Quantity', 'Stock Keeping Unit (SKU)', 'Price', 'Recipient', 'Recipient Number', 'Recipient Address', 'Courier', 'Shipping Price', 'Final Amount', 'AWB', 'Coupon Code');

    $input = $request->all();
    $orders = Order::rangeDate($start_date, $end_date)->where('store_id', session('store')->id)->when((! empty($input['status_id']) && $input['status_id']!=0), function ($query) use ($input) {
          return $query->where('status_id', $input['status_id']);
        })->when((! empty($input['outlet_id']) && $input['outlet_id']!=0), function ($query) use ($input) {
          return $query->where('outlet_id', $input['outlet_id']);
        })->get();

    foreach($orders as $order) {
      $code = (isset($order->couponuse)) ? $order->couponuse->coupon : '';
      foreach($order->details as $i => $detail) {
        if($i==0) {
          $data[] = array(
            $order->order_no, $order->user->name, $order->user->email, $order->currency, $order->exchange_rate, $order->payment_method, $order->status->title(), $order->channel->name, $order->created_at, $detail->title(), $detail->quantity, $detail->sku_code, $order->currency." ".number_format(ceil(($detail->price - $detail->discount)/$order->exchange_rate)), $order->shipping->name, $order->shipping->phone, $order->shipping->address." ".$order->shipping->subdistrict." ".$order->shipping->city." ".$order->shipping->state." ".$order->shipping->country." ".$order->shipping->postcode, $order->shipping->shipping_method, $order->currency." ".number_format($order->shipping_fee/$order->exchange_rate,0,",","."), $order->currency." ".number_format($order->final_amount/$order->exchange_rate,0,",","."), $order->shipping->tracking_number, $code
          );
        }else {
          $data[] = array(
            '', '', '', '', '', '', '', '', '', $detail->title(), $detail->quantity, $detail->sku_code, $order->currency." ".number_format(ceil(($detail->price - $detail->discount)/$order->exchange_rate)), '', '', '', '', '', '', ''
          );
        }
      }
    }

    return Excel::download(new Export($data), 'transaction#'.$outlet.'#'.$request->get('start_date').'-'.$request->get('end_date').'.xlsx');
  }

  function printInvoice($id) {
    $data['order'] = Order::where('id', $id)->where('store_id', session('store')->id)->first();
    $data['page_title'] = 'Print Invoice';
    $data['active'] = 'Print Invoice';
    return $this->view('backend/order/print', $data);
  }

  function printLabel($id) {
    $data['order'] = Order::where('id', $id)->where('store_id', session('store')->id)->first();
    $data['page_title'] = 'Print Label Pengiriman';
    $data['active'] = 'Print Label Pengiriman';
    return $this->view('backend/order/printlabel', $data);
  }

  function generateCnote($id) {
    $order = Order::where('id', $id)->where('store_id', session('store')->id)->first();
    JNE::generateCnote($order);
    return redirect()->route('order.edit',['id' => $id])->with('status', 'Success update airway bill number'); 
  }

  public function pickup($id) {
    $order = Order::where('id', $id)->where('store_id', session('store')->id)->whereIn('status_id',array(1,2))->first();
    if($order) {
      $response = OrderRepository::pickup($order);  
      if($response['status'] == 'success') {
        return redirect()->route('order.edit',['id' => $id])->with('status', $response['message']);
      }else {
        return redirect()->route('order.edit',['id' => $id])->with('error', $response['message']);
      }
    }
    
    return redirect()->route('order.edit',['id' => $id])->with('error', __('general.requestfailed'));
  }

  public function booking($id) {
    $order = Order::where('id', $id)->where('store_id', session('store')->id)->whereIn('status_id',array(1,2))->first();
    if($order) {
      $response = OrderRepository::createShippingOrder($order);  
      if($response['status'] == 'success') {
        return redirect()->route('order.edit',['id' => $id])->with('status', $response['message']);
      }else {
        return redirect()->route('order.edit',['id' => $id])->with('error', $response['message']);
      }
    }
    
    return redirect()->route('order.edit',['id' => $id])->with('error', __('general.requestfailed'));
  }
}