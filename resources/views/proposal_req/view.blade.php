@extends('layouts.masterLayout')
<style>

    .btn-space {
        margin-right: 10px; /* ปรับขนาดช่องว่างตามต้องการ */
    }
    .com {
        display: inline-block;  /* ทำให้ border-bottom มีความยาวเท่ากับข้อความ */
        border-bottom: 2px solid #2D7F7B;  /* กำหนดเส้นใต้ */
        padding-bottom: 5px;

    }
    .styled-hr {
        border: none; /* เอาขอบออก */
        border: 1px solid #2D7F7B; /* กำหนดระยะห่างด้านล่าง */
    }
</style>
@section('content')
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">View Proposal Request </div>
                </div>
                <div class="col-auto">

                </div>
            </div> <!-- .row end -->
        </div>
    </div>
    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
            <div class="row align-items-center mb-2" >
                <div class="col">
                    <ol class="breadcrumb d-inline-flex bg-transparent p-0 m-0">
                        <li></li>
                        <li></li>
                        <li></li>
                    </ol>
                </div>
                <div class="col-auto">
                    <div class="btn-group">
                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Select &nbsp;</button>
                        <ul class="dropdown-menu border-0 shadow p-3">
                            @foreach($Data as $key =>$itemdataid)
                                <li><a class="dropdown-item py-2 rounded" onclick="Approve('{{ $itemdataid->DummyNo }}')">Approve ID {{$itemdataid->DummyNo}}</a></li>
                            @endforeach
                            <li><a class="dropdown-item py-2 rounded" onclick="Reject()">Reject</a></li>
                            <li><a class="dropdown-item py-2 rounded" onclick="Back()">Back</a></li>
                        </ul>
                    </div>
                </div>
            </div> <!-- Row end  -->
        </div> <!-- Row end  -->
        @if ($Datacount > 1)
            <div class="container-xl">
                <div class="col-md-12 col-12 row">
                    @foreach($datarequest as  $key => $item )
                        @php
                            $priceless50 =0;
                            $price50=0;
                            $Add50=0;
                            $Net50=0;
                            $pricebefore52 =0;
                            $price51=0;
                            $price52=0;
                            $Add52=0;
                            $Net52=0;
                            $sp50=0;
                            $sp51=0;
                            $sp52=0;
                            $sp = 0;
                        @endphp
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <div class="card mb-4" style="height: 830px;  overflow-x: hidden; overflow-y: auto;">
                                <div class='card-body'>
                                    <div class="row">
                                        @if ($item['Type'] == 'DummyProposal')
                                        <div class="col-lg-12 col-md-12 col-sm-12 com">
                                            <h5  style="font-size: 20px">Dummy ID : {{ $item['Proposal'] }} <button style="float: right"type="button" class="btn btn-color-green lift btn_modal" target="_blank"  onclick="window.open('{{ url('/Dummy/Proposal/view/' . $item['id']) }}', '_blank')">View</button></h5>
                                            <b>Vat Type : </b><label> {{$item['vat']}}</label><br>
                                            <b>Date Type : </b><label> {{$item['Date_type']}}</label>
                                        </div>
                                        @else
                                        <div class="col-lg-12 col-md-12 col-sm-12 com">
                                            <h5 style="font-size: 20px">Proposal ID : {{ $item['Proposal'] }} <button style="float: right"type="button" class="btn btn-color-green lift btn_modal" target="_blank"  onclick="window.open('{{ url('/Proposal/view/' . $item['id']) }}', '_blank')">View</button></h5>
                                            <b>Vat Type : </b><label> {{$item['vat']}}</label><br>
                                            <b>Date Type : </b><label> {{$item['Date_type']}}</label>
                                        </div>
                                        @endif
                                        <input type="hidden" name="DummyNo[]" id="DummyNo" class="DummyNo" value="">
                                        @if ($item['type_Proposal'] == 'Company')
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <b>Company Name : </b><label> {{$item['fullName']}}</label><br>
                                                <b>Company Address : </b><label> {{$item['Adress']}} {{$item['TambonNames']}}{{$item['amphuresNames']}} {{$item['provinceNames']}} {{$item['Zip_Code']}} </label><br>
                                                <b>Company Number : </b><label> {{$item['phone']}}</label><br>
                                                <b>Company Fax : </b><label> {{$item['fax']}}</label><br>
                                                <b>Company Email : </b><label> {{$item['email']}}</label><br>
                                                <b>Taxpayer Identification : </b><label> {{$item['Identification']}}</label><br>
                                                <div class="styled-hr"></div>

                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 mt-2">
                                                <b>Contact Name : </b><label> {{$item['fullNameCon']}}</label><br>
                                                <b>Contact Email : </b><label> {{$item['emailcontact']}}</label><br>
                                                <b>Contact Number : </b><label> {{$item['phonecontact']}}</label><br>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 mt-2">
                                                @if ($item['checkin'])
                                                    <b>Check in : </b><label> {{$item['checkin']}}</label><br><b> Check out : </b><label> {{$item['checkout']}}</label><br>
                                                @else
                                                    <b>Check in : </b><label> {{$item['checkin'] ?? 'No Check in date'}}</label><br>
                                                @endif
                                                <b>Length of Stay : </b>
                                                <label> {{ isset($item['day']) ? $item['day'].' วัน' : ' - ' }}
                                                    {{ isset($item['night']) ? $item['night'].' คืน' : ' ' }}
                                                </label><br>
                                                <b>Number of Guests : </b>
                                                <label> {{$item['adult'].' Adult'}} {{$item['children'].' Children'}}
                                                </label>
                                            </div>
                                        @else
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <b>Guest Name : </b><label> {{$item['fullName']}}</label><br>
                                            <b>Guest Address : </b><label> {{$item['Adress']}} {{$item['TambonNames']}}{{$item['amphuresNames']}} {{$item['provinceNames']}} {{$item['Zip_Code']}} </label><br>
                                            <b>Guest Number : </b><label> {{$item['phone']}}</label><br>
                                            <b>Guest Fax : </b><label> {{$item['fax']}}</label><br>
                                            <b>Guest Email : </b><label> {{$item['email']}}</label><br>
                                            <b>Identification_Number : </b><label> {{$item['Identification']}}</label><br>
                                            <div class="styled-hr"></div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 mt-2">
                                            @if ($item['checkin'])
                                                <b>Check in : </b><label> {{$item['checkin']}}</label><b style="margin-left: 20px"> Check out : </b><label> {{$item['checkout']}}</label><br>
                                            @else
                                                <b>Check in : </b><label> {{$item['checkin'] ?? 'No Check in date'}}</label><br>
                                            @endif
                                            <b>Length of Stay : </b>
                                            <label> {{ isset($item['day']) ? $item['day'].' วัน' : ' - ' }}
                                                {{ isset($item['night']) ? $item['night'].' คืน' : ' ' }}
                                            </label><br>
                                            <b>Number of Guests : </b>
                                            <label> {{$item['adult'].' Adult'}} {{$item['children'].' Children'}}
                                            </label>
                                        </div>
                                        @endif
                                    </div>
                                    @foreach($Data as $key =>$itemdata)
                                        @if ($itemdata->id == $item['id'])
                                            <table class="example ui striped table nowrap unstackable hover" style="width:100%;overflow-x: hidden; overflow-y: auto;" >
                                                <thead>
                                                    <tr>
                                                        <th style="width: 5%">No</th>
                                                        <th>DESCRIPTION</th>
                                                        <th style="width: 5%">Quantity</th>
                                                        <th style="width: 5%">Unit</th>
                                                        <th style="width: 5%">Price / Unit</th>
                                                        <th style="width: 5%">Discount</th>
                                                        <th style="width: 5%">Net Price / Unit</th>
                                                        <th style="width: 5%">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $price50 = $sp50 = $priceless50 = $Add50 = $Net50 = 0;
                                                        $price51 = $sp51 = $price52 = $sp52 = $Add52 = $pricebefore52 = 0;
                                                        $pax = $average = $allaverage = $averagetotal =0;
                                                    @endphp
                                                    @foreach(@$itemdata->document as $key => $itemproduct)
                                                        @php
                                                            $productMatch = $product->firstWhere('Product_ID', $itemproduct->Product_ID);
                                                            $unitMatch = $unit->firstWhere('id', $productMatch->unit);
                                                            $quantityMatch = $quantity->firstWhere('id', $productMatch->quantity);
                                                        @endphp
                                                        @if($productMatch && $unitMatch && $quantityMatch)
                                                            <tr>
                                                                <td style="text-align: center;">{{ $key + 1 }}</td>
                                                                <td>{{ $productMatch->name_th }}</td>
                                                                <td style="text-align: center;">
                                                                    {{ @$itemproduct->Quantity }} {{ $unitMatch->name_th }}
                                                                </td>
                                                                <td style="text-align: center;">
                                                                    {{ @$itemproduct->Unit }} {{ $quantityMatch->name_th }}
                                                                </td>
                                                                <td style="text-align: center;">
                                                                    {{ number_format($productMatch->normal_price) }}
                                                                </td>
                                                                @if (@$itemproduct->discount == 0)
                                                                    <td style="text-align: center;"></td>
                                                                @else
                                                                    <td style="text-align: center;">
                                                                        {{ @$itemproduct->discount }}%
                                                                    </td>
                                                                @endif
                                                                <td style="text-align: center;">
                                                                    {{ number_format(@$itemproduct->netpriceproduct) }}
                                                                </td>
                                                                <td style="text-align: center;">
                                                                    {{ number_format(@$itemproduct->totaldiscount) }}
                                                                </td>
                                                            </tr>

                                                        @endif
                                                        @php
                                                            // Ensure the variables are numeric (float or int) to avoid type errors
                                                            $price50 += (float) @$itemproduct->totaldiscount;
                                                            $sp = (float) $itemdata->SpecialDiscountBath;

                                                            $sp50 = $price50-$sp ;
                                                            $priceless50 = $sp50 / 1.07;
                                                            $Add50 = $sp50 - $priceless50;
                                                            $Net50 = $priceless50 + $Add50;

                                                            // Repeating the same logic for price51 and price52
                                                            $price51 += (float) @$itemproduct->totaldiscount;
                                                            $sp51 = $price51 - $sp;

                                                            $price52 += (float) @$itemproduct->totaldiscount;
                                                            $sp52 = $price52 - $sp;
                                                            $Add52 = $sp52 * 0.07; // You can also write 7 / 100 as 0.07 directly
                                                            $pricebefore52 = $price52 + $Add52;
                                                            $Net52 = $priceless50 + $Add50;
                                                            // Ensure pax and Unit are numeric
                                                            $pax += (float) @$itemproduct->pax * (float) @$itemproduct->Quantity;

                                                            // Accumulate all pax and calculate average


                                                            $average += (float) @$itemproduct->totaldiscount ;
                                                            $averagetotal = $average- $sp;
                                                            // Avoid division by zero
                                                        @endphp
                                                @endforeach

                                                </tbody>
                                            </table>
                                            <style>

                                                .d-grid-2column {
                                                    display:grid;
                                                    grid-template-columns: auto auto;
                                                }

                                                .d-grid-2column :nth-child(2) {
                                                    text-align: end;
                                                }


                                            </style>
                                            <div class="row mt-2">
                                                @if ($itemdata->vat_type == 50)
                                                    <div class="col-lg-6 col-md-6 col-sm-6 "></div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 ">
                                                        <div class="d-grid-2column" >
                                                            <div class="" >
                                                                {{-- <span id="Subtotal">Subtotal : </span><br> --}}
                                                                @if ($itemdata->SpecialDiscountBath)
                                                                    <span id="Special">Special Discount : </span><br>
                                                                    <span id="less">Subtotal less Discount : </span><br>
                                                                @endif
                                                                <span id="Before">Price Before Tax :</span><br>
                                                                <span id="Added">Value Added Tax : </span><br>
                                                                <span id="Net">Net Total : </span><br>
                                                                <span id="Net">Number of Guests : </span><br>
                                                                <span id="Net">Average per person : </span><br>
                                                            </div>
                                                            <div class="">
                                                                {{-- {{ number_format($price50, 2, '.', ',') }} <br> --}}
                                                                @if ($itemdata->SpecialDiscountBath)
                                                                {{ number_format($sp, 2, '.', ',') }}<br>
                                                                {{ number_format($sp50, 2, '.', ',') }}<br>
                                                                @endif
                                                                {{ number_format($priceless50, 2, '.', ',') }}<br>
                                                                {{ number_format($Add50, 2, '.', ',') }}<br>
                                                                {{ number_format($Net50, 2, '.', ',') }}<br>
                                                                {{ number_format($pax) }}<br>
                                                                {{ number_format(($pax > 0) ? $Net50 / $pax : 0,2, '.', ',') }}<br>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif ($itemdata->vat_type == 51)
                                                <div class="col-lg-6 col-md-6 col-sm-6"></div>
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="d-grid-2column">
                                                        <div class="">
                                                                <span id="Subtotal">Subtotal : </span><br>
                                                                @if ($itemdata->SpecialDiscountBath)
                                                                <span id="Special">Special Discount : </span><br>
                                                                <span id="less">Subtotal less Discount : </span><br>
                                                                @endif
                                                                <span id="Net">Net Total : </span><br>
                                                                <span id="Net">Number of Guests : </span><br>
                                                                <span id="Net">Average per person : </span><br>
                                                            </div>
                                                            <div class="">
                                                                {{ number_format($price51, 2, '.', ',') }} <br>
                                                                @if ($itemdata->SpecialDiscountBath)
                                                                {{ number_format($sp, 2, '.', ',') }}<br>
                                                                {{ number_format($sp51, 2, '.', ',') }}<br>
                                                                @endif
                                                                {{ number_format($sp51, 2, '.', ',') }} <br>
                                                                {{ number_format($pax) }}<br>
                                                                {{ number_format(($pax > 0) ? $sp51 / $pax : 0,2, '.', ',') }}<br>
                                                            </div>
                                                        </div>

                                                    </div>
                                                @elseif ($itemdata->vat_type == 52)
                                                    <div class="col-lg-6 col-md-6 col-sm-6"></div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                                        <div class="d-grid-2column">
                                                            <div class="">
                                                                <span id="Subtotal">Subtotal : </span><br>
                                                                @if ($itemdata->SpecialDiscountBath)
                                                                <span id="Special">Special Discount : </span><br>
                                                                <span id="less">Subtotal less Discount : </span><br>
                                                                @endif
                                                                <span id="Added">Value Added Tax : </span><br>
                                                                <span id="Net">Net Total : </span><br>
                                                                <span id="Net">Number of Guests : </span><br>
                                                                <span id="Net">Average per person : </span><br>
                                                            </div>
                                                            <div class="">
                                                                {{ number_format($price52, 2, '.', ',') }} <br>
                                                                @if ($itemdata->SpecialDiscountBath)
                                                                {{ number_format($sp, 2, '.', ',') }}<br>
                                                                {{ number_format($sp52, 2, '.', ',') }} <br>
                                                                @endif
                                                                {{ number_format($Add52, 2, '.', ',') }} <br>
                                                                {{ number_format($pricebefore52, 2, '.', ',') }} <br>
                                                                {{ number_format($pax) }}<br>
                                                                {{ number_format(($pax > 0) ? $pricebefore52 / $pax : 0,2, '.', ',') }}<br>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                        @endif
                                    @endforeach
                                    <form id="myFormApprove" action="{{route('DummyQuotation.Approve')}}" method="POST">
                                        @csrf
                                        @foreach($Data as $item)
                                            <input type="hidden" name="QuotationType" value="{{$item->QuotationType}}">
                                            <!-- ฟิลด์ซ่อนเพื่อเก็บ id -->
                                        @endforeach
                                        <input type="hidden" name="approved_id" id="approved_id">
                                    </form>
                                    <form id="myForm" action="{{ route('DummyQuotation.Reject') }}" method="POST">
                                        @csrf
                                        @foreach($Data as $item)
                                            <input type="hidden" name="DummyNo[]" value="{{ $item->DummyNo }}">
                                            <input type="hidden" name="QuotationType" value="{{ $item->QuotationType }}">
                                        @endforeach
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="container-xl">
                <div class="col-lg-12 col-md-12 col-12 row">
                    @foreach($datarequest as  $key => $item )
                        @php
                            $priceless50 =0;
                            $price50=0;
                            $Add50=0;
                            $Net50=0;
                            $pricebefore52 =0;
                            $price51=0;
                            $price52=0;
                            $Add52=0;
                            $Net52=0;
                            $sp50=0;
                            $sp51=0;
                            $sp52=0;
                            $sp = 0;
                        @endphp
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="card mb-4" style="height: 830px;  overflow-x: hidden; overflow-y: auto;">
                                <div class='card-body'>
                                    <div class="row">
                                        @if ($item['Type'] == 'DummyProposal')
                                        <div class="col-lg-12 col-md-12 col-sm-12 com">
                                            <h5  style="font-size: 20px">Dummy ID : {{ $item['Proposal'] }} <button style="float: right"type="button" class="btn btn-color-green lift btn_modal" target="_blank"  onclick="window.open('{{ url('/Dummy/Proposal/view/' . $item['id']) }}', '_blank')">View</button></h5>
                                            <b>Vat Type : </b><label> {{$item['vat']}}</label><br>
                                            <b>Date Type : </b><label> {{$item['Date_type']}}</label>
                                        </div>
                                        @else
                                        <div class="col-lg-12 col-md-12 col-sm-12 com">
                                            <h5 style="font-size: 20px">Proposal ID : {{ $item['Proposal'] }} <button style="float: right"type="button" class="btn btn-color-green lift btn_modal" target="_blank"  onclick="window.open('{{ url('/Proposal/view/' . $item['id']) }}', '_blank')">View</button></h5>
                                            <b>Vat Type : </b><label> {{$item['vat']}}</label><br>
                                            <b>Date Type : </b><label> {{$item['Date_type']}}</label>
                                        </div>
                                        @endif
                                        <input type="hidden" name="DummyNo[]" id="DummyNo" class="DummyNo" value="">
                                        @if ($item['type_Proposal'] == 'Company')
                                            <div class="col-lg-12 col-md-12 col-sm-12">
                                                <b>Company Name : </b><label> {{$item['fullName']}}</label><br>
                                                <b>Company Address : </b><label> {{$item['Adress']}} {{$item['TambonNames']}}{{$item['amphuresNames']}} {{$item['provinceNames']}} {{$item['Zip_Code']}} </label><br>
                                                <b>Company Number : </b><label> {{$item['phone']}}</label><br>
                                                <b>Company Fax : </b><label> {{$item['fax']}}</label><br>
                                                <b>Company Email : </b><label> {{$item['email']}}</label><br>
                                                <b>Taxpayer Identification : </b><label> {{$item['Identification']}}</label><br>
                                                <div class="styled-hr"></div>

                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 mt-2">
                                                <b>Contact Name : </b><label> {{$item['fullNameCon']}}</label><br>
                                                <b>Contact Email : </b><label> {{$item['emailcontact']}}</label><br>
                                                <b>Contact Number : </b><label> {{$item['phonecontact']}}</label><br>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 mt-2" >
                                                @if ($item['checkin'])
                                                    <b>Check in : </b><label> {{$item['checkin']}}</label>
                                                    <b> Check out : </b><label> {{$item['checkout']}}</label><br>
                                                @else
                                                    <b>Check in : </b><label> {{$item['checkin'] ?? 'No Check in date'}}</label><br>
                                                @endif
                                                <b>Length of Stay : </b>
                                                <label> {{ isset($item['day']) ? $item['day'].' วัน' : ' - ' }}
                                                    {{ isset($item['night']) ? $item['night'].' คืน' : ' ' }}
                                                </label><br>
                                                <b>Number of Guests : </b>
                                                <label> {{$item['adult'].' Adult'}} {{$item['children'].' Children'}}
                                                </label>
                                            </div>
                                        @else
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <b>Guest Name : </b><label> {{$item['fullName']}}</label><br>
                                            <b>Guest Address : </b><label> {{$item['Adress']}} {{$item['TambonNames']}}{{$item['amphuresNames']}} {{$item['provinceNames']}} {{$item['Zip_Code']}} </label><br>
                                            <b>Guest Number : </b><label> {{$item['phone']}}</label><br>
                                            <b>Guest Fax : </b><label> {{$item['fax']}}</label><br>
                                            <b>Guest Email : </b><label> {{$item['email']}}</label><br>
                                            <b>Identification_Number : </b><label> {{$item['Identification']}}</label><br>
                                            <div class="styled-hr"></div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 mt-2" >
                                            @if ($item['checkin'])
                                                <b>Check in : </b><label> {{$item['checkin']}}</label><b> Check out : </b><label> {{$item['checkout']}}</label><br>
                                            @else
                                                <b>Check in : </b><label> {{$item['checkin'] ?? 'No Check in date'}}</label><br>
                                            @endif
                                            <b>Length of Stay : </b>
                                            <label> {{ isset($item['day']) ? $item['day'].' วัน' : ' - ' }}
                                                {{ isset($item['night']) ? $item['night'].' คืน' : ' ' }}
                                            </label><br>
                                            <b>Number of Guests : </b>
                                            <label> {{$item['adult'].' Adult'}} {{$item['children'].' Children'}}
                                            </label>
                                        </div>
                                        @endif
                                    </div>
                                    @foreach($Data as $key =>$itemdata)
                                        @if ($itemdata->id == $item['id'])
                                            <table class="example ui striped table nowrap unstackable hover" style="width:100%;overflow-x: hidden; overflow-y: auto;" >
                                                <thead>
                                                    <tr>
                                                        <th style="width: 5%">No</th>
                                                        <th>DESCRIPTION</th>
                                                        <th style="width: 5%">Quantity</th>
                                                        <th style="width: 5%">Unit</th>
                                                        <th style="width: 5%">Price / Unit</th>
                                                        <th style="width: 5%">Discount</th>
                                                        <th style="width: 5%">Net Price / Unit</th>
                                                        <th style="width: 5%">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $price50 = $sp50 = $priceless50 = $Add50 = $Net50 = 0;
                                                        $price51 = $sp51 = $price52 = $sp52 = $Add52 = $pricebefore52 = 0;
                                                        $pax = $average = $allaverage = $averagetotal =0;
                                                    @endphp
                                                    @foreach(@$itemdata->document as $key => $itemproduct)
                                                        @php
                                                            $productMatch = $product->firstWhere('Product_ID', $itemproduct->Product_ID);
                                                            $unitMatch = $unit->firstWhere('id', $productMatch->unit);
                                                            $quantityMatch = $quantity->firstWhere('id', $productMatch->quantity);
                                                        @endphp
                                                        @if($productMatch && $unitMatch && $quantityMatch)
                                                            <tr>
                                                                <td style="text-align: center;">{{ $key + 1 }}</td>
                                                                <td>{{ $productMatch->name_th }}</td>
                                                                <td style="text-align: center;">
                                                                    {{ @$itemproduct->Quantity }} {{ $unitMatch->name_th }}
                                                                </td>
                                                                <td style="text-align: center;">
                                                                    {{ @$itemproduct->Unit }} {{ $quantityMatch->name_th }}
                                                                </td>
                                                                <td style="text-align: center;">
                                                                    {{ number_format($productMatch->normal_price) }}
                                                                </td>
                                                                @if (@$itemproduct->discount == 0)
                                                                    <td style="text-align: center;"></td>
                                                                @else
                                                                    <td style="text-align: center;">
                                                                        {{ @$itemproduct->discount }}%
                                                                    </td>
                                                                @endif
                                                                <td style="text-align: center;">
                                                                    {{ number_format(@$itemproduct->netpriceproduct) }}
                                                                </td>
                                                                <td style="text-align: center;">
                                                                    {{ number_format(@$itemproduct->totaldiscount) }}
                                                                </td>
                                                            </tr>

                                                        @endif
                                                        @php
                                                            // Ensure the variables are numeric (float or int) to avoid type errors
                                                            $price50 += (float) @$itemproduct->totaldiscount;
                                                            $sp = (float) $itemdata->SpecialDiscountBath ?? 0;

                                                            $sp50 = $price50 -$sp;
                                                            $priceless50 = $sp50 / 1.07;
                                                            $Add50 = $sp50 - $priceless50;
                                                            $Net50 = $priceless50 + $Add50;

                                                            // Repeating the same logic for price51 and price52
                                                            $price51 += (float) @$itemproduct->totaldiscount;
                                                            $sp51 = $price51 - $sp;

                                                            $price52 += (float) @$itemproduct->totaldiscount;
                                                            $sp52 = $price52 - $sp;
                                                            $Add52 = $sp52 * 0.07; // You can also write 7 / 100 as 0.07 directly
                                                            $pricebefore52 = $price52 + $Add52;

                                                            // Ensure pax and Unit are numeric
                                                            $pax += (float) @$itemproduct->pax * (float) @$itemproduct->Quantity;

                                                            // Accumulate all pax and calculate average


                                                            $average += (float) @$itemproduct->totaldiscount ;
                                                            $averagetotal = $average- $sp;
                                                            $allaverage = ($pax > 0) ? $averagetotal / $pax : 0; // Avoid division by zero
                                                        @endphp
                                                @endforeach

                                                </tbody>
                                            </table>
                                            <style>

                                                .d-grid-2column {
                                                    display:grid;
                                                    grid-template-columns: auto auto;
                                                }

                                                .d-grid-2column :nth-child(2) {
                                                    text-align: end;
                                                }


                                            </style>
                                            <div class="row mt-2">
                                                @if ($itemdata->vat_type = 50)
                                                    <div class="col-lg-6 col-md-6 col-sm-6 "></div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 ">
                                                        <div class="d-grid-2column" >
                                                            <div class="" >
                                                                {{-- <span id="Subtotal">Subtotal : </span><br> --}}
                                                                @if ($itemdata->SpecialDiscountBath)
                                                                    <span id="Special">Special Discount : </span><br>
                                                                    <span id="less">Subtotal less Discount : </span><br>
                                                                @endif
                                                                <span id="Before">Price Before Tax :</span><br>
                                                                <span id="Added">Value Added Tax : </span><br>
                                                                <span id="Net">Net Total : </span><br>
                                                                <span id="Net">Number of Guests : </span><br>
                                                                <span id="Net">Average per person : </span><br>
                                                            </div>
                                                            <div class="">
                                                                {{-- {{ number_format($price50, 2, '.', ',') }} <br> --}}
                                                                @if ($itemdata->SpecialDiscountBath)
                                                                {{ number_format($sp, 2, '.', ',') }}<br>
                                                                {{ number_format($sp50, 2, '.', ',') }}<br>
                                                                @endif
                                                                {{ number_format($priceless50, 2, '.', ',') }}<br>
                                                                {{ number_format($Add50, 2, '.', ',') }}<br>
                                                                {{ number_format($Net50, 2, '.', ',') }}<br>
                                                                {{ number_format($pax) }}<br>
                                                                {{ number_format($allaverage,2, '.', ',') }}<br>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif ($itemdata->vat_type = 51)
                                                <div class="col-lg-6 col-md-6 col-sm-6"></div>
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="d-grid-2column">
                                                        <div class="">
                                                                <span id="Subtotal">Subtotal : </span><br>
                                                                @if ($itemdata->SpecialDiscountBath)
                                                                <span id="Special">Special Discount : </span><br>
                                                                <span id="less">Subtotal less Discount : </span><br>
                                                                @endif
                                                                <span id="Net">Net Total : </span><br>
                                                                <span id="Net">Number of Guests : </span><br>
                                                                <span id="Net">Average per person : </span><br>
                                                            </div>
                                                            <div class="">
                                                                {{ number_format($price51, 2, '.', ',') }} <br>
                                                                @if ($itemdata->SpecialDiscountBath)
                                                                {{ number_format($sp, 2, '.', ',') }}<br>
                                                                {{ number_format($sp51, 2, '.', ',') }}<br>
                                                                @endif
                                                                {{ number_format($sp51, 2, '.', ',') }} <br>
                                                                {{ number_format($pax) }}<br>
                                                                {{ number_format($allaverage,2, '.', ',') }}<br>
                                                            </div>
                                                        </div>

                                                    </div>
                                                @elseif ($itemdata->vat_type = 52)
                                                <div class="col-lg-6 col-md-6 col-sm-6"></div>
                                                <div class="col-lg-6 col-md-6 col-sm-6">
                                                    <div class="d-grid-2column">
                                                        <div class="">
                                                                {{-- <span id="Subtotal">Subtotal : </span><br> --}}
                                                                @if ($itemdata->SpecialDiscountBath)
                                                                <span id="Special">Special Discount : </span><br>
                                                                <span id="less">Subtotal less Discount : </span><br>
                                                                @endif
                                                                <span id="Added">Value Added Tax : </span><br>
                                                                <span id="Net">Net Total : </span><br>
                                                                <span id="Net">Number of Guests : </span><br>
                                                                <span id="Net">Average per person : </span><br>
                                                            </div>
                                                            <div class="">
                                                                {{-- {{ number_format($price52, 2, '.', ',') }} <br> --}}
                                                                @if ($itemdata->SpecialDiscountBath)
                                                                {{ number_format($sp, 2, '.', ',') }}<br>
                                                                {{ number_format($sp52, 2, '.', ',') }} <br>
                                                                @endif
                                                                {{ number_format($Add52, 2, '.', ',') }} <br>
                                                                {{ number_format($pricebefore52, 2, '.', ',') }} <br>
                                                                {{ number_format($pax) }}<br>
                                                                {{ number_format($allaverage,2, '.', ',') }}<br>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                        @endif
                                    @endforeach
                                    <form id="myFormApprove" action="{{route('DummyQuotation.Approve')}}" method="POST">
                                        @csrf
                                        @foreach($Data as $item)
                                            <input type="hidden" name="QuotationType" value="{{$item->QuotationType}}">
                                            <!-- ฟิลด์ซ่อนเพื่อเก็บ id -->
                                        @endforeach
                                        <input type="hidden" name="approved_id" id="approved_id">
                                    </form>
                                    <form id="myForm" action="{{ route('DummyQuotation.Reject') }}" method="POST">
                                        @csrf
                                        @foreach($Data as $item)
                                            <input type="hidden" name="DummyNo[]" value="{{ $item->DummyNo }}">
                                            <input type="hidden" name="QuotationType" value="{{ $item->QuotationType }}">
                                        @endforeach
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('script.script')
    <script>
        $(document).ready(function() {
            new DataTable('.example', {
                searching: false,
                paging: false,
                info: false,
                columnDefs: [{
                    className: 'dtr-control',
                    orderable: true,
                    target: null,
                }],
                order: [0, 'asc'],
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr'
                    }
                }
            });
        });
        function Approve(id) {
            document.getElementById('approved_id').value = id; // ตั้งค่า id
            console.log("Approved ID:", document.getElementById('approved_id').value);
            Swal.fire({
                title: `คุณต้องการ Approve รหัส ${id} เอกสารใช่หรือไม่?`,
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "บันทึกข้อมูล",
                cancelButtonText: "ยกเลิก",
                confirmButtonColor: "#2C7F7A",
                dangerMode: true
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log("Submitting form with Approved ID:", document.getElementById('approved_id').value);
                    document.getElementById('myFormApprove').submit();
                }
            });
        }
        function Reject() {
            Swal.fire({
                title: "คุณต้องการ Reject เอกสารใช่หรือไม่?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "บันทึกข้อมูล",
                cancelButtonText: "ยกเลิก",
                confirmButtonColor: "#2C7F7A",
                dangerMode: true
            }).then((result) => {
                if (result.isConfirmed) {

                    document.getElementById('myForm').submit();
                }
            });
        }
        function Back(){
            event.preventDefault();
            Swal.fire({
                title: "คุณต้องการยกเลิกใช่หรือไม่?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "ตกลง",
                cancelButtonText: "ยกเลิก",
                confirmButtonColor: "#28a745",
                dangerMode: true
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log(1);
                    // If user confirms, submit the form
                    window.location.href = "{{ route('ProposalReq.index') }}";
                }
            });
        }
    </script>

@endsection
