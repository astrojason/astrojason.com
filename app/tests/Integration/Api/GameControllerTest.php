<?php
/**
 * User: jasonsylvester
 * Date: 4/27/16
 * Time: 10:52 AM
 */

namespace Integration\Api;

use Game;
use Mockery, TestCase;
use Faker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as IlluminateResponse;

class GameControllerTest extends TestCase {

  public function setUp() {
    parent::setUp();

    $this->client->setServerParameter('HTTP_X-Requested-With', 'XMLHttpRequest');
    $this->client->setServerParameter('HTTP_CONTENT_TYPE', 'application/json');
    $this->client->setServerParameter('HTTP_ACCEPT', 'application/json');
  }

  public function test_game_query() {
    $this->mockUser(1);

    /** @var JsonResponse $response */
    $response = $this->call('GET', '/api/game');
    $responseData = $response->getData(true);
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
    $this->assertTrue(array_key_exists('games', $responseData));
    $this->assertTrue(array_key_exists('total', $responseData));
    $this->assertTrue(array_key_exists('pages', $responseData));
  }

  public function test_add_new_game() {
    $this->mockUser(1);

    $faker = Faker\Factory::create();
    $data = [
      'title' => ucwords(implode(' ', $faker->words(2))),
      'platform' => $faker->monthName(),
      'completed' => $faker->boolean()
      ];
    $response = $this->call('POST', '/api/game', $data);
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
  }

  public function test_add_existing_game() {
    $this->mockUser(1);

    $data = Game::where('user_id', 1)->firstOrFail()->toArray();
    unset($data['id']);
    $response = $this->call('POST', '/api/game', $data);
    $this->assertEquals(IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
  }

  public function test_add_existing_game_different_user() {
    Game::where('user_id', 2)->delete();
    $this->mockUser(2);

    $data = Game::where('user_id', 1)->firstOrFail()->toArray();
    unset($data['id']);
    unset($data['user_id']);
    $response = $this->call('POST', '/api/game', $data);
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
  }

  public function test_save_nonexistant_game() {
    $this->mockUser(1);

    $response = $this->call('POST', '/api/game/9999');
    $this->assertEquals(IlluminateResponse::HTTP_NOT_FOUND, $response->getStatusCode());
  }

  public function test_delete_game_not_users() {
    $this->mockUser(2);

    $gameToDelete = Game::where('user_id', 1)->firstOrFail();

    $response = $this->call('DELETE', "/api/game/$gameToDelete->id");
    $this->assertEquals(IlluminateResponse::HTTP_NOT_FOUND, $response->getStatusCode());
  }

  public function test_delete_game() {
    $this->mockUser(1);

    $gameToDelete = Game::where('user_id', 1)->firstOrFail();

    $response = $this->call('DELETE', "/api/game/$gameToDelete->id");
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
  }

  public function test_recommendation_existing_user() {
    $this->mockUser(1);

    $response = $this->call('GET', "/api/game/recommendation");
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
  }
}
