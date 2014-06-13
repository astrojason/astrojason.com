<?php
/**
 * Created by PhpStorm.
 * User: jasonsylvester
 * Date: 4/1/14
 * Time: 6:31 PM
 */

class ApiController extends BaseController {

  public function allBooks() {
    $books = Book::where('user_id', Auth::user()->id)->orderby('series')->get();
    return Response::json(array('books' => $books->toArray()), 200);
  }

  public function bookCategories() {
    $categories = Book::groupBy('category')->get(array('category'));
    return Response::json(array('categories' => $categories->toArray()), 200);
  }

  public function nextBook() {
    $book = Book::where('read', false)->where('category', 'To Read')->where('user_id', Auth::user()->id)->orderBy(DB::raw($this->getOrderCommand()))->take(1)->get();
    if(count($book) > 0) {
      $book = $book[0];
      if($book->seriesorder != 0) {
        $book = Book::where('user_id', Auth::user()->id)->where('series', $book->series)->where('read', 0)->orderBy('seriesorder')->take(1)->get()[0];
      }
      return Response::json(array('book' => $book->toArray()), 200);
    } else {
      return Response::json(array('error' => 'No Books Available'), 200);
    }
  }

  public function markBookAsRead($id) {
    $book = Book::where('user_id', Auth::user()->id)->where('id', $id)->get();
    if(count($book) > 0){
      $book = $book[0];
      $book->read = true;
      $book->save();
      return Response::json(array('success' => true), 200);
    } else {
      return Response::json(array('success' => false), 200);
    }
  }

  public function deleteBook($id) {
    $book = Book::where('user_id', Auth::user()->id)->where('id', $id)->get();
    if(count($book) > 0){
      $book = $book[0];
      $book->delete();
      return Response::json(array('success' => true), 200);
    } else {
      return Response::json(array('success' => false), 200);
    }
  }

  public function saveBook() {
    if(Auth::check()) {
      if(Input::get('id')) {
        $book = Book::where('id', Input::get('id'))->where('user_id', Auth::user()->id)->get();
        if(count($book) == 0) {
          return Response::json(array('success' => false, 'error' => 'Attempting to update a book that does not exist for this user.'), 200);
        } else {
          $book = $book[0];
        }
      } else {
        $book = Book::where('user_id', Auth::user()->id)->where('title', Input::get('title'))->where('author_lname', Input::get('author_lname'))->get();
        if(count($book) == 0) {
          $book = new Book();
          $book->user_id = Auth::user()->id;
        } else {
          return Response::json(array('success' => false, 'error' => 'It appears that this book already exists.'), 200);
        }
      }
      try {
        $book->title = Input::get('title');
        $book->goodreads_id = Input::get('goodreads_id');
        $book->author_fname = Input::get('author_fname');
        $book->author_lname = Input::get('author_lname');
        $book->category = Input::get('category');
        $book->series = Input::get('series');
        $book->seriesorder = Input::get('seriesorder');
        if(!isset($book->read)) {
          $book->read = false;
        }
        $book->save();
        return Response::json(array('success' => true), 200);
      } catch(Exception $exception) {
        return Response::json(array('success' => false, 'error' => $exception->getMessage()), 200);
      }
    } else {
      return Response::json(array('success' => false, 'error' => 'You must log in to perform this action.'), 200);
    }
  }

  public function linkCategories() {
    $categories = Link::groupBy('category')->get(array('category'));
    return Response::json(array('categories' => $categories->toArray()), 200);
  }

  public function allLinks() {
    $links = Link::where('user_id', Auth::user()->id)->get();
    return Response::json(array('links' => $links->toArray()), 200);
  }

  public function filterLinks($query) {
    $links = Link::where('user_id', Auth::user()->id)->where('name', 'LIKE', "%$query%")->get();
    return Response::json(array('links' => $links->toArray()), 200);
  }

  public function todaysLinks() {
    $atHome = $this->getRandomLinks('At Home', 1);
    $cooking = $this->getRandomLinks('Cooking', 1);
    $exercise = $this->getRandomLinks('Exercise', 1);
    $forReview = $this->getRandomLinks('For Review', 1);
    $forTheHouse = $this->getRandomLinks('For the House', 1);
    $groups = $this->getRandomLinks('Groups', 1);
    $guitar = $this->getRandomLinks('Guitar', 1);
    $photography = $this->getRandomLinks('Photography', 1);
    $projects = $this->getRandomLinks('Projects', 1);
    $programming = $this->getRandomLinks('Programming', 1);
    $wishlist = $this->getRandomLinks('Wishlist', 1);
    $wordpress = $this->getRandomLinks('Wordpress', 1);
    $hockey = $this->getRandomLinks('Hockey Exercise', 1);
    $links = $this->getRandomLinks('Unread', 20);
    $daily = Link::where('read', false)->where('category', 'Daily')->where('user_id', Auth::user()->id)->get();

    return Response::json(array(
      'athome' => $atHome->toArray(),
      'cooking' => $cooking->toArray(),
      'exercise' => $exercise->toArray(),
      'forreview' => $forReview->toArray(),
      'forthehouse' => $forTheHouse->toArray(),
      'groups' => $groups->toArray(),
      'guitar' => $guitar->toArray(),
      'links' => $links->toArray(),
      'projects' => $projects->toArray(),
      'programming' => $programming->toArray(),
      'photography' => $photography->toArray(),
      'wishlist' => $wishlist->toArray(),
      'wordpress' => $wordpress->toArray(),
      'daily' => $daily->toArray(),
      'hockey' => $hockey->toArray()
    ), 200);
  }

  public function getRandomLinksAction($category, $quantity) {
    return Response::json(array('links' => $this->getRandomLinks($category, $quantity)->toArray()), 200);
  }

  public function saveLink() {
    if(Auth::check()) {
      if(Input::get('id')) {
        $link = Link::where('id', Input::get('id'))->where('user_id', Auth::user()->id)->get()[0];
      } else {
        // Make sure this link doesn't already exist for this user
        $link = Link::where('user_id', Auth::user()->id)->where('link', Input::get('link'))->get();
        if(count($link) == 0) {
          $link = new Link();
          $link->user_id = Auth::user()->id;
        } else {
          unset($link);
        }
      }
      if(isset($link)) {
        try {
          $link->name = Input::get('name');
          $link->link = Input::get('link');
          $link->category = Input::get('category');
          $link->read = Input::get('read');
          if(Input::get('instapaper_id')) {
            $link->instapaper_id = Input::get('instapaper_id');
          }
          $link->save();
          return Response::json(array('success' => true, 'link' => $link->toArray()), 200);
        } catch(Exception $exception) {
          return Response::json(array('success' => false, 'error' => $exception->getMessage()), 200);
        }
      } else {
        return Response::json(array('success' => false, 'error' => 'Link already exists'), 200);
      }
    }
  }

  public function addLinkFromBookmarklet() {
    $link = Link::where('user_id', Input::get('user_id'))->where('link', Input::get('link'))->get();
    if(count($link) == 0) {
      $link = new Link();
      $link->user_id = Input::get('user_id');
      $link->name = Input::get('name');
      $link->link = Input::get('link');
      $link->category = 'Unread';
      $link->read = false;
      $link->save();
      return Response::json(array('success' => true), 200, array('Access-Control-Allow-Origin' => '*'));
    } else {
      return Response::json(array('success' => false, 'error' => 'Link already exists'), 200, array('Access-Control-Allow-Origin' => '*'));
    }
  }

  public function markLinkAsRead($id){
    $link = Link::where('user_id', Auth::user()->id)->where('id', $id)->get();
    if(count($link) > 0) {
      $link = $link[0];
      $link->read = true;
      if($link->instapaper_id){
        $instapaper_client = $_SERVER["INSTAPAPER_CLIENT"];
        $instapaper_client_secret = $_SERVER["INSTAPAPER_CLIENT_SECRET"];
        $instapaper_username = $_SERVER["INSTAPAPER_USERNAME"];
        $instapaper_password = $_SERVER["INSTAPAPER_PASSWORD"];
        $instapaper = new InstapaperOAuth($instapaper_client, $instapaper_client_secret);
        $token = $instapaper->get_access_token($instapaper_username, $instapaper_password);
        $oauth_token = $token["oauth_token"];
        $oauth_token_secret = $token["oauth_token_secret"];
        $instapaper = new InstapaperOAuth($instapaper_client, $instapaper_client_secret ,$oauth_token,$oauth_token_secret);
        $instapaper->archive_bookmark($link->instapaper_id);
      }
      $link->save();
      return Response::json(array('success' => true), 200);
    } else {
      return Response::json(array('success' => false), 200);
    }
  }

  public function login(){
    $response = array('success' => false, 'reason' => 'None');
    $rules = array(
      'username'    => 'required',
      'password' => 'required|alphaNum|min:3'
    );
    // run the validation rules on the inputs from the form
    $validator = Validator::make(Input::all(), $rules);
    if ($validator->fails()) {
      $response['reason'] = 'Validator failed';
      return Response::json($response);
    } else {
      $userdata = array(
        'username' 	=> Input::get('username'),
        'password' 	=> Input::get('password')
      );
      if (Auth::attempt($userdata, true)) {
        $response['success'] = true;
        return Response::json($response);
      } else {
        $response['reason'] = 'Auth failed';
        return Response::json($response);
      }
    }
  }

  public function allMovies() {
    $movies = Movie::where('user_id', Auth::user()->id)->get();
    return Response::json(array('movies' => $movies->toArray()), 200);
  }

  public function compareMovies() {
    $movies = Movie::where('user_id', Auth::user()->id)->orderBy('rating_order')->get();
    $first_movie = rand(0, count($movies) - 5);
    $movies = $movies->toArray();
    $movies = array_splice($movies, $first_movie, 5);
    return Response::json(array('movies' => $movies), 200);
  }

  public function saveMovie() {
    try {
      if(Auth::check()) {
        if(Input::get('id')) {
          $movie = Movie::where('id', Input::get('id'))->where('user_id', Auth::user()->id)->get()[0];
          $movie->rating_order = Input::get('rating_order');
        } else {
          $movie = Movie::where('user_id', Auth::user()->id)->where('title', Input::get('title'))->get();
          if(count($movie) == 0) {
            $movie = new Movie();
            $movie->user_id = Auth::user()->id;
            $movie->rating_order = Movie::max('rating_order') + 10;
          } else {
            unset($movie);
            return Response::json(array('success' => false, 'message' => 'movie exists'), 200);
          }
        }
        if(isset($movie)) {
          $movie->title = Input::get('title');
          $movie->save();
          return Response::json(array('success' => true), 200);
        } else {
          return Response::json(array('success' => false), 200);
        }
      }
    } catch(Exeption $exception) {
      return Response::make($exception->getMessage());
    }
  }

  public function saveGame() {
    try {
      if(Auth::check()) {
        if(Input::get('id')) {
          $game = Game::where('id', Input::get('id'))->where('user_id', Auth::user()->id)->get()[0];
        } else {
          $game = Game::where('user_id', Auth::user()->id)->where('title', Input::get('title'))->get();
          if(count($game) == 0) {
            $game = new Game();
            $game->user_id = Auth::user()->id;
          } else {
            unset($game);
            return Response::json(array('success' => false, 'message' => 'game exists'), 200);
          }
        }
        if(isset($game)) {
          $game->title = Input::get('title');
          $game->platform = Input::get('platform');
          $game->save();
          return Response::json(array('success' => true), 200);
        } else {
          return Response::json(array('success' => false), 200);
        }
      }
    } catch(Exeption $exception) {
      return Response::make($exception->getMessage());
    }
  }

  public function nextGame() {
    $game = Game::where('completed', false)->where('user_id', Auth::user()->id)->orderBy(DB::raw($this->getOrderCommand()))->take(1)->get()[0];
    return Response::json(array('game' => $game->toArray()), 200);
  }

  public function markGameAsPlayed($id) {
    $game = Game::where('user_id', Auth::user()->id)->where('id', $id)->get();
    if(count($game) > 0){
      $game = $game[0];
      $game->completed = true;
      $game->save();
      return Response::json(array('success' => true), 200);
    } else {
      return Response::json(array('success' => false), 200);
    }
  }

  public function getOrderCommand() {
    return isset($_SERVER["DATABASE_URL"]) ? 'random()' : 'RAND()';
  }

  public function getRandomLinks($category, $quantity) {
    return Link::where('read', false)->where('category', $category)->where('user_id', Auth::user()->id)->orderBy(DB::raw($this->getOrderCommand()))->take($quantity)->get();
  }

} 