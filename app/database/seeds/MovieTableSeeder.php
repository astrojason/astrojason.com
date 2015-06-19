<?php

class MovieTableSeeder extends Seeder
{

  public function run()
  {
    $this->command->info('Creating faker object.');
    $faker = Faker\Factory::create();

    $this->command->info('Truncating Movie table');
    Movie::truncate();

    $this->command->info('Inserting sample records using Faker ...');

    foreach(range(1,200) as $index) {
      Movie::create([
        'user_id' => 1,
        'title' => $faker->title(),
        'times_watched' => $faker->numberBetween(1, 10),
        'rating_order' => $index,
        'is_watched' => $faker->boolean()
      ]);
    }
  }
}