<?php

class GameTableSeeder extends Seeder
{

  public function run()
  {
    $this->command->info('Creating faker object.');
    $faker = Faker\Factory::create();

    $this->command->info('Truncating Game table');
    Game::truncate();

    $this->command->info('Inserting sample records using Faker ...');

    foreach(range(1,200) as $index) {
      Game::create([
        'user_id' => 1,
        'title' => ucwords(implode(' ', $faker->words(2))),
        'platform' => $faker->monthName(),
        'completed' => $faker->boolean()
      ]);
    }
  }
}