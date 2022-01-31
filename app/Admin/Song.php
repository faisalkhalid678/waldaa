<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;
use DB;
class Song extends Model
{
    public $timestamps = false;
    
    public function getAllSongs($where){
        return $this->where($where)->get()->toArray();
    }
    
    
    
    public function getSongDetail($where){
        return $this->where($where)->first();
    }
    
    
    public function getArtistSongs($artistId){
        $albums = DB::table('artists_songs')
            ->join('artists', 'artists.id', '=', 'artists_songs.artist_id')
            ->join('songs', 'songs.id', '=', 'artists_songs.song_id')
            ->select('songs.*','artists_songs.artist_id', 'artists.artist_name', 'artists.artist_description', 'artists.artist_image')
            ->where('artists_songs.artist_id', $artistId)
            ->get()->toArray();
        return $albums;
    }
    
    
    public function saveSong($data){
        $this->category_id = $data['song_category'];
        $this->song_name = $data['song_name'];
        $this->song_description = $data['song_description'];
        $this->song_lyrics = $data['song_lyrics'];
        $this->song_file = $data['file_name'];
        $this->cover_image = $data['cover_image'];
        $this->is_single = $data['is_single'];
        $this->date_created = date(getConstant('DATETIME_DB_FORMAT'));
        return $this->save();
    }
    
    
    public function updateSong($data,$where){
        return $this->where($where)->update($data);
    }
    
    public function deleteSong($where){
        $update = array(
            'status' => getConstant('STATUS_DELETED')
        );
        return $this->where($where)->update($update);
    }
}
