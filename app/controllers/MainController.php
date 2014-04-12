<?php

class MainController extends BaseController {

	public function showMain(){
		return View::make('main');
	}

}