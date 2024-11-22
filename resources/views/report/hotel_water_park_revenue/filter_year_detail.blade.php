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

@php
    $totalFrontByMonth = $total_front->keyBy('month');
    $totalGuestDepositByMonth = $total_guest_deposit->keyBy('month');
    $totalFbByMonth = $total_fb->keyBy('month');
    $frontChargeByMonth = $front_charge->keyBy('month');
    $guestDepositChargeByMonth = $guest_deposit_charge->keyBy('month');
    $sumChargeByMonth = $sum_charge->keyBy('month'); // Sum Hotel Charge
    $allOutletChargeByMonth = $all_outlet_charge->keyBy('month');
    $totalCreditByMonth = $total_credit->keyBy('month');
    $totalAgodaChargeByMonth = $total_agoda_charge->keyBy('month');
    $totalAgodaByMonth = $total_agoda->keyBy('month');
    $totalOtherByMonth = $total_other->keyBy('month');
    $totalWpByMonth = $total_wp->keyBy('month');
    $wpChargeByMonth = $wp_charge->keyBy('month');
    $totalEvByMonth = $total_ev->keyBy('month');
    $totalEvChargeByMonth = $total_ev_charge->keyBy('month');
@endphp

<table id="table-data" class="table-report-hotel-revenue">
    <thead>
        <tr class="table-row-bg1 text-capitalize">
            <th>Description</th>
            @for ($i = 1; $i <= 12; $i++)
                <th>{{ date('M', mktime(0, 0, 0, $i, 1)) }}</th>
            @endfor
        </tr>
    </thead>
    <tbody>
        <tr class="table-row-bg">
            <td colspan="100%">Front Desk Revenue</td>
        </tr>
        <tr>
            <td>Cash</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalFrontByMonth->has($i) ? $totalFrontByMonth[$i]->front_cash : 0, 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td>Bank Transfer</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalFrontByMonth->has($i) ? $totalFrontByMonth[$i]->front_transfer : 0, 2) }}</td>
            @endfor
        </tr>
        <tr class="table-row-bg">
            <td colspan="100%">Guest Deposit Revenue</td>
        </tr>
        <tr>
            <td>Cash</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalGuestDepositByMonth->has($i) ? $totalGuestDepositByMonth[$i]->room_cash : 0, 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td>Bank Transfer</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalGuestDepositByMonth->has($i) ? $totalGuestDepositByMonth[$i]->room_transfer : 0, 2) }}</td>
            @endfor
        </tr>
        <tr class="table-row-bg">
            <td colspan="100%">All Outlet Revenue</td>
        </tr>
        <tr>
            <td>Cash</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalFbByMonth->has($i) ? $totalFbByMonth[$i]->fb_cash : 0, 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td>Bank Transfer</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalFbByMonth->has($i) ? $totalFbByMonth[$i]->fb_transfer : 0, 2) }}</td>
            @endfor
        </tr>
        <tr class="table-row-bg">
            <td colspan="100%">hotel credit card revenue</td>
        </tr>
        <tr>
            <td>credit card front desk charge</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($frontChargeByMonth->has($i) ? $frontChargeByMonth[$i]->credit_amount : 0, 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td>credit card guest deposit charge</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($guestDepositChargeByMonth->has($i) ? $guestDepositChargeByMonth[$i]->credit_amount : 0, 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td>credit card all outlet charge</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($allOutletChargeByMonth->has($i) ? $allOutletChargeByMonth[$i]->credit_amount : 0, 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td>credit card fee</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format(($sumChargeByMonth->has($i) ? $sumChargeByMonth[$i]->credit_amount : 0) - ($totalCreditByMonth->has($i) ? $totalCreditByMonth[$i]->total_credit : 0), 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td>credit card revenue (bank transfer)</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalCreditByMonth->has($i) ? $totalCreditByMonth[$i]->total_credit : 0, 2) }}</td>
            @endfor
        </tr>
        <tr class="table-row-bg">
            <td colspan="100%">Agoda Revenue</td>
        </tr>
        <tr>
            <td>Agoda Charge</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalAgodaChargeByMonth->has($i) ? $totalAgodaChargeByMonth[$i]->agoda_charge : 0, 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td>Agoda Fee </td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalAgodaChargeByMonth->has($i) ? $totalAgodaChargeByMonth[$i]->total_credit_agoda : 0, 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td>Agoda Revenue</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalAgodaChargeByMonth->has($i) ? $totalAgodaChargeByMonth[$i]->agoda_outstanding : 0, 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td>Agoda Paid (bank transfer)</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalAgodaByMonth->has($i) ? $totalAgodaByMonth[$i]->sum_credit_agoda : 0, 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td>Agoda Revenue Outstanding </td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalAgodaChargeByMonth->has($i) ? $totalAgodaChargeByMonth[$i]->agoda_outstanding : 0, 2) }}</td>
            @endfor
        </tr>
        <tr class="table-row-bg">
            <td colspan="100%">Other Revenue</td>
        </tr>
        <tr>
            <td>Bank Transfer</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalOtherByMonth->has($i) ? $totalOtherByMonth[$i]->total_other_revenue : 0, 2) }}</td>
            @endfor
        </tr>
        <tr class="white-h-05em"></tr>
        <tr class="table-row-bg">
            <td>Summary Hotel Revenue</td>
            @for ($i = 1; $i <= 12; $i++)
                @php
                    $sum_cash = ($totalFrontByMonth->has($i) ? $totalFrontByMonth[$i]->front_cash : 0) + ($totalGuestDepositByMonth->has($i) ? $totalGuestDepositByMonth[$i]->room_cash : 0) + ($totalFbByMonth->has($i) ? $totalFbByMonth[$i]->fb_cash : 0);
                    $sum_transfer = ($totalFrontByMonth->has($i) ? $totalFrontByMonth[$i]->front_transfer : 0) + ($totalGuestDepositByMonth->has($i) ? $totalGuestDepositByMonth[$i]->room_transfer : 0) + ($totalFbByMonth->has($i) ? $totalFbByMonth[$i]->fb_transfer : 0) + ($totalCreditByMonth->has($i) ? $totalCreditByMonth[$i]->total_credit : 0) + ($totalAgodaByMonth->has($i) ? $totalAgodaByMonth[$i]->sum_credit_agoda : 0);
                    $sum_agoda = $totalAgodaChargeByMonth->has($i) ? $totalAgodaChargeByMonth[$i]->agoda_outstanding : 0;
                @endphp
                <td>{{ number_format($sum_cash + $sum_transfer + $sum_agoda, 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td class="text-end f-semi">Cash</td>
            @for ($i = 1; $i <= 12; $i++)
                @php
                    $sum_cash = ($totalFrontByMonth->has($i) ? $totalFrontByMonth[$i]->front_cash : 0) + ($totalGuestDepositByMonth->has($i) ? $totalGuestDepositByMonth[$i]->room_cash : 0) + ($totalFbByMonth->has($i) ? $totalFbByMonth[$i]->fb_cash : 0);
                @endphp
                <td>{{ number_format($sum_cash, 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td class="text-end f-semi">Bank Transfer</td>
            @for ($i = 1; $i <= 12; $i++)
                @php
                    $sum_transfer = ($totalFrontByMonth->has($i) ? $totalFrontByMonth[$i]->front_transfer : 0) + ($totalGuestDepositByMonth->has($i) ? $totalGuestDepositByMonth[$i]->room_transfer : 0) + ($totalFbByMonth->has($i) ? $totalFbByMonth[$i]->fb_transfer : 0) + ($totalCreditByMonth->has($i) ? $totalCreditByMonth[$i]->total_credit : 0) + ($totalAgodaByMonth->has($i) ? $totalAgodaByMonth[$i]->sum_credit_agoda : 0);
                @endphp
                <td>{{ number_format($sum_transfer, 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td class="text-end f-semi">Agoda Revenue Outstanding Balance</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalAgodaChargeByMonth->has($i) ? $totalAgodaChargeByMonth[$i]->agoda_outstanding : 0, 2) }}</td>
            @endfor
        </tr>
        <tr class="white-h-05em"></tr>
        <tr class="table-row-bg">
            <td colspan="100%">Water Park Revenue</td>
        </tr>
        <tr>
            <td>Cash</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalWpByMonth->has($i) ? $totalWpByMonth[$i]->wp_cash : 0, 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td>Bank Transfer</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalWpByMonth->has($i) ? $totalWpByMonth[$i]->wp_transfer : 0, 2) }}</td>
            @endfor
        </tr>
        <tr class="table-row-bg">
            <td colspan="100%">Water Park Credit Card Revenue</td>
        </tr>
        <tr>
            <td> Credit Card Water Park Charge </td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($wpChargeByMonth->has($i) ? $wpChargeByMonth[$i]->credit_amount : 0, 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td>Credit Card Fee</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($wpChargeByMonth->has($i) ? $wpChargeByMonth[$i]->total_credit : 0, 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td>Credit Card Water Park Revenue (Bank Transfer)</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalWpByMonth->has($i) ? $totalWpByMonth[$i]->wp_credit : 0, 2) }}</td>
            @endfor
        </tr>
        <tr class="table-row-bg">
            <td>Summary Water Park Revenue</td>
            @for ($i = 1; $i <= 12; $i++)
                @php
                    $sum_wp = ($totalWpByMonth->has($i) ? $totalWpByMonth[$i]->wp_cash : 0) + ($totalWpByMonth->has($i) ? $totalWpByMonth[$i]->wp_transfer : 0) + ($wpChargeByMonth->has($i) ? $wpChargeByMonth[$i]->credit_amount : 0);
                @endphp
                <td>{{ number_format($sum_wp, 2) }}</td>
            @endfor
        </tr>
        <tr class="white-h-05em"></tr>
        <tr class="table-row-bg">
            <td colspan="100%">Elexa EGAT Revenue</td>
        </tr>
        <tr>
            <td>EV Charging Charge</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalEvChargeByMonth->has($i) ? $totalEvChargeByMonth[$i]->ev_charge : 0, 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td>Elexa Fee</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalEvChargeByMonth->has($i) ? $totalEvChargeByMonth[$i]->ev_fee : 0, 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td> Elexa EGAT revenue</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalEvChargeByMonth->has($i) ? $totalEvChargeByMonth[$i]->ev_revenue : 0, 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td> Elexa EGAT Paid (Bank Transfer)</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalEvByMonth->has($i) ? $totalEvByMonth[$i]->sum_credit_ev : 0, 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td> Elexa EGAT Outstanding Balance</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalEvChargeByMonth->has($i) ? $totalEvChargeByMonth[$i]->ev_revenue : 0, 2) }}</td>
            @endfor
        </tr>
        <tr class="table-row-bg">
            <td>Summary Elexa EGAT Revenue</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalEvChargeByMonth->has($i) ? $totalEvChargeByMonth[$i]->ev_revenue : 0, 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td class="text-end f-semi"> Bank Transfer</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalEvByMonth->has($i) ? $totalEvByMonth[$i]->sum_credit_ev : 0, 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td class="text-end f-semi"> Elexa EGAT Outstanding Balance</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalEvChargeByMonth->has($i) ? $totalEvChargeByMonth[$i]->ev_revenue : 0, 2) }}</td>
            @endfor
        </tr>
        <tr class="table-row-bg">
            <td colspan="100%">Summary Revenue</td>
        </tr>
        <tr>
            <td class="text-end f-semi"> All Revenue </td>
            @for ($i = 1; $i <= 12; $i++)
                @php
                    $sum_cash = ($totalFrontByMonth->has($i) ? $totalFrontByMonth[$i]->front_cash : 0) + ($totalGuestDepositByMonth->has($i) ? $totalGuestDepositByMonth[$i]->room_cash : 0) + ($totalFbByMonth->has($i) ? $totalFbByMonth[$i]->fb_cash : 0);
                    $sum_transfer = ($totalFrontByMonth->has($i) ? $totalFrontByMonth[$i]->front_transfer : 0) + ($totalGuestDepositByMonth->has($i) ? $totalGuestDepositByMonth[$i]->room_transfer : 0) + ($totalFbByMonth->has($i) ? $totalFbByMonth[$i]->fb_transfer : 0) + ($totalCreditByMonth->has($i) ? $totalCreditByMonth[$i]->total_credit : 0) + ($totalAgodaByMonth->has($i) ? $totalAgodaByMonth[$i]->sum_credit_agoda : 0);
                    $sum_agoda = $totalAgodaChargeByMonth->has($i) ? $totalAgodaChargeByMonth[$i]->agoda_outstanding : 0;
                    $sum_wp = ($totalWpByMonth->has($i) ? $totalWpByMonth[$i]->wp_cash : 0) + ($totalWpByMonth->has($i) ? $totalWpByMonth[$i]->wp_transfer : 0) + ($wpChargeByMonth->has($i) ? $wpChargeByMonth[$i]->credit_amount : 0);
                    $sum_ev = $totalEvChargeByMonth->has($i) ? $totalEvChargeByMonth[$i]->ev_revenue : 0;
                @endphp
                <td>{{ number_format(($sum_cash + $sum_transfer + $sum_agoda) + $sum_wp + $sum_ev, 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td class="text-end f-semi"> Outstanding Balance From Last Year</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format(0, 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td class="text-end f-semi">Total Revenue & Outstanding Balance From Last Year</td>
            @for ($i = 1; $i <= 12; $i++)
                @php
                    $sum_cash = ($totalFrontByMonth->has($i) ? $totalFrontByMonth[$i]->front_cash : 0) + ($totalGuestDepositByMonth->has($i) ? $totalGuestDepositByMonth[$i]->room_cash : 0) + ($totalFbByMonth->has($i) ? $totalFbByMonth[$i]->fb_cash : 0);
                    $sum_transfer = ($totalFrontByMonth->has($i) ? $totalFrontByMonth[$i]->front_transfer : 0) + ($totalGuestDepositByMonth->has($i) ? $totalGuestDepositByMonth[$i]->room_transfer : 0) + ($totalFbByMonth->has($i) ? $totalFbByMonth[$i]->fb_transfer : 0) + ($totalCreditByMonth->has($i) ? $totalCreditByMonth[$i]->total_credit : 0) + ($totalAgodaByMonth->has($i) ? $totalAgodaByMonth[$i]->sum_credit_agoda : 0);
                    $sum_agoda = $totalAgodaChargeByMonth->has($i) ? $totalAgodaChargeByMonth[$i]->agoda_outstanding : 0;
                    $sum_wp = ($totalWpByMonth->has($i) ? $totalWpByMonth[$i]->wp_cash : 0) + ($totalWpByMonth->has($i) ? $totalWpByMonth[$i]->wp_transfer : 0) + ($wpChargeByMonth->has($i) ? $wpChargeByMonth[$i]->credit_amount : 0);
                    $sum_ev = $totalEvChargeByMonth->has($i) ? $totalEvChargeByMonth[$i]->ev_revenue : 0;
                @endphp
                <td>{{ number_format(($sum_cash + $sum_transfer + $sum_agoda) + $sum_wp + $sum_ev, 2) }}</td>
            @endfor
        </tr>
        <tr class="table-row-bg">
            <td colspan="100%"> Payment Summary Details Report</td>
        </tr>
        <tr>
            <td class="text-end f-semi">Hotel Revenue </td>
            @for ($i = 1; $i <= 12; $i++)
                @php
                    $sum_cash = ($totalFrontByMonth->has($i) ? $totalFrontByMonth[$i]->front_cash : 0) + ($totalGuestDepositByMonth->has($i) ? $totalGuestDepositByMonth[$i]->room_cash : 0) + ($totalFbByMonth->has($i) ? $totalFbByMonth[$i]->fb_cash : 0);
                    $sum_transfer = ($totalFrontByMonth->has($i) ? $totalFrontByMonth[$i]->front_transfer : 0) + ($totalGuestDepositByMonth->has($i) ? $totalGuestDepositByMonth[$i]->room_transfer : 0) + ($totalFbByMonth->has($i) ? $totalFbByMonth[$i]->fb_transfer : 0) + ($totalCreditByMonth->has($i) ? $totalCreditByMonth[$i]->total_credit : 0) + ($totalAgodaByMonth->has($i) ? $totalAgodaByMonth[$i]->sum_credit_agoda : 0);
                @endphp
                <td>{{ number_format(($sum_cash + $sum_transfer), 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td class="text-end f-semi"> Water Park Revenue </td>
            @for ($i = 1; $i <= 12; $i++)
                @php
                    $sum_wp = ($totalWpByMonth->has($i) ? $totalWpByMonth[$i]->wp_cash : 0) + ($totalWpByMonth->has($i) ? $totalWpByMonth[$i]->wp_transfer : 0) + ($wpChargeByMonth->has($i) ? $wpChargeByMonth[$i]->credit_amount : 0);
                @endphp
                <td>{{ number_format($sum_wp, 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td class="text-end f-semi">Elexa EGAT revenue </td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalEvByMonth->has($i) ? $totalEvByMonth[$i]->sum_credit_ev : 0, 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td class="text-end f-semi">Total Revenue</td>
            @for ($i = 1; $i <= 12; $i++)
                @php
                    $sum_cash = ($totalFrontByMonth->has($i) ? $totalFrontByMonth[$i]->front_cash : 0) + ($totalGuestDepositByMonth->has($i) ? $totalGuestDepositByMonth[$i]->room_cash : 0) + ($totalFbByMonth->has($i) ? $totalFbByMonth[$i]->fb_cash : 0);
                    $sum_transfer = ($totalFrontByMonth->has($i) ? $totalFrontByMonth[$i]->front_transfer : 0) + ($totalGuestDepositByMonth->has($i) ? $totalGuestDepositByMonth[$i]->room_transfer : 0) + ($totalFbByMonth->has($i) ? $totalFbByMonth[$i]->fb_transfer : 0) + ($totalCreditByMonth->has($i) ? $totalCreditByMonth[$i]->total_credit : 0) + ($totalAgodaByMonth->has($i) ? $totalAgodaByMonth[$i]->sum_credit_agoda : 0);
                    $sum_wp = ($totalWpByMonth->has($i) ? $totalWpByMonth[$i]->wp_cash : 0) + ($totalWpByMonth->has($i) ? $totalWpByMonth[$i]->wp_transfer : 0) + ($wpChargeByMonth->has($i) ? $wpChargeByMonth[$i]->credit_amount : 0);
                    $sum_ev = $totalEvByMonth->has($i) ? $totalEvByMonth[$i]->sum_credit_ev : 0;
                @endphp
                <td>{{ number_format(($sum_cash + $sum_transfer) + $sum_wp + $sum_ev, 2) }}</td>
            @endfor
        </tr>
        <tr class="white-h-05em"></tr>
        <tr class="table-row-bg">
            <td class="f-semi" colspan="100%">Revenue Outstanding Report</td>
        </tr>
        <tr>
            <td class="text-end f-semi">Agoda Outstanding Balance </td>
            @for ($i = 1; $i <= 12; $i++)
                @php
                    $sum_agoda = $totalAgodaChargeByMonth->has($i) ? $totalAgodaChargeByMonth[$i]->agoda_outstanding : 0;
                @endphp
                <td>{{ number_format($sum_agoda, 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td class="text-end f-semi">Elexa EGAT Outstanding Balance</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalEvChargeByMonth->has($i) ? $totalEvChargeByMonth[$i]->ev_revenue : 0, 2) }}</td>
            @endfor
        </tr>
        <tr>
            <td class="text-end f-semi">Total Outstanding Balance</td>
            @for ($i = 1; $i <= 12; $i++)
                @php
                    $sum_agoda = $totalAgodaChargeByMonth->has($i) ? $totalAgodaChargeByMonth[$i]->agoda_outstanding : 0;
                    $sum_ev = $totalEvChargeByMonth->has($i) ? $totalEvChargeByMonth[$i]->ev_revenue : 0;
                @endphp
                <td>{{ number_format($sum_agoda + $sum_ev, 2) }}</td>
            @endfor
        </tr>
    </tbody>
</table>