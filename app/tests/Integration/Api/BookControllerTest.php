<?php
/**
 * User: jasonsylvester
 * Date: 4/20/16
 * Time: 10:03 AM
 */

namespace Integration\Api;

use App\Models\Book as Book;
use Auth, Api\BookController, Mockery, TestCase, User;
use Faker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as IlluminateResponse;

class BookControllerTest extends TestCase {

  public function setUp() {
    parent::setUp();

    $this->client->setServerParameter('HTTP_X-Requested-With', 'XMLHttpRequest');
    $this->client->setServerParameter('HTTP_CONTENT_TYPE', 'application/json');
    $this->client->setServerParameter('HTTP_ACCEPT', 'application/json');
  }

  public function test_query_existing_user() {
    $this->mockUser(1);

    $bookController = new BookController();
    /** @var JsonResponse $response */
    $response = $bookController->query();
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
  }

  public function test_query_unknown_user() {
    $this->mockUser(9999);

    $bookController = new BookController();
    /** @var JsonResponse $response */
    $response = $bookController->query();
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
    $this->assertEquals(0, $response->getData(true)['total']);
  }

  public function test_recommendation_existing_user() {
    $this->mockUser(1);

    /** @var Book $bookToSave */
    $book = Book::where('user_id', 1)->first();

    /** @var JsonResponse $response */
    $response = $this->call('GET', "/api/book/recommendation/$book->category");
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
  }

  public function test_add_book_exists() {
    $this->mockUser(1);

    /** @var Book $bookToSave */
    $bookToSave = Book::where('user_id', 1)->first();
    $bookToSave->id = 0;

    /** @var JsonResponse $response */
    $response = $this->call('POST', '/api/book', $bookToSave->toArray());
    $this->assertEquals(IlluminateResponse::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
  }

  public function test_save_book() {
    $this->mockUser(1);

    /** @var Book $bookToSave */
    $bookToSave = Book::where('user_id', 1)->first();

    /** @var JsonResponse $response */
    $response = $this->call('POST', "/api/book/$bookToSave->id", $bookToSave->toArray());
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
  }

  public function test_save_book_different_user() {
    $this->mockUser(2);

    /** @var Book $bookToSave */
    $bookToSave = Book::where('user_id', 1)->first();

    /** @var JsonResponse $response */
    $response = $this->call('POST', "/api/book/$bookToSave->id", $bookToSave->toArray());
    $this->assertEquals(IlluminateResponse::HTTP_NOT_FOUND, $response->getStatusCode());
  }

  public function test_add_book_exists_different_user() {
    Book::where('user_id', 2)->delete();
    $this->mockUser(2);

    /** @var Book $bookToSave */
    $bookToSave = Book::where('user_id', 1)->first();
    unset($bookToSave['id']);
    unset($bookToSave['user_id']);

    /** @var JsonResponse $response */
    $response = $this->call('POST', '/api/book', $bookToSave->toArray());
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
  }

  public function test_add_book_does_not_exist() {
    $this->mockUser(1);

    $faker = Faker\Factory::create();

    /** @var Book $bookToSave */
    $bookToSave = new Book();
    $bookToSave->user_id = 1;
    $bookToSave->title = ucwords(implode(' ', $faker->words(3)));
    $bookToSave->author_fname = $faker->firstName;
    $bookToSave->author_lname = $faker->lastName;
    $bookToSave->category = $faker->boolean() ? 'To Read' : $faker->monthName();
    $bookToSave->is_read = $faker->boolean();
    $bookToSave->owned = $faker->boolean();
    $bookToSave->times_recommended = $faker->numberBetween(0, 50);

    /** @var JsonResponse $response */
    $response = $this->call('POST', '/api/book', $bookToSave->toArray());
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
  }

  public function test_delete_book_not_logged_in_users() {
    $this->mockUser(2);

    /** @var Book $bookToDelete */
    $bookToDelete = Book::where('user_id', 1)->first();

    /** @var JsonResponse $response */
    $response = $this->call('DELETE', "/api/book/$bookToDelete->id");
    $this->assertEquals(IlluminateResponse::HTTP_NOT_FOUND, $response->getStatusCode());
  }

  public function test_delete_book_logged_in_users() {
    $this->mockUser(1);

    /** @var Book $bookToDelete */
    $bookToDelete = Book::where('user_id', 1)->first();

    /** @var JsonResponse $response */
    $response = $this->call('DELETE', "/api/book/$bookToDelete->id");
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
  }

}