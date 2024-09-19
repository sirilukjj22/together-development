@extends('layouts.masterLayout')

</style>
@section('content')
    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <small class="text-muted">Welcome to Proforma Invoice.</small>
                    <div class=""><span class="span1">Proforma Invoice (ใบแจ้งหนี้)</span></div>
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
            </div> <!-- Row end  -->
        </div> <!-- Row end  -->
        <div class="row clearfix">
            <div class="col-sm-12 col-12">
                <ul class="nav nav-tabs px-3 border-bottom-0" role="tablist">
                    <li class="nav-item" id="nav1"><a class="nav-link active" data-bs-toggle="tab" href="#nav-Dummy" role="tab" onclick="nav($id='nav1')"><span class="badge" style="background-color:#64748b">{{$Approvedcount}}</span> Proposal</a></li>{{--ประวัติการแก้ไข--}}
                    <li class="nav-item" id="nav2"><a class="nav-link " data-bs-toggle="tab" href="#nav-Pending" onclick="nav($id='nav2')" role="tab"><span class="badge" style="background-color:#FF6633">{{$invoicecount}}</span> Invoice</a></li>
                </ul>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane fade  show active" id="nav-Dummy" role="tabpanel" rel="0">
                                <div style="min-height: 70vh;" class="mt-2">
                                    <caption class="caption-top">
                                        <div class="flex-end-g2">
                                            <label class="entriespage-label">entries per page :</label>
                                            <select class="entriespage-button" id="search-per-page-invoice" onchange="getPage(1, this.value, 'invoice')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "invoice" ? 'selected' : '' }}>10</option>
                                                <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "invoice" ? 'selected' : '' }}>25</option>
                                                <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "invoice" ? 'selected' : '' }}>50</option>
                                                <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "invoice" ? 'selected' : '' }}>100</option>
                                            </select>
                                            <input class="search-button search-data" id="invoice" style="text-align:left;" placeholder="Search" />
                                        </div>
                                    </caption>
                                    <table id="invoiceTable" class="example1 ui striped table nowrap unstackable hover">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center;"data-priority="1">No</th>
                                                <th data-priority="1">Proposal ID</th>
                                                <th data-priority="1">Company / Individual</th>
                                                <th>Issue Date</th>
                                                <th>Expiration Date</th>
                                                <th class="text-center">Amount</th>
                                                <th class="text-center">Deposit</th>
                                                <th class="text-center">Balance</th>
                                                <th class="text-center">Approve By</th>
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
                                                    <td>{{ $item->Quotation_ID}}</td>
                                                    @if ($item->type_Proposal == 'Company')
                                                        <td>{{ @$item->company->Company_Name}}</td>
                                                    @else
                                                        <td>{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                    @endif
                                                    <td>{{ $item->issue_date }}</td>
                                                    <td>{{ $item->Expirationdate }}</td>
                                                    <td style="text-align: center;">
                                                        {{ number_format($item->Nettotal) }}
                                                    </td>
                                                    <td style="text-align: center;">
                                                        @if ($item->total_payment == 0 )
                                                            0
                                                        @else
                                                            {{ number_format($item->total_payment) }}
                                                        @endif
                                                    </td>
                                                    <td style="text-align: center;">
                                                        @if ($item->min_balance == 0 )
                                                            0
                                                        @else
                                                        {{ number_format($item->min_balance) }}
                                                        @endif
                                                    </td>
                                                    <td style="text-align: center;">
                                                        @if (@$item->userConfirm->name == null)
                                                            Auto
                                                        @else
                                                            {{ @$item->userConfirm->name }}
                                                        @endif
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <span class="badge rounded-pill bg-success">Proposal</span>
                                                    </td>
                                                    @php
                                                        $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                        $canViewProposal = @Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                                                        $canEditProposal = @Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);
                                                    @endphp
                                                    <td style="text-align: center;">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                            <ul class="dropdown-menu border-0 shadow p-3">
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" target="_blank" href="{{ url('/Proposal/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                                @endif
                                                                @if(!empty($invoice) && $invoice->count() == 0)
                                                                    @if ($canEditProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/Generate/'.$item->id) }}">Generate</a></li>
                                                                    @endif
                                                                @else
                                                                    @if ($canEditProposal == 1)
                                                                        @php
                                                                            $hasStatusReceiveZero = false;
                                                                        @endphp
                                                                        @foreach ($invoicecheck as $key2 => $item2)
                                                                            @if ($item->QID == $item2->Quotation_ID && $item2->status_receive == 0)
                                                                                @php
                                                                                    $hasStatusReceiveZero = true;
                                                                                    break; // หยุดการลูปทันทีเมื่อพบเงื่อนไขที่ต้องการ
                                                                                @endphp
                                                                            @endif
                                                                        @endforeach
                                                                        @if ($hasStatusReceiveZero ==null)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/Generate/'.$item->id) }}">Generate</a></li>
                                                                        @endif
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
                                    <input type="hidden" id="get-total-invoice" value="{{ $Approved->total() }}">
                                    <input type="hidden" id="currentPage-invoice" value="1">
                                    <caption class="caption-bottom">
                                        <div class="md-flex-bt-i-c">
                                            <p class="py2" id="invoice-showingEntries">{{ showingEntriesTable($Approved, 'invoice') }}</p>
                                            <div id="invoice-paginate">
                                                {!! paginateTable($Approved, 'invoice') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                            </div>
                                        </div>
                                    </caption>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-Pending" role="tabpanel" rel="0">
                                <div style="min-height: 70vh;" class="mt-2">
                                    <caption class="caption-top">
                                        <div class="flex-end-g2">
                                            <label class="entriespage-label">entries per page :</label>
                                            <select class="entriespage-button" id="search-per-page-invoicePending" onchange="getPagePending(1, this.value, 'invoicePending')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "invoicePending" ? 'selected' : '' }}>10</option>
                                                <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "invoicePending" ? 'selected' : '' }}>25</option>
                                                <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "invoicePending" ? 'selected' : '' }}>50</option>
                                                <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "invoicePending" ? 'selected' : '' }}>100</option>
                                            </select>
                                            <input class="search-button search-data-Pending" id="invoicePending" style="text-align:left;" placeholder="Search" />
                                        </div>
                                    </caption>
                                    <table id="invoicePendingTable" class="example2 ui striped table nowrap unstackable hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th data-priority="1">Invoice ID</th>
                                                <th data-priority="1">Proposal ID</th>
                                                <th data-priority="1">Company / Individual</th>
                                                <th class="text-center">Issue Date</th>
                                                <th class="text-center">Expiration Date</th>
                                                <th class="text-center">Amount</th>
                                                <th class="text-center">Payment</th>
                                                <th class="text-center">Payment(%)</th>
                                                <th class="text-center">Balance</th>
                                                <th class="text-center">Document status</th>
                                                <th class="text-center">Order</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($invoice))
                                                @foreach ($invoice as $key => $item)
                                                <tr>
                                                    <td style="text-align: center;">
                                                    {{$key +1}}
                                                    </td>
                                                    <td>{{ $item->Invoice_ID}}</td>
                                                    <td>{{ $item->Quotation_ID}}</td>
                                                    @if ($item->type_Proposal == 'Company')
                                                        <td>{{ @$item->company00->Company_Name}}</td>
                                                    @else
                                                        <td>{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                    @endif
                                                    <td style="text-align: center;">{{ $item->IssueDate }}</td>
                                                    <td style="text-align: center;">{{ $item->Expiration }}</td>
                                                    <td style="text-align: center;">
                                                        {{ number_format($item->Nettotal) }}
                                                    </td>
                                                    <td style="text-align: center;"> {{ number_format($item->payment) }}</td>
                                                    @if ($item->paymentPercent == null)
                                                        <td style="text-align: center;">0</td>
                                                    @else
                                                        <td style="text-align: center;">{{$item->paymentPercent	}} %</td>
                                                    @endif

                                                    <td style="text-align: center;">{{ number_format($item->balance) }}</td>
                                                    <td style="text-align: center;">
                                                        <span class="badge rounded-pill "style="background-color: #FF6633	">Pending</span>
                                                    </td>
                                                    @php
                                                        $CreateBy = Auth::user()->id;
                                                        $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                        $canViewProposal = @Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                                                        $canEditProposal = @Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);
                                                    @endphp
                                                    <td style="text-align: center;">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                            <ul class="dropdown-menu border-0 shadow p-3">
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/view/'.$item->id) }}">View</a></li>
                                                                    <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Invoice/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/view/LOG/'.$item->id) }}">LOG</a></li>
                                                                @endif
                                                                @if ($rolePermission > 0)
                                                                    @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                        @if ($canEditProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded"  href="{{ url('/Document/invoice/revised/'.$item->id) }}">Edit</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/Generate/to/Re/'.$item->id) }}">Generate</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" onclick="Delete({{$item->id}})">Delete</a></li>
                                                                        @endif
                                                                    @elseif ($rolePermission == 2)
                                                                        @if ($item->Operated_by == $CreateBy)
                                                                            @if ($canEditProposal == 1)
                                                                                <li><a class="dropdown-item py-2 rounded"  href="{{ url('/Document/invoice/revised/'.$item->id) }}">Edit</a></li>
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/Generate/to/Re/'.$item->id) }}">Generate</a></li>
                                                                                <li><a class="dropdown-item py-2 rounded" onclick="Delete({{$item->id}})">Delete</a></li>
                                                                            @endif
                                                                        @endif
                                                                    @elseif ($rolePermission == 3)
                                                                        @if ($canEditProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded"  href="{{ url('/Document/invoice/revised/'.$item->id) }}">Edit</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/Generate/to/Re/'.$item->id) }}">Generate</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" onclick="Delete({{$item->id}})">Delete</a></li>
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    @if ($canViewProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/view/'.$item->id) }}">View</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Invoice/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/view/LOG/'.$item->id) }}">LOG</a></li>
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
                                    <input type="hidden" id="get-total-invoicePending" value="{{ $invoice->total() }}">
                                    <input type="hidden" id="currentPage-invoicePending" value="1">
                                    <caption class="caption-bottom">
                                        <div class="md-flex-bt-i-c">
                                            <p class="py2" id="invoicePending-showingEntries">{{ showingEntriesTablePending($invoice, 'invoicePending') }}</p>
                                                <div id="invoicePending-paginate">
                                                    {!! paginateTablePending($invoice, 'invoicePending') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                                </div>
                                        </div>
                                    </caption>
                                </div>
                            </div>
                            <div class="tab-pane fade "id="nav-Awaiting" role="tabpanel" rel="0">
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
    <script type="text/javascript" src="{{ asset('assets/helper/searchTableInvoice.js')}}"></script>
    <script>
        const table_name = ['invoiceTable','invoicePendingTable'];
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
                    url: '/invoice-search-table',
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
                                { targets: [0,3,4,5,6,7,8,9,10], className: 'dt-center td-content-center' },
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
                        { data: 'Deposit' },
                        { data: 'Balance' },
                        { data: 'Approve' },
                        { data: 'DocumentStatus' },
                        { data: 'btn_action' }
                    ],

                });


            document.getElementById(id).focus();
        });
        $(document).on('keyup', '.search-data-Pending', function () {
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
                    url: '/invoice-pendind-search-table',
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
                                { targets: [0,4,5,6,7,8,9,10,11], className: 'dt-center td-content-center' },
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
                        { data: 'Invoice' },
                        { data: 'Proposal' },
                        { data: 'Company_Name' },
                        { data: 'IssueDate' },
                        { data: 'ExpirationDate' },
                        { data: 'Amount' },
                        { data: 'PaymentB' },
                        { data: 'PaymentP' },
                        { data: 'Balance' },
                        { data: 'DocumentStatus' },
                        { data: 'btn_action' }
                    ],

                });


            document.getElementById(id).focus();
        });
        function Delete(id){
            Swal.fire({
            title: "คุณต้องการลบรายการนี้ใช่หรือไม่?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "ตกลง",
            cancelButtonText: "ยกเลิก",
            confirmButtonColor: "#28a745",
            dangerMode: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ url('/Document/invoice/delete/') }}/" + id;
                }
            });
        }
    </script>
@endsection
