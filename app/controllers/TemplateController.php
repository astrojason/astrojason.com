<?php

class TemplateController extends BaseController {
  public function linkForm() {
    $dbCategories = Link::groupBy('category')->get(array('category'));
    $categories = "['New'";
    foreach($dbCategories as $category) {
      $categories .= ", '" . $category->category . "'";
    }
    $categories .= "]";
    return View::make('templates/linkForm')->with('categories', $categories);
  }
} 