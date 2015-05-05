<?php

use Illuminate\Http\Response as IlluminateResponse;

abstract class AstroBaseController extends BaseController {

  abstract function transform($item);

  public function transformCollection ($items) {
    return array_map([$this, 'transform'], $items->toArray());
  }

  public function successResponse($data = null) {
    return Response::json($data, IlluminateResponse::HTTP_OK);
  }

  public function notFoundResponse($message) {
    return Response::json($message, IlluminateResponse::HTTP_NOT_FOUND);
  }

}