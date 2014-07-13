<?php

class MainController extends BaseController {

	public function showMain(){
		return View::make('dashboard');
	}

  public function showLinks(){
    return View::make('links');
  }

  public function showBooks(){
    return View::make('books');
  }

  public function showGames(){
    return View::make('games');
  }

  public function logout(){
    Auth::logout();
    return Redirect::to('/');
  }

}