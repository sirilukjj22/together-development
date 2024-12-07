@extends('layouts.masterLayout')

@php
    $excludeDatatable = false;
@endphp

@section('content')
<div id="content-index" class="body-header border-bottom d-flex py-3">
    <div class="container-xl">
        <div class="row align-items-center">
            <div class="col sms-header">
                <div class=""><span class="span1">Agoda</span><span class="span2"> / {{ $title }}</span></div>
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
        <div class="wrapTopAgodaDetails">
            <div class="wrapAdressTogether">
                <div class="top-img">
                    <img src="/image/logo.jpg" alt="logo of Together Resort" width="120" />
                </div>
                <div class="top-dt">
                    <b>Together Resort Limited Partnership</b>
                    <p>168 Moo 2 Kaengkrachan Phetchaburi 76170</p>
                    <p>Tel : 032-708-888, 098-393-944-4</p>
                    <p> Email : reservation@together-resort.com &nbsp; Website : www.together-resort.com</p>
                </div>
            </div>
            <div class="">
                <div class="codeDoc">
                    <p>Document No</p>
                    <p>{{ @$agoda_revenue->DocumentNoAgoda->doc_no }}</p>
                </div>
                <div class="center" style="border: #1c504c 1px solid; border-radius: 7px">
                    <li>Issue Date : {{ date('d/m/Y', strtotime(@$agoda_revenue->DocumentNoAgoda->issue_date)) }}</li>
                </div>
            </div>
        </div>
        <hr />
        <div class="wrapAdressAgoda">
            <div>
                <img src="/image/front/agoda.jpg" alt="" width="80" height="75" />
                <b>Agoda Services Co., Ltd.</b>
            </div>
            <p> 999/9 อาคารดิออฟฟิศเซส แอท เซ็นทรัลเวิลด์ ถนนพระราม 1</p>
            <p>แขวงปทุมวัน เขตปทุมวัน กรุงเทพมหานคร 10330</p>
            <p>
                <b>Tel :</b> 0-2625-9200
            </p>
        </div>
        <div class="text-center my-3">
            <b class="title-top-table">Debit Agoda Revenue</b>
        </div>
        <div class="wrap-detailPaid">
            <div class="detailPaid">
                <div>
                    <b>Date :</b> {{ date('d/m/Y', strtotime($agoda_revenue->sms_date)) }}
                </div>
                <div>
                    <b>Bank :</b>
                    <span>
                        <img src="/image/bank/SCB.jpg" alt="" width="30" style="margin: 5px; border-radius: 50px" /> Siam Commercial Bank PCL. </span>
                </div>
                <div>
                    <b>Bank Account :</b>
                    <span> 456-7657-09000</span>
                </div>
                <div>
                    <b>Amount : </b> {{ number_format($agoda_revenue->amount, 2) }}
                </div>
            </div>
        </div>

        <div class="wrap-table-together mt-3">
            <table id="agodaDebitDetailTable" class="example table-together table-style">
                <thead>
                    <tr class="text-capitalize">
                        <th data-priority="1">#</th>
                        <th data-priority="1">booking number</th>
                        <th data-priority="3">date check in</th>
                        <th data-priority="3">date check oun</th>
                        <th data-priority="1">amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($agoda_outstanding as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->batch }}</td>
                            <td>{{ Carbon\Carbon::parse($item->agoda_check_in)->format('d/m/Y') }}</td>
                            <td>{{ Carbon\Carbon::parse($item->agoda_check_out)->format('d/m/Y') }}</td>
                            <td class="text-end target-class">{{ $item->agoda_outstanding }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot style="background-color: #d7ebe1; font-weight: bold">
                    <tr>
                        <td class="text-center" style="padding: 10px">Total</td>
                        <td colspan="3"></td>
                        <td class="text-end"><span id="txt-total-agodaDebitDetail">{{ number_format($total_agoda_outstanding, 2) }}</span></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="flex-end mt-5">
            <div style="text-align: center">
                <p>ผู้ออกเอกสาร (ผู้ขาย)</p>
                <p style="border-bottom: 1px solid grey; height: 5em; width: 170px;"></p>
            </div>
        </div>
    </section>
</div>

<input type="hidden" id="sms-id" value="{{ $agoda_revenue->id }}">

<style>
    .table-together tr th{
        text-align: center !important;
    }
</style>

<link rel="stylesheet" href="{{ asset('assets/src/revenueAgoda.css') }}" />

<!-- Moment Date -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<!-- Custom Scripts -->
<script src="{{ asset('assets/js/table-together.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/helper/searchTableDebtorAgoda.js')}}"></script>

<script>

</script>

@endsection
