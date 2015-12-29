<?php

/**
 * User: jasonsylvester
 * Date: 12/11/15
 * Time: 10:37 AM
 */
class AstroBookRepo extends AstroData {

  public function getData() {
    $pageCount = 0;
    $page = \Input::get('page');
    $q = \Input::get('q');
    $limit = \Input::get('limit');
    $category = \Input::get('category');
    $sort = \Input::get('sort');
    $query = \Book::query()->where('user_id', $this->userId);
    $randomize = filter_var(\Input::get('randomize'), FILTER_VALIDATE_BOOLEAN);
    $include_read = filter_var(\Input::get('include_read'), FILTER_VALIDATE_BOOLEAN);

    if(isset($q)) {
      $query->where(function($query) use ($q) {
        $query->where('title', 'LIKE', '%' . $q . '%')
          ->orwhere('series', 'LIKE', '%' . $q . '%')
          ->orwhere('author_lname', 'LIKE', '%' . $q . '%');
      });
    }
    if(!$include_read) {
      $query->where('is_read', false);
    }
    if($randomize){
      $query->orderBy(\DB::raw('RAND()'));
    }
    if(isset($limit)){
      $query->take($limit);
    }
    if(isset($category)) {
      $query->where('category', $category);
    }
    if(isset($sort)) {
      if($sort == 'series') {
        $query->where('series', '!=', '');
        $query->orderBy('series');
        $query->orderBy('series_order');
      }
      else {
        $query->orderBy($sort);
      }
    }
    $total = $query->count();
    if (isset($limit)) {
      $pageCount = ceil($total / $limit);
      $query->take($limit);
      if (isset($page) && $page > 1 && !$randomize) {
        $query->skip($limit * ($page - 1));
      }
    }
    $books = $query->get();
    $this->results = [
      'books' => $books,
      'total' => $total,
      'pageCount' => $pageCount
    ];
  }
}