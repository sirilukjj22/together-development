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
                <small class="text-muted">Welcome to Receipt Payment.</small>
                <h1 class="h4 mt-1">Receipt Payment (ใบเสร็จรับเงิน)</h1>
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
                <li class="nav-item" id="nav4"><a class="nav-link active" data-bs-toggle="tab" href="#nav-Proposal" role="tab"><span class="badge "style="background-color:#64748b">{{$Proposalcount}}</span> Proposal</a></li>
                <li class="nav-item" id="nav2"><a class="nav-link " data-bs-toggle="tab" href="#nav-Generate" role="tab"> <span class="badge bg-warning" >{{$receiptcount}}</span> Generate</a></li>
            </ul>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="nav-Proposal" role="tabpanel" rel="0">
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
                                            <th class="text-center">Number Invoice</th>
                                            <th class="text-center">Document status</th>
                                            <th class="text-center">Order</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($Proposal))
                                        @foreach ($Proposal as $key => $item)
                                        <tr>
                                            <td style="text-align: center;">
                                                {{$key +1}}
                                            </td>
                                            <td>{{ $item->DummyNo}}</td>
                                            <td>{{ @$item->company->Company_Name}}</td>
                                            <td>{{ $item->issue_date }}</td>
                                            <td>{{ $item->Expirationdate }}</td>
                                            <td style="text-align: center;">
                                                {{ number_format($item->Nettotal) }}
                                            </td>
                                            <td style="text-align: center;">
                                                @if ($item->receipt_deposit == 0 )
                                                    0.00
                                                @else
                                                    {{ $item->receipt_deposit }}
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                @if ($item->receipt_Nettotal == 0 )
                                                    0.00
                                                @else
                                                    {{ number_format($item->receipt_Nettotal) }}
                                                @endif

                                            </td>
                                            <td style="text-align: center;">
                                                {{$item->invoice_count}}
                                            </td>
                                            <td style="text-align: center;">
                                                <span class="badge rounded-pill bg-success">Proposal</span>
                                            </td>
                                            <td style="text-align: center;">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                    <ul class="dropdown-menu border-0 shadow p-3">
                                                        {{-- <li><a class="dropdown-item py-2 rounded" target="_bank" href="#">Export</a></li> --}}
                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/receipt/Proposal/invoice/CheckPI/'.$item->id) }}">Generate</a></li>
                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/receipt/Proposal/invoice/view/LOG/'.$item->id) }}">LOG</a></li>
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
                        <div class="tab-pane fade" id="nav-Generate" role="tabpanel" rel="0">
                            <form enctype="multipart/form-data" class="row g-3 basic-form" id="form-id2">
                                @csrf
                                <input type="hidden" name="category" value="prename">
                                <table class="myTableProposalRequest2 table table-hover align-middle mb-0" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Receipt ID</th>
                                            <th class="text-center">Proposal ID</th>
                                            <th>Company</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Deposit</th>
                                            <th class="text-center">Balance</th>
                                            <th class="text-center">Document status</th>
                                            <th class="text-center">Order</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($receipt))
                                        @foreach ($receipt as $key => $item)
                                        <tr>
                                            <td style="text-align: center;">
                                                {{$key +1}}
                                            </td>
                                            <td>{{ $item->receipt_ID}}</td>
                                            <td>{{ $item->Quotation_ID}}</td>
                                            <td>{{ @$item->company00->Company_Name}}</td>
                                            <td style="text-align: center;">
                                                {{ number_format($item->total) }}
                                            </td>
                                            <td style="text-align: center;"> {{ number_format($item->deposit) }}</td>
                                            <td style="text-align: center;">{{ number_format($item->Nettotal) }}</td>
                                            <td style="text-align: center;">
                                                <span class="badge rounded-pill bg-warning">generate</span>
                                            </td>
                                            <td style="text-align: center;">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                    <ul class="dropdown-menu border-0 shadow p-3">
                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/receipt/Proposal/invoice/view/'.$item->id) }}">View</a></li>
                                                        <li><a class="dropdown-item py-2 rounded" target="_bank" href="#">Export</a></li>
                                                        <li><a class="dropdown-item py-2 rounded" href="#">LOG</a></li>
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
        var status = $('#nav-Generate').attr('rel');

        if (status == 0) {
            document.getElementById("nav-Generate").setAttribute("rel", "1");
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
</script>
@endsection
