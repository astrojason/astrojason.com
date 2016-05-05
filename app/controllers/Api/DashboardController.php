<?php


namespace Api;

use Auth, App\Models\Book as Book, Game, Link, Song;

class DashboardController extends AstroBaseController {

  public function get() {
    $total_links = Link::where('user_id', Auth::user()->id)->count();
    $links_read = Link::where('user_id', Auth::user()->id)->where('is_read', true)->count();
    $total_books = Book::where('user_id', Auth::user()->id)->count();
    $books_read = Book::where('user_id', Auth::user()->id)->where('is_read', true)->count();
    $books_unread = Book::where('user_id', Auth::user()->id)->where('is_read', false)->count();
    $games_unplayed = Game::where('user_id', Auth::user()->id)->where('completed', false)->count();
    $songs_unplayed = Song::where('user_id', Auth::user()->id)->where('learned', false)->count();
    $categories = \LinkController::getLinkCategories();
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
      'songs_toplay' => $songs_unplayed
    ]);
  }

  public function transform($data) {
    return $data;
  }

}