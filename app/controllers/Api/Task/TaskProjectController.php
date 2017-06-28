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
 * Date: 6/26/17
 * Time: 2:06 PM
 */
namespace Api\Task;

use Api\AstroBaseController;
use Auth;
use Illuminate\Http\Response as IlluminateResponse;
use Task\TaskProject;

class TaskProjectController extends AstroBaseController {

  private $user = null;

  public function __construct() {
    $this->user = Auth::user();
  }

  public function get() {
    $projects = TaskProject::where('user_id', $this->user->id)
      ->orderBy('name')
      ->get();
    return $this->successResponse([
      'projects' => $this->transformCollection($projects)
    ]);
  }

  public function transform($project) {
    return [
      'id' => (int)$project['id'],
      'name' => $project['name']
    ];
  }

}
