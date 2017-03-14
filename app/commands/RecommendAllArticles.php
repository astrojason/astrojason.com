<?php

use Article\Article;
use Article\Recommended;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RecommendAllArticles extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'articles:recommend';

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
    $this->info("Setting every article to have been recommended 8 days ago");
    $articles = Article::all();
    foreach ($articles as $article) {
      $dt = Carbon::create();
      $dt->subDay(8);
      $recommended = Recommended::create([
        'user_id' => $article->user_id,
        'article_id' => $article->id
      ]);
      $recommended->setCreatedAt($dt);
      $recommended->setUpdatedAt($dt);
      $recommended->save();
    }
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
