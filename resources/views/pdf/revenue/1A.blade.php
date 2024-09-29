<!DOCTYPE html>


<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Template 1A</title>
    <style>
        @page {
            margin: 0.2cm 1.0cm 0.5cm 1.0cm;
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

        #invoice {
            float: right;
            text-align: left;
            padding-right: 6px;
        }

        #invoice h1 {
            color: #07883d;
            font-size: 2.4em;
            line-height: 1em;
            font-weight: normal;
            margin: 0 0 10px 0;
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
            background-color: #109699;
            color: #FFFFFF;
            /* border: 0.5px solid #109699; */
        }

        /* table#detail tbody td {
            background-color: #FFFFFF;
            border: none;
        } */

        table#detail tbody th {
            background-color: #109699;
            color: #FFFFFF;
            /* border: none; */
        }

        table#detail {
            line-height: 10px;
            border-collapse: separate;
            border-spacing: 0;
        }

        table#detail tr th,
        table#detail tr td {
            /* background-color: white; */
            /* border-right: 1px solid #109699; */
            /* border-bottom: none;
            border-top: none; */
            padding: 5px;
            border: 0.5px solid #109699;
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
    {{-- <div class="wrapper-page"> --}}
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
            <?php
                if (isset($search_date)) {
                    $date_current = $search_date;
                } else {
                    $date_current = date('Y-m-d');
                }

                $this_week = date('d M', strtotime('last sunday', strtotime('next sunday', strtotime(date('Y-m-d'))))); // อาทิตย์ - เสาร์
                
                $day_sum = isset($search_date) ? date('j', strtotime($search_date)) : date('j');

                if (isset($filter_by) && $filter_by == 'date' || isset($filter_by) && $filter_by == 'today' || isset($filter_by) && $filter_by == 'yesterday' || isset($filter_by) && $filter_by == 'tomorrow') {
                    $pickup_time = date('d F Y', strtotime($search_date));
                } elseif (isset($filter_by) && $filter_by == 'month') {
                    $pickup_time = $search_date;
                } elseif (isset($filter_by) && $filter_by == 'year') {
                    $pickup_time = $search_date;
                } elseif (isset($filter_by) && $filter_by == 'week') {
                    $pickup_time = date('d M', strtotime('last sunday', strtotime('next sunday', strtotime(date('Y-m-d')))))." ~ ".date('d M', strtotime("+6 day", strtotime($this_week)));
                } elseif (isset($filter_by) && $filter_by == 'thisMonth') {
                    $pickup_time = "01 " . date('M') . " ~ " . date('t M');
                } elseif (isset($filter_by) && $filter_by == 'thisYear') {
                    $pickup_time = "01 " . "Jan" . " ~ ". date('d M', strtotime(date('Y-m-01')));
                } elseif (isset($filter_by) && $filter_by == 'customRang') {
                    $pickup_time = date('d M', strtotime($customRang_start)) . " " . substr(date('Y', strtotime($customRang_start)), -2) . " ~ ". date('d M', strtotime($customRang_end)) . " " . substr(date('Y', strtotime($customRang_end)), -2);
                }

            ?>
            <div class="clearfix" style="color: #020202;">
                <table id="topic" cellpadding="2" style="margin-bottom:0px;">
                    <tr>
                        <td width="10%" align="left"><b>Date : </b></td>
                        <td width="45%" align="left">{{ $pickup_time }}</td>
                    </tr>
                </table>
            </div>
            <?php
                $total_cash_month = $total_front_month->front_cash + $total_guest_deposit_month->room_cash + $total_fb_month->fb_cash;
                $total_bank_transfer_month = $total_front_month->front_transfer + $total_guest_deposit_month->room_transfer + $total_fb_month->fb_transfer;
                
                $total_cash_bank_month = $total_cash_month + $total_bank_transfer_month;
                
                $total_charge_month = $credit_revenue_month->total_credit ?? 0;
                
                $total_wp_cash_bank_month = $total_wp_month->wp_cash + $total_wp_month->wp_transfer;
                
                $total_wp_charge_month = $wp_charge[0]['total_month'];
                
                $monthly_revenue = $total_cash_bank_month + $total_charge_month + ($total_wp_cash_bank_month + $total_wp_charge_month) - $agoda_charge[0]['total'];
                
                $sum_charge = $front_charge[0]['revenue_credit_date'] + $guest_deposit_charge[0]['revenue_credit_date'] + $fb_charge[0]['revenue_credit_date'];
            ?>

            <?php
                $total_cash = $total_front_revenue->front_cash + $total_guest_deposit->room_cash + $total_fb_revenue->fb_cash;
                $total_cash_month = $total_front_month->front_cash + $total_guest_deposit_month->room_cash + $total_fb_month->fb_cash;
                $total_cash_year = $total_front_year->front_cash + $total_guest_deposit_year->room_cash + $total_fb_year->fb_cash;
                
                $total_bank_transfer = $total_front_revenue->front_transfer + $total_guest_deposit->room_transfer + $total_fb_revenue->fb_transfer + $today_other_revenue;
                $total_bank_transfer_month = $total_front_month->front_transfer + $total_guest_deposit_month->room_transfer + $total_fb_month->fb_transfer + $total_other_month;
                $total_bank_transfer_year = $total_front_year->front_transfer + $total_guest_deposit_year->room_transfer + $total_fb_year->fb_transfer + $total_other_year;
                
                $total_wp_cash_bank = $total_wp_revenue->wp_cash + $total_wp_revenue->wp_transfer;
                $total_wp_cash_bank_month = $total_wp_month->wp_cash + $total_wp_month->wp_transfer;
                $total_wp_cash_bank_year = $total_wp_year->wp_cash + $total_wp_year->wp_transfer;
                
                $total_cash_bank = $total_cash + $total_bank_transfer;
                $total_cash_bank_month = $total_cash_month + $total_bank_transfer_month;
                $total_cash_bank_year = $total_cash_year + $total_bank_transfer_year;
                
                $total_today_revenue_graph = $total_day + ($credit_revenue->total_credit ?? 0);
            ?>
            <table id="detail" cellpadding="5" style="line-height: 12px;">
                <thead>
                    <tr>
                        <th style="text-align: center;" colspan="2"><b>Description</b></th>
                        <th style="text-align: left;"><b>Today</b></th>
                        <th style="text-align: left;"><b>M-T-D</b></th>
                        <th style="text-align: left;"><b>Y-T-D</b></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2" class="bdr" style="text-align: center;"><b>Hotel</b></td>
                        <td colspan="3"></td>
                    </tr>
                    <tr>
                        <th colspan="5" style="text-align: left;"><b>Front Desk Revenue</b></th>
                    </tr>
                    <tr>
                        <td colspan="2"><b>Cash</b></td>
                        <td>{{ number_format(isset($total_front_revenue) ? $total_front_revenue->front_cash : 0, 2) }}
                        </td>
                        <td>{{ number_format(isset($total_front_month) ? $total_front_month->front_cash : 0, 2) }}</td>
                        <td>{{ number_format(isset($total_front_year) ? $total_front_year->front_cash : 0, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>Bank Transfer</b></td>
                        <td>{{ number_format(isset($total_front_revenue) ? $total_front_revenue->front_transfer : 0, 2) }}
                        </td>
                        <td>{{ number_format(isset($total_front_month) ? $total_front_month->front_transfer : 0, 2) }}
                        </td>
                        <td>{{ number_format(isset($total_front_year) ? $total_front_year->front_transfer : 0, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>Credit Card Front Desk Charge</b></td>
                        <td>{{ number_format($front_charge[0]['revenue_credit_date'], 2) }}</td>
                        <td>{{ number_format($front_charge[0]['revenue_credit_month'], 2) }}</td>
                        <td>{{ number_format($front_charge[0]['revenue_credit_year'], 2) }}</td>
                    </tr>
                    <tr>
                        <th colspan="5" style="text-align: left;"><b>Guest Deposit Revenue</b></th>
                    </tr>
                    <tr>
                        <td colspan="2"><b>Cash</b></td>
                        <td>{{ number_format(isset($total_guest_deposit) ? $total_guest_deposit->room_cash : 0, 2) }}
                        </td>
                        <td>{{ number_format(isset($total_guest_deposit_month) ? $total_guest_deposit_month->room_cash : 0, 2) }}
                        </td>
                        <td>{{ number_format(isset($total_guest_deposit_year) ? $total_guest_deposit_year->room_cash : 0, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>Bank Transfer</b></td>
                        <td>{{ number_format(isset($total_guest_deposit) ? $total_guest_deposit->room_transfer : 0, 2) }}
                        </td>
                        <td>{{ number_format(isset($total_guest_deposit_month) ? $total_guest_deposit_month->room_transfer : 0, 2) }}
                        </td>
                        <td>{{ number_format(isset($total_guest_deposit_year) ? $total_guest_deposit_year->room_transfer : 0, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>Credit Card Front Desk Charge</b></td>
                        <td>{{ number_format($guest_deposit_charge[0]['revenue_credit_date'], 2) }}</td>
                        <td>{{ number_format($guest_deposit_charge[0]['revenue_credit_month'], 2) }}</td>
                        <td>{{ number_format($guest_deposit_charge[0]['revenue_credit_year'], 2) }}</td>
                    </tr>
                    <tr>
                        <th colspan="5" style="text-align: left;"><b>All Outlet Revenue</b></th>
                    </tr>
                    <tr>
                        <td colspan="2"><b>Cash</b></td>
                        <td>{{ number_format($total_fb_revenue->fb_cash, 2) }}</td>
                        <td>{{ number_format($total_fb_month->fb_cash, 2) }}</td>
                        <td>{{ number_format($total_fb_year->fb_cash, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>Bank Transfer</b></td>
                        <td>{{ number_format($total_fb_revenue->fb_transfer, 2) }}</td>
                        <td>{{ number_format($total_fb_month->fb_transfer, 2) }}</td>
                        <td>{{ number_format($total_fb_year->fb_transfer, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>Credit card All Outlet Charge</b></td>
                        <td>{{ number_format($fb_charge[0]['revenue_credit_date'], 2) }}</td>
                        <td>{{ number_format($fb_charge[0]['revenue_credit_month'], 2) }}</td>
                        <td>{{ number_format($fb_charge[0]['revenue_credit_year'], 2) }}</td>
                    </tr>
                    <tr>
                        <th colspan="5" style="text-align: left;"><b>Other Revenue</b></th>
                    </tr>
                    <tr>
                        <td colspan="2"><b>Bank Transfer</b></td>
                        <td>{{ number_format($today_other_revenue, 2) }}</td>
                        <td>{{ number_format($total_other_month, 2) }}</td>
                        <td>{{ number_format($total_other_year, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right;"><b>Total Cash</b></td>
                        <td>{{ number_format($total_cash, 2) }}</td>
                        <td>{{ number_format($total_cash_month, 2) }}</td>
                        <td>{{ number_format($total_cash_year, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right;"><b>Total Bank Transfer</b></td>
                        <td>{{ number_format($total_bank_transfer, 2) }}</td>
                        <td>{{ number_format($total_bank_transfer_month, 2) }}</td>
                        <td>{{ number_format($total_bank_transfer_year, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right;"><b>Cash And Bank Transfer Hotel Revenue</b></td>
                        <td>{{ number_format($total_cash_bank, 2) }}</td>
                        <td>{{ number_format($total_cash_bank_month, 2) }}</td>
                        <td>{{ number_format($total_cash_bank_year, 2) }}</td>
                    </tr>
                    <tr>
                        <th colspan="5"></th>
                    </tr>
                    <tr>
                        <?php
                        $total_credit_card_revenue = $front_charge[0]['revenue_credit_date'] + $guest_deposit_charge[0]['revenue_credit_date'] + $fb_charge[0]['revenue_credit_date'];
                        $total_credit_card_revenue_month = $front_charge[0]['revenue_credit_month'] + $guest_deposit_charge[0]['revenue_credit_month'] + $fb_charge[0]['revenue_credit_month'];
                        $total_credit_card_revenue_year = $front_charge[0]['revenue_credit_year'] + $guest_deposit_charge[0]['revenue_credit_year'] + $fb_charge[0]['revenue_credit_year'];
                        ?>

                        <td colspan="2" style="text-align: right;"><b>Total Credit Card Charge</b></td>
                        <td>{{ number_format($total_credit_card_revenue, 2) }}</td>
                        <td>{{ number_format($total_credit_card_revenue_month, 2) }}</td>
                        <td>{{ number_format($total_credit_card_revenue_year, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right;"><b>Credit Card Fee</b></td>
                        <td>{{ number_format($total_credit_card_revenue == 0 || $credit_revenue->total_credit == 0 ? 0 : $total_credit_card_revenue - $credit_revenue->total_credit ?? 0, 2) }}
                        </td>
                        <td>{{ number_format($total_credit_card_revenue_month - $credit_revenue_month->total_credit ?? 0, 2) }}
                        </td>
                        <td>{{ number_format($total_credit_card_revenue_year - $credit_revenue_year->total_credit ?? 0, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <?php
                        $total_charge = $credit_revenue->total_credit ?? 0;
                        $total_charge_month = $credit_revenue_month->total_credit ?? 0;
                        $total_charge_year = $credit_revenue_year->total_credit ?? 0;
                        ?>

                        <td colspan="2" style="text-align: right;"><b>Credit Card Hotel Revenue</b></td>
                        <td>{{ number_format($credit_revenue->total_credit ?? 0, 2) }}</td>
                        <td>{{ number_format($credit_revenue_month->total_credit ?? 0, 2) }}</td>
                        <td>{{ number_format($credit_revenue_year->total_credit ?? 0, 2) }}</td>
                    </tr>
                    <tr> <!--Agoda-->
                        <th colspan="5" style="text-align: left;"> <b>Agoda Revenue</b></th>
                    </tr>
                    <tr>
                        <td colspan="2"><b>Credit Card Agoda Charge</b></td>
                        <td>{{ number_format($agoda_charge[0]['revenue_credit_date'], 2) }}</td>
                        <td>{{ number_format($agoda_charge[0]['revenue_credit_month'], 2) }}</td>
                        <td>{{ number_format($agoda_charge[0]['revenue_credit_year'], 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>Total Agoda Fee</b></td>
                        <td>{{ number_format($agoda_charge[0]['fee_date'], 2) }}</td>
                        <td>{{ number_format($agoda_charge[0]['fee_month'], 2) }}</td>
                        <td>{{ number_format($agoda_charge[0]['fee_year'], 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>Credit Agoda Revenue Outstanding</b></td>
                        <td>{{ number_format($agoda_charge[0]['total'], 2) }}</td>
                        <td>{{ number_format($agoda_charge[0]['total_month'], 2) }}</td>
                        <td>{{ number_format($agoda_charge[0]['total_year'], 2) }}</td>
                    </tr>
                    <tr>
                        <th colspan="5"></th>
                    </tr>
                    <tr class="tr-color-orange">
                        <td colspan="2" style="text-align: right;"><b>Total Hotel Revenue</b></td>
                        <td>{{ number_format($total_cash_bank + $total_charge + $agoda_charge[0]['total'], 2) }}</td>
                        <td>{{ number_format($total_cash_bank_month + $total_charge_month + $agoda_charge[0]['total_month'], 2) }}
                        </td>
                        <td>{{ number_format($total_cash_bank_year + $total_charge_year + $agoda_charge[0]['total_year'], 2) }}
                        </td>
                    </tr>
                    <tr> <!--Water Park-->
                        <th colspan="5" style="text-align: left;"><b>Water Park Revenue</b></th>
                    </tr>
                    <tr>
                        <td colspan="2"><b>Cash</b></td>
                        <td>{{ number_format($total_wp_revenue->wp_cash, 2) }}</td>
                        <td>{{ number_format($total_wp_month->wp_cash, 2) }}</td>
                        <td>{{ number_format($total_wp_year->wp_cash, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>Bank Transfer</b></td>
                        <td>{{ number_format($total_wp_revenue->wp_transfer, 2) }}</td>
                        <td>{{ number_format($total_wp_month->wp_transfer, 2) }}</td>
                        <td>{{ number_format($total_wp_year->wp_transfer, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right;"><b>Cash + Bank Transfer Water Park Revenue</b>
                        </td>
                        <td>{{ number_format($total_wp_cash_bank, 2) }}</td>
                        <td>{{ number_format($total_wp_cash_bank_month, 2) }}</td>
                        <td>{{ number_format($total_wp_cash_bank_year, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </main>
    {{-- </div> --}}



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
                        <td width="45%" align="left">{{ $pickup_time }}</td>
                    </tr>
                </table>
            </div>
            <?php
            $total_cash_month = $total_front_month->front_cash + $total_guest_deposit_month->room_cash + $total_fb_month->fb_cash;
            $total_bank_transfer_month = $total_front_month->front_transfer + $total_guest_deposit_month->room_transfer + $total_fb_month->fb_transfer;
            
            $total_cash_bank_month = $total_cash_month + $total_bank_transfer_month;
            
            $total_charge_month = $credit_revenue_month->total_credit ?? 0;
            
            $total_wp_cash_bank_month = $total_wp_month->wp_cash + $total_wp_month->wp_transfer;
            
            $total_wp_charge_month = $wp_charge[0]['total_month'];
            
            $monthly_revenue = $total_cash_bank_month + $total_charge_month + ($total_wp_cash_bank_month + $total_wp_charge_month) - $agoda_charge[0]['total'];
            
            $sum_charge = $front_charge[0]['revenue_credit_date'] + $guest_deposit_charge[0]['revenue_credit_date'] + $fb_charge[0]['revenue_credit_date'];
            ?>

            <?php
            $total_cash = $total_front_revenue->front_cash + $total_guest_deposit->room_cash + $total_fb_revenue->fb_cash;
            $total_cash_month = $total_front_month->front_cash + $total_guest_deposit_month->room_cash + $total_fb_month->fb_cash;
            $total_cash_year = $total_front_year->front_cash + $total_guest_deposit_year->room_cash + $total_fb_year->fb_cash;
            
            $total_bank_transfer = $total_front_revenue->front_transfer + $total_guest_deposit->room_transfer + $total_fb_revenue->fb_transfer;
            $total_bank_transfer_month = $total_front_month->front_transfer + $total_guest_deposit_month->room_transfer + $total_fb_month->fb_transfer;
            $total_bank_transfer_year = $total_front_year->front_transfer + $total_guest_deposit_year->room_transfer + $total_fb_year->fb_transfer;
            
            $total_wp_cash_bank = $total_wp_revenue->wp_cash + $total_wp_revenue->wp_transfer;
            $total_wp_cash_bank_month = $total_wp_month->wp_cash + $total_wp_month->wp_transfer;
            $total_wp_cash_bank_year = $total_wp_year->wp_cash + $total_wp_year->wp_transfer;
            
            $total_cash_bank = $total_cash + $total_bank_transfer;
            $total_cash_bank_month = $total_cash_month + $total_bank_transfer_month;
            $total_cash_bank_year = $total_cash_year + $total_bank_transfer_year;
            
            $total_today_revenue_graph = $total_day + ($credit_revenue->total_credit ?? 0);
            ?>
            <table id="detail" cellpadding="5" style="line-height: 12px;">
                <thead>
                    <tr>
                        <th style="text-align: center;" colspan="2"><b>Description</b></th>
                        <th style="text-align: left;"><b>Today</b></th>
                        <th style="text-align: left;"><b>M-T-D</b></th>
                        <th style="text-align: left;"><b>Y-T-D</b></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2" class="bdr" style="text-align: center;"><b>Hotel</b></td>
                        <td colspan="3"></td>
                    </tr>
                    <tr>
                        <?php
                        $total_wp_credit_card_revenue = $wp_charge[0]['revenue_credit_date'];
                        $total_wp_credit_card_revenue_month = $wp_charge[0]['revenue_credit_month'];
                        $total_wp_credit_card_revenue_year = $wp_charge[0]['revenue_credit_year'];
                        ?>

                        <td colspan="2" style="text-align: right;"><b>Credit Card Water Park Charge</b></td>
                        <td>{{ number_format($total_wp_credit_card_revenue, 2) }}</td>
                        <td>{{ number_format($total_wp_credit_card_revenue_month, 2) }}</td>
                        <td>{{ number_format($total_wp_credit_card_revenue_year, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right;"><b>Credit Card Fee</b></td>
                        <td>{{ number_format($wp_charge[0]['fee_date'], 2) }}</td>
                        <td>{{ number_format($wp_charge[0]['fee_month'], 2) }}</td>
                        <td>{{ number_format($wp_charge[0]['fee_year'], 2) }}</td>
                    </tr>
                    <tr>
                        <?php
                        $total_wp_charge = $wp_charge[0]['total'];
                        $total_wp_charge_month = $wp_charge[0]['total_month'];
                        $total_wp_charge_year = $wp_charge[0]['total_year'];
                        ?>
                        <td colspan="2" style="text-align: right;"><b>Credit Card Water Park Revenue</b></td>
                        <td>{{ number_format($total_wp_charge, 2) }}</td>
                        <td>{{ number_format($total_wp_charge_month, 2) }}</td>
                        <td>{{ number_format($total_wp_charge_year, 2) }}</td>
                    </tr>
                    <tr>
                        <th colspan="5"></th> 
                    </tr>
                    <tr class="tr-color-orange">
                        <td colspan="2" style="text-align: right;"><b>Total Water Park Revenue</b></td>
                        <td>{{ number_format($total_wp_cash_bank + $total_wp_charge, 2) }}</td>
                        <td>{{ number_format($total_wp_cash_bank_month + $total_wp_charge_month, 2) }}</td>
                        <td>{{ number_format($total_wp_cash_bank_year + $total_wp_charge_year, 2) }}</td>
                    </tr>
                    <tr> <!--Elexa EGAT Revenue-->
                        <th colspan="5" style="text-align: left;"><b>Elexa EGAT Revenue</b></th>
                    </tr>
                    <tr>
                        <td colspan="2"><b>EV Chargeing Charge</b></td>
                        <td>{{ number_format($ev_charge[0]['revenue_credit_date'], 2) }}</td>
                        <td>{{ number_format($ev_charge[0]['revenue_credit_month'], 2) }}</td>
                        <td>{{ number_format($ev_charge[0]['revenue_credit_year'], 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>Elexa Fee</b></td>
                        <td>{{ number_format($ev_charge[0]['fee_date'], 2) }}</td>
                        <td>{{ number_format($ev_charge[0]['fee_month'], 2) }}</td>
                        <td>{{ number_format($ev_charge[0]['fee_year'], 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>Elexa EGAT Revenue Outstanding</b></td>
                        <td>{{ number_format($ev_charge[0]['total'], 2) }}</td>
                        <td>{{ number_format($ev_charge[0]['total_month'], 2) }}</td>
                        <td>{{ number_format($ev_charge[0]['total_year'], 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right;"><b>Total Elexa EGAT Revenue</b></td>
                        <td>{{ number_format($ev_charge[0]['total'], 2) }}</td>
                        <td>{{ number_format($ev_charge[0]['total_month'], 2) }}</td>
                        <td>{{ number_format($ev_charge[0]['total_year'], 2) }}</td>
                    </tr>
                    <tr>
                        <th colspan="5"></th>
                    </tr>
                    <tr class="tr-color-blue">
                        <td colspan="2" style="text-align: right;"><b>Total Hotel, Water Park And Elexa EGAT Revenue</b></td>
                        <td>{{ number_format(($total_cash_bank + $total_charge) + ($total_wp_cash_bank + $total_wp_charge) + $agoda_charge[0]['total'] + $ev_charge[0]['total'] + $today_other_revenue, 2) }}</td>
                        <td>{{ number_format(($total_cash_bank_month + $total_charge_month) + ($total_wp_cash_bank_month + $total_wp_charge_month) + $agoda_charge[0]['total_month'] + $ev_charge[0]['total_month'] + $total_other_month, 2) }}</td>
                        <td>{{ number_format(($total_cash_bank_year + $total_charge_year) + ($total_wp_cash_bank_year + $total_wp_charge_year) + $agoda_charge[0]['total_year'] + $ev_charge[0]['total_year'] + $total_other_year, 2) }}</td>
                    </tr>
                    <tr class="tr-color-blue">
                        <td colspan="2" style="text-align: right;"><b>Credit Agoda Revenue Outstanding</b></td>
                        <td>{{ number_format($agoda_charge[0]['total'], 2) }}</td>
                        <td>{{ number_format($agoda_charge[0]['total_month'], 2) }}</td>
                        <td>{{ number_format($agoda_charge[0]['total_year'] - $total_agoda_year, 2) }}</td>
                    </tr>
                    <tr class="tr-color-blue">
                        <td colspan="2" style="text-align: right;"><b>Elexa EGAT Revenue Outstanding</b></td>
                        <td>{{ number_format($ev_charge[0]['total'], 2) }}</td>
                        <td>{{ number_format($ev_charge[0]['total_month'], 2) }}</td>
                        <td>{{ number_format($ev_charge[0]['total_year'], 2) }}</td>
                    </tr>
                    <tr class="tr-color-blue">
                        <td colspan="2" style="text-align: right;"><b>Agoda Revenue</b></td>
                        <td>{{ number_format($total_agoda_revenue, 2) }}</td>
                        <td>{{ number_format($total_agoda_month, 2) }}</td>
                        <td>{{ number_format($total_agoda_year, 2) }}</td>
                    </tr>
                    <tr class="tr-color-blue">
                        <td colspan="2" style="text-align: right;"><b>Elexa EGAT Revenue</b></td>
                        <td>{{ number_format($total_ev_revenue, 2) }}</td>
                        <td>{{ number_format($total_ev_month, 2) }}</td>
                        <td>{{ number_format($total_ev_year, 2) }}</td>
                    </tr>
                    <tr class="tr-color-blue">
                        <td colspan="2" style="text-align: right;"><b>Total Revenue</b></td>
                        <td>{{ number_format(($total_cash_bank + $total_charge) + ($total_wp_cash_bank + $total_wp_charge) + $total_ev_revenue + $total_agoda_revenue + $today_other_revenue, 2) }}</td>
                        <td>{{ number_format(($total_cash_bank_month + $total_charge_month) + ($total_wp_cash_bank_month + $total_wp_charge_month + $total_agoda_month + $total_ev_month) - $agoda_charge[0]['total_month'] + $total_other_month, 2) }}</td>
                        <td>{{ number_format(($total_cash_bank_year + $total_charge_year) + ($total_wp_cash_bank_year + $total_wp_charge_year + $total_agoda_year + $total_ev_year) - $agoda_charge[0]['total_year'] + $total_other_year, 2) }}</td>
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
