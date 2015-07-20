<?php


namespace Api;

use Illuminate\Http\Response as IlluminateResponse;

class LinkController extends AstroBaseController {

  /**
   * @return \Illuminate\Http\JsonResponse
   */
  public function query() {
    $pageCount = 0;
    $q = \Input::get('q');
    $category = \Input::get('category');
    $limit = \Input::get('limit');
    $page = \Input::get('page');
    $include_read = filter_var(\Input::get('include_read'), FILTER_VALIDATE_BOOLEAN);
    $randomize = filter_var(\Input::get('randomize'), FILTER_VALIDATE_BOOLEAN);
    $updateLoadCount = filter_var(\Input::get('update_load_count'), FILTER_VALIDATE_BOOLEAN);
    $query = \Link::query()->where('user_id', \Auth::user()->id);
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
      $query->orderBy(\DB::raw('RAND()'));
    }
    if (isset($limit)) {
      $pageCount = ceil($total / $limit);
      $query->take($limit);
      if (isset($page) && $page > 1 && !$randomize) {
        $query->skip($limit * ($page - 1));
      }
    }
    $links = $query->get();
    if($updateLoadCount){
      /** @var \Link $link */
      foreach($links as $link) {
        $link->times_loaded += 1;
        $link->save();
      }
    }
    return $this->successResponse(array('links' => $this->transformCollection($links), 'total' => $total, 'pages' => $pageCount));
  }

  /**
   * @return \Illuminate\Http\JsonResponse
   */
  public function save() {
    if(\Input::get('id')) {
      $link = \Link::where('id', \Input::get('id'))->where('user_id', \Auth::user()->id)->first();
    } else {
      $link = \Link::where('link', \Input::get('link'))->where('user_id', \Auth::user()->id)->first();
      if(isset($link)) {
        return $this->errorResponse(array('success' => false, 'error' => 'Link already exists'), IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY);
      }
      $link = new \Link;
      $link->user_id = \Auth::user()->id;
    }
    try {
      $link->name = \Input::get('name');
      $link->link = \Input::get('link');
      $link->category = \Input::get('category');
      $link->is_read = filter_var(\Input::get('is_read'), FILTER_VALIDATE_BOOLEAN);
      $link->times_loaded = \Input::get('times_loaded', 0);
      $link->times_loaded = \Input::get('times_read', 0);
      if(\Input::get('instapaper_id')) {
        $link->instapaper_id = \Input::get('instapaper_id');
      }
      $link->save();
      return $this->successResponse(array('link' => $this->transform($link)));
    } catch(\Exception $exception) {
      return $this->errorResponse($exception->getMessage());
    }
  }

  public function delete() {
    $id = \Input::get('id');
    $link = \Link::where('id', $id)->where('user_id', \Auth::user()->id)->first();
    if(isset($link)){
      $link->delete();
      return $this->successResponse();
    } else {
      return $this->notFoundResponse(array('error' => 'No link with that id exists'));
    }
  }

  public function populateLinks() {
    $links = \Link::where('category', '<>', 'At Home')
      ->orderBy(DB::raw('RAND()'))
      ->take(40)->get();
    foreach($links as $link) {
      $new_link = new \Link();
      $new_link->name = $link->name;
      $new_link->link = $link->link;
      $new_link->category = 'Unread';
      $new_link->save();
    }
    return $this->successResponse();
  }

  /**
   * @param \Link $link
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