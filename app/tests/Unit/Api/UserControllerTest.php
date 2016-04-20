<?php
/**
 * User: jasonsylvester
 * Date: 4/20/16
 * Time: 9:58 AM
 */

namespace Unit\Api;

use Api\UserController as UserController, TestCase;

class UserControllerTest extends TestCase {

  public function test_transform() {
    $userToTransform = [
      'id' => '1',
      'firstname' => 'firstname',
      'lastname' => 'lastname',
      'username' => 'username',
      'email' => 'email'
    ];
    $expectedTransformation = [
      'id' => 1,
      'firstname' => 'firstname',
      'lastname' => 'lastname',
      'username' => 'username',
      'email' => 'email'
    ];
    $userController = new UserController();
    $this->assertEquals($expectedTransformation, $userController->transform($userToTransform));
  }

}
