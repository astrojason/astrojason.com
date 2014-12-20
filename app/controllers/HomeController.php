<?php

class HomeController extends BaseController {

	public function showIndex() {
    $categories = null;
    $bookmarklet = null;
    if(Auth::user()) {
      $bookmarklet = str_replace('"', "'", file_get_contents('assets/js/bookmarkletLoader.min.js'));
      $dbCategories = Link::groupBy('category')
        ->where('category', '<>', 'Daily')
        ->where('category', '<>', 'Unread')
        ->where('user_id', Auth::user()->id)
        ->get(array('category'));
      $categories = "[";
      foreach($dbCategories as $category) {
        $categories .= "'" . $category->category . "',";
      }
      $categories .= "]";
    }
		return View::make('index')->with('categories', $categories)->with('bookmarklet', $bookmarklet);
	}
}