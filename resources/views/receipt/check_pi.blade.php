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
                <small class="text-muted">Welcome to Check before creating a receipt.</small>
                <h1 class="h4 mt-1">Check before creating a receipt (ตรวจสอบก่อนสร้างใบเสร็จรับเงิน)</h1>
            </div>
        </div>
    </div>
@endsection
<style>
    .tab1{
        background-color: white;
        color: black; /* เปลี่ยนสีตัวอักษรเป็นสีดำหากต้องการ */
    }
    .styled-hr {
        border: none; /* เอาขอบออก */
        border: 1px solid #2D7F7B; /* กำหนดระยะห่างด้านล่าง */
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
            <div class="card mb-3">
                <div class="card-body">
                    <div>
                        <table class=" table align-middle mb-0" style="width:100%">
                            <thead >
                                <tr>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:20%;">Proposal ID</th>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:5%;">Subtotal</th>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:15%;">Special Discount</th>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:15%;">Total Balance</th>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:15%;">Price Before Tax</th>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:15%;">Value Added Tax</th>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:10%;">Total Amount</th>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:5%;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>{{$Proposal_ID}}</th>
                                    <th style="text-align:center;">{{ number_format($total, 2, '.', ',') }}</th>
                                    <th style="text-align:center;">{{ number_format($SpecialDiscountBath, 2, '.', ',') }}</th>
                                    <th style="text-align:center;">{{ number_format($Nettotal, 2, '.', ',') }}</th>
                                    <th style="text-align:center;">{{ number_format($beforeTax, 2, '.', ',') }}</th>
                                    <th style="text-align:center;">{{ number_format($AddTax, 2, '.', ',') }}</th>
                                    <th style="text-align:center;">{{ number_format($Nettotal, 2, '.', ',') }}</th>
                                    <th style="text-align:left;">Baht</th>
                                </tr>
                                <thead >
                                    <tr>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:20%;">Receipt / Tax Invoice ID</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:5%;"></th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:15%;"></th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:15%;">Total Balance</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:15%;">Price Before Tax</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:15%;">Value Added Tax</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:10%;">Total Amount</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:5%;"></th>
                                    </tr>
                                </thead>
                                @if(!empty($receipt))
                                    @foreach ($receipt as $key => $item)
                                        <tr>
                                            <th>{{$item->receipt_ID}}</th>
                                            <th></th>
                                            <th></th>
                                            @if ($item->vat_type == 50)
                                                <th style="text-align:center;">{{ number_format($item->deposit, 2, '.', ',') }}</th>
                                                <th style="text-align:center;">{{ number_format($item->deposit /1.07 , 2, '.', ',') }}</th>
                                                <th style="text-align:center;">{{ number_format($item->deposit - $item->deposit /1.07  , 2, '.', ',') }}</th>
                                                <th style="text-align:center;"> - {{ number_format($item->deposit, 2, '.', ',') }}</th>
                                            @elseif ($item->vat_type == 51)
                                                <th style="text-align:center;">{{ number_format($item->deposit, 2, '.', ',') }}</th>
                                                <th style="text-align:center;">-</th>
                                                <th style="text-align:center;">-</th>
                                                <th style="text-align:center;"> - {{ number_format($item->deposit, 2, '.', ',') }}</th>
                                            @else
                                                <th style="text-align:center;">{{ number_format($item->deposit , 2, '.', ',') }}</th>
                                                <th style="text-align:center;">{{ number_format($item->deposit /1.07 , 2, '.', ',') }}</th>
                                                <th style="text-align:center;">{{ number_format($item->deposit *7/100  , 2, '.', ',') }}</th>
                                                <th style="text-align:center;"> - {{ number_format($item->deposit, 2, '.', ',') }}</th>
                                            @endif
                                            <th style="text-align:left;">Baht</th>
                                        </tr>
                                    @endforeach
                                @endif
                                <tr>
                                    <th style="text-align:left;"></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th style="text-align:center;">{{ number_format($totalreceipt, 2, '.', ',') }}</th>
                                    <th style="text-align:left;">Baht</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <table class=" table align-middle mb-0" style="width:100%">
                        <thead >
                            <tr>
                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:50%;">Proforma Invoice ID</th>
                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:15%;"></th>
                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:15%;">Receipt / Tax Invoice ID</th>
                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:5%;">Status</th>
                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:10%;">Total Amount</th>
                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:5%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($invoices))
                                @foreach ($invoices as $key => $item2)
                                        <tr>
                                            <th style="text-align:left;">{{$item2->Invoice_ID}}</th>
                                            <th style="text-align:right;"></th>
                                            <th style="text-align:center;">{{ optional($item2->sequence00)->receipt_ID}}</th>
                                            <th style="text-align:center;">
                                                @if ($item2->status_receive == 1 )
                                                    <span class="badge rounded-pill bg-success">Generate</span>
                                                @else
                                                    <span class="badge rounded-pill "style="background-color: #FF6633	">Pending</span>
                                                @endif
                                            </th>
                                            <th style="text-align:center;">{{ number_format($item2->sumpayment, 2, '.', ',') }}</th>
                                            <th style="text-align:left;">Baht</th>
                                        </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-md-11"></div>
                    <div class="col-md-1">
                        @if ($status == 1 )
                            <button type="button" class="btn btn-primary lift btn_modal btn-space" onclick="window.location.href='{{ url('/Document/receipt/Proposal/invoice/CheckPI/PD/'.$Proposal->id.'/'.$Proposal->Quotation_ID) }}'">
                                NEXT
                            </button>
                        @else
                            <button type="button" class="btn btn-primary lift btn_modal btn-space" disabled>
                                NEXT
                            </button>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


@endsection
