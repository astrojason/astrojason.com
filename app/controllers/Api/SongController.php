<?php


namespace Api;

use Illuminate\Http\Response as IlluminateResponse;

use DB;
use Input;
use Response;
use Song;

class SongController extends AstroBaseController {

  public function query() {
    $pageCount = 0;
    $query = Song::where('user_id', \Auth::user()->id);
    $q = Input::get('q');
    $randomize = filter_var(Input::get('randomize'), FILTER_VALIDATE_BOOLEAN);
    $limit = Input::get('limit');
    $page = Input::get('page');
    $include_learned = filter_var(Input::get('include_learned'), FILTER_VALIDATE_BOOLEAN);
    if(isset($q)){
      $query->where(function($query) use ($q) {
        $query->where('title', 'LIKE', '%' . $q . '%')
          ->orwhere('artist', 'LIKE', '%' . $q . '%');
      });
    }
    if(!$include_learned) {
      $query->where('learned', false);
    }
    $total = $query->count();
    if($randomize){
      $query->orderBy(DB::raw('RAND()'));
    }
    if (isset($limit)) {
      $pageCount = ceil($total / $limit);
      $query->take($limit);
      if (isset($page) && $page > 1 && !$randomize) {
        $query->skip($limit * ($page - 1));
      }
    }
    $songs = $query->get();
    if($randomize){
      /** @var Song $song */
      foreach($songs as $song) {
        $song->times_recommended += 1;
        $song->save();
      }
    }
    return $this->successResponse(array('songs' => $songs->toArray(), 'total' => $total, 'pages' => $pageCount));
  }

  public function save() {
    $title = Input::get('title');
    $artist = Input::get('artist');
    $location = Input::get('location');
    $learned = filter_var(Input::get('learned'), FILTER_VALIDATE_BOOLEAN);
    if(Input::get('id')){
      $song = Song::where('id', Input::get('id'))
        ->where('user_id', \Auth::user()->id)
        ->first();
      if(!isset($song)){
        return $this->notFoundResponse('No song with that id exists for the logged in user.');
      }
    } else {
      $song = Song::where('user_id', \Auth::user()->id)
        ->where('title', $title)
        ->where('artist', $artist)
        ->first();
      if(isset($song)){
        return Response::json(array('error' => 'A song with that name by that artist already exists.'), IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY);
      } else {
        $song = new Song();
        $song->user_id = \Auth::user()->id;
      }
    }
    $song->title = $title;
    $song->artist = $artist;
    $song->location = $location;
    $song->learned = $learned;
    $song->save();
    return $this->successResponse(array('song' => $song->toArray()));
  }

  public function delete() {
    if(Input::get('id')) {
      $song = Song::where('id', Input::get('id'))
        ->where('user_id', \Auth::user()->id)
        ->first();
      if (!isset($song)) {
        return $this->notFoundResponse('No song with that id exists for the logged in user.');
      } else {
        $song->delete();
        return $this->successResponse();
      }
    } else {
      return Response::json(array('error' => 'You must pass an id.'), IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY);
    }
  }


  public function transform($data) {

  }

}