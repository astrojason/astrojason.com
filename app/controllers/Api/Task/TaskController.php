<?php

/**
 *         _----_    _________       /\
 *        /      \  /         \/\ __///
 *       (        \/          / > /   \
 *        \        |      --/_>_/    /
 *          \_ ____|          \ /\ _/
 *            /               ///        __\
 *           (               // \       /  \\
 *            \      \     ///    \    /    \\
 *             (      \   //       \  /\  _  \\
 *              \   ___|///    _    \/  \/ \__)\
 *               ( / _ //\    ( \       /
 *                /_ /// /     \ \ _   /
 *                (__)  ) \_    \   --~
 *                ///--/    \____\
 *               //        __)    \
 *             ///        (________)
 *  _________///          ===========
 * //|_____|///
 *
 * Created by PhpStorm.
 * User: jsylvester
 * Date: 6/12/17
 * Time: 12:00 PM
 */
namespace Api\Task;

use Api\AstroBaseController;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Response as IlluminateResponse;
use Task\Task;
use Task\TaskDue;

class TaskController extends AstroBaseController {

  private $user = null;
  private $today = null;

  public function __construct() {
    $this->user = Auth::user();
    $this->today = Carbon::create();
  }

  public function daily() {
    $dailyTasks = $this->generateDailyTasks(new Task());
    foreach ($dailyTasks as $dailyTask) {
      $exists = TaskDue::where('task_id', $dailyTask)
        ->where('due', '>=', $this->today->toDateString() . "%")
        ->get();
      if(count($exists) == 0){
        TaskDue::create([
          'due' => $this->today->setTime(23, 59, 59),
          'task_id' => $dailyTask,
        ]);
      }
    }

    $todayTasks = TaskDue::with('Task')
      ->where('due', '>=', $this->today->toDateString() . "%")
      ->where('completed', false)
      ->where('skipped', false)
      ->whereHas('Task', function($query) {
        $query->where('user_id', $this->user->id)
        ->where('parent_task_id', 0);
      })
      ->get();

    $processedTasks = $this->transformCollection($todayTasks);

    $existingTaskIds = array_map(function($task){
      return $task['task_id'];
    }, $processedTasks);

    $overDueTasks = TaskDue::with('Task')
      ->where('due', '<', $this->today->toDateString() . "%")
      ->where('completed', false)
      ->where('skipped', false)
      ->whereHas('Task', function($query) use ($existingTaskIds) {
        $query->where('user_id', $this->user->id)
        ->whereNotIn('id', $existingTaskIds)
        ->where('parent_task_id', 0);
      })
      ->get();

    return $this->successResponse(
      [
      'tasks' => array_merge(
        $processedTasks,
        $this->transformCollection($overDueTasks))
      ]
    );
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

  public function transform($item) {
    $dueDate = Carbon::parse($item['due']);
    $subTasksQuery = TaskDue::with('Task')
      ->where('due', $item['due'])
      ->whereHas('Task', function($query) use ($item){
        $query->where('parent_task_id', $item['task']['id']);
      });
    if($item['task']['subtasks_to_show']){
      $subTasksQuery->take($item['task']['subtasks_to_show']);
    }

    $subTasks = $subTasksQuery->get();

    $returnItem = [
      'id' => $item['id'],
      'due' => $item['due'],
      'time_remaining' => $dueDate->diffForHumans($this->today, true),
      'overdue' => $dueDate < $this->today,
      'task_id' => $item['task']['id'],
      'parent_task_id' => $item['task']['parent_task_id'],
      'name' => $item['task']['name'],
      'hasSubTasks' => count($subTasks) > 0,
      'subTasks' => $this->transformCollection($subTasks),
      'completed' => $item['completed']
    ];
    return $returnItem;
  }

  private function generateDailyTasks(Task $parentTask) {
    $query = Task::where('user_id', $this->user->id)
      ->where('frequency', 'daily');
    if($parentTask->id) {
      $query->where('parent_task_id', $parentTask->id);
    } else {
      $query->where('parent_task_id', 0);
    }
    if($parentTask->cycle_subtasks) {
      // TODO: Filter out recently completed subtasks
      $query->orderBy(DB::raw('RAND()'));
    }
    if($parentTask->subtasks_to_show > 0){
      $query->take($parentTask->subtasks_to_show);
    }
    $tasks = $query->get();

    $returnTaskIds = [];
    foreach ($tasks as $task) {
      $returnTaskIds[] = $task->id;
      $returnTaskIds = array_merge(
        $returnTaskIds,
        $this->generateDailyTasks($task)
      );
    }
    return $returnTaskIds;
  }

}
