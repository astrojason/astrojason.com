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
 * Time: 12:03 PM
 */
Route::get('', 'ArticleController@query');
Route::put('', 'ArticleController@put');
Route::group(['prefix' => 'category'], function(){
  Route::get('', 'CategoryController@query');
});
Route::get('daily', 'ArticleController@daily');
Route::post('import', 'ArticleController@import');
Route::group(['prefix' => '{article_id}'], function(){
  Route::post('', 'ArticleController@post');
  Route::delete('', 'ArticleController@delete');
  Route::get('read', 'ArticleController@read');
  Route::get('postpone', 'ArticleController@postpone');
});