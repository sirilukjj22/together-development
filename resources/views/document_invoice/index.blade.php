@extends('layouts.masterLayout')
<!-- table design css -->
<link rel="stylesheet" href="{{ asset('assets/css/semantic.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/css/dataTables.semanticui.css')}}">
<link rel="stylesheet" href="{{ asset('assets/css/responsive.semanticui.css')}}">

<!-- table design js -->
<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="{{ asset('assets/js/semantic.min.js')}}"></script>
<script src="{{ asset('assets/js/dataTables.js')}}"></script>
<script src="{{ asset('assets/js/dataTables.semanticui.js')}}"></script>
<script src="{{ asset('assets/js/dataTables.responsive.js')}}"></script>
<script src="{{ asset('assets/js/responsive.semanticui.js')}}"></script>
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
</script>
@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to Proforma Invoice.</small>
                <h1 class="h4 mt-1">Proforma Invoice (ใบแจ้งหนี้)</h1>
            </div>
        </div>
    </div>
@endsection
<style>
    .tab1{
    background-color: white;
    color: black; /* เปลี่ยนสีตัวอักษรเป็นสีดำหากต้องการ */
}
</style>
@section('content')
<div class="container">
    <div class="row align-items-center mb-2">
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

    <div class="row clearfix">
        <div class="col-sm-12 col-12">
            <ul class="nav nav-tabs px-3 border-bottom-0" role="tablist">
                <li class="nav-item" id="nav4"><a class="nav-link active" data-bs-toggle="tab" href="#nav-Approved" role="tab"><span class="badge "style="background-color:#64748b">{{$Approvedcount}}</span> Approved</a></li>
                <li class="nav-item" id="nav2"><a class="nav-link " data-bs-toggle="tab" href="#nav-invoice" role="tab"> <span class="badge" style="background-color:#FF6633" >{{$invoicecount}}</span> Invoice</a></li>
                <li class="nav-item" id="nav3"><a class="nav-link" data-bs-toggle="tab" href="#nav-Complete" role="tab"><span class="badge bg-success" >{{$Completecount}}</span> Generate </a></li>
            </ul>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="nav-Approved" role="tabpanel" rel="0">
                            <form enctype="multipart/form-data" class="row g-3 basic-form" id="form-id2">
                                @csrf
                                <input type="hidden" name="category" value="prename">
                                <table class="example ui striped table nowrap unstackable hover" style="width:100%">
                                    <caption class="caption-top">
                                        <div>
                                            <div class="flex-end-g2">
                                                <label class="entriespage-label" >entries per page : </label>
                                                <select class="enteriespage-button">
                                                    <option value="7" class="bg-[#f7fffc] text-[#2C7F7A]">10</option>
                                                    <option value="15" class="bg-[#f7fffc] text-[#2C7F7A]">25</option>
                                                    <option value="30" class="bg-[#f7fffc] text-[#2C7F7A]">50</option>
                                                    <option value="30" class="bg-[#f7fffc] text-[#2C7F7A]">100</option>
                                                </select>
                                                <input class="search-button" placeholder="Search" /><i
                                                    class="fa fa-search fa-searh-middle"></i>
                                            </div>
                                    </caption>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>ID</th>
                                            <th>Company</th>
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
                                                <td>{{ @$item->company->Company_Name}}</td>
                                                <td>{{ $item->issue_date }}</td>
                                                <td>{{ $item->Expirationdate }}</td>
                                                <td style="text-align: center;">
                                                    {{ number_format($item->Nettotal) }}
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($item->total_payment == 0 )
                                                        0.00
                                                    @else
                                                        {{ number_format($item->total_payment) }}
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($item->min_balance == 0 )
                                                        0.00
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
                                                    <span class="badge rounded-pill bg-success">Approved</span>
                                                </td>
                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Quotation/Quotation/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                            @if($invoicecount)
                                                                @foreach ($invoice as $key2 => $item2)
                                                                    @if ($item->Quotation_ID == $item2->Quotation_ID)
                                                                        <!-- ลิงก์สำหรับไฟล์ที่มีชื่อเริ่มต้นเหมือนกัน -->
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/path/to/invoice/'.$item2->filename) }}">{{ $item2->filename }}</a></li>
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/Generate/'.$item->id) }}">Generate</a></li>
                                                            @endif
                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Quotation/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                    <caption class="caption-bottom ">
                                        <div class="md-flex-bt-i-c">
                                            <p class="py2">Showing 1 to 7 of 7 entries</p>
                                            <div class="font-bold">ยอดรวมทั้งหมด 00.00 บาท</div>
                                            <div class="dp-flex js-center">
                                                <div class="pagination">
                                                    <a href="" class="r-l-md">&laquo;</a>
                                                    {{-- @for($i=1;$i<=$data_sms->lastPage();$i++)
                                                        <!-- a Tag for another page --> --}}
                                                        <a class="active" href="">1</a>
                                                    {{-- @endfor --}}
                                                    <a href="" class="r-r-md">&raquo;</a>
                                                </div>
                                            </div>
                                        </div>
                                    </caption>
                                </table>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="nav-invoice" role="tabpanel" rel="0">
                            <form enctype="multipart/form-data" class="row g-3 basic-form" id="form-id2">
                                @csrf
                                <input type="hidden" name="category" value="prename">
                                <table class="myTableProposalRequest2 table table-hover align-middle mb-0" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Proposal ID</th>
                                            <th>Company</th>
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
                                            <td>{{ @$item->company00->Company_Name}}</td>
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
                                            <td style="text-align: center;">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                    <ul class="dropdown-menu border-0 shadow p-3">
                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/view/'.$item->id) }}">View</a></li>
                                                        <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Invoice/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                        <li><a class="dropdown-item py-2 rounded"  href="{{ url('/Document/invoice/revised/'.$item->id) }}">Edit</a></li>
                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/view/LOG/'.$item->id) }}">LOG</a></li>
                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/delete/'.$item->id) }}">Delete</a></li>
                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/Generate/to/Re/'.$item->id) }}">Generate</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="nav-Complete" role="tabpanel" rel="0">
                            <form enctype="multipart/form-data" class="row g-3 basic-form" id="form-id2">
                                @csrf
                                <input type="hidden" name="category" value="prename">
                                <table class="myTableProposalRequest3 table table-hover align-middle mb-0" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Proposal ID</th>
                                            <th>Company</th>
                                            <th class="text-center">Issue Date</th>
                                            <th class="text-center">Expiration Date</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Payment</th>
                                            <th class="text-center">Balance</th>
                                            <th class="text-center">Document status</th>
                                            <th class="text-center">Order</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($Complete))
                                        @foreach ($Complete as $key => $item)
                                        <tr>
                                            <td style="text-align: center;">
                                                {{$key +1}}
                                            </td>
                                            <td>{{ $item->Invoice_ID}}</td>
                                            <td>{{ $item->Quotation_ID}}</td>
                                            <td>{{ @$item->company00->Company_Name}}</td>
                                            <td style="text-align: center;">{{ $item->IssueDate }}</td>
                                            <td style="text-align: center;">{{ $item->Expiration }}</td>
                                            <td style="text-align: center;">
                                                {{ number_format($item->Nettotal) }}
                                            </td>
                                            <td style="text-align: center;"> {{ number_format($item->sumpayment) }}</td>
                                            <td style="text-align: center;">{{ number_format($item->balance) }}</td>
                                            <td style="text-align: center;">
                                                <span class="badge rounded-pill bg-success">Generate</span>
                                            </td>
                                            <td style="text-align: center;">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                    <ul class="dropdown-menu border-0 shadow p-3">
                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/view/'.$item->id) }}">View</a></li>
                                                        <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Invoice/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/view/LOG/'.$item->id) }}">LOG</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="form-id3">
    @csrf
    <input type="hidden" id="deleteID" name="deleteID" value="">
</form>

@include('script.script')

<script>
    $(document).ready(function () {
        $('.myTableProposalRequest1').addClass('nowrap').dataTable({
            responsive: true,
            searching: true,
            paging: true,
            ordering: true,
            info: true,
            columnDefs: [
                // className: 'bolded'
                // { targets: [-1, -3], className: 'dt-body-right' }
            ]
        });
    });
    $('#nav2').on('click', function () {
        var status = $('#nav-invoice').attr('rel');

        if (status == 0) {
            document.getElementById("nav-invoice").setAttribute("rel", "1");
            $('.myTableProposalRequest2').addClass('nowrap').dataTable({
                responsive: true,
                searching: true,
                paging: true,
                ordering: true,
                info: true,
                columnDefs: [
                    // className: 'bolded'
                    // { targets: [-1, -3], className: 'dt-body-right' }
                ]

            });
        }
    })
    $('#nav3').on('click', function () {
        var status = $('#nav-Complete').attr('rel');

        if (status == 0) {
            document.getElementById("nav-Complete").setAttribute("rel", "1");
            $('.myTableProposalRequest3').addClass('nowrap').dataTable({
                responsive: true,
                searching: true,
                paging: true,
                ordering: true,
                info: true,
                columnDefs: [
                    // className: 'bolded'
                    // { targets: [-1, -3], className: 'dt-body-right' }
                ]

            });
        }
    })
</script>
@endsection
