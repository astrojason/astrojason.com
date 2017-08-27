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
 * Time: 4:45 PM
 */

namespace Api\Article;


use Api\AstroBaseController;
use Article\Category;
use Auth;
use Illuminate\Http\JsonResponse;
use Input;
use Response;

class CategoryController extends AstroBaseController {

  /**
   * @return JsonResponse
   */
  public function get() {
    return Response::json(['categories' => $this->query()]);
  }

  /** @return Category[] */
  public function query() {
    return $this->transformCollection(Category::where('user_id', Auth::user()->id)->get());
  }

  /**
   * @return JsonResponse
   */
  public function put() {
    $category = Category::create([
        'name' => Input::get('name'),
        'user_id' =>  Auth::user()->id
      ]);
    return Response::json(['category' => $this->transform($category)]);
  }

  /**
   * @param Category[] $items
   *
   * @return array
   */
  public function transformCollection($items) {
    $transformedItems = [];
    foreach ($items as $item) {
      $transformedItems[] = $this->transform($item);
    }
    return $transformedItems;
  }

  /**
   * @param Category $item
   * @return array
   */
  public function transform($item) {
    return [
      'id' => (int)$item->id,
      'name' => $item->name
    ];
  }
}