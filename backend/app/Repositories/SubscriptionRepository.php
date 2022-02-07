<?php

namespace App\Repositories;

class SubscriptionRepository
{
  public function __construct(){
  
  }

  function subscribe($email) {
    $store = store();
    if($store->subscription == 'mailchimp') {
      return $this->mailchimp($email);
    }if($store->subscription == 'mailtarget') {
      return $this->mailtarget($email);
    } 
  }

  function mailtarget($email) {
    $store = store();
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, config('subscription.'.$store->subscription.'.url'));
    // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
    //   'Authorization: Basic '.$auth));
    curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array('email' => $email)));
    $result = json_decode(curl_exec($ch));

    if($result) {
      return array('status' => 'success', 'message' => $email .' success to subscribe');
    }else
      return array('status' => 'error', 'message' => 'Invalid request, please try again');  
  }

  function mailchimp($email) {
    $store = store();
    $auth = base64_encode( 'user:'.config('subscription.'.$store->subscription.'.apikey') );
    $data = array(
      'apikey'        => config('subscription.'.$store->subscription.'.apikey'),
      'email_address' => $email,
      'status'        => 'subscribed',
    );

    $json_data = json_encode($data);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://'.config('subscription.'.$store->subscription.'.server').'.api.mailchimp.com/3.0/lists/'.config('subscription.'.$store->subscription.'.listid').'/members');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
      'Authorization: Basic '.$auth));
    curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    $result = json_decode(curl_exec($ch));

    if($result != null)
    {
      $result->status = (String) $result->status;

      if($result->status == 'subscribed')
        return array('status' => 'success', 'message' => $email .' success to subscribe');
      else
        return array('status' => 'error' ,'message' => $email .' is already a list member.');
    }
    else
      return array('status' => 'error', 'message' => 'Invalid request, please try again');  
  }
}
