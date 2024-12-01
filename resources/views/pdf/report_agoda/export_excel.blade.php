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
            <th style="text-align: center;">Date</th>
            <th style="text-align: center;">Time</th>
            <th style="text-align: center;" colspan="2">Bank</th>
            <th style="text-align: center;" colspan="2">Bank Account</th>
            <th style="text-align: center;">Amount</th>
            <th style="text-align: center;">Creatd By</th>
            <th style="text-align: center;">Income Type</th>
            <th style="text-align: center;">Transfer Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data_query as $key => $item)
            <tr style="vertical-align: middle;">
                <td class="td-content-center;">{{ $key + 1 }}</td>
                <td class="td-content-center">{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                <td style="text-align: left;">{{ Carbon\Carbon::parse($item->date)->format('H:i:s') }}</td>
                <td style="text-align: left; align-items: center;">
                    <?php
                        $filename = base_path() . '/public/image/bank/' . @$item->transfer_bank->name_en . '.jpg';
                        $filename2 = base_path() . '/public/image/bank/' . @$item->transfer_bank->name_en . '.png';
                    ?>
                        @if (file_exists($filename))
                            <img class="img-bank" src="image/bank/{{ @$item->transfer_bank->name_en }}.jpg" width="30" style="padding-top: 15px;">
                        @elseif (file_exists($filename2))
                            <img class="img-bank" src="image/bank/{{ @$item->transfer_bank->name_en }}.png" width="30">
                        @endif
                </td>
                <td>{{ @$item->transfer_bank->name_en ?? 'SCB'  }}</td>
                <td style="text-align: left; align-items: center;">
                    <img class="img-bank" src="image/bank/SCB.jpg" width="30" style="padding-top: 15px;"> 
                </td>
                <td>{{ 'SCB ' . $item->into_account }}</td>
                <td>
                    {{ number_format($item->amount, 2) }}
                </td>
                <td style="text-align: center;">{{ $item->remark ?? 'Auto' }}</td>
                <td style="text-align: left;">Agoda Bank Transfer Revenue</td>
                <td style="text-align: center;">
                    {{ $item->date_into != '' ? Carbon\Carbon::parse($item->date_into)->format('d/m/Y') : '-' }}
                </td>
            </tr>
        @endforeach
        <tr style="font-weight: bold;">
            <td colspan="5">Total</td>
            <td style="text-align: right;">{{ number_format($total_sms_amount, 2) }}</td>
            <td colspan="3"></td>
        </tr>
    </tbody>
</table>