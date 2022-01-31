@extends('admin/template')  

@section('contents')
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h4 class="text-themecolor">{{$pageTitle}}</h4>
    </div>
    <div class="col-md-7 align-self-center text-right">
        <div class="d-flex justify-content-end align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active">{{$pageTitle}}</li>
            </ol>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Album</h4>
                <form class="form" method="POST" enctype="multipart/form-data" action="{{URL::to('/')}}/admin/albums/update/{{$albumDetail->id}}">
                    @csrf
                    @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                    @elseif(session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session()->get('error') }}
                    </div>
                    @else
                    <div></div>
                    @endif
                    <div class="form-group m-t-40 row">
                        <div class="col-md-6">
                            <label for="example-text-input" class="col-12 col-form-label">Album Name</label>
                            <div class="col-12">
                                <input class="form-control" value="{{$albumDetail->album_name}}" type="text" name="album_name" id="example-text-input" placeholder="Album Name">
                                @error('album_name')
                                <div class="text-danger"><strong>{{ $message }}</strong></div>
                                @enderror
                            </div>  
                        </div>
                        <div class="col-md-6">
                            <label for="example-text-input" class="col-12 col-form-label">Album Image</label>
                            <div class="col-12">
                                <input class="form-control" type="file" name="album_image" id="example-text-input">
                                
                                @error('album_image')
                                <div class="text-danger"><strong>{{ $message }}</strong></div>
                                @enderror
                                
                                <div class="mt-2">
                                    <img width="65" src="{{URL::to('/')}}/public/uploads/albums/{{$albumDetail->album_image}}" onerror="this.onerror=null;this.src='{{URL::to('/')}}/public/assets/images/users/album_placeholder.png';">
                                </div>
                                
                            </div> 
                        </div>
                    </div>
                    <div class="form-group m-t-20 row">
                        <div class="col-md-12">
                            <label for="example-text-input" class="col-12 col-form-label">Artist Description</label>
                            <div class="col-12">
                                <textarea class="form-control" rows="3" type="text" name="album_description" id="example-text-input" placeholder="Album Description">{{$albumDetail->album_description}}</textarea>
                                @error('album_description')
                                <div class="text-danger"><strong>{{ $message }}</strong></div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group m-t-20 row">
                       <div class="col-md-6">
                            <label for="example-text-input" class="col-12 col-form-label">Artist</label>
                            <div class="col-12">
                                <select class="form-control album_artist" name="artist_id">
                                    @if(!empty($artists))
                                    <option value="">Select Artist</option>
                                    @foreach($artists as $artist)
                                    <option value="{{ $artist['id'] }}">{{ $artist['artist_name']}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div> 
                        </div> 
                       
                    </div>

                    <div class="songsdiv">
                        
                        @if(!empty($albumArtistSongs))
                          @foreach($albumArtistSongs as $artist)
                          <div class="row artist_songs artist{{$artist['id']}}" style="margin-bottom: 40px;">
                            <div class="col">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h3 class="artist_name{{$artist['id']}}">{{$artist['artist_name']}}</h3>
                                    </div>
                                    <div class="col-md-6">
                                        <h4 class="text-danger remove_artist" style="cursor: pointer;">Remove From Album</h4>
                                    </div>
                                </div>
                                    
                                <select class="songs_dropdown{{$artist['id']}}" multiple="multiple" size="10" name="songs[{{$artist['id']}}][]" title="songs[]">
                                    @if(!empty($artist['songs']))
                                    @foreach($artist['songs'] as $song)
                                    <option {{$song['is_selected']}} value="{{$song['song_id']}}">{{$song['song_name']}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                          @endforeach
                        @endif
                    </div>

                    <div class="form-group row ml-1">

                        <div class="col-md-12">
                            <button type="submit" class="btn btn-danger waves-effect waves-light m-r-10">Submit</button>
                            <a href="{{URL::to('/')}}/admin/albums"><button type="button" class="btn btn-inverse waves-effect waves-light">Cancel</button></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection