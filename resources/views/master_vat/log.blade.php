@extends('layouts.masterLayout')
@php
    $excludeDatatable = false;
@endphp
@section('content')

    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Log Vat</div>
                </div>
                <div class="col-auto">

                </div>
            </div> <!-- .row end -->
        </div>
    </div>
    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div style="min-height: 70vh;" class="mt-2">
                                <table id="promotion-LogTable" class="table-together table-style">
                                    <thead>
                                        <tr>
                                            <th  class="text-center">No</th>
                                            <th >Category</th>
                                            <th  class="text-center">Type</th>
                                            <th  class="text-center">Created_by</th>
                                            <th  class="text-center">Created Date</th>
                                            <th  class="text-center">Content</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($log))
                                            @foreach($log as $key => $item)
                                            <tr>
                                                <td style="text-align: center;">{{$key +1 }}</td>
                                                <td style="text-align: left;">{{$item->Category}}</td>
                                                <td style="text-align: center;">{{$item->type}}</td>
                                                <td style="text-align: center;">{{@$item->userOperated->name}}</td>
                                                <td style="text-align: center;">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                                                @php
                                                    $contentArray = explode('+', $item->content);
                                                @endphp
                                                <td style="text-align: left;">
                                                    <b style="color:#0000FF ">{{$item->Category}}</b>
                                                    @foreach($contentArray as $contentItem)
                                                        <div>{{ $contentItem }}</div>
                                                    @endforeach
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12 row mt-5">
                                <div class="col-4"></div>
                                <div class="col-4 "  style="display:flex; justify-content:center; align-items:center;">
                                    <button type="button" class="btn btn-secondary lift btn_modal btn-space" onclick="window.location.href='{{ route('Quantity','index') }}'">
                                        Back
                                    </button>
                                </div>
                                <div class="col-4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <!-- dataTable -->
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.semanticui.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.semanticui.js"></script>
    <script src="{{ asset('assets/js/table-together.js') }}"></script>
    @include('script.script')


@endsection
