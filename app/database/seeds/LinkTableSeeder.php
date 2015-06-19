<?php

class LinkTableSeeder extends Seeder
{

  public function run()
  {
    $this->command->info('Creating faker object.');
    $faker = Faker\Factory::create();

    $this->command->info('Truncating Link table');
    Link::truncate();

    $this->command->info('Inserting sample records using Faker ...');

    foreach(range(1,1000) as $index) {
      Link::create([
        'user_id' => 1,
        'name' => $faker->sentence(3),
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
  }
}