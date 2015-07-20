<?php

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

}
