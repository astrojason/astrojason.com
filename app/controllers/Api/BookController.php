<?php

namespace Api;

class BookController extends AstroBaseController {

  public function query() {
    $astroSql = new \AstroBookRepo();
    $astroSql->userId = \Auth::user()->id;

    $this->get($astroSql);
    if($astroSql->errors) {
      return $this->errorResponse($astroSql->errors, $astroSql->errorCode);
    } else {
      $results = $astroSql->getData();
      $books = $results['books'];
      $total = $results['total'];
      $pageCount = $results['pageCount'];
      return $this->successResponse(array('books' => $this->transform($books->toArray()), 'total' => $total, 'pages' => $pageCount));
    }
  }

  public function get(\AstroData $dataProvider) {
    $dataProvider->getData();
  }

  public function recommendation($category) {
    $book = \Book::where('is_read', false)
      ->where('category', $category)
      ->where('user_id', \Auth::user()->id)
      ->orderBy(\DB::raw('RAND()'))
      ->first();
    if($book) {
      if ($book->series_order > 0) {
        $book = \Book::where('is_read', false)
          ->where('user_id', \Auth::user()->id)
          ->where('series', $book->series)
          ->orderBy('series_order')
          ->first();
      }
      $book->times_recommended += 1;
      $book->save();
      return $this->successResponse(array('book' => $book->toArray()));
    } else {
      return $this->notFoundResponse('No book found with that id');
    }
  }

  public function delete() {
    $id = \Input::get('id');
    $book = \Book::where('id', $id)->where('user_id', \Auth::user()->id)->first();
    if(isset($book)) {
      $book->delete();
      return $this->successResponse();
    } else {
      return $this->notFoundResponse('No book found with that id');
    }
  }

  public function save() {
    $title = \Input::get('title');
    $fname = \Input::get('author_fname');
    $lname = \Input::get('author_lname');
    $category = \Input::get('category');
    $series = \Input::get('series');
    $is_read = filter_var(\Input::get('is_read'), FILTER_VALIDATE_BOOLEAN);
    $owned = filter_var(\Input::get('owned'), FILTER_VALIDATE_BOOLEAN);
    $series_order = null;
    if ($series) {
      $series_order = \Input::get('series_order');
    }
    if(\Input::get('id')) {
      $book = \Book::where('id', \Input::get('id'))->where('user_id', \Auth::user()->id)->first();
    } else {
      $book = \Book::where('title', $title)
        ->where('author_lname', $lname)
        ->where('user_id', \Auth::user()->id)
        ->first();
      if(isset($book)) {
        return $this->notFoundResponse('Book already exists');
      }
      $book = new \Book();
      $book->user_id = \Auth::user()->id;
    }
    $book->title = $title;
    $book->author_fname = $fname;
    $book->author_lname = $lname;
    $book->category = $category;
    $book->is_read = $is_read;
    $book->owned = $owned;
    if($series) {
      $book->series = $series;
      $book->series_order = $series_order;
    }
    $book->save();
    return $this->successResponse(array('book' => $book->toArray()));
  }

  public function goodreads() {
    $page = \Input::get('page', 1);
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
      $book = \Book::where('user_id', \Auth::user()->id)->where('title', $title)->first();
      $books['titles'][] = [
        'goodreads_id' => (int)$review['book']['id'],
        'title' => $title,
        'author' => $review['book']['authors']['author']['name'],
        'average_rating' => (float)$review['book']['average_rating'],
        'id' => isset($book) ? $book->id : 0
      ];
    }

    return $this->successResponse(array('books' => $books));
  }

  public function transform($data){
    $transformedData = [];
    foreach($data as $book){
      $transformedData[] = [
        'id' => (int)$book['id'],
        'title' => $book['title'],
        'author_fname' => $book['author_fname'],
        'author_lname' => $book['author_lname'],
        'series' => $book['series'],
        'series_order' => $book['series_order'] ? (int)$book['series_order'] : 0,
        'category' => $book['category'],
        'owned' => (bool)$book['owned'],
        'is_read' => (bool)$book['is_read']
      ];
    }
    return $transformedData;
  }

}