<?php

class HomeController extends BaseController {

	public function showIndex() {
    $bookmarklet = null;
    if(Auth::user()) {
      $bookmarklet = str_replace('"', "'", file_get_contents('assets/js/bookmarkletLoader.min.js'));
    }
		return View::make('index')->with('bookmarklet', $bookmarklet);
	}
}