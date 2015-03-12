<?php

class MovieController extends BaseController {

  public function index() {
    return View::make('movies.index');
  }

  public function widget() {
    $rand_movie = Movie::where('user_id', Auth::user()->id)
      ->orderBy(DB::raw('RAND()'))->first();
    if(isset($rand_movie)) {
      $movies = Movie::where('user_id', Auth::user()->id)
        ->where('rating_order', '>=', $rand_movie->rating_order)
        ->orderBy('rating_order')
        ->take(5)
        ->get();
      return Response::json(array('success' => true, 'movies' => $movies->toArray()), 200);
    } else {
      return Response::json(array('success' => true, 'movies' => array()), 200);
    }
  }

  public function all() {
    $movies = Movie::where('user_id', Auth::user()->id)
      ->orderBy('rating_order')
      ->get();
    return Response::json(array('success' => true, 'movies' => $movies->toArray()), 200);
  }

  public function save() {
    $title = Input::get('title');
    $is_watched = filter_var(Input::get('is_watched'), FILTER_VALIDATE_BOOLEAN);
    $date_watched = strtotime(Input::get('date_watched'));
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
      $movie->rating_order = DB::table('movies')->max('rating_order') + 1;
      if($is_watched) {
        $movie->times_watched = 1;
      }
    }
    $movie->title = $title;
    $movie->is_watched = $is_watched;
    if($date_watched) {
      $movie->date_watched = date('Y-m-d',$date_watched);
    }
    $movie->save();
  }

  public function delete($id) {
    $movie = Movie::where('id', $id)->where('user_id', Auth::user()->id)->first();
    if(isset($movie)){
      $movies_after = Movie::where('user_id', Auth::user()->id)
        ->where('rating_order', '>', $movie->rating_order)
        ->get();
      foreach($movies_after as $update_movie) {
        $update_movie->rating_order -= 1;
        $update_movie->save();
      }
      $movie->delete();
      return Response::json(array('success' => true), 200);
    } else {
      return Response::json(array('success' => false, 'error' => 'No movie with that id exists'), 200);
    }
  }
}
