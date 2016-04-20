<?php
/**
 * User: jasonsylvester
 * Date: 4/19/16
 * Time: 11:32 AM
 */

namespace Unit\Api;

use Api\MovieController As MovieController, TestCase;

class MovieControllerTest extends TestCase {

  public function test_transform() {
    $movieToTransform = [
      'id' => '1',
      'title' => 'My Title',
      'is_watched' => 1,
      'times_watched' => '22',
      'rating_order' => '70'
    ];

    $expectedTransformation = [
      'id' => 1,
      'title' => 'My Title',
      'is_watched' => true,
      'times_watched' => 22,
      'rating_order' => 70
    ];
    $movieController = new MovieController();
    $this->assertEquals($expectedTransformation, $movieController->transform($movieToTransform));
  }

}
