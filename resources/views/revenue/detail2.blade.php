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
                        <th>เวลา</th>
                        <th>โอนจากบัญชี</th>
                        <th>เข้าบัญชี</th>
                        <th>จำนวนเงิน</th>
                        <th>ผู้ทำรายการ</th>
                        <th>ประเภทรายได้</th>
                        <th>วันที่โอนย้าย</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    @foreach ($data_sms as $key => $item)
                        <tr style="font-weight: bold; color: black;">
                            <td>{{ $key + 1 }}</td>
                            <td>{{ Carbon\Carbon::parse($item->status == 4 || $item->date_into != '' ? $item->date_into : $item->date)->format('d/m/Y') }}
                            </td>
                            <td>{{ Carbon\Carbon::parse($item->status == 4 || $item->date_into != '' ? $item->date_into : $item->date)->format('H:i:s') }}
                            </td>
                            <td>
                                <?php
                                $filename = base_path() . '/public/image/bank/' . @$item->transfer_bank->name_en . '.jpg';
                                $filename2 = base_path() . '/public/image/bank/' . @$item->transfer_bank->name_en . '.png';
                                ?>

                                @if (file_exists($filename))
                                    <img class="rounded-circle avatar"
                                        src="../image/bank/{{ @$item->transfer_bank->name_en }}.jpg" alt="avatar"
                                        title="">
                                @elseif (file_exists($filename2))
                                    <img class="rounded-circle avatar"
                                        src="../image/bank/{{ @$item->transfer_bank->name_en }}.png" alt="avatar"
                                        title="">
                                @endif
                                {{ @$item->transfer_bank->name_en }}
                            </td>
                            <td>
                                <img class="rounded-circle avatar" src="../image/bank/SCB.jpg" alt="avatar"
                                    title="">
                                <span style="color: black;">{{ 'SCB ' . $item->into_account }}</span>
                            </td>
                            <td>{{ number_format($item->amount, 2) }}</td>
                            <td>{{ $item->remark ?? 'Auto' }}</td>
                            <td>
                                @if ($item->status == 1)
                                    Guest Deposit Revenue
                                @elseif($item->status == 2)
                                    F&B Revenue
                                @elseif($item->status == 3)
                                    Water Park Revenue
                                @elseif($item->status == 4)
                                    Credit Card Revenue
                                @elseif($item->status == 5)
                                    Credit Card Agoda Revenue
                                @elseif($item->status == 6)
                                    Front Desk Revenue
                                @elseif($item->status == 7)
                                    Credit Card Water Park Revenue
                                @endif
                            </td>
                            <td>{{ $item->date_into != '' ? Carbon\Carbon::parse($item->date)->format('d/m/Y') : '' }}</td>
                        </tr>
                        <?php $total += $item->amount; ?>
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
