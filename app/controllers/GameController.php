<?php

class GameController extends BaseController {

  public function save() {
    $title = Input::get('title');
    $platform = Input::get('platform');
    if(Input::get('id')) {
      $game = Game::where('user_id', Auth::user()->id)->where('id', Input::get('id'))->first();
      if(!isset($game)) {
        return Response::json(array('success' => false, 'error' => 'There is no game with id for this user.'), 200);
      }
    } else {
      $game = Game::where('user_id', Auth::user()->id)
        ->where('title', $title)
        ->where('platform', $platform)->first();
      if(isset($game)) {
        return Response::json(array('success' => false, 'error' => 'There is already a game with that title for this platform'), 200);
      } else {
        $game = new Game();
      }
    }
    $game->title = $title;
    $game->platform = $platform;
    $game->completed = filter_var(Input::get('completed'), FILTER_VALIDATE_BOOLEAN);
    $game->save();
    return Response::json(array('success' => true), 200);
  }

  public function recommend() {
    $game = Game::where('completed', false)
      ->where('user_id', Auth::user()->id)
      ->orderBy(DB::raw('RAND()'))->first();
    return Response::json(array('success' => true, 'game' => $game->toArray()), 200);
  }

}
