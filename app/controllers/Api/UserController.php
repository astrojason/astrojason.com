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
        return $this->successResponse(['user' => $this->transform(Auth::user())]);
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
    if($this->email_exists(Input::get('email'))) {
      return $this->errorResponse();
    }
    return $this->successResponse();
  }

  public function checkUsername() {

    if($this->username_exists(Input::get('username'))) {
      return $this->errorResponse();
    }
    return $this->successResponse();
  }

  public function processRegistration(){
    try {
      if(!$this->email_exists(Input::get('email')) && !$this->username_exists(Input::get('username'))) {
        $user = new User;
        $user->firstname = Input::get('first_name');
        $user->lastname = Input::get('last_name');
        $user->username = Input::get('username');
        $user->email = Input::get('email');
        $user->password = Input::get('password');
        $user->save();
        return $this->successResponse();
      } else {
        return $this->errorResponse('Username and/or email already exists',
          IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY);
      }
    } catch (Exception $exception) {
      return $this->errorResponse($exception->getMessage(), IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY);
    }
  }

  public function username_exists($username) {
    $user = User::where('username', $username)->first();
    return isset($user);
  }

  public function email_exists($email) {
    $user = User::where('email', $email)->first();
    return isset($user);
  }

  public function transform($user) {
    return [
      'id' => (int)$user['id'],
      'firstname' => $user['firstname'],
      'lastname' => $user['lastname'],
      'username' => $user['username'],
      'email' => $user['email']
    ];
  }

}