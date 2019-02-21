@extends('layouts.master')

@section('title')
    List news
@endsection

@section('extra-css')
    <link rel="stylesheet" href="/assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="/assets/plugins/bootstrap-select/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="/assets/plugins/sweetalert/sweetalert.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
          integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
@endsection
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="header">
                <h2>
                    News
                </h2>
            </div>
            {{--<div class="row m-t-20">--}}
            {{--<div class="col-sm-5 col-xs-5 m-t-15" style='float: right'>--}}
            {{--<div class="form-group">--}}
            {{--<div class="form-line">--}}
            {{--<input type="text" id="search-input" class="form-control" placeholder='Search'>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-lg-1 col-sm-2 col-xs-1 m-t-15" style='float: right'>--}}
            {{--<button id="search-btn" class="btn btn-danger btn-circle waves-effect waves-circle waves-float"--}}
            {{--type="button">--}}
            {{--<i class="fas fa-search"></i>--}}
            {{--</button>--}}
            {{--</div>--}}
            {{--</div>--}}

            <div class="body">
                <div class="table-responsive">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="text-center">Image</th>
                                    <th class="text-center">Title</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-center">Source</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody class="text-center">
                                    @if(! empty($list))
                                        @foreach($list as $new)
                                            <tr>
                                                <th scope="row">{{$new->id}}</th>
                                                <th align="center">
                                                    <div style="background-image: url({{$new->img}});background-size: cover; height: 70px; width: 100px;"></div>
                                                </th>
                                                <td>{{$new->title}}
                                                </td>
                                                <td>{{$new->description}}
                                                </td>
                                                <td>{{$new->source}}</td>
                                                <td class="text-center js-sweetalert js-modal-buttons">
                                                    <a href="/news/{{$new->id}}/edit" class="btn btn-link waves-effect">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button class="btn btn-link waves-effect" type="button" data-type="ajax-loader" data-p="{{$new}}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row text-right">
                        {{$list}}
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
@section('extra-script')
    <script src="/assets/plugins/sweetalert/sweetalert.min.js"></script>
    <script src="/assets/plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="/assets/js/pages/news/list.js"></script>
@endsection
