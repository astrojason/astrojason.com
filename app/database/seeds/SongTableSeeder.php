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
        'title' => $faker->sentence(3),
        'artist' => $faker->sentence(2),
        'location' => $faker->sentence(1),
        'learned' => $faker->boolean()
      ]);
    }
  }
}