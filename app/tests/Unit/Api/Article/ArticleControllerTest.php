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
 * Date: 3/12/17
 * Time: 11:11 AM
 */

namespace Unit\Api\Article;

use Api\Article\ArticleController;
use Article\Article;
use Article\Category;
use Article\Recommended;
use Artisan;
use Carbon\Carbon;
use Faker\Factory;
use TestCase;

class ArticleControllerTest extends TestCase {
  /** @var  ArticleController $articleController */
  private $articleController;

  /** @var  Article */
  private $article;

  private $faker;

  public function setUp() {
    parent::setUp();
    $this->articleController = new ArticleController();
    $this->article = Article::where('user_id', $this->defaultUserId)->first();
    $this->faker = Factory::create();
  }

  public function test_check_new_article() {
    $addArticle = $this->articleController->checkIfArticleExists($this->defaultUserId, $this->faker->url);
    $this->assertFalse(isset($addArticle));
  }

  public function test_check_existing_article() {
    $addedArticle = $this->articleController->checkIfArticleExists($this->defaultUserId, $this->article->url);
    $this->assertTrue(isset($addedArticle));
  }

  public function test_add_article_new() {
    $params = [
      'title' => ucwords(implode(' ', $this->faker->words(3))),
      'url' => $this->faker->url,
      'categories' => []
    ];
    $article = $this->articleController->add($this->defaultUserId, $params);
    $this->assertTrue($article->justAdded);
  }

  public function test_add_article_existing() {
    $params = [
      'title' => $this->article->title,
      'url' => $this->article->url,
      'categories' => []
    ];
    $article = $this->articleController->add($this->defaultUserId, $params);
    $this->assertFalse($article->justAdded);
  }

  public function test_add_article_new_with_single_existing_category() {
    $category = Category::where('user_id', $this->defaultUserId)->first();
    $params = [
      'title' => ucwords(implode(' ', $this->faker->words(3))),
      'url' => $this->faker->url,
      'categories' => [
        [
          'id' => $category->id
        ]
      ]
    ];
    /** @var Article $article */
    $article = $this->articleController->add($this->defaultUserId, $params);
    $this->assertEquals($article->categories[0]->id, $category->id);
  }

  public function test_add_article_new_with_multiple_existing_categories() {
    $categories = Category::where('user_id', $this->defaultUserId)->take(2)->get();
    $params = [
      'title' => ucwords(implode(' ', $this->faker->words(3))),
      'url' => $this->faker->url,
      'categories' => [
        [
          'id' => $categories[0]->id
        ],
        [
          'id' => $categories[1]->id
        ]
      ]
    ];
    /** @var Article $article */
    $article = $this->articleController->add($this->defaultUserId, $params);
    $this->assertEquals($article->categories[0]->id, $categories[0]->id);
    $this->assertEquals($article->categories[1]->id, $categories[1]->id);
  }

  public function test_add_article_new_with_single_new_category() {
    $categoryName = 'My Shiny New Category';
    $params = [
      'title' => ucwords(implode(' ', $this->faker->words(3))),
      'url' => $this->faker->url,
      'categories' => [
        [
          'id' => null,
          'name' => $categoryName
        ]
      ]
    ];
    /** @var Article $article */
    $article = $this->articleController->add($this->defaultUserId, $params);
    $this->assertEquals($article->categories[0]->name, $categoryName);
  }

  public function test_save_article() {
    $article = Article::where('user_id', $this->defaultUserId)->first();
    $articleTitle = 'This is my updated category name';
    $params = [
      'title' => $articleTitle,
      'url' => $article->url,
      'categories' => $article->categories
    ];
    $article = $this->articleController->save($this->defaultUserId, $article, $params);
    $this->assertEquals($articleTitle, $article->title);
  }

  public function test_save_article_add_category() {
    $article = Article::where('user_id', $this->defaultUserId)->first();
    $articleCategory = Category::where('user_id', $this->defaultUserId)->first();
    $params = [
      'title' => $article->title,
      'url' => $article->url,
      'categories' => [
        [
          'id' => $articleCategory->id
        ]
      ]
    ];
    $article = $this->articleController->save($this->defaultUserId, $article, $params);
    $this->assertEquals($articleCategory->id, $article->categories[0]->id);
  }

  public function test_save_article_remove_category(){
    $article = Article::where('user_id', $this->defaultUserId)->first();
    $params = [
      'title' => $article->title,
      'url' => $article->url,
      'categories' => []
    ];
    $article = $this->articleController->save($this->defaultUserId, $article, $params);
    $this->assertEquals(0, count($article->categories));
  }

  public function test_save_article_remove_one_category() {
    $article = Article::where('user_id', $this->defaultUserId)->first();
    $categories = Category::where('user_id', $this->defaultUserId)->take(3)->get();
    $article->categories()->detach();
    $article->save();
    foreach ($categories as $category) {
      $article->categories()->attach($category);
    }
    $article->save();
    $article = Article::where('user_id', $this->defaultUserId)->first();
    $params = [
      'title' => $article->title,
      'url' => $article->url,
      'categories' => [
        [
          'id' => $categories[0]->id
        ],
        [
          'id' => $categories[2]->id
        ]
      ]
    ];
    $article = $this->articleController->save($this->defaultUserId, $article, $params);
    $this->assertEquals(2, count($article->categories));
  }

  public function test_create_today_links() {
    $this->articleController->generateDailyLinks($this->defaultUserId);
    $today = Carbon::create();
    $daily_articles = Recommended::where('created_at', 'LIKE', $today->toDateString() . '%')->get();
    $this->assertFalse(count($daily_articles) == 0);
  }

  public function test_clear_today_links() {
    $this->articleController->generateDailyLinks($this->defaultUserId);
    Artisan::call('articles:clearrecentrecommendations');
    $today = Carbon::create();
    $daily_articles = Recommended::where('created_at', 'LIKE', $today->toDateString() . '%')->get();
    $this->assertEquals(0, count($daily_articles));
  }

  public function test_clear_yesterday_links() {
    $yesterday = Carbon::create()->subDay(1);
    $recommended = Recommended::create([
      'article_id' => $this->article->id,
      'user_id' => $this->defaultUserId
    ]);
    $recommended->setUpdatedAt($yesterday);
    $recommended->setCreatedAt($yesterday);
    $recommended->save();
    Artisan::call('articles:clearrecentrecommendations');
    $yesterday_articles = Recommended::where('created_at', 'LIKE', $yesterday->toDateString() . '%')->get();
    $this->assertEquals(0, count($yesterday_articles));
  }

  public function test_return_today_links_already_created() {
    Artisan::call('articles:clearrecentrecommendations');
    $initialTodayLinks = $this->articleController->generateDailyLinks($this->defaultUserId);
    usort($initialTodayLinks, function($a, $b) {
      return strcmp($a['title'], $b['title']);
    });
    $secondTodayLinks = $this->articleController->generateDailyLinks($this->defaultUserId);
    usort($secondTodayLinks, function($a, $b) {
      return strcmp($a['title'], $b['title']);
    });
    $this->assertEquals($initialTodayLinks, $secondTodayLinks);
  }
}