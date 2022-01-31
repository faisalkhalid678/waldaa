<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Admin\Album;
use App\Admin\Artist;
use App\Admin\Song;


class AlbumsController extends Controller
{
    
    public function __construct() {
        $this->album = new Album();
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
        $pageTitle = 'Albums';
        $active = 'albums';
        $albums = $this->album->getAllAlbums(['album_status' => getConstant('STATUS_ACTIVE')]);
        $albumArray = array();
        if(!empty($albums)){
            $albumArray = array();
            foreach($albums as $alb){
                
               $albumArr['album_id'] = $alb['id']; 
               $albumArr['album_name'] = $alb['album_name']; 
               $albumArr['album_description'] = $alb['album_description']; 
               $albumArr['album_image'] = url('/').'/public/uploads/albums/'.$alb['album_image'];
               $albumArr['is_new'] = $alb['is_new'];
               $albumArr['album_status'] = $alb['album_status'];
               $albumArray[] = $albumArr;
            }
        }

        return view('admin.albums.listing', compact('pageTitle', 'albumArray', 'active'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Albums';
        $active = 'albums';
        $artists = $this->artist->getAllArtists(array('artist_status' => getConstant('STATUS_ACTIVE')));
        $songs = $this->song->getAllSongs(array('status' => getConstant('STATUS_ACTIVE')));
        return view('admin.albums.add', compact('pageTitle', 'active','artists','songs'));
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
            'album_name' => 'required',
            'album_description' => 'required',
            'songs' => 'required'
        ]);
        //echo '<pre>';
        //print_r($request->songs); die();
        if ($request->hasFile('album_image')) {
            $this->validate($request, [
                'album_image' => 'mimes:jpg,jpeg,png,tiff,bmp',
            ]);
            
            $file = $request->album_image;
            $file_name = str_replace(' ', '', strtolower($request->input('album_name'))) . '.' . $file->getClientOriginalExtension();
            $path = public_path() . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR. 'albums'.DIRECTORY_SEPARATOR;
            $file->move($path, $file_name);
            $filenametostore = $file_name;
        } else {
            $filenametostore = '';
        }
        
        $data = $request->input();
        $data['file_name'] = $filenametostore;
        $saved = $this->album->saveAlbum($data);
        if ($saved) {
            return redirect()->back()->with('message', 'Album Inserted Successfully');
        } else {
            return redirect()->back()->with('error', 'Album Not Inserted Successfully');
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
        $pageTitle = 'Albums';
        $active = 'albums';
        $where['id'] = $id;
        $artists = $this->artist->getAllArtists(array('artist_status' => getConstant('STATUS_ACTIVE')));
        $songs = $this->song->getAllSongs(array('status' => getConstant('STATUS_ACTIVE')));
        $where['album_status'] = getConstant('STATUS_ACTIVE');
        $albumDetail = $this->album->getAlbumDetail($where);
        $albumArtistSongs = $this->album->getAlbumSongsArr(array('album_id' => $id));
//        echo '<pre>';
//        print_r($albumArtistSongs); die();
        return view('admin.albums.edit', compact('pageTitle', 'active', 'albumDetail','albumArtistSongs','artists','songs'));
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
            'album_name' => 'required',
            'album_description' => 'required',
        ]);
        $where['id'] = $id;
        $albumDetail = $this->album->getAlbumDetail($where);
        if ($request->hasFile('album_image')) {
            $this->validate($request, [
                'artist_image' => 'mimes:jpg,jpeg,png,tiff,bmp',
            ]);
            
            $path = public_path() . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR. 'albums'.DIRECTORY_SEPARATOR;
            $previousFIlePath = $path.$albumDetail->album_image;
            if($albumDetail->album_image && file_exists($previousFIlePath)){
                unlink($previousFIlePath);
            }
            $file = $request->album_image;
            $file_name = str_replace(' ', '', strtolower($request->input('album_name'))) . '.' . $file->getClientOriginalExtension();
            $file->move($path, $file_name);
            $filenametostore = $file_name;
        } else {
            $filenametostore = $albumDetail->album_image;
        }
        $data = array(
            
            'album_name' => $request->input('album_name'),
            'album_description' => $request->input('album_description'),
            'album_image' => $filenametostore
        );
        $songs = $request->input('songs');
        $updated = $this->album->updateAlbum($data, $where,$songs, $id);
        
        if ($updated) {
            return redirect()->back()->with('message', 'Album Updated Successfully');
        } else {
            return redirect()->back()->with('error', 'Album Not Updated Successfully');
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
        //
    }
    
    
    
}
