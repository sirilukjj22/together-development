<!DOCTYPE html>


<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Report Hotel Manual Charge</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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

        .table-report-manual-charge tr > :nth-child(n+3) {
            text-align: right;
        }

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

    </style>
</head>

<body>
@php
    $num = 0;
@endphp
@for ($i = 1; $i <= $page_item; $i++)
    @php
        $num += 24;
    @endphp
    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
            <div id="content-to-export" class="table-2" style="overflow-x:auto;padding: 1em;">
                @if ($page_item > 1)
                    <span style="float: right; color: #777777">Page {{ $i }} / {{ $page_item }}</span>
                @endif
                <div class="wrap-top">
                    <div class="top-img">
                      <img src="image/Logo-tg2.png" alt="logo of Together Resort" width="80"/>
                    </div>
                    <div class="top-dt" >
                      <p class="f-semi">Together Resort Kaengkrachan</p>
                      <p>Hotel and water park revenue</p>
                      <p>Date On: {{ $search_date }}</p>
                    </div>
                </div>
                <table id="table-data" class="table-report-manual-charge">
                    <thead>
                        <tr class="table-row-bg1 text-capitalize">
                            <th>#</th>
                            <th>Date</th>
                            <th>Manual Charge</th>
                            <th>Fee</th>
                            <th>SMS Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_manual = 0;
                            $total_fee = 0;
                            $total_sms = 0;
                        @endphp
                        @foreach ($data_query as $key => $item)
                            @if (($key <= $num && $key > $num - 24) || $key <= $num && $i == 1)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}
                                    </td>
                                    <td>{{ $item->manual_charge == 0 ? '-' : number_format($item->manual_charge, 2) }}</td>
                                    <td>{{ $item->fee == 0 ? '-' : number_format($item->fee, 2) }}</td>
                                    <td>{{ $item->total_credit == 0 ? '-' : number_format($item->total_credit, 2) }}</td>
                                </tr>

                                @php
                                    $total_manual += $item->manual_charge;
                                    $total_fee += $item->fee;
                                    $total_sms += $item->total_credit;
                                @endphp
                            @endif
                        @endforeach
                        @if ($i == $page_item) 
                            <tr style="font-weight: bold;">
                                <td colspan="2" class="text-end">Total</td>
                                <td>{{ number_format($total_manual, 2) }}</td>
                                <td>{{ number_format($total_fee, 2) }}</td>
                                <td>{{ number_format($total_sms, 2) }}</td>
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
