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
 * Time: 11:03 AM
 */

namespace Api\Article;

use Article\Article;
use Article\Category;
use Article\DailySetting;
use Article\Read;
use Article\Recommended;
use Api\AstroBaseController;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Http\JsonResponse;
use Input;
use Response;

/**
 * Class ArticleController
 * @package Api
 */
class ArticleController extends AstroBaseController {

  /**
   * @return JsonResponse
   */
  public function query() {
    $pageCount = 0;
    $userId = Auth::user()->id;
    $page_size = Input::get('page_size');
    $page = Input::get('page', 1);
    $q = Input::get('q');
    $category = Input::get('category');
    $sort = Input::get('sort', 'title');
    $descending = filter_var(Input::get('descending'), FILTER_VALIDATE_BOOLEAN);

    $include_read = filter_var(Input::get('include_read', false), FILTER_VALIDATE_BOOLEAN);

    /** @var Collection $results */
    $results = Article::where('user_id', $userId)->get();

    /** @var Article $result */
    foreach ($results as $result) {
      $result->times_recommended = count($result->recommended);
    }

    if(!$include_read){
      $results = $results->filter(function($article){
        return !(count($article->read) > 0);
      });
    }

    if(isset($category)){
      $results = $results->filter(function($article) use ($category) {
        return in_array($category,
          array_map(function($category){return $category['id'];}, $article['categories']->toArray()));
      });
    }
    if(isset($q)) {
      $results = $results->filter(function($article) use ($q) {
        return (strpos($article->title, $q) || strpos($article->url, $q));
      });
    }

    $total = $results->count();
    if(isset($page_size)){
      $pageCount = ceil($total / $page_size);
    }

    $articles = $results->sortBy($sort, SORT_REGULAR, $descending);
    if($page > 1) {
      $articles = $articles->slice($page_size * $page, count($results));
    }
    $articles = $articles->take($page_size);

    return $this->successResponse([
      'articles' => $this->transformCollection($articles),
      'page_count' => $pageCount,
      'total' => $total
    ]);
  }

  /**
   * @return JsonResponse
   */
  public function daily(){
    return $this->successResponse(['articles' => $this->generateDailyArticles(Auth::user()->id)]);
  }

  /**
   * @return JsonResponse
   */
  public function put() {
    $article = $this->add(Auth::user()->id, Input::all());
    if($article->justAdded) {
      return $this->successResponse(
        ['article' => $this->transform($article)]
      );
    } else {
      return Response::json(
        [
          'message' => 'An article with that url already exists',
          'id' => $article->id
        ],
        IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY
      );
    }
  }

  /**
   * @param $article_id
   * @return JsonResponse
   */
  public function post($article_id){
    $article = Article::whereId($article_id)->first();
    if(isset($article)){
      if($article->user_id == Auth::user()->id){
        $article = $this->save(Auth::user()->id, $article, Input::all());
        return $this->successResponse(
          ['article' => $this->transform($article)]
        );
      } else {
        return $this->errorResponse('', IlluminateResponse::HTTP_FORBIDDEN);
      }
    }
    return $this->errorResponse('', IlluminateResponse::HTTP_NOT_FOUND);
  }

  /**
   * @param $article_id
   * @return JsonResponse
   */
  public function delete($article_id) {
    $article = Article::whereId($article_id)->first();
    if(isset($article)){
      if($article->user_id == Auth::user()->id){
        $article->delete();
        return $this->successResponse();
      } else {
        return $this->errorResponse('', IlluminateResponse::HTTP_FORBIDDEN);
      }
    }
    return $this->errorResponse('', IlluminateResponse::HTTP_NOT_FOUND);
  }

  /**
   * @param $article_id
   * @return JsonResponse
   */
  public function read($article_id){
    $article = Article::whereId($article_id)->first();
    if(isset($article)){
      if($article->user_id == Auth::user()->id){
        Read::create([
          'article_id' => $article_id
        ]);
        return $this->successResponse();
      } else {
        return $this->errorResponse('', IlluminateResponse::HTTP_FORBIDDEN);
      }
    }
    return $this->errorResponse('', IlluminateResponse::HTTP_NOT_FOUND);
  }

  /**
   * @param $article_id
   * @return JsonResponse
   */
  public function postpone($article_id) {
    $today = Carbon::create();
    /** @var Recommended $article */
    $article = Recommended::where('article_id', $article_id)
      ->where('created_at', 'LIKE', $today->toDateString() . '%')->first();
    if(isset($article)) {
      if($article->user_id == Auth::user()->id){
        $article->postpone = true;
        $article->save();
        return $this->successResponse();
      } else {
        return $this->errorResponse('', IlluminateResponse::HTTP_FORBIDDEN);
      }
    }
    return $this->errorResponse('', IlluminateResponse::HTTP_NOT_FOUND);
  }

  public function import() {
    $importArticles = Input::get('importlist');
    $articles = [];
    foreach($importArticles as $importLink){
      $params = [
        'title' => $importLink['name'],
        'url' => $importLink['url'],
        'categories' => []
      ];
      $articles[] = $this->add(Auth::user()->id, $params);
    }
    return $this->successResponse(['articles' => $this->transformCollection($articles)]);
  }

  public function populate() {
    $articles = Article::doesntHave('categories', Category::where('name', 'At Home')->first())
      ->orderBy(DB::raw('RAND()'))
      ->take(40)
      ->get();
    /** @var Article $article */
    foreach ($articles as $article) {
      Article::create([
        'title' => $article->title,
        'url' => $article->url,
        'user_id' => Auth::user()->id
      ]);
    }
    return $this->successResponse();
  }

  public function getReadToday() {
    return $this->successResponse(['articles_read_today' => $this->readToday()]);
  }

  public function readCount() {
    return Article::where('user_id', Auth::user()->id)->has('read')->count();
  }

  public function articleCount() {
    return Article::where('user_id', Auth::user()->id)->count();
  }

  public function readToday() {
    return Article::where('user_id', Auth::user()->id)->whereHas('read', function($query){
      $query->where('created_at', 'LIKE', Carbon::create()->toDateString() . '%');
    })->count();
  }

  /**
   * @param int $user_id
   * @param array $params
   * @return Article
   */
  public function add($user_id, $params) {
    $article = $this->checkIfArticleExists($user_id, $params['url']);
    if(!isset($article)) {
      $article = Article::create([
        'user_id' => $user_id,
        'title' => $params['title'],
        'url' => $params['url']
      ]);
      $article->justAdded = true;
    }
    $article = $this->save($user_id, $article, $params);
    if($params['is_read']) {
      Read::create([
        'article_id' => $article->id
      ]);
    }

    return $article;
  }

  /**
   * @param int $user_id
   * @param Article $article
   * @param array $params
   * @return Article
   */
  public function save($user_id, $article, $params) {
    if(!$article->justAdded){
      $article->title = $params['title'];
      $article->url = $params['url'];
      $article->categories()->detach();
      $article->save();
    }
    if(array_has($params, 'categories') && count($params['categories']) > 0){
      foreach($params['categories'] as $articleCategory){
        $category = $this->categoryCreateOrReturn($user_id, $articleCategory);
        $article->categories()->attach($category);
      }
      $article->save();
    }
    return $article;
  }

  public function generateDailyArticles($userId) {
    $today = Carbon::create();
    $returnArticles = [];
//  Check if the daily articles list has already been created
    $dailyArticles = Recommended::where('user_id', $userId)
      ->where('created_at', 'LIKE', $today->toDateString() . '%')->get();

    if(count($dailyArticles) == 0) {
//    Get all the postponed articles from yesterday
      $dailyArticles = $this->getPostponedArticles($userId);
      $selectedArticleIds = array_map(function($article){return $article->id;}, $dailyArticles);
//    Get the user's settings
      $userSettings = DailySetting::where('user_id', $userId)->get();
      $allArticles = Article::where('user_id', $userId)
        ->get();
      foreach ($userSettings as $userSetting) {
        $settingArticles = $allArticles->filter(function($article) use ($selectedArticleIds, $today) {
          return !(in_array($article->id, $selectedArticleIds))
            &&
            !(in_array($today->subDay(7), $article['recommended']->toArray()));
        });
        if (!$userSetting->allow_read) {
          $settingArticles = $settingArticles->filter(function($article){
            return !(count($article['read']) > 0);
          });
        }
        if ($userSetting->category_id) {
          $settingArticles = $settingArticles->filter(function($article) use ($userSetting) {
            return in_array($userSetting->category_id,
              array_map(function($category){return $category['id'];}, $article['categories']->toArray()));
          });
        }
        $settingArticles->shuffle();
        if($userSetting->number > 0) {
          $settingArticles = $settingArticles->take($userSetting->number);
        }
        $returnArticles = array_merge($returnArticles, $this->transformCollection($settingArticles));
        $selectedArticleIds = array_merge(
          array_map(function($article) use ($userId){
            Recommended::create([
              'article_id' => $article['id'],
              'user_id' => $userId
            ]);
            return $article['id'];
            },
            $settingArticles->toArray())
          , $selectedArticleIds);
      }
    } else {
      $ids = array_map(function($article){
          return $article['article_id'];
        },
        $dailyArticles->toArray());
      $dailyArticles = Article::whereIn('id', $ids)->get();
      $returnArticles = $this->transformCollection($dailyArticles);
    }
    return $returnArticles;
  }

  private function getPostponedArticles($userId) {
    $returnArticles = [];
    $yesterday = Carbon::create()->subDay(1);
    /** @var Recommended $postponedArticles */
    $postponedArticles = Recommended::where('user_id', $userId)
      ->where('created_at', 'LIKE', $yesterday->toDateString() . '%')
      ->where('postpone', true)
      ->get();
    $ids = [];
    foreach ($postponedArticles as $postponedArticle) {
      $ids[] = $postponedArticle->article_id;
    }
    if(count($ids) > 0) {
      $dailyArticles = Article::whereIn('id', $ids)->get();
      $returnArticles = $this->transformCollection($dailyArticles);
    }
    return $returnArticles;
  }

  /**
   * @param int $user_id
   * @param string $url
   * @return Article|null
   */
  public function checkIfArticleExists($user_id, $url){
   $article = Article::where('user_id', $user_id)
     ->where('url', $url)->first();
   return $article;
  }

  /**
   * @param $user_id
   * @param $articleCategory
   * @return Category
   */
  public function categoryCreateOrReturn($user_id, $articleCategory) {
    $category = Category::whereId($articleCategory['id'])->first();
    if(!isset($category)){
      $category = Category::where('user_id', $user_id)
        ->where('name', $articleCategory['name'])->first();
      if(!isset($category)){
        $category = Category::create([
          'name' => $articleCategory['name'],
          'user_id' => $user_id
        ]);
      }
    }
    return $category;
  }

  /**
   * @param $items
   * @return array
   */
  public function transformCollection($items) {
    $returnItems = [];
    foreach($items as $item) {
      $returnItems[] = $this->transform($item);
    }
    return $returnItems;
  }

  /**
   * @param Article $item
   * @return array
   */
  public function transform($item) {
    $returnValue = [
      'id' => (int)$item['id'],
      'title' => $item['title'],
      'url' => $item['url'],
      'categories' => [],
      'read' => [],
      'recommended' => [],
      'justAdded' => (bool)$item->justAdded,
      'times_recommended' => $item['times_recommended']
    ];
    /** @var Category $category */
    foreach($item->categories as $category) {
      $returnValue['categories'][] = [
        'id' => $category->id,
        'name' => $category->name
      ];
    }
    /** @var Read $read */
    foreach ($item->read as $read) {
      $returnValue['read'][] = $read->created_at->toDateString();
    }
    /** @var Recommended $recommended */
    foreach($item->recommended as $recommended) {
      $returnValue['recommended'][] = [
        'date' => $recommended->created_at->toDateString(),
        'postponed' => (bool)$recommended->postpone
      ];
    }
    return $returnValue;
  }
}