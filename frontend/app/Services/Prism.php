<?php

namespace App\Services;

use DB, Mail;
use App\Models\Order\Order;
use App\Models\PrismLog;
use App\Models\StorePlatform;

class Prism {
  protected $id;
  protected $secret;
  protected $url;
  protected $retry;

  public function __construct()
  {
    $this->id = (int)(env('APP_ENV') == 'production') ? config('services.prism.live_id') : config('services.prism.staging_id');
    $this->secret = (env('APP_ENV') == 'production') ? config('services.prism.live_secret') : config('services.prism.staging_secret');
    $this->url = (env('APP_ENV') == 'production') ? config('services.prism.live_url') : config('services.prism.staging_url');
  }

  public function createUser($input) {
    $time = time();
    $hash = md5($this->id.$time.$this->secret);
    $base64 = base64_encode($this->id.",".$hash.",".$time);
    $url = $this->url."/arterous/users";
    $log = PrismLog::create(array(
      "type" => "create_user",
      "request" => json_encode(array_merge($input, array(
        'id' => $this->id,
        'hash' => $hash,
        'time' => $time,
        'base64' => $base64,
        'url' => $url
      ))),
      "response" => null
    ));

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HEADER => true,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode($input),
      CURLOPT_HTTPHEADER => array(
        "Authorization: ".$base64,
        "Locale: id",
        "Content-Type: application/json"
      )
    ));

    $resp = curl_exec($curl);
    $log->response = $resp;
    $log->save();

    $headers=array();
    $data=explode("\n",$resp);
    $headers['status']=$data[0];
    array_shift($data);
    foreach($data as $part){
      $middle=explode(":",$part);
      if(isset($middle[1])) {
        $headers[strtolower(trim($middle[0]))] = trim($middle[1]);
      }
    }

    $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    curl_close($curl);
    $response = json_decode(substr($resp, $header_size),true);

    return $response;
  }

  public function createOrder($order) {
    $time = time();
    $hash = md5($this->id.$time.$this->secret);
    $base64 = base64_encode($this->id.",".$hash.",".$time);
    $url = $this->url."/arterous/orders";

    $items = array();
    foreach($order->details as $detail) {
      $items[] = array(
        "title" => $detail->title,
        "product_id" => $detail->product_id,
        "artwork_url" => $detail->product->editor->print_file,
        "preview_url" => $detail->product->editor->proof_file,
        "quantity" => $detail->quantity,
        "unit" => "pc",
        "unit_price" => $detail->price,
        "subtotal" => $detail->quantity*$detail->price
      );
    }

    $input = array(
      "number" => $order->order_no,
      "total_price" => $order->final_amount,
      "source" => $order->platform->platform,
      "shipping_courier" => array(
        "code" => $order->shipping->shipping_code,
        "service" => $order->shipping->shipping_type
      ),
      "shipping_address" => array(
        "code" => "SHIPPING-1",
        "label" => "Home",
        "name" => $order->shipping->name,
        "email" => "user-1@arterous.com",
        "phone" => $order->shipping->phone,
        "street" => $order->shipping->address,
        "zipcode"=> $order->shipping->postal_code
      ),
      "order_items" => $items
    );
    $log = PrismLog::create(array(
      "type" => "create_order",
      "request" => json_encode(array_merge($input, array(
        'id' => $this->id,
        'hash' => $hash,
        'time' => $time,
        'base64' => $base64,
        'url' => $url
      ))),
      "response" => null
    ));

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HEADER => true,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode($input),
      CURLOPT_HTTPHEADER => array(
        "Authorization: ".$base64,
        "Locale: id",
        "Content-Type: application/json",
        "User-Token: ".$order->store->prism_token
      )
    ));

    $resp = curl_exec($curl);
    $log->response = $resp;
    $log->save();

    $headers=array();
    $data=explode("\n",$resp);
    $headers['status']=$data[0];
    array_shift($data);
    foreach($data as $part){
      $middle=explode(":",$part);
      if(isset($middle[1])) {
        $headers[strtolower(trim($middle[0]))] = trim($middle[1]);
      }
    }

    $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    curl_close($curl);
    $response = json_decode(substr($resp, $header_size),true);

    return $response;
  }

  function handleResponse($log, $curl, $resp, $action, $shop_id, $input = array()) {
    $err = curl_error($curl);
    $log->response = $resp;
    $log->save();
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
    $headers=array();
    $data=explode("\n",$resp);
    $headers['status']=$data[0];
    array_shift($data);
    foreach($data as $part){
      $middle=explode(":",$part);
      if(isset($middle[1])) {
        $headers[strtolower(trim($middle[0]))] = trim($middle[1]);
      }
    }

    $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
    curl_close($curl);
    $response = json_decode(substr($resp, $header_size),true);

    if(in_array($httpcode, array(401,403)) && $this->retry==0) {
      $this->retry = 1;
      if(isset($response['message']) && $response['message'] == 'Invalid access_token.') {
        $this->refreshToken($shop_id);
      }
      if($action == 'create_product') {
        return $this->createProduct($shop_id, $input);
      }else if($action == 'create_variant') {
        return $this->createVariant($shop_id, $input);
      }else if($action == 'update_product') {
        return $this->updateProduct($shop_id, $input);
      }else if($action == 'list_item') {
        return $this->listItem($shop_id, $input);
      }else if($action == 'update_variant') {
        return $this->updateVariant($shop_id, $input);
      }else if($action == 'update_model') {
        return $this->updateModel($shop_id, $input);
      }else if($action == 'get_model_list') {
        return $this->getModel($shop_id, $input);
      }else if($action == 'update_price') {
        return $this->updatePrice($shop_id, $input);
      }else if($action == 'update_stock') {
        return $this->updateStock($shop_id, $input);
      }else if($action == 'delete_item') {
        return $this->deleteProduct($shop_id, $input);
      }
    }
      
    if($httpcode == 200) {
      return $response;  
    }else {
      if(isset($response['message'])) {
        return array("status" => "error", "message" => $response['message']);  
      }elseif(isset($response['meta']['message'])) {
        return array("status" => "error", "message" => $response['meta']['hint']);  
      }
      
    }
  }
}