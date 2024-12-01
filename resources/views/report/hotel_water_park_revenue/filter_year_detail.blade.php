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
@endphp

<table id="table-data" class="table-report-hotel-revenue">
    <thead>
        <tr class="table-row-bg1 text-capitalize">
            <th>Description</th>
            @for ($i = 1; $i <= 12; $i++)
                <th>{{ date('M', mktime(0, 0, 0, $i, 1)) }}</th>
            @endfor
            <th>Total</th>
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
                @php
                    $sum_front_cash += $totalFrontByMonth->has($i) ? $totalFrontByMonth[$i]->front_cash : 0;
                @endphp
            @endfor
            <td>{{ number_format($sum_front_cash, 2)}}</td>
        </tr>
        <tr>
            <td>Bank Transfer</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalFrontByMonth->has($i) ? $totalFrontByMonth[$i]->front_transfer : 0, 2) }}</td>
                @php
                    $sum_front_transfer += $totalFrontByMonth->has($i) ? $totalFrontByMonth[$i]->front_transfer : 0;
                @endphp
            @endfor
            <td>{{ number_format($sum_front_transfer, 2)}}</td>
        </tr>
        <tr class="table-row-bg">
            <td colspan="100%">Guest Deposit Revenue</td>
        </tr>
        <tr>
            <td>Cash</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalGuestDepositByMonth->has($i) ? $totalGuestDepositByMonth[$i]->room_cash : 0, 2) }}</td>
                @php
                    $sum_guest_cash += $totalGuestDepositByMonth->has($i) ? $totalGuestDepositByMonth[$i]->room_cash : 0;
                @endphp
            @endfor
            <td>{{ number_format($sum_guest_cash, 2)}}</td>
        </tr>
        <tr>
            <td>Bank Transfer</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalGuestDepositByMonth->has($i) ? $totalGuestDepositByMonth[$i]->room_transfer : 0, 2) }}</td>
                @php
                    $sum_guest_transfer += $totalGuestDepositByMonth->has($i) ? $totalGuestDepositByMonth[$i]->room_transfer : 0;
                @endphp
            @endfor
            <td>{{ number_format($sum_guest_transfer, 2)}}</td>
        </tr>
        <tr class="table-row-bg">
            <td colspan="100%">All Outlet Revenue</td>
        </tr>
        <tr>
            <td>Cash</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalFbByMonth->has($i) ? $totalFbByMonth[$i]->fb_cash : 0, 2) }}</td>
                @php
                    $sum_all_outlet_cash += $totalFbByMonth->has($i) ? $totalFbByMonth[$i]->fb_cash : 0;
                @endphp
            @endfor
            <td>{{ number_format($sum_all_outlet_cash, 2)}}</td>
        </tr>
        <tr>
            <td>Bank Transfer</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalFbByMonth->has($i) ? $totalFbByMonth[$i]->fb_transfer : 0, 2) }}</td>
                @php
                    $sum_all_outlet_transfer += $totalFbByMonth->has($i) ? $totalFbByMonth[$i]->fb_transfer : 0;
                @endphp
            @endfor
            <td>{{ number_format($sum_all_outlet_transfer, 2)}}</td>
        </tr>
        <tr class="table-row-bg">
            <td colspan="100%">hotel credit card revenue</td>
        </tr>
        <tr>
            <td>credit card front desk charge</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($frontChargeByMonth->has($i) ? $frontChargeByMonth[$i]->credit_amount : 0, 2) }}</td>
                @php
                    $sum_credit_front += $frontChargeByMonth->has($i) ? $frontChargeByMonth[$i]->credit_amount : 0;
                @endphp
            @endfor
            <td>{{ number_format($sum_credit_front, 2)}}</td>
        </tr>
        <tr>
            <td>credit card guest deposit charge</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($guestDepositChargeByMonth->has($i) ? $guestDepositChargeByMonth[$i]->credit_amount : 0, 2) }}</td>
                @php
                    $sum_credit_guest += $guestDepositChargeByMonth->has($i) ? $guestDepositChargeByMonth[$i]->credit_amount : 0;
                @endphp
            @endfor
            <td>{{ number_format($sum_credit_guest, 2)}}</td>
        </tr>
        <tr>
            <td>credit card all outlet charge</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($allOutletChargeByMonth->has($i) ? $allOutletChargeByMonth[$i]->credit_amount : 0, 2) }}</td>
                @php
                    $sum_credit_all_outlet += $allOutletChargeByMonth->has($i) ? $allOutletChargeByMonth[$i]->credit_amount : 0;
                @endphp
            @endfor
            <td>{{ number_format($sum_credit_all_outlet, 2)}}</td>
        </tr>
        <tr>
            <td>credit card fee</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format(($sumChargeByMonth->has($i) ? $sumChargeByMonth[$i]->credit_amount : 0) - ($totalCreditByMonth->has($i) ? $totalCreditByMonth[$i]->total_credit : 0), 2) }}</td>
                @php
                    $sum_credit_fee += ($sumChargeByMonth->has($i) ? $sumChargeByMonth[$i]->credit_amount : 0) - ($totalCreditByMonth->has($i) ? $totalCreditByMonth[$i]->total_credit : 0);
                @endphp
            @endfor
            <td>{{ number_format($sum_credit_fee, 2)}}</td>
        </tr>
        <tr>
            <td>credit card revenue (bank transfer)</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalCreditByMonth->has($i) ? $totalCreditByMonth[$i]->total_credit : 0, 2) }}</td>
                @php
                    $sum_credit_revenue += $totalCreditByMonth->has($i) ? $totalCreditByMonth[$i]->total_credit : 0;
                @endphp
            @endfor
            <td>{{ number_format($sum_credit_revenue, 2)}}</td>
        </tr>
        <tr class="table-row-bg">
            <td colspan="100%">Agoda Revenue</td>
        </tr>
        <tr>
            <td>Agoda Charge</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalAgodaChargeByMonth->has($i) ? $totalAgodaChargeByMonth[$i]->agoda_charge : 0, 2) }}</td>
                @php
                    $sum_agoda_charge += $totalAgodaChargeByMonth->has($i) ? $totalAgodaChargeByMonth[$i]->agoda_charge : 0;
                @endphp
            @endfor
            <td>{{ number_format($sum_agoda_charge, 2)}}</td>
        </tr>
        <tr>
            <td>Agoda Fee </td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalAgodaChargeByMonth->has($i) ? $totalAgodaChargeByMonth[$i]->total_credit_agoda : 0, 2) }}</td>
                @php
                    $sum_agoda_fee += $totalAgodaChargeByMonth->has($i) ? $totalAgodaChargeByMonth[$i]->total_credit_agoda : 0;
                @endphp
            @endfor
            <td>{{ number_format($sum_agoda_fee, 2)}}</td>
        </tr>
        <tr>
            <td>Agoda Revenue</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalAgodaChargeByMonth->has($i) ? $totalAgodaChargeByMonth[$i]->agoda_outstanding : 0, 2) }}</td>
                @php
                    $sum_agoda_revenue += $totalAgodaChargeByMonth->has($i) ? $totalAgodaChargeByMonth[$i]->agoda_outstanding : 0;
                @endphp
            @endfor
            <td>{{ number_format($sum_agoda_revenue, 2)}}</td>
        </tr>
        <tr>
            <td>Agoda Paid (bank transfer)</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalAgodaByMonth->has($i) ? $totalAgodaByMonth[$i]->sum_credit_agoda : 0, 2) }}</td>
                @php
                    $sum_agoda_paid += $totalAgodaByMonth->has($i) ? $totalAgodaByMonth[$i]->sum_credit_agoda : 0;
                @endphp
            @endfor
            <td>{{ number_format($sum_agoda_paid, 2)}}</td>
        </tr>
        <tr>
            <td>Agoda Revenue Outstanding </td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalAgodaChargeByMonth->has($i) ? $totalAgodaChargeByMonth[$i]->agoda_outstanding : 0, 2) }}</td>
                @php
                    $sum_agoda_outstanding += $totalAgodaChargeByMonth->has($i) ? $totalAgodaChargeByMonth[$i]->agoda_outstanding : 0;
                @endphp
            @endfor
            <td>{{ number_format($sum_agoda_outstanding, 2)}}</td>
        </tr>
        <tr class="table-row-bg">
            <td colspan="100%">Other Revenue</td>
        </tr>
        <tr>
            <td>Bank Transfer</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalOtherByMonth->has($i) ? $totalOtherByMonth[$i]->total_other_revenue : 0, 2) }}</td>
                @php
                    $sum_other_revenue += $totalOtherByMonth->has($i) ? $totalOtherByMonth[$i]->total_other_revenue : 0;
                @endphp
            @endfor
            <td>{{ number_format($sum_other_revenue, 2)}}</td>
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
                @php
                    $sum_all_hotel_agoda += ($sum_cash + $sum_transfer + $sum_agoda);
                @endphp
            @endfor
            <td>{{ number_format($sum_all_hotel_agoda, 2)}}</td>
        </tr>
        <tr>
            <td class="text-end f-semi">Cash</td>
            @for ($i = 1; $i <= 12; $i++)
                @php
                    $sum_cash = ($totalFrontByMonth->has($i) ? $totalFrontByMonth[$i]->front_cash : 0) + ($totalGuestDepositByMonth->has($i) ? $totalGuestDepositByMonth[$i]->room_cash : 0) + ($totalFbByMonth->has($i) ? $totalFbByMonth[$i]->fb_cash : 0);
                @endphp
                <td>{{ number_format($sum_cash, 2) }}</td>
                @php
                    $sum_all_cash += ($sum_cash);
                @endphp
            @endfor
            <td>{{ number_format($sum_all_cash, 2)}}</td>
        </tr>
        <tr>
            <td class="text-end f-semi">Bank Transfer</td>
            @for ($i = 1; $i <= 12; $i++)
                @php
                    $sum_transfer = ($totalFrontByMonth->has($i) ? $totalFrontByMonth[$i]->front_transfer : 0) + ($totalGuestDepositByMonth->has($i) ? $totalGuestDepositByMonth[$i]->room_transfer : 0) + ($totalFbByMonth->has($i) ? $totalFbByMonth[$i]->fb_transfer : 0) + ($totalCreditByMonth->has($i) ? $totalCreditByMonth[$i]->total_credit : 0) + ($totalAgodaByMonth->has($i) ? $totalAgodaByMonth[$i]->sum_credit_agoda : 0);
                @endphp
                <td>{{ number_format($sum_transfer, 2) }}</td>
                @php
                    $sum_all_transfer += $sum_transfer;
                @endphp
            @endfor
            <td>{{ number_format($sum_all_transfer, 2)}}</td>
        </tr>
        <tr>
            <td class="text-end f-semi">Agoda Revenue Outstanding Balance</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalAgodaChargeByMonth->has($i) ? $totalAgodaChargeByMonth[$i]->agoda_outstanding : 0, 2) }}</td>
                @php
                    $sum_all_agoda_outstanding += $totalAgodaChargeByMonth->has($i) ? $totalAgodaChargeByMonth[$i]->agoda_outstanding : 0;
                @endphp
            @endfor
            <td>{{ number_format($sum_all_agoda_outstanding, 2)}}</td>
        </tr>
        <tr class="white-h-05em"></tr>
        <tr class="table-row-bg">
            <td colspan="100%">Water Park Revenue</td>
        </tr>
        <tr>
            <td>Cash</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalWpByMonth->has($i) ? $totalWpByMonth[$i]->wp_cash : 0, 2) }}</td>
                @php
                    $sum_water_cash += $totalWpByMonth->has($i) ? $totalWpByMonth[$i]->wp_cash : 0;
                @endphp
            @endfor
            <td>{{ number_format($sum_water_cash, 2)}}</td>
        </tr>
        <tr>
            <td>Bank Transfer</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalWpByMonth->has($i) ? $totalWpByMonth[$i]->wp_transfer : 0, 2) }}</td>
                @php
                    $sum_water_transfer += $totalWpByMonth->has($i) ? $totalWpByMonth[$i]->wp_transfer : 0;
                @endphp
            @endfor
            <td>{{ number_format($sum_water_transfer, 2)}}</td>
        </tr>
        <tr class="table-row-bg">
            <td colspan="100%">Water Park Credit Card Revenue</td>
        </tr>
        <tr>
            <td> Credit Card Water Park Charge </td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($wpChargeByMonth->has($i) ? $wpChargeByMonth[$i]->credit_amount : 0, 2) }}</td>
                @php
                    $sum_water_credit_charge += $wpChargeByMonth->has($i) ? $wpChargeByMonth[$i]->credit_amount : 0;
                @endphp
            @endfor
            <td>{{ number_format($sum_water_credit_charge, 2)}}</td>
        </tr>
        <tr>
            <td>Credit Card Fee</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($wpChargeByMonth->has($i) ? $wpChargeByMonth[$i]->total_credit : 0, 2) }}</td>
                @php
                    $sum_water_credit_fee += $wpChargeByMonth->has($i) ? $wpChargeByMonth[$i]->total_credit : 0;
                @endphp
            @endfor
            <td>{{ number_format($sum_water_credit_fee, 2)}}</td>
        </tr>
        <tr>
            <td>Credit Card Water Park Revenue (Bank Transfer)</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalWpByMonth->has($i) ? $totalWpByMonth[$i]->wp_credit : 0, 2) }}</td>
                @php
                    $sum_water_credit_revenue += $totalWpByMonth->has($i) ? $totalWpByMonth[$i]->wp_credit : 0;
                @endphp
            @endfor
            <td>{{ number_format($sum_water_credit_revenue, 2)}}</td>
        </tr>
        <tr class="table-row-bg">
            <td>Summary Water Park Revenue</td>
            @for ($i = 1; $i <= 12; $i++)
                @php
                    $sum_wp = ($totalWpByMonth->has($i) ? $totalWpByMonth[$i]->wp_cash : 0) + ($totalWpByMonth->has($i) ? $totalWpByMonth[$i]->wp_transfer : 0) + ($wpChargeByMonth->has($i) ? $wpChargeByMonth[$i]->credit_amount : 0);
                @endphp
                <td>{{ number_format($sum_wp, 2) }}</td>
                @php
                    $sum_all_water += $sum_wp;
                @endphp
            @endfor
            <td>{{ number_format($sum_all_water, 2)}}</td>
        </tr>
        <tr class="white-h-05em"></tr>
        <tr class="table-row-bg">
            <td colspan="100%">Elexa EGAT Revenue</td>
        </tr>
        <tr>
            <td>EV Charging Charge</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalEvChargeByMonth->has($i) ? $totalEvChargeByMonth[$i]->ev_charge : 0, 2) }}</td>
                @php
                    $sum_ev_charge += $totalEvChargeByMonth->has($i) ? $totalEvChargeByMonth[$i]->ev_charge : 0;
                @endphp
            @endfor
            <td>{{ number_format($sum_ev_charge, 2)}}</td>
        </tr>
        <tr>
            <td>Elexa Fee</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalEvChargeByMonth->has($i) ? $totalEvChargeByMonth[$i]->ev_fee : 0, 2) }}</td>
                @php
                    $sum_ev_fee += $totalEvChargeByMonth->has($i) ? $totalEvChargeByMonth[$i]->ev_fee : 0;
                @endphp
            @endfor
            <td>{{ number_format($sum_ev_fee, 2)}}</td>
        </tr>
        <tr>
            <td> Elexa EGAT revenue</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalEvChargeByMonth->has($i) ? $totalEvChargeByMonth[$i]->ev_revenue : 0, 2) }}</td>
                @php
                    $sum_ev_revenue += $totalEvChargeByMonth->has($i) ? $totalEvChargeByMonth[$i]->ev_revenue : 0;
                @endphp
            @endfor
            <td>{{ number_format($sum_ev_revenue, 2)}}</td>
        </tr>
        <tr>
            <td> Elexa EGAT Paid (Bank Transfer)</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalEvByMonth->has($i) ? $totalEvByMonth[$i]->sum_credit_ev : 0, 2) }}</td>
                @php
                    $sum_ev_paid += $totalEvByMonth->has($i) ? $totalEvByMonth[$i]->sum_credit_ev : 0;
                @endphp
            @endfor
            <td>{{ number_format($sum_ev_paid, 2)}}</td>
        </tr>
        <tr>
            <td> Elexa EGAT Outstanding Balance</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalEvChargeByMonth->has($i) ? $totalEvChargeByMonth[$i]->ev_revenue : 0, 2) }}</td>
                @php
                    $sum_ev_outstanding += $totalEvChargeByMonth->has($i) ? $totalEvChargeByMonth[$i]->ev_revenue : 0;
                @endphp
            @endfor
            <td>{{ number_format($sum_ev_outstanding, 2)}}</td>
        </tr>
        <tr class="table-row-bg">
            <td>Summary Elexa EGAT Revenue</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalEvChargeByMonth->has($i) ? $totalEvChargeByMonth[$i]->ev_revenue : 0, 2) }}</td>
            @endfor
            <td>{{ number_format($sum_ev_outstanding, 2)}}</td>
        </tr>
        <tr>
            <td class="text-end f-semi"> Bank Transfer</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalEvByMonth->has($i) ? $totalEvByMonth[$i]->sum_credit_ev : 0, 2) }}</td>
            @endfor
            <td>{{ number_format($sum_ev_paid, 2)}}</td>
        </tr>
        <tr>
            <td class="text-end f-semi"> Elexa EGAT Outstanding Balance</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalEvChargeByMonth->has($i) ? $totalEvChargeByMonth[$i]->ev_revenue : 0, 2) }}</td>
            @endfor
            <td>{{ number_format($sum_ev_outstanding, 2)}}</td>
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
                @php
                    $sum_all_revenue += ($sum_cash + $sum_transfer + $sum_agoda) + $sum_wp + $sum_ev;
                @endphp
            @endfor
            <td>{{ number_format($sum_all_revenue, 2)}}</td>
        </tr>
        <tr>
            <td class="text-end f-semi"> Outstanding Balance From Last Year</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format(0, 2) }}</td>
            @endfor
            <td>{{ number_format(0, 2) }}</td>
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
            <td>{{ number_format($sum_all_revenue, 2)}}</td>
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
                @php
                    $sum_hotel += ($sum_cash + $sum_transfer);
                @endphp
            @endfor
            <td>{{ number_format($sum_hotel, 2)}}</td>
        </tr>
        <tr>
            <td class="text-end f-semi"> Water Park Revenue </td>
            @for ($i = 1; $i <= 12; $i++)
                @php
                    $sum_wp = ($totalWpByMonth->has($i) ? $totalWpByMonth[$i]->wp_cash : 0) + ($totalWpByMonth->has($i) ? $totalWpByMonth[$i]->wp_transfer : 0) + ($wpChargeByMonth->has($i) ? $wpChargeByMonth[$i]->credit_amount : 0);
                @endphp
                <td>{{ number_format($sum_wp, 2) }}</td>
                @php
                    $sum_water_park += $sum_wp;
                @endphp
            @endfor
            <td>{{ number_format($sum_water_park, 2)}}</td>
        </tr>
        <tr>
            <td class="text-end f-semi">Elexa EGAT revenue </td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalEvByMonth->has($i) ? $totalEvByMonth[$i]->sum_credit_ev : 0, 2) }}</td>
                @php
                    $sum_exlexa += $totalEvByMonth->has($i) ? $totalEvByMonth[$i]->sum_credit_ev : 0;
                @endphp
            @endfor
            <td>{{ number_format($sum_exlexa, 2)}}</td>
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
                @php
                    $sum_total_revenue += ($sum_cash + $sum_transfer) + $sum_wp + $sum_ev;
                @endphp
            @endfor
            <td>{{ number_format($sum_total_revenue, 2)}}</td>
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
            <td>{{ number_format($sum_agoda_outstanding, 2)}}</td>
        </tr>
        <tr>
            <td class="text-end f-semi">Elexa EGAT Outstanding Balance</td>
            @for ($i = 1; $i <= 12; $i++)
                <td>{{ number_format($totalEvChargeByMonth->has($i) ? $totalEvChargeByMonth[$i]->ev_revenue : 0, 2) }}</td>
            @endfor
            <td>{{ number_format($sum_ev_outstanding, 2)}}</td>
        </tr>
        <tr>
            <td class="text-end f-semi">Total Outstanding Balance</td>
            @for ($i = 1; $i <= 12; $i++)
                @php
                    $sum_agoda = $totalAgodaChargeByMonth->has($i) ? $totalAgodaChargeByMonth[$i]->agoda_outstanding : 0;
                    $sum_ev = $totalEvChargeByMonth->has($i) ? $totalEvChargeByMonth[$i]->ev_revenue : 0;
                @endphp
                <td>{{ number_format($sum_agoda + $sum_ev, 2) }}</td>
                @php
                    $sum_total_outstanding += ($sum_agoda + $sum_ev);
                @endphp
            @endfor
            <td>{{ number_format($sum_total_outstanding, 2)}}</td>
        </tr>
    </tbody>
</table>