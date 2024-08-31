@extends('layouts.masterLayout')

@section('content')
    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <small class="text-muted">Welcome to Company / Agent.</small>
                    <div class=""><span class="span1">Company / Agent (บริษัทและตัวแทน)</span></div>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-color-green lift btn_modal"  onclick="window.location.href='{{ route('Company.create') }}'">
                        <i class="fa fa-plus"></i> เพิ่มบริษัทและตัวแทน
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
                <div class="col">
                    <ol class="breadcrumb d-inline-flex bg-transparent p-0 m-0">
                        <li></li>
                        <li></li>
                        <li></li>
                    </ol>
                </div>
                <div class="col-auto">
                    <div class="dropdown">
                        <button class="btn btn-outline-dark lift dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            สถานะการใช้งาน
                        </button>
                        <ul class="dropdown-menu border-0 shadow p-3">
                            <li><a class="dropdown-item py-2 rounded" href="{{ route('Company.index') }}">ทั้งหมด</a></li>
                            <li><a class="dropdown-item py-2 rounded" href="{{ route('Company.ac', ['value' => 1]) }}">ใช้งาน</a></li>
                            <li><a class="dropdown-item py-2 rounded" href="{{ route('Company.no', ['value' => 0]) }}">ปิดใช้งาน</a></li>
                        </ul>
                    </div>
                </div>
            </div> <!-- Row end  -->
        </div> <!-- Row end  -->
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <caption class="caption-top">
                                <div>
                                    <div class="flex-end-g2">
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
                                            <th style="text-align: center;"data-priority="1">เรียงลำดับ</th>
                                            <th style="text-align: center;"data-priority="1">รหัสบริษัท</th>
                                            <th data-priority="1">ชื่อองค์กร</th>
                                            <th class="text-center">สาขา</th>
                                            <th class="text-center">เบอร์โทรศัพท์</th>
                                            <th class="text-center">สถานะการใช้งาน</th>
                                            <th class="text-center">คำสั่ง</th>
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
                                                    {{ substr($item->Phone_number, 0, 3) }}-{{ substr($item->Phone_number, 3, 3) }}-{{ substr($item->Phone_number, 6) }}
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($item->status == 1)
                                                    <button type="button" class="btn btn-light-success btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ใช้งาน</button>
                                                    @else
                                                        <button type="button" class="btn btn-light-danger btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ปิดใช้งาน</button>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">ทำรายการ &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Company/view/'.$item->id) }}">ดูรายละเอียด</a></li>
                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Company/edit/'.$item->id) }}">แก้ไขรายการ</a></li>
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
