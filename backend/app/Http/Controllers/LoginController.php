<?php 

namespace App\Http\Controllers;

use Validator, Mail, Hash, Lang;
use Theme, View, Form;
use Illuminate\Http\Request;
use App\Mail\ResetPasswordAdmin;
use App\Http\Controllers\Controller;
use App\Models\Setting\Admin;
use Illuminate\Support\Str;

class LoginController extends Controller {
  public function getReset(Request $request, $token) {
    $data = array(
      'token' => $token,
      'store_id'  => $request->store
    );
    return view('reset', $data);
  }

  public function postReset(Request $request, $token) {
    if($token) {
      $admin = Admin::where('email', $request->input('email'))->where('store_id', $request->store_id)->where('remember_token', $token)->whereNotNull('remember_token')->first();
      if($admin) {
        $admin->password = Hash::make($request->input('password'));
        $admin->remember_token = '';
        $admin->save();
        return redirect()->route('login')->with('message', 'Update password success.');  
      } else {
        return redirect()->back()->withErrors(array('Invalid token'));  
      }
    }else {
      return redirect()->back()->withErrors(array('Invalid token'));  
    }
  }

  public function getForget() {
    return view('forget');
  }

  public function postForget(Request $request) {
    $data['token'] = hash_hmac('sha256', Str::random(40), config('app.key'));
    $data['email'] = $request->input('email');

    $admin = Admin::where('email', $data['email'])->where('store_id', session('store')->id)->first();
    if($admin) {
      $admin->remember_token = $data['token'];
      $admin->save();
      Mail::to($data['email'])->send(new ResetPasswordAdmin($admin));

      return redirect()->route('forget')->with('message', 'Please check your email for reset your password.');
    }else {
      return redirect()->back()->withErrors(array('User not found'));
    }
      
  }

  public function getLogin() {
    return view('login');
  }

  public function postLogin(Request $request) {
    $request->flash();
    
    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
      'password' => 'required'
    ]);

    if ($validator->fails()) {
      return redirect()->route('login')->withErrors($validator)->withInput();
    }

    $input = $request->all();
    $admin = Admin::where('email', $input['email'])->first();
    if($admin && Hash::check($input['password'], $admin->password)) {
      session(['admin' => $admin]);
      session(['language' => config('app.locale')]);
      if(count(session('admin')->permissions)) {
        return redirect()->route(session('admin')->permissions->first()->feature->action_name)->cookie('guest_token', session()->getId(), 86400 * 3);  
      }else {
        return redirect()->route('login')->withErrors(array('Invalid permission'));  
      }
    }else {
      return redirect()->route('login')->withErrors(array('Invalid user'));
    } 
  }

  public function logout() {
    session(['admin'=> null]);
    return redirect()->route('login');
  }

  public function help() {
    $data = array(
      'page_title' => Lang::get('menuhelp'),
      'active' => 'help',
    );

    return view('help', $data);
  }
}