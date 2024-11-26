
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
            <th style="text-align: center; width: 250px;" colspan="2"><b>Description</b></th>

            @if ($filter_by == "date")
                <th style="text-align: center;"><b>{{ $filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] ? "Today" : $search_date }}</b></th>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <th style="text-align: center;"><b>M-T-D</b></th>
                @endif
                <th style="text-align: center;"><b>Y-T-D</b></th>
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
                <td class="td-default to-day" style="text-align: right;">{{ number_format(isset($total_front_revenue) ? $total_front_revenue->front_cash : 0, 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format(isset($total_front_month) && $filter_by != "year" ? $total_front_month->front_cash : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format(isset($total_front_year) ? $total_front_year->front_cash : 0, 2) }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="2">Bank Transfer</td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format(isset($total_front_revenue) ? $total_front_revenue->front_transfer : 0, 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format(isset($total_front_month) && $filter_by != "year" ? $total_front_month->front_transfer : 0, 2) }}</td>
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
                <td class="td-default to-day" style="text-align: right;">{{ number_format(isset($total_guest_deposit) ? $total_guest_deposit->room_cash : 0, 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format(isset($total_guest_deposit_month) && $filter_by != "year" ? $total_guest_deposit_month->room_cash : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format(isset($total_guest_deposit_year) ? $total_guest_deposit_year->room_cash : 0, 2) }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="2">Bank Transfer</td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format(isset($total_guest_deposit) ? $total_guest_deposit->room_transfer : 0, 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format(isset($total_guest_deposit_month) && $filter_by != "year" ? $total_guest_deposit_month->room_transfer : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format(isset($total_guest_deposit_year) ? $total_guest_deposit_year->room_transfer : 0, 2) }}</td>
            @endif
        </tr>
        <tr style="text-align: left; background-color: rgb(187, 226, 226);">
            <td colspan="{{ $numCol }}"><b>All Outlet Revenue</b></td>
        </tr>
        <tr>
            <td colspan="2">Cash</td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format(isset($total_fb_revenue) ? $total_fb_revenue->fb_cash : 0, 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $total_fb_month->fb_cash : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($total_fb_year->fb_cash ?? 0, 2) }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="2">Bank Transfer</td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format(isset($total_fb_revenue) ? $total_fb_revenue->fb_transfer : 0, 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $total_fb_month->fb_transfer : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($total_fb_year->fb_transfer ?? 0, 2) }}</td>
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
                <td class="td-default to-day" style="text-align: right;">{{ number_format($front_charge[0]['revenue_credit_date'], 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $front_charge[0]['revenue_credit_month'] : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($front_charge[0]['revenue_credit_year'], 2) }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="2">credit card guest deposit charge</td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format($guest_deposit_charge[0]['revenue_credit_date'], 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $guest_deposit_charge[0]['revenue_credit_month'] : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($guest_deposit_charge[0]['revenue_credit_year'], 2) }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="2">credit card all outlet charge</td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format($fb_charge[0]['revenue_credit_date'], 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $fb_charge[0]['revenue_credit_month'] : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($fb_charge[0]['revenue_credit_year'], 2) }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="2">credit card fee</td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format($total_credit_card_revenue == 0 || $credit_revenue->total_credit == 0 ? 0 : $total_credit_card_revenue - $credit_revenue->total_credit ?? 0, 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">
                        @if ($total_credit_card_revenue_month == 0 || $credit_revenue_month->total_credit == 0)
                            0.00
                        @else
                            {{ number_format($filter_by != "year" ? ($total_credit_card_revenue_month - $credit_revenue_month->total_credit ?? 0) : 0, 2) }}
                        @endif
                    </td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format(($total_credit_card_revenue_year - $credit_revenue_year->total_credit ?? 0), 2) }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="2">credit card revenue (bank transfer)</td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format($credit_revenue->total_credit, 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? ($credit_revenue_month->total_credit ?? 0) : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format(($credit_revenue_year->total_credit ?? 0), 2) }}</td>
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
                <td class="td-default to-day" style="text-align: right;">{{ number_format($agoda_charge[0]['revenue_credit_date'], 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $agoda_charge[0]['revenue_credit_month'] : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($agoda_charge[0]['revenue_credit_year'], 2) }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="2">Agoda Fee </td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format($agoda_charge[0]['fee_date'], 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $agoda_charge[0]['fee_month'] : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($agoda_charge[0]['fee_year'], 2) }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="2">Agoda Revenue</td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format($agoda_charge[0]['total'], 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $agoda_charge[0]['total_month'] : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($agoda_charge[0]['total_year'], 2) }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="2">Agoda Paid (bank transfer)</td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format($total_agoda_revenue, 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $total_agoda_month : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($total_agoda_year, 2) }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="2">Agoda Revenue Outstanding </td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format($agoda_revenue_outstanding_date, 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $agoda_revenue_outstanding_month : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($agoda_revenue_outstanding_year, 2) }}</td>
            @endif
        </tr>
        <tr style="text-align: left; background-color: rgb(187, 226, 226);">
            <td colspan="{{ $numCol }}"><b>Other Revenue</b></td>
        </tr>
        <tr>
            <td colspan="2">Bank Transfer</td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format($total_other_revenue, 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $total_other_month : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($total_other_year, 2) }}</td>
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
                <td class="td-default to-day" style="text-align: right;"><b>{{ number_format($summary_hotel_revenue_bank_date + $total_cash + $agoda_revenue_outstanding_date, 2) }}</b></td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;"><b>{{ number_format($filter_by != "year" ? $summary_hotel_revenue_bank_month + $total_cash_month + $agoda_revenue_outstanding_month : 0, 2) }}</b></td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;"><b>{{ number_format($summary_hotel_revenue_bank_year + $total_cash_year + $agoda_revenue_outstanding_year, 2) }}</b></td>
            @endif
        </tr>
        <tr>
            <td colspan="2">Cash</td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format($total_cash, 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $total_cash_month : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($total_cash_year, 2) }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="2">Bank Transfer</td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format($summary_hotel_revenue_bank_date, 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $summary_hotel_revenue_bank_month : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($summary_hotel_revenue_bank_year, 2) }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="2">Agoda Revenue Outstanding Balance</td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format($agoda_revenue_outstanding_date, 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $agoda_revenue_outstanding_month : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($agoda_revenue_outstanding_year, 2) }}</td>
            @endif
        </tr>
        <tr style="text-align: left; background-color: rgb(187, 226, 226);">
            <td colspan="{{ $numCol }}"><b>Water Park Revenue</b></td>
        </tr>
        <tr>
            <td colspan="2">Cash</td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format(isset($total_wp_revenue) ? $total_wp_revenue->wp_cash : 0, 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $total_wp_month->wp_cash ?? 0 : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($total_wp_year->wp_cash ?? 0, 2) }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="2">Bank Transfer</td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format(isset($total_wp_revenue) ? $total_wp_revenue->wp_transfer : 0, 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $total_wp_month->wp_transfer : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($total_wp_year->wp_transfer, 2) }}</td>
            @endif
        </tr>
        <tr style="text-align: left; background-color: rgb(187, 226, 226);">
            <td colspan="{{ $numCol }}"><b>Water Park Credit Card Revenue</b></td>
        </tr>
        <tr>
            <td colspan="2">Credit Card Water Park Charge </td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format($total_wp_credit_card_revenue, 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $total_wp_credit_card_revenue_month : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($total_wp_credit_card_revenue_year, 2) }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="2">Credit Card Fee</td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format($wp_charge[0]['fee_date'], 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $wp_charge[0]['fee_month'] : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($wp_charge[0]['fee_year'], 2) }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="2">Credit Card Water Park Revenue (Bank Transfer)</td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format($total_wp_charge, 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $total_wp_charge_month : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($total_wp_charge_year, 2) }}</td>
            @endif
        </tr>
        <tr style="text-align: left; background-color: rgb(187, 226, 226);">
            <td colspan="2"><b>Summary Water Park Revenue</b></td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;"><b>{{ number_format(($total_wp_revenue->wp_cash + $total_wp_revenue->wp_transfer) + $total_wp_charge, 2) }}</b></td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;"><b>{{ number_format($filter_by != "year" ? ($total_wp_cash_bank_month + $total_wp_charge_month) : 0, 2) }}</b></td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;"><b>{{ number_format($total_wp_cash_bank_year + $total_wp_charge_year, 2) }}</b></td>
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
                <td class="td-default to-day" style="text-align: right;">{{ number_format($ev_charge[0]['revenue_credit_date'], 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $ev_charge[0]['revenue_credit_month'] : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($ev_charge[0]['revenue_credit_year'], 2) }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="2">Elexa Fee</td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format($ev_charge[0]['fee_date'], 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $ev_charge[0]['fee_month'] : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($ev_charge[0]['fee_year'], 2) }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="2">Elexa EGAT revenue</td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format($ev_charge[0]['total'], 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $ev_charge[0]['total_month'] : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($ev_charge[0]['total_year'], 2) }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="2">Elexa EGAT Paid (Bank Transfer)</td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format($total_ev_revenue, 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $total_ev_month : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($total_ev_year, 2) }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="2">Elexa EGAT Outstanding Balance</td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format($ev_charge[0]['total'], 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $ev_charge[0]['total_month'] : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($ev_charge[0]['total_year'] - $total_ev_year, 2) }}</td>
            @endif
        </tr>
        <tr style="text-align: left; background-color: rgb(187, 226, 226);">
            <td colspan="2"><b>Summary Elexa EGAT Revenue</b></td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;"><b>{{ number_format($ev_charge[0]['total'] + $total_ev_revenue, 2) }}</b></td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;"><b>{{ number_format($filter_by != "year" ? ($ev_charge[0]['total_month'] + $total_ev_month) : 0, 2) }}</b></td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;"><b>{{ number_format($ev_charge[0]['total_year'], 2) }}</b></td>
            @endif
        </tr>
        <tr>
            <td colspan="2">Bank Transfer</td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format($total_ev_revenue, 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $total_ev_month : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($total_ev_year, 2) }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="2">Elexa EGAT Outstanding Balance</td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format($ev_charge[0]['total'], 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $ev_charge[0]['total_month'] : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($ev_charge[0]['total_year'] - $total_ev_year, 2) }}</td>
            @endif
        </tr>
        <tr style="text-align: left; background-color: rgb(187, 226, 226);">
            <td colspan="{{ $numCol }}"><b>Summary Revenue</b></td>
        </tr>
        <tr>
            <td colspan="2"> All Revenue </td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format(($summary_hotel_revenue_bank_date + $total_cash + $agoda_revenue_outstanding_date) + ($total_wp_cash_bank + $total_wp_charge) + ($ev_charge[0]['total'] + $total_ev_revenue), 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? (($summary_hotel_revenue_bank_month + $total_cash_month + $agoda_revenue_outstanding_month) + ($total_wp_cash_bank_month + $total_wp_charge_month) + ($ev_charge[0]['total_month'] + $total_ev_month)) : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format(($summary_hotel_revenue_bank_year + $total_cash_year + $agoda_revenue_outstanding_year) + ($total_wp_cash_bank_year + $total_wp_charge_year) + $ev_charge[0]['total_year'], 2) }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="2">Outstanding Balance From Last Year</td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">0.00</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">0.00</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($agoda_outstanding_last_year + $elexa_outstanding_last_year, 2) }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="2">Total Revenue and Outstanding Balance From Last Year</td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format(($total_cash_bank + $total_charge) + ($total_wp_cash_bank + $total_wp_charge) + ($agoda_charge[0]['total'] + ($ev_charge[0]['total'] + $total_ev_revenue)) + $total_agoda_revenue, 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? (($summary_hotel_revenue_bank_month + $total_cash_month + $agoda_revenue_outstanding_month) + ($total_wp_cash_bank_month + $total_wp_charge_month) + ($ev_charge[0]['total_month'] + $total_ev_month)) : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format((($summary_hotel_revenue_bank_year + $total_cash_year + $agoda_revenue_outstanding_year) + ($total_wp_cash_bank_year + $total_wp_charge_year) + $ev_charge[0]['total_year']), 2) }}</td>
            @endif
        </tr>
        <tr style="text-align: left; background-color: rgb(187, 226, 226);">
            <td colspan="{{ $numCol }}"><b>Payment Summary Details Report</b></td>
        </tr>
        <tr>
            <td colspan="2">Hotel Revenue </td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format($summary_hotel_revenue_bank_date + $total_cash, 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $summary_hotel_revenue_bank_month + $total_cash_month : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($summary_hotel_revenue_bank_year + $total_cash_year, 2) }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="2"> Water Park Revenue </td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format(($total_wp_revenue->wp_cash + $total_wp_revenue->wp_transfer) + $total_wp_charge, 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? ($total_wp_cash_bank_month + $total_wp_charge_month) : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($total_wp_cash_bank_year + $total_wp_charge_year, 2) }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="2">Elexa EGAT revenue </td>

            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format($total_ev_revenue, 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $total_ev_month : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($total_ev_year, 2) }}</td>
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
                <td class="td-default to-day" style="text-align: right;">{{ number_format($summary_details_report_date, 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $summary_details_report_month : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($summary_details_report_year, 2) }}</td>
            @endif
        </tr>
        <tr style="text-align: left; background-color: rgb(187, 226, 226);">
            <td colspan="{{ $numCol }}"><b>Revenue Outstanding Report</b></td>
        </tr>
        <tr>
            <td colspan="2">Agoda Outstanding Balance </td>
            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format($agoda_revenue_outstanding_date, 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $agoda_revenue_outstanding_month : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($agoda_revenue_outstanding_year, 2) }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="2">Elexa EGAT Outstanding Balance</td>
            @if ($filter_by == "date")
                <td class="td-default to-day" style="text-align: right;">{{ number_format($ev_charge[0]['total'], 2) }}</td>
            @endif

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $ev_charge[0]['total_month'] : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($ev_charge[0]['total_year'] - $total_ev_year, 2) }}</td>
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
                <td class="td-default to-day" style="text-align: right;">{{ number_format($revenue_outstanding_report_date, 2) }}</td>
            @endif  

            @if ($filter_by == "date" && $exp_dateRang[0] == $exp_dateRang[1] || $filter_by == "month" || $filter_by == "year")
                @if ($filter_by == "month" || $filter_by == "date")
                    <td class="td-default m-t-d" style="text-align: right;">{{ number_format($filter_by != "year" ? $revenue_outstanding_report_month : 0, 2) }}</td>
                @endif
                <td class="td-default y-t-d" style="text-align: right;">{{ number_format($revenue_outstanding_report_year, 2) }}</td>
            @endif
        </tr>
    </tbody>
</table>