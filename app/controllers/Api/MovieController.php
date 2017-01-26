<?php


namespace Api;

use Auth, DB, Input, Movie;
use Illuminate\Http\Response as IlluminateResponse;

class MovieController extends AstroBaseController {

  public function query() {
    $pageCount = 0;
    $q = Input::get('q');
    $limit = Input::get('limit');
    $page = Input::get('page');
    $query = Movie::where('user_id', Auth::user()->id);
    if(isset($q)) {
      $query->where(function($query) use ($q) {
        $query->where('title', 'LIKE', '%' . $q . '%');
      });
    }
    $total = $query->count();
    if (isset($limit)) {
      $pageCount = ceil($total / $limit);
      $query->take($limit);
      if (isset($page) && $page > 1) {
        $query->skip($limit * ($page - 1));
      }
    }
    $movies = $query->orderBy('rating_order')->get();
    return $this->successResponse(array('movies' => $this->transformCollection($movies), 'total' => $total, 'pages' => $pageCount));
  }

  public function save($movieId = null) {
    $title = Input::get('title');
    $is_watched = filter_var(Input::get('is_watched'), FILTER_VALIDATE_BOOLEAN);
    $date_watched = strtotime(Input::get('date_watched'));
    if($movieId) {
      $movie = Movie::where('user_id', Auth::user()->id)->where('id', $movieId)->first();
      if(isset($movie)) {
        if (Input::get('rating_order')) {
          $moving_movie = Movie::where('user_id', Auth::user()->id)->where('rating_order', Input::get('rating_order'))->first();
          $moving_movie->rating_order = $movie->rating_order;
          $moving_movie->save();
          $movie->rating_order = Input::get('rating_order');
        }
      } else {
        return $this->notFoundResponse('There is no movie with id for this user.');
      }
    } else {
      $movie = Movie::where('user_id', Auth::user()->id)->where('title', $title)->first();
      if(isset($movie)) {
        return $this->errorResponse('There is already a movie with that title', IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY);
      }
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
    return $this->successResponse(array('movie' => $this->transform($movie)));
  }

  public function delete($movieId) {
    $movie = Movie::where('id', $movieId)->where('user_id', Auth::user()->id)->first();
    if(isset($movie)){
      $movies_after = Movie::where('user_id', Auth::user()->id)
        ->where('rating_order', '>', $movie->rating_order)
        ->get();
      foreach($movies_after as $update_movie) {
        $update_movie->rating_order -= 1;
        $update_movie->save();
      }
      $movie->delete();
      $this->successResponse();
    } else {
      return $this->notFoundResponse('No movie with that id exists');
    }
  }

  public function populate() {
    $movies = Movie::orderBy(DB::raw('RAND()'))->take(20)->get();
    foreach ($movies as $movie) {
      $newMovie = new Movie();
      $newMovie->user_id = Auth::user()->id;
      $newMovie->title = $movie->title;
      $newMovie->save();
    }
    $movies = Movie::where('user_id', Auth::user()->id)->get();
    return $this->successResponse(array('movies' => $this->transformCollection($movies)));
  }

  public function transform($movie){
    return [
      'is_watched' => filter_var($movie['is_watched'], FILTER_VALIDATE_BOOLEAN),
      'id' => (int)$movie['id'],
      'times_watched' => (int)$movie['times_watched'],
      'rating_order' => (int)$movie['rating_order'],
      'title' => $movie['title']
    ];
  }

}