<?php

use \Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class GameController extends BaseController {

  public function index() {
    $platforms = [];
    $dbPlatforms = Game::groupBy('platform')
      ->where('user_id', Auth::user()->id)
      ->get(array('platform'));
    foreach ($dbPlatforms as $platform) {
      $platforms[] = $platform->platform;
    }
    if ($platforms) {
      $lastElement = end($platforms);
    }
    $platformsString = '[';
    foreach ($platforms as $platform) {
      $platformsString .= '\'' . $platform . '\'';
      if ($platform != $lastElement) {
        $platformsString .= ',';
      }
    }
    $platformsString .= ']';


    return View::make('games.index')->with('platforms', $platformsString);
  }

  public function query(){
    $pageCount = 0;
    $limit = Input::get('limit');
    $page = Input::get('page');
    $q = Input::get('q');
    $include_completed = filter_var(Input::get('include_completed'), FILTER_VALIDATE_BOOLEAN);
    $platform = Input::get('platform');
    $query = Game::query()->where('user_id', Auth::user()->id);
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
    return Response::json(array('success' => true, 'games' => $games->toArray(), 'total' => $total, 'pages' => $pageCount));
  }

  public function save() {
    $title = Input::get('title');
    $platform = Input::get('platform');
    if(Input::get('id')) {
      $game = Game::where('user_id', Auth::user()->id)->where('id', Input::get('id'))->first();
      if(!isset($game)) {
        return Response::json(array('success' => false, 'error' => 'There is no game with id for this user.'), SymfonyResponse::HTTP_NOT_FOUND);
      }
    } else {
      $game = Game::where('user_id', Auth::user()->id)
        ->where('title', $title)
        ->where('platform', $platform)->first();
      if(isset($game)) {
        return Response::json(array('success' => false, 'error' => 'There is already a game with that title for this platform'), SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY);
      } else {
        $game = new Game();
        $game->user_id = Auth::user()->id;
      }
    }
    $game->title = $title;
    $game->platform = $platform;
    $game->completed = filter_var(Input::get('completed'), FILTER_VALIDATE_BOOLEAN);
    $game->save();
    return Response::json(array('game' => $game), SymfonyResponse::HTTP_OK);
  }

  public function delete() {
    $id = Input::get('id');
    $game = Game::where('id', $id)->where('user_id', Auth::user()->id)->first();
    if(isset($game)){
      $game->delete();
      return Response::json(array('success' => true), SymfonyResponse::HTTP_OK);
    } else {
      return Response::json(array('success' => false, 'error' => 'No game with that id exists'), SymfonyResponse::HTTP_NOT_FOUND);
    }
  }

  public function recommend() {
    $game = Game::where('completed', false)
      ->where('user_id', Auth::user()->id)
      ->orderBy(DB::raw('RAND()'))->first();
    return Response::json(array('success' => true, 'game' => $game->toArray()), SymfonyResponse::HTTP_OK);
  }

}
