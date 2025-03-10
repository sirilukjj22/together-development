<!DOCTYPE html>


<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Report Revenue</title>
    <style>
        @page {
            margin: 0.2cm 1.0cm 0.5cm 1.0cm;
            size: A4 landscape; /* กำหนดขนาดเป็น A4 และแนวนอน */
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #0087C3;
            text-decoration: none;
        }

        body {
            position: relative;
            margin: 0 auto;
            color: #555555;
            background: #FFFFFF;
            font-size: 14px;
            font-family: "THSarabunNew";
        }

        header {
            padding-top: 10px;
            /* padding: 10px 0; */
            /* margin-bottom: 10px; */
            /* border-bottom: 1px solid #AAAAAA; */
        }

        .logo {
            float: left;
            margin-top: 8px;
        }

        .logo img {
            height: 80px;
        }

        #company {
            float: right;
            text-align: right;
        }


        #details {
            margin-top: -5px;
        }

        #client {
            padding-left: 6px;
            /* border-left: 6px solid #07883d; */
            float: left;
        }

        #client .to {
            color: #777777;
        }

        /* #invoice .date {
            font-size: 1.1em;
            color: #777777;
        } */

        table {
            width: 100%;
            padding: 5px;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 5px;
            font-size: 18px;
            color: #000;
            border: none;

        }

        footer {
            color: #777777;
            width: 100%;
            height: 5%;
            position: absolute;
            bottom: 10px;
            /* border-top: 1px solid #AAAAAA; */
            padding: 8px 0;
            text-align: center;
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url("{{ public_path('fonts/THSarabunNew.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            src: url("{{ public_path('fonts/THSarabunNew Bold.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: normal;
            src: url("{{ public_path('fonts/THSarabunNew Italic.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: bold;
            src: url("{{ public_path('fonts/THSarabunNew BoldItalic.ttf') }}") format('truetype');
        }

        h2.name {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin: 0;
        }

        .add-text {
            font-size: 16px;
            line-height: 12px;
            margin-left: 5px;
        }

        .add-name {
            font-size: 18px;
        }

        .add-tf {
            font-size: 18px;
        }

        .add-l1 {
            font-size: 18px;
            color: #c31d00
        }

        .td_right {
            text-align: right;
        }

        .receive {
            text-align: right;
            font-size: 18px;
        }

        /* new */
        .subject {
            font-size: 18px;
        }

        .date {
            font-size: 18px;
        }

        .customer {
            font-size: 18px;
        }

        .thank {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .lh-5 {
            line-height: 5px;
        }

        .lh-10 {
            line-height: 10px;
        }

        .lh-15 {
            line-height: 15px;
        }

        table#topic,
        table#topic tr th,
        table#topic tr td {
            color: #020202;
            line-height: 12px;
            background-color: white;
            border: none;
        }

        table#detail thead th {
            background-color: rgb(14, 68, 63);
            color: #FFFFFF;
            /* border: 0.5px solid #109699; */
        }

        /* table#detail tbody td {
            background-color: #FFFFFF;
            border: none;
        } */

        table#detail tbody th {
            background-color: rgb(42, 136, 127);
            color: #FFFFFF;
            /* border: none; */
        }

        table#detail {
            line-height: 10px;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 15px;
        }

        table#detail tr th,
        table#detail tr td {
            padding: 5px;
            border: 0.5px solid rgb(14, 68, 63);
        }

        table#detail tr th:first-child,
        table#detail tr td:first-child {
            /* border-left: 1px solid #109699; */
        }

        /* top-left border-radius */
        table tr:first-child th:first-child {
            border-top-left-radius: 6px;
        }

        /* top-right border-radius */
        table tr:first-child th:last-child {
            border-top-right-radius: 6px;
        }

        /* bottom-left border-radius */
        table#detail tr:last-child td:first-child {
            border-bottom-left-radius: 6px;
        }

        /* bottom-right border-radius */
        table#detail tr:last-child td:last-child {
            border-bottom-right-radius: 6px;
        }

        table#detail tr:last-child td {
            /* border-bottom: 1px solid #109699; */
        }

        table#signature tfoot th {
            border: 0px;
        }

        .bd_dotted {
            border-bottom: 1px dotted #000;
        }

        .bdt {
            border-top: none !important;
        }

        .bdr {
            border-right: none !important;
        }

        .bdb {
            border-bottom: none !important;
        }

        .bdl {
            border-left: none !important;
        }

        .tr {
            text-align: right;
        }

        .tl {
            text-align: left;
        }

        .tc {
            text-align: center;
        }

        /* .doc_name {
            height: 90px;
        } */

        hr {
            border-bottom: 0.1px solid #AAAAAA;
        }

        table#topic2 {
            line-height: 10px;
            border-collapse: separate;
            border-spacing: 0;
        }

        table#topic2 tr th,
        table#topic2 tr td {
            /* background-color: white; */
            border-right: none;
            border-bottom: none;
            border-top: none;
            border-left: none;
            border: none;
            padding: 5px;
        }

        table#topic2 tr th:first-child,
        table#topic2 tr td:first-child {
            border-left: none;
            border: none;
        }

        /* top-left border-radius */
        table#topic2 tr:first-child td#b-radius {
            border-top-left-radius: 6px;
        }

        /* top-right border-radius */
        table#topic2 tr:first-child td:last-child {
            border-top-right-radius: 6px;
        }

        /* bottom-left border-radius */
        table#topic2 tr:last-child td:first-child {
            border-bottom-left-radius: 6px;
        }

        /* bottom-right border-radius */
        table#topic2 tr:last-child td:last-child {
            border-bottom-right-radius: 6px;
        }

        /* bottom-right border-radius */
        table#topic2 tr:last-child td:last-child {
            border-bottom-right-radius: 6px;
        }

        .document-box-hr-color {
            background-color: white;
        }

        .document-box-color {
            background-color: #d2efff;
        }

        img.bank {
            border-radius: 50%;
        }

        .wrapper-page {
            page-break-after: always;
        }

        .wrapper-page:last-child {
            page-break-after: avoid;
        }

        .tr-color-orange td {
            background-color: #ffe6d2;
        }

        .tr-color-blue td{
                background-color: #d2efff;
          }
    </style>
</head>

<body>
@php
    $sum_front_cash = 0;
    $sum_front_transfer = 0;
    $sum_guest_cash = 0;
    $sum_guest_transfer = 0;
    $sum_all_outlet_cash = 0;
    $sum_all_outlet_transfer = 0;

    // Credit Charge
    $sum_credit_front = 0;
    $sum_credit_guest = 0;
    $sum_credit_all_outlet = 0;
    $sum_credit_fee = 0;
    $sum_credit_revenue = 0;

    // Agoda
    $sum_agoda_charge = 0;
    $sum_agoda_fee = 0;
    $sum_agoda_revenue = 0;
    $sum_agoda_paid = 0;
    $sum_agoda_outstanding = 0;

    // Other
    $sum_other_revenue = 0;

    // Summary Hotel
    $sum_all_hotel_agoda = 0;
    $sum_all_cash = 0;
    $sum_all_transfer = 0;
    $sum_all_agoda_outstanding = 0;

    // Water Park
    $sum_water_cash = 0;
    $sum_water_transfer = 0;
    $sum_water_credit_charge = 0;
    $sum_water_credit_fee = 0;
    $sum_water_credit_revenue = 0;
    $sum_all_water = 0;

    // Elexa
    $sum_ev_charge = 0;
    $sum_ev_fee = 0;
    $sum_ev_revenue = 0;
    $sum_ev_paid = 0;
    $sum_ev_outstanding = 0;

    // Summary Revenue
    $sum_all_revenue = 0;

    // Payment
    $sum_hotel = 0;
    $sum_water_park = 0;
    $sum_exlexa = 0;
    $sum_total_revenue = 0;

    // Revenue Outstanding
    $sum_total_outstanding = 0;

    $round_number = 10;
    $start_round = 1;
    $page_all = ($page_item * 4) + 4;
    $page_next = 0;
@endphp

@for ($i = 1; $i <= $page_item; $i++)
<div class="wrapper-page">
    <header class="clearfix" style="color: #020202;">
        <div class="logo">
            <img src="logo/logo_crop.png">
        </div>
        <div class="logo">
            <div class="add-text" style="line-height:14px;">
                <b style="font-size:30px;">Together Resort Limited Partnership</b>
                <br>
                <b>
                    168 Moo 2 Kaengkrachan Phetchaburi 76170
                    <br>
                    Tel: +66 (0) 32 708 888 | Fax: +66 (0) 32 708 888
                    <br>
                    Hotel Tax ID 0763559000169 | Email: reservation@together-resort.com
                </b>
            </div>
        </div>
    </header>
    <hr>
    <main>
        <div class="clearfix" style="color: #020202;">
            <table id="topic" cellpadding="2" style="margin-bottom:0px;">
                <tr>
                    <td width="10%" align="left"><b>Date : </b></td>
                    <td width="45%" align="left">
                        {{ date('d/m/Y', strtotime($data_query[($start_round - 1)]['date'])) }} - {{ date('d/m/Y', strtotime(isset($data_query[($round_number - 1)]['date']) ? $data_query[($round_number - 1)]['date'] : $data_query[count($data_query) - 1]['date'])) }}
                    </td>
                    <td width="30%"></td>
                    <td><i>Page {{ $page_next += 1 }}/{{ $page_all }}</i></td>
                </tr>
            </table>
        </div>
        <table id="detail" cellpadding="5" style="line-height: 12px;">
            <thead>
                <tr style="background-color: rgba(7, 45, 41, 0.734);">
                    <th>Description</th>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <th>{{ date('d/m/y', strtotime($item->date)) }}</th>
                            @endif
                        @endforeach
                        {{-- <th>Total</th> --}}
                    @else
                        <th>Today</th>
                        <th>M-T-D</th>
                        <th>Y-T-D</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="100%" style="text-align: left; background-color: rgb(187, 226, 226);"><b>Front Desk Revenue</b></td>
                </tr>
                <tr>
                    <td>Cash</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->front_cash, 2) }}</td>
                                @php
                                    $sum_front_cash += $item->front_cash;
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_front_cash, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td>Bank Transfer</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->front_transfer, 2) }}</td>
                                @php
                                    $sum_front_transfer += $item->front_transfer;
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_front_transfer, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td colspan="100%" style="text-align: left; background-color: rgb(187, 226, 226);"><b>Guest Deposit Revenue</b></td>
                </tr>
                <tr>
                    <td>Cash</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->room_cash, 2) }}</td>
                                @php
                                    $sum_guest_cash += $item->room_cash;
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_guest_cash, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td>Bank Transfer</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->room_transfer, 2) }}</td>
                                @php
                                    $sum_guest_transfer += $item->room_transfer;
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_guest_transfer, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td colspan="100%" style="text-align: left; background-color: rgb(187, 226, 226);"><b>All Outlet Revenue</b></td>
                </tr>
                <tr>
                    <td>Cash</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->fb_cash, 2) }}</td>
                                @php
                                    $sum_all_outlet_cash += $item->fb_cash;
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_all_outlet_cash, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td>Bank Transfer</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->fb_transfer, 2) }}</td>
                                @php
                                    $sum_all_outlet_transfer += $item->fb_transfer;
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_all_outlet_transfer, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td colspan="100%" style="text-align: left; background-color: rgb(187, 226, 226);"><b>Hotel Credit Card Revenue</b></td>
                </tr>
                <tr>
                    <td>credit card front desk charge</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->front_charge, 2) }}</td>
                                @php
                                    $sum_credit_front += $item->front_charge
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_credit_front, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td>credit card guest deposit charge</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->guest_charge, 2) }}</td>
                                @php
                                    $sum_credit_guest += $item->guest_charge
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_credit_guest, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td>credit card all outlet charge</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->outlet_charge, 2) }}</td>
                                @php
                                    $sum_credit_all_outlet += $item->outlet_charge
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_credit_all_outlet, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td>credit card fee</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->fee, 2) }}</td>
                                @php
                                    $sum_credit_fee += $item->fee
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_credit_fee, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td>credit card revenue (bank transfer)</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->total_credit, 2) }}</td>
                                @php
                                    $sum_credit_revenue += $item->total_credit
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_credit_revenue, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
            </tbody>
        </table>
    </main>
    <footer>
        <hr>
        <h4>Together Resort Limited Partnership</h4>
    </footer>
</div>

{{-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}

<div class="wrapper-page">
    <header class="clearfix" style="color: #020202;">
        <div class="logo">
            <img src="logo/logo_crop.png">
        </div>
        <div class="logo">
            <div class="add-text" style="line-height:14px;">
                <b style="font-size:30px;">Together Resort Limited Partnership</b>
                <br>
                <b>
                    168 Moo 2 Kaengkrachan Phetchaburi 76170
                    <br>
                    Tel: +66 (0) 32 708 888 | Fax: +66 (0) 32 708 888
                    <br>
                    Hotel Tax ID 0763559000169 | Email: reservation@together-resort.com
                </b>
            </div>
        </div>
    </header>
    <hr>
    <main>
        <div class="clearfix" style="color: #020202;">
            <table id="topic" cellpadding="2" style="margin-bottom:0px;">
                <tr>
                    <td width="10%" align="left"><b>Date : </b></td>
                    <td width="45%" align="left">
                        {{ date('d/m/Y', strtotime($data_query[($start_round - 1)]['date'])) }} - {{ date('d/m/Y', strtotime(isset($data_query[($round_number - 1)]['date']) ? $data_query[($round_number - 1)]['date'] : $data_query[count($data_query) - 1]['date'])) }}
                    </td>
                    <td width="30%"></td>
                    <td><i>Page {{ $page_next += 1 }}/{{ $page_all }}</i></td>
                </tr>
            </table>
        </div>
        <table id="detail" cellpadding="5" style="line-height: 12px;">
            <thead>
                <tr>
                    <th>Description</th>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <th>{{ date('d/m/y', strtotime($item->date)) }}</th>
                            @endif
                        @endforeach
                        {{-- <th>Total</th> --}}
                    @else
                        <th>Today</th>
                        <th>M-T-D</th>
                        <th>Y-T-D</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="100%" style="text-align: left; background-color: rgb(187, 226, 226);"><b>Agoda Revenue</b></td>
                </tr>
                <tr>
                    <td>Agoda Charge</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->agoda_charge, 2) }}</td>
                                @php
                                    $sum_agoda_charge += $item->agoda_charge
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_agoda_charge, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td>Agoda Fee </td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->agoda_fee, 2) }}</td>
                                @php
                                    $sum_agoda_fee += $item->agoda_fee
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_agoda_fee, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td>Agoda Revenue</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->agoda_revenue, 2) }}</td>
                                @php
                                    $sum_agoda_revenue += $item->agoda_revenue
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_agoda_revenue, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td>Agoda Paid (bank transfer)</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->total_credit_agoda, 2) }}</td>
                                @php
                                    $sum_agoda_paid += $item->total_credit_agoda
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_agoda_paid, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td>Agoda Revenue Outstanding </td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->agoda_revenue, 2) }}</td>
                                @php
                                    $sum_agoda_outstanding += $item->agoda_revenue
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_agoda_outstanding, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td colspan="100%" style="text-align: left; background-color: rgb(187, 226, 226);"><b>Other Revenue</b></td>
                </tr>
                <tr>
                    <td>Bank Transfer</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->other_revenue, 2) }}</td>
                                @php
                                    $sum_other_revenue += $item->other_revenue
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_other_revenue, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>

                <tr>
                    <td colspan="100%" class="bdl bdr"></td>
                </tr>

                <tr style="text-align: left; background-color: rgb(187, 226, 226);">
                    <td><b>Summary Hotel Revenue</b></td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->cash + $item->bank_transfer + $item->agoda_outstanding, 2) }}</td>
                                @php
                                    $sum_all_hotel_agoda += ($item->cash + $item->bank_transfer + $item->agoda_outstanding)
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_all_hotel_agoda, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td>Cash</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->cash, 2) }}</td>
                                @php
                                    $sum_all_cash += $item->cash
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_all_cash, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td>Bank Transfer</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->bank_transfer, 2) }}</td>
                                @php
                                    $sum_all_transfer += $item->bank_transfer
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_all_transfer, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td>Agoda Revenue Outstanding Balance</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->agoda_outstanding, 2) }}</td>
                                @php
                                    $sum_all_agoda_outstanding += $item->agoda_outstanding
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_all_agoda_outstanding, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>

                <tr>
                    <td colspan="100%" class="bdl bdr"></td>
                </tr>

                <tr>
                    <td colspan="100%" style="text-align: left; background-color: rgb(187, 226, 226);"><b>Water Park Revenue</b></td>
                </tr>
                <tr>
                    <td>Cash</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->wp_cash, 2) }}</td>
                                @php
                                    $sum_water_cash += $item->wp_cash
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_water_cash, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td>Bank Transfer</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->wp_transfer, 2) }}</td>
                                @php
                                    $sum_water_transfer += $item->wp_transfer
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_water_transfer, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
            </tbody>
        </table>
    </main>
    <footer>
        <hr>
        <h4>Together Resort Limited Partnership</h4>
    </footer>
</div>

<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<div class="wrapper-page">
    <header class="clearfix" style="color: #020202;">
        <div class="logo">
            <img src="logo/logo_crop.png">
        </div>
        <div class="logo">
            <div class="add-text" style="line-height:14px;">
                <b style="font-size:30px;">Together Resort Limited Partnership</b>
                <br>
                <b>
                    168 Moo 2 Kaengkrachan Phetchaburi 76170
                    <br>
                    Tel: +66 (0) 32 708 888 | Fax: +66 (0) 32 708 888
                    <br>
                    Hotel Tax ID 0763559000169 | Email: reservation@together-resort.com
                </b>
            </div>
        </div>
    </header>
    <hr>
    <main>
        <div class="clearfix" style="color: #020202;">
            <table id="topic" cellpadding="2" style="margin-bottom:0px;">
                <tr>
                    <td width="10%" align="left"><b>Date : </b></td>
                    <td width="45%" align="left">
                        {{ date('d/m/Y', strtotime($data_query[($start_round - 1)]['date'])) }} - {{ date('d/m/Y', strtotime(isset($data_query[($round_number - 1)]['date']) ? $data_query[($round_number - 1)]['date'] : $data_query[count($data_query) - 1]['date'])) }}
                    </td>
                    <td width="30%"></td>
                    <td><i>Page {{ $page_next += 1 }}/{{ $page_all }}</i></td>
                </tr>
            </table>
        </div>
        <table id="detail" cellpadding="5" style="line-height: 12px;">
            <thead>
                <tr>
                    <th>Description</th>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <th>{{ date('d/m/y', strtotime($item->date)) }}</th>
                            @endif
                        @endforeach
                        {{-- <th>Total</th> --}}
                    @else
                        <th>Today</th>
                        <th>M-T-D</th>
                        <th>Y-T-D</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="100%" style="text-align: left; background-color: rgb(187, 226, 226);"><b>Water Park Credit Card Revenue</b></td>
                </tr>
                <tr>
                    <td> Credit Card Water Park Charge </td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->wp_charge, 2) }}</td>
                                @php
                                    $sum_water_credit_charge += $item->wp_charge
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_water_credit_charge, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td>Credit Card Fee</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->wp_fee, 2) }}</td>
                                @php
                                    $sum_water_credit_fee += $item->wp_fee
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_water_credit_fee, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td>Credit Card Water Park Revenue (Bank Transfer)</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->wp_credit, 2) }}</td>
                                @php
                                    $sum_water_credit_revenue += $item->wp_credit
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_water_credit_revenue, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr style="text-align: left; background-color: rgb(187, 226, 226);">
                    <td>Summary Water Park Revenue</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->wp_cash + $item->wp_transfer, 2) }}</td>
                                @php
                                    $sum_all_water += $item->wp_transfer
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_all_water, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                
                <tr>
                    <td colspan="100%" class="bdl bdr"></td>
                </tr>

                <tr>
                    <td colspan="100%" style="text-align: left; background-color: rgb(187, 226, 226);">Elexa EGAT Revenue</td>
                </tr>
                <tr>
                    <td>EV Charging Charge</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->ev_charge, 2) }}</td>
                                @php
                                    $sum_ev_charge += $item->ev_charge
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_ev_charge, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td>Elexa Fee</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->ev_fee, 2) }}</td>
                                @php
                                    $sum_ev_fee += $item->ev_fee
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_ev_fee, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td> Elexa EGAT revenue</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->ev_revenue, 2) }}</td>
                                @php
                                    $sum_ev_revenue += $item->ev_revenue
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_ev_revenue, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td> Elexa EGAT Paid (Bank Transfer)</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->total_elexa, 2) }}</td>
                                @php
                                    $sum_ev_paid += $item->total_elexa
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_ev_paid, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td> Elexa EGAT Outstanding Balance</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->ev_revenue, 2) }}</td>
                                @php
                                    $sum_ev_outstanding += $item->ev_revenue
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_ev_outstanding, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr style="text-align: left; background-color: rgb(187, 226, 226);">
                    <td>Summary Elexa EGAT Revenue</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->ev_revenue, 2) }}</td>
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_ev_outstanding, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td class="text-end f-semi"> Bank Transfer</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->total_elexa, 2) }}</td>
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_ev_paid, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td class="text-end f-semi"> Elexa EGAT Outstanding Balance</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->ev_revenue, 2) }}</td>
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_ev_outstanding, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
            </tbody>
        </table>
    </main>
    <footer>
        <hr>
        <h4>Together Resort Limited Partnership</h4>
    </footer>
</div>

<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<div class="wrapper-page">
    <header class="clearfix" style="color: #020202;">
        <div class="logo">
            <img src="logo/logo_crop.png">
        </div>
        <div class="logo">
            <div class="add-text" style="line-height:14px;">
                <b style="font-size:30px;">Together Resort Limited Partnership</b>
                <br>
                <b>
                    168 Moo 2 Kaengkrachan Phetchaburi 76170
                    <br>
                    Tel: +66 (0) 32 708 888 | Fax: +66 (0) 32 708 888
                    <br>
                    Hotel Tax ID 0763559000169 | Email: reservation@together-resort.com
                </b>
            </div>
        </div>
    </header>
    <hr>
    <main>
        <div class="clearfix" style="color: #020202;">
            <table id="topic" cellpadding="2" style="margin-bottom:0px;">
                <tr>
                    <td width="10%" align="left"><b>Date : </b></td>
                    <td width="45%" align="left">
                        {{ date('d/m/Y', strtotime($data_query[($start_round - 1)]['date'])) }} - {{ date('d/m/Y', strtotime(isset($data_query[($round_number - 1)]['date']) ? $data_query[($round_number - 1)]['date'] : $data_query[count($data_query) - 1]['date'])) }}
                    </td>
                    <td width="30%"></td>
                    <td><i>Page {{ $page_next += 1 }}/{{ $page_all }}</i></td>
                </tr>
            </table>
        </div>
        <table id="detail" cellpadding="5" style="line-height: 12px;">
            <thead>
                <tr>
                    <th>Description</th>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <th>{{ date('d/m/y', strtotime($item->date)) }}</th>
                            @endif
                        @endforeach
                        {{-- <th>Total</th> --}}
                    @else
                        <th>Today</th>
                        <th>M-T-D</th>
                        <th>Y-T-D</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <tr style="text-align: left; background-color: rgb(187, 226, 226);">
                    <td colspan="100%">Summary Revenue</td>
                </tr>
                <tr>
                    <td class="text-end f-semi"> All Revenue </td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format(($item->cash + $item->bank_transfer + $item->agoda_outstanding) + ($item->wp_cash + $item->wp_transfer) + $item->ev_revenue, 2) }}</td>
                                @php
                                    $sum_all_revenue += ($item->cash + $item->bank_transfer + $item->agoda_outstanding) + ($item->wp_cash + $item->wp_transfer) + $item->ev_revenue;
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_all_revenue, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td class="text-end f-semi"> Outstanding Balance From Last Year</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>0.00</td>
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format(0, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td class="text-end f-semi">Total Revenue & Outstanding Balance From Last Year</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format(($item->cash + $item->bank_transfer + $item->agoda_outstanding) + ($item->wp_cash + $item->wp_transfer) + $item->ev_revenue, 2) }}</td>
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_all_revenue, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr style="text-align: left; background-color: rgb(187, 226, 226);">
                    <td colspan="100%"> Payment Summary Details Report</td>
                </tr>
                <tr>
                    <td class="text-end f-semi">Hotel Revenue </td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format(($item->cash + $item->bank_transfer), 2) }}</td>
                                @php
                                    $sum_hotel += ($item->cash + $item->bank_transfer);
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_hotel, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td class="text-end f-semi"> Water Park Revenue </td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format(($item->wp_cash + $item->wp_transfer), 2) }}</td>
                                @php
                                    $sum_water_park += ($item->wp_cash + $item->wp_transfer);
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_water_park, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td class="text-end f-semi">Elexa EGAT revenue </td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->ev_revenue, 2) }}</td>
                                @php
                                    $sum_exlexa += $item->ev_revenue;
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_exlexa, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td class="text-end f-semi">Total Revenue</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format(($item->cash + $item->bank_transfer) + ($item->wp_cash + $item->wp_transfer) + $item->ev_revenue, 2) }}</td>
                                @php
                                    $sum_total_revenue += ($item->cash + $item->bank_transfer) + ($item->wp_cash + $item->wp_transfer) + $item->ev_revenue;
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_total_revenue, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                
                <tr>
                    <td colspan="100%" class="bdl bdr"></td>
                </tr>

                <tr style="text-align: left; background-color: rgb(187, 226, 226);">
                    <td class="f-semi" colspan="100%">Revenue Outstanding Report</td>
                </tr>
                <tr>
                    <td class="text-end f-semi">Agoda Outstanding Balance </td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->agoda_revenue, 2) }}</td>
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_all_agoda_outstanding, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td class="text-end f-semi">Elexa EGAT Outstanding Balance</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->ev_revenue, 2) }}</td>
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_ev_outstanding, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
                <tr>
                    <td class="text-end f-semi">Total Outstanding Balance</td>
                    @if ($filter_by == "date" && $status == "detail")
                        @foreach ($data_query as $key => $item)
                            @if (($key + 1) >= $start_round && ($key + 1) <= $round_number)
                                <td>{{ number_format($item->agoda_revenue + $item->ev_revenue, 2) }}</td>
                                @php
                                    $sum_total_outstanding += ($item->agoda_revenue + $item->ev_revenue);
                                @endphp
                            @endif
                        @endforeach
                        {{-- <td>{{ number_format($sum_total_outstanding, 2) }}</td> --}}
                    @else
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                        <td class="td-default"></td>
                    @endif
                </tr>
            </tbody>
        </table>
    </main>
    <footer>
        <hr>
        <h4>Together Resort Limited Partnership</h4>
    </footer>
</div>

    @php
        $round_number += 10;
        $start_round += 10;
    @endphp
@endfor

<!-- ////////////////////////////////////////////// Total //////////////////////////////////////////////////////// -->

<div class="wrapper-page">
    <header class="clearfix" style="color: #020202;">
        <div class="logo">
            <img src="logo/logo_crop.png">
        </div>
        <div class="logo">
            <div class="add-text" style="line-height:14px;">
                <b style="font-size:30px;">Together Resort Limited Partnership</b>
                <br>
                <b>
                    168 Moo 2 Kaengkrachan Phetchaburi 76170
                    <br>
                    Tel: +66 (0) 32 708 888 | Fax: +66 (0) 32 708 888
                    <br>
                    Hotel Tax ID 0763559000169 | Email: reservation@together-resort.com
                </b>
            </div>
        </div>
    </header>
    <hr>
    <main>
        <div class="clearfix" style="color: #020202;">
            <table id="topic" cellpadding="2" style="margin-bottom:0px;">
                <tr>
                    <td width="10%" align="left"><b>Date : </b></td>
                    <td width="45%" align="left">{{ $search_date }}</td>
                    <td width="30%"></td>
                    <td><i>Page {{ $page_next += 1 }}/{{ $page_all }}</i></td>
                </tr>
            </table>
        </div>
        <table id="detail" cellpadding="5" style="line-height: 12px;">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" style="text-align: left; background-color: rgb(187, 226, 226);"><b>Front Desk Revenue</b></td>
                </tr>
                <tr>
                    <td>Cash</td>
                    <td>{{ number_format($sum_front_cash, 2) }}</td>
                </tr>
                <tr>
                    <td>Bank Transfer</td>
                    <td>{{ number_format($sum_front_transfer, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: left; background-color: rgb(187, 226, 226);"><b>Guest Deposit Revenue</b></td>
                </tr>
                <tr>
                    <td>Cash</td>
                    <td>{{ number_format($sum_guest_cash, 2) }}</td>
                </tr>
                <tr>
                    <td>Bank Transfer</td>
                    <td>{{ number_format($sum_guest_transfer, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: left; background-color: rgb(187, 226, 226);"><b>All Outlet Revenue</b></td>
                </tr>
                <tr>
                    <td>Cash</td>
                    <td>{{ number_format($sum_all_outlet_cash, 2) }}</td>
                </tr>
                <tr>
                    <td>Bank Transfer</td>
                    <td>{{ number_format($sum_all_outlet_transfer, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: left; background-color: rgb(187, 226, 226);"><b>Hotel Credit Card Revenue</b></td>
                </tr>
                <tr>
                    <td>credit card front desk charge</td>
                    <td>{{ number_format($sum_credit_front, 2) }}</td>
                </tr>
                <tr>
                    <td>credit card guest deposit charge</td>
                    <td>{{ number_format($sum_credit_guest, 2) }}</td>
                </tr>
                <tr>
                    <td>credit card all outlet charge</td>
                    <td>{{ number_format($sum_credit_all_outlet, 2) }}</td>
                </tr>
                <tr>
                    <td>credit card fee</td>
                    <td>{{ number_format($sum_credit_fee, 2) }}</td>
                </tr>
                <tr>
                    <td>credit card revenue (bank transfer)</td>
                    <td>{{ number_format($sum_credit_revenue, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </main>
    <footer>
        <hr>
        <h4>Together Resort Limited Partnership</h4>
    </footer>
</div>

<!-- ////////////////////////////////////////////////////////////////////////// -->

<div class="wrapper-page">
    <header class="clearfix" style="color: #020202;">
        <div class="logo">
            <img src="logo/logo_crop.png">
        </div>
        <div class="logo">
            <div class="add-text" style="line-height:14px;">
                <b style="font-size:30px;">Together Resort Limited Partnership</b>
                <br>
                <b>
                    168 Moo 2 Kaengkrachan Phetchaburi 76170
                    <br>
                    Tel: +66 (0) 32 708 888 | Fax: +66 (0) 32 708 888
                    <br>
                    Hotel Tax ID 0763559000169 | Email: reservation@together-resort.com
                </b>
            </div>
        </div>
    </header>
    <hr>
    <main>
        <div class="clearfix" style="color: #020202;">
            <table id="topic" cellpadding="2" style="margin-bottom:0px;">
                <tr>
                    <td width="10%" align="left"><b>Date : </b></td>
                    <td width="45%" align="left">{{ $search_date }}</td>
                    <td width="30%"></td>
                    <td><i>Page {{ $page_next += 1 }}/{{ $page_all }}</i></td>
                </tr>
            </table>
        </div>
        <table id="detail" cellpadding="5" style="line-height: 12px;">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" style="text-align: left; background-color: rgb(187, 226, 226);"><b>Agoda Revenue</b></td>
                </tr>
                <tr>
                    <td>Agoda Charge</td>
                    <td>{{ number_format($sum_agoda_charge, 2) }}</td>
                </tr>
                <tr>
                    <td>Agoda Fee </td>
                    <td>{{ number_format($sum_agoda_fee, 2) }}</td>
                </tr>
                <tr>
                    <td>Agoda Revenue</td>
                    <td>{{ number_format($sum_agoda_revenue, 2) }}</td>
                </tr>
                <tr>
                    <td>Agoda Paid (bank transfer)</td>
                    <td>{{ number_format($sum_agoda_paid, 2) }}</td>
                </tr>
                <tr>
                    <td>Agoda Revenue Outstanding </td>
                    <td>{{ number_format($sum_agoda_outstanding, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: left; background-color: rgb(187, 226, 226);"><b>Other Revenue</b></td>
                </tr>
                <tr>
                    <td>Bank Transfer</td>
                    <td>{{ number_format($sum_other_revenue, 2) }}</td>
                </tr>

                <tr>
                    <td colspan="2" class="bdl bdr"></td>
                </tr>

                <tr style="text-align: left; background-color: rgb(187, 226, 226);">
                    <td><b>Summary Hotel Revenue</b></td>
                    <td>{{ number_format($sum_all_hotel_agoda, 2) }}</td>
                </tr>
                <tr>
                    <td>Cash</td>
                    <td>{{ number_format($sum_all_cash, 2) }}</td>
                </tr>
                <tr>
                    <td>Bank Transfer</td>
                    <td>{{ number_format($sum_all_transfer, 2) }}</td>
                </tr>
                <tr>
                    <td>Agoda Revenue Outstanding Balance</td>
                    <td>{{ number_format($sum_all_agoda_outstanding, 2) }}</td>
                </tr>

                <tr>
                    <td colspan="2" class="bdl bdr"></td>
                </tr>

                <tr>
                    <td colspan="2" style="text-align: left; background-color: rgb(187, 226, 226);"><b>Water Park Revenue</b></td>
                </tr>
                <tr>
                    <td>Cash</td>
                    <td>{{ number_format($sum_water_cash, 2) }}</td>
                </tr>
                <tr>
                    <td>Bank Transfer</td>
                    <td>{{ number_format($sum_water_transfer, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </main>
    <footer>
        <hr>
        <h4>Together Resort Limited Partnership</h4>
    </footer>
</div>

<!-- ////////////////////////////////////////////////////////////////////////// -->

<div class="wrapper-page">
    <header class="clearfix" style="color: #020202;">
        <div class="logo">
            <img src="logo/logo_crop.png">
        </div>
        <div class="logo">
            <div class="add-text" style="line-height:14px;">
                <b style="font-size:30px;">Together Resort Limited Partnership</b>
                <br>
                <b>
                    168 Moo 2 Kaengkrachan Phetchaburi 76170
                    <br>
                    Tel: +66 (0) 32 708 888 | Fax: +66 (0) 32 708 888
                    <br>
                    Hotel Tax ID 0763559000169 | Email: reservation@together-resort.com
                </b>
            </div>
        </div>
    </header>
    <hr>
    <main>
        <div class="clearfix" style="color: #020202;">
            <table id="topic" cellpadding="2" style="margin-bottom:0px;">
                <tr>
                    <td width="10%" align="left"><b>Date : </b></td>
                    <td width="45%" align="left">{{ $search_date }}</td>
                    <td width="30%"></td>
                    <td><i>Page {{ $page_next += 1 }}/{{ $page_all }}</i></td>
                </tr>
            </table>
        </div>
        <table id="detail" cellpadding="5" style="line-height: 12px;">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" style="text-align: left; background-color: rgb(187, 226, 226);"><b>Water Park Credit Card Revenue</b></td>
                </tr>
                <tr>
                    <td> Credit Card Water Park Charge </td>
                    <td>{{ number_format($sum_water_credit_charge, 2) }}</td>
                </tr>
                <tr>
                    <td>Credit Card Fee</td>
                    <td>{{ number_format($sum_water_credit_fee, 2) }}</td>
                </tr>
                <tr>
                    <td>Credit Card Water Park Revenue (Bank Transfer)</td>
                    <td>{{ number_format($sum_water_credit_revenue, 2) }}</td>
                </tr>
                <tr style="text-align: left; background-color: rgb(187, 226, 226);">
                    <td><b>Summary Water Park Revenue</b></td>
                    <td>{{ number_format($sum_all_water, 2) }}</td>
                </tr>
                
                <tr>
                    <td colspan="2" class="bdl bdr"></td>
                </tr>

                <tr>
                    <td colspan="2" style="text-align: left; background-color: rgb(187, 226, 226);"><b>Elexa EGAT Revenue</b></td>
                </tr>
                <tr>
                    <td>EV Charging Charge</td>
                    <td>{{ number_format($sum_ev_charge, 2) }}</td>
                </tr>
                <tr>
                    <td>Elexa Fee</td>
                    <td>{{ number_format($sum_ev_fee, 2) }}</td>
                </tr>
                <tr>
                    <td> Elexa EGAT revenue</td>
                    <td>{{ number_format($sum_ev_revenue, 2) }}</td>
                </tr>
                <tr>
                    <td> Elexa EGAT Paid (Bank Transfer)</td>
                    <td>{{ number_format($sum_ev_paid, 2) }}</td>
                </tr>
                <tr>
                    <td> Elexa EGAT Outstanding Balance</td>
                    <td>{{ number_format($sum_ev_outstanding, 2) }}</td>
                </tr>
                <tr style="text-align: left; background-color: rgb(187, 226, 226);">
                    <td><b>Summary Elexa EGAT Revenue</b></td>
                    <td>{{ number_format($sum_ev_outstanding, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-end f-semi"> Bank Transfer</td>
                    <td>{{ number_format($sum_ev_paid, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-end f-semi"> Elexa EGAT Outstanding Balance</td>
                    <td>{{ number_format($sum_ev_outstanding, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </main>
    <footer>
        <hr>
        <h4>Together Resort Limited Partnership</h4>
    </footer>
</div>

<!-- ////////////////////////////////////////////////////////////////////////// -->

<div class="wrapper-page">
    <header class="clearfix" style="color: #020202;">
        <div class="logo">
            <img src="logo/logo_crop.png">
        </div>
        <div class="logo">
            <div class="add-text" style="line-height:14px;">
                <b style="font-size:30px;">Together Resort Limited Partnership</b>
                <br>
                <b>
                    168 Moo 2 Kaengkrachan Phetchaburi 76170
                    <br>
                    Tel: +66 (0) 32 708 888 | Fax: +66 (0) 32 708 888
                    <br>
                    Hotel Tax ID 0763559000169 | Email: reservation@together-resort.com
                </b>
            </div>
        </div>
    </header>
    <hr>
    <main>
        <div class="clearfix" style="color: #020202;">
            <table id="topic" cellpadding="2" style="margin-bottom:0px;">
                <tr>
                    <td width="10%" align="left"><b>Date : </b></td>
                    <td width="45%" align="left">{{ $search_date }}</td>
                    <td width="30%"></td>
                    <td><i>Page {{ $page_next += 1 }}/{{ $page_all }}</i></td>
                </tr>
            </table>
        </div>
        <table id="detail" cellpadding="5" style="line-height: 12px;">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr style="text-align: left; background-color: rgb(187, 226, 226);">
                    <td colspan="2"><b>Summary Revenue</b></td>
                </tr>
                <tr>
                    <td class="text-end f-semi"> All Revenue </td>
                    <td>{{ number_format($sum_all_revenue, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-end f-semi"> Outstanding Balance From Last Year</td>
                    <td>{{ number_format(0, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-end f-semi">Total Revenue & Outstanding Balance From Last Year</td>
                    <td>{{ number_format($sum_all_revenue, 2) }}</td>
                </tr>
                <tr style="text-align: left; background-color: rgb(187, 226, 226);">
                    <td colspan="2"><b>Payment Summary Details Report</b></td>
                </tr>
                <tr>
                    <td class="text-end f-semi">Hotel Revenue </td>
                    <td>{{ number_format($sum_hotel, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-end f-semi"> Water Park Revenue </td>
                    <td>{{ number_format($sum_water_park, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-end f-semi">Elexa EGAT revenue </td>
                    <td>{{ number_format($sum_exlexa, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-end f-semi">Total Revenue</td>
                    <td>{{ number_format($sum_total_revenue, 2) }}</td>
                </tr>
                
                <tr>
                    <td colspan="2" class="bdl bdr"></td>
                </tr>

                <tr style="text-align: left; background-color: rgb(187, 226, 226);">
                    <td class="f-semi" colspan="2"><b>Revenue Outstanding Report</b></td>
                </tr>
                <tr>
                    <td class="text-end f-semi">Agoda Outstanding Balance </td>
                    <td>{{ number_format($sum_all_agoda_outstanding, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-end f-semi">Elexa EGAT Outstanding Balance</td>
                    <td>{{ number_format($sum_ev_outstanding, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-end f-semi">Total Outstanding Balance</td>
                    <td>{{ number_format($sum_total_outstanding, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </main>
    <footer>
        <hr>
        <h4>Together Resort Limited Partnership</h4>
    </footer>
</div>
</body>
</html>
