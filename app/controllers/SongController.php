<?php

class SongController extends BaseController {

  public function index() {
    return View::make('songs.index');
  }

}
