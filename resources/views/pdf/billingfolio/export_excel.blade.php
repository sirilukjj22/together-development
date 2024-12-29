<table>
    <thead>
        <tr>
            <th style="text-align: center;">
                <img src="image/Logo-tg2.png" alt="logo of Together Resort" width="80"/>
            </th>
            <th colspan="4">
                <span><b>Together Resort Kaengkrachan</b></span> <br>
                Billing Folio <br>
                Date On : {{ $search_date ?? '' }}
            </th>
        </tr>
        <tr></tr>
        <tr>
            <th style="text-align:center;">#</th>
            <th style="text-align:center;">Receipt ID</th>
            <th style="text-align:center;">Proposal ID</th>
            <th style="text-align:center;">Company / Individual</th>
            <th style="text-align:center;">Payment Date</th>
            <th style="text-align:center;">Creatd By</th>
            <th style="text-align:center;">Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data_query as $key => $item)
            <tr style="vertical-align: middle;">
                <td style="text-align:center;">{{ $key + 1 }}</td>
                <td style="text-align:center;">{{ $item->Receipt_ID}}</td>
                <td style="text-align:center;">{{ $item->Quotation_ID}}</td>
                <td  style="text-align:left;">{{ $item->fullname }}</td>
                <td  style="text-align:center;">{{ $item->paymentDate }}</td>
                <td  style="text-align:center;">{{ @$item->userOperated->name }}</td>
                <td  style="text-align:right;">{{ number_format($item->document_amount, 2) }}</td>
            </tr>
        @endforeach
        <tr style="font-weight: bold;">
            <td colspan="6" style="text-align: center;">Total</td>
            <td style="text-align: right;">{{ number_format($total_receipt, 2) }}</td>
            <td></td>
        </tr>
    </tbody>
</table>
