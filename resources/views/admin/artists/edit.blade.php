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
                <h4 class="card-title">Edit Artist</h4>
                <form class="form" method="POST" enctype="multipart/form-data" action="{{URL::to('/')}}/admin/artists/update/{{$artistDetail->id}}">
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
                            <label for="example-text-input" class="col-12 col-form-label">Artist Name</label>
                            <div class="col-12">
                                <input class="form-control" value="{{$artistDetail->artist_name}}" type="text" name="artist_name" id="example-text-input" placeholder="Artist Name">
                                @error('artist_name')
                                <div class="text-danger"><strong>{{ $message }}</strong></div>
                                @enderror
                            </div>  
                        </div>
                        <div class="col-md-6">
                            <label for="example-text-input" class="col-12 col-form-label">Artist Image</label>
                            <div class="col-12">
                                <input class="form-control" type="file" name="artist_image" id="example-text-input">
                                <div class="mt-3">
                                   <img width="65" src="{{URL::to('/')}}/public/uploads/artists/{{$artistDetail->artist_image}}" onerror="this.onerror=null;this.src='{{URL::to('/')}}/public/assets/images/users/placeholder.png';"> 
                                </div>
                                @error('artist_image')
                                <div class="text-danger"><strong>{{ $message }}</strong></div>
                                @enderror
                            </div> 
                        </div>
                    </div>
                    <div class="form-group m-t-20 row">
                        <div class="col-md-12">
                            <label for="example-text-input" class="col-12 col-form-label">Artist Description</label>
                        <div class="col-12">
                            <textarea class="form-control" rows="3" type="text" name="artist_description" id="example-text-input" placeholder="Artist Description">{{$artistDetail->artist_description}}</textarea>
                            @error('artist_description')
                            <div class="text-danger"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                        </div>
                    </div>
                    
                    <div class="row" style="margin-bottom: 40px;">
                        <div class="col">
                            <select multiple="multiple" size="10" name="songs[]" title="songs[]">
                                @if(!empty($songs))
                                @foreach($songs as $song)
                                <option {{ in_array($song['id'],$artistSongs)?"selected":""}} value="{{ $song['id'] }}">{{ $song['song_name']}}</option>
                                @endforeach
                                @endif
                            </select>

                        </div>
                    </div>
                    <div class="form-group row ml-1">
                        
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-danger waves-effect waves-light m-r-10">Submit</button>
                            <a href="{{URL::to('/')}}/admin/artists"><button type="button" class="btn btn-inverse waves-effect waves-light">Cancel</button></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection