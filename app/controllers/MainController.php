<?php

class MainController extends BaseController {

	public function showMain(){
		return View::make('main');
	}

  public function showLinks(){
    return View::make('links');
  }

  public function showBooks(){
    return View::make('books');
  }

  public function logout(){
    Auth::logout();
    return Redirect::to('/');
  }

}