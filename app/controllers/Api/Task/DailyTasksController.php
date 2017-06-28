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
use Api\Task\TaskController;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Response as IlluminateResponse;
use Task\Task;
use Task\TaskDue;
use Task\TaskProject;

class DailyTasksController extends AstroBaseController {

  private $user = null;
  private $today = null;

  public function __construct() {
    $this->user = Auth::user();
    $this->today = Carbon::create();
  }

  public function get() {
    // Get the user's projects
    $projects = TaskProject::where('user_id', $this->user->id)->get();
    $this->generateTodaysProjectTasks($projects);
    $this->generateTodaysNonProjectTasks();

    // Get the top level tasks
    $tasks = TaskDue::with('Task')
      ->where('completed', false)
      ->where('due', '>=', $this->today->toDateString() . "%")
      ->whereHas('Task', function($query) {
        $query->where('user_id', $this->user->id)
          ->where('parent_task_id', 0);
      })
      ->get();

    return $this->successResponse([
      'projects' => $this->transformCollection($projects),
      'tasks' => $this->transformTasks($tasks)
    ]);
  }

  public function transform($item) {
    return [
      'id' => $item['id'],
      'name' => $item['name'],
      'tasks' => $this->getProjectDailyTasks($item['id'])
    ];
  }

  private function getProjectDailyTasks($projectId) {
    $todaysTasks = TaskDue::with('Task')
      ->whereHas('Task', function($query) use ($projectId) {
        $query->where('project_id', $projectId);
      })
      ->where('completed', false)
      ->where('due', '>=', $this->today->toDateString() . "%")
      ->get();
    return $this->transformTasks($todaysTasks);
  }

  private function transformTasks($tasks) {
    return array_map([$this, 'transformTask'], $tasks->toArray());
  }

  private function transformTask($task) {
    $taskController = new TaskController();
    $dueDate = Carbon::parse($task['due']);
    $transformedTask = $taskController->transform($task['task']);
    $transformedTask['tasks'] = $this->getSubTasks($task);
    return [
      'id' => $task['id'],
      'due' => $dueDate->toW3cString(),
      'overdue' => $dueDate < $this->today,
      'completed' => $task['completed'],
      'task' => $transformedTask
    ];
  }

  private function getSubTasks($task) {
    $tasks = TaskDue::with('Task')
      ->where('due', $task['due'])
      ->whereHas('Task', function($query) use ($task){
        $query->where('parent_task_id', $task['task']['id']);
      })
      ->get();
    return $this->transformTasks($tasks);
  }

  private function generateTodaysProjectTasks($projects) {
    // Generate the tasks for today
    foreach ($projects as $project) {
      // Get the top level tasks that are due daily
      $projectDailyTasks = Task::where('project_id', $project->id)
        ->where('frequency', 'daily')
        ->where('parent_task_id', 0)
        ->get();
      $this->createDailyTaskDues($projectDailyTasks);
    }
  }

  private function generateTodaysNonProjectTasks() {
    $tasks = Task::where('user_id', $this->user->id)
      ->where('project_id', 0)
      ->get();
    $this->createDailyTaskDues($tasks);
  }

  private function generateDailySubTasks($task) {
    $subTaskQuery = Task::where('parent_task_id', $task->id)
      ->where('frequency', 'daily');
    // if($task->cycle_subtasks) {
      // TODO: Figure out how to do this
    // }
    if($task->subtasks_to_show > 0){
      $subTaskQuery->take($task->subtasks_to_show);
    }
    $tasks = $subTaskQuery->get();
    $this->createDailyTaskDues($tasks);
  }

  // create the entry in the TaskDue table for the passed tasks
  public function createDailyTaskDues($tasks) {
    foreach ($tasks as $task) {
      $taskDue = TaskDue::firstOrNew([
        'due' => $this->today->setTime(23, 59, 59),
        'task_id' => $task->id,
      ]);
      $taskDue->save();
      $this->generateDailySubTasks($task);
    }
  }

}
