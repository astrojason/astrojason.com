<?php

class LinksController extends BaseController {

  public function add() {
    if(Auth::check()) {
      if(Input::get('id')) {
        $link = Link::where('id', Input::get('id'))->where('user_id', Auth::user()->id)->get()[0];
      } else {
        // Make sure this link doesn't already exist for this user
        $link = Link::where('user_id', Auth::user()->id)->where('link', Input::get('link'))->get();
        if(count($link) == 0) {
          $link = new Link();
          $link->user_id = Auth::user()->id;
        } else {
          unset($link);
        }
      }
      if(isset($link)) {
        try {
          $link->name = Input::get('name');
          $link->link = Input::get('link');
          $link->category = Input::get('category');
          $link->is_read = Input::get('read') || false;
          if(Input::get('instapaper_id')) {
            $link->instapaper_id = Input::get('instapaper_id');
          }
          $link->save();
          return Response::json(array('success' => true, 'link' => $link->toArray()), 200);
        } catch(Exception $exception) {
          return Response::json(array('success' => false, 'error' => $exception->getMessage()), 200);
        }
      } else {
        return Response::json(array('success' => false, 'error' => 'Link already exists'), 200);
      }
    }
  }

} 