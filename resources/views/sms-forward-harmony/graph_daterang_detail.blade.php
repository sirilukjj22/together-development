@extends('layouts.masterLayout')

@section('content')

    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class=""><span class="span1">Bank Transaction Revenue</span><span class="span2"> / Graph Detail</span></div>
                    <div class="span3">Graph Detail</div>
                </div>
                <div class="col-auto">
                    <a href="javascript:history.back(1)" type="button" class="btn btn-color-green text-white lift">Back</a>
                </div>
            </div> <!-- .row end -->
        </div>
    </div>

    <div id="content-index" class="body d-flex py-lg-4 py-2">
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <div class="container-graph" id="graphChartByMonthOrYear" style="grid-column: 1/3;">
                        <canvas id="revenueChartByMonthOrYear" style="max-height: 50vh;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="startdate" value="{{ $startdate }}">
    <input type="hidden" id="enddate" value="{{ $enddate }}">
    <input type="hidden" id="status" value="{{ $type }}">
    <input type="hidden" id="into_account" value="{{ $account }}">

    <!-- Moment Date -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- card graph -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript" src="{{ asset('assets/graph/graphDateRangDetail.js')}}"></script>

    <script>
        $(document).ready(function() {
            var startdate = $('#startdate').val();
            var enddate = $('#enddate').val();

            chartDateRang(startdate, enddate);
        });
    </script>
@endsection