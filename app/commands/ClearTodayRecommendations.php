<?php

use Article\Recommended;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ClearTodayRecommendations extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'articles:cleartoday';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description.';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
      parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function fire() {
    $this->info("Clearing today's recommendations");
    $today = Carbon::create();
    $yesterday = Carbon::create()->subDay(1);
    Recommended::where('created_at', 'LIKE', $today->toDateString() . '%')
      ->delete();
    Recommended::where('created_at', 'LIKE', $yesterday->toDateString() . '%')
      ->delete();
  }

  /**
   * Get the console command arguments.
   *
   * @return array
   */
  protected function getArguments()
  {
      return [];
  }

  /**
   * Get the console command options.
   *
   * @return array
   */
  protected function getOptions()
  {
      return [];
  }

}
