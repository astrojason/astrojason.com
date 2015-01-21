<?php

class HomeController extends BaseController {

	public function showIndex() {
    $bookmarklet = null;
		$bookCategories = null;
    if(Auth::user()) {
      $bookmarklet = str_replace('"', "'", file_get_contents('assets/js/bookmarkletLoader.min.js'));
			// TODO: Make this a shared function
			$dbCategories = Book::groupBy('category')
			->where('user_id', Auth::user()->id)
			->get(array('category'));
			foreach($dbCategories as $category) {
				$categories[] = $category->category;
			}
			$lastElement = end($categories);
			$categoriesString = '[';
			foreach($categories as $category) {
				$categoriesString .= '\'' . $category . '\'';
				if($category != $lastElement) {
					$categoriesString .= ',';
				}
			}
			$categoriesString .= ']';
    }
		return View::make('index')->with('bookmarklet', $bookmarklet)->with('book_categories', $categoriesString);
	}

	public function register() {
		return View::make('register');
	}
}
