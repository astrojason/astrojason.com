<?php

class BaseController extends Controller {

  public function __construct() {
    $userNav = [];
    $userNav[] = new NavItem('Home', '/', Route::current()->getPath() == '/');
    $userNav[] = new NavItem('Articles', '/articles', Route::current()->getPath() == 'articles');
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
