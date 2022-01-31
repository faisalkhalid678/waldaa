<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Admin\Album;
use App\Admin\Favourite_album;


class AlbumsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function getNewAlbums(Request $request){
        $validator = Validator::make($request->all(), [
                    'userId' => 'required',
        ]);
        if ($validator->fails()) {
            return buildResponse('error', "Fields Validation Errors.", buildResponseError());
        }
        $album = new Album();
        $albums = $album->getAllAlbums(['is_new' => '1','album_status' => getConstant('STATUS_ACTIVE')]);
        
        if(!empty($albums)){
            $albumArray = array();
            foreach($albums as $alb){
                $favourite_album = new Favourite_album();
                $isFav = $favourite_album->where(['user_id' => $request->userId, 'album_id' => $alb['id']])->first();
                if($isFav && $isFav->is_favourite == '1'){
                  $songFav = '1';  
                }else{
                   $songFav = '0'; 
                }
               $albumArr['album_id'] = $alb['id']; 
               $albumArr['album_name'] = $alb['album_name']; 
               $albumArr['album_description'] = $alb['album_description']; 
               $albumArr['album_image'] = url('/').'/public/uploads/albums/'.$alb['album_image'];
               $albumArr['is_new'] = $alb['is_new'];
               $albumArr['is_favourite'] = $songFav;
               $albumArray[] = $albumArr;
            }
          return buildResponse('success', "New Albums Data.", $albumArray);  
        }else{
           return buildResponse('error', "New Album Not Found.", buildResponseError()); 
        }
        
         
    }
    
    public function getAllAlbums(Request $request){
        $validator = Validator::make($request->all(), [
                    'userId' => 'required',
        ]);
        if ($validator->fails()) {
            return buildResponse('error', "Fields Validation Errors.", buildResponseError());
        }
        $album = new Album();
        $albums = $album->getAllAlbums(['album_status' => getConstant('STATUS_ACTIVE')]);
        
        if(!empty($albums)){
            $albumArray = array();
            foreach($albums as $alb){
                $favourite_album = new Favourite_album();
                $isFav = $favourite_album->where(['user_id' => $request->userId, 'album_id' => $alb['id']])->first();
                if($isFav && $isFav->is_favourite == '1'){
                  $songFav = '1';  
                }else{
                   $songFav = '0'; 
                }
               $albumArr['album_id'] = $alb['id']; 
               $albumArr['album_name'] = $alb['album_name']; 
               $albumArr['album_description'] = $alb['album_description'];
               $albumArr['album_image'] = url('/').'/public/uploads/albums/'.$alb['album_image'];
               $albumArr['is_new'] = $alb['is_new'];
               $albumArr['is_favourite'] = $songFav;
               $albumArray[] = $albumArr;
            }
          return buildResponse('success', "All Albums Data.", $albumArray);  
        }else{
           return buildResponse('error', "Album Not Found.", buildResponseError()); 
        }
        
         
    }
    
    public function getAlbumArtists(Request $request){
        $validator = Validator::make($request->all(), [
                    'albumId' => 'required',
        ]);
        if ($validator->fails()) {
            return buildResponse('error', "Fields Validation Errors.", buildResponseError());
        }
        
        $album = new Album();
        $artists = $album->getAlbumArtists($request->albumId);
        $artistArray = array();
        if(!empty($artists)){
            foreach($artists as $artist){
                $artistArr['album_id'] = $artist->album_id;
                $artistArr['artist_id'] = $artist->id;
                $artistArr['artist_name'] = $artist->artist_name;
                $artistArr['artist_description'] = $artist->artist_description;
                $artistArr['artist_image'] = url('/').'/public/uploads/artists/'.$artist->artist_image;
                $artistArr['no_of_songs'] = $album->getNumOfSongs($artist->album_id,$artist->id);
                $artistArray[] = $artistArr;
            }
        }
        return buildResponse('success', "Album Artists List.", $artistArray); 
    }
    
    
    
    public function getAlbumArtistSongs(Request $request){
        $validator = Validator::make($request->all(), [
                    'albumId' => 'required',
                    'artistId' => 'required',
        ]);
        if ($validator->fails()) {
            return buildResponse('error', "Fields Validation Errors.", buildResponseError());
        }
        
        $album = new Album();
        $songs = $album->getAlbumArtistSongs($request->albumId,$request->artistId);
        $songsArray = array();
        if(!empty($songs)){
            foreach($songs as $song){
               $songArr['album_id'] = $song->album_id;
               $songArr['album_name'] = $song->album_name;
               $songArr['album_description'] = $song->album_description;
               $songArr['album_image'] = url('/').'/public/uploads/albums/'.$song->album_image;
               $songArr['artist_id'] = $song->artist_id;
               $songArr['artist_name'] = $song->artist_name;
               $songArr['artist_description'] = $song->artist_description;
               $songArr['artist_image'] = url('/').'/public/uploads/artists/'.$song->artist_image;
               $songArr['song_id'] = $song->id;
               $songArr['category_id'] = $song->category_id;
               $songArr['song_name'] = $song->song_name;
               $songArr['song_description'] = $song->song_description;
               $songArr['song_lyrics'] = $song->song_lyrics;
               $songArr['song_file'] = url('/').'/public/uploads/songs/'.$song->song_file;
               $songArr['cover_image'] = url('/').'/public/uploads/songs_covers/'.$song->cover_image;
               $songArr['is_new'] = $song->is_new;
               $songsArray[] = $songArr;
            }
        }
        return buildResponse('success', "Album Artists Songs.", $songsArray); 
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    
    public function makeFovourite(Request $request){
        $validator = Validator::make($request->all(), [
                    'userId' => 'required',
                    'albumId' => 'required',
        ]);
        if ($validator->fails()) {
            return buildResponse('error', "Fields Validation Errors.", buildResponseError());
        }
        
        $favourite_album = new Favourite_album();
        $isAlreadyExistAndFav = $favourite_album->where(['user_id' => $request->userId, 'album_id' => $request->albumId])->first();
        if(!$isAlreadyExistAndFav){
            $favourite_album->user_id = $request->userId;
            $favourite_album->album_id = $request->albumId;
            $favourite_album->is_favourite = '1';
            $favourite_album->save();
        }else{
            if($isAlreadyExistAndFav->is_favourite == '1'){
                $updateFav = '0';
            }else{
                $updateFav = '1';
            }
            
            $updateFavourite = array(
                'is_favourite' => $updateFav
            );
            $favourite_album->where('id',$isAlreadyExistAndFav->id)->update($updateFavourite);
        }
        return buildResponse('success', "Favourite Status Updated.", buildResponseError());
    }
    
    
    
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
