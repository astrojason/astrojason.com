<?php

class HomeController extends BaseController {

	public function showIndex() {
    $categories = null;
    if(Auth::user()) {
      $dbCategories = Link::groupBy('category')
        ->where('category', '<>', 'Daily')
        ->where('user_id', Auth::user()->id)
        ->get(array('category'));
      $categories = "[";
      foreach($dbCategories as $category) {
        $categories .= "'" . $category->category . "',";
      }
      $categories .= "]";
    }
		return View::make('index')->with('categories', $categories);
	}
}