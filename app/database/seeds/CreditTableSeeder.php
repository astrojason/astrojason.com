<?php

class AccountTableSeeder extends Seeder
{

  public function run()
  {
    $this->command->info('Creating faker object.');
    $faker = Faker\Factory::create();

    $this->command->info('Truncating Account and Balance tables');
    Account::truncate();
    Balance::truncate();

    $this->command->info('Inserting sample records using Faker ...');

    foreach(range(1,15) as $index) {
      Account::create([
        'user_id' => 1,
        'name' => $faker->word(),
        'limit' => $faker->numberBetween(200,5000)
      ]);
    };

    $this->command->info('Populating a years worth of balances ...');
    $accounts = Account::where('user_id', 1)->get();
    foreach ($accounts as $account) {
      foreach (['01','02','03','04','05','06','07','08','09','10','11','12'] as $month) {
        Balance::create([
          'account_id' => $account->id,
          'amount' => $faker->numberBetween(0, $account->limit),
          'created_at' => \Carbon\Carbon::createFromFormat('m/d/Y', "$month/01/2016")
        ]);
      }
    }
  }
}