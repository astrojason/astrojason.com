<?php
/**
 * User: jasonsylvester
 * Date: 4/27/16
 * Time: 10:52 AM
 */

namespace Integration\Api;

use App\Models\Song;
use Mockery, TestCase;
use Faker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as IlluminateResponse;

class SongControllerTest extends TestCase {

  public function setUp() {
    parent::setUp();

    $this->client->setServerParameter('HTTP_X-Requested-With', 'XMLHttpRequest');
    $this->client->setServerParameter('HTTP_CONTENT_TYPE', 'application/json');
    $this->client->setServerParameter('HTTP_ACCEPT', 'application/json');
  }

  public function test_song_query() {
    $this->mockUser(1);

    /** @var JsonResponse $response */
    $response = $this->call('GET', '/api/song');
    $responseData = $response->getData(true);
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
    $this->assertTrue(array_key_exists('songs', $responseData));
    $this->assertTrue(array_key_exists('total', $responseData));
    $this->assertTrue(array_key_exists('pages', $responseData));
  }

  public function test_add_new_song() {
    $this->mockUser(1);

    $faker = Faker\Factory::create();
    $data = [
      'title' => ucwords(implode(' ', $faker->words(2))),
      'artist' => $faker->monthName(),
      'location' => $faker->word()
    ];
    $response = $this->call('POST', '/api/song', $data);
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
  }

  public function test_add_existing_song() {
    $this->mockUser(1);

    $data = Song::where('user_id', 1)->firstOrFail()->toArray();
    unset($data['id']);
    $response = $this->call('POST', '/api/song', $data);
    $this->assertEquals(IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
  }

  public function test_add_existing_song_different_user() {
    Song::where('user_id', 2)->delete();
    $this->mockUser(2);

    $data = Song::where('user_id', 1)->firstOrFail()->toArray();
    unset($data['id']);
    unset($data['user_id']);
    $response = $this->call('POST', '/api/song', $data);
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
  }

  public function test_save_nonexistant_song() {
    $this->mockUser(1);

    $response = $this->call('POST', '/api/song/9999');
    $this->assertEquals(IlluminateResponse::HTTP_NOT_FOUND, $response->getStatusCode());
  }

  public function test_delete_song_not_users() {
    $this->mockUser(2);

    $songToDelete = Song::where('user_id', 1)->firstOrFail();

    $response = $this->call('DELETE', "/api/song/$songToDelete->id");
    $this->assertEquals(IlluminateResponse::HTTP_NOT_FOUND, $response->getStatusCode());
  }

  public function test_delete_song() {
    $this->mockUser(1);

    $songToDelete = Song::where('user_id', 1)->firstOrFail();

    $response = $this->call('DELETE', "/api/song/$songToDelete->id");
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
  }
}
