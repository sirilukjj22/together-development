@extends('layouts.masterLayout')
<style>
@media screen and (max-width: 500px) {
    .mobileHidden {
    display: none;
    }

    .mobileLabelShow {
    display: inline;
    }

    #mobileshow {
    margin-top: 60px;
    }
}
.table-revenue-detail {
    display: none;
    margin: 1rem 0;
    padding: 1rem;
    background-color: aliceblue;
    border-radius: 7px;
    color: white;
    min-height: 20rem;
    }
</style>
@section('content')
    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <small class="text-muted">Welcome to Billing Folio.</small>
                    <div class=""><span class="span1">Billing Folio (ใบเรียกเก็บเงิน)</span></div>
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
                <div class="card mb-3">
                    <div class="card-body">
                        <div id="total" class="table-revenue-detail bg-together-full" style="display: block;">
                            <div class="center" >
                                <div class="wrap-show-income">
                                    <div class="show-income-left">
                                        <div class="tech-circle-container">
                                            <div class="outer-glow-circle"></div>
                                            <div class="circle-content">
                                                <p class="circle-text">
                                                    <span>{{ number_format($total, 2) }}</span>
                                                    <span class="subtext">Total Revenue </span>
                                                </p>
                                            </div>
                                            <div class="outer-ring">
                                                <div class="rotating-dot"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="show-income-right d-grid2-1rem">
                                        <div class="bg-together card-d-grid2-auto m-h-8rem" onclick="openCity(event, 'room')">
                                            <span>Room Revenue</span>
                                            <span>{{ number_format($totalnetpriceproduct, 2) }}</span>
                                        </div>
                                        <div class="bg-together card-d-grid2-auto" onclick="openCity(event, 'fb')">
                                            <span>F&B Revenue</span>
                                            <span>{{ number_format($totalnetMeals, 2) }}</span>
                                        </div>
                                        <div class="bg-together card-d-grid2-auto" onclick="openCity(event, 'banquet')">
                                            <span>Banquet Revenue</span>
                                            <span>{{ number_format($totalnetBanquet, 2) }}</span>
                                        </div>
                                        <div class="bg-together card-d-grid2-auto" onclick="openCity(event, 'entertainment')">
                                            <span>Entertainment Revenue</span>
                                            <span>{{ number_format($totalentertainment, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="room" class="table-revenue-detail bg-together-full">
                            <div class="flex-between mb-4">
                            <h3>Room Revenue</h3>
                            <button
                                id="total"
                                onclick="openCity(event, 'total')"
                                class="bt-tg grey-back">back
                            </button>
                            </div>
                            <table id="roomTable" class="example1 ui striped table nowrap unstackable hover" >
                                <thead>
                                    <tr>
                                    <th data-priority="1">Description</th>
                                    <th >Quantity</th>
                                    <th>Unit</th>
                                    <th>Price/Unit</th>
                                    <th>Discount</th>
                                    <th>Price Discount</th>
                                    <th data-priority="1">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($room))
                                        @foreach ($room as $key => $item)
                                            @foreach ($unit as $singleUnit)
                                                @foreach ($quantity as $singleQuantity)
                                                    @if($singleUnit->id == @$item->product->unit)
                                                        @if($singleQuantity->id == @$item->product->quantity)
                                                            <tr >
                                                                <td>{{@$item->product->name_th}}</td>
                                                                <td>{{$item->Quantity}} {{ $singleUnit->name_th }}</td>
                                                                <td>{{$item->Unit}} {{ $singleQuantity->name_th }}</td>
                                                                <td>{{ number_format($item->priceproduct, 2, '.', ',') }}</td>
                                                                <td>{{$item->discount}}%</td>
                                                                <td>{{ number_format($item->priceproduct * $item->discount /100 * $item->Quantity*$item->Unit , 2, '.', ',') }}</td>
                                                                <td>{{ number_format($item->netpriceproduct, 2, '.', ',') }}</td>
                                                            </tr>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div id="fb" class="table-revenue-detail bg-together-full">
                            <div class="flex-between mb-4">
                                <h3>F&B Revenue</h3>
                                <button
                                    id="total"
                                    onclick="openCity(event, 'total')"
                                    class="bt-tg grey-back"
                                    >
                                    back
                                </button>
                            </div>
                            <table id="fbTable" class="example1 ui striped table nowrap unstackable hover" >
                                <thead>
                                    <tr>
                                    <th data-priority="1">Description</th>
                                    <th >Quantity</th>
                                    <th>Unit</th>
                                    <th>Price/Unit</th>
                                    <th>Discount</th>
                                    <th>Price Discount</th>
                                    <th data-priority="1">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($Meals))
                                        @foreach ($Meals as $key => $item)
                                            @foreach ($unit as $singleUnit)
                                                @foreach ($quantity as $singleQuantity)
                                                    @if($singleUnit->id == @$item->product->unit)
                                                        @if($singleQuantity->id == @$item->product->quantity)
                                                            <tr >
                                                                <td>{{@$item->product->name_th}}</td>
                                                                <td>{{$item->Quantity}} {{ $singleUnit->name_th }}</td>
                                                                <td>{{$item->Unit}} {{ $singleQuantity->name_th }}</td>
                                                                <td>{{ number_format($item->priceproduct, 2, '.', ',') }}</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>{{ number_format($item->netpriceproduct, 2, '.', ',') }}</td>
                                                            </tr>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div id="banquet" class="table-revenue-detail bg-together-full">
                            <div class="flex-between mb-4">
                                <h3>Banquet Revenue</h3>

                                <button
                                id="total"
                                onclick="openCity(event, 'total')"
                                class="bt-tg grey-back"
                                >
                                back
                                </button>
                            </div>
                            <table id="banquetTable" class="example1 ui striped table nowrap unstackable hover" >
                                <thead>
                                    <tr>
                                    <th data-priority="1">Description</th>
                                    <th >Quantity</th>
                                    <th>Unit</th>
                                    <th>Price/Unit</th>
                                    <th>Discount</th>
                                    <th>Price Discount</th>
                                    <th data-priority="1">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($Banquet))
                                        @foreach ($Banquet as $key => $item)
                                            @foreach ($unit as $singleUnit)
                                                @foreach ($quantity as $singleQuantity)
                                                    @if($singleUnit->id == @$item->product->unit)
                                                        @if($singleQuantity->id == @$item->product->quantity)
                                                            <tr >
                                                                <td>{{@$item->product->name_th}}</td>
                                                                <td>{{$item->Quantity}} {{ $singleUnit->name_th }}</td>
                                                                <td>{{$item->Unit}} {{ $singleQuantity->name_th }}</td>
                                                                <td>{{ number_format($item->priceproduct, 2, '.', ',') }}</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>{{ number_format($item->netpriceproduct, 2, '.', ',') }}</td>
                                                            </tr>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div id="entertainment" class="table-revenue-detail bg-together-full">
                            <div class="flex-between mb-4">
                                <h3>Entertainment Revenue</h3>
                                <button
                                    id="total"
                                    onclick="openCity(event, 'total')"
                                    class="bt-tg grey-back"
                                >
                                    back
                                </button>
                            </div>
                            <table id="entertainmentTable" class="example1 ui striped table nowrap unstackable hover" >
                                <thead>
                                    <tr>
                                    <th data-priority="1">Description</th>
                                    <th >Quantity</th>
                                    <th>Unit</th>
                                    <th>Price/Unit</th>
                                    <th>Discount</th>
                                    <th>Price Discount</th>
                                    <th data-priority="1">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($entertainment))
                                        @foreach ($entertainment as $key => $item)
                                            @foreach ($unit as $singleUnit)
                                                @foreach ($quantity as $singleQuantity)
                                                    @if($singleUnit->id == @$item->product->unit)
                                                        @if($singleQuantity->id == @$item->product->quantity)
                                                            <tr >
                                                                <td>{{@$item->product->name_th}}</td>
                                                                <td>{{$item->Quantity}} {{ $singleUnit->name_th }}</td>
                                                                <td>{{$item->Unit}} {{ $singleQuantity->name_th }}</td>
                                                                <td>{{ number_format($item->priceproduct, 2, '.', ',') }}</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>{{ number_format($item->netpriceproduct, 2, '.', ',') }}</td>
                                                            </tr>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-body">
                        <b>Proposal</b>
                        <table id="ProposalTable" class="example1 ui striped table nowrap unstackable hover" style="width:100%">
                            <thead >
                                <tr>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:20%;">Proposal ID</th>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:10%;">Subtotal</th>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:15%;">Special Discount Bath</th>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:15%;">Total Balance</th>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:15%;">Price Before Tax</th>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:15%;">Value Added Tax</th>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:10%;">Total Amount</th>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:5%;">List</th>
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
                                    <th style="text-align:left;">
                                        <a type="button" class="btn btn-light-info" target="_blank" href="{{ url('/Receipt/Quotation/view/quotation/view/'.$ProposalID) }}">
                                            View
                                        </a>
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body">
                        <b>Invoice</b>
                        <table id="InvoiceTable" class="example1 ui striped table nowrap unstackable hover" style="width:100%">
                            <thead >
                                <tr>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:50%;">Proforma Invoice ID</th>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;">Payment</th>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;">Valid</th>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;">Status</th>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;">Total Amount</th>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:5%;">List</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($invoices))
                                    @foreach ($invoices as $key => $item2)
                                            <tr>
                                                <th style="text-align:left;">{{$item2->Invoice_ID}}</th>
                                                <th style="text-align:center;">{{ number_format($item2->sumpayment, 2, '.', ',') }}</th>
                                                <th style="text-align:center;">{{$item2->valid}}</th>
                                                <th style="text-align:center;">
                                                    <span class="badge rounded-pill "style="background-color: #FF6633	">Pending</span>
                                                </th>
                                                <th style="text-align:center;">{{ number_format($item2->sumpayment, 2, '.', ',') }}</th>
                                                <th style="text-align:left;">
                                                    <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ url('/Document/BillingFolio/Proposal/invoice/Generate/Paid/'.$item2->id) }}'">
                                                        Paid
                                                    </button>
                                                </th>
                                            </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body">
                        <b>Receipt</b>
                        <table id="ReceiptTable" class="example1 ui striped table nowrap unstackable hover" style="width:100%">
                            <thead >
                                <tr>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">Receive ID</th>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">Proforma Invoice ID</th>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;">Category</th>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;">paymentDate</th>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;">Status</th>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;">Total Amount</th>
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:5%;">List</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($Receipt))
                                    @foreach ($Receipt as $key => $item3)
                                        <tr>
                                            <th style="text-align:left;">{{$item3->Receipt_ID}}</th>
                                            <th style="text-align:left;">{{$item3->Invoice_ID}}</th>
                                            <th style="text-align:center;">{{ $item3->category}}</th>
                                            <th style="text-align:center;">{{$item3->paymentDate}}</th>
                                            <th style="text-align:center;">
                                                <span class="badge rounded-pill bg-success">Approved</span>
                                            </th>
                                            <th style="text-align:center;">{{ number_format($item3->Amount, 2, '.', ',') }}</th>
                                            <th style="text-align:left;">
                                                <a type="button" class="btn btn-light-info" target="_blank" href="{{ url('/Document/BillingFolio/Proposal/invoice/view/'.$item3->id) }}">
                                                    View
                                                </a>
                                            </th>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-ml-12 col-sm-12">
            <div class="col-lg-1 col-ml-1 col-sm-4" style="float: right">
                <div class="row">
                    @if ($status == '1')
                        <button type="button" class="btn btn-color-green lift btn_modal">
                            Next
                        </button>
                    @endif
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

        function openCity(evt, cityName) {
            // ซ่อนทุกตาราง
            $(".bg-together-full").hide();
            $(".box-revenue-detail").removeClass("active");

            // แสดงตารางที่เลือก
            $("#" + cityName).show();

            // เพิ่ม active class สำหรับการเลือกแท็บ
            $(evt.currentTarget).addClass("active");

            // ถ้า DataTable ถูกใช้งานกับ cityName ให้ทำการรีเฟรช DataTables
            if ($.fn.DataTable.isDataTable("#" + cityName + " table")) {
            $("#" + cityName + " table").DataTable().columns.adjust().draw();
            }
        }
    </script>
    <script>
        const table_name = ['roomTable','fbTable','banquetTable','entertainmentTable','ProposalTable','InvoiceTable','ReceiptTable'];
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

    </script>
@endsection
