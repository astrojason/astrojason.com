<?php

use Task\Task;
use Task\TaskProject;
use Task\TaskDue;

class TaskTablesSeeder extends Seeder
{

  public function run()
  {
    $this->command->info('Creating faker object.');
    $faker = Faker\Factory::create();

    $this->command->info('Truncating Task Due table');
    TaskDue::truncate();

    $this->command->info('Truncating Task table');
    Task::truncate();

    $this->command->info('Truncating Task Project table');
    TaskProject::truncate();

    $this->command->info('Inserting sample records using Faker ...');

    foreach(range(1,20) as $index) {
      TaskProject::create([
        'user_id' => 1,
        'name' => ucwords(implode(' ', $faker->words(3))),
      ]);
    }

    $projects = TaskProject::where('user_id', 1)->get();
    foreach ($projects as $project) {
      $numTasks = $faker->numberBetween(1, 50);
      foreach(range(1, $numTasks) as $index) {
        Task::create([
          'user_id' => 1,
          'name' => ucwords(implode(' ', $faker->words(3))),
          'project_id' => $project->id
        ]);
      }
    }

    $dailyTasks = Task::where('user_id', 1)->orderBy(DB::raw('RAND()'))->take(20)->get();
    foreach ($dailyTasks as $dailyTask) {
      $dailyTask->frequency = 'daily';
      $dailyTask->save();
    }

    $subTasks = Task::where('user_id', 1)->orderBy(DB::raw('RAND()'))->take(20)->get();
    foreach ($subTasks as $subTask) {
      $parentTask = Task::whereId($faker->numberBetween(1, 50))->first();
      $parentTask->frequency = 'daily';
      $subTask->parent_task_id = $parentTask->id;
      $subTask->project_id = $parentTask->project_id;
      $subTask->frequency = 'daily';
      $subTask->save();
    }
  }
}
