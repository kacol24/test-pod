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
        "Locale: id"
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

  public function getToken() {
    $time = time();
    $hash = md5($this->id.$time.$this->secret);
    $base64 = base64_encode($this->id.",".$hash.",".$time);
    $log = PrismLog::create(array(
      "type" => "token",
      "request" => json_encode(array(
        'id' => $this->id,
        'hash' => $hash,
        'time' => $time,
        'base64' => $base64
      )),
      "response" => null
    ));

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $this->url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HEADER => true,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Authorization: ".$base64,
        "Locale: id"
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

  public function createProduct($shop_id, $input) {
    $platform = StorePlatform::where('platform','prism')->where('platform_store_id', $shop_id)->first();
    $url = (env('APP_ENV') == 'production') ? "https://partner.prismmobile.com/api/v2/product/add_item" : "https://partner.test-stable.prismmobile.com/api/v2/product/add_item";

    if($platform) {
      $time = time();
      $sign = hash_hmac('sha256', $this->id."/api/v2/product/add_item".$time.$platform->access_token.$shop_id , $this->secret);
      $url = $url."?timestamp=".$time."&id=".$this->id."&sign=".$sign."&shop_id=".$shop_id."&access_token=".$platform->access_token;

      $log = PrismLog::create(array(
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
      $log = PrismLog::create(array(
        "type" => "create_product",
        "request" => json_encode($input),
        "response" => json_encode(array("status" => "error", "message" => "Aunthorized access"))
      ));
    }
  }

  public function refreshToken($shop_id) {
    $platform = StorePlatform::where('platform','prism')->where('platform_store_id', $shop_id)->first();
    if($platform) {
      $url = (env('APP_ENV') == 'production') ? "https://partner.prismmobile.com/api/v2/auth/access_token/get" : "https://partner.test-stable.prismmobile.com/api/v2/auth/access_token/get";

      $time = time();
      $sign = hash_hmac('sha256', $this->id."/api/v2/auth/access_token/get".$time , $this->secret);

      $input = array(
        'refresh_token' => $platform->refresh_token,
        'shop_id' => (int) $shop_id,
        'id' => $this->id
      );

      $url = $url."?timestamp=".$time."&id=".$this->id."&sign=".$sign;

      $log = PrismLog::create(array(
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
    $url = (env('APP_ENV') == 'production') ? "https://partner.prismmobile.com/api/v2/media_space/upload_image" : "https://partner.test-stable.prismmobile.com/api/v2/media_space/upload_image";

    $time = time();
    $sign = hash_hmac('sha256', $this->id."/api/v2/media_space/upload_image".$time , $this->secret);


    $post = ["image" => curl_file_create($image)];

    $url = $url."?timestamp=".$time."&id=".$this->id."&sign=".$sign;

    $log = PrismLog::create(array(
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