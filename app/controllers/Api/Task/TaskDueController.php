<?php

namespace Api\Task;

use Api\AstroBaseController;
use Auth;
use Illuminate\Http\Response as IlluminateResponse;
use Task\TaskDue;

class TaskDueController extends AstroBaseController {

  private $user = null;

  public function __construct() {
    $this->user = Auth::user();
  }

  public function complete($task_due_id) {
    $task = TaskDue::where('id', $task_due_id)->firstOrFail();
    if($task->task->user_id == $this->user->id) {
      $task->completed = true;
      $task->save();
      return $this->successResponse();
    } else {
      $this->errorResponse(
        "You are not allowed to complete someone else's task.",
        IlluminateResponse::HTTP_FORBIDDEN);
    }
  }
  public function skip($task_due_id) {
    $task = TaskDue::where('id', $task_due_id)->firstOrFail();
    if($task->task->user_id == $this->user->id) {
      $task->skipped = true;
      $task->save();
      return $this->successResponse();
    } else {
      $this->errorResponse(
        "You are not allowed to skip someone else's task.",
        IlluminateResponse::HTTP_FORBIDDEN);
    }
  }

  public function postpone($task_id) {
    $task = TaskDue::where('id', $task_due_id)->firstOrFail();
    if($task->task->user_id == $this->user->id) {
      $task->due = $task->due->addDays(1);
      $task->save();
      return $this->successResponse();
    } else {
      $this->errorResponse(
        "You are not allowed to postpone someone else's task.",
        IlluminateResponse::HTTP_FORBIDDEN);
    }
  }

  public function transform($item) {
    return $item;
  }

}
