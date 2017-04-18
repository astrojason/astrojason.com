<?php

use App\Models\Book as LegacyBook;
use Book\Book, Book\Author, Book\Category, Book\Series, Book\Read;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MigrateBooks extends Command
{

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'books:migrate';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Migrate books from old data structure to new.';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct() {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function fire() {
    Book::truncate();
    Author::truncate();
    $books = LegacyBook::all();
    /** @var LegacyBook $book */
    foreach ($books as $book) {
      $migratedBook = Book::create([
        'title' => $book->title
      ]);
      $this->addBookAuthor($book->author_fname, $book->author_lname, $migratedBook);
      if($book->series != '') {
        $this->addBookSeries($book, $migratedBook);
      }
      if($book->is_read) {
        $bookRead = Read::create([
          'book_id' => $migratedBook->id,
        ]);
        $bookRead->setCreatedAt($book->updated_at);
        $bookRead->setUpdatedAt($book->updated_at);
      }
      if($book->category) {
        $this->addBookCategory($book, $migratedBook);
      }
      $migratedBook->users()->save(User::whereId($book->user_id)->first());
    }
  }

  /**
   * Get the console command arguments.
   *
   * @return array
   */
  protected function getArguments() {
    return [];
  }

  /**
   * Get the console command options.
   *
   * @return array
   */
  protected function getOptions() {
    return [];
  }

  private function addBookAuthor($first_name, $last_name, $book) {
    $author = Author::where('last_name', $last_name)->where('first_name', $first_name)->first();

    if(!isset($author)) {
      $author = Author::create([
        'first_name' => $first_name,
        'last_name' => $last_name
      ]);
    }
    $author->books()->save($book);
  }

  private function addBookSeries($book, $migratedBook) {
    $series = Series::where('name', $book->series)->first();
    if(!isset($series)) {
      $series = Series::create([
        'name' => $book->series
      ]);
    }
    $series->books()->save($migratedBook, ['number' => $book->series_order]);
  }

  private function addBookCategory($book, $migratedBook) {
    $category = Category::where('name', $book->category)->where('user_id', $book->user_id)->first();
    if(!isset($category)) {
      Category::create([
        'user_id' => $book->user_id,
        'name' => $book->category
      ]);
    }
  }
}
