<?php

class MovieController extends BaseController {

  public function widget() {
    $rand_movie = Movie::where('user_id', Auth::user()->id)
      ->orderBy(DB::raw('RAND()'))->first();
    $movies = Movie::where('user_id', Auth::user()->id)
      ->where('rating_order', '>=', $rand_movie->rating_order)
      ->orderBy('rating_order')
      ->take(5)
      ->get();
    return Response::json(array('success' => true, 'movies' => $movies->toArray()), 200);
  }

  public function save() {
    $title = Input::get('title');
    if(Input::get('id')) {
      $movie = Movie::where('user_id', Auth::user()->id)->where('id', Input::get('id'))->first();
      if(Input::get('rating_order')) {
        $moving_movie = Movie::where('user_id', Auth::user()->id)->where('rating_order', Input::get('rating_order'))->first();
        $moving_movie->rating_order = $movie->rating_order;
        $moving_movie->save();
        $movie->rating_order = Input::get('rating_order');
      }
    } else {
      $movie = new Movie();
      $movie->user_id = Auth::user()->id;
    }
    $movie->title = $title;
    $movie->save();
  }
}
