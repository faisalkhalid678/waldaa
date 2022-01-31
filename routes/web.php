<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});


Route::get('admin/dashboard','Admin\DashboardController@index')->middleware('admin');
Route::get('admin','Admin\DashboardController@index')->middleware('admin');
Route::get('admin/login', 'Admin\Admin_authentication@index');
Route::post('admin/login/authenticate', 'Admin\Admin_authentication@checklogin');
Route::get('admin/logout', 'Admin\Admin_authentication@logout');


//Routes for Categories
Route::get('admin/categories', 'Admin\CategoriesController@index')->middleware('admin');
Route::get('admin/categories/add', 'Admin\CategoriesController@create')->middleware('admin');
Route::post('admin/categories/store', 'Admin\CategoriesController@store')->middleware('admin');
Route::get('admin/categories/edit/{id}', 'Admin\CategoriesController@edit')->middleware('admin');
Route::post('admin/categories/update/{id}', 'Admin\CategoriesController@update')->middleware('admin');
Route::get('admin/categories/delete/{id}', 'Admin\CategoriesController@destroy')->middleware('admin');



//Routes for Songs
Route::get('admin/songs', 'Admin\SongsController@index')->middleware('admin');
Route::get('admin/songs/add', 'Admin\SongsController@create')->middleware('admin');
Route::post('admin/songs/store', 'Admin\SongsController@store')->middleware('admin');
Route::get('admin/songs/edit/{id}', 'Admin\SongsController@edit')->middleware('admin');
Route::post('admin/songs/update/{id}', 'Admin\SongsController@update')->middleware('admin');
Route::get('admin/songs/delete/{id}', 'Admin\SongsController@destroy')->middleware('admin');
Route::post('admin/songs/mark-new', 'Admin\SongsController@mark_new')->middleware('admin');
Route::post('admin/songs/mark-featured', 'Admin\SongsController@mark_featured')->middleware('admin');


//Routes for Artists
Route::get('admin/artists', 'Admin\ArtistsController@index')->middleware('admin');
Route::get('admin/artists/add', 'Admin\ArtistsController@create')->middleware('admin');
Route::post('admin/artists/store', 'Admin\ArtistsController@store')->middleware('admin');
Route::get('admin/artists/edit/{id}', 'Admin\ArtistsController@edit')->middleware('admin');
Route::post('admin/artists/update/{id}', 'Admin\ArtistsController@update')->middleware('admin');
Route::get('admin/artists/delete/{id}', 'Admin\ArtistsController@destroy')->middleware('admin');


//Routes for Albums
Route::get('admin/albums', 'Admin\AlbumsController@index')->middleware('admin');
Route::get('admin/albums/add', 'Admin\AlbumsController@create')->middleware('admin');
Route::post('admin/albums/store', 'Admin\AlbumsController@store')->middleware('admin');
Route::get('admin/albums/edit/{id}', 'Admin\AlbumsController@edit')->middleware('admin');
Route::post('admin/albums/update/{id}', 'Admin\AlbumsController@update')->middleware('admin');
Route::get('admin/albums/delete', 'Admin\AlbumsController@destroy')->middleware('admin');
Route::post('admin/albums/get-songs-by-artist', 'Api\SongsController@getSongsByArtist')->middleware('admin');


//Weekly Selection Routes

Route::get('admin/weekly_selection', 'Admin\WeeklySelectionController@index')->middleware('admin');
Route::post('admin/weekly_selection/update', 'Admin\WeeklySelectionController@update')->middleware('admin');

//Advertisement Routes

Route::get('admin/advertisement', 'Admin\AdvertisementController@index')->middleware('admin');
Route::post('admin/advertisement/update', 'Admin\AdvertisementController@update')->middleware('admin');
