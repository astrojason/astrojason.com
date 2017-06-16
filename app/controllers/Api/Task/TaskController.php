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
use Task\Task;
use Task\TaskDue;

class TaskController extends AstroBaseController {

  public function daily() {
    $today = Carbon::create()->setTime(23, 59, 59);
    $user = Auth::user();

    $dailyTasks = Task::where('frequency', 'daily')
      ->get();

    foreach ($dailyTasks as $dailyTask) {
      $exists = TaskDue::where('task_id', $dailyTask->id)
        ->where('due', '>=', $today->toDateString() . "%")
        ->get();
      if(count($exists) == 0){
        TaskDue::create([
          'due' => $today,
          'task_id' => $dailyTask->id,
        ]);
      }
    }

    $todayTasks = TaskDue::with('Task')
      ->where('due', '>=', $today->toDateString() . "%")
      ->where('completed', false)
      ->whereHas('Task', function($query) use ($user) {
        $query->where('user_id', $user->id)
        ->where('parent_task_id', 0);
      })
      ->get();

    return $this->successResponse(
      ['tasks' => $this->transformCollection($todayTasks)]
    );
  }

  public function transform($item) {
    $today = Carbon::create();
    $dueDate = Carbon::parse($item['due']);
    $subTasksQuery = TaskDue::with('Task')
      ->whereHas('Task', function($query) use ($item){
        $query->where('id', $item['task']['id']);
      });
    if($item['task']['subtasks_to_show']){
      $subTasksQuery->take($item['task']['subtasks_to_show']);
    }
    $returnItem = [
      'id' => $item['id'],
      'days_remaining' => $dueDate->diffForHumans($today, true),
      'task_id' => $item['task']['id'],
      'name' => $item['task']['name'],
      'subTasks' => []
    ];
    return $returnItem;
  }

}