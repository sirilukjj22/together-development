@extends('layouts.masterLayout')
@php
    $excludeDatatable = false;
@endphp
@section('content')
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class=""><span class="span1">Hotel & Water Park Revenue </span><span class="span2"> / {{ $title }}</span></div>
                    <div class="span3">{{ $title }}</div>
                </div>
                <div class="col-auto">
                    <a href="javascript:history.back(1)" type="button" class="btn btn-color-green text-white lift">Back</a>
                </div>
            </div> <!-- .row end -->
        </div>
    </div>
    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <div class="card p-4 mb-4">
                        <div style="min-height: 70vh;">
                            <table id="revenueTable" class="table-together table-style">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;" data-priority="1">#</th>
                                        <th style="text-align: center;" data-priority="1">Date</th>
                                        <th style="text-align: center;">Time</th>
                                        <th style="text-align: center;">Bank</th>
                                        <th style="text-align: center;">Bank Account</th>
                                        <th style="text-align: center;" data-priority="1">Amount</th>
                                        <th style="text-align: center;">Creatd By</th>
                                        <th style="text-align: center;">Income Type</th>
                                        <th style="text-align: center;">Transfer Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_query as $key => $item)
                                        <tr style="text-align: center;">
                                            <td class="td-content-center">{{ $key + 1 }}</td>
                                            <td class="td-content-center">{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                            <td class="td-content-center">{{ Carbon\Carbon::parse($item->date)->format('H:i:s') }}</td>
                                            <td class="td-content-center text-start">
                                                <?php
                                                $filename = base_path() . '/public/image/bank/' . @$item->transfer_bank->name_en . '.jpg';
                                                $filename2 = base_path() . '/public/image/bank/' . @$item->transfer_bank->name_en . '.png';
                                                ?>
                                                <div class="flex-jc p-left-4">
                                                    @if (file_exists($filename))
                                                        <img  src="../../../image/bank/{{ @$item->transfer_bank->name_en }}.jpg" alt="" class="img-bank" />
                                                    @elseif (file_exists($filename2))
                                                        <img  src="../../../image/bank/{{ @$item->transfer_bank->name_en }}.png" alt="" class="img-bank" />
                                                    @endif
                                                    {{ @$item->transfer_bank->name_en }}
                                                </div>
                                            </td>
                                            <td class="td-content-center">
                                                <div class="flex-jc p-left-4 center">
                                                    <img  src="../../../image/bank/SCB.jpg" alt="" class="img-bank" />{{ 'SCB ' . $item->into_account }}
                                                </div>
                                            </td>
                                            <td class="td-content-center target-class text-end">
                                                {{ $item->amount_before_split > 0 ? $item->amount_before_split : $item->amount }}
                                            </td>
                                            <td class="td-content-center">{{ $item->remark ?? 'Auto' }}</td>
                                            <td class="td-content-center">
                                                @if ($item->status == 0)
                                                                -
                                                @elseif ($item->status == 1)
                                                    Guest Deposit Revenue
                                                @elseif($item->status == 2)
                                                    All Outlet Revenue
                                                @elseif($item->status == 3)
                                                    Water Park Revenue
                                                @elseif($item->status == 4)
                                                    Credit Card Revenue
                                                @elseif($item->status == 5)
                                                    Agoda Bank Transfer Revenue
                                                @elseif($item->status == 6)
                                                    Front Desk Revenue
                                                @elseif($item->status == 7)
                                                    Credit Card Water Park Revenue
                                                @elseif($item->status == 8)
                                                    Elexa EGAT Revenue
                                                @elseif($item->status == 9)
                                                    Other Revenue Bank Transfer
                                                @endif
                                            </td>

                                            <td class="td-content-center">
                                                {{ $item->date_into != '' ? Carbon\Carbon::parse($item->date_into)->format('d/m/Y') : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="fw-bold" style="background-color: #dff8f0;">Total</td>
                                        <td colspan="4" class="fw-bold text-start" style="background-color: #dff8f0;">{{ number_format($total_query, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div> <!-- .card end -->
                </div>
            </div> <!-- .row end -->
        </div>
    </div>

    @if (isset($_SERVER['HTTPS']) ? 'https' : 'http' == 'https')
        {{-- <script src="https://code.jquery.com/jquery-1.10.2.js"></script> --}}
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @else
        {{-- <script src="http://code.jquery.com/jquery-1.10.2.js"></script> --}}
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @endif

    <!-- สำหรับค้นหาในส่วนของตาราง -->
    <script src="{{ asset('assets/js/table-together.js') }}"></script>

@endsection
