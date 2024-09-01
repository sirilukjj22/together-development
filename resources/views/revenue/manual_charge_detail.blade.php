@extends('layouts.masterLayout')
@section('content')
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class=""><span class="span1">Daily Revenue by Type </span><span class="span2"> / {{ $title }}</span></div>
                    <div class="span3">{{ $title }}</div>
                </div>
                <div class="col-auto">
                    <a href="javascript:history.back(1)" type="button" class="btn btn-color-green text-white lift">ย้อนกลับ</a>
                </div>
            </div> <!-- .row end -->
        </div>
    </div>
    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <div class="card p-4 mb-4">
                        <caption class="caption-top">
                            <div>
                                <div class="flex-end-g2">
                                    <label class="entriespage-label">entries per page :</label>
                                    <select class="entriespage-button" id="search-per-page-manual" onchange="getPage(1, this.value, 'manual')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                        <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "manual" ? 'selected' : '' }}>10</option>
                                        <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "manual" ? 'selected' : '' }}>25</option>
                                        <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "manual" ? 'selected' : '' }}>50</option>
                                        <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "manual" ? 'selected' : '' }}>100</option>
                                    </select>
                                    <input class="search-button search-data" id="manual" style="text-align:left;" placeholder="Search" />
                                </div>
                        </caption>
                        <div style="min-height: 70vh;">
                            <table id="manualTable" class="example ui striped table nowrap unstackable hover">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;" data-priority="1">#</th>
                                        <th style="text-align: center;" data-priority="1">Date</th>
                                        <th style="text-align: center;">Stan</th>
                                        <th style="text-align: center;">Income type</th>
                                        <th style="text-align: center;">Credit Card Front Desk Charge</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_query as $key => $item)
                                        <tr style="text-align: center;">
                                            <td class="td-content-center">{{ $key + 1 }}</td>
                                            <td class="td-content-center">{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                            <td class="td-content-center">{{ $item->batch }}</td>
                                            <td class="td-content-center">
                                                @if ($item->status == 0)
                                                    -
                                                @elseif ($item->status == 1)
                                                    Credit Card Guest Deposit
                                                @elseif($item->status == 2)
                                                    Credit Card All Outlet
                                                @elseif($item->status == 3)
                                                    Credit Card Water Park
                                                @elseif($item->status == 4)
                                                    Credit Card Revenue
                                                @elseif($item->status == 5)
                                                    Agoda Bank Transfer Revenue
                                                @elseif($item->status == 6)
                                                    Front Desk Revenue
                                                @elseif($item->status == 7)
                                                    Credit Card Front Desk
                                                @elseif($item->status == 8)
                                                    Elexa EGAT Revenue
                                                @elseif($item->status == 9)
                                                    Other Revenue Bank Transfer
                                                @endif
                                            </td>
                                            <td class="td-content-center">{{ number_format($item->credit_amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <caption class="caption-bottom">
                            <div class="md-flex-bt-i-c">
                                <p class="py2" id="manual-showingEntries">{{ showingEntriesTable($data_query, 'manual') }}</p>
                                <div class="font-bold ">ยอดรวมทั้งหมด {{ number_format($total_query, 2) }} บาท</div>
                                    <div id="manual-paginate">
                                        {!! paginateTable($data_query, 'manual') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                    </div>
                            </div>
                        </caption>
                    </div> <!-- .card end -->
                </div>
            </div> <!-- .row end -->
        </div>
    </div>

    <input type="hidden" id="filter-by" name="filter_by" value="{{ $filter_by }}">
    <input type="hidden" id="input-search-day" name="day" value="{{ $day }}">
    <input type="hidden" id="input-search-month" name="month" value="{{ $month }}">
    <input type="hidden" id="input-search-month-to" name="month_to" value="{{ $month_to }}">
    <input type="hidden" id="input-search-year" name="year" value="{{ $year }}">
    <input type="hidden" id="status" value="{{ $status }}">
    <input type="time" id="time" name="time" value="<?php echo date('20:59:59'); ?>" hidden>
    <input type="hidden" id="get-total-manual" value="{{ $data_query->total() }}">
    <input type="hidden" id="currentPage-manual" value="1">

    @if (isset($_SERVER['HTTPS']) ? 'https' : 'http' == 'https')
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @else
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @endif

    <!-- table design css -->
    <link rel="stylesheet" href="{{ asset('assets/css/semantic.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.semanticui.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.semanticui.css') }}">

    <!-- table design js -->
    <script src="{{ asset('assets/js/semantic.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.semanticui.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.responsive.js') }}"></script>
    <script src="{{ asset('assets/js/responsive.semanticui.js') }}"></script>

    <!-- สำหรับค้นหาในส่วนของตาราง -->
    <script type="text/javascript" src="{{ asset('assets/helper/searchTableRevenue.js')}}"></script>

    <script>
        $(document).ready(function() {

            new DataTable('.example', {
                responsive: true,
                searching: false,
                paging: false,
                info: false,
                columnDefs: [{
                        className: 'dtr-control',
                        orderable: true,
                        target: null,
                    },
                    {
                        width: '7%',
                        targets: 0
                    },
                    {
                        width: '10%',
                        targets: 3
                    },
                    {
                        width: '15%',
                        targets: 4
                    }

                ],
                order: [0, 'asc'],
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr'
                    }
                }
            });
        });

        // Search
        $(document).on('keyup', '.search-data', function () {
            var id = $(this).attr('id');
            var search_value = $(this).val();
            var total = parseInt($('#get-total-'+id).val());
            var table_name = id+'Table';

            var filter_by = $('#filter-by').val();
            var day = $('#input-search-day').val();
            var month = $('#input-search-month').val();
            var year = $('#input-search-year').val();
            var month_to = $('#input-search-month-to').val();
            var type_status = $('#status').val();
            var getUrl = id;

            $('#'+table_name).DataTable().destroy();
            var table = $('#'+table_name).dataTable({
                searching: false,
                paging: false,
                info: false,
                ajax: {
                    url: '/revenue-search-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        search_value: search_value,
                        table_name: table_name,
                        filter_by: filter_by,
                        day: day,
                        month: month,
                        year: year,
                        month_to: month_to,
                        status: type_status,
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                },
                "initComplete": function (settings, json) {

                    if ($('#'+id+'Table .dataTables_empty').length == 0) {
                        var count = $('#'+id+'Table tr').length - 1;
                    } else {
                        var count = 0;
                        $('.dataTables_empty').addClass('dt-center');
                    }

                    if (search_value == '') {
                        count_total = total;
                    } else {
                        count_total = count;
                    }
                
                    $('#'+id+'-paginate').children().remove().end();
                    $('#'+id+'-showingEntries').text(showingEntriesSearch(1, count_total, id));
                    $('#'+id+'-paginate').append(paginateSearch(count_total, id, getUrl));

                },
                columnDefs: [
                            { targets: [0, 1, 2, 3, 4], className: 'dt-center td-content-center' },
                ],
                order: [0, 'asc'],
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr'
                    }
                },
                columns: [
                    { data: 'number' },
                    { data: 'date' },
                    { data: 'stan' },
                    { data: 'revenue_name' },
                    { data: 'amount' },
                ],

            });

            document.getElementById(id).focus();
        });
    </script>
@endsection
