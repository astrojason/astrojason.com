<?php

use App\Models\Book as Book;

class BookController extends BaseController {

  public function index() {
    $categoriesString = BookController::getBookCategoryString();
    return View::make('books.index')->with('book_categories', $categoriesString);
  }


  public function goodreads() {
    return View::make('books.goodreads');
  }

  /**
   * @return string
   */
  public static function getBookCategoryString() {
    $categories = [];
    $dbCategories = Book::groupBy('category')
      ->where('user_id', Auth::user()->id)
      ->get(array('category'));
    foreach ($dbCategories as $category) {
      $categories[] = $category->category;
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
