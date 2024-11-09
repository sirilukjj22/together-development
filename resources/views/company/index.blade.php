@extends('layouts.masterLayout')

@section('content')
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Company / Agent</div>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-color-green lift btn_modal"  onclick="window.location.href='{{ route('Company.create') }}'">
                        <i class="fa fa-plus"></i> Create Company / Agent
                    </button>
                </div>
            </div> <!-- .row end -->
        </div>
    </div>
    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
            <div class="row align-items-center mb-2" >
                @if (session("success"))
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">บันทึกสำเร็จ!</h4>
                    <hr>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
                @endif
                @if (session("error"))
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">บันทึกไม่สำเร็จ!</h4>
                        <hr>
                        <p class="mb-0">{{ session('error') }}</p>
                    </div>
                @endif
                <div class="col">
                    <ol class="breadcrumb d-inline-flex bg-transparent p-0 m-0">
                        <li></li>
                        <li></li>
                        <li></li>
                    </ol>
                </div>
                <div class="col-auto">

                </div>
            </div> <!-- Row end  -->
        </div> <!-- Row end  -->
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <div class="card p-4 mb-4">
                        <caption class="caption-top">
                            <div>
                                <div class="top-table-3c">
                                    <div class="top-table-3c_1">
                                        <div class="dropdown">
                                            <button class="bd-button statusbtn enteriespage-button" style="min-width: 100px; text-align: left;" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="text-align: left;">
                                                @if ($menu == 'Company.all')
                                                    All
                                                @elseif ($menu == 'Company.ac')
                                                    Active
                                                @elseif ($menu == 'Company.no')
                                                    Disabled
                                                @else
                                                    Status
                                                @endif
                                        <i class="fas fa-angle-down arrow-dropdown"></i>
                                            </button>
                                            <ul class="dropdown-menu border-0 shadow p-3">
                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('Company', 'Company.all') }}">All</a></li>
                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('Company', 'Company.ac') }}">Active</a></li>
                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('Company', 'Company.no') }}">Disabled</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <label class="entriespage-label">entries per page :</label>
                                    <select class="entriespage-button" id="search-per-page-Company" onchange="getPage(1, this.value, 'Company')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                        <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "Company" ? 'selected' : '' }}>10</option>
                                        <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "Company" ? 'selected' : '' }}>25</option>
                                        <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "Company" ? 'selected' : '' }}>50</option>
                                        <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "Company" ? 'selected' : '' }}>100</option>
                                    </select>
                                    <input class="search-button search-data" id="Company" style="text-align:left;" placeholder="Search" />
                                </div>
                        </caption>
                        <div style="min-height: 70vh;" class="mt-2">
                            <table id="CompanyTable" class="example ui striped table nowrap unstackable hover">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;"data-priority="1">No</th>
                                        <th style="text-align: center;"data-priority="1">Company ID</th>
                                        <th data-priority="1">Company Name</th>
                                        <th class="text-center">Branch </th>
                                        <th class="text-center">Phone Number</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($Company))
                                        @foreach ($Company as $key => $item)
                                        <tr>
                                            <td style="text-align: center;">{{ $key + 1 }}</td>
                                            <td style="text-align: center;">{{ $item->Profile_ID }}</td>
                                            <td>{{ $item->Company_Name }}</td>
                                            <td style="text-align: center;">{{ $item->Branch }}</td>
                                            <td style="text-align: center;">
                                                {{ $item->Phone_numbers }}
                                            </td>
                                            <td style="text-align: center;">
                                                @if ($item->status == 1)
                                                <button type="button" class="btn btn-light-success btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ใช้งาน</button>
                                                @else
                                                    <button type="button" class="btn btn-light-danger btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ปิดใช้งาน</button>
                                                @endif
                                            </td>
                                            @php
                                                $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                $canViewProposal = @Auth::user()->roleMenuView('Company / Agent', Auth::user()->id);
                                                $canEditProposal = @Auth::user()->roleMenuEdit('Company / Agent', Auth::user()->id);
                                            @endphp
                                            <td style="text-align: center;">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                    <ul class="dropdown-menu border-0 shadow p-3">
                                                        @if ($rolePermission > 0)
                                                            @if ($canViewProposal == 1)
                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Company/view/'.$item->id) }}">View</a></li>
                                                            @endif
                                                            @if ($canEditProposal == 1)
                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Company/edit/'.$item->id) }}">Edit</a></li>
                                                            @endif
                                                        @else
                                                            @if ($canViewProposal == 1)
                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Company/view/'.$item->id) }}">View</a></li>
                                                            @endif
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <input type="hidden" id="get-total-Company" value="{{ $Company->total() }}">
                        <input type="hidden" id="currentPage-Company" value="1">
                        <caption class="caption-bottom">
                            <div class="md-flex-bt-i-c">
                                <p class="py2" id="Company-showingEntries">{{ showingEntriesTable($Company, 'Company') }}</p>
                                <div id="Company-paginate">
                                    {!! paginateTable($Company, 'Company') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                </div>
                            </div>
                        </caption>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <!-- dataTable -->
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.semanticui.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.semanticui.js"></script>
    <script type="text/javascript" src="{{ asset('assets/helper/searchTableCompany.js')}}"></script>

    <script>
        // Search
        $(document).on('keyup', '.search-data', function () {
            var id = $(this).attr('id');
            var search_value = $(this).val();
            var table_name = id+'Table';
            var filter_by = $('#filter-by').val();
            var type_status = $('#status').val();
            var total = parseInt($('#get-total-'+id).val());
            var getUrl = window.location.pathname;
            console.log(search_value);


                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                    url: '/company-search-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        search_value: search_value,
                        table_name: table_name,
                        filter_by: filter_by,
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
                    $('#'+id+'-showingEntries').text(showingEntriesSearch(1,count_total, id));
                    $('#'+id+'-paginate').append(paginateSearch(count_total, id, getUrl));
                },
                    columnDefs: [
                                { targets: [0, 1, 3, 4, 5,6], className: 'dt-center td-content-center' },
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
                        { data: 'Profile_ID' },
                        { data: 'Company_Name' },
                        { data: 'Branch' },
                        { data: 'Phone' },
                        { data: 'Status' },
                        { data: 'btn_action' },
                    ],
                });
            document.getElementById(id).focus();
        });
        $(document).ready(function() {
            new DataTable('.example', {
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
        });

    </script>
    @include('script.script')

    <script>

        function btnstatus(id) {
            jQuery.ajax({
                type: "GET",
                url: "{!! url('/Company/change-status/" + id + "') !!}",
                datatype: "JSON",
                async: false,
                success: function(result) {
                    Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                    location.reload();
                },
            });
        }
    </script>
@endsection
