<?php

class MovieController extends BaseController {

  public function index() {
    return View::make('movies.index');
  }

}
