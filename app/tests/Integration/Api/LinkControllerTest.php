<?php
/**
 * User: jasonsylvester
 * Date: 4/29/16
 * Time: 11:48 AM
 */

namespace Integration\Api;

use Faker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as IlluminateResponse;
use Link;
use TestCase;

class LinkControllerTest extends TestCase {

  public function setUp() {
    parent::setUp();

    $this->client->setServerParameter('HTTP_X-Requested-With', 'XMLHttpRequest');
    $this->client->setServerParameter('HTTP_CONTENT_TYPE', 'application/json');
    $this->client->setServerParameter('HTTP_ACCEPT', 'application/json');

    Link::where('user_id', 2)->delete();
  }

  public function test_query() {
    $this->mockUser(1);

    /** @var JsonResponse $response */
    $response = $this->call('GET', '/api/link');
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
  }

  public function test_add_link() {
    $this->mockUser(2);
    $faker = Faker\Factory::create();

    $linkToSave = [
      'name' => ucwords(implode(' ', $faker->words(3))),
      'link' => $faker->url(),
      'category' => 'Unread'
    ];

    /** @var JsonResponse $response */
    $response = $this->call('POST', "/api/link/", $linkToSave);
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
  }

  public function test_add_link_exists() {
    $this->mockUser(1);

    $linkToSave = Link::where('user_id', 1)->first();
    unset($linkToSave['id']);

    /** @var JsonResponse $response */
    $response = $this->call('POST', "/api/link/", $linkToSave->toArray());
    $this->assertEquals(IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
  }

  public function test_add_link_exists_different_querystring() {
    $this->mockUser(1);
    $faker = Faker\Factory::create();

    $fakeUrl = $faker->url();

    $linkToSave = new Link();
    $linkToSave->user_id = 1;
    $linkToSave->name = ucwords(implode(' ', $faker->words(3)));
    $linkToSave->link = $fakeUrl . '?' . $faker->word() . '='. $faker->word();
    $linkToSave->category = 'Unread';
    $linkToSave->save();

    $linkToSave->id = null;
    $linkToSave->link = $fakeUrl . '?' . $faker->word() . '='. $faker->word();

    /** @var JsonResponse $response */
    $response = $this->call('POST', "/api/link/", $linkToSave->toArray());
    $this->assertEquals(IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
  }

  public function test_add_link_exists_not_user() {
    $this->mockUser(2);

    $linkToSave = Link::where('user_id', 1)->first();
    unset($linkToSave['id']);
    unset($linkToSave['user_id']);

    /** @var JsonResponse $response */
    $response = $this->call('POST', "/api/link/", $linkToSave->toArray());
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
  }

  public function test_save() {
    $this->mockUser(1);

    $linkToSave = Link::where('user_id', 1)->first();

    /** @var JsonResponse $response */
    $response = $this->call('POST', "/api/link/$linkToSave->id", $linkToSave->toArray());
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
  }

  public function test_save_not_user() {
    $this->mockUser(2);

    $linkToSave = Link::where('user_id', 1)->first();

    /** @var JsonResponse $response */
    $response = $this->call('POST', "/api/link/$linkToSave->id", $linkToSave->toArray());
    $this->assertEquals(IlluminateResponse::HTTP_NOT_FOUND, $response->getStatusCode());
  }

  public function test_delete() {
    $this->mockUser(1);

    $linkToSave = Link::where('user_id', 1)->first();
    /** @var JsonResponse $response */
    $response = $this->call('DELETE', "/api/link/$linkToSave->id");
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
  }

  public function test_delete_not_user() {
    $this->mockUser(2);

    $linkToSave = Link::where('user_id', 1)->first();
    /** @var JsonResponse $response */
    $response = $this->call('DELETE', "/api/link/$linkToSave->id");
    $this->assertEquals(IlluminateResponse::HTTP_NOT_FOUND, $response->getStatusCode());
  }

  public function test_populate() {
    $this->mockUser(2);

    /** @var JsonResponse $response */
    $response = $this->call('GET', "/api/link/populate");
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
    $numPopulatedLinks = Link::where('user_id', 2)->count();
    $this->assertEquals(40, $numPopulatedLinks);
  }

  public function test_import() {
    $this->mockUser(2);
    $importlist = [];

    $faker = Faker\Factory::create();

    for($i = 0; $i < 20; $i++) {
      $importlist[] = [
        'name' => ucwords(implode(' ', $faker->words(3))),
        'url' => $faker->url()
      ];
    }
    /** @var JsonResponse $response */
    $response = $this->call('POST', '/api/link/import', ['importlist' => $importlist]);
    $this->assertEquals(20, $response->getData(true)['count']);
  }

  public function test_import_existing_link() {
    $this->mockUser(2);
    $importList = [];

    for($i = 0; $i < 20; $i++) {
      $newLink = $this->generateNewLink();
      while(in_array($newLink, $importList)) {
        $newLink = $this->generateNewLink();
      }

      $importList[] = $newLink;
    }
    $linkToSave = Link::where('user_id', 1)->first();

    $newLink = new Link();
    $newLink->user_id = 2;
    $newLink->link = $linkToSave->link;
    $newLink->name = $linkToSave->name;
    $newLink->save();

    $importList[] = [
      'url' => $linkToSave->link,
      'name' => $linkToSave->name
    ];
    /** @var JsonResponse $response */
    $response = $this->call('POST', '/api/link/import', ['importlist' => $importList]);
    $this->assertEquals(20, $response->getData(true)['count']);
    $this->assertEquals(1, $response->getData(true)['skipped']);
  }

  public function test_update_read_count() {
    $this->mockUser(1);

    $response = $this->call('GET', '/api/link/readtoday');
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
  }

  private function generateNewLink() {
    $faker = Faker\Factory::create();
    return [
      'name' => ucwords(implode(' ', $faker->words(3))),
      'url' => $faker->url()
    ];
  }
  
}
