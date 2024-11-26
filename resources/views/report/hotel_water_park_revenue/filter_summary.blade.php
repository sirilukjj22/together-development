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
@endphp

<table id="table-data" class="table-report-hotel-revenue">
    <thead>
        <tr class="table-row-bg1 text-capitalize">
            <th>Description</th>
            <th class="to-day">{{ isset($filter_by) && $filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] ? "Today" : $search_date }}</th>
            <th class="m-t-d">M-T-D</th>
            <th class="y-t-d">Y-T-D</th>
        </tr>
    </thead>
    <tbody>
        <tr class="table-row-bg">
            <td colspan="100%">Front Desk Revenue</td>
        </tr>
        <tr>
            <td>Cash</td>
            <td class="td-default to-day">{{ number_format(isset($total_front_revenue) ? $total_front_revenue->front_cash : 0, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format(isset($total_front_month) && $filter_by != "year" ? $total_front_month->front_cash : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format(isset($total_front_year) ? $total_front_year->front_cash : 0, 2) }}</td>
        </tr>
        <tr>
            <td>Bank Transfer</td>
            <td class="td-default to-day">{{ number_format(isset($total_front_revenue) ? $total_front_revenue->front_transfer : 0, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format(isset($total_front_month) && $filter_by != "year" ? $total_front_month->front_transfer : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format(isset($total_front_year) ? $total_front_year->front_transfer : 0, 2) }}</td>
        </tr>
        <tr class="table-row-bg">
            <td colspan="100%">Guest Deposit Revenue</td>
        </tr>
        <tr>
            <td>Cash</td>
            <td class="td-default to-day">{{ number_format(isset($total_guest_deposit) ? $total_guest_deposit->room_cash : 0, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format(isset($total_guest_deposit_month) && $filter_by != "year" ? $total_guest_deposit_month->room_cash : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format(isset($total_guest_deposit_year) ? $total_guest_deposit_year->room_cash : 0, 2) }}</td>
        </tr>
        <tr>
            <td>Bank Transfer</td>
            <td class="td-default to-day">{{ number_format(isset($total_guest_deposit) ? $total_guest_deposit->room_transfer : 0, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format(isset($total_guest_deposit_month) && $filter_by != "year" ? $total_guest_deposit_month->room_transfer : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format(isset($total_guest_deposit_year) ? $total_guest_deposit_year->room_transfer : 0, 2) }}</td>
        </tr>
        <tr class="table-row-bg">
            <td colspan="100%">All Outlet Revenue</td>
        </tr>
        <tr>
            <td>Cash</td>
            <td class="td-default to-day">{{ number_format(isset($total_fb_revenue) ? $total_fb_revenue->fb_cash : 0, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $total_fb_month->fb_cash : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($total_fb_year->fb_cash ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td>Bank Transfer</td>
            <td class="td-default to-day">{{ number_format(isset($total_fb_revenue) ? $total_fb_revenue->fb_transfer : 0, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $total_fb_month->fb_transfer : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($total_fb_year->fb_transfer ?? 0, 2) }}</td>
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

        <tr class="table-row-bg">
            <td colspan="100%">Hotel credit card revenue</td>
        </tr>
        <tr>
            <td>credit card front desk charge</td>
            <td class="td-default to-day">{{ number_format($front_charge[0]['revenue_credit_date'], 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $front_charge[0]['revenue_credit_month'] : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($front_charge[0]['revenue_credit_year'], 2) }}</td>
        </tr>
        <tr>
            <td>credit card guest deposit charge</td>
            <td class="td-default to-day">{{ number_format($guest_deposit_charge[0]['revenue_credit_date'], 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $guest_deposit_charge[0]['revenue_credit_month'] : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($guest_deposit_charge[0]['revenue_credit_year'], 2) }}</td>
        </tr>
        <tr>
            <td>credit card all outlet charge</td>
            <td class="td-default to-day">{{ number_format($fb_charge[0]['revenue_credit_date'], 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $fb_charge[0]['revenue_credit_month'] : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($fb_charge[0]['revenue_credit_year'], 2) }}</td>
        </tr>
        <tr>
            <td>credit card fee</td>
            <td class="td-default to-day">{{ number_format($total_credit_card_revenue == 0 || $credit_revenue->total_credit == 0 ? 0 : $total_credit_card_revenue - $credit_revenue->total_credit ?? 0, 2) }}</td>
            <td class="td-default m-t-d">
                @if ($total_credit_card_revenue_month == 0 || $credit_revenue_month->total_credit == 0)
                    0.00
                @else
                    {{ number_format($filter_by != "year" ? ($total_credit_card_revenue_month - $credit_revenue_month->total_credit ?? 0) : 0, 2) }}
                @endif
            </td>
            <td class="td-default y-t-d">{{ number_format(($total_credit_card_revenue_year - $credit_revenue_year->total_credit ?? 0), 2) }}</td>
        </tr>
        <tr>
            <td>credit card revenue (bank transfer)</td>
            <td class="td-default to-day">{{ number_format($credit_revenue->total_credit, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? ($credit_revenue_month->total_credit ?? 0) : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format(($credit_revenue_year->total_credit ?? 0), 2) }}</td>
        </tr>

        @php
            $agoda_revenue_outstanding_date = $agoda_charge[0]['total'];
            $agoda_revenue_outstanding_month = $agoda_charge[0]['total_month'];
            $agoda_revenue_outstanding_year = $agoda_charge[0]['total_year'] - $total_agoda_year;
        @endphp

        <tr class="table-row-bg">
            <td colspan="100%">Agoda Revenue</td>
        </tr>
        <tr>
            <td>Agoda Charge</td>
            <td class="td-default to-day">{{ number_format($agoda_charge[0]['revenue_credit_date'], 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $agoda_charge[0]['revenue_credit_month'] : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($agoda_charge[0]['revenue_credit_year'], 2) }}</td>
        </tr>
        <tr>
            <td>Agoda Fee </td>
            <td class="td-default to-day">{{ number_format($agoda_charge[0]['fee_date'], 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $agoda_charge[0]['fee_month'] : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($agoda_charge[0]['fee_year'], 2) }}</td>
        </tr>
        <tr>
            <td>Agoda Revenue</td>
            <td class="td-default to-day">{{ number_format($agoda_charge[0]['total'], 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $agoda_charge[0]['total_month'] : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($agoda_charge[0]['total_year'], 2) }}</td>
        </tr>
        <tr>
            <td>Agoda Paid (bank transfer)</td>
            <td class="td-default to-day">{{ number_format($total_agoda_revenue, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $total_agoda_month : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($total_agoda_year, 2) }}</td>
        </tr>
        <tr>
            <td>Agoda Revenue Outstanding </td>
            <td class="td-default to-day">{{ number_format($agoda_revenue_outstanding_date, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $agoda_revenue_outstanding_month : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($agoda_revenue_outstanding_year, 2) }}</td>
        </tr>
        <tr class="table-row-bg">
            <td colspan="100%">Other Revenue</td>
        </tr>
        <tr>
            <td>Bank Transfer</td>
            <td class="td-default to-day">{{ number_format($total_other_revenue, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $total_other_month : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($total_other_year, 2) }}</td>
        </tr>

        <tr class="white-h-05em"></tr>

        @php
            // Bank Transfer
            $summary_hotel_revenue_bank_date = $total_bank_transfer + ($credit_revenue->total_credit ?? 0) + $total_agoda_revenue;
            $summary_hotel_revenue_bank_month = $total_bank_transfer_month + ($credit_revenue_month->total_credit ?? 0) + $total_agoda_month;
            $summary_hotel_revenue_bank_year = $total_bank_transfer_year + ($credit_revenue_year->total_credit ?? 0) + $total_agoda_year;
        @endphp

        <tr class="table-row-bg">
            <td>Summary Hotel Revenue</td>
            <td class="td-default to-day">{{ number_format($summary_hotel_revenue_bank_date + $total_cash + $agoda_revenue_outstanding_date, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $summary_hotel_revenue_bank_month + $total_cash_month + $agoda_revenue_outstanding_month : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($summary_hotel_revenue_bank_year + $total_cash_year + $agoda_revenue_outstanding_year, 2) }}</td>
        </tr>
        <tr>
            <td class="text-end f-semi">Cash</td>
            <td class="td-default to-day">{{ number_format($total_cash, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $total_cash_month : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($total_cash_year, 2) }}</td>
        </tr>
        <tr>
            <td class="text-end f-semi">Bank Transfer</td>
            <td class="td-default to-day">{{ number_format($summary_hotel_revenue_bank_date, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $summary_hotel_revenue_bank_month : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($summary_hotel_revenue_bank_year, 2) }}</td>
        </tr>
        <tr>
            <td class="text-end f-semi">Agoda Revenue Outstanding Balance</td>
            <td class="td-default to-day">{{ number_format($agoda_revenue_outstanding_date, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $agoda_revenue_outstanding_month : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($agoda_revenue_outstanding_year, 2) }}</td>
        </tr>
        <tr class="white-h-05em"></tr>
        <tr class="table-row-bg">
            <td colspan="100%">Water Park Revenue</td>
        </tr>
        <tr>
            <td>Cash</td>
            <td class="td-default to-day">{{ number_format(isset($total_wp_revenue) ? $total_wp_revenue->wp_cash : 0, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $total_wp_month->wp_cash ?? 0 : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($total_wp_year->wp_cash ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td>Bank Transfer</td>
            <td class="td-default to-day">{{ number_format(isset($total_wp_revenue) ? $total_wp_revenue->wp_transfer : 0, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $total_wp_month->wp_transfer : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($total_wp_year->wp_transfer, 2) }}</td>
        </tr>
        <tr class="table-row-bg">
            <td colspan="100%">Water Park Credit Card Revenue</td>
        </tr>
        <tr>
            <td> Credit Card Water Park Charge </td>
            <td class="td-default to-day">{{ number_format($total_wp_credit_card_revenue, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $total_wp_credit_card_revenue_month : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($total_wp_credit_card_revenue_year, 2) }}</td>
        </tr>
        <tr>
            <td>Credit Card Fee</td>
            <td class="td-default to-day">{{ number_format($wp_charge[0]['fee_date'], 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $wp_charge[0]['fee_month'] : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($wp_charge[0]['fee_year'], 2) }}</td>
        </tr>
        <tr>
            <td>Credit Card Water Park Revenue (Bank Transfer)</td>
            <td class="td-default to-day">{{ number_format($total_wp_charge, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $total_wp_charge_month : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($total_wp_charge_year, 2) }}</td>
        </tr>
        <tr class="table-row-bg">
            <td>Summary Water Park Revenue</td>
            <td class="td-default to-day">{{ number_format(($total_wp_revenue->wp_cash + $total_wp_revenue->wp_transfer) + $total_wp_charge, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? ($total_wp_cash_bank_month + $total_wp_charge_month) : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($total_wp_cash_bank_year + $total_wp_charge_year, 2) }}</td>
        </tr>
        <tr class="white-h-05em"></tr>
        <tr class="table-row-bg">
            <td colspan="100%">Elexa EGAT Revenue</td>
        </tr>
        <tr>
            <td>EV Charging Charge</td>
            <td class="td-default to-day">{{ number_format($ev_charge[0]['revenue_credit_date'], 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $ev_charge[0]['revenue_credit_month'] : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($ev_charge[0]['revenue_credit_year'], 2) }}</td>
        </tr>
        <tr>
            <td>Elexa Fee</td>
            <td class="td-default to-day">{{ number_format($ev_charge[0]['fee_date'], 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $ev_charge[0]['fee_month'] : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($ev_charge[0]['fee_year'], 2) }}</td>
        </tr>
        <tr>
            <td> Elexa EGAT revenue</td>
            <td class="td-default to-day">{{ number_format($ev_charge[0]['total'], 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $ev_charge[0]['total_month'] : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($ev_charge[0]['total_year'], 2) }}</td>
        </tr>
        <tr>
            <td> Elexa EGAT Paid (Bank Transfer)</td>
            <td class="td-default to-day">{{ number_format($total_ev_revenue, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $total_ev_month : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($total_ev_year, 2) }}</td>
        </tr>
        <tr>
            <td> Elexa EGAT Outstanding Balance</td>
            <td class="td-default to-day">{{ number_format($ev_charge[0]['total'], 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $ev_charge[0]['total_month'] : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($ev_charge[0]['total_year'] - $total_ev_year, 2) }}</td>
        </tr>
        <tr class="table-row-bg">
            <td>Summary Elexa EGAT Revenue</td>
            <td class="td-default to-day">{{ number_format($ev_charge[0]['total'] + $total_ev_revenue, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? ($ev_charge[0]['total_month'] + $total_ev_month) : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($ev_charge[0]['total_year'], 2) }}</td>
        </tr>
        <tr>
            <td class="text-end f-semi"> Bank Transfer</td>
            <td class="td-default to-day">{{ number_format($total_ev_revenue, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $total_ev_month : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($total_ev_year, 2) }}</td>
        </tr>
        <tr>
            <td class="text-end f-semi"> Elexa EGAT Outstanding Balance</td>
            <td class="td-default to-day">{{ number_format($ev_charge[0]['total'], 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $ev_charge[0]['total_month'] : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($ev_charge[0]['total_year'] - $total_ev_year, 2) }}</td>
        </tr>
        <tr class="table-row-bg">
            <td colspan="100%">Summary Revenue</td>
        </tr>
        <tr>
            <td class="text-end f-semi"> All Revenue </td>
            <td class="td-default to-day">{{ number_format(($summary_hotel_revenue_bank_date + $total_cash + $agoda_revenue_outstanding_date) + ($total_wp_cash_bank + $total_wp_charge) + ($ev_charge[0]['total'] + $total_ev_revenue), 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? (($summary_hotel_revenue_bank_month + $total_cash_month + $agoda_revenue_outstanding_month) + ($total_wp_cash_bank_month + $total_wp_charge_month) + ($ev_charge[0]['total_month'] + $total_ev_month)) : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format(($summary_hotel_revenue_bank_year + $total_cash_year + $agoda_revenue_outstanding_year) + ($total_wp_cash_bank_year + $total_wp_charge_year) + $ev_charge[0]['total_year'], 2) }}</td>
        </tr>
        <tr>
            <td class="text-end f-semi"> Outstanding Balance From Last Year</td>
            <td class="td-default to-day">0.00</td>
            <td class="td-default m-t-d">0.00</td>
            <td class="td-default y-t-d">{{ number_format($agoda_outstanding_last_year + $elexa_outstanding_last_year, 2) }}</td>
        </tr>
        <tr>
            <td class="text-end f-semi">Total Revenue & Outstanding Balance From Last Year</td>
            <td class="td-default to-day">{{ number_format(($total_cash_bank + $total_charge) + ($total_wp_cash_bank + $total_wp_charge) + ($agoda_charge[0]['total'] + ($ev_charge[0]['total'] + $total_ev_revenue)) + $total_agoda_revenue, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? (($summary_hotel_revenue_bank_month + $total_cash_month + $agoda_revenue_outstanding_month) + ($total_wp_cash_bank_month + $total_wp_charge_month) + ($ev_charge[0]['total_month'] + $total_ev_month)) : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format((($summary_hotel_revenue_bank_year + $total_cash_year + $agoda_revenue_outstanding_year) + ($total_wp_cash_bank_year + $total_wp_charge_year) + $ev_charge[0]['total_year']), 2) }}</td>
        </tr>
        <tr class="table-row-bg">
            <td colspan="100%"> Payment Summary Details Report</td>
        </tr>
        <tr>
            <td class="text-end f-semi">Hotel Revenue </td>
            <td class="td-default to-day">{{ number_format($summary_hotel_revenue_bank_date + $total_cash, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $summary_hotel_revenue_bank_month + $total_cash_month : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($summary_hotel_revenue_bank_year + $total_cash_year, 2) }}</td>
        </tr>
        <tr>
            <td class="text-end f-semi"> Water Park Revenue </td>
            <td class="td-default to-day">{{ number_format(($total_wp_revenue->wp_cash + $total_wp_revenue->wp_transfer) + $total_wp_charge, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? ($total_wp_cash_bank_month + $total_wp_charge_month) : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($total_wp_cash_bank_year + $total_wp_charge_year, 2) }}</td>
        </tr>
        <tr>
            <td class="text-end f-semi">Elexa EGAT revenue </td>
            <td class="td-default to-day">{{ number_format($total_ev_revenue, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $total_ev_month : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($total_ev_year, 2) }}</td>
        </tr>

        @php
            $summary_details_report_date = ($summary_hotel_revenue_bank_date + $total_cash) + ($total_wp_revenue->wp_cash + $total_wp_revenue->wp_transfer + $total_wp_charge) + $total_ev_revenue;
            $summary_details_report_month = ($summary_hotel_revenue_bank_month + $total_cash_month) + ($total_wp_cash_bank_month + $total_wp_charge_month) + $total_ev_month;
            $summary_details_report_year = ($summary_hotel_revenue_bank_year + $total_cash_year) + ($total_wp_cash_bank_year + $total_wp_charge_year) + $total_ev_year;
        @endphp

        <tr>
            <td class="text-end f-semi">Total Revenue</td>
            <td class="td-default to-day">{{ number_format($summary_details_report_date, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $summary_details_report_month : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($summary_details_report_year, 2) }}</td>
        </tr>
        <tr class="white-h-05em"></tr>
        <tr class="table-row-bg">
            <td class="f-semi" colspan="100%">Revenue Outstanding Report</td>
        </tr>
        <tr>
            <td class="text-end f-semi">Agoda Outstanding Balance </td>
            <td class="td-default to-day">{{ number_format($agoda_revenue_outstanding_date, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $agoda_revenue_outstanding_month : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($agoda_revenue_outstanding_year, 2) }}</td>
        </tr>
        <tr>
            <td class="text-end f-semi">Elexa EGAT Outstanding Balance</td>
            <td class="td-default to-day">{{ number_format($ev_charge[0]['total'], 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $ev_charge[0]['total_month'] : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($ev_charge[0]['total_year'] - $total_ev_year, 2) }}</td>
        </tr>

        @php
            $revenue_outstanding_report_date = $agoda_revenue_outstanding_date + $ev_charge[0]['total'];
            $revenue_outstanding_report_month = $agoda_revenue_outstanding_month + $ev_charge[0]['total_month'];
            $revenue_outstanding_report_year = $agoda_revenue_outstanding_year + ($ev_charge[0]['total_year'] - $total_ev_year);
        @endphp

        <tr>
            <td class="text-end f-semi">Total Outstanding Balance</td>
            <td class="td-default to-day">{{ number_format($revenue_outstanding_report_date, 2) }}</td>
            <td class="td-default m-t-d">{{ number_format($filter_by != "year" ? $revenue_outstanding_report_month : 0, 2) }}</td>
            <td class="td-default y-t-d">{{ number_format($revenue_outstanding_report_year, 2) }}</td>
        </tr>
    </tbody>
</table>