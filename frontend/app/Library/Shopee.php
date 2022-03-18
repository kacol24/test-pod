<?php

namespace App\Library;

use DB, Mail;
use App\Models\Order\Order;
use App\Models\ShopeeLog;
use App\Models\StorePlatform;

class Shopee {
  protected $partner_id;
  protected $key;
  protected $retry;

  public function __construct()
  {
    $this->partner_id = (int)(env('APP_ENV') == 'production') ? config('services.shopee.live_partner_id') : config('services.shopee.staging_partner_id');
    $this->key = (env('APP_ENV') == 'production') ? config('services.shopee.live_key') : config('services.shopee.staging_key');
    $this->auth_url = (env('APP_ENV') == 'production') ? config('services.shopee.live_auth_url') : config('services.shopee.staging_auth_url');
  }

  public function authUrl() {
    $url = (env('APP_ENV') == 'production') ? "https://partner.shopeemobile.com/api/v2/shop/auth_partner" : "https://partner.test-stable.shopeemobile.com/api/v2/shop/auth_partner";
    $time = time();
    $sign = hash_hmac('sha256', $this->partner_id."/api/v2/shop/auth_partner".$time , $this->key);
    return $url."?timestamp=".$time."&partner_id=".$this->partner_id."&sign=".$sign."&redirect=".route('shopee.callback');
  }

  public function createProduct($shop_id, $input) {
    $platform = StorePlatform::where('platform','shopee')->where('platform_store_id', $shop_id)->first();
    $url = (env('APP_ENV') == 'production') ? "https://partner.shopeemobile.com/api/v2/product/add_item" : "https://partner.test-stable.shopeemobile.com/api/v2/product/add_item";

    if($platform) {
      $time = time();
      $sign = hash_hmac('sha256', $this->partner_id."/api/v2/product/add_item".$time.$platform->access_token.$shop_id , $this->key);
      $url = $url."?timestamp=".$time."&partner_id=".$this->partner_id."&sign=".$sign."&shop_id=".$shop_id."&access_token=".$platform->access_token;

      $log = ShopeeLog::create(array(
        "type" => "create_product",
        "request" => json_encode(array_merge($input, array("url" => $url))),
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
          "Content-Type: application/json",
        )
      ));

      $resp = curl_exec($curl);
      return $this->handleResponse($log, $curl, $resp, 'create_product', $shop_id , $input);
    }else {
      $log = ShopeeLog::create(array(
        "type" => "create_product",
        "request" => json_encode($input),
        "response" => json_encode(array("status" => "error", "message" => "Aunthorized access"))
      ));
    }
  }

  public function updateProduct($shop_id, $input) {
    $platform = StorePlatform::where('platform','shopee')->where('platform_store_id', $shop_id)->first();
    $url = (env('APP_ENV') == 'production') ? "https://partner.shopeemobile.com/api/v2/product/update_item" : "https://partner.test-stable.shopeemobile.com/api/v2/product/update_item";

    if($platform) {
      $time = time();
      $sign = hash_hmac('sha256', $this->partner_id."/api/v2/product/update_item".$time.$platform->access_token.$shop_id , $this->key);
      $url = $url."?timestamp=".$time."&partner_id=".$this->partner_id."&sign=".$sign."&shop_id=".$shop_id."&access_token=".$platform->access_token;

      $log = ShopeeLog::create(array(
        "type" => "update_product",
        "request" => json_encode(array_merge($input, array("url" => $url))),
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
          "Content-Type: application/json",
        )
      ));

      $resp = curl_exec($curl);
      return $this->handleResponse($log, $curl, $resp, 'update_product', $shop_id , $input);
    }else {
      $log = ShopeeLog::create(array(
        "type" => "update_product",
        "request" => json_encode($input),
        "response" => json_encode(array("status" => "error", "message" => "Aunthorized access"))
      ));
    }
  }

  public function updateVariant($shop_id, $input) {
    $platform = StorePlatform::where('platform','shopee')->where('platform_store_id', $shop_id)->first();
    $url = (env('APP_ENV') == 'production') ? "https://partner.shopeemobile.com/api/v2/product/update_tier_variation" : "https://partner.test-stable.shopeemobile.com/api/v2/product/update_tier_variation";

    if($platform) {
      $time = time();
      $sign = hash_hmac('sha256', $this->partner_id."/api/v2/product/update_tier_variation".$time.$platform->access_token.$shop_id , $this->key);
      $url = $url."?timestamp=".$time."&partner_id=".$this->partner_id."&sign=".$sign."&shop_id=".$shop_id."&access_token=".$platform->access_token;

      $log = ShopeeLog::create(array(
        "type" => "update_variant",
        "request" => json_encode(array_merge($input, array("url" => $url))),
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
          "Content-Type: application/json",
        )
      ));

      $resp = curl_exec($curl);
      return $this->handleResponse($log, $curl, $resp, 'update_variant', $shop_id , $input);
    }else {
      $log = ShopeeLog::create(array(
        "type" => "update_variant",
        "request" => json_encode($input),
        "response" => json_encode(array("status" => "error", "message" => "Aunthorized access"))
      ));
    }
  }

  public function updateModel($shop_id, $input) {
    $platform = StorePlatform::where('platform','shopee')->where('platform_store_id', $shop_id)->first();
    $url = (env('APP_ENV') == 'production') ? "https://partner.shopeemobile.com/api/v2/product/update_model" : "https://partner.test-stable.shopeemobile.com/api/v2/product/update_model";

    if($platform) {
      $time = time();
      $sign = hash_hmac('sha256', $this->partner_id."/api/v2/product/update_model".$time.$platform->access_token.$shop_id , $this->key);
      $url = $url."?timestamp=".$time."&partner_id=".$this->partner_id."&sign=".$sign."&shop_id=".$shop_id."&access_token=".$platform->access_token;

      $log = ShopeeLog::create(array(
        "type" => "update_model",
        "request" => json_encode(array_merge($input, array("url" => $url))),
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
          "Content-Type: application/json",
        )
      ));

      $resp = curl_exec($curl);
      return $this->handleResponse($log, $curl, $resp, 'update_model', $shop_id , $input);
    }else {
      $log = ShopeeLog::create(array(
        "type" => "update_model",
        "request" => json_encode($input),
        "response" => json_encode(array("status" => "error", "message" => "Aunthorized access"))
      ));
    }
  }

  public function updatePrice($shop_id, $input) {
    $platform = StorePlatform::where('platform','shopee')->where('platform_store_id', $shop_id)->first();
    $url = (env('APP_ENV') == 'production') ? "https://partner.shopeemobile.com/api/v2/product/update_price" : "https://partner.test-stable.shopeemobile.com/api/v2/product/update_price";

    if($platform) {
      $time = time();
      $sign = hash_hmac('sha256', $this->partner_id."/api/v2/product/update_price".$time.$platform->access_token.$shop_id , $this->key);
      $url = $url."?timestamp=".$time."&partner_id=".$this->partner_id."&sign=".$sign."&shop_id=".$shop_id."&access_token=".$platform->access_token;

      $log = ShopeeLog::create(array(
        "type" => "update_price",
        "request" => json_encode(array_merge($input, array("url" => $url))),
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
          "Content-Type: application/json",
        )
      ));

      $resp = curl_exec($curl);
      return $this->handleResponse($log, $curl, $resp, 'update_price', $shop_id , $input);
    }else {
      $log = ShopeeLog::create(array(
        "type" => "update_price",
        "request" => json_encode($input),
        "response" => json_encode(array("status" => "error", "message" => "Aunthorized access"))
      ));
    }
  }

  public function updateStock($shop_id, $input) {
    $platform = StorePlatform::where('platform','shopee')->where('platform_store_id', $shop_id)->first();
    $url = (env('APP_ENV') == 'production') ? "https://partner.shopeemobile.com/api/v2/product/update_stock" : "https://partner.test-stable.shopeemobile.com/api/v2/product/update_stock";

    if($platform) {
      $time = time();
      $sign = hash_hmac('sha256', $this->partner_id."/api/v2/product/update_stock".$time.$platform->access_token.$shop_id , $this->key);
      $url = $url."?timestamp=".$time."&partner_id=".$this->partner_id."&sign=".$sign."&shop_id=".$shop_id."&access_token=".$platform->access_token;

      $log = ShopeeLog::create(array(
        "type" => "update_stock",
        "request" => json_encode(array_merge($input, array("url" => $url))),
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
          "Content-Type: application/json",
        )
      ));

      $resp = curl_exec($curl);
      return $this->handleResponse($log, $curl, $resp, 'update_stock', $shop_id , $input);
    }else {
      $log = ShopeeLog::create(array(
        "type" => "update_stock",
        "request" => json_encode($input),
        "response" => json_encode(array("status" => "error", "message" => "Aunthorized access"))
      ));
    }
  }

  public function deleteProduct($shop_id, $input) {
    $platform = StorePlatform::where('platform','shopee')->where('platform_store_id', $shop_id)->first();
    $url = (env('APP_ENV') == 'production') ? "https://partner.shopeemobile.com/api/v2/product/delete_item" : "https://partner.test-stable.shopeemobile.com/api/v2/product/delete_item";

    if($platform) {
      $time = time();
      $sign = hash_hmac('sha256', $this->partner_id."/api/v2/product/delete_item".$time.$platform->access_token.$shop_id , $this->key);
      $url = $url."?timestamp=".$time."&partner_id=".$this->partner_id."&sign=".$sign."&shop_id=".$shop_id."&access_token=".$platform->access_token;

      $log = ShopeeLog::create(array(
        "type" => "delete_item",
        "request" => json_encode(array_merge($input, array("url" => $url))),
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
          "Content-Type: application/json",
        )
      ));

      $resp = curl_exec($curl);
      return $this->handleResponse($log, $curl, $resp, 'delete_item', $shop_id , $input);
    }else {
      $log = ShopeeLog::create(array(
        "type" => "delete_item",
        "request" => json_encode($input),
        "response" => json_encode(array("status" => "error", "message" => "Aunthorized access"))
      ));
    }
  }

  public function getModel($shop_id, $input) {
    $platform = StorePlatform::where('platform','shopee')->where('platform_store_id', $shop_id)->first();
    $url = (env('APP_ENV') == 'production') ? "https://partner.shopeemobile.com/api/v2/product/get_model_list" : "https://partner.test-stable.shopeemobile.com/api/v2/product/get_model_list";

    if($platform) {
      $time = time();
      $sign = hash_hmac('sha256', $this->partner_id."/api/v2/product/get_model_list".$time.$platform->access_token.$shop_id , $this->key);
      $url = $url."?timestamp=".$time."&partner_id=".$this->partner_id."&sign=".$sign."&shop_id=".$shop_id."&access_token=".$platform->access_token."&item_id=".$input;

      $log = ShopeeLog::create(array(
        "type" => "get_model_list",
        "request" => json_encode(array("url" => $url)),
        "response" => null
      ));

      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HEADER => true,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          "Content-Type: application/json",
        )
      ));

      $resp = curl_exec($curl);
      return $this->handleResponse($log, $curl, $resp, 'get_model_list', $shop_id , $input);
    }else {
      $log = ShopeeLog::create(array(
        "type" => "get_model_list",
        "request" => $input,
        "response" => json_encode(array("status" => "error", "message" => "Aunthorized access"))
      ));
    }
  }

  public function listItem($shop_id, $input) {
    $platform = StorePlatform::where('platform','shopee')->where('platform_store_id', $shop_id)->first();
    $url = (env('APP_ENV') == 'production') ? "https://partner.shopeemobile.com/api/v2/product/unlist_item" : "https://partner.test-stable.shopeemobile.com/api/v2/product/unlist_item";
    if($platform) {
      $time = time();
      $sign = hash_hmac('sha256', $this->partner_id."/api/v2/product/unlist_item".$time.$platform->access_token.$shop_id , $this->key);
      $url = $url."?timestamp=".$time."&partner_id=".$this->partner_id."&sign=".$sign."&shop_id=".$shop_id."&access_token=".$platform->access_token;

      $log = ShopeeLog::create(array(
        "type" => "list_item",
        "request" => json_encode(array_merge($input, array("url" => $url))),
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
          "Content-Type: application/json",
        )
      ));

      $resp = curl_exec($curl);
      return $this->handleResponse($log, $curl, $resp, 'list_item', $shop_id , $input);
    }else {
      $log = ShopeeLog::create(array(
        "type" => "list_item",
        "request" => json_encode($input),
        "response" => json_encode(array("status" => "error", "message" => "Aunthorized access"))
      ));
    }
  }

  public function createVariant($shop_id, $input) {
    $platform = StorePlatform::where('platform','shopee')->where('platform_store_id', $shop_id)->first();
    $url = (env('APP_ENV') == 'production') ? "https://partner.shopeemobile.com/api/v2/product/init_tier_variation" : "https://partner.test-stable.shopeemobile.com/api/v2/product/init_tier_variation";

    if($platform) {
      $time = time();
      $sign = hash_hmac('sha256', $this->partner_id."/api/v2/product/init_tier_variation".$time.$platform->access_token.$shop_id , $this->key);

      $url = $url."?timestamp=".$time."&partner_id=".$this->partner_id."&sign=".$sign."&shop_id=".$shop_id."&access_token=".$platform->access_token;

      $log = ShopeeLog::create(array(
        "type" => "create_variant",
        "request" => json_encode(array_merge($input, array("url" => $url))),
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
          "Content-Type: application/json",
        )
      ));

      $resp = curl_exec($curl);
      return $this->handleResponse($log, $curl, $resp, 'create_variant', $shop_id , $input);
    }else {
      $log = ShopeeLog::create(array(
        "type" => "create_variant",
        "request" => json_encode($input),
        "response" => json_encode(array("status" => "error", "message" => "Aunthorized access"))
      ));
    }
  }

  public function getToken($code, $shop_id) {
    $url = (env('APP_ENV') == 'production') ? "https://partner.shopeemobile.com/api/v2/auth/token/get" : "https://partner.test-stable.shopeemobile.com/api/v2/auth/token/get";

    $time = time();
    $sign = hash_hmac('sha256', $this->partner_id."/api/v2/auth/token/get".$time , $this->key);

    $input = array(
      'partner_id' => $this->partner_id,
      'code' => $code,
      'shop_id' => (int)$shop_id
    );

    $url = $url."?timestamp=".$time."&partner_id=".$this->partner_id."&sign=".$sign;

    $log = ShopeeLog::create(array(
      "type" => "token",
      "request" => json_encode(array_merge($input, array('url' => $url))),
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
        "Content-Type: application/json",
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

  public function refreshToken($shop_id) {
    $platform = StorePlatform::where('platform','shopee')->where('platform_store_id', $shop_id)->first();
    if($platform) {
      $url = (env('APP_ENV') == 'production') ? "https://partner.shopeemobile.com/api/v2/auth/access_token/get" : "https://partner.test-stable.shopeemobile.com/api/v2/auth/access_token/get";

      $time = time();
      $sign = hash_hmac('sha256', $this->partner_id."/api/v2/auth/access_token/get".$time , $this->key);

      $input = array(
        'refresh_token' => $platform->refresh_token,
        'shop_id' => (int) $shop_id,
        'partner_id' => $this->partner_id
      );

      $url = $url."?timestamp=".$time."&partner_id=".$this->partner_id."&sign=".$sign;

      $log = ShopeeLog::create(array(
        "type" => "refresh_token",
        "request" => json_encode(array_merge($input, array('url' => $url))),
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
          "Content-Type: application/json",
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
      if(isset($response['access_token'])) {
        $platform->access_token = $response['access_token'];
        $platform->refresh_token = $response['refresh_token'];
        $platform->save();
      }else if($response['error'] == "error_auth"){
        $platform->delete();
      }
    }else {
      return array("status" => "error", "message" => "Aunthorized access");  
    }
      
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

  public function uploadImage($image) {
    $url = (env('APP_ENV') == 'production') ? "https://partner.shopeemobile.com/api/v2/media_space/upload_image" : "https://partner.test-stable.shopeemobile.com/api/v2/media_space/upload_image";

    $time = time();
    $sign = hash_hmac('sha256', $this->partner_id."/api/v2/media_space/upload_image".$time , $this->key);


    $post = ["image" => curl_file_create($image)];

    $url = $url."?timestamp=".$time."&partner_id=".$this->partner_id."&sign=".$sign;

    $log = ShopeeLog::create(array(
      "type" => "upload_image",
      "request" => json_encode(array_merge($post, array('url' => $url))),
      "response" => null
    ));

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HEADER => true,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => $post,
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
}