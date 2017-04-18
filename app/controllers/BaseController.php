<?php

class BaseController extends Controller {

  public function __construct() {
    $userNav = [];
    $userNav[] = new NavItem('Home', '/', Route::current()->getPath() == '/');
    $userNav[] = new NavItem('Books', '/books', Route::current()->getPath() == 'books');
    $userNav[] = new NavItem('Games', '/games', Route::current()->getPath() == 'games');
    $userNav[] = new NavItem('Links', '/links', Route::current()->getPath() == 'links');
    $userNav[] = new NavItem('Songs', '/songs', Route::current()->getPath() == 'songs');
    $userNav[] = new NavItem('Credit Accounts', '/credit', Route::current()->getPath() == 'credit');
    $userNav[] = new NavItem('Charts', '/charts', Route::current()->getPath() == 'charts');
    View::share('userNav', $userNav);
  }

  /**
   * Setup the layout used by the controller.
   *
   * @return void
   */
  protected function setupLayout() {
    if (!is_null($this->layout)) {
      $this->layout = View::make($this->layout);
    }
  }

}
