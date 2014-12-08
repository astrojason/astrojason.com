<?php

class TemplateController extends BaseController {
  public function linkForm() {
    $dbCategories = Link::groupBy('category')
      ->where('user_id', Auth::user()->id)
      ->get(array('category'));
    $categories = "['New'";
    foreach($dbCategories as $category) {
      $categories .= ", '" . $category->category . "'";
    }
    $categories .= "]";
    return View::make('templates/linkForm')->with('categories', $categories);
  }
} 