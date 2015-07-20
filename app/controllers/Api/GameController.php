<?php


namespace Api;

use Illuminate\Http\Response as IlluminateResponse;

class GameController extends AstroBaseController {

  public function query(){
    $pageCount = 0;
    $limit = \Input::get('limit');
    $page = \Input::get('page');
    $q = \Input::get('q');
    $include_completed = filter_var(\Input::get('include_completed'), FILTER_VALIDATE_BOOLEAN);
    $platform = \Input::get('platform');
    $query = \Game::query()->where('user_id', \Auth::user()->id);
    if(isset($q)){
      $query->where('title', 'LIKE', '%' . $q . '%');
    }
    if(!$include_completed){
      $query->where('completed', false);
    }
    if(isset($platform)){
      $query->where('platform', $platform);
    }
    $total = $query->count();
    if (isset($limit)) {
      $pageCount = ceil($total / $limit);
      $query->take($limit);
      if (isset($page) && $page > 1) {
        $query->skip($limit * ($page - 1));
      }
    }
    $games = $query->get();
    return $this->successResponse(array('games' => $games->toArray(), 'total' => $total, 'pages' => $pageCount));
  }

  public function save() {
    $title = \Input::get('title');
    $platform = \Input::get('platform');
    if(\Input::get('id')) {
      $game = \Game::where('user_id', \Auth::user()->id)->where('id', \Input::get('id'))->first();
      if(!isset($game)) {
        return $this->notFoundResponse('There is no game with id for this user.');
      }
    } else {
      $game = \Game::where('user_id', \Auth::user()->id)
        ->where('title', $title)
        ->where('platform', $platform)->first();
      if(isset($game)) {
        return $this->errorResponse('There is already a game with that title for this platform', IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY);
      } else {
        $game = new \Game();
        $game->user_id = \Auth::user()->id;
      }
    }
    $game->title = $title;
    $game->platform = $platform;
    $game->completed = filter_var(\Input::get('completed'), FILTER_VALIDATE_BOOLEAN);
    $game->save();
    return $this->successResponse(array('game' => $game));
  }

  public function delete() {
    $id = \Input::get('id');
    $game = \Game::where('id', $id)->where('user_id', \Auth::user()->id)->first();
    if(isset($game)){
      $game->delete();
      return $this->successResponse();
    } else {
      return $this->notFoundResponse('There is no game with id for this user.');
    }
  }

  public function recommend() {
    $game = \Game::where('completed', false)
      ->where('user_id', \Auth::user()->id)
      ->orderBy(\DB::raw('RAND()'))->first();
    $game->times_recommended += 1;
    $game->save();
    return $this->successResponse(array('game' => $game->toArray()));
  }

  public function transform($data) {
    return $data;
  }
}