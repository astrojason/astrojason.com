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
 * Date: 6/12/17
 * Time: 12:05 PM
 */
Route::get('', 'TaskController@get');
Route::get('today', 'DailyTasksController@get');
Route::get('projects', 'TaskProjectController@get');
Route::group(['prefix' => '{task_id}'], function(){
  Route::delete('', 'TaskController@delete');
  Route::post('', 'TaskController@save');
  Route::get('/skip', 'TaskDueController@skip');
  Route::get('complete', 'TaskDueController@complete');
});
