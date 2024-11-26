<!DOCTYPE html>


<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Report Revenue</title>
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
            <?php
                if (isset($search_date)) {
                    $date_current = $search_date;
                } else {
                    $date_current = date('d/m/Y').' - '.date('d/m/Y');
                }

                $this_week = date('d M', strtotime('last sunday', strtotime('next sunday', strtotime(date('Y-m-d'))))); // อาทิตย์ - เสาร์
        
                $formatMonth = date('Y-m', strtotime($date_current));

                $exp_date = array_map('trim', explode(' - ', $date_current));

                if ($filter_by == 'date' && count($exp_date) == 2) {
                    $FormatDate = Carbon\Carbon::createFromFormat('d/m/Y', $exp_date[0]);
                    $FormatDate2 = Carbon\Carbon::createFromFormat('d/m/Y', $exp_date[1]);

                    $diffInDay = Carbon\Carbon::create(Carbon\Carbon::parse($FormatDate->format('Y-m-d')))->diffInDays(Carbon\Carbon::parse($FormatDate2->format('Y-m-d'))) + 1;

                    $day_sum = $diffInDay == 1 ? date('t') : $diffInDay;

                } elseif ($filter_by == 'week') {
                    $diffInDay = 7;
                    $day_sum = 7;
                } else {
                    $diffInDay = isset($formatMonth) ? date('t', strtotime($formatMonth)) : date('t');
                    $day_sum = $diffInDay;
                }

                $pickup_time = $date_current;

                if ($filter_by == 'date' && count($exp_date) != 2 || $filter_by == 'today' || $filter_by == 'yesterday' || $filter_by == 'tomorrow') {
                    $pickup_time = date('d F Y', strtotime($search_date));
                } elseif ($filter_by == 'date' && count($exp_date) == 2) {
                    $pickup_time = date('d M', strtotime($FormatDate)) . " " . substr(date('Y', strtotime($FormatDate)), -2) . " ~ ". date('d M', strtotime($FormatDate2)) . " " . substr(date('Y', strtotime($FormatDate2)), -2);
                } elseif ($filter_by == 'month') {
                    $pickup_time = date('F Y', strtotime($search_date));
                } elseif ($filter_by == 'year') {
                    $pickup_time = $search_date;
                } elseif ($filter_by == 'week') {
                    $pickup_time = date('d M', strtotime('last sunday', strtotime('next sunday', strtotime(date('Y-m-d')))))." ~ ".date('d M', strtotime("+6 day", strtotime($this_week)));
                } elseif ($filter_by == 'thisMonth') {
                    $pickup_time = "01 " . date('M') . " ~ " . date('t M');
                } elseif ($filter_by == 'thisYear') {
                    $pickup_time = "01 " . "Jan" . " ~ ". date('d M', strtotime(date('Y-m-d')));
                }
                if ($filter_by == 'date' && count($exp_date) == 2 && $exp_date[0] == $exp_date[1]) {
                    $pickup_time = date('d F Y', strtotime(Carbon\Carbon::createFromFormat('d/m/Y', $exp_date[0])));
                }

            ?>
            <div class="clearfix" style="color: #020202;">
                <table id="topic" cellpadding="2" style="margin-bottom:0px;">
                    <tr>
                        <td width="10%" align="left"><b>Date : </b></td>
                        <td width="45%" align="left">{{ $pickup_time }}</td>
                        <td width="30%"></td>
                        <td><i>Page 1/3 {{ $filter_by }}</i></td>
                    </tr>
                </table>
            </div>
            @php
                $total_cash = $total_front_revenue->front_cash + $total_guest_deposit->room_cash + $total_fb_revenue->fb_cash;
                $total_cash_month = $total_front_month->front_cash + $total_guest_deposit_month->room_cash + $total_fb_month->fb_cash;
                $total_cash_year = $total_front_year->front_cash + $total_guest_deposit_year->room_cash + $total_fb_year->fb_cash;

                $total_bank_transfer = $total_front_revenue->front_transfer + $total_guest_deposit->room_transfer + $total_fb_revenue->fb_transfer + $total_other_revenue;
                $total_bank_transfer_month = $total_front_month->front_transfer + $total_guest_deposit_month->room_transfer + $total_fb_month->fb_transfer + $total_other_month;
                $total_bank_transfer_year = $total_front_year->front_transfer + $total_guest_deposit_year->room_transfer + $total_fb_year->fb_transfer + $total_other_year;

                $total_wp_cash_bank = $total_wp_revenue->wp_cash + $total_wp_revenue->wp_transfer;
                $total_wp_cash_bank_month = $total_wp_month->wp_cash + $total_wp_month->wp_transfer;
                $total_wp_cash_bank_year = $total_wp_year->wp_cash + $total_wp_year->wp_transfer;

                $total_cash_bank = $total_cash + $total_bank_transfer;
                $total_cash_bank_month = $total_cash_month + $total_bank_transfer_month;
                $total_cash_bank_year = $total_cash_year + $total_bank_transfer_year;

                $exp_dateRang = explode(' - ', $search_date);

                if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1]) {
                    $numCol = 5;
                } elseif ($filter_by == "month") {
                    $numCol = 4;
                } elseif ($filter_by == "year") {
                    $numCol = 3;
                } else {
                    $numCol = "100%";
                }
            @endphp
            <table id="detail" cellpadding="5" style="line-height: 12px;">
                <thead>
                    <tr style="background-color: rgba(7, 45, 41, 0.734);">
                        <th style="text-align: center;" colspan="2"><b>Description</b></th>

                        @if ($filter_by == "date")
                            <th style="text-align: left;"><b>{{ $filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] ? "Today" : $search_date }}</b></th>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <th style="text-align: left;"><b>M-T-D</b></th>
                            @endif
                            <th style="text-align: left;"><b>Y-T-D</b></th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    <tr style="text-align: left; background-color: rgb(187, 226, 226);">
                        <td colspan="{{ $numCol }}"><b>Front Desk Revenue</b></td>
                    </tr>
                    <tr>
                        <td colspan="2">Cash</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format(isset($total_front_revenue) ? $total_front_revenue->front_cash : 0, 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format(isset($total_front_month) && $filter_by != "year" ? $total_front_month->front_cash : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format(isset($total_front_year) ? $total_front_year->front_cash : 0, 2) }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">Bank Transfer</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format(isset($total_front_revenue) ? $total_front_revenue->front_transfer : 0, 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format(isset($total_front_month) && $filter_by != "year" ? $total_front_month->front_transfer : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-m-d">{{ number_format(isset($total_front_year) ? $total_front_year->front_transfer : 0, 2) }}</td>
                        @endif
                    </tr>
                    <tr style="text-align: left; background-color: rgb(187, 226, 226);">
                        <td colspan="{{ $numCol }}"><b>Guest Deposit Revenue</b></td>
                    </tr>
                    <tr>
                        <td colspan="2">Cash</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format(isset($total_guest_deposit) ? $total_guest_deposit->room_cash : 0, 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format(isset($total_guest_deposit_month) && $filter_by != "year" ? $total_guest_deposit_month->room_cash : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format(isset($total_guest_deposit_year) ? $total_guest_deposit_year->room_cash : 0, 2) }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">Bank Transfer</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format(isset($total_guest_deposit) ? $total_guest_deposit->room_transfer : 0, 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format(isset($total_guest_deposit_month) && $filter_by != "year" ? $total_guest_deposit_month->room_transfer : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format(isset($total_guest_deposit_year) ? $total_guest_deposit_year->room_transfer : 0, 2) }}</td>
                        @endif
                    </tr>
                    <tr style="text-align: left; background-color: rgb(187, 226, 226);">
                        <td colspan="{{ $numCol }}"><b>All Outlet Revenue</b></td>
                    </tr>
                    <tr>
                        <td colspan="2">Cash</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format(isset($total_fb_revenue) ? $total_fb_revenue->fb_cash : 0, 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $total_fb_month->fb_cash : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($total_fb_year->fb_cash ?? 0, 2) }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">Bank Transfer</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format(isset($total_fb_revenue) ? $total_fb_revenue->fb_transfer : 0, 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $total_fb_month->fb_transfer : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($total_fb_year->fb_transfer ?? 0, 2) }}</td>
                        @endif
                    </tr>

                    @php
                        $total_credit_card_revenue = $front_charge[0]['revenue_credit_date'] + $guest_deposit_charge[0]['revenue_credit_date'] + $fb_charge[0]['revenue_credit_date'];
                        $total_credit_card_revenue_month = $front_charge[0]['revenue_credit_month'] + $guest_deposit_charge[0]['revenue_credit_month'] + $fb_charge[0]['revenue_credit_month'];
                        $total_credit_card_revenue_year = $front_charge[0]['revenue_credit_year'] + $guest_deposit_charge[0]['revenue_credit_year'] + $fb_charge[0]['revenue_credit_year'];

                        $total_charge = $credit_revenue->total_credit ?? 0;
                        $total_charge_month = $credit_revenue_month->total_credit ?? 0;
                        $total_charge_year = $credit_revenue_year->total_credit ?? 0;

                        $total_wp_credit_card_revenue = $wp_charge[0]['revenue_credit_date'];
                        $total_wp_credit_card_revenue_month = $wp_charge[0]['revenue_credit_month'];
                        $total_wp_credit_card_revenue_year = $wp_charge[0]['revenue_credit_year'];

                        $total_wp_charge = $wp_charge[0]['total'];
                        $total_wp_charge_month = $wp_charge[0]['total_month'];
                        $total_wp_charge_year = $wp_charge[0]['total_year'];
                    @endphp

                    <tr style="text-align: left; background-color: rgb(187, 226, 226);">
                        <td colspan="{{ $numCol }}"><b>Hotel credit card revenue</b></td>
                    </tr>
                    <tr>
                        <td colspan="2">credit card front desk charge</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($front_charge[0]['revenue_credit_date'], 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $front_charge[0]['revenue_credit_month'] : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($front_charge[0]['revenue_credit_year'], 2) }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">credit card guest deposit charge</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($guest_deposit_charge[0]['revenue_credit_date'], 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $guest_deposit_charge[0]['revenue_credit_month'] : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($guest_deposit_charge[0]['revenue_credit_year'], 2) }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">credit card all outlet charge</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($fb_charge[0]['revenue_credit_date'], 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $fb_charge[0]['revenue_credit_month'] : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($fb_charge[0]['revenue_credit_year'], 2) }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">credit card fee</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($total_credit_card_revenue == 0 || $credit_revenue->total_credit == 0 ? 0 : $total_credit_card_revenue - $credit_revenue->total_credit ?? 0, 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">
                                    @if ($total_credit_card_revenue_month == 0 || $credit_revenue_month->total_credit == 0)
                                        0.00
                                    @else
                                        {{ number_format($filter_by != "year" ? ($total_credit_card_revenue_month - $credit_revenue_month->total_credit ?? 0) : 0, 2) }}
                                    @endif
                                </td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format(($total_credit_card_revenue_year - $credit_revenue_year->total_credit ?? 0), 2) }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">credit card revenue (bank transfer)</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($credit_revenue->total_credit, 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? ($credit_revenue_month->total_credit ?? 0) : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format(($credit_revenue_year->total_credit ?? 0), 2) }}</td>
                        @endif
                    </tr>

                    @php
                        $agoda_revenue_outstanding_date = $agoda_charge[0]['total'];
                        $agoda_revenue_outstanding_month = $agoda_charge[0]['total_month'];
                        $agoda_revenue_outstanding_year = $agoda_charge[0]['total_year'] - $total_agoda_year;
                    @endphp

                    <tr style="text-align: left; background-color: rgb(187, 226, 226);">
                        <td colspan="{{ $numCol }}"><b>Agoda Revenue</b></td>
                    </tr>
                    <tr>
                        <td colspan="2">Agoda Charge</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($agoda_charge[0]['revenue_credit_date'], 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $agoda_charge[0]['revenue_credit_month'] : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($agoda_charge[0]['revenue_credit_year'], 2) }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">Agoda Fee </td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($agoda_charge[0]['fee_date'], 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $agoda_charge[0]['fee_month'] : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($agoda_charge[0]['fee_year'], 2) }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">Agoda Revenue</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($agoda_charge[0]['total'], 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $agoda_charge[0]['total_month'] : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($agoda_charge[0]['total_year'], 2) }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">Agoda Paid (bank transfer)</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($total_agoda_revenue, 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $total_agoda_month : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($total_agoda_year, 2) }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">Agoda Revenue Outstanding </td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($agoda_revenue_outstanding_date, 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $agoda_revenue_outstanding_month : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($agoda_revenue_outstanding_year, 2) }}</td>
                        @endif
                    </tr>
                    <tr style="text-align: left; background-color: rgb(187, 226, 226);">
                        <td colspan="{{ $numCol }}"><b>Other Revenue</b></td>
                    </tr>
                    <tr>
                        <td colspan="2">Bank Transfer</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($total_other_revenue, 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $total_other_month : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($total_other_year, 2) }}</td>
                        @endif
                    </tr>

                    <tr>
                        <td colspan="{{ $numCol }}" class="bdl bdr"></td>
                    </tr>

                    @php
                        // Bank Transfer
                        $summary_hotel_revenue_bank_date = $total_bank_transfer + ($credit_revenue->total_credit ?? 0) + $total_agoda_revenue;
                        $summary_hotel_revenue_bank_month = $total_bank_transfer_month + ($credit_revenue_month->total_credit ?? 0) + $total_agoda_month;
                        $summary_hotel_revenue_bank_year = $total_bank_transfer_year + ($credit_revenue_year->total_credit ?? 0) + $total_agoda_year;
                    @endphp

                    <tr style="text-align: left; background-color: rgb(187, 226, 226);">
                        <td colspan="2"><b>Summary Hotel Revenue</b></td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day"><b>{{ number_format($summary_hotel_revenue_bank_date + $total_cash + $agoda_revenue_outstanding_date, 2) }}</b></td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d"><b>{{ number_format($filter_by != "year" ? $summary_hotel_revenue_bank_month + $total_cash_month + $agoda_revenue_outstanding_month : 0, 2) }}</b></td>
                            @endif
                            <td class="td-default y-t-d"><b>{{ number_format($summary_hotel_revenue_bank_year + $total_cash_year + $agoda_revenue_outstanding_year, 2) }}</b></td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">Cash</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($total_cash, 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $total_cash_month : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($total_cash_year, 2) }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">Bank Transfer</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($summary_hotel_revenue_bank_date, 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $summary_hotel_revenue_bank_month : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($summary_hotel_revenue_bank_year, 2) }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">Agoda Revenue Outstanding Balance</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($agoda_revenue_outstanding_date, 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $agoda_revenue_outstanding_month : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($agoda_revenue_outstanding_year, 2) }}</td>
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
                        <td width="45%" align="left">{{ $pickup_time }}</td>
                        <td width="30%"></td>
                        <td><i>Page 2/3</i></td>
                    </tr>
                </table>
            </div>
            <table id="detail" cellpadding="5" style="line-height: 12px;">
                <thead>
                    <tr style="background-color: rgba(7, 45, 41, 0.734);">
                        <th style="text-align: center;" colspan="2"><b>Description</b></th>

                        @if ($filter_by == "date")
                            <th style="text-align: left;"><b>{{ $filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] ? "Today" : $search_date }}</b></th>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <th style="text-align: left;"><b>M-T-D</b></th>
                            @endif
                            <th style="text-align: left;"><b>Y-T-D</b></th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    <tr style="text-align: left; background-color: rgb(187, 226, 226);">
                        <td colspan="{{ $numCol }}"><b>Water Park Revenue</b></td>
                    </tr>
                    <tr>
                        <td colspan="2">Cash</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format(isset($total_wp_revenue) ? $total_wp_revenue->wp_cash : 0, 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $total_wp_month->wp_cash ?? 0 : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($total_wp_year->wp_cash ?? 0, 2) }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">Bank Transfer</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format(isset($total_wp_revenue) ? $total_wp_revenue->wp_transfer : 0, 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $total_wp_month->wp_transfer : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($total_wp_year->wp_transfer, 2) }}</td>
                        @endif
                    </tr>
                    <tr style="text-align: left; background-color: rgb(187, 226, 226);">
                        <td colspan="{{ $numCol }}"><b>Water Park Credit Card Revenue</b></td>
                    </tr>
                    <tr>
                        <td colspan="2">Credit Card Water Park Charge </td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($total_wp_credit_card_revenue, 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $total_wp_credit_card_revenue_month : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($total_wp_credit_card_revenue_year, 2) }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">Credit Card Fee</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($wp_charge[0]['fee_date'], 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $wp_charge[0]['fee_month'] : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($wp_charge[0]['fee_year'], 2) }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">Credit Card Water Park Revenue (Bank Transfer)</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($total_wp_charge, 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $total_wp_charge_month : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($total_wp_charge_year, 2) }}</td>
                        @endif
                    </tr>
                    <tr style="text-align: left; background-color: rgb(187, 226, 226);">
                        <td colspan="2"><b>Summary Water Park Revenue</b></td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day"><b>{{ number_format(($total_wp_revenue->wp_cash + $total_wp_revenue->wp_transfer) + $total_wp_charge, 2) }}</b></td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d"><b>{{ number_format($filter_by != "year" ? ($total_wp_cash_bank_month + $total_wp_charge_month) : 0, 2) }}</b></td>
                            @endif
                            <td class="td-default y-t-d"><b>{{ number_format($total_wp_cash_bank_year + $total_wp_charge_year, 2) }}</b></td>
                        @endif
                    </tr>

                    <tr>
                        <td colspan="{{ $numCol }}" class="bdl bdr"></td>
                    </tr>

                    <tr style="text-align: left; background-color: rgb(187, 226, 226);">
                        <td colspan="{{ $numCol }}"><b>Elexa EGAT Revenue</b></td>
                    </tr>
                    <tr>
                        <td colspan="2">EV Charging Charge</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($ev_charge[0]['revenue_credit_date'], 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $ev_charge[0]['revenue_credit_month'] : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($ev_charge[0]['revenue_credit_year'], 2) }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">Elexa Fee</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($ev_charge[0]['fee_date'], 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $ev_charge[0]['fee_month'] : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($ev_charge[0]['fee_year'], 2) }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">Elexa EGAT revenue</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($ev_charge[0]['total'], 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $ev_charge[0]['total_month'] : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($ev_charge[0]['total_year'], 2) }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">Elexa EGAT Paid (Bank Transfer)</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($total_ev_revenue, 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $total_ev_month : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($total_ev_year, 2) }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">Elexa EGAT Outstanding Balance</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($ev_charge[0]['total'], 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $ev_charge[0]['total_month'] : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($ev_charge[0]['total_year'] - $total_ev_year, 2) }}</td>
                        @endif
                    </tr>
                    <tr style="text-align: left; background-color: rgb(187, 226, 226);">
                        <td colspan="2"><b>Summary Elexa EGAT Revenue</b></td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day"><b>{{ number_format($ev_charge[0]['total'] + $total_ev_revenue, 2) }}</b></td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d"><b>{{ number_format($filter_by != "year" ? ($ev_charge[0]['total_month'] + $total_ev_month) : 0, 2) }}</b></td>
                            @endif
                            <td class="td-default y-t-d"><b>{{ number_format($ev_charge[0]['total_year'], 2) }}</b></td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">Bank Transfer</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($total_ev_revenue, 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $total_ev_month : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($total_ev_year, 2) }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">Elexa EGAT Outstanding Balance</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($ev_charge[0]['total'], 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $ev_charge[0]['total_month'] : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($ev_charge[0]['total_year'] - $total_ev_year, 2) }}</td>
                        @endif
                    </tr>
                    <tr style="text-align: left; background-color: rgb(187, 226, 226);">
                        <td colspan="{{ $numCol }}"><b>Summary Revenue</b></td>
                    </tr>
                    <tr>
                        <td colspan="2"> All Revenue </td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format(($summary_hotel_revenue_bank_date + $total_cash + $agoda_revenue_outstanding_date) + ($total_wp_cash_bank + $total_wp_charge) + ($ev_charge[0]['total'] + $total_ev_revenue), 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? (($summary_hotel_revenue_bank_month + $total_cash_month + $agoda_revenue_outstanding_month) + ($total_wp_cash_bank_month + $total_wp_charge_month) + ($ev_charge[0]['total_month'] + $total_ev_month)) : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format(($summary_hotel_revenue_bank_year + $total_cash_year + $agoda_revenue_outstanding_year) + ($total_wp_cash_bank_year + $total_wp_charge_year) + $ev_charge[0]['total_year'], 2) }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">Outstanding Balance From Last Year</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">0.00</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">0.00</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($agoda_outstanding_last_year + $elexa_outstanding_last_year, 2) }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">Total Revenue & Outstanding Balance From Last Year</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format(($total_cash_bank + $total_charge) + ($total_wp_cash_bank + $total_wp_charge) + ($agoda_charge[0]['total'] + ($ev_charge[0]['total'] + $total_ev_revenue)) + $total_agoda_revenue, 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? (($summary_hotel_revenue_bank_month + $total_cash_month + $agoda_revenue_outstanding_month) + ($total_wp_cash_bank_month + $total_wp_charge_month) + ($ev_charge[0]['total_month'] + $total_ev_month)) : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format((($summary_hotel_revenue_bank_year + $total_cash_year + $agoda_revenue_outstanding_year) + ($total_wp_cash_bank_year + $total_wp_charge_year) + $ev_charge[0]['total_year']), 2) }}</td>
                        @endif
                    </tr>
                    <tr style="text-align: left; background-color: rgb(187, 226, 226);">
                        <td colspan="{{ $numCol }}"><b>Payment Summary Details Report</b></td>
                    </tr>
                    <tr>
                        <td colspan="2">Hotel Revenue </td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($summary_hotel_revenue_bank_date + $total_cash, 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $summary_hotel_revenue_bank_month + $total_cash_month : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($summary_hotel_revenue_bank_year + $total_cash_year, 2) }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2"> Water Park Revenue </td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format(($total_wp_revenue->wp_cash + $total_wp_revenue->wp_transfer) + $total_wp_charge, 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? ($total_wp_cash_bank_month + $total_wp_charge_month) : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($total_wp_cash_bank_year + $total_wp_charge_year, 2) }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">Elexa EGAT revenue </td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($total_ev_revenue, 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $total_ev_month : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($total_ev_year, 2) }}</td>
                        @endif
                    </tr>
            
                    @php
                        $summary_details_report_date = ($summary_hotel_revenue_bank_date + $total_cash) + ($total_wp_revenue->wp_cash + $total_wp_revenue->wp_transfer + $total_wp_charge) + $total_ev_revenue;
                        $summary_details_report_month = ($summary_hotel_revenue_bank_month + $total_cash_month) + ($total_wp_cash_bank_month + $total_wp_charge_month) + $total_ev_month;
                        $summary_details_report_year = ($summary_hotel_revenue_bank_year + $total_cash_year) + ($total_wp_cash_bank_year + $total_wp_charge_year) + $total_ev_year;
                    @endphp
            
                    <tr>
                        <td colspan="2">Total Revenue</td>

                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($summary_details_report_date, 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $summary_details_report_month : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($summary_details_report_year, 2) }}</td>
                        @endif
                    </tr>
                    <tr class="white-h-05em"></tr>
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
                        <td width="45%" align="left">{{ $pickup_time }}</td>
                        <td width="30%"></td>
                        <td><i>Page 3/3</i></td>
                    </tr>
                </table>
            </div>
            <table id="detail" cellpadding="5" style="line-height: 12px;">
                <thead>
                    <tr style="background-color: rgba(7, 45, 41, 0.734);">
                        <th style="text-align: center;" colspan="2"><b>Description</b></th>

                        @if ($filter_by == "date")
                            <th style="text-align: left;"><b>{{ $filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] ? "Today" : $search_date }}</b></th>
                        @endif
                        
                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <th style="text-align: left;"><b>M-T-D</b></th>
                            @endif
                            <th style="text-align: left;"><b>Y-T-D</b></th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    <tr style="text-align: left; background-color: rgb(187, 226, 226);">
                        <td colspan="{{ $numCol }}"><b>Revenue Outstanding Report</b></td>
                    </tr>
                    <tr>
                        <td colspan="2">Agoda Outstanding Balance </td>
                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($agoda_revenue_outstanding_date, 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $agoda_revenue_outstanding_month : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($agoda_revenue_outstanding_year, 2) }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">Elexa EGAT Outstanding Balance</td>
                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($ev_charge[0]['total'], 2) }}</td>
                        @endif

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $ev_charge[0]['total_month'] : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($ev_charge[0]['total_year'] - $total_ev_year, 2) }}</td>
                        @endif
                    </tr>
            
                    @php
                        $revenue_outstanding_report_date = $agoda_revenue_outstanding_date + $ev_charge[0]['total'];
                        $revenue_outstanding_report_month = $agoda_revenue_outstanding_month + $ev_charge[0]['total_month'];
                        $revenue_outstanding_report_year = $agoda_revenue_outstanding_year + ($ev_charge[0]['total_year'] - $total_ev_year);
                    @endphp
            
                    <tr>
                        <td colspan="2">Total Outstanding Balance</td>
                        @if ($filter_by == "date")
                            <td class="td-default to-day">{{ number_format($revenue_outstanding_report_date, 2) }}</td>
                        @endif  

                        @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                            @if ($filter_by == "month" || $filter_by == "date")
                                <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $revenue_outstanding_report_month : 0, 2) }}</td>
                            @endif
                            <td class="td-default y-t-d">{{ number_format($revenue_outstanding_report_year, 2) }}</td>
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
</body>

</html>
