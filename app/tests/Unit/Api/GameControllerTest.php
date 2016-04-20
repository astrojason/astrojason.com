<?php
/**
 * User: jasonsylvester
 * Date: 4/19/16
 * Time: 11:21 AM
 */

namespace Unit\Api;

use Api\GameController as GameController, TestCase;

class GameControllerTest extends TestCase {

  public function test_transform() {
    $gameToTransform = [
      'id' => '1',
      'title' => 'My Title',
      'platform' => 'XBox One',
      'completed' => 0,
      'times_recommended' => '22'
    ];

    $expectedTransformation = [
      'id' => 1,
      'title' => 'My Title',
      'platform' => 'XBox One',
      'completed' => false,
      'times_recommended' => 22
    ];
    $gameController = new GameController();
    $this->assertEquals($expectedTransformation, $gameController->transform($gameToTransform));
  }

}
