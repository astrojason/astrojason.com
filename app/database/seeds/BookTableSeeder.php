<?php

class BookTableSeeder extends Seeder
{



//* @property string $series
//* @property integer $series_order

  public function run()
  {
    $this->command->info('Creating faker object.');
    $faker = Faker\Factory::create();

    $this->command->info('Truncating Book table');
    Book::truncate();

    $this->command->info('Inserting sample records using Faker ...');

    foreach(range(1, 200) as $index) {
      Book::create([
        'user_id' => 1,
        'title' => $faker->sentence(3),
        'author_fname' => $faker->firstName,
        'author_lname' => $faker->lastName,
        'category' => $faker->boolean() ? 'To Read' : $faker->monthName(),
        'is_read' => $faker->boolean(),
        'owned' => $faker->boolean(),
        'times_loaded' => $faker->numberBetween(0, 50)
      ]);
    }

//    Create some series books
    foreach(range(1, 30) as $index) {
      $first_name = $faker->firstName;
      $last_name = $faker->lastName;
      $category = 'To Read';
      $series = $faker->colorName();
      foreach(range(1,15) as $series_order) {
        Book::create([
          'user_id' => 1,
          'title' => $faker->sentence(3),
          'author_fname' => $first_name,
          'author_lname' => $last_name,
          'category' => $category,
          'series' => $series,
          'series_order' => $series_order,
          'is_read' => $faker->boolean(),
          'owned' => $faker->boolean(),
          'times_loaded' => $faker->numberBetween(0, 50)
        ]);
      }
    }
  }
}