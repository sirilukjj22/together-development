@extends('layouts.test')



@section('content')
    <div style="width: 100%; float: left;">
        <div class="page_target">
            <a style="color: #2D7F7B; font-size: 20px; font-weight: 500;" href="javascript:history.back(1)">SMS Alert</a> /
            Agoda bank Transfer Revenue <br>
            <h1>Agoda bank Transfer Revenue</h1>
        </div>

        <div class="back">
            <a href="javascript:history.back(1)"><button type="button">ย้อนกลับ</button></a>
        </div>
    </div>

    <div style="width: 98%;">
        <div class="search" style="width: 49%; margin-right: 10px; float: left;">
            <h1>Revenue</h1>
            <div>
                <table id="example4" class="display4">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>วันที่</th>
                            <th>จำนวนเงิน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total = 0; ?>
                        @foreach ($sum_revenue as $key => $item)
                            <tr class="my-row">
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->date == '' ? '' : Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
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

        <div class="search" style="width: 49%; margin-left: 10px; float: left;">
            <h1>SMS Revenue</h1>
            <div>
                <table id="example3" class="display">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>วันที่</th>
                            <th>เวลา</th>
                            <th>โอนจากบัญชี</th>
                            <th>เข้าบัญชี</th>
                            <th>จำนวนเงิน</th>
                            <th>คำสั่ง</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total_sms = 0; ?>

                        @foreach ($data_sms as $key => $item)
                        <tr style="font-weight: bold; color: black;">
                            <td>{{ $key + 1 }}</td>
                            <td>{{ Carbon\Carbon::parse($item->status == 4 || $item->date_into != "" ? $item->date_into : $item->date)->format('d/m/Y') }}</td>
                            <td>{{ Carbon\Carbon::parse($item->status == 4 || $item->date_into != "" ? $item->date_into : $item->date)->format('H:i:s') }}</td>
                            <td>
                                <?php 

                                    $filename = base_path()."/public/image/bank/".@$item->transfer_bank->name_en.".jpg";

                                    $filename2 = base_path()."/public/image/bank/".@$item->transfer_bank->name_en.".png";

                                ?>
                                @if (file_exists($filename)) 
                                    <img class="rounded-circle avatar" src="../../../image/bank/{{ @$item->transfer_bank->name_en }}.jpg" alt="avatar" title="">
                                @elseif (file_exists($filename2)) 
                                    <img class="rounded-circle avatar" src="../../../image/bank/{{ @$item->transfer_bank->name_en }}.png" alt="avatar" title="">
                                @endif
                                {{ @$item->transfer_bank->name_en }}
                            </td>
                            <td>
                                <img class="rounded-circle avatar" src="../../../image/bank/SCB.jpg" alt="avatar" title="">
                                <span style="color: black;">{{ "SCB ".$item->into_account }}</span>
                            </td>
                            <td>{{ number_format($item->amount, 2) }}</td>
                            <td class="text-center">
                                <a href="{{ route('sms-agoda-receive-payment', $item->id) }}" class="btn btn-primary rounded-pill" type="button">รับชำระ</a>
                            </td>
                        </tr>
                        <?php $total_sms += $item->amount; ?>
                        @endforeach
                    </tbody>
                    <div class="totalrevenue">
                        ยอดรวมทั้งหมด {{ number_format($total_sms, 2) }} บาท 
                    </div>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            new DataTable('#example3', {});

            new DataTable('#example4', {});
        });
    </script>
@endsection
