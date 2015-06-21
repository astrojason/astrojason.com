<?php

class SongTableSeeder extends Seeder
{

  public function run()
  {
    $this->command->info('Creating faker object.');
    $faker = Faker\Factory::create();

    $this->command->info('Truncating Song table');
    Song::truncate();

    $this->command->info('Inserting sample records using Faker ...');

    foreach(range(1,100) as $index) {
      Song::create([
        'user_id' => 1,
        'title' => ucwords(implode(' ', $faker->words(3))),
        'artist' => ucwords(implode(' ', $faker->words(2))),
        'location' => implode(' ', $faker->words(1)),
        'learned' => $faker->boolean()
      ]);
    }
  }
}