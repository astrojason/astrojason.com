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

}
