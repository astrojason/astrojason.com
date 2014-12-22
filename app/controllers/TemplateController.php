<?php

class TemplateController extends BaseController {
  public function linkForm() {
    $categories = ['New', 'Unread'];
    if(Auth::user()) {
      $dbCategories = Link::groupBy('category')
        ->where('user_id', Auth::user()->id)
        ->where('category', '<>', 'Unread')
        ->get(array('category'));
      foreach($dbCategories as $category) {
        array_push($categories, $category->category);
      }
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
    return View::make('templates/linkForm')->with('categories', $categoriesString);
  }
} 