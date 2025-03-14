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
            <th style="text-align: center;">#</th>
            <th style="text-align: center;">วันที่ทำรายการ</th>
            <th style="text-align: center;">Booking number</th>
            <th style="text-align: center;">Check in date</th>
            <th style="text-align: center;">Check out date</th>
            <th style="text-align: center;">amount</th>
            <th style="text-align: center;">status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data_query as $key => $item)
            <tr style="vertical-align: middle;">
                <td style="text-align: center;">{{ $key + 1 }}</td>
                <td style="text-align: center;">{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                <td style="text-align: center; align-items: center;">{{ $item->batch }}</td>
                <td style="text-align: center;">{{ Carbon\Carbon::parse($item->agoda_check_in)->format('d/m/Y') }}</td>
                <td style="text-align: center;">{{ Carbon\Carbon::parse($item->agoda_check_out)->format('d/m/Y') }}</td>
                <td style="text-align: right;">{{ number_format($item->agoda_outstanding, 2) }}</td>
                <td style="text-align: center;">unpaid</td>
            </tr>
        @endforeach
        <tr style="font-weight: bold;">
            <td colspan="5" style="text-align: center;">Total</td>
            <td style="text-align: right;">{{ number_format($total_agoda_amount, 2) }}</td>
            <td></td>
        </tr>
    </tbody>
</table>