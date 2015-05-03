<?php

/**
 * Class LinkController
 */
class LinkController extends AstroBaseController {

  /**
   * @return \Illuminate\Http\JsonResponse
   */
  public function query() {
    $q = Input::get('q');
    $category = Input::get('category');
    $limit = Input::get('limit');
    $page = Input::get('page');
    $include_read = filter_var(Input::get('include_read'), FILTER_VALIDATE_BOOLEAN);
    $randomize = filter_var(Input::get('randomize'), FILTER_VALIDATE_BOOLEAN);
    $query = Link::query()->where('user_id', Auth::user()->id);
    if(!$include_read) {
      $query->where('is_read', false);
    }
    if(isset($q)) {
      $query->where(function ($query) use ($q) {
        $query->where('name', 'LIKE', '%' . $q . '%')
          ->orwhere('link', 'LIKE', '%' . $q . '%');
      });
    }
    if(isset($category)) {
      $query->where('category', $category);
    }
    $total = $query->count();
    if($randomize){
      $query->orderBy(DB::raw('RAND()'));
    }
    if (isset($limit)) {
      $query->take($limit);
      if (isset($page) && $page > 1 && !$randomize) {
        $query->skip($limit * ($page - 1));
      }
    }
    $links = $query->get();
    return $this->successResponse(array('links' => $this->transformCollection($links), 'total' => $total));
  }

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

  public function readLater() {
    $title = Input::get('title');
    $link = Input::get('link');
    return View::make('readlater')->with('title', $title)->with('link', $link);
  }

  public function populateLinks() {
    $links = Link::where('category', '<>', 'At Home')
      ->orderBy(DB::raw('RAND()'))
      ->take(40)->get();
    foreach($links as $link) {
      $new_link = new Link();
      $new_link->name = $link->name;
      $new_link->link = $link->link;
      $new_link->category = 'Unread';
      $new_link->save();
    }
    return Response::json(array('success' => true), 200);
  }

  /**
   * @param Link $link
   * @return array
   */
  public function transform($link) {
    return [
      'id' => (int) $link['id'],
      'name' => $link['name'],
      'link' => $link['link'],
      'is_read' => (boolean) $link['is_read'],
      'category' => $link['category'],
      'times_loaded' => (int) $link['times_loaded'],
      'times_read' => (int) $link['times_read']
    ];
  }
}
