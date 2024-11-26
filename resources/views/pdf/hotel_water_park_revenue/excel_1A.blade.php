<table id="detail" cellpadding="5" style="line-height: 12px;">
    <thead>
        <tr style="background-color: rgba(7, 45, 41, 0.734);">
            <th>Description {{ $filter_by }}</th>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <th>{{ date('d/m/y', strtotime($item->date)) }}</th>
                @endforeach
            @else
                <th style="text-align: center;">Today</th>
                <th style="text-align: center;">M-T-D</th>
                <th style="text-align: center;">Y-T-D</th>
            @endif
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="100%" style="text-align: left; background-color: rgb(187, 226, 226);"><b>Front Desk Revenue</b>
            </td>
        </tr>
        <tr>
            <td>Cash</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->front_cash, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Bank Transfer</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->front_transfer, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td colspan="100%" style="text-align: left; background-color: rgb(187, 226, 226);"><b>Guest Deposit
                    Revenue</b></td>
        </tr>
        <tr>
            <td>Cash</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->room_cash, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Bank Transfer</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->room_transfer, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td colspan="100%" style="text-align: left; background-color: rgb(187, 226, 226);"><b>All Outlet Revenue</b>
            </td>
        </tr>
        <tr>
            <td>Cash</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->fb_cash, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Bank Transfer</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->fb_transfer, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td colspan="100%" style="text-align: left; background-color: rgb(187, 226, 226);"><b>hotel credit card revenue</b></td>
        </tr>
        <tr>
            <td>credit card front desk charge</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->front_charge, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>credit card guest deposit charge</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->guest_charge, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>credit card all outlet charge</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->outlet_charge, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>credit card fee</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->fee, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>credit card revenue (bank transfer)</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->total_credit, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td colspan="100%" style="text-align: left; background-color: rgb(187, 226, 226);"><b>Agoda Revenue</b></td>
        </tr>
        <tr>
            <td>Agoda Charge</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->agoda_charge, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Agoda Fee </td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->agoda_fee, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Agoda Revenue</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->agoda_revenue, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Agoda Paid (bank transfer)</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->total_credit_agoda, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Agoda Revenue Outstanding </td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->agoda_revenue, 2) }}</td>
                @endforeach
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
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->other_revenue, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr style="text-align: left; background-color: rgb(187, 226, 226);">
            <td>Summary Hotel Revenue</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->cash + $item->bank_transfer + $item->agoda_outstanding, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Cash</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->cash, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Bank Transfer</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->bank_transfer, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Agoda Revenue Outstanding Balance</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->agoda_outstanding, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td colspan="100%" style="text-align: left; background-color: rgb(187, 226, 226);"><b>Water Park Revenue</b>
            </td>
        </tr>
        <tr>
            <td>Cash</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->wp_cash, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Bank Transfer</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->wp_transfer, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td colspan="100%" style="text-align: left; background-color: rgb(187, 226, 226);"><b>Water Park Credit Card Revenue</b></td>
        </tr>
        <tr>
            <td> Credit Card Water Park Charge </td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->wp_charge, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Credit Card Fee</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->wp_fee, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Credit Card Water Park Revenue (Bank Transfer)</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->wp_credit, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr style="text-align: left; background-color: rgb(187, 226, 226);">
            <td>Summary Water Park Revenue</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->wp_cash + $item->wp_transfer, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>

        <tr>
            <td colspan="100%" style="text-align: left; background-color: rgb(187, 226, 226);">Elexa EGAT Revenue</td>
        </tr>
        <tr>
            <td>EV Charging Charge</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->ev_charge, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Elexa Fee</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->ev_fee, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td> Elexa EGAT revenue</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->ev_revenue, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td> Elexa EGAT Paid (Bank Transfer)</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->total_elexa, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td> Elexa EGAT Outstanding Balance</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->ev_revenue, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr style="text-align: left; background-color: rgb(187, 226, 226);">
            <td>Summary Elexa EGAT Revenue</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->ev_revenue, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td> Bank Transfer</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->total_elexa, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td> Elexa EGAT Outstanding Balance</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->ev_revenue, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr style="text-align: left; background-color: rgb(187, 226, 226);">
            <td colspan="100%">Summary Revenue</td>
        </tr>
        <tr>
            <td> All Revenue </td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">
                        {{ number_format($item->cash + $item->bank_transfer + $item->agoda_outstanding + ($item->wp_cash + $item->wp_transfer) + $item->ev_revenue, 2) }}
                    </td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td> Outstanding Balance From Last Year</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">0.00</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Total Revenue and Outstanding Balance From Last Year</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->cash + $item->bank_transfer + $item->agoda_outstanding + ($item->wp_cash + $item->wp_transfer) + $item->ev_revenue, 2) }}
                    </td>
                @endforeach
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
            <td>Hotel Revenue </td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->cash + $item->bank_transfer, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td> Water Park Revenue </td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->wp_cash + $item->wp_transfer, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Elexa EGAT revenue </td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->ev_revenue, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Total Revenue</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">
                        {{ number_format($item->cash + $item->bank_transfer + ($item->wp_cash + $item->wp_transfer) + $item->ev_revenue, 2) }}
                    </td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr style="text-align: left; background-color: rgb(187, 226, 226);">
            <td class="f-semi" colspan="100%">Revenue Outstanding Report</td>
        </tr>
        <tr>
            <td>Agoda Outstanding Balance </td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->agoda_revenue, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Elexa EGAT Outstanding Balance</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->ev_revenue, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
        <tr>
            <td>Total Outstanding Balance</td>
            @if ($filter_by == 'date' && $status == 'detail')
                @foreach ($data_query as $item)
                    <td style="text-align: right;">{{ number_format($item->agoda_revenue + $item->ev_revenue, 2) }}</td>
                @endforeach
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
    </tbody>
</table>
