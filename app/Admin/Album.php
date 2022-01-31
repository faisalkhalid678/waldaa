<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;
use App\Admin\Album_song;
use App\Admin\Album_artist;
use App\Admin\Artist;
use App\Admin\Artists_song;
use App\Admin\Song;
use DB;

class Album extends Model {

    public $timestamps = false;

    public function getAllAlbums($where) {
        return $this->where($where)->get()->toArray();
    }

    public function getAlbumArtists($albumId) {
        $albums = DB::table('album_artists')
                        ->join('artists', 'artists.id', '=', 'album_artists.artist_id')
                        ->select('artists.*', 'album_artists.album_id')
                        ->where('album_artists.album_id', $albumId)
                        ->get()->toArray();
        return $albums;
    }

    public function getAlbumArtistSongs($albumId, $artistId) {
        $albums = DB::table('album_songs')
                        ->join('artists', 'artists.id', '=', 'album_songs.artist_id')
                        ->join('albums', 'albums.id', '=', 'album_songs.album_id')
                        ->join('songs', 'songs.id', '=', 'album_songs.song_id')
                        ->select('songs.*', 'album_songs.album_id', 'album_songs.artist_id', 'artists.artist_name', 'artists.artist_description', 'artists.artist_image', 'albums.album_name', 'albums.album_description', 'albums.album_image')
                        ->where('album_songs.album_id', $albumId)
                        ->where('album_songs.artist_id', $artistId)
                        ->get()->toArray();
        return $albums;
    }

    public function getAllAlbumsData() {
        $albums = DB::table('albums')
                        ->join('artists', 'artists.id', '=', 'albums.artist_id')
                        ->select('albums.*', 'artists.artist_name')
                        ->get()->toArray();
        return $albums;
    }

    public function getAlbumDetail($where) {
        return $this->where($where)->first();
    }

    public function getAlbumSongsArr($where) {

        $albumArtist = new Album_artist();
        $albumSong = new Album_song();
        $albumArtists = $albumArtist->where($where)->get()->toArray();

        $albumArtistSongsArray = array();
        if (!empty($albumArtists)) {
            foreach ($albumArtists as $artistData) {

                $artist = new Artist();
                $artistDataArr = $artist->where(['artist_status' => getConstant('STATUS_ACTIVE'), 'id' => $artistData['artist_id']])->first();

                $artistDataArrD = array();
                if ($artistDataArr) {
                    $artistDataArrD = $artistDataArr->toArray();
                    
                    $where['artist_id'] = $artistData['artist_id'];
                    $albumSongs = $albumSong->where($where)->get()->toArray();
                    $songArr = array();
                    if (!empty($albumSongs)) {
                        foreach ($albumSongs as $song) {
                            $songArr[] = $song['song_id'];
                        }
                    }
                    //$artistDataArr['ArtistSongs'] = $songArr;
                    $ArtistAllSongs = $artist->getArtistSongsArr(array('artist_id' => $artistData['artist_id']));
                    $artistAllSongsArray = array();
                    if (!empty($ArtistAllSongs)) {
                        foreach ($ArtistAllSongs as $as) {
                            $song = new Song();
                            $artistAllSongsData = $song->getAllSongs(array('id' => $as));
                            if (!empty($artistAllSongsData)) {
                                foreach ($artistAllSongsData as $aasd) {
                                    $songsDataArr['song_id'] = $aasd['id'];
                                    $songsDataArr['song_name'] = $aasd['song_name'];
                                    $songsDataArr['is_selected'] = in_array($aasd['id'], $songArr) ? "selected" : "";
                                    $artistAllSongsArray[] = $songsDataArr;
                                }
                            }
                        }
                    }
                    
                    $artistDataArrD['songs'] = $artistAllSongsArray;
                }
                $albumArtistSongsArray[] = $artistDataArrD;
            }
        }
        return $albumArtistSongsArray;
    }

    public function saveAlbum($data) {
        $this->album_name = $data['album_name'];
        $this->album_description = $data['album_description'];
        $this->album_image = $data['file_name'];
        $this->date_created = date(getConstant('DATETIME_DB_FORMAT'));
        $this->save();
        $albumId = $this->id;
        if ($albumId) {
            $this->saveAlbumArtistsAndSongs($albumId, $data['songs']);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function saveAlbumArtistsAndSongs($albumId, $data) {
        if (!empty($data)) {
            foreach ($data as $key => $songData) {
                $albumArtist = new Album_artist();
                $albumArtist->album_id = $albumId;
                $albumArtist->artist_id = $key;
                $albumArtist->save();
                if (!empty($songData)) {
                    foreach ($songData as $song) {
                        $album_song = new Album_song();
                        $album_song->album_id = $albumId;
                        $album_song->artist_id = $key;
                        $album_song->song_id = $song;
                        $album_song->save();
                    }
                }
            }
        }
        return True;
    }

    public function getNumOfSongs($albumId, $artistId) {
        $album_song = new Album_song();
        return $noOfSongs = $album_song->where(['album_id' => $albumId, 'artist_id' => $artistId])->count();
    }

    public function updateAlbum($data, $where, $songs, $albumId) {
        $album_song = new Album_song();
        $album_artist = new Album_artist();
        $album_artist->where(['album_id' => $albumId])->delete();
        $album_song->where(['album_id' => $albumId])->delete();
        $this->saveAlbumArtistsAndSongs($albumId, $songs);
        $this->where($where)->update($data);
        return True;
    }

    public function deleteAlbum($where) {
        $update = array(
            'album_status' => getConstant('STATUS_DELETED')
        );
        return $this->where($where)->update($update);
    }

}
