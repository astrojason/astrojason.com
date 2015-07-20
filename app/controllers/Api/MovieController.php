<?php


namespace Api;


class MovieController extends AstroBaseController {

  public function query() {
    $pageCount = 0;
    $q = \Input::get('q');
    $limit = \Input::get('limit');
    $page = \Input::get('page');
    $query = \Movie::where('user_id', \Auth::user()->id);
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
    return $this->successResponse(array('movies' => $movies->toArray(), 'total' => $total, 'pages' => $pageCount));
  }

  public function save() {
    $title = \Input::get('title');
    $is_watched = filter_var(\Input::get('is_watched'), FILTER_VALIDATE_BOOLEAN);
    $date_watched = strtotime(\Input::get('date_watched'));
    if(\Input::get('id')) {
      $movie = \Movie::where('user_id', \Auth::user()->id)->where('id', \Input::get('id'))->first();
      if(\Input::get('rating_order')) {
        $moving_movie = \Movie::where('user_id', \Auth::user()->id)->where('rating_order', \Input::get('rating_order'))->first();
        $moving_movie->rating_order = $movie->rating_order;
        $moving_movie->save();
        $movie->rating_order = \Input::get('rating_order');
      }
    } else {
      $movie = new \Movie();
      $movie->user_id = \Auth::user()->id;
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
    return $this->successResponse(array('movie' => $movie->toArray()));
  }

  public function delete() {
    $id = \Input::get('id');
    $movie = \Movie::where('id', $id)->where('user_id', \Auth::user()->id)->first();
    if(isset($movie)){
      $movies_after = \Movie::where('user_id', \Auth::user()->id)
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


  public function transform($data){

  }

}