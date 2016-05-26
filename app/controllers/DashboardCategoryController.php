<?php

/**
 * User: jasonsylvester
 * Date: 5/25/16
 * Time: 9:34 AM
 */
class DashboardCategoryController extends BaseController {

  public function add() {
    $validator = $this->validate();
    if($validator->passes()) {
      $params = Input::all();
      $dashboardCategory = new DashboardCategory();
      $dashboardCategory->category = $params['category'];
      $dashboardCategory->position = $params['position'];
      $dashboardCategory->user_id = Auth::user()->id;

      if($params['num_items'] && $params['num_items'] > 0) {
        $dashboardCategory->num_items = $params['num_items'];
      }
      $dashboardCategory->randomize = array_key_exists('randomize', $params);
      $dashboardCategory->track = array_key_exists('track', $params);
      $dashboardCategory->save();
      Session::flash('success', 'Dashboard category added');
    } else {
      return Redirect::to('account')
        ->withErrors($validator);
    }

    return Redirect::to('account');
  }

  public function update($dashboardCategoryId) {
    $validator = $this->validate();
    if($validator->passes()) {
      try {
        $dashboardCategory = DashboardCategory::whereId($dashboardCategoryId)
          ->where('user_id', Auth::user()->id)->firstOrFail();
        $params = Input::all();
        $dashboardCategory->category = $params['category'];
        $dashboardCategory->randomize = array_key_exists('randomize', $params);
        $dashboardCategory->track = array_key_exists('track', $params);
        $dashboardCategory->num_items = $params['num_items'] && $params['num_items'] > 0 ? $params['num_items'] : 0;
        $dashboardCategory->save();
        Session::flash('success', 'Dashboard category updated');
        return Redirect::to('account');
      } catch (Exception $e){
        return Redirect::to('account');
      }
    } else {
      return Redirect::to('account')
        ->withErrors($validator);
    }
  }

  private function validate() {
    $params = Input::all();
    $rules = [
      'category' => "required",
      'position' => "required",
    ];
    return Validator::make($params, $rules);
  }

}