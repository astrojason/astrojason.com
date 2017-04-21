<?php

class UserTableSeeder extends Seeder
{

  public function run()
  {
    $this->command->info('Creating faker object.');
    $faker = Faker\Factory::create();

    $this->command->info('Truncating User table');
    User::truncate();

    $this->command->info('Creating test users ...');

    User::create([
      'username' => 'primaryuser',
      'firstname' => 'Primary',
      'lastname' => 'User',
      'email' => 'primary@testuser.com',
      'password' => Hash::make('a')
    ]);

    User::create([
      'username' => 'testuser',
      'firstname' => 'Test',
      'lastname' => 'User',
      'email' => 'test@testuser.com',
      'password' => Hash::make('a')
    ]);

    $this->command->info('Inserting sample records using Faker ...');

    foreach(range(1,20) as $index) {
      User::create([
        'username' => $faker->userName,
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'email' => $faker->email,
        'password' => Hash::make($faker->password)
      ]);
    }
  }
}