<!DOCTYPE html>


<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Proposal Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 0.2cm 1.0cm 0.5cm 1.0cm;
            size: A4 landscape; /* กำหนดขนาดเป็น A4 และแนวนอน */
        }

        body {
            font-size: 18px;
            font-family: "THSarabunNew";
        }

        .table-2 {
            border-radius: 0.5rem;
            border-width: 2px;
            --tw-bg-opacity: 1;
            background-color: rgb(255 255 255 / var(--tw-bg-opacity));
            padding: 0.75em 0.5em;
            box-shadow: rgba(0, 0, 0, 0.07) 0px 1px 2px, rgba(0, 0, 0, 0.07) 0px 2px 4px,
                rgba(0, 0, 0, 0.07) 0px 4px 8px, rgba(0, 0, 0, 0.07) 0px 8px 16px,
                rgba(0, 0, 0, 0.07) 0px 16px 32px, rgba(0, 0, 0, 0.07) 0px 32px 64px;
        }

        @media (min-width: 768px) {
            .table-2 {
                grid-column: span 12 / span 12;
            }
        }

        .table-2  .wrap-top  .top-img {
        float: left;
        width: 70px;
        margin-right: 1em;
        text-align: center;
        }

        .table-2 .wrap-top .top-img img {
            margin-bottom: 0.5em;
        }


        .table-2 .wrap-top .top-dt p  {
            line-height: 13px;
        }

        .table-2 .wrap-top .top-dt  p:nth-child(1) {
            font-size: 20px;
            font-weight: bold;
            line-height: 15px;
        }

        .table-report-manual-charge {
            width: 100%;
            color: rgb(3, 47, 39);
            border-collapse: collapse;
            margin-top:0.7em;
        }

        .table-report-manual-charge tr > :nth-child(1),.table-report-manual-charge tr > :nth-child(2) {
            text-align: center ;
        }

        /* .table-report-manual-charge tr > :nth-child(n+3) {
            text-align: right;
        } */

        .table-report-manual-charge tr.table-row-bg1 th {
            border: 1px solid rgba(210, 219, 218, 0.514);
            padding: 0.1em 0.1em;
            background: #0c6f67;
            color: white;
            font-size: 1em;
            text-align: center !important;
        }

        .table-report-manual-charge td {
            border: 1px solid rgb(236, 235, 235);
            padding: 2px 5px;
            text-transform: capitalize;
        }


        .table-report-manual-charge tbody tr td.total-table-revenue {
            font-weight: 600;
            text-align: end;
        }

        .table-report-manual-charge tbody tr:nth-child(odd):not(.table-row-bg) {
            background-color: rgba(245, 245, 245, 0.501);
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

        footer {
            color: #777777;
            width: 100%;
            height: 4%;
            position: absolute;
            bottom: 10px;
            font-size: 16px;
            /* border-top: 1px solid #AAAAAA; */
            padding: 8px 0;
            text-align: center;
        }

        .wrapper-page {
            page-break-after: always;
        }

        .wrapper-page:last-child {
            page-break-after: avoid;
        }

    </style>
</head>

<body>
@php
    $num = 0;
    $total_manual = 0;
    $total_fee = 0;
    $total_sms = 0;
    $number = 0;
    $number_round = 0;
@endphp
@for ($i = 1; $i <= $page_item; $i++)
    @php
        $num += 11;
    @endphp
    <div class="wrapper-page">
        <header class="clearfix" style="color: #020202;">
            @if ($page_item > 1)
                <span style="float: right; color: #777777; margin-right: 20px;">Page {{ $i }} / {{ $page_item }}</span>
            @endif
        </header>
        <div class="container-xl">
            <div id="content-to-export" class="table-2" style="overflow-x:auto;padding: 1em;">

                <div class="wrap-top">
                    <div class="top-img">
                      <img src="image/Logo-tg2.png" alt="logo of Together Resort" width="80"/>
                    </div>
                    <div class="top-dt" >
                      <p class="f-semi">Together Resort Kaengkrachan</p>
                      <p>Proposal</p>
                      <p>Date On: {{ $search_date }}</p>
                    </div>
                </div>
                <table id="table-data" class="table-report-manual-charge">
                    <thead>
                        <tr class="table-row-bg1 text-capitalize">
                            <th >#</th>
                            <th >Proposal ID</th>
                            <th >Company / Individual</th>
                            <th >Check IN</th>
                            <th >Check OUT</th>
                            <th >Issue Date</th>
                            <th >Creatd By</th>
                            <th >Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_query as $key => $item)
                        @if (($key <= $num && $key > $num - 11) || $key <= $num && $i == 1)
                            <tr style="vertical-align: middle;">
                                <td class="td-content-center">{{ $key + 1 }}</td>
                                <td class="td-content-center">{{ $item->Quotation_ID}}</td>
                                @if ($item->type_Proposal == 'Company')
                                    <td class="td-content-center ">{{ @$item->company->Company_Name}}</td>
                                @else
                                    <td class="td-content-center ">{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                @endif
                                <td  style="text-align:center;">
                                    {{ $item->checkin}}
                                </td>
                                <td  style="text-align:center;">
                                    {{ $item->checkout}}
                                </td>
                                <td  style="text-align:center;">{{ $item->issue_date }}</td>
                                <td  style="text-align:center;">{{ @$item->userOperated->name }}</td>
                                <td  style="text-align:right;">{{ number_format($item->Nettotal, 2) }}</td>
                            </tr>
                        @endif
                    @endforeach
                    @if ($i == $page_item)
                        <tr style="font-weight: bold;">
                            <td colspan="7"  style="text-align:center;">Total</td>
                            <td style="text-align: right;">{{ number_format($total_quotation, 2) }}</td>

                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
            <footer>
                <h4>Together Resort Limited Partnership</h4>
            </footer>
        </div>
    </div>
@endfor
</body>

</html>