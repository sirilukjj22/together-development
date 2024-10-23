@extends('layouts.masterLayout')

@section('content')
    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <small class="text-muted">Welcome to Log Billing Folio.</small>
                    <div class=""><span class="span1"> Log Billing Folio (ประวัติการจัดทำเอกสาร)</span></div>
                </div>
            </div> <!-- .row end -->
        </div>
    </div>

    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <ul class="nav nav-tabs px-3 border-bottom-0" role="tablist">
                        <li class="nav-item" id="nav1"><a class="nav-link active" data-bs-toggle="tab" href="#nav-PDF" role="tab" onclick="nav($id='nav1')">Log PDF</a></li>{{--ประวัติการแก้ไข--}}
                        <li class="nav-item" id="nav2"><a class="nav-link " data-bs-toggle="tab" href="#nav-Log" onclick="nav($id='nav2')" role="tab">Log</a></li>{{--QUOTAION--}}
                    </ul>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade  show active" id="nav-PDF" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" class="mt-2">
                                        <caption class="caption-top">
                                            <div class="flex-end-g2">
                                                <label class="entriespage-label">entries per page :</label>
                                                <select class="entriespage-button" id="search-per-page-proposalLog" onchange="getPageLog(1, this.value, 'proposalLog')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                    <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "proposalLog" ? 'selected' : '' }}>10</option>
                                                    <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "proposalLog" ? 'selected' : '' }}>25</option>
                                                    <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "proposalLog" ? 'selected' : '' }}>50</option>
                                                    <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "proposalLog" ? 'selected' : '' }}>100</option>
                                                </select>
                                                <input class="search-button search-data-proposalLog" id="proposalLog" style="text-align:left;" placeholder="Search" />
                                            </div>
                                        </caption>
                                        <table id="proposalLogTable" class="example ui striped table nowrap unstackable hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th data-priority="1">Quotation ID</th>
                                                    <th class="text-center"data-priority="1">Quotation Type</th>
                                                    <th class="text-center">Correct No</th>
                                                    <th class="text-center">Created Date</th>
                                                    <th class="text-center">Export</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($log))
                                                    @foreach ($log as $key => $item)
                                                    <tr>
                                                        <td class="text-center">{{ $key + 1 }}</td>
                                                        <td>{{ $item->Quotation_ID }}</td>
                                                        <td class="text-center">{{ $item->QuotationType }}</td>
                                                        <td class="text-center">{{ $item->correct}}</td>
                                                        <td class="text-center">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                                                        <td class="text-center">
                                                            @if ($item->correct == $correct)
                                                                @if ($correct == 0)
                                                                    <a href="{{ asset($path.$item->Quotation_ID.".pdf") }}" type="button" class="btn btn-outline-dark rounded-pill lift" target="_blank" data-toggle="tooltip" data-placement="top" title="พิมพ์เอกสาร">
                                                                        <i class="fa fa-print"></i>
                                                                    </a>
                                                                @else
                                                                    <a href="{{ asset($path.$item->Quotation_ID.'-'.$correct.".pdf") }}" type="button" class="btn btn-outline-dark rounded-pill lift" target="_blank" data-toggle="tooltip" data-placement="top" title="พิมพ์เอกสาร">
                                                                        <i class="fa fa-print"></i>
                                                                    </a>
                                                                @endif
                                                            @else
                                                                <a href="{{ asset($path.$item->Quotation_ID.".pdf") }}" type="button" class="btn btn-outline-dark rounded-pill lift" target="_blank" data-toggle="tooltip" data-placement="top" title="พิมพ์เอกสาร">
                                                                    <i class="fa fa-print"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                        <input type="hidden" id="profile-proposalLog" name="profile-proposalLog" value="{{$Receipt_ID}}">
                                        <input type="hidden" id="get-total-proposalLog" value="{{ $log->total() }}">
                                        <input type="hidden" id="currentPage-proposalLog" value="1">
                                        <caption class="caption-bottom">
                                            <div class="md-flex-bt-i-c">
                                                <p class="py2" id="proposalLog-showingEntries">{{ showingEntriesTableLog($log, 'proposalLog') }}</p>
                                                <div id="proposalLog-paginate">
                                                    {!! paginateTableLog($log, 'proposalLog') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                                </div>
                                            </div>
                                        </caption>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-Log" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" class="mt-2">
                                        <caption class="caption-top">
                                            <div class="flex-end-g2">
                                                <label class="entriespage-label">entries per page :</label>
                                                <select class="entriespage-button" id="search-per-page-proposal-Log" onchange="getPageLogDoc(1, this.value, 'proposal-Log')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                    <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "proposal-Log" ? 'selected' : '' }}>10</option>
                                                    <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "proposal-Log" ? 'selected' : '' }}>25</option>
                                                    <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "proposal-Log" ? 'selected' : '' }}>50</option>
                                                    <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "proposal-Log" ? 'selected' : '' }}>100</option>
                                                </select>
                                                <input class="search-button search-data-proposal-Log" id="proposal-Log" style="text-align:left;" placeholder="Search" />
                                            </div>
                                        </caption>
                                        <table id="proposal-LogTable" class="example ui striped table nowrap unstackable hover">
                                            <thead>
                                                <tr>
                                                    <th  class="text-center">No</th>
                                                    <th >Category</th>
                                                    <th  class="text-center">Type</th>
                                                    <th  class="text-center">Created_by</th>
                                                    <th  class="text-center">Created Date</th>
                                                    <th  class="text-center">Content</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($logReceipt))
                                                    @foreach($logReceipt as $key => $item)
                                                    <tr>
                                                        <td style="text-align: center;">{{$key +1 }}</td>
                                                        <td style="text-align: left;">{{$item->Category}}</td>
                                                        <td style="text-align: center;">{{$item->type}}</td>
                                                        <td style="text-align: center;">{{@$item->userOperated->name}}</td>
                                                        <td style="text-align: center;">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                                                        @php
                                                            // แยกข้อมูล content ออกเป็น array
                                                            $contentArray = explode('+', $item->content);
                                                        @endphp
                                                        <td style="text-align: left;">

                                                            <b style="color:#0000FF ">{{$item->Category}}</b>
                                                            @foreach($contentArray as $contentItem)
                                                                <div>{{ $contentItem }}</div>
                                                            @endforeach
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                        <input type="hidden" id="profile-proposal-Log" name="profile-proposal" value="{{$Receipt_ID}}">
                                        <input type="hidden" id="get-total-proposal-Log" value="{{ $logReceipt->total() }}">
                                        <input type="hidden" id="currentPage-proposal-Log" value="1">
                                        <caption class="caption-bottom">
                                            <div class="md-flex-bt-i-c">
                                                <p class="py2" id="proposal-Log-showingEntries">{{ showingEntriesTableLogDoc($logReceipt, 'proposal-Log') }}</p>
                                                    <div id="proposal-Log-paginate">
                                                        {!! paginateTableLogDoc($logReceipt, 'proposal-Log') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                                    </div>
                                            </div>
                                        </caption>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row mt-5">
                                <div class="col-4"></div>
                                <div class="col-4 "  style="display:flex; justify-content:center; align-items:center;">
                                    <button type="button" class="btn btn-secondary lift btn_modal btn-space" onclick="window.location.href='{{ route('BillingFolio.index') }}'">
                                        Back
                                    </button>
                                </div>
                                <div class="col-4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- dataTable -->
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.semanticui.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.semanticui.js"></script>
    <script type="text/javascript" src="{{ asset('assets/helper/searchTableBilling.js')}}"></script>
    <script>
        const table_name = ['proposalLogTable','proposal-LogTable'];
            $(document).ready(function() {
                for (let index = 0; index < table_name.length; index++) {
                    console.log();

                    new DataTable('#'+table_name[index], {
                        searching: false,
                        paging: false,
                        info: false,
                        columnDefs: [{
                            className: 'dtr-control',
                            orderable: true,
                            target: null,
                        }],
                        order: [0, 'asc'],
                        responsive: {
                            details: {
                                type: 'column',
                                target: 'tr'
                            }
                        }
                    });
                }
            });
        function nav(id) {
            for (let index = 0; index < table_name.length; index++) {
                $('#'+table_name[index]).DataTable().destroy();
                new DataTable('#'+table_name[index], {
                    searching: false,
                    paging: false,
                    info: false,
                    columnDefs: [{
                        className: 'dtr-control',
                        orderable: true,
                        target: null,
                    }],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    }
                });
            }
        }
        $(document).on('keyup', '.search-data-proposalLog', function () {
            var id = $(this).attr('id');
            var search_value = $(this).val();
            var table_name = id+'Table';
            var guest_profile = $('#profile-proposalLog').val();
            var type_status = $('#status').val();
            var total = parseInt($('#get-total-'+id).val());
            var getUrl = window.location.pathname;
            console.log(id);


                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                    url: '/billing-Log-search-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        search_value: search_value,
                        table_name: table_name,
                        guest_profile: guest_profile,
                        status: type_status,
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                },
                    "initComplete": function (settings,json){

                        if ($('#'+id+'Table .dataTable_empty').length == 0) {
                            var count = $('#'+id+'Table tr').length - 1;
                        }else{
                            var count = 0;
                        }
                        if (search_value == '') {
                            count_total = total;
                        }else{
                            count_total = count;
                        }
                        $('#'+id+'-paginate').children().remove().end();
                        $('#'+id+'-showingEntries').text(showingEntriesSearchLog(1,count_total, id));
                        $('#'+id+'-paginate').append(paginateSearchLog(count_total, id, getUrl));
                    },
                    columnDefs: [
                                { targets: [0, 2, 3,4,5], className: 'dt-center td-content-center' },
                    ],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    },
                    columns: [
                        { data: 'id', "render": function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                        { data: 'Quotation_ID' },
                        { data: 'type' },
                        { data: 'Correct' },
                        { data: 'created_at' },
                        { data: 'Export' },
                    ],
                });
            document.getElementById(id).focus();
        });
        $(document).on('keyup', '.search-data-proposal-Log', function () {
            var id = $(this).attr('id');
            var search_value = $(this).val();
            var table_name = id+'Table';
            var guest_profile = $('#profile-proposal-Log').val();
            var type_status = $('#status').val();
            var total = parseInt($('#get-total-'+id).val());
            var getUrl = window.location.pathname;
            console.log(guest_profile);


                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                    url: '/billing-LogDoc-search-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        search_value: search_value,
                        table_name: table_name,
                        guest_profile: guest_profile,
                        status: type_status,
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                },
                    "initComplete": function (settings,json){

                        if ($('#'+id+'Table .dataTable_empty').length == 0) {
                            var count = $('#'+id+'Table tr').length - 1;
                        }else{
                            var count = 0;
                        }
                        if (search_value == '') {
                            count_total = total;
                        }else{
                            count_total = count;
                        }
                        $('#'+id+'-paginate').children().remove().end();
                        $('#'+id+'-showingEntries').text(showingEntriesSearchLogDoc(1,count_total, id));
                        $('#'+id+'-paginate').append(paginateSearchLogDoc(count_total, id, getUrl));
                    },
                    columnDefs: [
                                { targets: [0, 2, 3,4], className: 'dt-center td-content-center' },
                    ],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    },
                    columns: [
                        { data: 'id', "render": function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                        { data: 'Category' },
                        { data: 'type' },
                        { data: 'Created_by' },
                        { data: 'created_at' },
                        { data: 'Content' },
                    ],

                });
            document.getElementById(id).focus();
        });
    </script>

@endsection
