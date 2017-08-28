<?php

class HomeController extends BaseController {

  public function showIndex() {
    $fileName = 'assets/js/v1/bookmarkletLoader.min.js';
    $bookmarklet = null;
    $categoriesString = null;
    if(Auth::user()) {
      if(file_exists($fileName)) {
        $bookmarklet = str_replace('{{BASEURL}}', Config::get('App.url'), str_replace('"', "'", file_get_contents($fileName)));
      } else {
        $bookmarklet = null;
      }
    }
    return View::make('v1.index')->with('bookmarklet', $bookmarklet);
  }

  public function register() {
    return View::make('v1.register');
  }

  public function react() {
    return View::make('react.index');
  }
}
