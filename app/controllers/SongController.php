<?php

use \Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class SongController extends BaseController {

  public function index() {
    return View::make('songs.index');
  }

  public function query() {
    $pageCount = 0;
    $query = Song::where('user_id', Auth::user()->id);
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
    return Response::json(array('songs' => $songs->toArray(), 'total' => $total, 'pages' => $pageCount), SymfonyResponse::HTTP_OK);
  }

  public function save() {
    $title = Input::get('title');
    $artist = Input::get('artist');
    $location = Input::get('location');
    $learned = filter_var(Input::get('learned'), FILTER_VALIDATE_BOOLEAN);
    if(Input::get('id')){
      $song = Song::where('id', Input::get('id'))
        ->where('user_id', Auth::user()->id)
        ->first();
      if(!isset($song)){
        return Response::json(array('error' => 'No song with that id exists for the logged in user.'), SymfonyResponse::HTTP_NOT_FOUND);
      }
    } else {
      $song = Song::where('user_id', Auth::user()->id)
        ->where('title', $title)
        ->where('artist', $artist)
        ->first();
      if(isset($song)){
        return Response::json(array('error' => 'A song with that name by that artist already exists.'), SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY);
      } else {
        $song = new Song();
        $song->user_id = Auth::user()->id;
      }
    }
    $song->title = $title;
    $song->artist = $artist;
    $song->location = $location;
    $song->learned = $learned;
    $song->save();
    return Response::json(array('song' => $song->toArray()), SymfonyResponse::HTTP_OK);
  }

  public function delete() {
    if(Input::get('id')) {
      $song = Song::where('id', Input::get('id'))
        ->where('user_id', Auth::user()->id)
        ->first();
      if (!isset($song)) {
        return Response::json(array('error' => 'No song with that id exists for the logged in user.'), SymfonyResponse::HTTP_NOT_FOUND);
      } else {
        $song->delete();
        return Response::json(array('success' => true), SymfonyResponse::HTTP_OK);
      }
    } else {
      return Response::json(array('error' => 'You must pass an id.'), SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY);
    }
  }

}
