@extends('layouts.master')

@section('title')
    Edit News
@endsection

@section('extra-css')
    <link rel="stylesheet" href="/assets/plugins/sweetalert/sweetalert.css">
    <link rel="stylesheet" href="/assets/plugins/morrisjs/morris.css">
    <link rel="stylesheet" href="/assets/plugins/bootstrap-select/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="/assets/plugins/dropzone/min/dropzone.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
          integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

@endsection

@section('content')
    <div class="container-fluid">
        <div class="block-header">
            <h2>
                News
            </h2>
        </div>
        <!-- Basic Validation -->

        <div class="row">
            <div style="padding-left: 0px; padding-right: 0px" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>EDIT NEWS</h2>
                    </div>
                    <div class="body">
                        <form name="form_news">
                                <input type="hidden" value="{{$news->id}}" name="news_id">
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <b>Title</b>
                                    <div class="input-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control date" name="title" placeholder="Enter title" onkeyup="validateTitle(this)" value="{{$news->title}}">
                                        </div>
                                        <label></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <b>Description</b>
                                    <div class="input-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control date" name="description" placeholder="Enter description" onkeyup="validateDescription(this)" value="{{$news->description}}">
                                        </div>
                                        <label></label>
                                    </div>
                                </div>
                            </div>
                            <h2 class="card-inside-title">Content</h2>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <div class="form-line">
                                            <textarea name="content" id="ckeditor">
                                                {{$news->content}}
                                            </textarea>
                                        </div>
                                        <label></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <b>Source</b>
                                    <div class="input-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control date" name="source" placeholder="Enter source" onkeyup="validateSource(this)" value="{{$news->source}}">
                                        </div>
                                        <label></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <b>Author</b>
                                    <div class="input-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control date" name="author" placeholder="Enter source" onkeyup="validateAuthor(this)" value="{{$news->author}}">
                                        </div>
                                        <label></label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <b>Category</b>
                                    <div class="input-group">
                                        <div id="category">
                                            <select name="category" class="form-control show-tick" onchange="validateCategory(this)">
                                                <option value="">-- Please select --</option>
                                                @if(! empty($categories))
                                                    @foreach($categories as $category)
                                                        <option value="{{$category->id}}" {{($news->category_id == $category->id) ? 'selected' : ''}}>
                                                            {{$category->name}}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <label></label>
                                    </div>
                                </div>
                            </div>
                            <h2 class="card-inside-title">Image</h2>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <div id="frm-file-upload" class="dropzone custom-dropzone" action='https://api.cloudinary.com/v1_1/fpt-aptech/image/upload' enctype='multipart/form-data'>
                                            <div class="dz-message">
                                                <div class="drag-icon-cph">
                                                    <i style="font-size: 80px; color: #777"
                                                       class="fas fa-hand-point-up"></i>
                                                </div>
                                                <h3>Drop image here or click to upload</h3>
                                            </div>
                                            <div class="fallback">
                                                <input type="hidden" name="upload_preset" value="b3uy9rh5">
                                            </div>
                                        </div>
                                        <label></label>
                                    </div>
                                </div>
                            </div>
                            <div id="url-images" style="display: none;">
                                @if(! empty($news) && $news->img)
                                    <input type="hidden" class="urlImg edit-img" name="img" value="{{$news->img}}">
                                @endif
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-5"></div>
                                <div class="col-sm-2">
                                    <button class="btn btn-block btn-primary waves-effect" id="edit-btn-submit" type='button'>
                                        SUBMIT
                                    </button>
                                </div>
                                <div class="col-sm-5"></div>
                            </div>
                        </form>
                        {{--<form id="form_news" name="form_news" method="POST" action="/news/edit/{{$news->id}}">--}}
                            {{--<input type="hidden" name="_token" value="{{csrf_token()}}">--}}
                            {{--<div class="form-group form-float">--}}
                                {{--<div class="form-line">--}}
                                    {{--<input type="text" class="form-control" name="title" value="{{$news->title}}" required>--}}
                                    {{--<label class="form-label">Title</label>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="form-group form-float">--}}
                                {{--<div class="form-line">--}}
                                    {{--<input type="text" class="form-control" name="description" value="{{$news->description}}" required>--}}
                                    {{--<label class="form-label">Description</label>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="form-group form-float">--}}
                                {{--<div class="form-line">--}}
                                    {{--<label class="">Content</label>--}}
                                    {{--<textarea class="form-control" id="ckeditor" name="content" required>--}}
                                        {{--{{$news->content}}--}}
                                    {{--</textarea>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="form-group form-float">--}}
                                {{--<div class="form-line">--}}
                                    {{--<input type="text" class="form-control" name="source" value="{{$news->source}}" required>--}}
                                    {{--<label class="form-label">Source</label>--}}
                                {{--</div>--}}
                            {{--</div>--}}


                            {{--<div class="row">--}}
                                {{--<div class="col-sm-6">--}}
                                    {{--<div class="form-group form-float">--}}
                                        {{--<div class="form-line">--}}
                                            {{--<input type="text" class="form-control" name="author" value="{{$news->author}}" required>--}}
                                            {{--<label class="form-label">Author</label>--}}
                                        {{--</div>--}}

                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="col-sm-6">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<div class="form-inline">--}}
                                            {{--<select class="form-control show-tick" name="category_id" >--}}
                                                {{--<option value="">Category</option>--}}
                                                {{--@if(! empty($categories))--}}
                                                    {{--@foreach($categories as $category)--}}
                                                        {{--<option value="{{$category->id}}" {{($news->category_id == $category->id) ? 'selected' : ''}}>--}}
                                                            {{--{{$category->name}}--}}
                                                        {{--</option>--}}
                                                    {{--@endforeach--}}
                                                {{--@endif--}}
                                            {{--</select>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}

                                {{--</div>--}}

                            {{--</div>--}}

                            {{--<div class="row">--}}
                                {{--<div class="col-sm-6">--}}
                                    {{--<div class="form-group form-float">--}}
                                        {{--<div class="form-line">--}}
                                            {{--<input type="file" class="form-control" name="img" value="{{$news->img}}" required>--}}

                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="row clearfix">--}}
                                {{--<div class="col-sm-12">--}}
                                    {{--<div class="input-group">--}}
                                        {{--<div class="dropzone custom-dropzone dz-clickable" id="frm-file-upload"--}}
                                             {{--action="https://api.cloudinary.com/v1_1/dqbat91l8/upload">--}}
                                            {{--<div class="dz-message">--}}
                                                {{--<div class="drag-icon-cph">--}}
                                                    {{--<i style="font-size: 80px; color: #777"--}}
                                                       {{--class="fas fa-hand-point-up"></i>--}}
                                                    {{--<h3>Drop image here or click to upload.</h3>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            {{--<div class="fallback">--}}
                                                {{--<input type="file" name="myImg" multiple="">--}}
                                                {{--<input type="hidden" name='upload_preset' value='b3uy9rh5'>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="row">--}}
                                {{--<div class="col-sm-6">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<div class="" id="url-images">--}}
                                            {{--@if(! empty($news) && $news->img)--}}
                                                {{--<input type="hidden" name="img" class="urlImg edit-img" value="{{$news->img}}" required>--}}
                                            {{--@endif--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<div style="display: none;" id="url-images">--}}
                                {{--@if(! empty($news) && $news->img)--}}
                                    {{--<input type="hidden" name="img" class="urlImg edit-img" value="{{$news->img}}">--}}
                                {{--@endif--}}
                            {{--</div>--}}

                            {{--<div class="row clearfix">--}}
                                {{--<div class="col-lg-offset-6 col-md-offset-6 col-sm-offset-6 col-xs-offset-5">--}}
                                    {{--<button type="button" class="btn btn-primary m-t-15 waves-effect" id="btn-submit">Submit</button>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                        {{--</form>--}}

                    </div>
                </div>
            </div>
        </div>
        <!-- #END# Basic Validation -->
    </div>
@endsection

@section('extra-script')
    <script type="text/javascript" src="https://unpkg.com/xregexp@4.2.4/xregexp-all.js"></script>
    <script type="text/javascript" src="/assets/plugins/bootstrap-notify/bootstrap-notify.min.js"></script>
    <script type="text/javascript" src="/assets/plugins/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="/assets/plugins/tinymce/tinymce.min.js"></script>
    {{--<script type="text/javascript" src="/assets/js/pages/forms/editors.js"></script>--}}
    <script type="text/javascript" src="/assets/plugins/dropzone/dropzone.min.js"></script>
    <script type="text/javascript" src="/assets/js/pages/news/edit.js"></script>

    <script type="text/javascript" src="/assets/plugins/jquery-validation/jquery.validate.min.js"></script>
    <script type="text/javascript" src="/assets/plugins/jquery-steps/jquery.steps.min.js"></script>
    <script type="text/javascript" src="/assets/plugins/sweetalert/sweetalert.min.js"></script>
    <script type="text/javascript" src="/assets/js/pages/forms/form-validation.js"></script>
@endsection