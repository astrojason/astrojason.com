<?php

class UserTableSeeder extends Seeder
{

  public function run()
  {
    $this->command->info('Creating faker object.');
    $faker = Faker\Factory::create();

    $this->command->info('Truncating User table');
    User::truncate();

    $this->command->info('Inserting sample records using Faker ...');

    User::create([
      'username' => 'astrojason',
      'firstname' => 'Jason',
      'lastname' => 'Sylvester',
      'email' => 'jason@astrojason.com',
      'password' => Hash::make('a')
    ]);

    User::create([
      'username' => 'testuser',
      'firstname' => 'Test',
      'lastname' => 'User',
      'email' => 'test@astrojason.com',
      'password' => Hash::make('a')
    ]);

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