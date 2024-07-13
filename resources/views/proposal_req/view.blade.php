@extends('layouts.masterLayout')

@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to Proposal Request View.</small>
                <h1 class="h4 mt-1">Proposal Request View</h1>
            </div>
        </div>
    </div>
@endsection

<style>

    .btn-space {
        margin-right: 10px; /* ปรับขนาดช่องว่างตามต้องการ */
    }
    .com {
        display: inline-block;  /* ทำให้ border-bottom มีความยาวเท่ากับข้อความ */
        border-bottom: 2px solid #2D7F7B;  /* กำหนดเส้นใต้ */
        padding-bottom: 5px;
        font-size: 20px;
    }
</style>
@section('content')
<div class="container">
    <div class="row align-items-center mb-2">
        @if (session("success"))
        <div class="alert alert-success" role="alert">
            <h4 class="alert-heading">บันทึกสำเร็จ!</h4>
            <hr>
            <p class="mb-0">{{ session('success') }}</p>
        </div>
        @endif
        <div class="col">
            <ol class="breadcrumb d-inline-flex bg-transparent p-0 m-0">
                <li></li>
                <li></li>
                <li></li>
            </ol>
        </div>
        <div class="col-auto ">
            <div class="dropdown">
                <button type="button" class="btn btn-secondary lift btn_modal"style="float: right"onclick="Reject()" >Reject</button>
            </div>
        </div>
    </div> <!-- Row end  -->

    <div class="col-sm-12 col-12">
        <div class="row clearfix">
            @foreach($proposal as  $key => $item )
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
            @endphp

                <div class="col-6">
                    <div class="card mb-4" style="height: 830px;  overflow-x: hidden; overflow-y: auto;">
                        <div class='card-body'>
                            <h5 class="com">รหัสใบข้อเสนอ : {{$item->DummyNo}}</h5>
                            <input type="hidden" name="DummyNo[]" id="DummyNo" class="DummyNo" value="{{$item->DummyNo}}">
                            <div class="row">
                                <div class="col-6">
                                    <span>จำนวนผู้เข้าพัก : {{$item->adult}} ผู้ใหญ่ {{$item->children}} เด็ก</span>
                                </div>
                                <div class="col-6">
                                    <span>Special Discount : {{$item->SpecialDiscount}}%</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <span>บริษัท : {{@$item->company2->Company_Name}}</span>
                                </div>
                                <div class="col-6">
                                    <span>ตัวแทน : {{@$item->contact2->First_name}} {{@$item->contact2->Last_name}}</span>
                                </div>
                            </div>
                            <table class="table table-hover align-middle mb-0 mt-2" style="width:100%;overflow-x: hidden; overflow-y: auto;" >
                                <thead>
                                    <tr>
                                        <th style="width: 5%">No</th>
                                        <th>DESCRIPTION</th>
                                        <th style="width: 5%">QUANTITY</th>
                                        <th style="width: 5%">DISCOUNT</th>
                                        <th style="width: 5%">AMOUNT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(@$item->document as $key =>$itemproduct)
                                            <tr>
                                                <td style="text-align: center;">{{ $key+1}}</td>
                                                <td>{{@$itemproduct->product->name_th}}</td>
                                                <td style="text-align: center;">

                                                    {{@$itemproduct->Quantity }}
                                                </td>
                                                <td style="text-align: center;">

                                                    {{@$itemproduct->discount }}
                                                </td>
                                                <td style="text-align: center;">

                                                    {{ number_format(@$itemproduct->netpriceproduct) }}

                                                </td>
                                            </tr>
                                            @php
                                                $price50 += @$itemproduct->netpriceproduct * @$itemproduct->Quantity;
                                                $priceless50 = $price50/1.07;
                                                $Add50 = $price50-$priceless50;
                                                $Net50 = $priceless50+$Add50;

                                                $price51 += @$itemproduct->netpriceproduct * @$itemproduct->Quantity;
                                                $price52 += @$itemproduct->netpriceproduct * @$itemproduct->Quantity;
                                                $Add52 = $price50*7/100;
                                                $pricebefore52 = $price52+$Add52;
                                            @endphp
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="row mt-2">
                                @if ($item->vat_type = 50)
                                    <div class="col-6"></div>
                                    <div class="col-6">
                                        <div class="row">
                                            <div class="col-8">
                                                <span id="Subtotal">Subtotal : </span><br>
                                                <span id="Special">Special Discount : </span><br>
                                                <span id="less">Subtotal less Discount : </span><br>
                                                <span id="Before">Price Before Tax :</span><br>
                                                <span id="Added">Value Added Tax : </span><br>
                                                <span id="Net">Net Total : </span><br>
                                            </div>
                                            <div class="col-4">
                                                {{ number_format($price50, 2, '.', ',') }} <br>
                                                0<br>
                                                {{ number_format($price50, 2, '.', ',') }}<br>
                                                {{ number_format($priceless50, 2, '.', ',') }}<br>
                                                {{ number_format($Add50, 2, '.', ',') }}<br>
                                                {{ number_format($Net50, 2, '.', ',') }}<br>
                                            </div>
                                        </div>
                                    </div>
                                @elseif ($item->vat_type = 51)
                                    <div class="col-6"></div>
                                    <div class="col-6">
                                        <div class="row">
                                            <div class="col-8">
                                                <span id="Subtotal">Subtotal : </span><br>
                                                <span id="Special">Special Discount : </span><br>
                                                <span id="less">Subtotal less Discount : </span><br>
                                                <span id="Net">Net Total : </span><br>
                                            </div>
                                            <div class="col-4">
                                                {{ number_format($price51, 2, '.', ',') }} <br>
                                                0<br>
                                                {{ number_format($price51, 2, '.', ',') }} <br>
                                                0<br>
                                                {{ number_format($price51, 2, '.', ',') }} <br>
                                            </div>
                                        </div>

                                    </div>
                                @elseif ($item->vat_type = 52)
                                    <div class="col-6"></div>
                                    <div class="col-6">
                                        <div class="row">
                                            <div class="col-8">
                                                <span id="Subtotal">Subtotal : </span><br>
                                                <span id="Special">Special Discount : </span><br>
                                                <span id="less">Subtotal less Discount : </span><br>
                                                <span id="Added">Value Added Tax : </span><br>
                                                <span id="Net">Net Total : </span><br>
                                            </div>
                                            <div class="col-4">
                                                {{ number_format($price52, 2, '.', ',') }} <br>
                                                0<br>
                                                {{ number_format($Add52, 2, '.', ',') }} <br>
                                                {{ number_format($pricebefore52, 2, '.', ',') }} <br>
                                                {{ number_format($pricebefore52, 2, '.', ',') }} <br>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <form id="myForm" action="{{route('DummyQuotation.Approve')}}" method="POST">
                            @csrf
                            <div class="row mt-2 my-4">
                                <div class="col-3"></div>
                                <input type="hidden" name="DummyNo" value="{{$item->DummyNo}}">
                                <input type="hidden" name="QuotationType" value="{{$item->QuotationType}}">
                                <div class="col-6" style="display:flex; justify-content:center; align-items:center;">
                                    <button type="submit" class="btn btn-success lift btn_modal" >
                                        <i class="fa fa-check"></i> Approve
                                    </button>
                                </div>
                                <div class="col-3">
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        var DummyNo = [];
            $('.DummyNo').each(function() {
                DummyNo.push($(this).val());
            });
    });
    function Reject() {
        var dummyNos = [];
        $('.DummyNo').each(function() {
            dummyNos.push($(this).val());
        });
        jQuery.ajax({
            type: "GET",
            url: "{!! url('/Dummy/Proposal/Request/document/view/Reject/" + dummyNos + "') !!}",
            datatype: "JSON",
            async: false,
            success: function(response) {
                console.log("AJAX request successful: ", response);
                if (response.success) {
                // เปลี่ยนไปยังหน้าที่ต้องการ
                window.location.href = "/Proposal/request/index";
                } else {
                    alert("An error occurred while processing the request.");
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed: ", status, error);
            }
        });
    }
</script>
@endsection
