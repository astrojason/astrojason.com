<?php

class BookController extends BaseController {

  public function index() {
    $categoriesString = BookController::getBookCategoryString();
    return View::make('books.index')->with('book_categories', $categoriesString);
  }

  public function query() {
    $query = Book::query()->where('user_id', Auth::user()->id);
    $q = Input::get('q');
    $limit = Input::get('limit');
    $randomize = filter_var(Input::get('randomize'), FILTER_VALIDATE_BOOLEAN);
    $include_read = filter_var(Input::get('include_read'), FILTER_VALIDATE_BOOLEAN);
    $category = Input::get('category');
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
    $books = $query->get();
    return Response::json(array('books' => $books->toArray()), 200);
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
      return Response::json(array('success' => true, 'book' => $book->toArray()), 200);
    } else {
      return Response::json(array('success' => false, 'error' => 'No book found'), 200);
    }
  }

  public function read($id) {
    $book = Book::where('id', $id)->where('user_id', Auth::user()->id)->first();
    if(isset($book)) {
      $book->is_read = true;
      $book->save();
      return Response::json(array('success' => true), 200);
    } else {
      return Response::json(array('success' => false, 'error' => 'No book with that id exists'), 200);
    }
  }

  public function unread($id) {
    $book = Book::where('id', $id)->where('user_id', Auth::user()->id)->first();
    if(isset($book)) {
      $book->is_read = false;
      $book->save();
      return Response::json(array('success' => true), 200);
    } else {
      return Response::json(array('success' => false, 'error' => 'No book with that id exists'), 200);
    }
  }

  public function delete() {
    $id = Input::get('id');
    $book = Book::where('id', $id)->where('user_id', Auth::user()->id)->first();
    if(isset($book)) {
      $book->delete();
      return Response::json(array('success' => true), 200);
    } else {
      return Response::json(array('success' => false, 'error' => 'No book with that id exists'), 200);
    }
  }

  public function save() {
    $title = Input::get('title');
    $fname = Input::get('author_fname');
    $lname = Input::get('author_lname');
    $category = Input::get('category');
    $series = Input::get('series');
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
        return Response::json(array('success' => false, 'error' => 'Book already exists'), 200);
      }
      $book = new Book();
    }
    $book->title = $title;
    $book->author_fname = $fname;
    $book->author_lname = $lname;
    $book->category = $category;
    if($series) {
      $book->series = $series;
      $book->series_order = $series_order;
    }
    $book->save();
    return Response::json(array('success' => true, 'book' => $book->toArray()), 200);
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
