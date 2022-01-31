<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Admin\Artist;
use App\Admin\Song;

class ArtistsController extends Controller
{
    
    public function __construct() {
        $this->artist = new Artist();
        $this->song = new Song();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'Artists';
        $active = 'artists';
        $where['artist_status'] = getConstant('STATUS_ACTIVE');
        $artists = $this->artist->getAllArtists($where);
        return view('admin.artists.listing', compact('pageTitle', 'artists', 'active'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Artists';
        $active = 'artists';
        $songs = $this->song->getAllSongs(array('status' => getConstant('STATUS_ACTIVE')));
        return view('admin.artists.add', compact('pageTitle', 'active','songs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'artist_name' => 'required',
            'artist_description' => 'required',
        ]);
        if ($request->hasFile('artist_image')) {
            $this->validate($request, [
                'artist_image' => 'mimes:jpg,jpeg,png,tiff,bmp',
            ]);
            
            $file = $request->artist_image;
            $file_name = str_replace(' ', '', strtolower($request->input('artist_name'))) . '.' . $file->getClientOriginalExtension();
            $path = public_path() . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR. 'artists'.DIRECTORY_SEPARATOR;
            $file->move($path, $file_name);
            $filenametostore = $file_name;
        } else {
            $filenametostore = '';
        }
        
        $data = $request->input();
        $data['file_name'] = $filenametostore;
        $saved = $this->artist->saveArtist($data);
        if ($saved) {
            return redirect()->back()->with('message', 'Artist Inserted Successfully');
        } else {
            return redirect()->back()->with('error', 'Artist Not Inserted Successfully');
        }
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
        $pageTitle = 'Artists';
        $active = 'artists';
        $where['id'] = $id;
        $where['artist_status'] = getConstant('STATUS_ACTIVE');
        $artistDetail = $this->artist->getArtistDetail($where);
        $songs = $this->song->getAllSongs(array('status' => getConstant('STATUS_ACTIVE')));
        $artistSongs = $this->artist->getArtistSongsArr(array('artist_id' => $id));
        return view('admin.artists.edit', compact('pageTitle', 'active', 'artistDetail','songs','artistSongs'));
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
        $this->validate($request, [
            'artist_name' => 'required',
            'artist_description' => 'required',
        ]);
        $where['id'] = $id;
        $artistDetail = $this->artist->getArtistDetail($where);
        if ($request->hasFile('artist_image')) {
            $this->validate($request, [
                'artist_image' => 'mimes:jpg,jpeg,png,tiff,bmp',
            ]);
            
            $path = public_path() . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR. 'artists'.DIRECTORY_SEPARATOR;
            $previousFIlePath = $path.$artistDetail->artist_image;
            if($artistDetail->artist_image && file_exists($previousFIlePath)){
                unlink($previousFIlePath);
            }
            $file = $request->artist_image;
            $file_name = str_replace(' ', '', strtolower($request->input('artist_name'))) . '.' . $file->getClientOriginalExtension();
            $file->move($path, $file_name);
            $filenametostore = $file_name;
        } else {
            $filenametostore = $artistDetail->artist_image;
        }
        $data = array(
            'artist_name' => $request->input('artist_name'),
            'artist_description' => $request->input('artist_description'),
            'artist_image' => $filenametostore
        );
        $songs = $request->input('songs');
        $updated = $this->artist->updateArtist($data, $where,$songs,$id);
        if ($updated) {
            return redirect()->back()->with('message', 'Artist Updated Successfully');
        } else {
            return redirect()->back()->with('error', 'Artist Not Updated Successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $where['id'] = $id;
        $deleted = $this->artist->deleteArtist($where);
        if ($deleted) {
            return redirect()->back()->with('message', 'Artist Deleted Successfully');
        } else {
            return redirect()->back()->with('error', 'Artist Not Deleted Successfully');
        }
    }
}
