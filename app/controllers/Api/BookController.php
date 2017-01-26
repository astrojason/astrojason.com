<?php

namespace Api;

use Auth, App\Models\Book as Book, DB, Input;

class BookController extends AstroBaseController {

  public function query() {
    $pageCount = 0;
    $page = Input::get('page');
    $q = Input::get('q');
    $limit = Input::get('limit');
    $category = Input::get('category');
    $sort = Input::get('sort');
    $query = Book::query()->where('user_id', Auth::user()->id);
    $randomize = filter_var(Input::get('randomize'), FILTER_VALIDATE_BOOLEAN);
    $include_read = filter_var(Input::get('include_read'), FILTER_VALIDATE_BOOLEAN);
    $descending = filter_var(Input::get('descending'), FILTER_VALIDATE_BOOLEAN);
    if(isset($q)) {
      $query->where(function($query) use ($q) {
        $query->where('title', 'LIKE', '%' . $q . '%')
          ->orwhere('series', 'LIKE', '%' . $q . '%')
          ->orwhere('author_lname', 'LIKE', '%' . $q . '%');
      });
    }
    if(!$include_read) {
      $query->where('is_read', false);
    }
    if($randomize){
      $query->orderBy(DB::raw('RAND()'));
    }
    if(isset($limit)){
      $query->take($limit);
    }
    if(isset($category)) {
      $query->where('category', $category);
    }
    if(isset($sort)) {
      if($sort == 'series') {
        $query->where('series', '!=', '');
        $query->orderBy('series');
        $query->orderBy('series_order');
      }
      else {
        $query->orderBy($sort, $descending ? 'DESC' : 'ASC');
      }
    }
    $total = $query->count();
    if (isset($limit)) {
      $pageCount = ceil($total / $limit);
      $query->take($limit);
      if (isset($page) && $page > 1 && !$randomize) {
        $query->skip($limit * ($page - 1));
      }
    }
    $books = $query->get();
    return $this->successResponse(array('books' => $this->transformCollection($books), 'total' => $total, 'pages' => $pageCount));
  }

  public function populate() {
    $books = Book::orderBy(DB::raw('RAND()'))->take(20)->get();
    foreach ($books as $book) {
      $userBook = new Book();
      $userBook->title = $book->title;
      $userBook->user_id = Auth::user()->id;
      $userBook->author_fname = $book->author_fname;
      $userBook->author_lname = $book->author_lname;
      $userBook->category = 'To Read';
      $userBook->series = $book->series;
      $userBook->series_order = $book->series_order;
      $userBook->save();
    }
    $books = Book::query()->where('user_id', Auth::user()->id)->get();
    return $this->successResponse(array('books' => $this->transformCollection($books)));
  }

  public function recommendation($category) {
    $book = Book::where('is_read', false)
      ->where('category', $category)
      ->where('user_id', Auth::user()->id)
      ->orderBy(\DB::raw('RAND()'))
      ->first();
    if($book) {
      if ($book->series_order > 0) {
        $book = Book::where('is_read', false)
          ->where('user_id', Auth::user()->id)
          ->where('series', $book->series)
          ->orderBy('series_order')
          ->first();
      }
      $book->times_recommended += 1;
      $book->save();
      return $this->successResponse(array('book' => $this->transform($book)));
    } else {
      return $this->notFoundResponse('You do not have any unread books');
    }
  }

  public function delete($bookId) {
    $book = Book::where('id', $bookId)->where('user_id', Auth::user()->id)->first();
    if(isset($book)) {
      $book->delete();
      return $this->successResponse();
    } else {
      return $this->notFoundResponse('No book found with that id');
    }
  }

  public function save($bookId = null) {
    $title = Input::get('title');
    $fname = Input::get('author_fname');
    $lname = Input::get('author_lname');
    $category = Input::get('category');
    $series = Input::get('series');
    $is_read = filter_var(Input::get('is_read'), FILTER_VALIDATE_BOOLEAN);
    $owned = filter_var(Input::get('owned'), FILTER_VALIDATE_BOOLEAN);
    $series_order = null;
    if ($series) {
      $series_order = Input::get('series_order');
    }
    if($bookId) {
      $book = Book::where('id', $bookId)->where('user_id', Auth::user()->id)->first();
      if(!isset($book)) {
        return $this->notFoundResponse('No book found with that id');
      }
    } else {
      $book = Book::where('title', $title)
        ->where('author_lname', $lname)
        ->where('user_id', Auth::user()->id)
        ->first();
      if(isset($book)) {
        return $this->errorResponse('A book with that name buy an author with the same last name already exists');
      }
      $book = new Book();
      $book->user_id = Auth::user()->id;
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
    return $this->successResponse(array('book' => $this->transform($book)));
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
        'goodreads_id' => (int)$review['book']['id'],
        'title' => $title,
        'author' => $review['book']['authors']['author']['name'],
        'average_rating' => (float)$review['book']['average_rating'],
        'id' => isset($book) ? $book->id : 0
      ];
    }
    return $this->successResponse(array('books' => $books));
  }

  public function transform($book){
    return [
        'id' => (int)$book['id'],
        'title' => $book['title'],
        'author_fname' => $book['author_fname'],
        'author_lname' => $book['author_lname'],
        'series' => $book['series'],
        'series_order' => $book['series_order'] ? (int)$book['series_order'] : 0,
        'category' => $book['category'],
        'owned' => (bool)$book['owned'],
        'is_read' => (bool)$book['is_read'],
        'times_recommended' => (int)$book['times_recommended']
      ];
  }

}