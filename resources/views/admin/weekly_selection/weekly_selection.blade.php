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
                <h4 class="card-title">Weekly Selection</h4>
                <form class="form" method="POST" enctype="multipart/form-data" action="{{URL::to('/')}}/admin/weekly_selection/update">
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
                    
                    <input type="hidden" name="id" value="{{$weekly_selection?$weekly_selection->id:''}}">
                    <div class="form-group m-t-40 row">
                        <div class="col-md-6">
                            <label for="example-text-input" class="col-12 col-form-label">Title</label>
                            <div class="col-12">
                                <input class="form-control" value="{{$weekly_selection?$weekly_selection->title:''}}" type="text" name="title" id="example-text-input" placeholder="Title">
                                @error('title')
                                <div class="text-danger"><strong>{{ $message }}</strong></div>
                                @enderror
                            </div>  
                        </div>
                        <div class="col-md-6">
                            <label for="example-text-input" class="col-12 col-form-label">Weekly Selection Link</label>
                            <div class="col-12">
                                <input class="form-control" value="{{$weekly_selection?$weekly_selection->link:''}}" type="text" name="link" id="example-text-input" placeholder="Link">
                                @error('link')
                                <div class="text-danger"><strong>{{ $message }}</strong></div>
                                @enderror
                            </div>  
                        </div>
                        
                    </div>
                    
                    <div class="form-group row ml-1">
                        
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-danger waves-effect waves-light m-r-10">Submit</button>
                            
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection