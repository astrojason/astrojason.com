<?php

/**
 * User: jasonsylvester
 * Date: 10/4/16
 * Time: 12:41 PM
 */
class ChartController extends BaseController {

  public function index() {
    return View::make('charts.index');
  }

}