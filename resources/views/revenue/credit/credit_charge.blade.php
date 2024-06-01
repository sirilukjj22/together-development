@extends('layouts.test')

@section('content')
    <div class="page_target">
        <a href="javascript:history.back(1)" style="color: #2D7F7B; font-size: 20px; font-weight: 500;">Revenue</a> /
        {{ $title ?? '' }} <br>
        <h1>{{ $title ?? '' }}</h1>
    </div>
    <div class="back">
        <a href="javascript:history.back(1)"><button type="button">ย้อนกลับ</button></a>
    </div>

    <div class="search">
        <div>
            <table id="example" class="display">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>วันที่</th>
                        <th>ประเภทรายได้</th>
                        <th>จำนวนเงิน</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    @foreach ($credit_charge as $key => $item)
                        <tr style="font-weight: bold; color: black;">
                            <td>{{ $key + 1 }}</td>
                            <td>{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                            <td>
                                @if ($item->revenue_type == 1)
                                    Guest Deposit Revenue
                                @elseif($item->revenue_type == 2)
                                    All Outlet Revenue
                                @elseif($item->revenue_type == 3)
                                    Water Park Revenue
                                @elseif($item->revenue_type == 4)
                                    Credit Card Revenue
                                @elseif($item->revenue_type == 5)
                                    Credit Card Agoda Revenue
                                @elseif($item->revenue_type == 6)
                                    Front Desk Revenue
                                @elseif($item->revenue_type == 7)
                                    Credit Card Water Park Revenue
                                @endif
                            </td>
                            <td>{{ number_format($item->credit_amount, 2) }}</td>
                        </tr>
                        <?php $total += $item->credit_amount; ?>
                    @endforeach
                </tbody>
                <div class="totalrevenue">
                    ยอดรวมทั้งหมด {{ number_format($total, 2) }} บาท
                </div>
            </table>
        </div>
    </div>

    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>

    <link rel="stylesheet" href="dataTables.dataTables.css">

    <script>
        $(document).ready(function() {
            new DataTable('#example', {
                //ajax: 'arrays.txt'
            });
        });
    </script>
@endsection
