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
                <h4 class="card-title">Add Category</h4>
                <form class="form" method="POST" action="{{URL::to('/')}}/admin/categories/store">
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
                        <label for="example-text-input" class="col-2 col-form-label">Category Name</label>
                        <div class="col-10">
                            <input class="form-control" type="text" name="category_name" id="example-text-input" placeholder="Category Name">
                            @error('category_name')
                            <div class="text-danger"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-2"></div>
                        <div class="col-10">
                            <button type="submit" class="btn btn-danger waves-effect waves-light m-r-10">Submit</button>
                            <a href="{{URL::to('/')}}/admin/categories"><button type="button" class="btn btn-inverse waves-effect waves-light">Cancel</button></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection