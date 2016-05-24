<?php

class LinkTableSeeder extends Seeder {

  public function run() {
    $this->command->info('Creating faker object.');
    $faker = Faker\Factory::create();

    $this->command->info('Truncating Link table');
    Link::truncate();

    $this->command->info('Inserting sample records using Faker ...');

    foreach(range(1,1000) as $index) {
      Link::create([
        'user_id' => 1,
        'name' => ucwords(implode(' ', $faker->words(3))),
        'link' => $faker->url(),
        'category' => $faker->boolean() ? 'Unread' : $faker->monthName(),
        'is_read' => $faker->boolean(),
        'times_loaded' => $faker->numberBetween(0, 50),
        'times_read' => $faker->numberBetween(0, 10)
      ]);
    }
    $dailies = Link::where('is_read', false)->orderBy(DB::raw('RAND()'))->take(10)->get();
    /** @var Link $daily */
    foreach($dailies as $daily){
      $daily->category = 'Daily';
      $daily->save();
    }
    $todos = Link::where('is_read', false)->where('Category', '<>', 'Daily')->orderBy(DB::raw('RAND()'))->take(10)->get();
    /** @var Link $daily */
    foreach($todos as $todo){
      $todo->category = 'ToDo';
      $todo->save();
    }

    $this->command->info('Truncating DashboardCategory table');
    DashboardCategory::truncate();


    $this->command->info('Creating DashboardCategory entries');
    DashboardCategory::create([
      'user_id' => 1,
      'category' => 'ToDo',
      'randomize' => false,
      'num_items' => 0,
      'track' => false,
      'position' => 1
    ]);

    DashboardCategory::create([
      'user_id' => 1,
      'category' => 'Daily',
      'randomize' => false,
      'num_items' => 0,
      'track' => false,
      'position' => 2
    ]);

    DashboardCategory::create([
      'user_id' => 1,
      'category' => 'Unread',
      'randomize' => true,
      'num_items' => 20,
      'track' => true,
      'position' => 3
    ]);
  }
}