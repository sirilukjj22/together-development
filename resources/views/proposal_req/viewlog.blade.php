@extends('layouts.masterLayout')
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
.select2{
    margin: 4px 0;
    border: 1px solid #ffffff;
    border-radius: 4px;
    box-sizing: border-box;
}
.calendar-icon {
    background: #fff no-repeat center center;
    vertical-align: middle;
    margin-right: 5px;
    transition: background-color 0.3s, transform 0.3s;/* ระยะห่างจากข้อความ */
}
.calendar-icon:hover {
    background: #fff ;
    transform: scale(1.1);
}
.calendar-icon:active {
    transform: scale(0.9);
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
                <small class="text-muted">Welcome to Create Proposal.</small>
                <h1 class="h4 mt-1">Create Proposal (เพิ่มข้อเสนอ)</h1>
            </div>
        </div>
    </div>
@endsection
@section('content')
<form id="myForm" action="{{url('/Dummy/Quotation/edit/company/quotation/update/'.$Quotation->id)}}" method="POST">
@csrf
    <div class="container">
        <div class="container mt-3">
            <div class="row clearfix">
                <div class="col-sm-12 col-12">
                    <div class="card p-4 mb-4">
                        <div class="row">
                            <div class="col-lg-8 col-md-12 col-sm-12 image-container">
                                <img src="{{ asset('assets2/images/logo_crop.png') }}" alt="Together Resort Logo" class="logo"/>
                                <div class="info">
                                    <p class="titleh1">Together Resort Limited Partnership</p>
                                    <p>168 Moo 2 Kaengkrachan Phetchaburi 76170</p>
                                    <p>Tel : 032-708-888, 098-393-944-4 Fax :</p>
                                    <p>Email : reservation@together-resort.com Website : www.together-resort.com</p>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <div class="row">
                                    <b class="titleQuotation" style="font-size: 24px;color:rgba(45, 127, 123, 1);">Proposal</b>
                                    <span class="titleQuotation">{{$QuotationID}}</span>
                                    <input type="hidden" id="Quotationold" name="Quotationold" value="{{$QuotationID}}">
                                    <div  style="background: #fff; cursor: pointer; padding: 5px 10px; width: 100%;" >
                                        <div class="col-12 col-md-12 col-sm-12">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-12 col-sm-12"style="display:flex; justify-content:right; align-items:center;">
                                                    <span>Issue Date:</span>
                                                </div>
                                                <div class="col-lg-6 col-md-12 col-sm-12" id="reportrange1">
                                                    <input type="text" id="datestart" class="form-control" name="IssueDate" style="text-align: left;" value="{{$Quotation->issue_date}}"disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12 col-sm-12 mt-2">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-12 col-sm-12"style="display:flex; justify-content:right; align-items:center;">
                                                    <span>Expiration Date:</span>
                                                </div>
                                                <div class="col-lg-6 col-md-12 col-sm-12">
                                                    <input type="text" id="dateex" class="form-control" name="Expiration" style="text-align: left;"readonly value="{{$Quotation->Expirationdate}}" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="Quotation_ID" name="Quotation_ID" value="{{$QuotationID}}">
                        <div class="row mt-5">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label class="labelcontact" for="">Customer Company</label>
                                <select name="Company" id="Company" class="select2" onchange="companyContact()" disabled>
                                    @foreach($Company as $item)
                                        <option value="{{ $item->Profile_ID }}"{{$Quotation->Company_ID == $item->Profile_ID ? 'selected' : ''}}>{{ $item->Company_Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label class="labelcontact" for="">Customer Contact</label>
                                <input type="text" name="Company_Contact" id="Company_Contact" class="form-control" value="{{$Contact_name->First_name}} {{$Contact_name->Last_name}}" disabled>
                                <input type="hidden" name="Company_Contact" id="Company_Contactname" class="form-control" value="{{$Contact_name->id}}">
                            </div>
                        </div>
                        <hr class="mt-3 my-3" style="border: 1px solid #000">
                        <div class="row mt-2">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <label for="chekin">Check In Date</label>
                                <input type="date" name="Checkin" id="Checkin" class="form-control" onchange="CheckDate()" value="{{$Quotation->checkin}}" disabled>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <label for="chekin">Check Out Date </label>
                                <input type="date" name="Checkout" id="Checkout" class="form-control"onchange="CheckDate()"  value="{{$Quotation->checkout}}"  disabled>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="">จำนวน</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="Day" id="Day" placeholder="จำนวนวัน" value="{{$Quotation->day}}"disabled>
                                    <span class="input-group-text">Day</span>
                                    <input type="text" class="form-control" name="Night" id="Night" placeholder="จำนวนคืน" value="{{$Quotation->night}}"disabled>
                                    <span class="input-group-text">Night</span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="">จำนวนผู้เข้าพัก (ผู้ใหญ่/เด็ก)</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="Adult" id="Adult" placeholder="จำนวนผู้ใหญ่" value="{{$Quotation->adult}}"disabled>
                                    <span class="input-group-text">ผู้ใหญ่</span>
                                    <input type="text" class="form-control" name="Children"id="Children" placeholder="จำนวนเด็ก" value="{{$Quotation->children}}"disabled>
                                    <span class="input-group-text">เด็ก</span>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label  for="">Event Format</label>
                                <select name="Mevent" id="Mevent" class="select2"  onchange="masterevent()" disabled>
                                    <option value=""></option>
                                    @foreach($Mevent as $item)
                                        <option value="{{ $item->id }}"{{$Quotation->eventformat == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label  for="">Vat Type</label>
                                <select name="Mvat" id="Mvat" class="select2"  onchange="mastervat()" disabled>
                                    <option value=""></option>
                                    @foreach($Mvat as $item)
                                        <option value="{{ $item->id }}"{{$Quotation->vat_type == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label class="Freelancer_member" for="">Introduce By</label>
                                <select name="Freelancer_member" id="Freelancer_member" class="select2" disabled>
                                    <option value=""></option>
                                    @foreach($Freelancer_member as $item)
                                        <option value="{{ $item->Profile_ID }}"{{$Quotation->freelanceraiffiliate == $item->Profile_ID ? 'selected' : ''}}>{{ $item->First_name }} {{ $item->Last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label  for="">Company Discount Contract</label>{{--ดึงของcompanyมาใส่--}}
                                <div class="input-group">
                                    <span class="input-group-text">DC</span>
                                    <input type="text" class="form-control" name="Company_Rate_Code" aria-label="Amount (to the nearest dollar)" disabled>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label  for="">Company Commission</label>
                                <div class="input-group">
                                    <input type="text" class="form-control"  name="Company_Commission_Rate_Code" disabled>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label  for="">User discount </label>{{--ดึงของuserมาใส่--}}
                                <div class="input-group">
                                    <input type="text" class="form-control" name="Max_discount"  value="{{@Auth::user()->discount}}" placeholder="ส่วนลดคิดเป็น %" disabled>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label  for="">Special Discount</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="SpecialDiscount" id="SpecialDiscount" placeholder="ส่วนลดคิดเป็น %"  value="{{$Quotation->SpecialDiscount}}"disabled >
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label  for="">Discount Amount</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="DiscountAmount"   placeholder="ส่วนลดคิดเป็นบาท" disabled>
                                    <span class="input-group-text">Bath</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="vat_type" value="{{$Quotation->vat_type}}">
    <input type="hidden" id="Meventcheck" value="{{$Quotation->eventformat}}">
    <div class="container" style="font-size: 16px; font-family: Arial, sans-serif;position: relative;">
        <div class="container mt-3">
            <div class="row clearfix">
                <div class="col-sm-12 col-12">
                    <div class="card p-4 mb-4">
                            <div class="row mt-2">
                                <div class="col-lg-7 col-md-12 col-sm-12" style=" border-right-style: solid  ; border-right-width: 2px;border-right-color:#109699">
                                    <b class="com mt-2 my-2"style="font-size:18px">Company Information</b>
                                    <table>
                                        <tr>
                                            <td style="padding: 10px"><b style="margin-left: 2px; width:30%;font-weight: bold;color:#000;">Company Name :</b></td>
                                            <td>
                                                <span id="Company_name" name="Company_name" >{{$comtypefullname}}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Company Address :</b></td>
                                            <td><span id="Address" >{{$CompanyID->Address}} {{'ตำบล' . $TambonID->name_th}} </span></td>

                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td><span id="Address2" >{{'อำเภอ' .$amphuresID->name_th}} {{'จังหวัด' .$provinceNames->name_th}} {{$TambonID->Zip_Code}}</span></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Company Number :</b></td>
                                            <td>
                                                <span id="Company_Number">{{$company_phone->Phone_number}}</span>
                                                <b style="margin-left: 10px;color:#000;">Company Fax : </b><span id="Company_Fax">{{$company_fax->Fax_number}}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Company Email :</b></td>
                                            <td><span id="Company_Email">{{$CompanyID->Company_Email}}</span></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Taxpayer Identification : </b></td>
                                            <td><span id="Taxpayer">{{$CompanyID->Taxpayer_Identification}}</span></td>
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
                                            <p style="display: inline-block;"><span id="Company_contact">{{$Contact_name->First_name}} {{$Contact_name->Last_name}}</span></p>
                                        </div>
                                        <div class="col-6">
                                            <p style="display: inline-block;font-weight: bold;">Contact Number :</p>
                                            <p style="display: inline-block;"><span id="Contact_Phone">{{$Contact_phone->Phone_number}}</span></p>
                                        </div>
                                        <div>
                                            <p style="display: inline-block;font-weight: bold;margin-left: 10px;">Contact Email :</p>
                                            <p style="display: inline-block;"><span id="Contact_Email">{{$Contact_name->Email}}</span></p>
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
                                            <p style="display: inline-block;"><span id="checkinpo">{{$Quotation->checkin}}</span></p><br>
                                            <p style="display: inline-block;"><span id="checkoutpo">{{$Quotation->checkout}}</span></p><br>
                                            <p style="display: inline-block;"><span id="daypo">{{$Quotation->day}}</span> วัน <span id="nightpo">{{$Quotation->night}}</span> คืน</p><br>
                                            <p style="display: inline-block;"><span id="Adultpo">{{$Quotation->adult}}</span> Adult , <span id="Childrenpo">{{$Quotation->children}}</span> Children</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="styled-hr"></div>
                            </div>
                            <div class="mt-2">
                                <strong>ขอเสนอราคาและเงื่อนไขสำหรับท่าน ดังนี้ <br> We are pleased to submit you the following desctibed here in as price,items and terms stated :</strong>
                            </div>
                            <div class="row mt-2">
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
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:10%;text-align:center">Quantity</th>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">Unit</th>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center">Price / Unit</th>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:10%;text-align:center">Discount</th>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center">Price Discount</th>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center">Amount</th>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center">Order</th>
                                        </tr>
                                    </thead>
                                    <tbody id="display-selected-items">
                                        @if (!empty($selectproduct))
                                            @foreach ($selectproduct as $key => $item)
                                                @foreach ($unit as $singleUnit)
                                                    @if($singleUnit->id == @$item->product->unit)
                                                        @php
                                                        $var = $key+1;
                                                        @endphp
                                                        <tr id="tr-select-main{{$item->Product_ID}}">
                                                            <input type="hidden" id="tr-select-main{{$item->Product_ID}}" name="tr-select-main[]" value="{{$item->Product_ID}}">
                                                            <td><input type="hidden" id="ProductID" name="ProductIDmain[]" value="{{$item->Product_ID}}">{{$key+1}}</td>
                                                            <td style="text-align:left;"><input type="hidden" id="Productname_th" name="Productname_th" value="{{@$item->product->name_th}}">{{@$item->product->name_th}}</td>
                                                            <td class="Quantity" data-value="{{$item->Quantity}}" style="text-align:center;">
                                                                <input type="text" id="quantity{{$var}}" name="Quantitymain[]" rel="{{$var}}" style="text-align:center;"class="quantity-input form-control" value="{{$item->Quantity}}" disabled>
                                                            </td>
                                                            <td><input type="hidden" id="unitname_th" name="unitname_th" value="{{ $singleUnit->name_th }}">{{ $singleUnit->name_th }}</td>
                                                            <td class="priceproduct" data-value="{{$item->priceproduct}}"><input type="hidden" id="totalprice-unit{{$var}}" name="priceproductmain[]" value="{{$item->priceproduct}}">{{ number_format($item->priceproduct, 2, '.', ',') }}</td>
                                                            <td class="discount"style="text-align:center;">
                                                                <div class="input-group">
                                                                    <input type="text" id="discount{{$var}}" name="discountmain[]" rel="{{$var}}"style="text-align:center;" class="discount-input form-control" value="{{$item->discount}}"disabled>
                                                                    <input type="hidden" id="maxdiscount{{$var}}" name="maxdiscount[]" rel="{{$var}}" class=" form-control" value="{{$item->product->maximum_discount}}">
                                                                    <span class="input-group-text">%</span>
                                                                </div>
                                                            </td>
                                                            <td class="net-price"style="text-align:center;" ><span id="net_discount{{$var}}">{{ number_format($item->netpriceproduct, 2, '.', ',') }}</span></td>
                                                            <td class="item-total"style="text-align:center;"><span id="all-total{{$var}}">{{ number_format($item->netpriceproduct, 2, '.', ',') }}</span></td>
                                                            <td>
                                                                <button type="button" class="Btn remove-button1"  id="remove-button1{{$var}}" value="{{$item->Product_ID}}">
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
                            @if (@Auth::user()->roleMenuDiscount('Proposal',Auth::user()->id) == 1)
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
                                    <table class="table table-borderless" id="PRICE_INCLUDE_VAT" style="display: none;">
                                        <tbody>
                                            <tr >
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Subtotal</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="total-amount">0</span></td>
                                            </tr>
                                            <tr>
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Special Discount</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;">
                                                    <input type="text" id="SpecialDis" name="SpecialDis" class="form-control" value="0" disabled>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Subtotal less Discount</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="lessDiscount">0</span></td>
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
                                    <table class="table table-borderless" id="PRICE_EXCLUDE_VAT" style="display: none;">
                                        <tbody>
                                            <tr >
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Subtotal</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="total-amountEXCLUDE">0</span></td>
                                            </tr>
                                            <tr>
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Special Discount</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;">
                                                    <input type="text" id="SpecialDis" name="SpecialDis" class="form-control" value="0" disabled>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Subtotal less Discount</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="lessDiscountEXCLUDE">0</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-borderless "id="PRICE_PLUS_VAT" style="display: none;">
                                        <tbody>
                                            <tr >
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Subtotal</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="total-amountpus">0</span></td>
                                            </tr>
                                            <tr>
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Special Discount</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;">
                                                    <input type="text" id="SpecialDis" name="SpecialDis" class="form-control" value="0" disabled>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Subtotal less Discount</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="lessDiscountpus">0</span></td>
                                            </tr>
                                            <tr>
                                                <td scope="row" style="text-align:right;width: 55%;font-size: 14px;"><b>Value Added Tax</b></td>
                                                <td style="text-align:left;width: 45%;font-size: 14px;"><span id="total-Vatpus">0</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <input type="hidden" id="SpecialDischeck" name="SpecialDischeck" class="form-control" >
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
                                <span class="col-md-8 col-sm-12"id="Payment50" style="display: block" >
                                    Please make a 50% deposit within 7 days after confirmed. <br>
                                    Transfer to <strong> " Together Resort Limited Partnboership "</strong> following banks details.<br>
                                    If you use transfer, Please inform Accounting / Finance Department Tel or LINE ID<span style="font-size: 18px"> @Together-resort</span><br>
                                    pay-in slip to number 032-708-888 every time for the correctness of payment allocation.<br>
                                </span>
                                <span class="col-md-8 col-sm-12"  id="Payment100" style="display: none">
                                    Please make a 100% deposit within 3 days after confirmed. <br>
                                    Transfer to <strong> " Together Resort Limited Partnboership "</strong> following banks details.<br>
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

                                            @endphp
                                            <div class="mt-3">
                                                {!! QrCode::size(90)->generate('No found'); !!}
                                            </div>
                                        </div>
                                        <div class="col-lg-2 centered-content">
                                            <span>ผู้ออกเอกสาร (ผู้ขาย)</span><br>
                                            <br><br>
                                            <span>{{@Auth::user()->name}}</span>
                                            <span id="issue_date_document"></span>
                                        </div>
                                        <div class="col-lg-2 centered-content">
                                            <span>ผู้อนุมัติเอกสาร (ผู้ขาย)</span><br>
                                            <br><br>
                                            <span>{{@Auth::user()->name}}</span>
                                            <span id="issue_date_document1"></span>
                                        </div>
                                        <div class="col-lg-2 centered-content">
                                            <span>ตราประทับ (ผู้ขาย)</span>
                                        </div>
                                        <div class="col-lg-2 centered-content">
                                            <span>ผู้รับเอกสาร (ลูกค้า)</span>
                                            <br><br><br>
                                            ______________________
                                            <span>_____/__________/_____</span>
                                        </div>
                                        <div class="col-lg-2 centered-content">
                                            <span >ตราประทับ (ลูกค้า)</span>
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
                                    <button type="button" class="btn btn-secondary lift btn_modal btn-space"  onclick="window.location.href='{{ route('ProposalReq.index') }}'">
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
</form>
<input type="hidden" name="preview" value="1" id="preview">
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="{{ asset('assets/js/daterangepicker.min.js')}}" defer></script>
<script type="text/javascript" src="{{ asset('assets/js/moment.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css')}}" />
<script>
    $(document).ready(function() {
        var typevat  = $('#Mvat').val();
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
            var adultValue = parseFloat(document.getElementById('Adult').value);
            var childrenValue = parseFloat(document.getElementById('Children').value);
            let priceCell = $(this).find('td').eq(7);
            let pricetotal = parseFloat(priceCell.text().replace(/,/g, '')) || 0;
            var person =adultValue+childrenValue;
            if (typevat == '50') {
                allprice += pricetotal;
                lessDiscount = allprice-specialDisValue;
                beforetax= lessDiscount/1.07;
                addedtax = lessDiscount-beforetax;
                Nettotal= beforetax+addedtax;
                totalperson = Nettotal/person;
                $('#total-amount').text(isNaN(allprice) ? '0' : allprice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#lessDiscount').text(isNaN(lessDiscount) ? '0' : lessDiscount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#Net-price').text(isNaN(beforetax) ? '0' : beforetax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#total-Vat').text(isNaN(addedtax) ? '0' : addedtax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#Net-Total').text(isNaN(Nettotal) ? '0' : Nettotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#Average').text(isNaN(totalperson) ? '0' : totalperson.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            }
            else if(typevat == '51')
            {
                allprice += pricetotal;
                lessDiscount = allprice-specialDisValue;
                beforetax= lessDiscount;
                addedtax =0;
                Nettotal= beforetax;
                totalperson = Nettotal/person;
                $('#total-amountEXCLUDE').text(isNaN(allprice) ? '0' : allprice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#lessDiscountEXCLUDE').text(isNaN(lessDiscount) ? '0' : lessDiscount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#Net-priceEXCLUDE').text(isNaN(beforetax) ? '0' : beforetax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#total-VatEXCLUDE').text(isNaN(addedtax) ? '0' : addedtax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#Net-Total').text(isNaN(Nettotal) ? '0' : Nettotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#Average').text(isNaN(totalperson) ? '0' : totalperson.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            } else if(typevat == '52'){
                allprice += pricetotal;
                lessDiscount = allprice-specialDisValue;
                addedtax = lessDiscount*7/100;;
                beforetax= lessDiscount+addedtax;
                Nettotal= beforetax;
                totalperson = Nettotal/person;
                $('#total-amountpus').text(isNaN(allprice) ? '0' : allprice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#lessDiscountpus').text(isNaN(lessDiscount) ? '0' : lessDiscount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#Net-pricepus').text(isNaN(beforetax) ? '0' : beforetax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#total-Vatpus').text(isNaN(addedtax) ? '0' : addedtax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#Net-Total').text(isNaN(Nettotal) ? '0' : Nettotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                $('#Average').text(isNaN(totalperson) ? '0' : totalperson.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            }
        });
        function masterevent() {
        var Mevent =$('#Mevent').val();
        if (Mevent == '43') {

            $('#Payment50').css('display', 'block');
            $('#Payment100').css('display', 'none');
        } else if (Mevent == '53') {

            $('#Payment50').css('display', 'none');
            $('#Payment100').css('display', 'block');
        } else {
            $('#Payment50').css('display', 'none');
            $('#Payment100').css('display', 'none');
        }
    }
    function mastervat() {
        var Mvat =$('#Mvat').val();
        if (Mvat == '50') {
            $('#PRICE_INCLUDE_VAT').css('display', 'block');
            $('#PRICE_EXCLUDE_VAT').css('display', 'none');
            $('#PRICE_PLUS_VAT').css('display', 'none');
        }else if (Mvat == '51') {
            $('#PRICE_INCLUDE_VAT').css('display', 'none');
            $('#PRICE_EXCLUDE_VAT').css('display', 'block');
            $('#PRICE_PLUS_VAT').css('display', 'none');
        }
        else if (Mvat == '52') {
            $('#PRICE_INCLUDE_VAT').css('display', 'none');
            $('#PRICE_EXCLUDE_VAT').css('display', 'none');
            $('#PRICE_PLUS_VAT').css('display', 'block');
        }else{
            $('#PRICE_INCLUDE_VAT').css('display', 'none');
            $('#PRICE_EXCLUDE_VAT').css('display', 'none');
            $('#PRICE_PLUS_VAT').css('display', 'none');
        }
        totalAmost()
    }
    });
    $(document).ready(function() {
        var Mvat ={{$Quotation->vat_type}};
        if (Mvat == '50') {
            $('#PRICE_INCLUDE_VAT').css('display', 'block');
            $('#PRICE_EXCLUDE_VAT').css('display', 'none');
            $('#PRICE_PLUS_VAT').css('display', 'none');
        }else if (Mvat == '51') {
            $('#PRICE_INCLUDE_VAT').css('display', 'none');
            $('#PRICE_EXCLUDE_VAT').css('display', 'block');
            $('#PRICE_PLUS_VAT').css('display', 'none');
        }
        else if (Mvat == '52') {
            $('#PRICE_INCLUDE_VAT').css('display', 'none');
            $('#PRICE_EXCLUDE_VAT').css('display', 'none');
            $('#PRICE_PLUS_VAT').css('display', 'block');
        }else{
            $('#PRICE_INCLUDE_VAT').css('display', 'none');
            $('#PRICE_EXCLUDE_VAT').css('display', 'none');
            $('#PRICE_PLUS_VAT').css('display', 'none');
        }
        var Mevent ={{$Quotation->eventformat}};
        if (Mevent == '43') {

            $('#Payment50').css('display', 'block');
            $('#Payment100').css('display', 'none');
        } else if (Mevent == '53') {

            $('#Payment50').css('display', 'none');
            $('#Payment100').css('display', 'block');
        } else {
            $('#Payment50').css('display', 'none');
            $('#Payment100').css('display', 'none');
        }
    });
</script>
@endsection
