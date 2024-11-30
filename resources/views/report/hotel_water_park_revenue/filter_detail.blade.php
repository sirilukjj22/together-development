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
            @if ($filter_by == "date" && $status == "detail")
                @foreach ($data_query as $item)
                    <th>{{ date('d/m/y', strtotime($item->date)) }}</th>
                @endforeach
                <th>Total</th>
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
                    @php
                        $sum_front_cash += $item->front_cash;
                    @endphp
                @endforeach
                <td>{{ number_format($sum_front_cash, 2) }}</td>
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
                    @php
                        $sum_front_transfer += $item->front_transfer;
                    @endphp
                @endforeach
                <td>{{ number_format($sum_front_transfer, 2) }}</td>
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
                    @php
                        $sum_guest_cash += $item->room_cash;
                    @endphp
                @endforeach
                <td>{{ number_format($sum_guest_cash, 2) }}</td>
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
                    @php
                        $sum_guest_transfer += $item->room_transfer;
                    @endphp
                @endforeach
                <td>{{ number_format($sum_guest_transfer, 2) }}</td>
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
                    @php
                        $sum_all_outlet_cash += $item->fb_cash;
                    @endphp
                @endforeach
                <td>{{ number_format($sum_all_outlet_cash, 2) }}</td>
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
                    @php
                        $sum_all_outlet_transfer += $item->fb_transfer;
                    @endphp
                @endforeach
                <td>{{ number_format($sum_all_outlet_transfer, 2) }}</td>
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
                    @php
                        $sum_credit_front += $item->front_charge
                    @endphp
                @endforeach
                <td>{{ number_format($sum_credit_front, 2) }}</td>
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
                    @php
                        $sum_credit_guest += $item->guest_charge
                    @endphp
                @endforeach
                <td>{{ number_format($sum_credit_guest, 2) }}</td>
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
                    @php
                        $sum_credit_all_outlet += $item->outlet_charge
                    @endphp
                @endforeach
                <td>{{ number_format($sum_credit_all_outlet, 2) }}</td>
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
                    @php
                        $sum_credit_fee += $item->fee
                    @endphp
                @endforeach
                <td>{{ number_format($sum_credit_fee, 2) }}</td>
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
                @php
                    $sum_credit_revenue += $item->total_credit
                @endphp
            @endforeach
            <td>{{ number_format($sum_credit_revenue, 2) }}</td>
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
                    @php
                        $sum_agoda_charge += $item->agoda_charge
                    @endphp
                @endforeach
                <td>{{ number_format($sum_agoda_charge, 2) }}</td>
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
                    @php
                        $sum_agoda_fee += $item->agoda_fee
                    @endphp
                @endforeach
                <td>{{ number_format($sum_agoda_fee, 2) }}</td>
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
                    @php
                        $sum_agoda_revenue += $item->agoda_revenue
                    @endphp
                @endforeach
                <td>{{ number_format($sum_agoda_revenue, 2) }}</td>
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
                    @php
                        $sum_agoda_paid += $item->total_credit_agoda
                    @endphp
                @endforeach
                <td>{{ number_format($sum_agoda_paid, 2) }}</td>
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
                    @php
                        $sum_agoda_outstanding += $item->agoda_revenue
                    @endphp
                @endforeach
                <td>{{ number_format($sum_agoda_outstanding, 2) }}</td>
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
                    @php
                        $sum_other_revenue += $item->other_revenue
                    @endphp
                @endforeach
                <td>{{ number_format($sum_other_revenue, 2) }}</td>
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
                    @php
                        $sum_all_hotel_agoda += ($item->cash + $item->bank_transfer + $item->agoda_outstanding)
                    @endphp
                @endforeach
                <td>{{ number_format($sum_all_hotel_agoda, 2) }}</td>
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
                    @php
                        $sum_all_cash += $item->cash
                    @endphp
                @endforeach
                <td>{{ number_format($sum_all_cash, 2) }}</td>
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
                    @php
                        $sum_all_transfer += $item->bank_transfer
                    @endphp
                @endforeach
                <td>{{ number_format($sum_all_transfer, 2) }}</td>
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
                    @php
                        $sum_all_agoda_outstanding += $item->agoda_outstanding
                    @endphp
                @endforeach
                <td>{{ number_format($sum_all_agoda_outstanding, 2) }}</td>
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
                    @php
                        $sum_water_cash += $item->wp_cash
                    @endphp
                @endforeach
                <td>{{ number_format($sum_water_cash, 2) }}</td>
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
                    @php
                        $sum_water_transfer += $item->wp_transfer
                    @endphp
                @endforeach
                <td>{{ number_format($sum_water_transfer, 2) }}</td>
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
                    @php
                        $sum_water_credit_charge += $item->wp_charge
                    @endphp
                @endforeach
                <td>{{ number_format($sum_water_credit_charge, 2) }}</td>
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
                    @php
                        $sum_water_credit_fee += $item->wp_fee
                    @endphp
                @endforeach
                <td>{{ number_format($sum_water_credit_fee, 2) }}</td>
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
                    @php
                        $sum_water_credit_revenue += $item->wp_credit
                    @endphp
                @endforeach
                <td>{{ number_format($sum_water_credit_revenue, 2) }}</td>
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
                    @php
                        $sum_all_water += $item->wp_transfer
                    @endphp
                @endforeach
                <td>{{ number_format($sum_all_water, 2) }}</td>
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
                    @php
                        $sum_ev_charge += $item->ev_charge
                    @endphp
                @endforeach
                <td>{{ number_format($sum_ev_charge, 2) }}</td>
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
                    @php
                        $sum_ev_fee += $item->ev_fee
                    @endphp
                @endforeach
                <td>{{ number_format($sum_ev_fee, 2) }}</td>
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
                    @php
                        $sum_ev_revenue += $item->ev_revenue
                    @endphp
                @endforeach
                <td>{{ number_format($sum_ev_revenue, 2) }}</td>
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
                    @php
                        $sum_ev_paid += $item->total_elexa
                    @endphp
                @endforeach
                <td>{{ number_format($sum_ev_paid, 2) }}</td>
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
                    @php
                        $sum_ev_outstanding += $item->ev_revenue
                    @endphp
                @endforeach
                <td>{{ number_format($sum_ev_outstanding, 2) }}</td>
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
                <td>{{ number_format($sum_ev_outstanding, 2) }}</td>
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
                <td>{{ number_format($sum_ev_paid, 2) }}</td>
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
                <td>{{ number_format($sum_ev_outstanding, 2) }}</td>
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
                    @php
                        $sum_all_revenue += ($item->cash + $item->bank_transfer + $item->agoda_outstanding) + ($item->wp_cash + $item->wp_transfer) + $item->ev_revenue;
                    @endphp
                @endforeach
                <td>{{ number_format($sum_all_revenue, 2) }}</td>
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
                <td>{{ number_format(0, 2) }}</td>
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
                <td>{{ number_format($sum_all_revenue, 2) }}</td>
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
                    @php
                        $sum_hotel += ($item->cash + $item->bank_transfer);
                    @endphp
                @endforeach
                <td>{{ number_format($sum_hotel, 2) }}</td>
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
                    @php
                        $sum_water_park += ($item->wp_cash + $item->wp_transfer);
                    @endphp
                @endforeach
                <td>{{ number_format($sum_water_park, 2) }}</td>
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
                    @php
                        $sum_exlexa += $item->ev_revenue;
                    @endphp
                @endforeach
                <td>{{ number_format($sum_exlexa, 2) }}</td>
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
                    @php
                        $sum_total_revenue += ($item->cash + $item->bank_transfer) + ($item->wp_cash + $item->wp_transfer) + $item->ev_revenue;
                    @endphp
                @endforeach
                <td>{{ number_format($sum_total_revenue, 2) }}</td>
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
                <td>{{ number_format($sum_all_agoda_outstanding, 2) }}</td>
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
                <td>{{ number_format($sum_ev_outstanding, 2) }}</td>
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
                    @php
                        $sum_total_outstanding += ($item->agoda_revenue + $item->ev_revenue);
                    @endphp
                @endforeach
                <td>{{ number_format($sum_total_outstanding, 2) }}</td>
            @else
                <td class="td-default"></td>
                <td class="td-default"></td>
                <td class="td-default"></td>
            @endif
        </tr>
    </tbody>
</table>