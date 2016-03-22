<?php


namespace Api;

use Illuminate\Http\Response as IlluminateResponse;

use Auth, Exception, Hash, Input, User, Validator;

class UserController extends AstroBaseController {

  public function login() {
    $rules = array(
      'username'    => 'required',
      'password' => 'required|alphaNum'
    );
    // run the validation rules on the inputs from the form
    $validator = Validator::make(Input::all(), $rules);
    if ($validator->fails()) {
      return $this->errorResponse('Validator failed', IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY);
    } else {
      $userdata = array(
        'username' 	=> Input::get('username'),
        'password' 	=> Input::get('password')
      );
      if (Auth::attempt($userdata, true)) {
        return $this->successResponse(array('user' => Auth::user()->toArray()));
      } else {
        return $this->errorResponse('Auth failed', IlluminateResponse::HTTP_UNAUTHORIZED);
      }
    }
  }

  public function logout() {
    Auth::logout();
    return $this->successResponse();
  }

  public function checkEmail() {
    $user = User::where('email', Input::get('email'))->get();
    $available = true;
    if(count($user) > 0) {
      $available = false;
    }
    return $this->successResponse(array('available' => $available));
  }

  public function checkUsername() {
    $user = User::where('username', Input::get('username'))->get();
    $available = true;
    if(count($user) > 0) {
      $available = false;
    }
    return $this->successResponse(array('available' => $available));
  }

  public function processRegistration(){
    try {
      $user = new User;
      $user->firstname = Input::get('first_name');
      $user->lastname = Input::get('last_name');
      $user->username = Input::get('username');
      $user->email = Input::get('email');
      $user->password = Hash::make(Input::get('password'));
      $user->save();
      return $this->successResponse();
    } catch (Exception $exception) {
      return $this->errorResponse($exception->getMessage(), IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY);
    }
  }

  public function transform($data) {

  }

}