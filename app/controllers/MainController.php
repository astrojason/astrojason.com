<?php

class MainController extends BaseController {

	public function showMain(){
		return View::make('main');
	}

  public function logout(){
    Auth::logout();
    return Redirect::to('/');
  }

}