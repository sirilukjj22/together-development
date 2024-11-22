<style>
    .table-report-hotel-revenue th,.table-report-hotel-revenue td {
        font-size: 19px;
         padding: 3px 7px;
      }

      @media (min-width:1500px) {
         .table-report-hotel-revenue th,.table-report-hotel-revenue td {
         /* font-size: 17px; */
         font-size: 12px;
         padding: 3px 7px;
      }
    }
      
      .table-report-hotel-revenue td:nth-child(n+2),
      .table-report-hotel-revenue th:nth-child(n+2) {
        width: 6%;
      }

      .table-report-hotel-revenue td:nth-child(n+2) {
        width: 6%;
        text-align: right;
      }

      .table-report-hotel-revenue tr>td:nth-child(1) {
        white-space: wrap;
        min-width: 260px;
      }
</style>

<table id="table-data" class="table-report-hotel-revenue">
    <thead>
        <tr class="table-row-bg1 text-capitalize">
            <th>Description</th>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <th>{{ date('d/m/y', strtotime($item->date)) }}</th>
                @endforeach
            @else
                <th>Today</th>
                <th>M-T-D</th>
                <th>Y-T-D</th>
            @endif
        </tr>
    </thead>
    <tbody>
        <tr class="table-row-bg">
            <td colspan="100%">Front Desk Revenue</td>
        </tr>
        <tr>
            <td>Cash</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->front_cash, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Bank Transfer</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->front_transfer, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr class="table-row-bg">
            <td colspan="100%">Guest Deposit Revenue</td>
        </tr>
        <tr>
            <td>Cash</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->room_cash, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Bank Transfer</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->room_transfer, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr class="table-row-bg">
            <td colspan="100%">All Outlet Revenue</td>
        </tr>
        <tr>
            <td>Cash</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->fb_cash, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Bank Transfer</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->fb_transfer, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr class="table-row-bg">
            <td colspan="100%">hotel credit card revenue</td>
        </tr>
        <tr>
            <td>credit card front desk charge</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->front_charge, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>credit card guest deposit charge</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->guest_charge, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>credit card all outlet charge</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->outlet_charge, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>credit card fee</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->fee, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>credit card revenue (bank transfer)</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->total_credit, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr class="table-row-bg">
            <td colspan="100%">Agoda Revenue</td>
        </tr>
        <tr>
            <td>Agoda Charge</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->agoda_charge, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Agoda Fee </td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->agoda_fee, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Agoda Revenue</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->agoda_revenue, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Agoda Paid (bank transfer)</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->total_credit_agoda, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Agoda Revenue Outstanding </td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->agoda_revenue, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr class="table-row-bg">
            <td colspan="100%">Other Revenue</td>
        </tr>
        <tr>
            <td>Bank Transfer</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->other_revenue, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr class="white-h-05em"></tr>
        <tr class="table-row-bg">
            <td>Summary Hotel Revenue</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->cash + $item->bank_transfer + $item->agoda_outstanding, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td class="text-end f-semi">Cash</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->cash, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td class="text-end f-semi">Bank Transfer</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->bank_transfer, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td class="text-end f-semi">Agoda Revenue Outstanding Balance</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->agoda_outstanding, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr class="white-h-05em"></tr>
        <tr class="table-row-bg">
            <td colspan="100%">Water Park Revenue</td>
        </tr>
        <tr>
            <td>Cash</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->wp_cash, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Bank Transfer</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->wp_transfer, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr class="table-row-bg">
            <td colspan="100%">Water Park Credit Card Revenue</td>
        </tr>
        <tr>
            <td> Credit Card Water Park Charge </td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->wp_charge, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Credit Card Fee</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->wp_fee, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Credit Card Water Park Revenue (Bank Transfer)</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->wp_credit, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr class="table-row-bg">
            <td>Summary Water Park Revenue</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->wp_cash + $item->wp_transfer, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr class="white-h-05em"></tr>
        <tr class="table-row-bg">
            <td colspan="100%">Elexa EGAT Revenue</td>
        </tr>
        <tr>
            <td>EV Charging Charge</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->ev_charge, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Elexa Fee</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->ev_fee, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td> Elexa EGAT revenue</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->ev_revenue, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td> Elexa EGAT Paid (Bank Transfer)</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->total_elexa, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td> Elexa EGAT Outstanding Balance</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->ev_revenue, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr class="table-row-bg">
            <td>Summary Elexa EGAT Revenue</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->ev_revenue, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td class="text-end f-semi"> Bank Transfer</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->total_elexa, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td class="text-end f-semi"> Elexa EGAT Outstanding Balance</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->ev_revenue, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr class="table-row-bg">
            <td colspan="100%">Summary Revenue</td>
        </tr>
        <tr>
            <td class="text-end f-semi"> All Revenue </td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format(($item->cash + $item->bank_transfer + $item->agoda_outstanding) + ($item->wp_cash + $item->wp_transfer) + $item->ev_revenue, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td class="text-end f-semi"> Outstanding Balance From Last Year</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>0.00</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td class="text-end f-semi">Total Revenue & Outstanding Balance From Last Year</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format(($item->cash + $item->bank_transfer + $item->agoda_outstanding) + ($item->wp_cash + $item->wp_transfer) + $item->ev_revenue, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr class="table-row-bg">
            <td colspan="100%"> Payment Summary Details Report</td>
        </tr>
        <tr>
            <td class="text-end f-semi">Hotel Revenue </td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format(($item->cash + $item->bank_transfer), 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td class="text-end f-semi"> Water Park Revenue </td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format(($item->wp_cash + $item->wp_transfer), 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td class="text-end f-semi">Elexa EGAT revenue </td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->ev_revenue, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td class="text-end f-semi">Total Revenue</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format(($item->cash + $item->bank_transfer) + ($item->wp_cash + $item->wp_transfer) + $item->ev_revenue, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr class="white-h-05em"></tr>
        <tr class="table-row-bg">
            <td class="f-semi" colspan="100%">Revenue Outstanding Report</td>
        </tr>
        <tr>
            <td class="text-end f-semi">Agoda Outstanding Balance </td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->agoda_revenue, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td class="text-end f-semi">Elexa EGAT Outstanding Balance</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->ev_revenue, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td class="text-end f-semi">Total Outstanding Balance</td>
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <td>{{ number_format($item->agoda_revenue + $item->ev_revenue, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
    </tbody>
</table>