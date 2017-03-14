<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {

  public $user;

  public $defaultUserId = 1;

  /**
   * Creates the application.
   *
   * @return \Symfony\Component\HttpKernel\HttpKernelInterface
   */
  public function createApplication() {
    $unitTesting = true;

    $testEnvironment = 'testing';

    return require __DIR__.'/../../bootstrap/start.php';
  }
  
  public function mockUser($userId) {
    $testUser = new User();
    $testUser->id = $userId;

    Auth::shouldReceive('user')->andReturn($testUser);
  }

  public function logUserIn() {
    $this->user = User::whereId($this->defaultUserId)->first();
    Auth::login($this->user);
  }

}
