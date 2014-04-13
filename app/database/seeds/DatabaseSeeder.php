<?php

class DatabaseSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Eloquent::unguard();
    $this->call('UserTableSeeder');

    $this->command->info('User table seeded!');
  }

}

class UserTableSeeder extends Seeder {

  public function run()
  {
    DB::table('users')->delete();

    User::create(array(
      'name'     => 'Jason Sylvester',
      'username' => 'astrojason',
      'email'    => 'jason@astrojason.com',
      'password' => Hash::make('awesome'),
    ));
  }

}