<?php
/**
 * User: jasonsylvester
 * Date: 4/19/16
 * Time: 11:39 AM
 */

namespace Unit\Api;

use Api\SongController as SongController, TestCase;

class SongControllerTest extends TestCase {

  public function test_transform() {
    $songToTransform = [
      'id' => '1',
      'title' => 'My Title',
      'learned' => 1,
      'times_recommended' => '22',
      'artist' => 'My Artist',
      'location' => 'My Location'
    ];

    $expectedTransformation = [
      'id' => 1,
      'title' => 'My Title',
      'learned' => true,
      'times_recommended' => 22,
      'artist' => 'My Artist',
      'location' => 'My Location'
    ];
    $songController = new SongController();
    $this->assertEquals($expectedTransformation, $songController->transform($songToTransform));
  }

}
