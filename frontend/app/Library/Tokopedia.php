<?php

namespace App\Library;

use DB, Mail;
use App\Models\Order\Order;
use App\Models\TokopediaLog;

class Tokopedia {
  protected $client_id;
  protected $client_secret;
  protected $app_id;
  protected $retry;

  public function __construct()
  {
    $this->client_id = (env('APP_ENV') == 'production') ? config('services.tokopedia.live_client_id') : config('services.tokopedia.staging_client_id');
    $this->client_secret = (env('APP_ENV') == 'production') ? config('services.tokopedia.live_client_secret') : config('services.tokopedia.staging_client_secret');
    $this->app_id = (env('APP_ENV') == 'production') ? config('services.tokopedia.live_app_id') : config('services.tokopedia.staging_app_id');
  }

  public function createProduct($input, $shop_id) {
    $url = "https://fs.tokopedia.net/v3/products/fs/".$this->app_id."/create?shop_id=".$shop_id;

    $log = TokopediaLog::create(array(
      "type" => "create_product",
      "request" => json_encode($input),
      "response" => null
    ));

    $curl = curl_init();
    $token = (session('tokopedia_token')) ? session('tokopedia_token') : $this->getToken();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_POST => true,
      CURLOPT_HEADER => true,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode($input),
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',                                                                                
        "Authorization: Bearer ".$token,
      )
    ));

    $resp = curl_exec($curl);
    $resp = $this->handleResponse($log, $curl, $resp, 'create_product', $input , $shop_id);    

    return $resp;
  }

  public function updateProduct($input, $shop_id) {
    $url = "https://fs.tokopedia.net/v3/products/fs/".$this->app_id."/edit?shop_id=".$shop_id;

    $log = TokopediaLog::create(array(
      "type" => "update_product",
      "request" => json_encode($input),
      "response" => null
    ));

    $curl = curl_init();
    $token = (session('tokopedia_token')) ? session('tokopedia_token') : $this->getToken();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_POST => true,
      CURLOPT_HEADER => true,
      CURLOPT_CUSTOMREQUEST => "PATCH",
      CURLOPT_POSTFIELDS => json_encode($input),
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',                                                                                
        "Authorization: Bearer ".$token,
      )
    ));

    $resp = curl_exec($curl);
    $resp = $this->handleResponse($log, $curl, $resp, 'update_product', $input , $shop_id);    

    return $resp;
  }

  public function setPrice($input, $shop_id) {
    $url = "https://fs.tokopedia.net/inventory/v1/fs/".$this->app_id."/price/update?shop_id=".$shop_id;

    $log = TokopediaLog::create(array(
      "type" => "price",
      "request" => json_encode($input),
      "response" => null
    ));

    $curl = curl_init();
    $token = (session('tokopedia_token')) ? session('tokopedia_token') : $this->getToken();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_POST => true,
      CURLOPT_HEADER => true,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode($input),
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',                                                                                
        "Authorization: Bearer ".$token,
      )
    ));

    $resp = curl_exec($curl);
    $resp = $this->handleResponse($log, $curl, $resp, 'price', $input , $shop_id);    

    return $resp;
  }

  public function setStock($input, $shop_id) {
    $url = "https://fs.tokopedia.net/inventory/v1/fs/".$this->app_id."/stock/update?shop_id=".$shop_id;

    $log = TokopediaLog::create(array(
      "type" => "stock",
      "request" => json_encode($input),
      "response" => null
    ));

    $curl = curl_init();
    $token = (session('tokopedia_token')) ? session('tokopedia_token') : $this->getToken();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_POST => true,
      CURLOPT_HEADER => true,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode($input),
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',                                                                                
        "Authorization: Bearer ".$token,
      )
    ));

    $resp = curl_exec($curl);
    $resp = $this->handleResponse($log, $curl, $resp, 'stock', $input , $shop_id);    

    return $resp;
  }

  public function setActiveProduct($input, $shop_id) {
    $url = "https://fs.tokopedia.net/v1/products/fs/".$this->app_id."/active?shop_id=".$shop_id;

    $log = TokopediaLog::create(array(
      "type" => "active_product",
      "request" => json_encode($input),
      "response" => null
    ));

    $curl = curl_init();
    $token = (session('tokopedia_token')) ? session('tokopedia_token') : $this->getToken();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_POST => true,
      CURLOPT_HEADER => true,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode($input),
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',                                                                                
        "Authorization: Bearer ".$token,
      )
    ));

    $resp = curl_exec($curl);
    $resp = $this->handleResponse($log, $curl, $resp, 'active_product', $input , $shop_id);    

    return $resp;
  }

  public function deleteProduct($input, $shop_id) {
    $url = "https://fs.tokopedia.net/v3/products/fs/".$this->app_id."/delete?shop_id=".$shop_id;

    $log = TokopediaLog::create(array(
      "type" => "delete_product",
      "request" => json_encode($input),
      "response" => null
    ));

    $curl = curl_init();
    $token = (session('tokopedia_token')) ? session('tokopedia_token') : $this->getToken();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_POST => true,
      CURLOPT_HEADER => true,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode($input),
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',                                                                                
        "Authorization: Bearer ".$token,
      )
    ));

    $resp = curl_exec($curl);
    $resp = $this->handleResponse($log, $curl, $resp, 'delete_product', $input , $shop_id);    

    return $resp;
  }

  public function setInactiveProduct($input, $shop_id) {
    $url = "https://fs.tokopedia.net/v1/products/fs/".$this->app_id."/inactive?shop_id=".$shop_id;

    $log = TokopediaLog::create(array(
      "type" => "inactive_product",
      "request" => json_encode($input),
      "response" => null
    ));

    $curl = curl_init();
    $token = (session('tokopedia_token')) ? session('tokopedia_token') : $this->getToken();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_POST => true,
      CURLOPT_HEADER => true,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode($input),
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',                                                                                
        "Authorization: Bearer ".$token,
      )
    ));

    $resp = curl_exec($curl);
    $resp = $this->handleResponse($log, $curl, $resp, 'inactive_product', $input , $shop_id);    

    return $resp;
  }

  public function getCategories() {
    $url = "https://fs.tokopedia.net/inventory/v1/fs/".$this->app_id."/product/category";

    $log = TokopediaLog::create(array(
      "type" => "categories",
      "request" => null,
      "response" => null
    ));

    $token = (session('tokopedia_token')) ? session('tokopedia_token') : $this->getToken();

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HEADER => true,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer ".$token
      )
    ));

    $resp = curl_exec($curl);
    $resp = $this->handleResponse($log, $curl, $resp, 'category');    
    return $resp;
  }

  public function getProduct($product_id) {
    $url = "https://fs.tokopedia.net/inventory/v1/fs/".$this->app_id."/product/info?product_id=".$product_id;

    $log = TokopediaLog::create(array(
      "type" => "get_product",
      "request" => $product_id,
      "response" => null
    ));

    $token = (session('tokopedia_token')) ? session('tokopedia_token') : $this->getToken();

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HEADER => true,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer ".$token
      )
    ));

    $resp = curl_exec($curl);
    $resp = $this->handleResponse($log, $curl, $resp, 'get_product', $product_id);    
    return $resp;
  }

  public function getVariant($category_id) {
    $url = "https://fs.tokopedia.net/inventory/v2/fs/".$this->app_id."/category/get_variant?cat_id=".$category_id;

    $log = TokopediaLog::create(array(
      "type" => "categories",
      "request" => null,
      "response" => null
    ));

    $token = (session('tokopedia_token')) ? session('tokopedia_token') : $this->getToken();

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HEADER => true,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer ".$token
      )
    ));

    $resp = curl_exec($curl);
    $resp = $this->handleResponse($log, $curl, $resp, 'get_variant', $category_id);    
    return $resp['data'];
  }

  public function getToken() {
    $url = "https://accounts.tokopedia.com/token?grant_type=client_credentials";

    $log = TokopediaLog::create(array(
      "type" => "token",
      "request" => null,
      "response" => null
    ));

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HEADER => true,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_HTTPHEADER => array(
        "Authorization: Basic ".base64_encode( $this->client_id.':'.$this->client_secret),
        'Content-Length: 0',
      )
    ));

    $resp = curl_exec($curl);
    $resp = $this->handleResponse($log, $curl, $resp, 'token');    
    session(['tokopedia_token' => $resp['access_token']]);
    return $resp['access_token'];
  }

  function handleResponse($log, $curl, $resp, $action, $data = array(), $shop_id = null) {
    $err = curl_error($curl);
    $log->response = $resp;
    $log->save();
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
    if(in_array($httpcode, array(401,403)) && $this->retry==0) {
      $this->retry = 1;
      $this->getToken();
      if($action == 'category') {
        return $this->getCategories();
      }elseif($action == 'create_product') {
        return $this->createOrder($data, $shop_id);
      }else if($action == 'get_variant') {
        return $this->getVariant($data);
      }else if($action == 'inactive_product') {
        return $this->setInactiveProduct($data, $shop_id);
      }else if($action == 'active_product') {
        return $this->setActiveProduct($data, $shop_id);
      }else if($action == 'price') {
        return $this->setPrice($data, $shop_id);
      }else if($action == 'stock') {
        return $this->setStock($data, $shop_id);
      }else if($action == 'delete_product') {
        return $this->deleteProduct($data, $shop_id);
      }else if($action == 'update_product') {
        return $this->updateProduct($data, $shop_id);
      }else if($action == 'get_product') {
        return $this->getProduct($data);
      }
    }
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