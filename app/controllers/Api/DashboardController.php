<?php


namespace Api;

use Auth, App\Models\Book, Game, App\Models\Song, Api\Article\CategoryController, Api\Article\ArticleController;

class DashboardController extends AstroBaseController {

  public function get() {
    $categoryController = new CategoryController();
    $articleController = new ArticleController();
    $user_id = Auth::user()->id;
    $total_articles = $articleController->articleCount();
    $articles_read = $articleController->readCount();
    $total_books = Book::where('user_id', $user_id)->count();
    $books_read = Book::where('user_id', $user_id)->where('is_read', true)->count();
    $books_unread = Book::where('user_id', $user_id)->where('is_read', false)->count();
    $games_unplayed = Game::where('user_id', $user_id)->where('completed', false)->count();
    $songs_unplayed = Song::where('user_id', $user_id)->where('learned', false)->count();
    $categories = $categoryController->query();
    $dashboard_layout = \DashboardCategory::where('user_id', $user_id)->get();

    return $this->successResponse([
      'articles_read_today' => $articleController->readToday(),
      'categories' => $categories,
      'total_articles' => $total_articles,
      'articles_read' => $articles_read,
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