<?php
/**
 * Created by PhpStorm.
 * User: jasonsylvester
 * Date: 9/27/15
 * Time: 3:15 PM
 */

namespace Unit\Api;

use Api\BookController as BookController, TestCase;

class BookControllerTest extends TestCase {

  public function test_transform() {
    $bookToTransform = [
      'id' => '1',
      'title' => 'My Title',
      'author_fname' => 'First',
      'author_lname' => 'Last',
      'series' => '',
      'series_order' => '',
      'category' => 'Unread',
      'owned' => 0,
      'is_read' => 0,
      'times_recommended' => '22'
    ];

    $expectedTransformation = [
      'id' => 1,
      'title' => 'My Title',
      'author_fname' => 'First',
      'author_lname' => 'Last',
      'series' => '',
      'series_order' => 0,
      'category' => 'Unread',
      'owned' => false,
      'is_read' => false,
      'times_recommended' => 22
    ];
    $bookController = new BookController();
    $this->assertEquals($expectedTransformation, $bookController->transform($bookToTransform));
  }

}