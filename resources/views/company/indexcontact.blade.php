@extends('layouts.masterLayout')

@section('content')
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Company Contact</div>
                </div>
                <div class="col-auto">
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
                                <div class="flex-end-g2">
                                    <label class="entriespage-label">entries per page :</label>
                                    <select class="entriespage-button" id="search-per-page-company-Contact" onchange="getPageContact(1, this.value, 'company-Contact')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                        <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "company-Contact" ? 'selected' : '' }}>10</option>
                                        <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "company-Contact" ? 'selected' : '' }}>25</option>
                                        <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "company-Contact" ? 'selected' : '' }}>50</option>
                                        <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "company-Contact" ? 'selected' : '' }}>100</option>
                                    </select>
                                    <input class="search-button search-data" id="company-Contact" style="text-align:left;" placeholder="Search" />
                                </div>
                        </caption>
                        <div style="min-height: 70vh;" class="mt-2">
                            <table id="company-ContactTable" class="example ui striped table nowrap unstackable hover">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;"data-priority="1">No</th>
                                        <th style="text-align: center;"data-priority="1">Contact ID</th>
                                        <th class="text-center"data-priority="1">Company ID</th>
                                        <th class="text-center">Branch</th>
                                        <th class="text-center">Name</th>
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
                                            <td style="text-align: center;">{{ $item->Company_ID }}</td>
                                            <td style="text-align: center;">{{ $item->Branch }}</td>
                                            <td style="text-align: center;">
                                                คุณ {{ $item->First_name.' '.$item->Last_name }}
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
                                                        @if ($canViewProposal == 1)
                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Company/view/'.$item->id) }}">View</a></li>
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
                        <input type="hidden" id="profile-company-Contact" value="{{ $Company_ID }}">
                        <input type="hidden" id="get-total-company-Contact" value="{{ $Company->total() }}">
                        <input type="hidden" id="currentPage-company-Contact" value="1">
                        <caption class="caption-bottom">
                            <div class="md-flex-bt-i-c">
                                <p class="py2" id="company-Contact-showingEntries">{{ showingEntriesTableContact($Company, 'company-Contact') }}</p>
                                <div id="company-Contact-paginate">
                                    {!! paginateTableContact($Company, 'company-Contact') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                </div>
                            </div>
                        </caption>
                        @if ($count == 1)
                            <div class="row mt-2">
                                <div class="col-lg-3 col-sm-12"></div>
                                <div class="col-lg-6 col-sm-12 d-flex justify-content-center align-items-center">
                                    <button type="button" class="btn btn-secondary lift  btn-space"  onclick="window.location.href='{{url('/Company/edit/'.$CompanyID)}}'">{{ __('ย้อนกลับ') }}</button>
                                </div>
                                <div class="col-lg-3 col-sm-12"></div>
                            </div>
                        @endif
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

            var guest_profile = $('#profile-company-Contact').val();
            console.log(guest_profile);
                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                    url: '/Contact-company-search-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        search_value: search_value,
                        table_name: table_name,
                        filter_by: filter_by,
                        status: type_status,
                        guest_profile:guest_profile,
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
                    $('#'+id+'-showingEntries').text(showingEntriesSearchContact(1,count_total, id));
                    $('#'+id+'-paginate').append(paginateSearchContact(count_total, id, getUrl));
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
                        { data: 'Profile_ID_TAX' },
                        { data: 'Company' },
                        { data: 'Branch' },
                        { data: 'Name' },
                        { data: 'Status' },
                        { data: 'Order' },
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
                url: "{!! url('/company/change-status/Contact/" + id + "') !!}",
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
