<?php

class BookController extends BaseController {
  public function nextToRead($category) {
    $book = Book::where('is_read', false)
    ->where('category', $category)
    ->where('user_id', Auth::user()->id)
    ->orderBy(DB::raw('RAND()'))->first();
    return Response::json(array('success' => true, 'book' => $book->toArray()), 200);
  }
}
