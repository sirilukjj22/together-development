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
                            <table id="feeTable" class="example table-together table-style">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;" data-priority="1">#</th>
                                        <th style="text-align: center;" data-priority="1">Date</th>
                                        <th style="text-align: center;">Revenue Type</th>
                                        <th style="text-align: center;" data-priority="1">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total_amount = 0;
                                    @endphp
                                    @foreach ($data_query as $key => $item)
                                        @php
                                        if ($status == "credit_hotel_fee") {
                                            $sum_charge = App\Models\Harmony\Harmony_revenue_credit::where('revenue_id', $item->id)->sum('credit_amount');
                                        } else {
                                            $sum_charge = App\Models\Harmony\Harmony_revenue_credit::where('revenue_id', $item->id)->sum('ev_fee');
                                        }
                                        @endphp
                                        @if (($sum_charge - $item->total_credit) != 0)
                                            <tr style="text-align: center;">
                                                <td class="td-content-center">{{ $key + 1 }}</td>
                                                <td class="td-content-center">{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                                <td class="td-content-center">{{ $status != "credit_hotel_fee" ? "Elexa EGAT Fee" : "Credit Card Hotel Fee" }}</td>
                                                <td style="text-align: left;">{{ number_format($sum_charge - $item->total_credit, 2) }}</td>
                                            </tr>
                                            @php
                                                $total_amount += $sum_charge - $item->total_credit;
                                            @endphp
                                        @endif
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="fw-bold text-center">Total</td>
                                        <td></td>
                                        <td></td>
                                        <td class="fw-bold text-start">{{ number_format($total_amount, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div> <!-- .card end -->
                </div>
            </div> <!-- .row end -->
        </div>
    </div>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.semanticui.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.semanticui.js"></script>

    <!-- สำหรับค้นหาในส่วนของตาราง -->
    <script src="{{ asset('assets/js/table-together.js') }}"></script>

    <script>
        $(document).ready(function() { 
            $('input[type="search"]').attr("placeholder", "Type to search...");
            $('label[for^="dt-length-"], label[for^="dt-search-"]').hide();
        });
    </script>
@endsection
