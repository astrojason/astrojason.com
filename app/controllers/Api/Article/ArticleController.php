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
    $page_size = Input::get('page_size');
    $page = Input::get('page', 1);
    $q = Input::get('q');
    $category = Input::get('category');
    $include_read = filter_var(Input::get('include_read'), FILTER_VALIDATE_BOOLEAN);

    $query = Article::where('user_id', Auth::user()->id);
    if(!$include_read){
      $query->has('read', '<', 1);
    }
    if(isset($category)){
      $query->whereHas('categories', function($query) use ($category){
        $query->where('category_id', $category);
      });
    }
    if(isset($q)) {
      $query->where(function ($query) use ($q) {
        $query->where('title', 'LIKE', '%' . $q . '%')
          ->orwhere('url', 'LIKE', '%' . $q . '%');
      });
    }
    if(isset($page_size)){
      $query->take($page_size)->skip($page_size * ($page - 1));
    }
    $articles = $query->get();

    return Response::json([
      'articles' => $this->transformCollection($articles)
    ]);
  }

  /**
   * @return JsonResponse
   */
  public function daily(){
    $today = Carbon::create();
    $userId = Auth::user()->id;
    $dailyArticles = Recommended::where('user_id', $userId)
      ->where('created_at', 'LIKE', $today->toDateString() . '%')->get();
    if(count($dailyArticles) == 0) {
      $yesterday = Carbon::create()->subDay(1);
      /** @var Recommended $postponedArticles */
      $postponedArticles = Recommended::where('user_id', Auth::user()->id)
        ->where('created_at', 'LIKE', $yesterday->toDateString() . '%')
        ->where('postpone', true)
        ->get();
      $ids = [];
      foreach ($postponedArticles as $postponedArticle) {
        $ids[] = $postponedArticle->article_id;
      }
      if(count($ids) > 0) {
        $dailyArticles = Article::whereIn('id', $ids)->get();
        $dailyArticles = $this->transformCollection($dailyArticles);
      }
      $userSettings = DailySetting::where('user_id', Auth::user()->id)->get();
      foreach ($userSettings as $userSetting) {
        $query = Article::where('user_id', Auth::user()->id);
        if ($userSetting->category_id) {
          $query->whereHas('categories', function ($query) use ($userSetting) {
            $query->where('category_id', $userSetting->category_id);
          });
        } else {
          $query->has('categories', '<', 1);
        }
        $query->whereHas('recommended', function ($query) use ($today) {
          $query->where('articles_recommended.created_at', '<', $today->subDay(7));
        });
        $query->orHas('recommended', '<', 1);
        if(!$userSetting->allow_read) {
          $query->has('read', '<', 1);
        }
        $query->orderBy(DB::raw('RAND()'));
        $articles = $query->take($userSetting->number)->get();
        foreach ($articles as $article) {
          Recommended::create([
            'article_id' => $article->id,
            'user_id' => $article->user_id
          ]);
          $dailyArticles[] = $this->transform($article);
        }
      }
    } else {
      $ids = [];
      foreach ($dailyArticles as $dailyArticle) {
        $ids[] = $dailyArticle->article_id;
      }
      $dailyArticles = Article::whereIn('id', $ids)->get();
      $dailyArticles = $this->transformCollection($dailyArticles);
    }
    return Response::json(['articles' => $dailyArticles]);
  }

  /**
   * @return JsonResponse
   */
  public function put() {
    $article = $this->add(Auth::user()->id, Input::all());
    if($article->justAdded) {
      return Response::json(
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
        return Response::json(
          ['article' => $this->transform($article)]
        );
      } else {
        return Response::json([], IlluminateResponse::HTTP_FORBIDDEN);
      }
    }
    return Response::json([], IlluminateResponse::HTTP_NOT_FOUND);
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
        return Response::json([], IlluminateResponse::HTTP_OK);
      } else {
        return Response::json([], IlluminateResponse::HTTP_FORBIDDEN);
      }
    }
    return Response::json([], IlluminateResponse::HTTP_NOT_FOUND);
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
        return Response::json([], IlluminateResponse::HTTP_OK);
      } else {
        return Response::json([], IlluminateResponse::HTTP_FORBIDDEN);
      }
    }
    return Response::json([], IlluminateResponse::HTTP_NOT_FOUND);
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
        return Response::json([], IlluminateResponse::HTTP_OK);
      } else {
        return Response::json([], IlluminateResponse::HTTP_FORBIDDEN);
      }
    }
    return Response::json([], IlluminateResponse::HTTP_NOT_FOUND);
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
    if(count($params['categories']) > 0){
      foreach($params['categories'] as $articleCategory){
        $category = $this->categoryCreateOrReturn($user_id, $articleCategory);
        $article->categories()->attach($category);
      }
      $article->save();
    }
    return $article;
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
      'recommended' => []
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
        'id' => $recommended->id,
        'date' => $recommended->created_at->toDateString()
      ];
    }
    return $returnValue;
  }
}