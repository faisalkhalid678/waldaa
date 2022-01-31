<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Admin\Artist;
use App\Admin\Album;
use App\Admin\Song;
use App\User;

class DashboardController extends Controller
{
    public function __construct() {
        $this->album = new Album();
        $this->artist = new Artist();
        $this->song = new Song();
        $this->user = new User();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $active ="dashboard";
        $noOfSongs = $this->song->where('status', getConstant('STATUS_ACTIVE'))->count();
        $noOfArtists = $this->artist->where('artist_status', getConstant('STATUS_ACTIVE'))->count();
        $noOfAlbums = $this->album->where('album_status', getConstant('STATUS_ACTIVE'))->count();
        $noOfUsers = $this->user->where(['status' => getConstant('STATUS_ACTIVE'), 'user_type' => 'User'])->count();
        return view('admin.dashboard', compact('active','noOfSongs','noOfArtists','noOfAlbums','noOfUsers'));
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
