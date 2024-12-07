@extends('layouts.masterLayout')
@php
    $excludeDatatable = false;
@endphp
@section('content')
<div id="content-index" class="body-header border-bottom d-flex py-3">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col sms-header">
                <div class=""><span class="span1">Agoda</span><span class="span2"> / Agoda Revenue / {{ $title }}</span></div>
                <div class="span3">{{ $title }}</div>
            </div>
            <div class="col-auto">
                <a href="javascript:history.back(1)" class="bt-tg-normal">Back</a>
            </div>
        </div> <!-- .row end -->
    </div>
</div>

<div>
    <section class="doc my-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-4">
            <h4 class="title-top-table">Logs</h4>
        </div>
        <div class="wrap-table-together">
            <table id="agodaRevenueDayTable" class="table-together table-style">
                <thead>
                    <tr>
                        <th data-priority="2">#</th>
                        <th data-priority="4">Type</th>
                        <th data-priority="3">Created By</th>
                        <th data-priority="1">Created Date</th>
                        <th data-priority="1">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($log_agoda as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td class="fw-bold">{{ $item->type }}</td>
                            <td>{{ @$item->userCreatedBy->firstname }} {{ @$item->userCreatedBy->lastname }}</td>
                            <td>{{ Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                            <td class="text-start">{!! $item->original_attributes !!}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>

<style>
    .table-together tr th{
        text-align: center !important;
    }
</style>

<link rel="stylesheet" href="{{ asset('assets/src/revenueAgoda.css') }}" />

<!-- Custom Scripts -->
<script src="{{ asset('assets/js/table-together.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/helper/searchTableDebtorAgoda.js')}}"></script>

@endsection
