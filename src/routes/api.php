<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/site','Basesvr\SimpleBase\Https\Controllers\SiteController@getSiteList');
Route::get('/menu','Basesvr\SimpleBase\Https\Controllers\MenuController@getMenuList');
Route::get('/category','Basesvr\SimpleXiaoshuo\Https\Controllers\CategoryController@categoryList');
Route::post('/category-add','Basesvr\SimpleXiaoshuo\Https\Controllers\CategoryController@categoryAdd');
Route::post('/category-update','Basesvr\SimpleXiaoshuo\Https\Controllers\CategoryController@categoryUpdate');
Route::post('/category-del','Basesvr\SimpleXiaoshuo\Https\Controllers\CategoryController@categoryDel');
Route::get('/content','Basesvr\SimpleXiaoshuo\Https\Controllers\ContentController@getContent');
Route::get('/erweima','Basesvr\SimpleXiaoshuo\Https\Controllers\ContentController@erweima');
Route::get('/chapter-list','Basesvr\SimpleXiaoshuo\Https\Controllers\ContentController@getChapterList');
Route::get('/chapter-content','Basesvr\SimpleXiaoshuo\Https\Controllers\ContentController@getChapterContent');
Route::get('/source','Basesvr\SimpleXiaoshuo\Https\Controllers\SourceController@getSourceList');
Route::post('/source-add','Basesvr\SimpleXiaoshuo\Https\Controllers\SourceController@addSource');
Route::post('/source-update','Basesvr\SimpleXiaoshuo\Https\Controllers\SourceController@updateSource');
Route::post('/source-del','Basesvr\SimpleXiaoshuo\Https\Controllers\SourceController@delSource');
Route::get('/content-sort','Basesvr\SimpleXiaoshuo\Https\Controllers\ContentController@sort');
Route::post('/content-update','Basesvr\SimpleXiaoshuo\Https\Controllers\ContentController@updateContentHot');

Route::get('/video-list','Basesvr\SimpleVideo\Https\Controllers\VideoController@getList');
Route::post('/set-banner','Basesvr\SimpleVideo\Https\Controllers\VideoController@setBanner');
Route::get('/get-banner','Basesvr\SimpleVideo\Https\Controllers\VideoController@getBanner');
Route::post('/edit-banner','Basesvr\SimpleVideo\Https\Controllers\VideoController@editBanner');
Route::post('/del-banner','Basesvr\SimpleVideo\Https\Controllers\VideoController@delBanner');
