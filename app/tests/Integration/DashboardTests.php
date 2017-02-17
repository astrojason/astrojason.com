<?php
/**
 * User: jasonsylvester
 * Date: 2/6/17
 * Time: 11:07 AM
 */

namespace Integration;

use Route, TestCase;

class DashboardTests extends TestCase {

  public function test_no_login() {
    Route::enableFilters();

    $response = $this->call('GET', '/');
    $this->assertContains('Do you have too much stuff to read, paralyzed by choices. Let me decide! Create an account now.', $response->getContent());
  }

  public function test_logged_in() {
    $this->logUserIn();
    $response = $this->call('GET', '/');

    $this->assertContains("Hello $this->user->first_name", $response->getContent());
  }

}
