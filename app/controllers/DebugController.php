<?php


class DebugController extends AstroBaseController {

  public function queries() {
    $log = DB::getQueryLog();
    return View::make('debug.queries')->with('queries', $log);
  }

  public function transform($data) {
      return $data;
  }
}