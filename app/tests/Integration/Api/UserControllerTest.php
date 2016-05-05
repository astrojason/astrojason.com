<?php
/**
 * User: jasonsylvester
 * Date: 5/3/16
 * Time: 12:10 PM
 */

namespace Integration\Api;

use Auth;
use Faker;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Http\JsonResponse;
use TestCase;
use User;

class UserControllerTest extends TestCase {

  public function setUp() {
    parent::setUp();

    $this->client->setServerParameter('HTTP_X-Requested-With', 'XMLHttpRequest');
    $this->client->setServerParameter('HTTP_CONTENT_TYPE', 'application/json');
    $this->client->setServerParameter('HTTP_ACCEPT', 'application/json');
  }

  private function generateRegistrationInfo() {
    $faker = Faker\Factory::create();
    return [
      'username' => $faker->userName,
      'first_name' => $faker->firstName,
      'last_name' => $faker->lastName,
      'email' => $faker->email,
      'password' => $faker->password
    ];
  }

  public function test_login_failure_no_data() {
    /** @var JsonResponse $response */
    $response = $this->call('POST', '/api/user/login');
    $this->assertEquals(IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
  }

  public function test_login_failure_no_user() {
    /** @var JsonResponse $response */
    $response = $this->call('POST', '/api/user/login', ['username' => 'doesnotexist', 'password' => 'a']);
    $this->assertEquals(IlluminateResponse::HTTP_UNAUTHORIZED, $response->getStatusCode());
  }

  public function test_login_failure_wrong_password() {
    /** @var JsonResponse $response */
    $response = $this->call('POST', '/api/user/login', ['username' => 'astrojason', 'password' => 'z']);
    $this->assertEquals(IlluminateResponse::HTTP_UNAUTHORIZED, $response->getStatusCode());
  }

  public function test_login_correct_password() {
    /** @var JsonResponse $response */
    $response = $this->call('POST', '/api/user/login', ['username' => 'astrojason', 'password' => 'a']);
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
    $this->assertEquals(Auth::user()->toArray()['username'], $response->getData(true)['user']['username']);
  }

  public function test_logout_not_logged_in() {
    $this->call('POST', '/api/user/logout');
    $this->assertEquals(false, Auth::check());
  }

  public function test_logout_logged_in() {
    $userdata = [
      'username' 	=> 'astrojason',
      'password' 	=> 'a'
    ];
    Auth::attempt($userdata, true);
    $this->call('POST', '/api/user/logout');
    $this->assertEquals(false, Auth::check());
  }

  public function test_check_username_exists() {
  /** @var JsonResponse $response */
  $response = $this->call('POST', '/api/user/checkusername', ['username' => 'astrojason']);
  $this->assertEquals(IlluminateResponse::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
}

  public function test_check_username_does_not_exist() {
    /** @var JsonResponse $response */
    $response = $this->call('POST', '/api/user/checkusername', ['username' => 'newuser']);
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
  }

  public function test_check_email_exists() {
    /** @var JsonResponse $response */
    $response = $this->call('POST', '/api/user/checkemail', ['email' => 'jason@astrojason.com']);
    $this->assertEquals(IlluminateResponse::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
  }

  public function test_check_email_does_not_exist() {
    /** @var JsonResponse $response */
    $response = $this->call('POST', '/api/user/checkemail', ['email' => 'test@test.com']);
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
  }

  public function test_user_create_existing_username() {
    $data = $this->generateRegistrationInfo();
    $data['username'] = 'astrojason';
    /** @var JsonResponse $response */
    $response = $this->call('PUT', '/api/user', $data);
    $this->assertEquals(IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
  }

  public function test_user_create_existing_email() {
    $data = $this->generateRegistrationInfo();
    $data['email'] = 'jason@astrojason.com';
    /** @var JsonResponse $response */
    $response = $this->call('PUT', '/api/user', $data);
    $this->assertEquals(IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
  }

  public function test_user_create() {
    $data = $this->generateRegistrationInfo();
    User::where('username', $data['username'])->delete();
    User::where('email', $data['email'])->delete();
    /** @var JsonResponse $response */
    $response = $this->call('PUT', '/api/user', $data);
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
  }
}
