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

    :root {
    --primary-color: #2b81e4;
    --secondary-color: #eee;
    --white-color: #fff;
    --grey-color: #555;
    --light-grey-color: #777;
    --together1-color: #2c7f7a;
    --together2-color: #008996;
    --together3-color: #3cc3b1;
    --together4-color: #68c2c3;
    --together5-color: #4d4d4d;
    --together6-color: #87888a;
    --together7-color: v;
    }

    .center {
    display: flex;
    justify-content: center;
    align-items: center;
    }

    .total-revenue-details {
    text-align: center;
    }

    .total-revenue-details-sub {
    display: grid;
    gap: 0;
    }

    .sub-revenue {
    /* border: red 6px solid; */
    display: grid;
    gap: 0;
    }

    .sub-revenue h3 {
    /* border: yellow 2px solid; */
    display: flex;
    justify-content: space-between;
    padding: 0.7rem 2rem;
    background-color: var(--together1-color);
    color: var(--white-color);
    font-size: 1.7rem;
    font-weight: 400;
    margin: 0;
    }

    .sub-revenue table {
    /* border: yellow 2px solid; */
    margin: 0;
    }

    /* table */

    table {
    table-layout: fixed;
    width: 100%;
    border-collapse: collapse;
    background-image: linear-gradient(rgb(225, 244, 237), #408e7e6a);
    /* border: 3px solid purple; */
    }

    thead th:nth-child(1) {
    width: 25%;
    }

    thead th:nth-child(2) {
    width: 10%;
    }

    thead th:nth-child(3) {
    width: 10%;
    }

    thead th:nth-child(4) {
    width: 15%;
    }

    thead th:nth-child(5) {
    width: 10%;
    }

    thead th:nth-child(6) {
    width: 15%;
    }

    thead th:nth-child(7) {
    width: 15%;
    }

    th,
    td {
    padding: 5px;
    font-weight: 400;
    border-bottom: 1px solid #3f6b683a;
    /* border: 1px solid var(--together1-color); */
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
                    <div class="total-revenue-details">
                        <div class="total-revenue-details-sub">
                        <div class="sub-revenue">
                            <h3 class=""><span>Room Revenue</span><span>75,000.00</span></h3>
                            <table class="revenue-table">
                                <thead>
                                    <tr>
                                        <th scope="col"><strong>Description</strong></th>
                                        <th scope="col"><strong>Quantity</strong></th>
                                        <th scope="col"><strong>Unit</strong></th>
                                        <th scope="col"><strong>Price/Unit</strong></th>
                                        <th scope="col"><strong>Discount</strong></th>
                                        <th scope="col"><strong>Price Discount</strong></th>
                                        <th scope="col"><strong>Amount</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">Deluxe king Mountain View</th>
                                        <td>20</td>
                                        <td>ห้อง</td>
                                        <td>4,000.00</td>
                                        <td>40%</td>
                                        <td>1,600.00</td>
                                        <td>48,000.00</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Deluxe king Water Park View</th>
                                        <td>10</td>
                                        <td>ห้อง</td>
                                        <td>4,500.00</td>
                                        <td>40%</td>
                                        <td>2,700.00</td>
                                        <td>27,000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="sub-revenue">
                            <h3><span>F&B Revenue</span><span>45,600.00</span></h3>
                            <table>
                                <thead>
                                    <tr>
                                        <th scope="col"><strong>Description</strong></th>
                                        <th scope="col"><strong>Quantity</strong></th>
                                        <th scope="col"><strong>Unit</strong></th>
                                        <th scope="col"><strong>Price/Unit</strong></th>
                                        <th scope="col"><strong>Discount</strong></th>
                                        <th scope="col"><strong>Price Discount</strong></th>
                                        <th scope="col"><strong>Amount</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">Lunch</th>
                                        <td>60</td>
                                        <td>ท่าน</td>
                                        <td>300.00</td>
                                        <td>0%</td>
                                        <td>300.00</td>
                                        <td>18,000.00</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Dinner</th>
                                        <td>60</td>
                                        <td>ท่าน</td>
                                        <td>400.00</td>
                                        <td>0%</td>
                                        <td>400.00</td>
                                        <td>24,000.00</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Soft drink</th>
                                        <td>60</td>
                                        <td>ท่าน</td>
                                        <td>60.00</td>
                                        <td>0%</td>
                                        <td>60.00</td>
                                        <td>3,600.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="sub-revenue">
                            <h3><span>Banquet Revenue</span><span>2,400.00</span></h3>
                            <table>
                                <thead>
                                    <tr>
                                        <th scope="col"><strong>Description</strong></th>
                                        <th scope="col"><strong>Quantity</strong></th>
                                        <th scope="col"><strong>Unit</strong></th>
                                        <th scope="col"><strong>Price/Unit</strong></th>
                                        <th scope="col"><strong>Discount</strong></th>
                                        <th scope="col"><strong>Price Discount</strong></th>
                                        <th scope="col"><strong>Amount</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                    <th scope="row">Meeting room Type B Full day</th>
                                    <td>1</td>
                                    <td>ห้อง</td>
                                    <td>4,000.00</td>
                                    <td>40%</td>
                                    <td>2,400.00</td>
                                    <td>2,400.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="sub-revenue">
                            <h3><span>Entertainment Revenue</span><span>2,700.00</span></h3>
                            <table>
                                <thead>
                                    <tr>
                                        <th scope="col"><strong>Description</strong></th>
                                        <th scope="col"><strong>Quantity</strong></th>
                                        <th scope="col"><strong>Unit</strong></th>
                                        <th scope="col"><strong>Price/Unit</strong></th>
                                        <th scope="col"><strong>Discount</strong></th>
                                        <th scope="col"><strong>Price Discount</strong></th>
                                        <th scope="col"><strong>Amount</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                    <th scope="row">Snooker room</th>
                                    <td>1</td>
                                    <td>ห้อง</td>
                                    <td>4,000.00</td>
                                    <td>40%</td>
                                    <td>2,700.00</td>
                                    <td>2,700.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        <div class="sub-revenue" >
                            <h3 style="justify-content: center;gap:2rem;width: 100%;"><span>Entertainment Revenue</span><span>125,100.00</span></h3></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-md-11"></div>
                    <div class="col-md-1">
                        {{-- @if ($status == 1 )
                            <button type="button" class="btn btn-primary lift btn_modal btn-space" onclick="window.location.href='{{ url('/Document/receipt/Proposal/invoice/CheckPI/PD/'.$Proposal->id.'/'.$Proposal->Quotation_ID) }}'">
                                NEXT
                            </button>
                        @else
                            <button type="button" class="btn btn-primary lift btn_modal btn-space" disabled>
                                NEXT
                            </button>
                        @endif --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
