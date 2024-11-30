<table>
    <thead>
        <tr>
            <th style="text-align: center;">
                <img src="image/Logo-tg2.png" alt="logo of Together Resort" width="80"/>
            </th>
            <th colspan="4">
                <span><b>Together Resort Kaengkrachan</b></span> <br>
                Hotel and water park revenue <br>
                Date On : {{ $search_date ?? '' }}
            </th>
        </tr>
        <tr></tr>
        <tr>
            <th style="text-align: center;"><b>#</b></th>
            <th style="text-align: center;"><b>Date</b></th>
            <th style="text-align: center;"><b>Manual Charge</b></th>
            <th style="text-align: center;"><b>Fee</b></th>
            <th style="text-align: center;"><b>SMS Revenue</b></th>
        </tr>
    </thead>
    <tbody>
        @php
            $total_manual = 0;
            $total_fee = 0;
            $total_sms = 0;
        @endphp

        @if (isset($status) && $status == 'not_complete')
            @foreach ($data_query as $key => $item)
                @if ($item->manual_charge == 0 || $item->total_credit == 0)
                    <tr>
                        <td style="text-align: center;">{{ $key + 1 }}</td>
                        <td style="text-align: left;">{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                        <td style="text-align: right;">{{ $item->manual_charge == 0 ? "-" : number_format($item->manual_charge, 2) }}</td>
                        <td style="text-align: right;">{{ $item->fee == 0 || $item->manual_charge == 0 ? "-" : number_format($item->fee, 2) }}</td>
                        <td style="text-align: right;">{{ $item->total_credit == 0 ? "-" : number_format($item->total_credit, 2) }}</td>
                    </tr>

                    @php
                        $total_manual += $item->manual_charge;
                        $total_fee += $item->fee == 0 || $item->manual_charge == 0 ? 0 : $item->fee;
                        $total_sms += $item->total_credit;
                    @endphp
                @endif
            @endforeach
        @else
            @foreach ($data_query as $key => $item)
                <tr>
                    <td style="text-align: center;">{{ $key + 1 }}</td>
                    <td style="text-align: left;">{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                    <td style="text-align: right;">{{ $item->manual_charge == 0 ? "-" : number_format($item->manual_charge, 2) }}</td>
                    <td style="text-align: right;">{{ $item->fee == 0 ? "-" : number_format($item->fee, 2) }}</td>
                    <td style="text-align: right;">{{ $item->total_credit == 0 ? "-" : number_format($item->total_credit, 2) }}</td>
                </tr>

                @php
                    $total_manual += $item->manual_charge;
                    $total_fee += $item->fee;
                    $total_sms += $item->total_credit;
                @endphp
            @endforeach
        @endif
        <tr>
            <td colspan="2" style="text-align: right;"><b>Total</b></td>
            <td style="text-align: right;"><b>{{ number_format($total_manual, 2) }}</b></td>
            <td style="text-align: right;"><b>{{ number_format($total_fee, 2) }}</b></td>
            <td style="text-align: right;"><b>{{ number_format($total_sms, 2) }}</b></td>
        </tr>
    </tbody>
</table>