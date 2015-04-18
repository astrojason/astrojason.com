<?php

class SongController extends BaseController {

  public function index() {
    return View::make('songs.index');
  }

  public function all() {
    $songs = Song::where('user_id', Auth::user()->id)->get();
    return Response::json(array('success' => true, 'songs' => $songs->toArray()), 200);
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
        return Response::json(array('success' => false, 'error' => 'No song with that id exists for the logged in user'), 200);
      }
    } else {
      $song = Song::where('user_id', Auth::user()->id)
        ->where('title', $title)
        ->where('artist', $artist)
        ->first();
      if(isset($song)){
        return Response::json(array('success' => false, 'error' => 'A song with that name by that artist already exists'), 200);
      } else {
        $song = new Song();
      }
    }
    $song->title = $title;
    $song->artist = $artist;
    $song->location = $location;
    $song->learned = $learned;
    $song->save();
  }

}
