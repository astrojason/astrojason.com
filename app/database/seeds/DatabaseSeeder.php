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

    $this->call('LinkTableSeeder');
		$this->call('SongTableSeeder');
		$this->call('BookTableSeeder');
		$this->call('GameTableSeeder');
		$this->call('MovieTableSeeder');
	}

}