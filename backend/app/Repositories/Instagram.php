<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;

class Instagram {
  protected $app_id = "555237569262052";
  protected $client_secret = "951a0c87ea709e99a672629f2067ccf3";
  protected $redirect_uri = "https://marketplace.jpcc.org/instagram/oauth";

  public function __construct()
  {
    // $this->url = (env('APP_ENV') == 'production') ? config('couriers.goodapi.live_url') : config('couriers.goodapi.sandbox_url');;
    // $this->client_id = (env('APP_ENV') == 'production') ? config('couriers.goodapi.live_client_id') : config('couriers.goodapi.sandbox_client_id');
    // $this->secret_key = (env('APP_ENV') == 'production') ? config('couriers.goodapi.live_secret_key') : config('couriers.goodapi.sandbox_secret_key');
    // $this->retry = 0;
  }

  function getToken($code) {
    $user = array();
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.instagram.com/oauth/access_token",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_POST => true,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => array(
        "client_id" => $this->app_id,
        "client_secret" => $this->client_secret,
        "grant_type" => "authorization_code",
        "redirect_uri" => $this->redirect_uri,
        "code"=> $code
      )
    ));

    $resp = json_decode(curl_exec($curl),true);
    if(isset($resp['access_token'])) {
      $user = $resp;
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://graph.instagram.com/access_token?grant_type=ig_exchange_token&client_secret=".$this->client_secret."&access_token=".$user['access_token'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => "GET",
      ));
      $resp = json_decode(curl_exec($curl),true);
      if(isset($resp['access_token'])) {
        $user['access_token'] = $resp['access_token'];
        $user['expires_in'] = $resp['expires_in'];

        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://graph.instagram.com/me?fields=id,username&access_token=".$resp['access_token'],
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $resp = json_decode(curl_exec($curl),true);
        if(isset($resp['id'])) {
          $user['username'] = $resp['username'];
          $user['id'] = $resp['id'];
          return array(
            "status" => "success",
            "user" => $user
          );
        }else {
          return array(
            "status" => "error",
            "message" => $resp['error_message']
          );
        }
      }else {
        return array(
          "status" => "error",
          "message" => $resp['error_message']
        );
      }
    }else {
      return array(
        "status" => "error",
        "message" => $resp['error_message']
      );
    }
  }

  function getMedia($id, $token) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://graph.instagram.com/me/media?fields=id,media_type,media_url,permalink,thumbnail_url&limit=6&access_token=".$token,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_CUSTOMREQUEST => "GET",
    ));
    $resp = json_decode(curl_exec($curl),true);
    if(isset($resp['data'])) {
      return $resp['data'];
    }else {
      return array();
    }
  }

    public function refreshToken($token)
    {
        $url = 'https://graph.instagram.com/refresh_access_token';
        $query = [
            'grant_type' => 'ig_refresh_token',
            'access_token' => $token
        ];

        return Http::get($url, $query)->throw()->json();
    }
}
