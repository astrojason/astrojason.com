<?php

class SongController extends BaseController {

  public function index() {
    $artists = SongController::getSongArtistsString();
    return View::make('songs.index')->with('song_artists', $artists);
  }

  /**
   * @return array
   */
  public static function getSongArtists() {
    $artists = [];
    $dbArtists = Song::groupBy('artist')
      ->where('user_id', Auth::user()->id)
      ->get(array('artist'));
    foreach ($dbArtists as $artist) {
      array_push($artists, $artist->artist);
    }
    return $artists;
  }

  /**
   * @return string
   */
  public static function getSongArtistsString() {
    $artists = [];
    $dbArtists = self::getSongArtists();
    foreach ($dbArtists as $artist) {
      $artists[] = $artist;
    }
    if ($artists) {
      $lastElement = end($artists);
    }
    $artistsString = '[';
    foreach ($artists as $artist) {
      $artistsString .= '\'' . htmlspecialchars($artist) . '\'';
      if ($artist != $lastElement) {
        $artistsString .= ',';
      }
    }
    $artistsString .= ']';
    return $artistsString;
  }

}
