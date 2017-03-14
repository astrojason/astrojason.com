<?php

use Articles\Article;
use Articles\Category;
use Articles\Read;
use Articles\Recommended;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MigrateArticles extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'articles:migrate';

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
      Article::truncate();
      Category::truncate();
      Read::truncate();
      Recommended::truncate();
      $links = Link::all();
      $this->info('Parsing the links');
      foreach($links as $link) {
        /** @var Article $article */
        $article = new Article();
        $article->title = $link->name;
        $article->url = $link->link;
        $article->user_id = $link->user_id;
        $article->save();
        if($link->category != 'Unread' && $link->category != '') {
          $category = Category::where('name', $link->category)->first();
          if(!isset($category)) {
            $category = Category::create([
              'name' => $link->category,
              'user_id' => $link->user_id
            ]);
          }
          $article->categories()->attach($category);
        }
        /** @var \Carbon\Carbon $dt */
        $dt = $link->updated_at;
        $dt->subMonth();
        if($link->is_read) {
          $read = new Read();
          $read->article_id = $article->id;
          $read->setUpdatedAt($dt);
          $read->setCreatedAt($dt);
          $read->save();
        }
        if($link->times_loaded > 0) {
          for($i = 0; $i < $link->times_loaded; $i++) {
            $loaded_date = $dt->subDay();
            $recommended = new Recommended();
            $recommended->user_id = $link->user_id;
            $recommended->article_id = $article->id;
            $recommended->setUpdatedAt($loaded_date);
            $recommended->setCreatedAt($loaded_date);
            $recommended->save();
          }
        }

      }
      $this->info('Links migrated to Articles');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments() {
		return [];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions() {
		return [];
	}

}
