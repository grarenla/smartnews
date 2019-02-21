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

                        <form id="form_validation" method="POST" action="/dashboard/news/edit">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="title" required>
                                    <label class="form-label">Title</label>
                                </div>
                            </div>
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="description" required>
                                    <label class="form-label">Description</label>
                                </div>
                            </div>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <label class="">Content</label>
                                    <textarea class="form-control" id="ckeditor" name="content" required>

                                    </textarea>
                                </div>
                            </div>

                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="source" required>
                                    <label class="form-label">Source</label>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="author" required>
                                            <label class="form-label">Author</label>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="form-inline">
                                            <select class="form-control show-tick" name="category_id" >
                                                <option value="">Category</option>
                                                @if(! empty($categories))
                                                    @foreach($categories as $category)
                                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="file" class="form-control" name="img" required>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{--<div class="row clearfix">--}}
                            {{--<div class="col-sm-12">--}}
                            {{--<div class="input-group">--}}
                            {{--<div class="dropzone custom-dropzone dz-clickable" id="frm-file-upload" action="https://api.cloudinary.com/v1_1/dqbat91l8/upload">--}}
                            {{--<div class="dz-message">--}}
                            {{--<div class="drag-icon-cph">--}}
                            {{--<i style="font-size: 80px; color: #777" class="fas fa-hand-point-up"></i>--}}
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

                            <div class="row clearfix">
                                <div class="col-lg-offset-6 col-md-offset-6 col-sm-offset-6 col-xs-offset-5">
                                    <button type="submit" class="btn btn-primary m-t-15 waves-effect">Submit</button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!-- #END# Basic Validation -->
    </div>
@endsection

@section('extra-script')
    <script type="text/javascript" src="/assets/plugins/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="/assets/plugins/tinymce/tinymce.min.js"></script>
    <script type="text/javascript" src="/assets/js/pages/forms/editors.js"></script>
    <script type="text/javascript" src="/assets/plugins/dropzone/dropzone.min.js"></script>

    <script type="text/javascript" src="/assets/plugins/jquery-validation/jquery.validate.min.js"></script>
    <script type="text/javascript" src="/assets/plugins/jquery-steps/jquery.steps.min.js"></script>
    <script type="text/javascript" src="/assets/plugins/sweetalert/sweetalert.min.js"></script>
    <script type="text/javascript" src="/assets/js/pages/forms/form-validation.js"></script>
@endsection
