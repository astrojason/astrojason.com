<?php

class MovieController extends BaseController {

  public function index() {
    return View::make('v1.movies.index');
  }

}
