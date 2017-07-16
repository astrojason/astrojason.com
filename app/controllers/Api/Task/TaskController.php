<?php

namespace Api\Task;

use Api\AstroBaseController;
use Auth;
use Illuminate\Http\Response as IlluminateResponse;
use Task\Task;
use Task\TaskDue;

class TaskController extends AstroBaseController {

  private $user = null;

  public function __construct() {
    $this->user = Auth::user();
  }

  public function get() {
    $tasks = Task::where('user_id', $this->user->id)->get();

    return $this->successResponse([
      'tasks' => $this->transformCollection($tasks),
      'page_count' => 0,
      'total' => count($tasks)
    ]);
  }

  public function post($task_id) {
    $task = Task::whereId($task_id)->firstOrFail();
  }

  public function delete($task_due_id) {
    $task = TaskDue::where('id', $task_due_id)->firstOrFail();
    if($task->task->user_id == $this->user->id) {
      $task->task->delete();
      return $this->successResponse();
    } else {
      $this->errorResponse(
        "You are not allowed to delete someone else's task.",
        IlluminateResponse::HTTP_FORBIDDEN);
    }
  }

  public function transform($task) {
    return [
      'id' => $task['id'],
      'parent_task_id' => $task['parent_task_id'],
      'project_id' => $task['project_id'],
      'name' => $task['name'],
      'frequency' => $task['frequency']
    ];
  }

}
