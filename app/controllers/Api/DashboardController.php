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

    return $this->successResponse([
      'articles_read_today' => $articleController->readToday(),
      'categories' => $categories,
      'total_articles' => $total_articles,
      'articles_read' => $articles_read,
      'total_books' => $total_books,
      'books_read' => $books_read,
      'books_toread' => $books_unread,
      'games_toplay' => $games_unplayed,
      'songs_toplay' => $songs_unplayed
    ]);
  }
  public function transform($data) {
    return $data;
  }

}