<?php

use App\Models\Song as LegacySong;
use Guitar\Song, Guitar\Artist, Guitar\Category;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MigrateSongs extends Command
{

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'songs:migrate';

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
    Song::truncate();
    Artist::truncate();
    Category::truncate();
    Category::create([
      'name' => 'Project'
    ]);
    $ego = Category::create([
      'name' => 'Ego'
    ]);
    $songs = LegacySong::all();
    /** @var LegacySong $song */
    foreach ($songs as $song) {
      $migratedSong = Song::create([
        'title' => $song->title
      ]);
      $this->addSongArtist($song, $migratedSong);

      $migratedSong->users()->save(User::whereId($song->user_id)->first());
      $migratedSong->categories()->save($ego, ['user_id' => $song->user_id]);
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

  private function addSongArtist($song, $migratedSong) {
    $artist = Artist::whereName($song->artist)->first();
    if(!isset($artist)) {
      $artist = Artist::create([
        'name' => $song->artist
      ]);
    }
    $artist->songs()->save($migratedSong);
  }

}
