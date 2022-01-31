<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Admin\Song;

class SongsController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getFeaturedSongs() {
        $song = new Song();
        $songsArray = array();
        $songs = $song->getAllSongs(['is_featured' => '1', 'status' => getConstant('STATUS_ACTIVE')]);
        if (!empty($songs)) {
            foreach ($songs as $sng) {
                $songArr['song_id'] = $sng['id'];
                $songArr['category_id'] = $sng['category_id'];
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
        return buildResponse('success', "Featured Songs List.", $songsArray); 
    }
    
    
    public function getSongsByArtist(Request $request) {
        $validator = Validator::make($request->all(), [
                    'artistId' => 'required',
        ]);
        if ($validator->fails()) {
            return buildResponse('error', "Fields Validation Errors.", buildResponseError());
        }
        $song = new Song();
        $songs = $song->getArtistSongs($request->artistId);
        $songsArray = array();
        if(!empty($songs)){
            foreach($songs as $song){
               $songArr['artistId'] = $song->artist_id;
               $songArr['artist_name'] = $song->artist_name;
               $songArr['artist_description'] = $song->artist_description;
               $songArr['song_lyrics'] = $song->song_lyrics;
               $songArr['artist_image'] = url('/').'/public/uploads/artists/'.$song->artist_image;
               $songArr['song_id'] = $song->id;
               $songArr['category_id'] = $song->category_id;
               $songArr['song_name'] = $song->song_name;
               $songArr['song_description'] = $song->song_description;
               $songArr['song_file'] = url('/').'/public/uploads/songs/'.$song->song_file;
               $songArr['song_cover_image'] = url('/') . '/public/uploads/songs_covers/' . $song->cover_image;
               $songArr['is_new'] = $song->is_new;
               $songsArray[] = $songArr;
            }
        }
        return buildResponse('success', "Artists Songs.", $songsArray);  
    }

    public function index() {
        //
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
