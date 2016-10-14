<?php

namespace Api;

use Illuminate\Http\Response as IlluminateResponse;

use Auth, Carbon\Carbon, DB, Exception, Input, Link;

/**
 * Class LinkController
 * @package Api
 */
class LinkController extends AstroBaseController {

  /**
   * @return \Illuminate\Http\JsonResponse
   */
  public function query() {
    $pageCount = 0;
    $q = Input::get('q');
    $category = Input::get('category');
    $limit = Input::get('limit');
    $page = Input::get('page');
    $sort = Input::get('sort');
    $include_read = filter_var(Input::get('include_read'), FILTER_VALIDATE_BOOLEAN);
    $randomize = filter_var(Input::get('randomize'), FILTER_VALIDATE_BOOLEAN);
    $updateLoadCount = filter_var(Input::get('update_load_count'), FILTER_VALIDATE_BOOLEAN);
    $descending = filter_var(Input::get('descending'), FILTER_VALIDATE_BOOLEAN);
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
    if(isset($sort)) {
      $query->orderBy($sort, $descending ? 'DESC' : 'ASC');
    }
    $total = $query->count();
    if($randomize){
      $query->orderBy(DB::raw('RAND()'));
    }
    if (isset($limit) && $limit > 0) {
      $pageCount = ceil($total / $limit);
      $query->take($limit);
      if (isset($page) && $page > 1 && !$randomize) {
        $query->skip($limit * ($page - 1));
      }
    }
    $links = $query->get();
    if($updateLoadCount){
      /** @var Link $link */
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
  public function save($linkId = null) {
    if($linkId) {
      $link = Link::where('id', $linkId)->where('user_id', Auth::user()->id)->first();
      if(!isset($link)){
        return $this->notFoundResponse('No link found with that id');
      }
    } else {
      if($this->checkLinkExists(Input::get('link'))) {
        return $this->errorResponse(array('success' => false, 'error' => 'Link already exists'), IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY);
      }
      $link = new Link;
      $link->user_id = Auth::user()->id;
    }
    try {
      $isRead = filter_var(Input::get('is_read'), FILTER_VALIDATE_BOOLEAN);
      $link->name = Input::get('name');
      $link->link = Input::get('link');
      $link->category = Input::get('category');
      if($link->is_read != $isRead) {
        $link->is_read = $isRead;
        if($isRead) {
          $link->read_at = Carbon::now();
        } else {
          $link->read_at = null;
        }
      }
      $link->times_loaded = Input::get('times_loaded', 0);
      $link->times_loaded = Input::get('times_read', 0);
      if(Input::get('instapaper_id')) {
        $link->instapaper_id = Input::get('instapaper_id');
      }
      $link->save();
      return $this->successResponse(array('link' => $this->transform($link)));
    } catch(Exception $exception) {
      return $this->errorResponse($exception->getMessage());
    }
  }

  public function delete($linkId) {
    $link = Link::where('id', $linkId)->where('user_id', Auth::user()->id)->first();
    if(isset($link)){
      $link->delete();
      return $this->successResponse();
    } else {
      return $this->notFoundResponse(array('error' => 'No link with that id exists'));
    }
  }

  public function populateLinks() {
    $links = Link::where('category', '<>', 'At Home')
      ->orderBy(DB::raw('RAND()'))
      ->take(40)->get();
    foreach($links as $link) {
      $new_link = new Link();
      $new_link->user_id = Auth::user()->id;
      $new_link->name = $link->name;
      $new_link->link = $link->link;
      $new_link->category = 'Unread';
      $new_link->save();
    }
    return $this->successResponse();
  }

  public function importLinks() {
    $importLinks = Input::get('importlist');
    $unsavedLinks = [];
    $links = [];
    foreach($importLinks as $importLink){
      if(!$this->checkLinkExists($importLink['url'])) {
        $link = new Link();
        $link->name = $importLink['name'];
        $link->link = $importLink['url'];
        $link->category = 'Unread';
        $link->user_id = Auth::user()->id;
        $link->save();
        $links[] = $link;
      } else {
        $unsavedLinks[] = [
          'name' => $importLink['name'],
          'link' => $importLink['url'],
          'reason' => 'Link already exists'
        ];
      }
    }

    return $this->successResponse(['imported' => $links, 'skipped' => $unsavedLinks]);
  }

  public function readToday() {
    return $this->successResponse(['total_read' => $this->getReadTodayCount()]);
  }

  public function getReadTodayCount() {
    $query = Link::where('is_read', true)
      ->where('user_id', Auth::user()->id)
      ->where('read_at', 'LIKE', '%' . date('Y-m-d') . '%');
    return $query->count();
  }

  public function report() {
    $today = Carbon::today();
    $oneMonthAgo = Carbon::parse(date('Y-m-d', strtotime("-1 months")));

    $report_query = Link::where('user_id', Auth::user()->id)
      ->where(function($query) use ($oneMonthAgo){
        $query->where('read_at', '>=', $oneMonthAgo)
          ->orWhere('created_at', '>=', $oneMonthAgo);
      });
    $report_links = $report_query->get();
    $report = new Report();

    for($date = $oneMonthAgo; $date->lte($today); $date->addDay()) {
      $report->{$date->format('Y-m-d')} = [
          'read' => 0,
          'created' => 0
        ];
    }
    foreach ($report_links as $report_link) {
      if($report_link->is_read) {
        $row_date = $report_link->read_at;
      } else {
        $row_date = $report_link->created_at->toDateString();
      }
      if(isset($row_date)) {
        if ($report_link->is_read == 1) {
          $report->{$row_date}['read'] += 1;
          if ($report_link->created_at->toDateString() == $row_date) {
            $report->{$row_date}['created'] += 1;
          }
        } else {
          $report->{$row_date}['created'] += 1;
        }
      }
    }
    $parsedReport = [];
    foreach ($report as $key => $value) {
      $parsedReport[] = [
        'Date' => $key,
        'Action' => 'Read',
        'Articles' => $value['read']
      ];
      $parsedReport[] = [
        'Date' => $key,
        'Action' => 'Added',
        'Articles' => $value['created']
      ];
    }

    return $this->successResponse(['report' => $parsedReport]);
  }

  /**
   * @param array $link
   * @return array
   */
  public function transform($link) {
    return [
      'id' => (int) $link['id'],
      'name' => $link['name'],
      'link' => $link['link'],
      'is_read' => filter_var($link['is_read'], FILTER_VALIDATE_BOOLEAN),
      'category' => $link['category'],
      'times_loaded' => (int) $link['times_loaded'],
      'times_read' => (int) $link['times_read']
    ];
  }

  /**
   * @param string
   * @return bool
   */
  public function checkLinkExists($checkLink) {
    $checkLink = substr($checkLink, strpos($checkLink, '//') + 2);
    if (strstr($checkLink, '?') && !strstr($checkLink, 'youtube')) {
      $checkLink = substr($checkLink, 0, strpos($checkLink, '?'));
    }
    $link = Link::where('link', 'LIKE', "%$checkLink%")->where('user_id', Auth::user()->id)->first();
    return isset($link);
  }

}

class Report {}