<?php


namespace Api;

use Illuminate\Http\Response as IlluminateResponse;

use Auth, DB, Input, Response, Song;

class SongController extends AstroBaseController {

  public function query() {
    $pageCount = 0;
    $query = Song::where('user_id', Auth::user()->id);
    $q = Input::get('q');
    $randomize = filter_var(Input::get('randomize'), FILTER_VALIDATE_BOOLEAN);
    $limit = Input::get('limit');
    $page = Input::get('page');
    $sort = Input::get('sort');
    $artist = Input::get('artist');
    $include_learned = filter_var(Input::get('include_learned'), FILTER_VALIDATE_BOOLEAN);
    $descending = filter_var(Input::get('descending'), FILTER_VALIDATE_BOOLEAN);
    if(isset($q)){
      $query->where(function($query) use ($q) {
        $query->where('title', 'LIKE', '%' . $q . '%')
          ->orwhere('artist', 'LIKE', '%' . $q . '%');
      });
    }
    if(isset($artist)) {
      $query->where('artist', $artist);
    }
    if(!$include_learned) {
      $query->where('learned', false);
    }
    if(isset($sort)) {
      $query->orderBy($sort, $descending ? 'DESC' : 'ASC');
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
    return $this->successResponse(array('songs' => $this->transformCollection($songs), 'total' => $total, 'pages' => $pageCount));
  }

  public function save($songId = null) {
    $title = Input::get('title');
    $artist = Input::get('artist');
    $location = Input::get('location');
    $learned = filter_var(Input::get('learned'), FILTER_VALIDATE_BOOLEAN);
    if($songId){
      $song = Song::where('id', $songId)
        ->where('user_id', Auth::user()->id)
        ->first();
      if(!isset($song)){
        return $this->notFoundResponse('No song with that id exists for the logged in user.');
      }
    } else {
      $song = Song::where('user_id', Auth::user()->id)
        ->where('title', $title)
        ->where('artist', $artist)
        ->first();
      if(isset($song)){
        return Response::json(array('error' => 'A song with that name by that artist already exists.'), IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY);
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
    return $this->successResponse(array('song' => $this->transform($song)));
  }

  public function populate() {
    $songs = Song::orderBy(DB::raw('RAND()'))->take(20)->get();
    foreach ($songs as $song) {
      $newSong = new Song();
      $newSong->user_id = Auth::user()->id;
      $newSong->title = $song->title;
      $newSong->artist = $song->artist;
      $newSong->save();
    }
    $songs = Song::where('user_id', Auth::user()->id)->get();
    return $this->successResponse(array('songs' => $this->transformCollection($songs)));
  }

  public function delete($songId) {
    if($songId) {
      $song = Song::where('id', $songId)
        ->where('user_id', Auth::user()->id)
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

  public function transform($song) {
    return [
      'id' => (int)$song['id'],
      'times_recommended' => (int)$song['times_recommended'],
      'learned' => filter_var($song['learned'], FILTER_VALIDATE_BOOLEAN),
      'title' => $song['title'],
      'artist' => $song['artist'],
      'location' => $song['location']
    ];
  }

}