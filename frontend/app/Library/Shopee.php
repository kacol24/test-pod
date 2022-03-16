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
    }
  }

  function handleResponse($log, $curl, $resp, $action, $shop_id, $data = array()) {
    $err = curl_error($curl);
    $log->response = $resp;
    $log->save();
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
    if(in_array($httpcode, array(401,403)) && $this->retry==0) {
      $this->retry = 1;
      $this->refreshToken($shop_id);
      if($action == 'create_product') {
        return $this->createProduct($data, $shop_id);
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