<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;
use App\Admin\Artists_song;


class Artist extends Model
{
    public $timestamps = false;
    
    public function getAllArtists($where){
        return $this->where($where)->get()->toArray();
    }
    
    
    
    public function getArtistDetail($where){
        return $this->where($where)->first();
    }
    
    
    public function getArtistSongsArr($where){
        $artistSong = new Artists_song();
        $artistSongs = $artistSong->where($where)->get()->toArray();
        $songArr = array();
        if(!empty($artistSongs)){
            foreach($artistSongs as $song){
                $songArr[] = $song['song_id'];
            }
        }
        return $songArr;
    }
    
    
    public function saveArtist($data){
        $this->artist_name = $data['artist_name'];
        $this->artist_description = $data['artist_description'];
        $this->artist_image = $data['file_name'];
        $this->date_created = date(getConstant('DATETIME_DB_FORMAT'));
        $this->save();
        $artistId = $this->id;
        if($artistId){
        $this->saveArtistSongs($artistId, $data['songs']);
        return TRUE;
        }else{
            return FALSE;
        }
    }
    
    public function saveArtistSongs($artistId,$data){
        if(!empty($data)){
            foreach($data as $song){
                $artist_song = new Artists_song();
                $artist_song->artist_id = $artistId;
                $artist_song->song_id = $song;
                $artist_song->save();
            }
        }
    }
    
    
    public function updateArtist($data,$where,$songs,$artistId){
        $artist_song = new Artists_song();
        $artist_song->where('artist_id',$artistId)->delete();
        $this->saveArtistSongs($artistId,$songs);
        return $this->where($where)->update($data);
    }
    
    public function deleteArtist($where){
        $update = array(
            'artist_status' => getConstant('STATUS_DELETED')
        );
        return $this->where($where)->update($update);
    }
}
