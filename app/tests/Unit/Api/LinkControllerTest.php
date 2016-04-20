<?php
/**
 * User: jasonsylvester
 * Date: 4/19/16
 * Time: 11:28 AM
 */

namespace Unit\Api;

use Api\LinkController as LinkController, TestCase;


class LinkControllerTest extends TestCase {

  public function test_transform() {
    $linkToTransform = [
      'id' => '1',
      'name' => 'My Title',
      'link' => 'http://wwww.google.com',
      'category' => 'Unread',
      'is_read' => 0,
      'times_loaded' => '22',
      'times_read' => '12'
    ];

    $expectedTransformation = [
      'id' => 1,
      'name' => 'My Title',
      'link' => 'http://wwww.google.com',
      'category' => 'Unread',
      'is_read' => false,
      'times_loaded' => 22,
      'times_read' => 12
    ];
    $linkController = new LinkController();
    $this->assertEquals($expectedTransformation, $linkController->transform($linkToTransform));
  }
}
