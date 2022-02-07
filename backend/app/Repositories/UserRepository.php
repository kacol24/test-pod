<?php

namespace App\Repositories;

use App\Models\User\User;
use App\Models\User\UserAddress;

class UserRepository {
  function insertAddress($input, $return = false) {
    $input['address'] = preg_replace( "/\r|\n/", " ", $input['address'] );
    if(!session('store') || session('store')->indonesia_only) {
      $input['country'] = 'Indonesia';
    }

    if($input['address_id']) {
      $address = UserAddress::where('id', $input['address_id'])->where('user_id', $input['user_id'])->first();
      $address->name = htmlentities($input['name']);
      $address->email = htmlentities($input['email']);
      $address->phone = htmlentities($input['phone']);
      $address->address = htmlentities($input['address']);
      $address->country = $input['country'];
      $address->state = $input['state'];
      $address->city = $input['city'];
      $address->subdistrict = $input['subdistrict'];
      $address->postcode = htmlentities($input['postcode']);
      if($input['is_primary']) {
        $address->is_primary = $input['is_primary'];  
      }
      if(isset($input['geolabel']) && $input['geolabel']) {
        $address->latitude = $input['latitude'];
        $address->longitude = $input['longitude'];
        $address->geolabel = $input['geolabel'];
      }
      $address->save();
    }else {
      if(!isset($input['geolabel']) || !$input['geolabel']) {
        unset($input['longitude']);
        unset($input['latitude']);
      }
      $address = UserAddress::create($input);
    }
    if($input['is_primary']) {
      UserAddress::where('user_id', $input['user_id'])->where('id','!=', $address->id)->update(array('is_primary' => 0));
      UserAddress::where('user_id', $input['user_id'])->where('id','=', $address->id)->update(array('is_primary' => 1));
    }

    if($return) {
      if($address) {
        return ['status' => 'success','message' => 'Success save address', 'addresses' => UserAddress::where('user_id', $input['user_id'])->orderBy('is_primary','desc')->get(), 'address_id' => $address->id, 'address' => $address];
      } else {
        return ['status' => 'error','message' => 'Failed to save address'];
      }
    }
  }
}
