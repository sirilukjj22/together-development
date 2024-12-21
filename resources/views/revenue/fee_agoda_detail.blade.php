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
                            <table id="agoda_feeTable" class="table-together table-style">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;" data-priority="1">#</th>
                                        <th style="text-align: center;" data-priority="1">Date</th>
                                        {{-- <th style="text-align: center;">Stan</th> --}}
                                        <th style="text-align: center;">Revenue Type</th>
                                        <th style="text-align: center;" data-priority="1">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_query as $key => $item)
                                        <tr style="text-align: center;">
                                            <td class="td-content-center">{{ $key + 1 }}</td>
                                            <td class="td-content-center">{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                            {{-- <td class="td-content-center">{{ $item->batch }}</td> --}}
                                            <td class="td-content-center">Agoda Revenue</td>
                                            <td style="text-align: right;" class="target-class text-end">{{ $item->fee }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="fw-bold" style="background-color: #dff8f0;">Total</td>
                                        <td class="fw-bold text-start" style="background-color: #dff8f0;">{{ number_format($total_query, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div> <!-- .card end -->
                </div>
            </div> <!-- .row end -->
        </div>
    </div>

    <!-- สำหรับค้นหาในส่วนของตาราง -->
    <script src="{{ asset('assets/js/table-together.js') }}"></script>
@endsection
