<?php
/**
 * Created by PhpStorm.
 * User: jasonsylvester
 * Date: 4/1/14
 * Time: 6:31 PM
 */

class ApiController extends BaseController {

  public function allBooks() {
    $books = Book::where('user_id', Auth::user()->id)->get();
    return Response::json(array('books' => $books->toArray()), 200);
  }

  public function nextBook() {
    $book = Book::where('read', false)->where('category', 'Fiction')->where('user_id', Auth::user()->id)->orderBy(DB::raw($this->getOrderCommand()))->take(1)->get();
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
      return Redirect::action('ApiController@nextBook');
    } else {
      return Response::json(array('success' => false), 200);
    }
  }

  public function saveBook() {
    if(Auth::check()) {
      if(Input::get('id')) {
        $book = Book::where('id', Input::get('id'))->where('user_id', Auth::user()->id)->get()[0];
      } else {
        $book = Book::where('user_id', Auth::user()->id)->where('title', Input::get('title'))->where('author_lname', Input::get('author_lname'))->get();
        if(count($book) == 0) {
          $book = new Book();
        } else {
          return Response::json(array('success' => false), 200);
        }
      }
      if(isset($book)) {
        try {
          $book->user_id = Auth::user()->id;
          $book->title = Input::get('title');
          $book->goodreads_id = Input::get('goodreads_id');
          $book->author_fname = Input::get('author_fname');
          $book->author_lname = Input::get('author_lname');
          $book->category = Input::get('category');
          $book->series = Input::get('series');
          $book->seriesorder = Input::get('seriesorder');
          $book->read = Input::get('read');
          $book->save();
          return Response::json(array('success' => true), 200);
        } catch(Exception $exception) {
          return Response::make($exception->getMessage());
        }
      } else {
        return Response::json(array('success' => false), 200);
      }
    } else {
      return Response::json(array('success' => false), 200);
    }
  }

  public function allLinks() {
    $links = Link::where('user_id', Auth::user()->id)->get();
    return Response::json(array('links' => $links->toArray()), 200);
  }

  public function todaysLinks() {
    $categories = Link::groupBy('category')->get(array('category'));
    $links = Link::where('read', false)->where('category', 'Unread')->where('user_id', Auth::user()->id)->orderBy(DB::raw($this->getOrderCommand()))->take(20)->get();
    $programming = Link::where('read', false)->where('category', 'Programming')->where('user_id', Auth::user()->id)->orderBy(DB::raw($this->getOrderCommand()))->take(1)->get();
    $photography = Link::where('read', false)->where('category', 'Photography')->where('user_id', Auth::user()->id)->orderBy(DB::raw($this->getOrderCommand()))->take(1)->get();
    $guitar = Link::where('read', false)->where('category', 'Guitar')->where('user_id', Auth::user()->id)->orderBy(DB::raw($this->getOrderCommand()))->take(1)->get();
    return Response::json(array('links' => $links->toArray(),
      'programming' => $programming->toArray(),
      'photography' => $photography->toArray(),
      'guitar' => $guitar->toArray(),
      'categories' => $categories->toArray()
    ), 200);
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
          return Response::make($exception->getMessage());
        }
      } else {
        return Response::json(array('success' => false), 200);
      }
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

  //TODO: Create edit functions for movies

  //TODO: Create functions for games

  public function getOrderCommand() {
    return isset($_SERVER["DATABASE_URL"]) ? 'random()' : 'RAND()';
  }
} 