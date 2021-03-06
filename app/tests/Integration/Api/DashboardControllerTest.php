<?php
/**
 * User: jasonsylvester
 * Date: 4/27/16
 * Time: 10:43 AM
 */

namespace Integration\Api;

use Auth, Mockery, TestCase, User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as IlluminateResponse;

class DashboardControllerTest extends TestCase {

  public function setUp() {
    parent::setUp();

    $this->client->setServerParameter('HTTP_X-Requested-With', 'XMLHttpRequest');
    $this->client->setServerParameter('HTTP_CONTENT_TYPE', 'application/json');
    $this->client->setServerParameter('HTTP_ACCEPT', 'application/json');

    $this->mockUser(1);
  }

  public function test_dashboard_call() {
    /** @var JsonResponse $response */
    $response = $this->call('GET', '/api/dashboard');
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
    $responseData = $response->getData(true);
    $this->assertTrue(array_key_exists('articles_read_today', $responseData));
    $this->assertTrue(array_key_exists('categories', $responseData));
    $this->assertTrue(array_key_exists('total_articles', $responseData));
    $this->assertTrue(array_key_exists('articles_read', $responseData));
  }

}
