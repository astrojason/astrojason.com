<?php

/**
 * User: jasonsylvester
 * Date: 5/10/16
 * Time: 11:06 AM
 */
class UserController extends BaseController {

  public function account() {
    return View::make('user.account')->with(['user' => Auth::user()]);
  }

  public function update() {
    /** @var User $user */
    $user = Auth::user();
    $params = Input::all();
    $rules = [
      'email' => "required|unique:users,email,$user->id",
      'firstname' => 'required',
      'lastname' => 'required'
    ];
    $messages = [
      'valid_password' => 'You have entered an incorrect password.'
    ];
    if($params['password'] != '') {
      Validator::extend('valid_password', function($attribute, $value) {
        return Hash::check($value, Auth::user()->password);
      });
      $rules['current_password'] = 'required|valid_password';
    }
    $validator = Validator::make($params, $rules, $messages);
    if ($validator->fails()) {
      return Redirect::to('account')
        ->withErrors($validator);
    } else {
      $user->firstname = $params['firstname'];
      $user->lastname = $params['lastname'];
      $user->email = $params['email'];
      if($params['password'] != '') {
        $user->password = Hash::make($params['password']);
      }
      $user->save();
      Session::flash('success', 'Account changes saved');
      return Redirect::to('account');
    }
  }

}