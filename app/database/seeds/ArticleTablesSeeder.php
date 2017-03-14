<?php

/**
 *         _----_    _________       /\
 *        /      \  /         \/\ __///
 *       (        \/          / > /   \
 *        \        |      --/_>_/    /
 *          \_ ____|          \ /\ _/
 *            /               ///        __\
 *           (               // \       /  \\
 *            \      \     ///    \    /    \\
 *             (      \   //       \  /\  _  \\
 *              \   ___|///    _    \/  \/ \__)\
 *               ( / _ //\    ( \       /
 *                /_ /// /     \ \ _   /
 *                (__)  ) \_    \   --~
 *                ///--/    \____\
 *               //        __)    \
 *             ///        (________)
 *  _________///          ===========
 * //|_____|///
 *
 * Created by PhpStorm.
 * User: jsylvester
 * Date: 3/14/17
 * Time: 9:57 AM
 */

use Articles\Article;
use Articles\Category;
use Articles\DailySetting;
use Articles\Read;
use Articles\Recommended;

class ArticleTablesSeeder extends Seeder {
  public function run() {
    $this->command->info('Creating faker object.');
    $faker = Faker\Factory::create();

    $this->command->info('Truncating Article tables');
    Article::truncate();
    Category::truncate();
    DailySetting::truncate();
    Read::truncate();
    Recommended::truncate();

    $this->command->info('Inserting sample articles using Faker ...');

    foreach(range(1,1000) as $index) {
      Article::create([
        'user_id' => 1,
        'title' => ucwords(implode(' ', $faker->words(3))),
        'url' => $faker->url()
      ]);
    }

    $this->command->info('Inserting sample categories using Faker ...');

    foreach(range(1,30) as $index) {
      Category::create([
        'user_id' => 1,
        'name' => ucwords(implode(' ', $faker->words(3)))
      ]);
    }
    $this->command->info('Randomly assign categories to articles ...');

    $categories = Category::where('user_id', 1)
      ->orderBy(DB::raw('RAND()'))->take(10)->get();
    foreach ($categories as $category) {
      $articles = Article::where('user_id', 1)
        ->whereHas('categories', function ($query) use ($category) {
          $query->where('category_id', '<>', $category->id);
        })
        ->orderBy(DB::raw('RAND()'))->take(10)->get();
      /** @var Article $article */
      foreach ($articles as $article) {
        $article->categories()->attach($category);
      }
    }

    $this->command->info('Randomly assign recommended dates to articles ...');

    $articles = Article::where('user_id', 1)
      ->orderBy(DB::raw('RAND()'))->take(100)->get();

    foreach ($articles as $article) {
      $numberTimes = $faker->numberBetween(1, 10);
      foreach(range(1,$numberTimes) as $index) {
        $recommended = Recommended::create([
          'user_id' => 1,
          'article_id' => $article->id
        ]);
        $date = $faker->dateTimeThisYear();
        $recommended->setCreatedAt($date);
        $recommended->setUpdatedAt($date);
        $recommended->save();
      }
    }

    $this->command->info('Randomly assign read dates to articles ...');

    $articles = Article::where('user_id', 1)
      ->orderBy(DB::raw('RAND()'))->take(100)->get();
    foreach ($articles as $article) {
      $numberTimes = $faker->numberBetween(1, 10);
      foreach(range(1,$numberTimes) as $index) {
        $read = Read::create([
          'article_id' => $article->id
        ]);
        $date = $faker->dateTimeThisYear();
        $read->setCreatedAt($date);
        $read->setUpdatedAt($date);
        $read->save();
      }
    }

    $this->command->info('Randomly create some daily settings ...');
    $categories = Category::where('user_id', 1)
      ->orderBy(DB::raw('RAND()'))->take(10)->get();
    DailySetting::create([
      'user_id' => 1,
      'number' => $faker->numberBetween(1, 5)
    ]);
    foreach ($categories as $category) {
      DailySetting::create([
        'user_id' => 1,
        'number' => $faker->numberBetween(1, 5),
        'category_id' => $category->id
      ]);
    }
  }
}