<?php


namespace Api;


class DashboardController extends AstroBaseController {

  public function get() {
    $total_links = \Link::where('user_id', \Auth::user()->id)->count();
    $links_read = \Link::where('user_id', \Auth::user()->id)->where('is_read', true)->count();
    $total_books = \Book::where('user_id', \Auth::user()->id)->count();
    $books_read = \Book::where('user_id', \Auth::user()->id)->where('is_read', true)->count();
    $books_unread = \Book::where('user_id', \Auth::user()->id)->where('is_read', false)->count();
    $games_unplayed = \Game::where('user_id', \Auth::user()->id)->where('completed', false)->count();
    $songs_unplayed = \Song::where('user_id', \Auth::user()->id)->where('learned', false)->count();
    $categories = \LinkController::getLinkCategories();
    $query = \Link::where('is_read', true)
      ->where('user_id', \Auth::user()->id);
    $query->where(function ($query) {
      $query->where('updated_at', 'LIKE', '%' . date('Y-m-d') . '%')
        ->orwhere('created_at', 'LIKE', '%' . date('Y-m-d') . '%');
    });
    $total_read = $query->count();


    return $this->successResponse(array(
      'success' => true,
      'total_read' => $total_read,
      'categories' => $categories,
      'total_links' => $total_links,
      'links_read' => $links_read,
      'total_books' => $total_books,
      'books_read' => $books_read,
      'books_toread' => $books_unread,
      'games_toplay' => $games_unplayed,
      'songs_toplay' => $songs_unplayed
    ));
  }

  public function transform($data) {
    return $data;
  }

}