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
            <a href="{{URL::to('/')}}/admin/songs/add"><button type="button" class="btn btn-danger d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Create New</button></a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{$pageTitle}} Listing</h4>

                <div class="table-responsive m-t-40">
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
                    <table id="myTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Song Name</th>
                                <th>Song File</th>
                                <th>New Song</th>
                                <th>Featured Song</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @if(!empty($songs))
                                @foreach($songs as $song)
                                <td>{{$song['song_name']}}</td>
                                <td><audio controls><source src="{{URL::to('/')}}/public/uploads/songs/{{$song['song_file']}}" type="audio/ogg"><source src="horse.mp3" type="audio/mpeg">Your browser does not support the audio tag.</audio></td>
                                <td><span song_id="{{$song['id']}}" title="{{$song['is_new'] == '1'?'Remove from New Songs List.':'Add To New Songs List.'}}" class="cursor_pointer badge badge-{{$song['is_new'] == '1'?'info':'danger'}} mark_new" >{{$song['is_new'] == '1'?'Yes':'No'}}</span></td>
                                <td><span song_id="{{$song['id']}}" title="{{$song['is_featured'] == '1'?'Remove from Featured Songs List.':'Add To Featured Songs List.'}}" class="cursor_pointer badge badge-{{$song['is_featured'] == '1'?'info':'danger'}} mark_featured">{{$song['is_featured'] == '1'?'Yes':'No'}}</span></td>
                                <td><span class="badge badge-success"><strong>{{$song['status']}}</strong></span></td>
                                <td style="width:100px;">
                                    <a class="btn" href="{{URL::to('/')}}/admin/songs/edit/{{$song['id']}}"><i class="fas fa-edit text-info"></i></a>
                                    <a class="btn" href="{{URL::to('/')}}/admin/songs/delete/{{$song['id']}}"><i class="fas fa-trash-alt text-danger"></i></a>
                                </td>
                            </tr>
                            @endforeach

                            @else
                            <tr>
                                <td colspan="6" class="text-center">No Record Found.</td>
                            </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div> 
    </div>
</div>
@endsection