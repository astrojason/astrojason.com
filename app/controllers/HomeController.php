<?php

class HomeController extends BaseController {

	public function showIndex() {
    $bookmarklet = null;
    $categoriesString = null;
    if(Auth::user()) {
      $bookmarklet = str_replace('"', "'", file_get_contents('assets/js/bookmarkletLoader.min.js'));
			// TODO: Make this a shared function
			$categories = [];
			$dbCategories = Book::groupBy('category')
				->where('user_id', Auth::user()->id)
				->get(array('category'));
			foreach($dbCategories as $category) {
				$categories[] = $category->category;
			}
			if($categories) {
				$lastElement = end($categories);
			}
			$categoriesString = '[';
			foreach($categories as $category) {
				$categoriesString .= '\'' . $category . '\'';
				if($category != $lastElement) {
					$categoriesString .= ',';
				}
			}
			$categoriesString .= ']';
    }
		return View::make('index')->with('bookmarklet', $bookmarklet)->with('book_categories', $categoriesString);
	}

  public function getDashboard() {
    $total_links = Link::where('user_id', Auth::user()->id)->count();
    $links_read = Link::where('user_id', Auth::user()->id)->where('is_read', true)->count();
    $total_books = Book::where('user_id', Auth::user()->id)->count();
    $books_read = Book::where('user_id', Auth::user()->id)->where('is_read', true)->count();
		$books_unread = Book::where('user_id', Auth::user()->id)->where('is_read', false)->count();
		$games_unplayed = Game::where('user_id', Auth::user()->id)->where('completed', false)->count();

    $links = Link::where('is_read', false)
      ->where('category', 'Daily')
      ->where('user_id', Auth::user()->id)
      ->get();

    $categories = [];
    $dbCategories = Link::groupBy('category')
      ->where('category', '<>', 'Daily')
      ->where('category', '<>', 'Unread')
      ->where('user_id', Auth::user()->id)
      ->get(array('category'));
    foreach($dbCategories as $category) {
      array_push($categories, $category->category);
    }
    $total_read = Link::where('updated_at', 'LIKE', date('Y-m-d') . '%')->where('is_read', true)->where('user_id', Auth::user()->id)->count();
    return Response::json(array(
      'success' => true,
      'total_read' => $total_read,
      'categories' => $categories,
      'total_links' => $total_links,
      'links_read' => $links_read,
      'total_books' => $total_books,
      'books_read' => $books_read,
			'books_toread' => $books_unread,
			'games_toplay' => $games_unplayed
    ), 200);
  }

	public function register() {
		return View::make('register');
	}
}
