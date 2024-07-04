@extends('layouts.masterLayout')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">

<style>

.image-container {
    display: flex;
    flex-direction: row;
    align-items: center;
    text-align: left;
}
.image-container img.logo {
    width: 15%; /* กำหนดขนาดคงที่ */
    height: auto;
    margin-right: 20px;
}

.image-container .info {
    margin-top: 0;
}

.image-container .info p {
    margin: 5px 0;
}

.image-container .titleh1 {
    font-size: 1.2em;
    font-weight: bold;
    margin-bottom: 10px;
}
.titleQuotation{
    display:flex;
    justify-content:center;
    align-items:center;
}
.com {
    display: inline-block;  /* ทำให้ border-bottom มีความยาวเท่ากับข้อความ */
    border-bottom: 2px solid #2D7F7B;  /* กำหนดเส้นใต้ */
    padding-bottom: 5px;
    font-size: 20px;
}
.Profile{
    width: 15%;
}
.styled-hr {
    border: none; /* เอาขอบออก */
    border: 1px solid #2D7F7B; /* กำหนดระยะห่างด้านล่าง */
}
.table-borderless1{
        border-radius: 6px;
        background-color: #109699;
        color:#fff;
    }
    .centered-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .centered-content4 {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        border: 2px solid #6b6b6b; /* กำหนดกรอบสี่เหลี่ยม */
        padding: 20px; /* เพิ่ม padding ภายในกรอบ */
        border-radius: 5px; /* เพิ่มมุมโค้ง (ถ้าต้องการ) */
        height: 120px;
        width: 120px; /* กำหนดความสูงของกรอบ */
    }
    .proposal{
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        top: 0px;
        right: 6;
        width: 70%;
        height: 60px;
        border: 3px solid #2D7F7B;
        border-radius: 10px;
        background-color: #109699;
    }
    .proposalcode{
        top: 0px;
        right: 6;
        width: 70%;
        height: 90px;
        border: 3px solid #2D7F7B;
        border-radius: 10px;
    }
    .btn-space {
        margin-right: 10px; /* ปรับขนาดช่องว่างตามต้องการ */
    }
@media (max-width: 768px) {
    .image-container {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .image-container img.logo {
        margin-bottom: 20px;
        width: 50%;
    }
    .Profile{
    width: 100%;
    }
}
</style>
@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to add Product to Proposal.</small>
                <h1 class="h4 mt-1">Add Product to Proposal (เพิ่มโปรดักส์ลงข้อเสนอ)</h1>
            </div>
        </div>
    </div>
@endsection
@section('content')
<div class="container" style="font-size: 16px; font-family: Arial, sans-serif;position: relative;">
    <div class="container mt-3">
        <div class="row clearfix">
            <div class="col-sm-12 col-12">
                <div class="card p-4 mb-4">
                    <form id="myForm" action="{{url('/Quotation/company/update/quotationupdate/'.$Quotation->id)}}" method="POST"enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-lg-8 col-md-12 col-sm-12 image-container">
                                <img src="{{ asset('assets2/images/logo_crop.png') }}" alt="Together Resort Logo" class="logo"/>
                                <div class="info">
                                    <b style="font-size:20px;">Together Resort Limited Partnership</b>

                                    <br> <span> 168 Moo 2 Kaengkrachan Phetchaburi 76170</span>

                                    <br> <span>Tel : 032-708-888, 098-393-944-4 Fax :</span></br>

                                    <span> Email : reservation@together-resort.com Website : www.together-resort.com</span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-lg-12  d-flex justify-content-center" >
                                        <div class="proposal">
                                            <span  class="titleQuotation" style="font-size:28px;color:#ffffff;">PROPOSAL</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-center mt-2" >
                                        <div class="proposalcode">
                                            <div style="padding: 4%">

                                                <b >Proposal ID : </b><span style="margin-left: 10px;">{{ $Quotation->Quotation_ID }}</span><br>

                                                <b >Issue Date : </b><span >{{ $Quotation->issue_date }}</span><br>

                                                <b>Expiration Date : </b><span>{{ $Quotation->Expirationdate }}</span>
                                                <input type="hidden" id="Quotation_ID" name="Quotation_ID" value="{{$Quotation->Quotation_ID}}">
                                                <input type="hidden" id="Quotation_ID" name="IssueDate" value="{{ $Quotation->issue_date }}">
                                                <input type="hidden" id="Quotation_ID" name="ExpirationDate" value="{{ $Quotation->Expirationdate }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-7 col-md-12 col-sm-12" style=" border-right-style: solid  ; border-right-width: 2px;border-right-color:#109699">
                                <b class="com mt-2 my-2"style="font-size:18px">Company Information</b>
                                <table>
                                    <tr>
                                        <td style="padding: 10px"><b style="margin-left: 2px; width:30%;font-weight: bold;color:#000;">Company Name :</b></td>
                                        <td>@if ($Company_type->name_th === 'บริษัทจำกัด')
                                            <span id="Company_name" name="Company_name" >บริษัท {{ $Company_ID->Company_Name }} จำกัด</span>
                                        @elseif ($Company_type->name_th === 'บริษัทมหาชนจำกัด')
                                            <span id="Company_name" name="Company_name">บริษัท {{ $Company_ID->Company_Name }} จำกัด (มหาชน)</span>
                                        @elseif ($Company_type->name_th === 'ห้างหุ้นส่วนจำกัด')
                                            <span id="Company_name" name="Company_name">ห้างหุ้นส่วนจำกัด {{ $Company_ID->Company_Name }}</span>
                                        @endif</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Company Address :</b></td>
                                        <td>{{$Company_ID->Address}} {{'ตำบล' . $TambonID->name_th}} {{'อำเภอ' .$amphuresID->name_th}} {{'จังหวัด' .$provinceNames->name_th}} {{$TambonID->Zip_Code}}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Company Number :</b></td>
                                        <td>{{ substr($company_phone->Phone_number, 0, 3) }}-{{ substr($company_phone->Phone_number, 3, 3) }}-{{ substr($company_phone->Phone_number, 6) }}
                                            <b style="margin-left: 10px;color:#000;">Company Fax : </b><span>{{$company_fax->Fax_number}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Company Email :</b></td>
                                        <td>{{$Company_ID->Company_Email}}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Taxpayer Identification : </b></td>
                                        <td>{{$Company_ID->Taxpayer_Identification}}</td>
                                    </tr>

                                </table>
                                <div>
                                    <br>
                                </div>
                                <div>
                                    <b class="com my-2" style="font-size:18px">Personal Information</b>
                                </div>
                                <div class="col-12 row">
                                    <div class="col-6">
                                        <p style="display: inline-block;font-weight: bold;margin-left: 10px;">Contact Name :</p>
                                        <p id="Company_contact" name="Company_contact" style="display: inline-block;">คุณ{{$Contact_name->First_name}} {{$Contact_name->Last_name}}</p>
                                    </div>
                                    <div class="col-6">
                                        <p style="display: inline-block;font-weight: bold;">Contact Number :</p>
                                        <p id="Contact_Phone" name="Contact_Phone" style="display: inline-block;">{{ substr($Contact_phone->Phone_number, 0, 3) }}-{{ substr($Contact_phone->Phone_number, 3, 3) }}-{{ substr($Contact_phone->Phone_number, 6) }}</p>
                                    </div>
                                    <div>
                                        <p style="display: inline-block;font-weight: bold;margin-left: 10px;">Contact Email :</p>
                                        <p id="Contact_Email" name="Contact_Email" style="display: inline-block;">{{$Contact_name->Email}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <div><br><br><br><br></div>
                                <div class="col-12 row" >
                                    <div class="col-lg-6">
                                        <p style="display: inline-block;font-weight: bold;">Check In :</p><br>
                                        <p style="display: inline-block;font-weight: bold;">Check Out :</p><br>
                                        <p style="display: inline-block;font-weight: bold;">Length of Stay :</p><br>
                                        <p style="display: inline-block;font-weight: bold;">Number of Guests :</p>
                                    </div>
                                    <div class="col-lg-6">
                                        <p style="display: inline-block;">{{$Quotation->checkin}}</p><br>
                                        <p style="display: inline-block;">{{$Quotation->checkout}}</p><br>
                                        <p style="display: inline-block;">{{$Quotation->day}} วัน {{$Quotation->night}} คืน</p><br>
                                        <p style="display: inline-block;">{{$Quotation->adult}} Adult , {{$Quotation->adult}} Children</p>
                                    </div>
                                </div>
                            </div>
                            <div class="styled-hr"></div>
                        </div>

                        <div class="mt-2">
                            <strong>ขอเสนอราคาและเงื่อนไขสำหรับท่าน ดังนี้ <br> We are pleased to submit you the following desctibed here in as price,items and terms stated :</strong>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-2 col-md-12 col-sm-12">
                                <button  id="addproduct" type="button" class="btn btn-color-green lift btn_modal my-3" data-bs-toggle="modal" data-bs-target="#exampleModalproduct"onclick="fetchProducts('all')">
                                    <i class="fa fa-plus"></i> Add Product</button>
                            </div>
                            <div class="modal fade" id="exampleModalproduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header btn-color-green ">
                                        <h5 class="modal-title text-white" id="exampleModalLabel">Product</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-12 mt-3">
                                            <div class="dropdown">
                                                <button class="btn btn-outline-dark lift dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                    ประเภท Product
                                                </button>
                                                <ul class="dropdown-menu border-0 shadow p-3">
                                                    <li><a class="dropdown-item py-2 rounded" data-value="all" onclick="fetchProducts('all')">All Product</a></li>
                                                    <li><a class="dropdown-item py-2 rounded" data-value="Room_Type"onclick="fetchProducts('Room_Type')">Room</a></li>
                                                    <li><a class="dropdown-item py-2 rounded" data-value="Banquet"onclick="fetchProducts('Banquet')">Banquet</a></li>
                                                    <li><a class="dropdown-item py-2 rounded" data-value="Meals"onclick="fetchProducts('Meals')">Meal</a></li>
                                                    <li><a class="dropdown-item py-2 rounded" data-value="Entertainment"onclick="fetchProducts('Entertainment')">Entertainment</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <hr class="mt-3 my-3" style="border: 1px solid #000">
                                            <table  class="myDataTableQuotationmodal table table-hover align-middle mb-0" style="width:100%">
                                                <thead >
                                                    <tr>
                                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 10%">#</th>
                                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 10%">รหัส</th>
                                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">รายการ</th>
                                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 10%">หน่วย</th>
                                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 10%">ราคา</th>
                                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 5%">คำสั่ง</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="product-list">

                                                </tbody>
                                            </table>
                                        <div class="col-12 mt-3">
                                            <h3>รายการที่เลือก</h3>
                                            <table  class="table table-hover align-middle mb-0">
                                                <thead >
                                                    <tr>
                                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 10%">#</th>
                                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 10%">รหัส</th>
                                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">รายการ</th>
                                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 10%">หน่วย</th>
                                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 10%">ราคา</th>
                                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 5%">คำสั่ง</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="product-list-select">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary lift" data-bs-dismiss="modal">ยกเลิก</button>
                                        <button type="button" class="btn btn-color-green lift confirm-button" id="confirm-button">สร้าง</button>
                                    </div>
                                </div>
                                </div>
                                <div id="modalOverlay" class="modal-overlay"></div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <table class=" table table-hover align-middle mb-0" style="width:100%">
                                <thead >
                                    <tr>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">No.</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">Description</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:10%;text-align:center;">Quantity</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">Unit</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">Price / Unit</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:10%;text-align:center;">Discount</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;">Net price/Unit</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;">Amount</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">Order</th>
                                    </tr>
                                </thead>
                                <tbody id="display-selected-items">
                                    @if (!empty($selectproduct))
                                        @foreach ($selectproduct as $key => $item)
                                            @foreach ($unit as $singleUnit)
                                                @if($singleUnit->id == @$item->product->unit)
                                                    <tr id="tr-select-main{{$item->Product_ID}}">
                                                        <input type="hidden" id="tr-select-main{{$item->Product_ID}}" name="tr-select-main[]" value="{{$item->Product_ID}}">
                                                        <td><input type="hidden" id="ProductID" name="ProductIDmain[]" value="{{$item->Product_ID}}">{{$key+1}}</td>
                                                        <td style="text-align:left;"><input type="hidden" id="Productname_th" name="Productname_th" value="{{@$item->product->name_th}}">{{@$item->product->name_th}}</td>
                                                        <td class="Quantity" data-value="{{$item->Quantity}}"style="text-align:center;"><input type="hidden" id="Quantity" name="Quantitymain[]" value="{{$item->Quantity}}">{{$item->Quantity}}</td>
                                                        <td><input type="hidden" id="unitname_th" name="unitname_th" value="{{ $singleUnit->name_th }}">{{ $singleUnit->name_th }}</td>
                                                        <td class="priceproduct" data-value="{{$item->priceproduct}}"><input type="hidden" id="totalprice-unit{{$key+1}}" name="priceproductmain[]" value="{{$item->priceproduct}}">{{ number_format($item->priceproduct, 2, '.', ',') }}</td>
                                                        <td class="discount"style="text-align:center;"><input type="hidden" id="discount" name="discountmain[]" value="{{$item->discount}}">{{$item->discount}}%</td>
                                                        <td class="net-price"style="text-align:center;" ><input type="hidden" id="net_discount{{$key+1}}" name="net_discountmain[]" value="{{$item->totaldiscount}}">{{ number_format($item->netpriceproduct, 2, '.', ',') }}</td>
                                                        <td class="item-total"style="text-align:center;"><input type="hidden" id="allcounttotal{{$key+1}}" name="allcounttotalmain[]" value="{{$item->netpriceproduct}}">{{ number_format($item->netpriceproduct, 2, '.', ',') }}</td>
                                                        <td>
                                                            <button type="button" class="Btn remove-button1">
                                                                <i class="fa fa-minus-circle text-danger fa-lg"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <input type="hidden" name="adult" id="adult" value="{{$Quotation->adult}}">
                        <input type="hidden" name="children" id="children" value="{{$Quotation->children}}">
                        <input type="hidden" name="vat_type_name" id="vat_type_name" value="{{ $Mvat->firstWhere('id', $Quotation->vat_type)->name_th ?? '' }}">
                        @if (@Auth::user()->roleMenuDiscount('Proposal') == 1)
                            <input type="hidden" name="roleMenuDiscount" id="roleMenuDiscount" value="1">
                        @else
                            <input type="hidden" name="roleMenuDiscount" id="roleMenuDiscount" value="0">
                        @endif
                        <input type="hidden" name="discountuser" id="discountuser" value="{{@Auth::user()->discount}}">
                        <div class="col-12 row ">
                            <div class="col-lg-8 col-md-8 col-sm-12 mt-2" >
                                <span >Notes or Special Comment</span>
                                <textarea class="form-control mt-2"cols="30" rows="5"name="comment" id="comment" placeholder="Leave a comment here" id="floatingTextarea"></textarea>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 " >
                                @php
                                    $vatTypeName = $Mvat->firstWhere('id', $Quotation->vat_type)->name_th ?? '';
                                @endphp
                                @if ($vatTypeName == 'PRICE INCLUDE VAT')
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr >
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Subtotal</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="total-amount">0</span></td>
                                            </tr>
                                            <tr>
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Special Discount</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;">
                                                    @if (@Auth::user()->roleMenuSpecialDiscount('Proposal') == 1)
                                                        <input type="text" id="SpecialDis" name="SpecialDis" class="form-control" value="0" >
                                                    @else
                                                        <input type="text" id="SpecialDis" name="SpecialDis" class="form-control" value="0" disabled>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Subtotal less Discount</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="lessDiscount"></span></td>
                                            </tr>
                                            <tr>
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Price Before Tax</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="Net-price">0</span></td>
                                            </tr>
                                            <tr>
                                                <td scope="row" style="text-align:right;width: 55%;font-size: 14px;"><b>Value Added Tax</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="total-Vat">0</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @elseif($vatTypeName == 'PRICE EXCLUDE VAT')
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr >
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Subtotal</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="total-amount">0</span></td>
                                            </tr>
                                            <tr>
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Special Discount</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;">
                                                    @if (@Auth::user()->roleMenuSpecialDiscount('Proposal') == 1)
                                                        <input type="text" id="SpecialDis" name="SpecialDis" class="form-control" value="0" >
                                                    @else
                                                        <input type="text" id="SpecialDis" name="SpecialDis" class="form-control" value="0" disabled>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Subtotal less Discount</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="lessDiscount">0</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @else
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr >
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Subtotal</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="total-amount">0</span></td>
                                            </tr>
                                            <tr>
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Special Discount</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;">
                                                    @if (@Auth::user()->roleMenuSpecialDiscount('Proposal') == 1)
                                                        <input type="text" id="SpecialDis" name="SpecialDis" class="form-control" value="0" >
                                                    @else
                                                        <input type="text" id="SpecialDis" name="SpecialDis" class="form-control" value="0" disabled>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Subtotal less Discount</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="lessDiscount">0</span></td>
                                            </tr>
                                            <tr>
                                                <td scope="row" style="text-align:right;width: 55%;font-size: 14px;"><b>Value Added Tax</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="total-Vat">0</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 row">
                            <div class="col-8"></div>
                            <div class="col-lg-4 col-md-3 col-sm-12">
                                <table class="table table-borderless1" >
                                    <tbody>
                                        <tr>
                                            <td colspan="2" style="text-align:center;">
                                                <div style="display: flex; justify-content: center; align-items: center; border: 2px solid #2D7F7B; background-color: #2D7F7B; border-radius: 5px; color: #ffffff;padding:5px;  padding-bottom: 8px;">
                                                    <b style="font-size: 14px;">Net Total</b>
                                                    <strong id="total-Price" style="font-size: 16px; margin-left: 10px;"><span id="Net-Total">0</span></strong>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 row">
                            <div class="col-8"></div>
                            <div class="col-4 styled-hr"></div>
                        </div>
                        <div class="col-12 row">
                            <div class="col-8">
                            </div>
                            <div class="col-lg-4 col-md-3 col-sm-12">
                                <table class="table table-borderless" >
                                    <tbody>
                                        <tr>
                                            <td style="text-align:right;width: 55%;font-size: 14px;"><b>Average per person</b></td>
                                            <td style="text-align:left;width: 45%;font-size: 14px;"><span id="Average">0</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <strong class="com" style="font-size: 18px">Method of Payment</strong>
                            </div>
                            <span class="col-md-6 col-sm-12">
                                Please make a 50% deposit within 7 days after confirmed. <br>
                                Transfer to <strong> " Together Resort Limited Partnership "</strong> following banks details.<br>
                                If you use transfer, Please inform Accounting / Finance Department Tel or LINE ID<span style="font-size: 18px"> @Together-resort</span><br>
                                pay-in slip to number 032-708-888 every time for the correctness of payment allocation.<br>
                            </span>
                            <div class="row">
                                <div class="col-lg-8 col-md-6 col-sm-12">
                                    <div class="col-12  mt-2">
                                        <div class="row">
                                            <div class="col-2 mt-3" style="display: flex;justify-content: center;align-items: center;">
                                                <img src="{{ asset('/image/bank/SCB.jpg') }}" style="width: 60%;border-radius: 50%;"/>
                                            </div>
                                            <div class="col-7 mt-2">
                                                <strong>The Siam Commercial Bank Public Company Limited <br>Bank Account No. 708-226791-3<br>Tha Yang - Phetchaburi Branch (Savings Account)</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="styled-hr mt-3"></div>
                        <div class="col-12 mt-2">
                            <div class="col-4">
                                <strong class="titleh1">รับรอง</strong>
                            </div>
                            <div class="col-12 my-2">
                                <div class="row">
                                    <div class="col-lg-2 centered-content">
                                        <span>สแกนเพื่อเปิดด้วยเว็บไซต์</span>
                                        @php
                                            use SimpleSoftwareIO\QrCode\Facades\QrCode;
                                            $id = $Quotation->id;
                                            $gethttp =(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http";
                                            $linkQR = $gethttp."://".$_SERVER['HTTP_HOST']."/Quotation/Quotation/cover/document/PDF/$id?page_shop=".@$_GET['page_shop'];
                                        @endphp
                                        <div class="mt-3">
                                            {!! QrCode::size(90)->generate($linkQR); !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-2 centered-content">
                                        <span>ผู้ออกเอกสาร (ผู้ขาย)</span><br>
                                        <br><br>
                                        <span>{{@$Quotation->user->name}}</span>
                                        <span>{{ $Quotation->issue_date }}</span>
                                    </div>
                                    <div class="col-lg-2 centered-content">
                                        <span>ผู้อนุมัติเอกสาร (ผู้ขาย)</span><br>
                                        <br><br>
                                        <span>{{@$Quotation->user->name}}</span>
                                        <span>{{ $Quotation->issue_date }}</span>
                                    </div>
                                    <div class="col-lg-2 centered-content">
                                        <span>ตราประทับ (ผู้ขาย)</span>
                                    </div>
                                    <div class="col-lg-2 centered-content">
                                        <span>ผู้รับเอกสาร (ลูกค้า)</span>
                                        <br><br><br>
                                            ---------------------
                                        <span>{{ $Company_ID->Company_Name }}</span>
                                    </div>
                                    <div class="col-lg-2 centered-content">
                                        <span>ตราประทับ (ลูกค้า)</span>
                                        <div class="centered-content4 mt-1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="styled-hr mt-3"></div>
                        <div class="col-12 row mt-5">
                            <div class="col-4"></div>
                            <div class="col-4 "  style="display:flex; justify-content:center; align-items:center;">
                                <button type="button" class="btn btn-primary lift btn_modal btn-space" onclick="submitPreview()">
                                    แสดงตัวอย่างใบเสนอ
                                </button>
                                <button type="submit" class="btn btn-color-green lift btn_modal">บันทึกใบเสนอราคา</button>
                            </div>
                            <div class="col-4"></div>
                        </div>
                    </form>
                </div>
                <input type="hidden" name="preview" value="preview" id="preview">
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script>
    function fetchProducts(status) {
        var table = $('.myDataTableQuotationmodal').DataTable();
        var Quotation_ID = '{{ $Quotation->Quotation_ID }}'; // Replace this with the actual ID you want to send
        var clickCounter = 1;
        $.ajax({
            url: '{{ route("Quotation.addProducttable", ["Quotation_ID" => ":id"]) }}'.replace(':id', Quotation_ID),
            method: 'GET',
            data: {
                value: status
            },
            success: function(response) {
                if (response.products.length > 0) {
                    // Clear the existing rows
                    table.clear();

                    for (let i = 0; i < response.products.length; i++) {
                        const data = response.products[i];
                        const productId = data.id;
                        if ($('#tr-select-main' + data.Product_ID).length == 0) {
                            table.row.add([
                                i + 1,
                                data.Product_ID,
                                data.name_th,
                                data.unit_name,
                                data.normal_price,
                                `<button type="button"  class="btn btn-color-green lift btn_modal select-button-product" id="product-${data.id}" value="${data.id}"><i class="fa fa-plus"></i></button>`
                            ]).node().id = `row-${productId}`;
                        }

                    }
                    table.draw(false);

                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
        $(document).ready(function() {
            if (!$.fn.DataTable.isDataTable('.product-list-select')) {
                var table = $('.product-list-select').DataTable();
            } else {
                var table = $('.product-list-select').DataTable();
            }
            $(document).on('click', '.select-button-product', function() {
                var product = $(this).val();
                if ($('#productselect' + product).length > 0) {
                    return;
                }
                $.ajax({
                    url: '{{ route("Quotation.addProducttableselect", ["Quotation_ID" => ":id"]) }}'.replace(':id', Quotation_ID),
                    method: 'GET',
                    data: {
                        value:product
                    },
                    success: function(response) {
                        $.each(response.products, function(index, val) {
                            var name = '';
                            var price = 0;
                            var rowNumber = $('#product-list-select tr').length+1;
                            if ($('#productselect' + val.id).length > 0) {
                                console.log("Product already exists after AJAX call: ", val.id);
                            return;
                            }
                            $('#product-list-select').append(
                                '<tr id="tr-select-add' + val.id + '">' +
                                '<td>' + rowNumber + '</td>' +
                                '<td><input type="hidden" class="randomKey" name="randomKey" id="randomKey" value="' + val.Product_ID + '">' + val.Product_ID + '</td>' +
                                '<td style="text-align:left;">' + val.name_en + '</td>' +
                                '<td style="text-align:right;">' + val.unit_name + '</td>' +
                                '<td>' + val.normal_price + '</td>' +
                                '<td><button type="button" class="Btn remove-button" value="' + val.id + '"> <i class="fa fa-minus-circle text-danger fa-lg"></i></button></td>' +
                                '<input type="hidden" id="productselect' + val.id + '" value="' + val.id + '">' +
                                '</tr>'
                            );
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
        function renumberRows() {
            $('#product-list-select tr').each(function(index) {
                $(this).find('td:first-child').text(index+1); // เปลี่ยนเลขลำดับในคอลัมน์แรก
            });
            $('#display-selected-items tr').each(function(index) {
                $(this).find('td:first-child').text(index+1); // เปลี่ยนเลขลำดับในคอลัมน์แรก
            });
        }
        $(document).on('click', '.remove-button', function() {
            console.log(1);
            var product = $(this).val();
            $('#tr-select-add' + product).remove();
            renumberRows(); // ลบแถวที่มี id เป็น 'tr-select-add' + product
        });
        $(document).on('click', '.confirm-button', function() {
            var product = $(this).val();
            var number = $('#randomKey').val();
            console.log(number);
            $.ajax({
                url: '{{ route("Quotation.addProducttablemain", ["Quotation_ID" => ":id"]) }}'.replace(':id', Quotation_ID),
                method: 'GET',
                data: {
                    value: "all"
                },
                success: function(response) {
                    $.each(response.products, function (key, val) {
                        if ($('#productselect' + val.id).val() !== undefined) {
                            if ($('#display-selected-items #tr-select-addmain' + val.id).length === 0) {
                                number += 1;
                                var name = '';
                                var price = 0;
                                var normalPriceString = val.normal_price.replace(/[^0-9.]/g, ''); // ล้างค่าที่ไม่ใช่ตัวเลขและจุดทศนิยม
                                var normalPrice = parseFloat(normalPriceString);
                                var netDiscount = ((normalPrice)).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                var normalPriceview = ((normalPrice)).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                var rowNumbemain = $('#display-selected-items tr').length + 1;
                                let discountInput;
                                var roleMenuDiscount = document.getElementById('roleMenuDiscount').value;
                                var maximum_discount = val.maximum_discount;
                                if (roleMenuDiscount == 1) {
                                    discountInput = '<div class="input-group">' +
                                        '<input class="discountmain form-control" type="text" id="discountmain' + number + '" name="discountmain[]" value="0" min="0" rel="' + number + '" style="text-align:center;" ' +
                                        'oninput="if (parseFloat(this.value) > ' + discountuser + '|| parseFloat(this.value) > ' + val.maximum_discount + ' ) this.value = ' + 0 + ';">' +
                                        '<span class="input-group-text">%</span>' +
                                        '</div>';
                                } else {
                                    discountInput = '<div class="input-group">' +
                                        '<input class="discountmain form-control" type="text" id="discountmain' + number + '" name="discountmain[]" value="0" rel="' + number + '" style="text-align:center;" disabled ' +
                                        'oninput="if (parseFloat(this.value) > ' + val.maximum_discount + ') this.value = ' + val.maximum_discount + ';">' +
                                        '<span class="input-group-text">%</span>' +
                                        '</div>';
                                }
                                $('#display-selected-items').append(
                                    '<tr id="tr-select-addmain' + val.id + '">' +
                                    '<td>' + rowNumbemain + '</td>' +
                                    '<td style="text-align:left;"><input type="hidden" id="Product_ID" name="ProductIDmain[]" value="' + val.Product_ID + '">' + val.name_en + '</td>' +
                                    '<td ><input class="quantitymain form-control" type="text" id="quantitymain' + number + '" name="Quantitymain[]" value="1" min="1" rel="' + number + '" style="text-align:center;"></td>' +
                                    '<td>' + val.unit_name + '</td>' +
                                    '<td><input type="hidden" id="totalprice-unit-' + number + '" name="priceproductmain[]" value="' + val.normal_price + '">' + val.normal_price + '</td>' +
                                    '<td>' + discountInput + '</td>' +
                                    '<td style="text-align:center;"><input type="hidden" id="net_discount-' + number + '" value="' + val.normal_price + '"><span id="netdiscount' + number + '">' + normalPriceview + '</span></td>' +
                                    '<td style="text-align:center;"><input type="hidden" id="allcounttotal-' + number + '" value=" ' + val.normal_price + '"><span id="allcount' + number + '">' + normalPriceview + '</span></td>' +
                                    '<td><button type="button" class="Btn remove-buttonmain" value="' + val.id + '"><i class="fa fa-minus-circle text-danger fa-lg"></i></button></td>' +
                                    '</tr>'
                                );
                            }
                        }
                    });
                    totalAmost();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
            $('#exampleModalproduct').modal('hide');
        });
        $(document).on('click', '.remove-buttonmain', function() {
            var product = $(this).val();
            $('#tr-select-add' + product + ', #tr-select-addmain' + product).remove();

            $('#display-selected-items tbody tr').each(function(index) {
                // เปลี่ยนเลขลำดับใหม่
                $(this).find('td:first').text(index + 1);
            });
            renumberRows();
            totalAmost();// ลบแถวที่มี id เป็น 'tr-select-add' + product
        });
    }
    $(document).on('click', '.remove-button1', function() {
        $(this).closest('tr').remove(); // Remove the row
        $('#display-selected-items tbody tr').each(function(index) {
                // เปลี่ยนเลขลำดับใหม่
                $(this).find('td:first').text(index + 1);
            });
        calculateTotals();
        totalAmost();// Recalculate totals after removing row
    });
    $(document).ready(function() {
        $(document).on('keyup', '.quantitymain', function() {
            var number_ID = $(this).attr('rel');
            var quantitymain =  Number($(this).val());
            var discountmain =  $('#discountmain'+number_ID).val();
            var number = Number($('#number-product').val());
            var price = parseFloat($('#totalprice-unit-'+number_ID).val().replace(/,/g, ''));
            var pricediscount =  (price*discountmain /100);
            var allcount0 = price - pricediscount;
            $('#netdiscount'+number_ID).text(allcount0.toLocaleString('th-TH', {minimumFractionDigits: 2}));
            var pricenew = price*quantitymain
            var pricediscount = pricenew - (pricenew*discountmain /100);
            $('#allcount'+number_ID).text(pricediscount.toLocaleString('th-TH', {minimumFractionDigits: 2}));
            // $('#allcount0'+number_ID).text(allcount0.toLocaleString('th-TH', {minimumFractionDigits: 2}));
            totalAmost();
        });
        $(document).on('keyup', '.discountmain', function() {
            var number_ID = $(this).attr('rel');
            var discountmain =  Number($(this).val());
            var quantitymain =  Number($('.quantitymain').val());
            var number = Number($('#number-product').val());
            var price = parseFloat($('#totalprice-unit-'+number_ID).val().replace(/,/g, ''));
            var pricediscount =  (price*discountmain /100);
            var allcount0 = price - pricediscount;
            $('#netdiscount'+number_ID).text(allcount0.toLocaleString('th-TH', {minimumFractionDigits: 2}));
            var pricenew = price*quantitymain
            var pricediscount = pricenew - (pricenew*discountmain /100);
            $('#allcount'+number_ID).text(pricediscount.toLocaleString('th-TH', {minimumFractionDigits: 2}));
            // $('#allcount0'+number_ID).text(allcount0.toLocaleString('th-TH', {minimumFractionDigits: 2}));
            totalAmost();
        });
    });
    $('#SpecialDis').on('input', function() {
        var specialDisValue = $(this).val();
        var typevat = document.getElementById('vat_type_name').value;
        let allprice = 0;
        let lessDiscount = 0;
        let beforetax =0;
        let addedtax =0;
        let Nettotal =0;
        let totalperson=0;
        $('#display-selected-items tr').each(function() {

            var adultValue = parseFloat(document.getElementById('adult').value);
            var childrenValue = parseFloat(document.getElementById('children').value);
            let priceCell = $(this).find('td').eq(7);
            let pricetotal = parseFloat(priceCell.text().replace(/,/g, '')) || 0;
            var person =adultValue+childrenValue;

            if (typevat == 'PRICE INCLUDE VAT') {
                allprice += pricetotal;
                lessDiscount = allprice-specialDisValue;
                beforetax= lessDiscount/1.07;
                addedtax = lessDiscount-beforetax;
                Nettotal= beforetax+addedtax;
                totalperson = Nettotal/person;
            }
            else if(typevat == 'PRICE EXCLUDE VAT')
            {
                allprice += pricetotal;
                lessDiscount = allprice-specialDisValue;
                beforetax= lessDiscount;
                addedtax =0;
                Nettotal= beforetax;
                totalperson = Nettotal/person;
            } else{
                allprice += pricetotal;
                lessDiscount = allprice-specialDisValue;
                addedtax = lessDiscount*7/100;;
                beforetax= lessDiscount+addedtax;
                Nettotal= beforetax;
                totalperson = Nettotal/person;

            }

        });

        $('#total-amount').text(isNaN(allprice) ? '0' : allprice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        $('#lessDiscount').text(isNaN(lessDiscount) ? '0' : lessDiscount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        $('#Net-price').text(isNaN(beforetax) ? '0' : beforetax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        $('#total-Vat').text(isNaN(addedtax) ? '0' : addedtax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        $('#Net-Total').text(isNaN(Nettotal) ? '0' : Nettotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        $('#Average').text(isNaN(totalperson) ? '0' : totalperson.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
    });
    function totalAmost() {
        $(document).ready(function() {
            var typevat = document.getElementById('vat_type_name').value;
            let allprice = 0;
            let lessDiscount = 0;
            let beforetax =0;
            let addedtax =0;
            let Nettotal =0;
            let totalperson=0;
            let priceArray = [];
            let pricedistotal = [];// เริ่มต้นตัวแปร allprice และ allpricedis ที่นอกลูป
            var specialDisValue = parseFloat(document.getElementById('SpecialDis').value);
            $('#display-selected-items tr').each(function() {
                var adultValue = parseFloat(document.getElementById('adult').value);
                var childrenValue = parseFloat(document.getElementById('children').value);
                let priceCell = $(this).find('td').eq(7);
                let pricetotal = parseFloat(priceCell.text().replace(/,/g, '')) || 0;
                var person =adultValue+childrenValue;

                if (typevat == 'PRICE INCLUDE VAT') {
                    allprice += pricetotal;
                    lessDiscount = allprice-specialDisValue;
                    beforetax= lessDiscount/1.07;
                    addedtax = lessDiscount-beforetax;
                    Nettotal= beforetax+addedtax;
                    totalperson = Nettotal/person;
                }
                else if(typevat == 'PRICE EXCLUDE VAT')
                {
                    allprice += pricetotal;
                    lessDiscount = allprice-specialDisValue;
                    beforetax= lessDiscount;
                    addedtax =0;
                    Nettotal= beforetax;
                    totalperson = Nettotal/person;
                } else{
                    allprice += pricetotal;
                    lessDiscount = allprice-specialDisValue;
                    addedtax = lessDiscount*7/100;;
                    beforetax= lessDiscount+addedtax;
                    Nettotal= beforetax;
                    totalperson = Nettotal/person;
                }
            });
            $('#total-amount').text(isNaN(allprice) ? '0' : allprice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#lessDiscount').text(isNaN(lessDiscount) ? '0' : lessDiscount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#Net-price').text(isNaN(beforetax) ? '0' : beforetax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#total-Vat').text(isNaN(addedtax) ? '0' : addedtax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#Net-Total').text(isNaN(Nettotal) ? '0' : Nettotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#Average').text(isNaN(totalperson) ? '0' : totalperson.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        });
    }
    totalAmost();
</script>
<script>
    function submitPreview() {
        var previewValue = document.getElementById("preview").value;

        // สร้าง input แบบ hidden ใหม่
        var input = document.createElement("input");
        input.type = "hidden";
        input.name = "preview";
        input.value = previewValue;

        // เพิ่ม input ลงในฟอร์ม
        document.getElementById("myForm").appendChild(input);
        document.getElementById("myForm").submit();
    }
</script>
@endsection
