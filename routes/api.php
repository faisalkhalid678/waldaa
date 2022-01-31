<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('login', 'Api\UserController@login');
Route::post('signup', 'Api\UserController@signup');
Route::post('forget-password', 'Api\UserController@forgetPassword');
Route::post('verify-code', 'Api\UserController@verifyCode');
Route::post('reset-password', 'Api\UserController@resetPassword');
Route::post('get-profilepic', 'Api\UserController@getProfilePic');

//Home page api
Route::post('music-data', 'Api\HomepageController@index');
Route::post('get-new-albums', 'Api\AlbumsController@getNewAlbums');
Route::post('get-all-albums', 'Api\AlbumsController@getNewAlbums');
Route::get('get-all-artists', 'Api\ArtistsController@index');
Route::post('get-album-artists', 'Api\AlbumsController@getAlbumArtists');
Route::post('get-album-artist-songs', 'Api\AlbumsController@getAlbumArtistSongs');
Route::post('make-album-favourite', 'Api\AlbumsController@makeFovourite');
Route::get('get-featured-songs', 'Api\SongsController@getFeaturedSongs');
Route::post('get-artist-songs', 'Api\SongsController@getSongsByArtist');

//Follow APIs
Route::post('follow', 'Api\FollowersController@follow');
Route::post('request-status-update', 'Api\FollowersController@request_status_update');
Route::get('/weekly-selection', 'Api\WeeklySelectionController@index');
Route::get('/advertisement', 'Api\AdvertisementController@index');
Route::get('/search', 'Api\HomepageController@search');
