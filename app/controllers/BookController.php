<?php

use \Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class BookController extends BaseController {

  public function index() {
    $categoriesString = BookController::getBookCategoryString();
    return View::make('books.index')->with('book_categories', $categoriesString);
  }

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
        $query->orderBy($sort);
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
    return Response::json(array('books' => $books->toArray(), 'total' => $total, 'pages' => $pageCount), SymfonyResponse::HTTP_OK);
  }

  public function recommendation($category) {
    $book = Book::where('is_read', false)
      ->where('category', $category)
      ->where('user_id', Auth::user()->id)
      ->orderBy(DB::raw('RAND()'))
      ->first();
    if($book) {
      if ($book->series_order > 0) {
        $book = Book::where('is_read', false)
          ->where('user_id', Auth::user()->id)
          ->where('series', $book->series)
          ->orderBy('series_order')
          ->first();
      }
      $book->times_loaded = $book->times_loaded + 1;
      $book->save();
      return Response::json(array('success' => true, 'book' => $book->toArray()), SymfonyResponse::HTTP_OK);
    } else {
      return Response::json(array('success' => false, 'error' => 'No book found'), SymfonyResponse::HTTP_NOT_FOUND);
    }
  }

  public function delete() {
    $id = Input::get('id');
    $book = Book::where('id', $id)->where('user_id', Auth::user()->id)->first();
    if(isset($book)) {
      $book->delete();
      return Response::json(array('success' => true), SymfonyResponse::HTTP_OK);
    } else {
      return Response::json(array('success' => false, 'error' => 'No book with that id exists'), SymfonyResponse::HTTP_NOT_FOUND);
    }
  }

  public function save() {
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
    if(Input::get('id')) {
      $book = Book::where('id', Input::get('id'))->where('user_id', Auth::user()->id)->first();
    } else {
      $book = Book::where('title', $title)
        ->where('author_lname', $lname)
        ->where('user_id', Auth::user()->id)
        ->first();
      if(isset($book)) {
        return Response::json(array('success' => false, 'error' => 'Book already exists'), SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY);
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
    return Response::json(array('success' => true, 'book' => $book->toArray()), SymfonyResponse::HTTP_OK);
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
