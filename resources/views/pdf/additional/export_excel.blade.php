<table>
    <thead>
        <tr>
            <th style="text-align: center;">
                <img src="image/Logo-tg2.png" alt="logo of Together Resort" width="80"/>
            </th>
            <th colspan="4">
                <span><b>Together Resort Kaengkrachan</b></span> <br>
                Additional Charge <br>
                Date On : {{ $search_date ?? '' }}
            </th>
        </tr>
        <tr></tr>
        <tr>
            <th style="text-align:center;">#</th>
            <th style="text-align:center;">Additional ID</th>
            <th style="text-align:center;">Proposal ID</th>
            <th style="text-align:center;">Company / Individual</th>
            <th style="text-align:center;">Check IN</th>
            <th style="text-align:center;">Check OUT</th>
            <th style="text-align:center;">Issue Date</th>
            <th style="text-align:center;">Creatd By</th>
            <th style="text-align:center;">Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data_query as $key => $item)
            <tr style="vertical-align: middle;">
                <td style="text-align:center;">{{ $key + 1 }}</td>
                <td style="text-align:center;">{{ $item->Additional_ID}}</td>
                <td style="text-align:center;">{{ $item->Quotation_ID}}</td>
                @if ($item->type_Proposal == 'Company')
                    <td style="text-align:left;">{{ @$item->company->Company_Name}}</td>
                @else
                    <td style="text-align:left;">{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                @endif
                <td  style="text-align:center;">
                    {{ $item->checkin}}
                </td>
                <td  style="text-align:center;">
                    {{ $item->checkout}}
                </td>
                <td  style="text-align:center;">{{ $item->issue_date }}</td>
                <td  style="text-align:center;">{{ @$item->userOperated->name }}</td>
                <td  style="text-align:right;">{{ number_format($item->Nettotal, 2) }}</td>
            </tr>
        @endforeach
        <tr style="font-weight: bold;">
            <td colspan="8" style="text-align: center;">Total</td>
            <td style="text-align: right;">{{ number_format($total_additional, 2) }}</td>
            <td></td>
        </tr>
    </tbody>
</table>
