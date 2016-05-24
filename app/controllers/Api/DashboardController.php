<?php


namespace Api;

use Auth, App\Models\Book as Book, Game, Link, Song;

class DashboardController extends AstroBaseController {

  public function get() {
    $user_id = Auth::user()->id;
    $total_links = Link::where('user_id', $user_id)->count();
    $links_read = Link::where('user_id', $user_id)->where('is_read', true)->count();
    $total_books = Book::where('user_id', $user_id)->count();
    $books_read = Book::where('user_id', $user_id)->where('is_read', true)->count();
    $books_unread = Book::where('user_id', $user_id)->where('is_read', false)->count();
    $games_unplayed = Game::where('user_id', $user_id)->where('completed', false)->count();
    $songs_unplayed = Song::where('user_id', $user_id)->where('learned', false)->count();
    $categories = \LinkController::getLinkCategories();
    $dashboard_layout = \DashboardCategory::where('user_id', $user_id)->get();
    $linkController = new LinkController();

    return $this->successResponse([
      'total_read' => $linkController->getReadTodayCount(),
      'categories' => $categories,
      'total_links' => $total_links,
      'links_read' => $links_read,
      'total_books' => $total_books,
      'books_read' => $books_read,
      'books_toread' => $books_unread,
      'games_toplay' => $games_unplayed,
      'songs_toplay' => $songs_unplayed,
      'dashboard_layout' => $this->transformDashboardLayout($dashboard_layout)
    ]);
  }
  public function transform($data) {
    return $data;
  }

  private function transformDashboardLayout($data) {
    foreach ($data as $item){
      $item['num_items'] = (int)$item['num_items'];
      $item['randomize'] = filter_var($item['randomize'], FILTER_VALIDATE_BOOLEAN);
      $item['track'] = filter_var($item['track'], FILTER_VALIDATE_BOOLEAN);
      if($item['num_items'] == 0) {
        $item['num_items'] = false;
      }
    }
    return $data;
  }

}