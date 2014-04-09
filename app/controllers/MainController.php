<?php

class MainController extends BaseController {

	public function showMain(){
    $password = Hash::make('awesome');
		return View::make('main')->with('password', $password);
	}

}