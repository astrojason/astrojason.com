<?php

/**
 * Class ArticleController
 */

class ArticleController extends BaseController {

  public function index() {
    return View::make('v1.articles.index');
  }

  public function readLater() {
    $title = addslashes(Input::get('title'));
    $url = Input::get('url');
    return View::make('v1.readlater')->with('title', $title)->with('url', $url);
  }

}
