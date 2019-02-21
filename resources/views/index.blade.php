@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@section('extra-css')
    <link rel="stylesheet" href="/assets/plugins/morrisjs/morris.css">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="block-header">
            <h2>DASHBOARD</h2>
        </div>
    </div>
@endsection

@section('extra-script')
    <script type="text/javascript" src="/assets/plugins/jquery-countto/jquery.countTo.min.js"></script>
    <script type="text/javascript" src="/assets/plugins/raphael/raphael.min.js"></script>
    {{--<script type="text/javascript" src="/assets/plugins/morrisjs/morris.min.js"></script>--}}
    <script type="text/javascript" src="/assets/plugins/chartjs/Chart.bundle.min.js"></script>
    {{--<script type="text/javascript" src="/assets/plugins/flot-charts/jquery.flot.min.js"></script>--}}
    {{--<script type="text/javascript" src="/assets/plugins/flot-charts/jquery.flot.resize.js"></script>--}}
    {{--<script type="text/javascript" src="/assets/plugins/flot-charts/jquery.flot.pie.min.js"></script>--}}
    {{--<script type="text/javascript" src="/assets/plugins/flot-charts/jquery.flot.categories.min.js"></script>--}}
    {{--<script type="text/javascript" src="/assets/plugins/flot-charts/jquery.flot.time.min.js"></script>--}}
    <script type="text/javascript" src="/assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
    <script type="text/javascript" src="/assets/js/pages/index.js"></script>
@endsection
