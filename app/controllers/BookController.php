<?php

class BookController extends BaseController {

  public function recommendation($category) {
    $book = Book::where('is_read', false)
    ->where('category', $category)
    ->where('user_id', Auth::user()->id)
    ->orderBy(DB::raw('RAND()'))->first();
    if($book->series_order > 0) {
      $book = Book::where('is_read', false)
      ->where('user_id', Auth::user()->id)
      ->where('series', $book->series)
      ->orderBy('series_order')
      ->first();
    }
    $book->times_loaded = $book->times_loaded + 1;
    $book->save();
    return Response::json(array('success' => true, 'book' => $book->toArray()), 200);
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

  public function delete($id) {
    $book = Book::where('id', $id)->where('user_id', Auth::user()->id)->first();
    if(isset($book)) {
      $book->delete();
      return Response::json(array('success' => true), 200);
    } else {
      return Response::json(array('success' => false, 'error' => 'No book with that id exists'), 200);
    }
  }

  public function save() {
    if(Input::get('id')) {
      $book = Book::where('id', Input::get('id'))->where('user_id', Auth::user()->id)->first();
    } else {
      $book = Book::where('title', Input::get('title'))
        ->where('author_lname', Input::get('author_lname'))
        ->where('user_id', Auth::user()->id)
        ->first();
      if(isset($book)) {
        return Response::json(array('success' => false, 'error' => 'Book already exists'), 200);
      }
      $book = new Book;
      $book->user_id = Input::get('user_id');
    }
  }

}
