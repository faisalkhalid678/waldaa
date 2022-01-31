<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Admin\Song;
use App\Admin\Category;

class SongsController extends Controller {

    public function __construct() {
        $this->song = new Song();
        $this->category = new Category();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $pageTitle = 'Songs';
        $active = 'songs';
        $where['status'] = getConstant('STATUS_ACTIVE');
        $songs = $this->song->getAllSongs($where);
        return view('admin.songs.listing', compact('pageTitle', 'songs', 'active'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $pageTitle = 'Songs';
        $active = 'songs';
        $categories = $this->category->getAllCategories(array('status' => getConstant('STATUS_ACTIVE')));
        return view('admin.songs.add', compact('pageTitle', 'active', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'song_name' => 'required',
            'song_description' => 'required',
        ]);
        if ($request->hasFile('song_file')) {
            $this->validate($request, [
                'song_file' => 'mimes:audio/mpeg,mpga,mp3,wav,aac',
            ]);

            $file = $request->song_file;
            $file_name = str_replace(' ', '', strtolower($request->input('song_name'))) . '.' . $file->getClientOriginalExtension();
            $path = public_path() . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'songs' . DIRECTORY_SEPARATOR;
            $file->move($path, $file_name);
            $filenametostore = $file_name;
        } else {
            $filenametostore = '';
        }
        
        if ($request->hasFile('cover_image')) {
            $this->validate($request, [
                'cover_image' => 'mimes:jpg,jpeg,png,tiff,bmp',
            ]);

            $file = $request->cover_image;
            $file_name = str_replace(' ', '', strtolower($request->input('song_name'))) . '.' . $file->getClientOriginalExtension();
            $path = public_path() . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'songs_covers' . DIRECTORY_SEPARATOR;
            $file->move($path, $file_name);
            $coverName = $file_name;
        } else {
            $coverName = '';
        }

        $data = $request->input();
        $data['file_name'] = $filenametostore;
        $data['cover_image'] = $coverName;
        $saved = $this->song->saveSong($data);
        if ($saved) {
            return redirect()->back()->with('message', 'Song Inserted Successfully');
        } else {
            return redirect()->back()->with('error', 'Song Not Inserted Successfully');
        }
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
        $pageTitle = 'Songs';
        $active = 'songs';
        $where['id'] = $id;
        $where['status'] = getConstant('STATUS_ACTIVE');
        $categories = $this->category->getAllCategories(array('status' => getConstant('STATUS_ACTIVE')));
        $songDetail = $this->song->getSongDetail($where);
        return view('admin.songs.edit', compact('pageTitle', 'active', 'songDetail', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $this->validate($request, [
            'song_name' => 'required',
            'song_description' => 'required',
        ]);
        $where['id'] = $id;
        $songDetail = $this->song->getSongDetail($where);
        if ($request->hasFile('song_file')) {
            $this->validate($request, [
                'song_file' => 'mimes:audio/mpeg,mpga,mp3,wav,aac',
            ]);

            $path = public_path() . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'songs' . DIRECTORY_SEPARATOR;
            if ($songDetail->song_file != "") {
                $previousFIlePath = $path . $songDetail->song_file;
                if (file_exists($previousFIlePath)) {
                    unlink($previousFIlePath);
                }
            }
            $file = $request->song_file;
            $file_name = str_replace(' ', '', strtolower($request->input('song_name'))) . '.' . $file->getClientOriginalExtension();

            $file->move($path, $file_name);
            $filenametostore = $file_name;
        } else {
            $filenametostore = $songDetail->song_file;
        }
        
        if ($request->hasFile('cover_image')) {
            $this->validate($request, [
                'cover_image' => 'mimes:jpg,jpeg,png,tiff,bmp',
            ]);

            $path = public_path() . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'songs_covers' . DIRECTORY_SEPARATOR;
            if ($songDetail->cover_image != "") {
                $previousFIlePath = $path . $songDetail->cover_image;
                if (file_exists($previousFIlePath)) {
                    unlink($previousFIlePath);
                }
            }
            $file = $request->cover_image;
            $file_name = str_replace(' ', '', strtolower($request->input('song_name'))) . '.' . $file->getClientOriginalExtension();

            $file->move($path, $file_name);
            $coverImage = $file_name;
        } else {
            $coverImage = $songDetail->cover_image;
        }
        //print_r($coverImage); die();
        $data = array(
            'category_id' => $request->input('song_category'),
            'song_name' => $request->input('song_name'),
            'song_description' => $request->input('song_description'),
            'song_lyrics' => $request->input('song_lyrics'),
            'song_file' => $filenametostore,
            'cover_image' => $coverImage,
            'is_single' => $request->input('is_single')
        );
        $updated = $this->song->updateSong($data, $where);
        if ($updated) {
            return redirect()->back()->with('message', 'Song Updated Successfully');
        } else {
            return redirect()->back()->with('error', 'Song Not Updated Successfully');
        }
    }

    public function mark_new(Request $request) {

        $where['id'] = $request->input('song_id');
        $data = array(
            'is_new' => $request->input('is_new'),
        );
        $updated = $this->song->updateSong($data, $where);
        if ($updated) {
            return json_encode(array('status' => 'true'));
        } else {
            return json_encode(array('status' => 'false'));
        }
    }

    public function mark_featured(Request $request) {

        $where['id'] = $request->input('song_id');
        $data = array(
            'is_featured' => $request->input('is_featured'),
        );
        $updated = $this->song->updateSong($data, $where);
        if ($updated) {
            return json_encode(array('status' => 'true'));
        } else {
            return json_encode(array('status' => 'false'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $where['id'] = $id;
        $deleted = $this->song->deleteSong($where);
        if ($deleted) {
            return redirect()->back()->with('message', 'Song Deleted Successfully');
        } else {
            return redirect()->back()->with('error', 'Song Not Deleted Successfully');
        }
    }

}
