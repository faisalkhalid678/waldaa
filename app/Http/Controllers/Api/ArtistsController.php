<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Admin\Artist;
use App\Admin\Artists_song;

class ArtistsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $artist = new Artist();
        $artists = $artist->where(['artist_status' => getConstant('STATUS_ACTIVE')])->get()->toArray();
        $artistArray = array();
        if (!empty($artists)) {
            foreach ($artists as $art) {
                $artistArr['artist_id'] = $art['id'];
                $artistArr['artist_name'] = $art['artist_name'];
                $artistArr['artist_description'] = $art['artist_description'];
                $artistArr['artist_image'] = url('/') . '/public/uploads/artists/' . $art['artist_image'];
                $artistSong = new Artists_song();
                $noOfSongs = $artistSong->where('artist_id',$art['id'])->count();
                $artistArr['no_of_songs'] = $noOfSongs;
                $artistArray[] = $artistArr;
            }
        }
        
        return buildResponse('success', "All Artists Data.", $artistArray);  
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
