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
 * Time: 11:52 AM
 */

namespace Integration\Api\Article;

use Article\Article;
use Article\Recommended;
use Artisan;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as IlluminateResponse;
use TestCase;

class ArticleControllerTest extends TestCase {

  private $faker;

  private $article;

  public function setUp() {
    parent::setUp();

    $this->client->setServerParameter('HTTP_X-Requested-With', 'XMLHttpRequest');
    $this->client->setServerParameter('HTTP_CONTENT_TYPE', 'application/json');
    $this->client->setServerParameter('HTTP_ACCEPT', 'application/json');

    $this->faker = Factory::create();
    $this->article = Article::where('user_id', $this->defaultUserId)->first();
  }

  public function test_get_articles() {
    $this->mockUser($this->defaultUserId);
    $articles = Article::where('user_id', $this->defaultUserId)->has('read', '<', 1)->get();

    /** @var JsonResponse $response */
    $response = $this->call('GET', "/api/article/");
    $this->assertEquals(count($articles), count($response->getData(true)['articles']));
  }

  public function test_get_articles_with_limit() {
    $this->mockUser($this->defaultUserId);

    /** @var JsonResponse $response */
    $response = $this->call('GET', "/api/article?page_size=10");
    $this->assertEquals(10, count($response->getData(true)['articles']));
  }

  public function test_add_article_new() {
    $this->mockUser(2);

    $articleToSave = [
      'title' => ucwords(implode(' ', $this->faker->words(3))),
      'url' => $this->faker->url,
      'categories' => []
    ];

    /** @var JsonResponse $response */
    $response = $this->call('PUT', "/api/article/", $articleToSave);
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
  }

  public function test_add_article_existing_response() {
    $this->mockUser($this->defaultUserId);

    $articleToSave = [
      'title' => $this->article->title,
      'url' => $this->article->url,
      'categories' => []
    ];

    /** @var JsonResponse $response */
    $response = $this->call('PUT', "/api/article/", $articleToSave);
    $this->assertEquals(IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
  }

  public function test_add_article_existing_id() {
    $this->mockUser($this->defaultUserId);

    $articleToSave = [
      'title' => $this->article->title,
      'url' => $this->article->url,
      'categories' => []
    ];

    /** @var JsonResponse $response */
    $response = $this->call('PUT', "/api/article/", $articleToSave);

    $this->assertEquals($response->getData(true)['id'], $this->article->id);
  }

  public function test_save_article_user() {
    $this->mockUser($this->defaultUserId);
    $articleId = $this->article->id;

    $articleToSave = [
      'title' => $this->article->title,
      'url' => $this->article->url,
      'categories' => []
    ];

    /** @var JsonResponse $response */
    $response = $this->call('POST', "/api/article/$articleId", $articleToSave);
    $this->assertEquals($response->getData(true)['article']['id'], $this->article->id);
  }


  public function test_save_article_not_user() {
    $this->mockUser(2);
    $articleId = $this->article->id;

    $articleToSave = [
      'title' => $this->article->title,
      'url' => $this->article->url,
      'categories' => []
    ];

    /** @var JsonResponse $response */
    $response = $this->call('POST', "/api/article/$articleId", $articleToSave);
    $this->assertEquals(IlluminateResponse::HTTP_FORBIDDEN, $response->getStatusCode());
  }

  public function test_get_daily_articles() {
    $this->mockUser($this->defaultUserId);
    Artisan::call('articles:clearrecentrecommendations');

    /** @var JsonResponse $response */
    $response = $this->call('GET', "/api/article/daily");
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
  }

  public function test_get_daily_with_postponed() {
    $this->mockUser($this->defaultUserId);
    Artisan::call('articles:clearrecentrecommendations');

    /** @var JsonResponse $response */
    $response = $this->call('GET', "/api/article/daily");
    $normalCount = count($response->getData(true)['articles']);

    Artisan::call('articles:clearrecentrecommendations');
    $recommended = Recommended::where('user_id', $this->defaultUserId)
      ->where('created_at', 'NOT LIKE', Carbon::create()->toDateString() . '%')
      ->first();
    $yesterday = Carbon::create()->subDay(1);
    $recommended->setCreatedAt($yesterday);
    $recommended->setUpdatedAt($yesterday);
    $recommended->postpone = true;
    $recommended->save();
    /** @var JsonResponse $response */
    $response = $this->call('GET', "/api/article/daily");
    $this->assertEquals($normalCount + 1, count($response->getData(true)['articles']));
  }

  public function test_delete_user() {
    $this->mockUser($this->defaultUserId);
    $articleId = $this->article->id;

    /** @var JsonResponse $response */
    $response = $this->call('DELETE', "/api/article/$articleId");
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
  }

  public function test_delete_404() {
    $this->mockUser($this->defaultUserId);

    /** @var JsonResponse $response */
    $response = $this->call('DELETE', "/api/article/999999");
    $this->assertEquals(IlluminateResponse::HTTP_NOT_FOUND, $response->getStatusCode());
  }

  public function test_delete_not_user() {
    $this->mockUser(2);
    $articleId = $this->article->id;

    /** @var JsonResponse $response */
    $response = $this->call('DELETE', "/api/article/$articleId");
    $this->assertEquals(IlluminateResponse::HTTP_FORBIDDEN, $response->getStatusCode());
  }

  public function test_read_user() {
    $this->mockUser($this->defaultUserId);
    $articleId = $this->article->id;

    /** @var JsonResponse $response */
    $response = $this->call('GET', "/api/article/$articleId/read");
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
  }

  public function test_read_404() {
    $this->mockUser($this->defaultUserId);

    /** @var JsonResponse $response */
    $response = $this->call('GET', "/api/article/999999/read");
    $this->assertEquals(IlluminateResponse::HTTP_NOT_FOUND, $response->getStatusCode());
  }

  public function test_read_not_user() {
    $this->mockUser(2);
    $articleId = $this->article->id;

    /** @var JsonResponse $response */
    $response = $this->call('GET', "/api/article/$articleId/read");
    $this->assertEquals(IlluminateResponse::HTTP_FORBIDDEN, $response->getStatusCode());
  }

  public function test_postpone_article_user() {
    $this->mockUser($this->defaultUserId);
    Artisan::call('articles:clearrecentrecommendations');

    /** @var JsonResponse $response */
    $response = $this->call('GET', "/api/article/daily");
    $recommendations = $response->getData(true)['articles'];

    $articleId = $recommendations[0]['id'];

    /** @var JsonResponse $response */
    $response = $this->call('GET', "/api/article/$articleId/postpone");
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
  }

  public function test_get_categories() {
    $this->mockUser($this->defaultUserId);

    /** @var JsonResponse $response */
    $response = $this->call('GET', "/api/article/category");
    $this->assertEquals(IlluminateResponse::HTTP_OK, $response->getStatusCode());
  }

  public function test_import_articles() {
    $this->mockUser(2);
    $importlist = [];
    $urls = [];

    for($i = 0; $i < 20; $i++) {
      do {
        $url = $this->faker->url();
      } while(in_array($url, $urls));
      $urls[] = $url;
      $importlist[] = [
        'name' => ucwords(implode(' ', $this->faker->words(3))),
        'url' => $url
      ];
    }
    /** @var JsonResponse $response */
    $response = $this->call('POST', '/api/article/import', ['importlist' => $importlist]);
    $this->assertEquals(20, count($response->getData(true)['articles']));
  }

  public function test_import_articles_flagged_added() {
    Article::where('user_id', 2)->delete();
    $this->mockUser(2);
    $importlist = [];
    $urls = [];

    for($i = 0; $i < 20; $i++) {
      do {
        $url = $this->faker->url();
      } while(in_array($url, $urls));
      $urls[] = $url;
      $importlist[] = [
        'name' => ucwords(implode(' ', $this->faker->words(3))),
        'url' => $url
      ];
    }
    /** @var JsonResponse $response */
    $response = $this->call('POST', '/api/article/import', ['importlist' => $importlist]);
    $articles = $response->getData(true)['articles'];
    foreach ($articles as $article) {
      $this->assertTrue($article['justAdded']);
    }
  }

  public function test_import_articles_with_dupes() {
    $this->mockUser($this->defaultUserId);
    $importlist = [];
    $urls = [];
    $articles = Article::where('user_id', $this->defaultUserId)->take(2)->get();
    foreach($articles as $article) {
      $urls[] = $article->url;
      $importlist[] = [
        'name' => $article->title,
        'url' => $article->url
      ];
    }
    for($i = 0; $i < 18; $i++) {
      do {
        $url = $this->faker->url();
      } while(in_array($url, $urls));
      $urls[] = $url;
      $importlist[] = [
        'name' => ucwords(implode(' ', $this->faker->words(3))),
        'url' => $url
      ];
    }
    /** @var JsonResponse $response */
    $response = $this->call('POST', '/api/article/import', ['importlist' => $importlist]);
    $this->assertEquals(20, count($response->getData(true)['articles']));
  }

  public function test_import_articles_dupes_not_flagged_as_added() {
    $this->mockUser($this->defaultUserId);
    $importlist = [];
    $urls = [];
    $articles = Article::where('user_id', $this->defaultUserId)->take(2)->get();
    $dupeArticle = $articles[0];
    foreach($articles as $article) {
      $urls[] = $article->url;
      $importlist[] = [
        'name' => $article->title,
        'url' => $article->url
      ];
    }
    for($i = 0; $i < 18; $i++) {
      do {
        $url = $this->faker->url();
      } while(in_array($url, $urls));
      $urls[] = $url;
      $importlist[] = [
        'name' => ucwords(implode(' ', $this->faker->words(3))),
        'url' => $url
      ];
    }
    /** @var JsonResponse $response */
    $response = $this->call('POST', '/api/article/import', ['importlist' => $importlist]);
    $articles = $response->getData(true)['articles'];
    foreach ($articles as $article) {
      if($article['title'] == $dupeArticle->title) {
        $this->assertFalse($article['justAdded']);
        break;
      }
    }
  }

  public function test_populate_articles() {
    $this->mockUser(17);
    $this->call('GET', '/api/article/populate');
    $this->assertResponseOk();
  }
}