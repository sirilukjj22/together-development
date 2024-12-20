@extends('layouts.masterLayout')

@section('content')

    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Proposal Request </div>
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
                    <ul class="nav nav-tabs px-3 border-bottom-0" role="tablist">
                        <li class="nav-item" id="nav1"><a class="nav-link active" data-bs-toggle="tab" href="#nav-proposal" role="tab" onclick="nav($id='nav1')"><span class="badge" style="background-color:#64748b">{{$proposalcount}}</span> Proposal Request</a></li>{{--ประวัติการแก้ไข--}}
                        <li class="nav-item" id="nav2"><a class="nav-link " data-bs-toggle="tab" href="#nav-Pending" onclick="nav($id='nav2')" role="tab"><span class="badge" style="background-color:#FF6633">{{$requestcount}}</span> Request OverBill</a></li>{{--QUOTAION--}}
                        <li class="nav-item" id="nav3"><a class="nav-link" data-bs-toggle="tab" href="#nav-Awaiting" onclick="nav($id='nav3')" role="tab"><span class="badge bg-warning" >{{$Additionalcount}}</span> Additional</a></li>{{--เอกสารออกบิล--}}
                    </ul>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade  show active" id="nav-proposal" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" class="mt-2">
                                        <caption class="caption-top">
                                            <div class="top-table-3c">
                                                <div class="top-table-3c_1">
                                                    <div class="dropdown">
                                                        <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ route('ProposalReq.log') }}'">LOG</button>
                                                    </div>
                                                </div>
                                                <label class="entriespage-label">entries per page :</label>
                                                <select class="entriespage-button" id="search-per-page-proposal" onchange="getPage(1, this.value, 'proposal')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                    <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "proposal" ? 'selected' : '' }}>10</option>
                                                    <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "proposal" ? 'selected' : '' }}>25</option>
                                                    <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "proposal" ? 'selected' : '' }}>50</option>
                                                    <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "proposal" ? 'selected' : '' }}>100</option>
                                                </select>
                                                <input class="search-button search-data" id="proposal" style="text-align:left;" placeholder="Search" />
                                            </div>
                                        </caption>
                                        <table id="proposalTable" class="example ui striped table nowrap unstackable hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"data-priority="1">No</th>
                                                    <th>Company / Individual</th>
                                                    <th class="text-center" data-priority="1">Proposal Type</th>
                                                    <th class="text-center" data-priority="1">Operated by</th>
                                                    <th class="text-center">Count</th>
                                                    <th class="text-center">Document status</th>
                                                    <th class="text-center">Order</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($proposal))
                                                    @foreach ($proposal as $key => $item)
                                                    <tr>
                                                        <td style="text-align: center;">
                                                            {{$key +1}}
                                                        </td>
                                                        @if ($item->type_Proposal == 'Company')
                                                            <td>{{ @$item->company->Company_Name}}</td>
                                                        @else
                                                            <td>{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                        @endif
                                                        <td style="text-align: center;">{{$item->QuotationType}}</td>
                                                        <td style="text-align: center;">{{ @$item->userOperated->name }}</td>
                                                        <td style="text-align: center;">{{ $item->COUNTDummyNo }}</td>
                                                        <td style="text-align: center;"><span class="badge rounded-pill bg-warning">Awaiting Approval</span></td>
                                                        <td style="text-align: center;">
                                                            <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ url('/Dummy/Proposal/Request/document/view/'.$item->Company_ID.'/'.$item->QuotationType) }}'">
                                                                <i class="fa fa-folder-open-o"></i> View
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                        <input type="hidden" id="get-total-proposal" value="{{ $proposal->total() }}">
                                        <input type="hidden" id="currentPage-proposal" value="1">
                                        <caption class="caption-bottom">
                                            <div class="md-flex-bt-i-c">
                                                <p class="py2" id="proposal-showingEntries">{{ showingEntriesTable($proposal, 'proposal') }}</p>
                                                    <div id="proposal-paginate">
                                                        {!! paginateTable($proposal, 'proposal') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                                    </div>
                                            </div>
                                        </caption>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-Pending" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" class="mt-2">
                                        <table id="requestTable" class="example ui striped table nowrap unstackable hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"data-priority="1">No</th>
                                                    <th>Name request</th>
                                                    <th class="text-center" data-priority="1">Expiration Time</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($request))
                                                    @foreach ($request as $key => $item)
                                                    <tr>
                                                        <td style="text-align: center;">
                                                            {{$key +1}}
                                                        </td>
                                                        <td>{{ @$item->requestername->name}}</td>
                                                        <td style="text-align: center;">{{$item->expiration_time	}}</td>
                                                        <td style="text-align: center;"> <span class="badge rounded-pill "style="background-color: #FF6633	">Pending</span></td>
                                                        <td style="text-align: center;">
                                                            <button type="button" class="btn btn-light-success btn-sm" value="{{ $item->id }}" onclick="btnConfirm({{ $item->id }})">Confirm</button>
                                                            <button type="button" class="btn btn-light-danger btn-sm" value="{{ $item->id }}" onclick="btnCancel({{ $item->id }})">Cancel</button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                        <input type="hidden" id="get-total-request" value="{{ $request->total() }}">
                                        <input type="hidden" id="currentPage-request" value="1">
                                        <caption class="caption-bottom">
                                            <div class="md-flex-bt-i-c">
                                                <p class="py2" id="request-showingEntries">{{ showingEntriesTablePending($request, 'request') }}</p>
                                                <div id="request-paginate">
                                                    {!! paginateTablePending($request, 'request') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                                </div>
                                            </div>
                                        </caption>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-Awaiting" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" class="mt-2">
                                        <caption class="caption-top">
                                            <div class="top-table-3c">
                                                <div class="top-table-3c_1">
                                                    <div class="dropdown">
                                                        <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ route('ProposalReq.LogAdditional') }}'">LOG</button>
                                                    </div>
                                                </div>
                                                <label class="entriespage-label">entries per page :</label>
                                                <select class="entriespage-button" id="search-per-page-proposalAwaiting" onchange="getPageAwaiting(1, this.value, 'proposalAwaiting')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                    <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "proposalAwaiting" ? 'selected' : '' }}>10</option>
                                                    <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "proposalAwaiting" ? 'selected' : '' }}>25</option>
                                                    <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "proposalAwaiting" ? 'selected' : '' }}>50</option>
                                                    <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "proposalAwaiting" ? 'selected' : '' }}>100</option>
                                                </select>
                                                <input class="search-button search-data-Awaiting" id="proposalAwaiting" style="text-align:left;" placeholder="Search" />
                                            </div>
                                        </caption>
                                        <table id="proposalAwaitingTable" class="example ui striped table nowrap unstackable hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"data-priority="1">No</th>
                                                    <th class="text-center" data-priority="1">Additional ID</th>
                                                    <th class="text-center" data-priority="1">Proposal ID</th>
                                                    <th data-priority="1">Company / Individual</th>
                                                    <th class="text-center">Issue Date</th>
                                                    <th class="text-center">Day Type</th>
                                                    <th class="text-center">Check In</th>
                                                    <th class="text-center">Check Out</th>
                                                    <th class="text-center">Expiration Date</th>
                                                    <th class="text-center">Operated By</th>
                                                    <th class="text-center">Document Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($Additional))
                                                    @foreach ($Additional as $key => $item)
                                                        <tr>
                                                            <td style="text-align: center;">
                                                                {{$key +1}}
                                                            </td>
                                                            <td style="text-align: center;">{{ $item->Additional_ID }}</td>
                                                            <td style="text-align: center;">{{ $item->Quotation_ID }}</td>
                                                            @if ($item->type_Proposal == 'Company')
                                                                <td>{{ @$item->company->Company_Name}}</td>
                                                            @else
                                                                <td>{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                            @endif
                                                            <td>{{ $item->issue_date }}</td>
                                                            <td style="text-align: center;">{{ $item->Date_type ?? 'No Check In Date' }}</td>
                                                            @if ($item->checkin)
                                                            <td style="text-align: center;">{{ $item->checkin}}</td>
                                                            <td style="text-align: center;">{{ $item->checkout }}</td>
                                                            @else
                                                            <td style="text-align: center;">-</td>
                                                            <td style="text-align: center;">-</td>
                                                            @endif
                                                            <td style="text-align: center;">{{ $item->Expirationdate }}</td>
                                                            <td >{{ @$item->userOperated->name }}</td>
                                                            <td style="text-align: center;">
                                                                <span class="badge rounded-pill bg-warning">Awaiting Approval</span>
                                                            </td>
                                                            @php
                                                                $CreateBy = Auth::user()->id;
                                                                $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                                $canViewProposal = @Auth::user()->roleMenuView('Billing Folio', Auth::user()->id);
                                                                $canEditProposal = @Auth::user()->roleMenuEdit('Billing Folio', Auth::user()->id);
                                                            @endphp
                                                            <td style="text-align: center;">
                                                                <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ url('/Proposal/request/document/Additional/view/'.$item->id) }}'">
                                                                    <i class="fa fa-folder-open-o"></i> View
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                        <input type="hidden" id="get-total-proposalAwaiting" value="{{ $Additional->total() }}">
                                        <input type="hidden" id="currentPage-proposalAwaiting" value="1">
                                        <caption class="caption-bottom">
                                            <div class="md-flex-bt-i-c">
                                                <p class="py2" id="proposalAwaiting-showingEntries">{{ showingEntriesTableAwaiting($Additional, 'proposalAwaiting') }}</p>
                                                    <div id="proposalAwaiting-paginate">
                                                        {!! paginateTableAwaiting($Additional, 'proposalAwaiting') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                                    </div>
                                            </div>
                                        </caption>
                                    </div>
                                </div>
                            </div>
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
    <script type="text/javascript" src="{{ asset('assets/helper/searchTableProposalRequest.js')}}"></script>

    <script>
        const table_name = ['proposalTable','proposal-LogTable','requestTable','proposalAwaitingTable'];
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
                    url: '/Proposal-request-search-table',
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
                                { targets: [0,2,3,4,5,6], className: 'dt-center td-content-center' },
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
                        { data: 'Company_Name' },
                        { data: 'QuotationType' },
                        { data: 'Operated_by' },
                        { data: 'Count' },
                        { data: 'status' },
                        { data: 'btn_action' },
                    ],
                });
            document.getElementById(id).focus();
        });
        $(document).on('keyup', '.search-data-Awaiting', function () {
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
                    url: '/Proposal-request-Additional-search-table',
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
                    $('#'+id+'-showingEntries').text(showingEntriesSearchAwaiting(1,count_total, id));
                    $('#'+id+'-paginate').append(paginateSearchAwaiting(count_total, id, getUrl));
                },
                    columnDefs: [
                                { targets: [0,1,2,3,4,5,6,7,8,9,10], className: 'dt-center td-content-center' },
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
                        { data: 'Additional_ID' },
                        { data: 'Proposal_ID' },
                        { data: 'Company_Name' },
                        { data: 'IssueDate' },
                        { data: 'Type' },
                        { data: 'CheckIn' },
                        { data: 'CheckOut' },
                        { data: 'ExpirationDate' },
                        { data: 'Operated' },
                        { data: 'DocumentStatus' },
                        { data: 'btn_action' }
                    ],

                });


            document.getElementById(id).focus();
        });
    </script>
    @include('script.script')
    <script>
        function btnConfirm(id) {
            jQuery.ajax({
                type: "GET",
                url: "{!! url('/Proposal-request/confirm-request/" + id + "') !!}",
                datatype: "JSON",
                async: false,
                success: function(result) {
                    Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                    location.reload();
                },
            });
        }
        function btnCancel(id) {
            jQuery.ajax({
                type: "GET",
                url: "{!! url('/Proposal-request/Cancel-request/" + id + "') !!}",
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
