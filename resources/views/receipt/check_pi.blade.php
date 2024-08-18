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
                <small class="text-muted">Welcome to Receipt / Tax Invoice.</small>
                <h1 class="h4 mt-1">Receipt / Tax Invoice</h1>
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
    li {
    list-style: none;
    }

    .bg-tegether-full {
    background-image: linear-gradient(to right, rgb(10, 87, 83), rgb(4, 8, 8));
    }

    .bg-tegether {
    background-image: linear-gradient(to right, rgb(4, 8, 8), rgb(12, 73, 70));
    }

    .bg-tegether:hover {
    background: linear-gradient(rgba(17, 17, 16, 0.3), rgba(0, 0, 0, 0.7)),
        url(img/together-view.jpg) center no-repeat;
    }

    .bg-tegether-inside {
    background-image: linear-gradient(to right, rgb(9, 55, 55), rgb(2, 26, 24));
    }

    .bg-tegether-inside:hover {
    background-image: linear-gradient(to right, rgb(6, 94, 56), rgb(2, 26, 16));
    }

    .a {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    transition: 0.3s;
    }

    .border-rd-7px {
    border-radius: 7px;
    }

    .a:hover {
    overflow: visible;
    white-space: wrap;
    font-weight: 600;
    color: #093835;
    text-align: start;
    /* background-color: #d9f7fa; */
    }

    .center {
    display: flex;
    justify-content: center;
    align-items: center;
    }

    .flex-between {
    display: flex;
    justify-content: space-between;
    align-items: center;
    }

    .box {
    height: 1fr;
    background-color: #2c7f7a;
    height: 2rem;
    }

    /* Table */
    .table-revenue-detail {
    display: none;
    margin: 1rem 0;
    padding: 1rem;
    background-color: aliceblue;
    border-radius: 7px;
    color: white;
    position: relative;
    }

    #table-revenue td {
    text-align: center;
    }

    #table-revenue th {
    text-transform: uppercase;
    text-align: center;
    }

    #table-revenue td.total {
    font-weight: 600;
    }

    /* Card */

    .wrap-card-revenue-inside {
    display: grid;
    gap: 4px;
    }

    @media (min-width: 600px) {
    .wrap-card-revenue-inside {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 4px;
    }
    }

    @media (min-width: 600px) and (max-width: 900px) {
    .col-span-2 {
        grid-column: 1 / 3;
    }
    }

    @media (min-width: 900px) {
    .wrap-card-revenue-inside {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 4px;
    }
    }

    .card-revenue-inside-sum {
    display: block;
    color: white;
    text-align: center;
    align-content: center;
    border: 1px solid white;
    border-radius: 50%;
    width: 15rem;
    height: 15rem;
    font-size: 1.8rem;
    margin: 01rem;
    }

    .card-revenue-inside-sum :nth-child(2) {
    font-size: 1rem;
    }

    .card-revenue-inside {
    flex-grow: 1;
    min-height: 100%;
    padding: 1rem;
    display: grid;
    border-radius: 7px;
    grid-template-rows: auto 1fr auto;
    }

    .card-revenue-inside div {
    background-color: rgba(249, 247, 247, 0.6);
    font-weight: 400;
    }

    .card-revenue-inside h1 {
    color: white;
    font-size: 1.2rem;
    text-align: center;
    }

    .card-revenue-inside div p {
    /* display: flex;
    justify-content: space-between; */
    display: grid;
    grid-template-columns: auto auto;
    gap: 10px;
    padding: 0.3rem 0.5rem;
    margin: 0.5rem;
    border-bottom: 1px solid rgb(207, 215, 215);
    }

    .card-revenue-inside div p :nth-child(2) {
    text-align: right;
    }

    .card-revenue-inside .total {
    display: flex;
    justify-content: space-between;
    padding: 0.3rem 0.5rem;
    font-weight: 700;
    }

    /* card 1 */

    .wrap-show-income {
    display: grid;
    width: 100%;
    margin: 1rem 0;
    text-align: center;
    }

    @media (min-width: 1000px) {
    .wrap-show-income {
        display: grid;
        grid-template-columns: 20rem auto;
    }
    }

    .show-income-left {
    display: grid;
    color: white;
    text-align: center;
    align-content: center;
    border: 1px solid white;
    border-radius: 50%;
    width: 15rem;
    height: 15rem;
    font-size: 1.8rem;
    margin: 1rem;
    }

    .show-income-left :nth-child(2) {
    font-size: 1rem;
    }

    .show-income-right {
    width: 100%;
    display: grid;
    gap: 0.5rem;
    color: white;
    }

    @media (min-width: 600px) {
    .show-income-right {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    }

    .show-income-right div {
    display: grid;
    grid-template-columns: auto auto;
    border: rgba(227, 224, 224, 0.389) 1px solid;
    padding: 1rem;
    border-radius: 7px;
    font-size: 1.3rem;
    align-items: center;
    min-height: 6rem;
    }

    .show-income-right div :nth-child(1) {
    text-align: left;
    }

    .show-income-right div :nth-child(2) {
    text-align: right;
    }

    .back-button {
    position: absolute;
    right: 1rem;
    top: 0;
    border: white 1px solid;
    padding: 0.5rem 2rem;
    border-radius: 7px;
    margin: 0.5rem;
    background-color: rgba(243, 248, 248, 0.4);
    }

    .back-button:before {
    content: "\00AB";
    font-size: 1.4rem;
    margin-right: 0.5rem;
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
<!-- main body area -->
<div class="main px-xl-5 px-lg-4 px-md-3">
    <div id="content-index" class="body-header border-bottom d-flex py-3">
      <div class="container">
        <div id="box"></div>

        <script>
          function openCity(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName(
              "table-revenue-detail"
            );
            for (i = 0; i < tabcontent.length; i++) {
              tabcontent[i].style.display = "none";
            }
            tablinks =
              document.getElementsByClassName("box-revenue-detail");
            for (i = 0; i < tablinks.length; i++) {
              tablinks[i].className = tablinks[i].className.replace(
                " active",
                ""
              );
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
          }
        </script>

        <!-- Table1 Room -->

        <div>
            <div
                id="total"
                class="table-revenue-detail bg-tegether-full"
                style="display: block"
            >
                <div id="total" class="center">
                <div class="wrap-show-income">
                    <div class="center" style="margin: 1rem">
                    <div>
                        <h1 class="show-income-left">
                        <span>{{ number_format($totalreceipt, 2, '.', ',') }}</span>
                        <span>Total Revenue</span>
                        </h1>
                    </div>
                    </div>

                    <div class="show-income-right">
                    <div
                        class="bg-tegether-inside"
                        onclick="openCity(event, 'room')"
                    >
                        <span>Room Revenue</span><span>{{ number_format($totalnetpriceproduct, 2, '.', ',') }}</span>
                    </div>
                    <div
                        class="bg-tegether-inside"
                        onclick="openCity(event, 'fb')"
                    >
                        <span>F&B Revenue</span><span>{{ number_format($totalnetMeals, 2, '.', ',') }}</span>
                    </div>
                    <div
                        class="bg-tegether-inside"
                        onclick="openCity(event, 'banquet')"
                    >
                        <span>Banquet Revenue</span><span>{{ number_format($totalnetBanquet, 2, '.', ',') }}</span>
                    </div>
                    <div
                        class="bg-tegether-inside"
                        onclick="openCity(event, 'entertainment')"
                    >
                        <span>Entertainment Revenue</span><span>{{ number_format($totalentertainment, 2, '.', ',') }}</span>
                    </div>
                    </div>
                </div>
                </div>
            </div>

            <div id="room" class="table-revenue-detail bg-tegether-full">
                <span
                    id="total" style="float: right"
                    onclick="openCity(event, 'total')"
                    type="button" class="btn btn-secondary lift btn_modal"
                    >back</span
                >
                <h3>Room Revenue</h3>
                <div>
                    <table id="table-revenue" class="example ui striped table nowrap unstackable hover">
                        <thead>
                            <tr>
                                <th data-priority="1">Description</th>
                                <th>Quantity</th>
                                <th>unit</th>
                                <th>price/unit</th>
                                <th>discount</th>
                                <th>net price / unit</th>
                                <th data-priority="1">amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($room))
                                @foreach ($room as $key => $item)
                                    @foreach ($unit as $singleUnit)
                                        @if($singleUnit->id == @$item->product->unit)
                                            <tr>
                                                <th>{{@$item->product->name_th}}</th>
                                                <td>{{$item->Quantity}}</td>
                                                <td>{{ $singleUnit->name_th }}</td>
                                                <td>{{ number_format($item->priceproduct, 2, '.', ',') }}</td>
                                                <td>{{$item->discount}}%</td>
                                                <td>{{ number_format($item->priceproduct * $item->discount /100, 2, '.', ',') }}</td>
                                                <td>{{ number_format($item->netpriceproduct, 2, '.', ',') }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                            @endif
                        <tr>
                            <th>Total <span></span></th>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="total">{{ number_format($totalnetpriceproduct, 2, '.', ',')}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Table2 F&B Revenue -->
            <div id="fb" class="table-revenue-detail bg-tegether-full">
                <span
                    id="total" style="float: right"
                    onclick="openCity(event, 'total')"
                    type="button" class="btn btn-secondary lift btn_modal"
                    >back</span
                >
                <h3>F&B Revenue</h3>
                <div>
                    <table
                    id="table-revenue"
                    class="example ui striped table nowrap unstackable hover"
                    >
                        <thead>
                            <tr>
                            <th data-priority="1">Description</th>
                            <th>Quantity</th>
                            <th>unit</th>
                            <th>price/unit</th>
                            <th>discount</th>
                            <th>net price / unit</th>
                            <th data-priority="1">amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($Meals))
                                @foreach ($Meals as $key => $item)
                                    @foreach ($unit as $singleUnit)
                                        @if($singleUnit->id == @$item->product->unit)
                                            <tr>
                                                <th>{{@$item->product->name_th}}</th>
                                                <td>{{$item->Quantity}}</td>
                                                <td>{{ $singleUnit->name_th }}</td>
                                                <td>{{ number_format($item->priceproduct, 2, '.', ',') }}</td>
                                                <td>{{$item->discount}}%</td>
                                                <td>{{ number_format($item->priceproduct * $item->discount /100, 2, '.', ',') }}</td>
                                                <td>{{ number_format($item->netpriceproduct, 2, '.', ',') }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                            @endif
                            <tr>
                                <th>Total <span></span></th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="total">{{ number_format($totalnetMeals, 2, '.', ',')}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Table3 Banquet Revenue-->
            <div id="banquet" class="table-revenue-detail bg-tegether-full">
                <span
                    id="total" style="float: right"
                    onclick="openCity(event, 'total')"
                    type="button" class="btn btn-secondary lift btn_modal"
                    >back</span
                >
                <h3>Banquet Revenue</h3>
                <div>
                    <table
                    id="table-revenue"
                    class="example ui striped table nowrap unstackable hover"
                    >
                    <thead>
                        <tr>
                        <th data-priority="1">Description</th>
                        <th>Quantity</th>
                        <th>unit</th>
                        <th>price/unit</th>
                        <th>discount</th>
                        <th>net price / unit</th>
                        <th data-priority="1">amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($Banquet))
                            @foreach ($Banquet as $key => $item)
                                @foreach ($unit as $singleUnit)
                                    @if($singleUnit->id == @$item->product->unit)
                                        <tr>
                                            <th>{{@$item->product->name_th}}</th>
                                            <td>{{$item->Quantity}}</td>
                                            <td>{{ $singleUnit->name_th }}</td>
                                            <td>{{ number_format($item->priceproduct, 2, '.', ',') }}</td>
                                            <td>{{$item->discount}}%</td>
                                            <td>{{ number_format($item->priceproduct * $item->discount /100, 2, '.', ',') }}</td>
                                            <td>{{ number_format($item->netpriceproduct, 2, '.', ',') }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                        @endif
                        <tr>
                            <th>Total <span></span></th>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="total">{{ number_format($totalnetBanquet, 2, '.', ',') }}</td>
                        </tr>
                    </tbody>
                    </table>
                </div>
            </div>

            <!-- Table4 Entertainment Revenue -->
            <div
                id="entertainment"
                class="table-revenue-detail bg-tegether-full"
            >
                <span
                    id="total" style="float: right"
                    onclick="openCity(event, 'total')"
                    type="button" class="btn btn-secondary lift btn_modal"
                    >back</span
                >
                <h3>Entertainment Revenue</h3>
                <div>
                <table
                    id="table-revenue"
                    class="example ui striped table nowrap unstackable hover"
                >
                    <thead>
                    <tr>
                        <th data-priority="1">Description</th>
                        <th>Quantity</th>
                        <th>unit</th>
                        <th>price/unit</th>
                        <th>discount</th>
                        <th>net price / unit</th>
                        <th data-priority="1">amount</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if(!empty($entertainment))
                        @foreach ($entertainment as $key => $item)
                            @foreach ($unit as $singleUnit)
                                @if($singleUnit->id == @$item->product->unit)
                                    <tr>
                                        <th>{{@$item->product->name_th}}</th>
                                        <td>{{$item->Quantity}}</td>
                                        <td>{{ $singleUnit->name_th }}</td>
                                        <td>{{ number_format($item->priceproduct, 2, '.', ',') }}</td>
                                        <td>{{$item->discount}}%</td>
                                        <td>{{ number_format($item->priceproduct * $item->discount /100, 2, '.', ',') }}</td>
                                        <td>{{ number_format($item->netpriceproduct, 2, '.', ',') }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endforeach
                    @endif
                    <tr>
                        <th>Total <span></span></th>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="total">{{ number_format($totalentertainment, 2, '.', ',') }}</td>
                    </tr>
                    </tbody>
                </table>
                </div>
            </div>
            </div>
        </div>
    </div>
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
                                    <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:5%;">VIEW</th>
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
                                        <button type="button" class="btn btn-light-info" onclick="window.location.href='{{ url('/Receipt/Quotation/view/quotation/view/'.$ProposalID) }}'">
                                           View
                                        </button>
                                    </th>
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
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:5%;">VIEW</th>
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
                                            <th style="text-align:left;">
                                                <button type="button" class="btn btn-light-info" onclick="window.location.href='{{ url('/Document/receipt/Proposal/invoice/view/'.$item->id) }}'">
                                                    View
                                                </button>
                                            </th>
                                        </tr>
                                    @endforeach
                                @endif
                                <tr>
                                    <th style="text-align:left;">ToTal balance</th>
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
                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:5%;">VIEW</th>
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
                                            <th style="text-align:left;">
                                                <button type="button" class="btn btn-light-info" onclick="window.location.href='{{ url('/Receipt/Invice/view/'.$item2->id) }}'">
                                                    View
                                                </button>
                                            </th>
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
