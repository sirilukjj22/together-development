@extends('layouts.masterLayout')

</style>
@section('content')
    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <small class="text-muted">Welcome to Billing Folio.</small>
                    <div class=""><span class="span1">Billing Folio (ใบเรียกเก็บเงิน)</span></div>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ route('BillingFolio.issuebill') }}'">
                        <i class="fa fa-plus"></i> Issue Bill
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
            </div> <!-- Row end  -->
        </div> <!-- Row end  -->
        <div class="row clearfix">
            <div class="col-sm-12 col-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <div style="min-height: 70vh;" class="mt-2">
                            <caption class="caption-top">
                                <div class="flex-end-g2">
                                    <label class="entriespage-label">entries per page :</label>
                                    <select class="entriespage-button" id="search-per-page-billing" onchange="getPage(1, this.value, 'billing')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                        <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "billing" ? 'selected' : '' }}>10</option>
                                        <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "billing" ? 'selected' : '' }}>25</option>
                                        <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "billing" ? 'selected' : '' }}>50</option>
                                        <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "billing" ? 'selected' : '' }}>100</option>
                                    </select>
                                    <input class="search-button search-data" id="billing" style="text-align:left;" placeholder="Search" />
                                </div>
                            </caption>
                            <table id="billingTable" class="example1 ui striped table nowrap unstackable hover">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;"data-priority="1">No</th>
                                        <th data-priority="1">Receipt ID</th>
                                        <th data-priority="1">Company / Individual</th>
                                        <th>Room No</th>
                                        <th>Payment Date</th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">Category</th>
                                        <th class="text-center">Operated By</th>
                                        <th class="text-center">Document status</th>
                                        <th class="text-center">Order</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($Approved))
                                        @foreach ($Approved as $key => $item)
                                        <tr>
                                            <td style="text-align: center;">
                                                {{$key +1}}
                                            </td>
                                            <td>{{ $item->Receipt_ID}}</td>
                                            @if ($item->type_Proposal == 'Company')
                                                <td>{{ isset($item->company00->Company_Name) ? $item->company00->Company_Name : '' }}</td>
                                            @elseif ($item->type_Proposal == 'Guest')
                                                <td>{{ isset($item->guest->First_name) && isset($item->guest->Last_name) ? $item->guest->First_name.' '.$item->guest->Last_name : '' }}</td>
                                            @elseif ($item->type_Proposal == 'company_tax')
                                                <td>{{ isset($item->company_tax->Companny_name) ? $item->company_tax->Companny_name : (isset($item->company_tax->first_name) && isset($item->company_tax->last_name) ? $item->company_tax->first_name.' '.$item->company_tax->last_name : '') }}</td>
                                            @elseif ($item->type_Proposal == 'guest_tax')
                                                <td>{{ isset($item->guest_tax->Company_name) ? $item->guest_tax->Company_name : (isset($item->guest_tax->first_name) && isset($item->guest_tax->last_name) ? $item->guest_tax->first_name.' '.$item->guest_tax->last_name : '') }}</td>
                                            @endif

                                            <td>{{ $item->roomNo }}</td>
                                            <td>{{ $item->paymentDate }}</td>
                                            <td style="text-align: center;">
                                                {{ number_format($item->Amount) }}
                                            </td>
                                            <td style="text-align: center;">{{ $item->category }}</td>
                                            <td style="text-align: center;">
                                                {{ @$item->userOperated->name }}
                                            </td>
                                            <td style="text-align: center;">
                                                <span class="badge rounded-pill bg-success">Confirm</span>
                                            </td>
                                            @php
                                                $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                $canViewProposal = @Auth::user()->roleMenuView('Billing Folio', Auth::user()->id);
                                                $canEditProposal = @Auth::user()->roleMenuEdit('Billing Folio', Auth::user()->id);
                                            @endphp
                                            <td style="text-align: center;">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                    <ul class="dropdown-menu border-0 shadow p-3">
                                                        @if ($rolePermission > 0)
                                                            @if ($canViewProposal == 1)
                                                                <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Document/BillingFolio/Proposal/invoice/view/'.$item->id) }}">Export</a></li>
                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/invoice/log/'.$item->id) }}">LOG</a></li>
                                                            @endif
                                                            @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                @if ($canEditProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/invoice/Generate/Paid/Edit/'.$item->id) }}">Edit</a></li>
                                                                @endif
                                                            @elseif ($rolePermission == 2)
                                                                @if ($item->Operated_by == $CreateBy)
                                                                    @if ($canEditProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/invoice/Generate/Paid/Edit/'.$item->id) }}">Edit</a></li>
                                                                    @endif
                                                                @endif
                                                            @elseif ($rolePermission == 3)
                                                                @if ($canEditProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/invoice/Generate/Paid/Edit/'.$item->id) }}">Edit</a></li>
                                                                @endif
                                                            @endif
                                                        @else
                                                            @if ($canViewProposal == 1)
                                                                <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Document/BillingFolio/Proposal/invoice/view/'.$item->id) }}">Export</a></li>
                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/invoice/log/'.$item->id) }}">LOG</a></li>
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
                            <input type="hidden" id="get-total-billing" value="{{ $Approved->total() }}">
                            <input type="hidden" id="currentPage-billing" value="1">
                            <caption class="caption-bottom">
                                <div class="md-flex-bt-i-c">
                                    <p class="py2" id="billing-showingEntries">{{ showingEntriesTable($Approved, 'billing') }}</p>
                                    <div id="billing-paginate">
                                        {!! paginateTable($Approved, 'billing') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                    </div>
                                </div>
                            </caption>
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
        const table_name = ['billingTable'];
        $(document).ready(function() {
            for (let index = 0; index < table_name.length; index++) {
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
            console.log(table_name);
                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                    url: '/billing-search-table',
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
                                { targets: [0,3,4,5,6,7,8,9], className: 'dt-center td-content-center' },
                    ],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    },
                    columns: [
                        { data: 'number'},
                        { data: 'Proposal' },
                        { data: 'Company_Name' },
                        { data: 'IssueDate' },
                        { data: 'ExpirationDate' },
                        { data: 'Amount' },
                        { data: 'Balance' },
                        { data: 'Approve' },
                        { data: 'DocumentStatus' },
                        { data: 'btn_action' }
                    ],

                });


            document.getElementById(id).focus();
        });
    </script>
@endsection
