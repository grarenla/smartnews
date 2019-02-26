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
            @if(! empty($list) && count($list->getCollection()) > 0)

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
                                                <a href="/news/edit/{{$new->id}}" class="btn btn-link waves-effect">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-link waves-effect" type="button"
                                                        data-type="ajax-loader" data-p="{{$new}}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row text-right">
                            {{$list}}
                        </div>
                    </div>
                </div>
            @else
                <div class="container">
                    <div class="col-md-2"></div>
                    <div class="col-md-6">
                        <img src='http://www.idealexchangepawn.com/images/noProductsFound.png' style='width :100%'
                             alt="">
                        <h1></h1>
                        <div style="text-align: center;">
                            <a href="" class="btn btn-default btn-lg waves-effect">GO TO LIST PRODUCTS</a>
                        </div>
                        <h1></h1>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection
@section('extra-script')
    <script src="/assets/plugins/sweetalert/sweetalert.min.js"></script>
    <script src="/assets/plugins/bootstrap-notify/bootstrap-notify.js"></script>
    <script src="/assets/js/pages/news/list.js"></script>
@endsection
