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
            <a href="{{URL::to('/')}}/admin/categories/add"><button type="button" class="btn btn-danger d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Create New</button></a>
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
                                <th>Category Name</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @if(!empty($categories))
                                @foreach($categories as $category)
                                <td>{{$category['category_name']}}</td>
                                <td><span class="badge badge-success"><strong>{{$category['status']}}</strong></span></td>
                                <td>
                                    <a class="btn" href="{{URL::to('/')}}/admin/categories/edit/{{$category['id']}}"><i class="fas fa-edit text-info"></i></a>
                                    <a class="btn" href="{{URL::to('/')}}/admin/categories/delete/{{$category['id']}}"><i class="fas fa-trash-alt text-danger"></i></a>
                                </td>
                            </tr>
                            @endforeach

                            @else
                            <tr>
                                <td colspan="3" class="text-center">No Record Found.</td>
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