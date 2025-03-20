@extends('layouts.masterLayoutHarmony')
@php
    $excludeDatatable = false;
@endphp
@section('content')
<div id="content-index" class="body-header border-bottom d-flex py-3">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col sms-header">
                <div class=""><span class="span1">Elexa EGAT</span><span class="span2"> / Elexa EGAT Revenue / {{ $title }}</span></div>
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
                        <th data-priority="1" style="width: 70px;">#</th>
                        <th data-priority="1" style="width: 70px;">Type</th>
                        <th data-priority="3" style="width: 150px;">Created By</th>
                        <th data-priority="2" style="width: 150px;">Created Date</th>
                        <th data-priority="4">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($log_elexa as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td class="fw-bold">{{ $item->type }}</td>
                            <td>{{ @$item->userCreatedBy->firstname }} {{ @$item->userCreatedBy->lastname }}</td>
                            <td>{{ Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s') }}</td>
                            <td class="text-start">
                                {{-- <div class="d-grid"> --}}
                                    @if ($item->type == "Add")
                                        {!! $item->original_attributes !!}
                                    @else
                                        {!! $item->changed_attributes !!}
                                    @endif
                                {{-- </div> --}}
                            </td>
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

<style>
    .content-in-log {
      display: grid;
      grid-template-columns: 1fr;
    }
    .content-in-log > div:nth-child(1) {
      display: flex;
      flex-direction: column;
      align-items: start;
    }

    .content-in-log > div:nth-child(1) span b {
      font-weight: 550;
    }

    .content-in-log > div:nth-child(1) > b:nth-child(4) {
      color: #3da38d;
      margin-top: 7px;
      margin-bottom: 3px;
    }

    .content-in-log > div:nth-child(2) div {
      display: flex;
      justify-content: start;
      align-items: start;
      flex-wrap: wrap;
      gap: 0.2em;
      border-bottom: 1px solid rgb(214, 214, 213);
    }

    .content-in-log > div:nth-child(2) div:nth-child(1) {
      border-top: 1px solid rgb(214, 214, 213);
    }
    .content-in-log > div:nth-child(2) div:nth-last-child(1) {
      border-bottom: none;
    }

    .content-in-log > div:nth-child(2) div span {
      flex-grow: 1;
      text-align: start;
    }

    .content-in-log > div:nth-child(2) div span b {
      font-weight: 550;
    }
  </style>

<link rel="stylesheet" href="{{ asset('assets/src/revenueAgoda.css') }}" />

<!-- Custom Scripts -->
<script src="{{ asset('assets/js/table-together.js') }}"></script>

@endsection
