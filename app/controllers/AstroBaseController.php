<?php


abstract class AstroBaseController extends BaseController {

  abstract function transform($item);

  public function transformCollection ($items) {
    return array_map([$this, 'transform'], $items->toArray());
  }

  public function successResponse($data = null) {
    return Response::json($data, 200);
  }

  public function notFoundResponse($message) {
    return Response::json($message, 404);
  }

}