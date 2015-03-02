<?php

class LinkController extends BaseController {

  public function save() {
    if(Input::get('id')) {
      $link = Link::where('id', Input::get('id'))->where('user_id', Auth::user()->id)->first();
    } else {
      $link = Link::where('link', Input::get('link'))->where('user_id', Auth::user()->id)->first();
      if(isset($link)) {
        return Response::json(array('success' => false, 'error' => 'Link already exists'), 200);
      }
      $link = new Link;
      $link->user_id = Input::get('user_id');
    }
    try {
      $link->name = Input::get('name');
      $link->link = Input::get('link');
      $link->category = Input::get('category');
      $link->is_read = filter_var(Input::get('is_read'), FILTER_VALIDATE_BOOLEAN);
      if(Input::get('instapaper_id')) {
        $link->instapaper_id = Input::get('instapaper_id');
      }
      $link->save();
      return Response::json(array('success' => true, 'link' => $link->toArray()), 200);
    } catch(Exception $exception) {
      return Response::json(array('success' => false, 'error' => $exception->getMessage()), 200);
    }
  }

  public function read($id) {
    $link = Link::where('id', $id)->where('user_id', Auth::user()->id)->first();
    if(isset($link)){
      $link->is_read = true;
      $link->save();
      return Response::json(array('success' => true), 200);
    } else {
      return Response::json(array('success' => false, 'error' => 'No link with that id exists'), 200);
    }
  }

  public function unread($id) {
    $link = Link::where('id', $id)->where('user_id', Auth::user()->id)->first();
    if(isset($link)){
      $link->is_read = false;
      $link->save();
      return Response::json(array('success' => true), 200);
    } else {
      return Response::json(array('success' => false, 'error' => 'No link with that id exists'), 200);
    }
  }

  public function delete($id) {
    $link = Link::where('id', $id)->where('user_id', Auth::user()->id)->first();
    if(isset($link)){
      $link->delete();
      return Response::json(array('success' => true), 200);
    } else {
      return Response::json(array('success' => false, 'error' => 'No link with that id exists'), 200);
    }
  }

  public function open($id) {
    $link = Link::where('id', $id)->where('user_id', Auth::user()->id)->first();
    if(isset($link)){
      $link->times_read = $link->times_read + 1;
      $link->save();
      return Response::json(array('success' => true), 200);
    } else {
      return Response::json(array('success' => false, 'error' => 'No link with that id exists'), 200);
    }
  }

  public function getRandomLinks($category, $quantity = 10) {
    $links = self::getLinksToRead($category, $quantity);
    return Response::json(array('success' => true, 'links' => $links->toArray()), 200);
  }

  public function search() {
    $q = Input::get('q');
    $include_read = Input::get('include_read', false);
    $query =Link::query();
    if(!$include_read) {
      $query->where('is_read', false);
    }
    $query->where('user_id', Auth::user()->id);
    $query->where(function($query) use ($q) {
      $query->where('name', 'LIKE', '%' . $q . '%')
        ->orwhere('link', 'LIKE', '%' . $q . '%');
    });
    $links = $query->get();
    return Response::json(array('success' => true, 'links' => $links->toArray()), 200);
  }

  public function readLater() {
    $title = Input::get('title');
    $link = Input::get('link');
    return View::make('readlater')->with('title', $title)->with('link', $link);
  }

  public static function getLinksToRead($category, $numResults = 10) {
    $links = Link::where('is_read', false)
      ->where('category', $category)
      ->where('user_id', Auth::user()->id)
      ->orderBy(DB::raw('RAND()'))
      ->take($numResults)->get();
    foreach($links as $link) {
      $link->times_loaded = $link->times_loaded + 1;
      $link->save();
    }
    return $links;
  }
}
