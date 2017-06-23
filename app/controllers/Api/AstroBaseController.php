<?php

namespace Api;

use Controller;

use Illuminate\Http\Response as IlluminateResponse;

use Response;

abstract class AstroBaseController extends Controller {

  abstract function transform($item);

  public function transformCollection ($items) {
    return array_map([$this, 'transform'], $items->toArray());
  }

  public function successResponse($data = null) {
    return Response::json($data, IlluminateResponse::HTTP_OK);
  }

  public function errorResponse($message = '', $statusCode = IlluminateResponse::HTTP_INTERNAL_SERVER_ERROR) {
    return Response::json($message, $statusCode);
  }

  public function notFoundResponse($message) {
    return Response::json($message, IlluminateResponse::HTTP_NOT_FOUND);
  }

}
