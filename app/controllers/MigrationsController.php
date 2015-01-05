<?php
/**
 * Created by PhpStorm.
 * User: jasonsylvester
 * Date: 1/1/15
 * Time: 7:06 PM
 */

class MigrationsController extends BaseController {

  public function links() {
    $linksJson = json_decode(File::get(public_path() . '/assets/seeds/links.json'));
    $migrationCounter = 0;
    foreach($linksJson->books as $jsonLink) {
      $link = Book::where('link', $jsonLink->link)
        ->where('user_id', Auth::user()->id)
        ->first();
      if(!isset($link)){
        $link = new Link();
        $link->user_id = Auth::user()->id;
        $link->name = $jsonLink->name;
        $link->link = $jsonLink->link;
        $link->is_read = $jsonLink->read;
        $link->instapaper_id = $jsonLink->instapaper_id;
        $link->category = $jsonLink->category;
        $link->save();
        $migrationCounter++;
        print('Added ' . $link->name . '.<br />');
      }
    }
    return 'Migrated ' . $migrationCounter . ' books.';
  }

  public function books() {
    $booksJson = json_decode(File::get(public_path() . '/assets/seeds/books.json'));
    $migrationCounter = 0;
    foreach($booksJson->books as $jsonBook) {
      $book = Book::where('title', $jsonBook->title)
        ->where('author_fname', $jsonBook->author_fname)
        ->where('author_lname', $jsonBook->author_lname)
        ->where('user_id', Auth::user()->id)
        ->first();
      if(!isset($book)){
        $book = new Book();
        $book->user_id = Auth::user()->id;
        $book->goodreads_id = $jsonBook->goodreads_id;
        $book->author_fname = $jsonBook->author_fname;
        $book->author_lname = $jsonBook->author_lname;
        $book->category = $jsonBook->category;
        if(isset($jsonBook->series)) $book->series = $jsonBook->series;
        if(isset($jsonBook->series_order)) $book->series_order = $jsonBook->series_order;
        $book->is_read = $jsonBook->read;
        $book->owned = $jsonBook->owned;
        $book->title = $jsonBook->title;
        $book->save();
        $migrationCounter++;
        print('Added ' . $book->title . '.<br />');
      }
    }
    return 'Migrated ' . $migrationCounter . ' books.';
  }

  public function games() {
    $gamesJson = json_decode(File::get(public_path() . '/assets/seeds/games.json'));
    foreach($gamesJson as $jsonGame) {
      $game = Game::where('user_id', Auth::user()->id)
        ->where('title', $jsonGame->title)
        ->first();
      if(!isset($game)){

      }
    }
  }

} 