<?php

/**
 * Class ArticleController
 */

class ArticleController extends BaseController {

  public function index() {
    $categories = ArticleController::getLinkCategoryString();
    return View::make('v1.articles.index')->with('link_categories', $categories);
  }

  public function readLater() {
    $title = addslashes(Input::get('title'));
    $url = Input::get('url');
    return View::make('v1.readlater')->with('title', $title)->with('url', $url);
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
