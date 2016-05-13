<?php
/**
 * User: jasonsylvester
 * Date: 5/13/16
 * Time: 11:59 AM
 */

namespace Integration;

use Route, TestCase;

class UserControllerTests extends TestCase {

  public function test_access_no_login() {
    Route::enableFilters();

    $this->call('GET', '/account');
    $this->assertRedirectedTo('/');
  }

}
