<?php

/**
 * Class LinkController
 */

class LinkController extends BaseController {

  public function index() {
    $categories = LinkController::getLinkCategoryString();
    return View::make('v1.links.index')->with('link_categories', $categories);
  }

  public function readLater() {
    $title = addslashes(Input::get('title'));
    $link = Input::get('link');
    return View::make('v1.readlater')->with('title', $title)->with('link', $link);
  }

  /**
   * @return array
   */
  public static function getLinkCategories() {
    $categories = [];
    $dbCategories = Link::groupBy('category')
      ->where('category', '<>', 'Daily')
      ->where('category', '<>', 'Unread')
      ->where('user_id', Auth::user()->id)
      ->get(array('category'));
    foreach ($dbCategories as $category) {
      array_push($categories, $category->category);
    }
    return $categories;
  }

  /**
   * @return string
   */
  public static function getLinkCategoryString() {
    $categories = [];
    $dbCategories = self::getLinkCategories();
    foreach ($dbCategories as $category) {
      $categories[] = $category;
    }
    if ($categories) {
      $lastElement = end($categories);
    }
    $categoriesString = '[';
    foreach ($categories as $category) {
      $categoriesString .= '\'' . $category . '\'';
      if ($category != $lastElement) {
        $categoriesString .= ',';
      }
    }
    $categoriesString .= ']';
    return $categoriesString;
  }
}
