@extends('layouts.master')

@section('title')
    Form Validation
@endsection

@section('extra-css')
    <link rel="stylesheet" href="/assets/plugins/sweetalert/sweetalert.css">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="block-header">
            <h2>
                News
            </h2>
        </div>
        <!-- Basic Validation -->
        <div class="row ">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>POST NEWS</h2>
                    </div>
                    <div class="body">
                        <form id="form_validation" method="POST">
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
                                    <label class="" >Content</label>
                                    <textarea class="form-control" id="summary-ckeditor" name="summary-ckeditor"></textarea>
                                </div>
                            </div>
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="source" required>
                                    <label class="form-label">Source</label>
                                </div>
                            </div>
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" class="form-control" name="author" required>
                                    <label class="form-label">Author</label>
                                </div>
                            </div>

                            <div class="form-group form-float">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="inputGroupSelect01">Category</label>
                                </div>
                                <select class="form-control show-tick">
                                    <option value="">-- Please select --</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="inputGroupSelect01">Image</label>
                                </div>
                                <div class="custom-file">

                                    <input type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">

                                </div>
                            </div>

                            <button class="btn btn-primary waves-effect" type="submit">SUBMIT</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- #END# Basic Validation -->
    </div>
@endsection

@section('extra-script')
    <script type="text/javascript" src="/js/app.js"></script>
    <script type="text/javascript" src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'summary-ckeditor' );
    </script>
    <script type="text/javascript" src="/assets/plugins/jquery-validation/jquery.validate.min.js"></script>
    <script type="text/javascript" src="/assets/plugins/jquery-steps/jquery.steps.min.js"></script>
    <script type="text/javascript" src="/assets/plugins/sweetalert/sweetalert.min.js"></script>
    <script type="text/javascript" src="/assets/js/pages/forms/form-validation.js"></script>
@endsection
