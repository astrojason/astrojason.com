<?php

class HomeController extends BaseController {

	public function showIndex() {
	  $fileName = 'assets/js/bookmarkletLoader.min.js';
//	  file_exists
    $bookmarklet = null;
    $categoriesString = null;
    if(Auth::user()) {
      if(file_exists($fileName)) {
        $bookmarklet = str_replace('"', "'", file_get_contents($fileName));
      } else {
        $bookmarklet = null;
      }
      $categoriesString = BookController::getBookCategoryString();
    }
		return View::make('v1.index')->with('bookmarklet', $bookmarklet)->with('book_categories', $categoriesString);
	}

	public function register() {
		return View::make('v1.register');
	}
  public function dashboard() {
	  return View::make('v2.index');
}
}
