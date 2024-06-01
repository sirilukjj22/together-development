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
                        <th>ประเภทรายได้</th>
                        <th>จำนวนเงิน</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    @foreach ($ev_outstanding as $key => $item)
                    <tr style="font-weight: bold; color: black;">
                        <td>{{ $key + 1 }}</td>
                        <td>
                            @if ($item->revenue_type == 8)
                                Elexa EGAT Revenue
                            @endif
                        </td>
                        <td>{{ number_format($item->agoda_outstanding, 2) }}</td>
                    </tr>
                    <?php $total += $item->agoda_outstanding; ?>
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
