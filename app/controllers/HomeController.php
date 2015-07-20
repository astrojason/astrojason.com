<?php

class HomeController extends BaseController {

	public function showIndex() {
    $bookmarklet = null;
    $categoriesString = null;
    if(Auth::user()) {
      $bookmarklet = str_replace('"', "'", file_get_contents('assets/js/bookmarkletLoader.min.js'));
      $categoriesString = BookController::getBookCategoryString();
    }
		return View::make('index')->with('bookmarklet', $bookmarklet)->with('book_categories', $categoriesString);
	}

	public function register() {
		return View::make('register');
	}
}
