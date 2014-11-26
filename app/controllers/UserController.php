<?php

class UserController extends BaseController {
  public function processRegistration(){
    try {
      $user = new User;
      $user->firstname = Input::get('first_name');
      $user->lastname = Input::get('last_name');
      $user->username = Input::get('username');
      $user->email = Input::get('email');
      $user->password = Hash::make(Input::get('password'));
      $user->save();
      return Response::json(array('success' => true), 200);
    } catch (Exception $exception) {
      return Response::json(array('success' => false, 'error' => $exception->getMessage()), 200);
    }
  }

  public function checkUsername() {
    $user = User::where('username', Input::get('username'))->get();
    $available = true;
    if(count($user) > 0) {
      $available = false;
    }
    return Response::json(array('success' => true, 'available' => $available), 200);
  }

  public function checkEmail() {
    $user = User::where('email', Input::get('email'))->get();
    $available = true;
    if(count($user) > 0) {
      $available = false;
    }
    return Response::json(array('success' => true, 'available' => $available), 200);
  }

  public function login() {
    $response = array('success' => false, 'reason' => 'None');
    $rules = array(
      'username'    => 'required',
      'password' => 'required|alphaNum'
    );
    // run the validation rules on the inputs from the form
    $validator = Validator::make(Input::all(), $rules);
    if ($validator->fails()) {
      $response['reason'] = 'Validator failed';
      return Response::json($response);
    } else {
      $userdata = array(
        'username' 	=> Input::get('username'),
        'password' 	=> Input::get('password')
      );
      if (Auth::attempt($userdata, true)) {
        $response['success'] = true;
        $response['user'] = Auth::user()->firstname;
        return Response::json($response);
      } else {
        $response['reason'] = 'Auth failed';
        return Response::json($response);
      }
    }
  }

  public function logout() {
    Auth::logout();
    $response = array('success' => true);
    return Response::json($response);
  }
} 