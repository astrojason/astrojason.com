<?php

use \Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class BookController extends BaseController {

  public function index() {
    $categoriesString = BookController::getBookCategoryString();
    return View::make('books.index')->with('book_categories', $categoriesString);
  }

  public function goodreadsView() {
    return View::make('books.goodreads');
  }

  public function goodreads() {
    $page = Input::get('page', 1);
    $url = 'https://www.goodreads.com/review/list/1387939?format=xml&key=LWJgJG6enKKElIBM6nzNyw&v=2';
    $params = ['id' => 1387939, 'shelf' => 'to-read', 'key' => 'LWJgJG6enKKElIBM6nzNyw', 'page' => $page];
    $url .= '&' . http_build_query($params, null, '&', PHP_QUERY_RFC3986);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return the result on success, rather than just TRUE
    $curlResponse = curl_exec($ch);
    $xml = simplexml_load_string($curlResponse);
    $json = json_encode($xml);
    $array = json_decode($json, true);
    $books = ['end' => $array['reviews']['@attributes']['end'],
      'page' => $page,
      'total' => $array['reviews']['@attributes']['total'],
      'titles' => []];
    foreach($array['reviews']['review'] as $review) {
      $title = $review['book']['title'];
      $book = Book::where('user_id', Auth::user()->id)->where('title', $title)->first();
      $books['titles'][] = [
        'goodreads_id' => $book['book']['id'],
        'title' => $title,
        'author' => $review['book']['authors']['author']['name'],
        'average_rating' => $review['book']['average_rating'],
        'id' => isset($book) ? $book->id : 0
      ];
    }

    return Response::json(array('books' => $books->toArray()), SymfonyResponse::HTTP_OK);
  }

  /**
   * @return string
   */
  public static function getBookCategoryString()
  {
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
