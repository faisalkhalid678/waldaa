<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Admin\Album;
use App\Admin\Artist;
use App\Admin\Artists_song;
use App\Admin\Category;
use App\Admin\Song;
use App\Admin\Favourite_album;

class HomepageController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $validator = Validator::make($request->all(), [
                    'userId' => 'required',
        ]);
        if ($validator->fails()) {
            return buildResponse('error', "Fields Validation Errors.", buildResponseError());
        }
        $album = new Album();
        $artist = new Artist();
        $albums = $album->where(['album_status' => getConstant('STATUS_ACTIVE')])->limit(5)->get()->toArray();
        $artists = $artist->where(['artist_status' => getConstant('STATUS_ACTIVE')])->limit(5)->get()->toArray();
        $albumArray = array();
        if (!empty($albums)) {
            foreach ($albums as $alb) {
                $favourite_album = new Favourite_album();
                $isFav = $favourite_album->where(['user_id' => $request->userId, 'album_id' => $alb['id']])->first();
                if ($isFav && $isFav->is_favourite == '1') {
                    $songFav = '1';
                } else {
                    $songFav = '0';
                }
                $albumArr['album_id'] = $alb['id'];
                $albumArr['album_name'] = $alb['album_name'];
                $albumArr['album_description'] = $alb['album_description'];
                $albumArr['album_image'] = url('/') . '/public/uploads/albums/' . $alb['album_image'];
                $albumArr['is_new'] = $alb['is_new'];
                $albumArr['is_favourite'] = $songFav;
                $albumArray[] = $albumArr;
            }
        }

        $artistArray = array();
        if (!empty($artists)) {
            foreach ($artists as $art) {
                $artistArr['artist_id'] = $art['id'];
                $artistArr['artist_name'] = $art['artist_name'];
                $artistArr['artist_description'] = $art['artist_description'];
                $artistArr['artist_image'] = url('/') . '/public/uploads/artists/' . $art['artist_image'];
                $artistSong = new Artists_song();
                $noOfSongs = $artistSong->where('artist_id', $art['id'])->count();
                $artistArr['no_of_songs'] = $noOfSongs;
                $artistArray[] = $artistArr;
            }
        }

        $category = new Category();
        $song = new Song();
        $categoryData = $category->where(['status' => getConstant('STATUS_ACTIVE')])->get()->toArray();
        $songsArray = array();
        if (!empty($categoryData)) {
            foreach ($categoryData as $cat) {
                $songs = $song->getAllSongs(['category_id' => $cat['id'], 'status' => getConstant('STATUS_ACTIVE')]);

                if (!empty($songs)) {
                    foreach ($songs as $sng) {
                        $songArr['song_id'] = $sng['id'];
                        $songArr['category_id'] = $sng['category_id'];
                        $songArr['category_name'] = $cat['category_name'];
                        $songArr['song_name'] = $sng['song_name'];
                        $songArr['song_description'] = $sng['song_description'];
                        $songArr['song_lyrics'] = $sng['song_lyrics'];
                        $songArr['song_file'] = url('/') . '/public/uploads/songs/' . $sng['song_file'];
                        $songArr['song_cover_image'] = url('/') . '/public/uploads/songs_covers/' . $sng['cover_image'];
                        $songArr['is_new'] = $sng['is_new'];
                        $songArr['is_featured'] = $sng['is_featured'];
                        $songArr['is_single'] = $sng['is_single'];
                        $songsArray[] = $songArr;
                    }
                }
            }
        }

        $singleSongs = $song->getAllSongs(['is_single' => '1', 'status' => getConstant('STATUS_ACTIVE')]);
        $singleSongsArray = array();
        if (!empty($singleSongs)) {
            foreach ($singleSongs as $sng) {
                $songArr['song_id'] = $sng['id'];
                $songArr['song_name'] = $sng['song_name'];
                $songArr['song_description'] = $sng['song_description'];
                $songArr['song_lyrics'] = $sng['song_lyrics'];
                $songArr['song_file'] = url('/') . '/public/uploads/songs/' . $sng['song_file'];
                $songArr['song_cover_image'] = url('/') . '/public/uploads/songs_covers/' . $sng['cover_image'];
                $songArr['is_new'] = $sng['is_new'];
                $songArr['is_featured'] = $sng['is_featured'];
                $songArr['is_single'] = $sng['is_single'];
                $singleSongsArray[] = $songArr;
            }
        }
        $dataArray = array(
            'albums' => $albumArray,
            'artists' => $artistArray,
            'oldSongsList' => $songsArray,
            'singlesSongsList' => $singleSongsArray
        );
        return buildResponse('success', "Homepage Data.", $dataArray);
    }

    public function getSongWithCategory() {

        $category = new Category();
        $song = new Song();
        $categoryData = $category->where(['status' => getConstant('STATUS_ACTIVE')])->get()->toArray();
        $songsArray = array();
        if (!empty($categoryData)) {
            foreach ($categoryData as $cat) {
                $songs = $song->getAllSongs(['category_id' => $cat['id'], 'status' => getConstant('STATUS_ACTIVE')]);

                if (!empty($songs)) {
                    foreach ($songs as $sng) {
                        $songArr['song_id'] = $sng['id'];
                        $songArr['category_id'] = $sng['category_id'];
                        $songArr['category_name'] = $cat['category_name'];
                        $songArr['song_name'] = $sng['song_name'];
                        $songArr['song_description'] = $sng['song_description'];
                        $songArr['song_lyrics'] = $sng['song_lyrics'];
                        $songArr['song_file'] = url('/') . '/public/uploads/songs/' . $sng['song_file'];
                        $songArr['song_cover_image'] = url('/') . '/public/uploads/songs_covers/' . $sng['cover_image'];
                        $songArr['is_new'] = $sng['is_new'];
                        $songArr['is_featured'] = $sng['is_featured'];
                        $songsArray[] = $songArr;
                    }
                }
            }
        }


        return buildResponse('success', "Homepage Data For songs with category.", $songsArray);
    }

    
    
    public function search(Request $request) {
        
        $album = new Album();
        $artist = new Artist();
        if($request->has('search_query')){
            $searchQuery = $request->search_query;
        }else{
            $searchQuery = "";
        }
        //Albums Search
        $albums = $album->where(['album_status' => getConstant('STATUS_ACTIVE')])
                ->where('album_name','like','%'.$searchQuery.'%')
                ->get()->toArray();
        
        //Artists Search
        $artists = $artist->where(['artist_status' => getConstant('STATUS_ACTIVE')])
                          ->where('artist_name','like','%'.$searchQuery.'%')
                          ->get()->toArray();
        $albumArray = array();
        if (!empty($albums)) {
            foreach ($albums as $alb) {
                $favourite_album = new Favourite_album();
                $isFav = $favourite_album->where(['user_id' => $request->userId, 'album_id' => $alb['id']])->first();
                if ($isFav && $isFav->is_favourite == '1') {
                    $songFav = '1';
                } else {
                    $songFav = '0';
                }
                $albumArr['album_id'] = $alb['id'];
                $albumArr['album_name'] = $alb['album_name'];
                $albumArr['album_description'] = $alb['album_description'];
                $albumArr['album_image'] = url('/') . '/public/uploads/albums/' . $alb['album_image'];
                $albumArr['is_new'] = $alb['is_new'];
                $albumArr['is_favourite'] = $songFav;
                $albumArray[] = $albumArr;
            }
        }

        $artistArray = array();
        if (!empty($artists)) {
            foreach ($artists as $art) {
                $artistArr['artist_id'] = $art['id'];
                $artistArr['artist_name'] = $art['artist_name'];
                $artistArr['artist_description'] = $art['artist_description'];
                $artistArr['artist_image'] = url('/') . '/public/uploads/artists/' . $art['artist_image'];
                $artistSong = new Artists_song();
                $noOfSongs = $artistSong->where('artist_id', $art['id'])->count();
                $artistArr['no_of_songs'] = $noOfSongs;
                $artistArray[] = $artistArr;
            }
        }

        $song = new Song();
        $songsArray = array();
        
                $songs = $song->where(['status' => getConstant('STATUS_ACTIVE')])
                        ->where('song_name','like','%'.$searchQuery.'%')
                        ->get();

                if (!empty($songs)) {
                    foreach ($songs as $sng) {
                        $songArr['song_id'] = $sng['id'];
                        
                        $songArr['song_name'] = $sng['song_name'];
                        $songArr['song_description'] = $sng['song_description'];
                        $songArr['song_lyrics'] = $sng['song_lyrics'];
                        $songArr['song_file'] = url('/') . '/public/uploads/songs/' . $sng['song_file'];
                        $songArr['song_cover_image'] = url('/') . '/public/uploads/songs_covers/' . $sng['cover_image'];
                        $songArr['is_new'] = $sng['is_new'];
                        $songArr['is_featured'] = $sng['is_featured'];
                        $songArr['is_single'] = $sng['is_single'];
                        $songsArray[] = $songArr;
                    }
                }
            

        
        $dataArray = array(
            'albums' => $albumArray,
            'artists' => $artistArray,
            'songs' => $songsArray,
        );
        return buildResponse('success', "Homepage Data.", $dataArray);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

}
