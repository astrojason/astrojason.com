<?php

/**
 * User: jasonsylvester
 * Date: 5/10/16
 * Time: 11:06 AM
 */

use Article\Category, Article\DailySetting, Article\Recommended, Carbon\Carbon;

class UserController extends BaseController {

  public function account() {
    $user = Auth::user();
    $userSettings = DailySetting::where('user_id', $user->id)->get();
    $categories = Category::where('user_id', $user->id)->get();
    $selectedIds = [];
    foreach($userSettings as $userSetting) {
      $selectedIds[] = $userSetting->category_id ? $userSetting->category_id : 0;
    }
    return View::make('v1.user.account')->with([
      'user' => $user,
      'articleSettings' => $userSettings,
      'categories' => $categories,
      'selectedIds' => $selectedIds
    ]);
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
      $updateItems = Input::get('update', []);
      $updateCategories = Input::get('articleSettingsCategory', []);
      $updateNumbers = Input::get('articleSettingsNumber', []);
      $updateAllowRead = Input::get('articleSettingsAllowRead', []);
      foreach ($updateItems as $id => $value) {
        $itemToUpdate = DailySetting::whereId($id)->firstOrFail();
        if($itemToUpdate->user_id == $user->id) {
          $itemToUpdate->category_id = $updateCategories[$id];
          $itemToUpdate->number = $updateNumbers[$id];
          $itemToUpdate->allow_read = isset($updateAllowRead[$id]);
          $itemToUpdate->save();
        }
      }
      $deleteItems = Input::get('delete', []);
      foreach ($deleteItems as $id => $value) {
        /** @var DailySetting $itemToDelete */
        $itemToDelete = DailySetting::whereId($id)->firstOrFail();
        if($itemToDelete->user_id == $user->id) {
          $itemToDelete->delete();
        }
      }
      if(filter_var(Input::get('newArticleSetting_save', false), FILTER_VALIDATE_BOOLEAN)){
        $newDashboardCategory = new DailySetting([
          'category_id' => $params['newArticleSetting_category'],
          'number' => $params['newArticleSetting_number'] ? $params['newArticleSetting_number'] : 0,
          'allow_read' => filter_var($params['newArticleSetting_number'], FILTER_VALIDATE_BOOLEAN),
          'user_id' => $user->id
        ]);
        $newDashboardCategory->save();
      }
      if(filter_var(Input::get('resetToday', false), FILTER_VALIDATE_BOOLEAN)){
        $recent = Carbon::create();
        Recommended::where('created_at', '>', $recent->toDateString())
          ->where('user_id', $user->id)
          ->delete();
      }
      Session::flash('success', 'Account changes saved');
      return Redirect::to('account');
    }
  }

}