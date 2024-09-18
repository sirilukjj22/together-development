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
    .dataTables_empty {
    display: none; /* ซ่อนข้อความ */
    /* หรือสามารถปรับแต่งสไตล์อื่น ๆ ได้ที่นี่ */
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
        display: inline; /* ทำให้เส้นมีความยาวตามข้อความ */
        border-bottom: 2px solid #2D7F7B;
        padding-bottom: 5px;
        font-size: 20px;
        width: fit-content;
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
    .pagination-container {
    display: flex;
    justify-content: center;
    align-items: center;
    }

    .paginate-btn {
        border: 1px solid #2D7F7B;
        background-color: white;
        color: #2D7F7B;
        padding: 8px 16px;
        margin: 0 2px;
        border-radius: 4px;
        cursor: pointer;
    }

    .paginate-btn.active, .paginate-btn:hover {
        background-color: #2D7F7B;
        color: white;
    }

    .paginate-btn:disabled {
        cursor: not-allowed;
        opacity: 0.5;
    }
    div.PROPOSAL {
        padding: 10px ;
        border: 3px solid #2D7F7B;
        border-radius: 10px;
        background-color: #109699;
    }
    div.PROPOSALfirst {
        padding: 10px ;
        border: 3px solid #2D7F7B;
        border-radius: 10px;
        background-color: #109699;
    }
    .readonly-input {
        background-color: #ffffff !important;/* สีพื้นหลังขาว */
    }

    .readonly-input:focus {
        background-color: #ffffff !important;/* ให้สีพื้นหลังขาวเมื่ออยู่ในสถานะโฟกัส */
        box-shadow: none; /* ลบเงาเมื่อโฟกัส */
        border-color: #ced4da; /* ให้เส้นขอบมีสีเทาอ่อนเพื่อให้เหมือนกับการไม่ได้โฟกัส */
    }
    .disabled-input {
        background-color: #E8ECEF !important; /* Light gray background */
        color: #6c757d; /* Gray text color */
        border-color: #ced4da; /* Gray border */
        cursor: not-allowed; /* Change cursor to indicate disabled state */
    }

    /* Style for enabled inputs */
    .table-custom-borderless {
        border-collapse: collapse;
    }

    .table-custom-borderless th,
    .table-custom-borderless td {
        border: none !important;
    }
</style>
@section('content')
    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <small class="text-muted">Welcome to Create Proposal.</small>
                    <div class=""><span class="span1">Create Proposal (เพิ่มเอกสารใบข้อเสนอ)</span></div>
                </div>
            </div> <!-- .row end -->
        </div>
    </div>
    <form id="myForm" action="{{route('Proposal.save')}}" method="POST">
    @csrf
        <div id="content-index" class="body d-flex py-lg-4 py-3">
            <div class="container-xl">
                <div class="row clearfix">
                    <div class="col-md-12 col-12">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-8 col-md-12 col-sm-12 image-container">
                                        <img src="{{ asset('assets2/images/' . $settingCompany->image) }}" alt="Together Resort Logo" class="logo"/>
                                        <div class="info">
                                            <p class="titleh1">{{$settingCompany->name}}</p>
                                            <p>{{$settingCompany->address}}</p>
                                            <p>Tel : {{$settingCompany->tel}}
                                                @if ($settingCompany->fax)
                                                    Fax : {{$settingCompany->fax}}
                                                @endif
                                            </p>
                                            <p>Email : {{$settingCompany->email}} Website : {{$settingCompany->web}}</p>
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-12 col-sm-12">
                                        <div class="row">
                                            <div class="col-lg-4"></div>
                                            <div class="PROPOSAL col-lg-7" style="margin-left: 5px">
                                                <div class="row">
                                                    <b class="titleQuotation" style="font-size: 24px;color:rgb(255, 255, 255);">Proposal</b>
                                                    <b  class="titleQuotation" style="font-size: 16px;color:rgb(255, 255, 255);">{{$Quotation_ID}}</b>
                                                </div>
                                                <input type="hidden" id="Quotation_ID" name="Quotation_ID" value="{{$Quotation_ID}}">
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-lg-4"></div>
                                            <div class="PROPOSALfirst col-lg-7" style="background-color: #ffffff;">
                                                <div class="col-12 col-md-12 col-sm-12">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-12 col-sm-12"style="display:flex; justify-content:right; align-items:center;">
                                                            <span>Issue Date:</span>
                                                        </div>
                                                        <div class="col-lg-6 col-md-12 col-sm-12" id="reportrange1">
                                                            <input type="text" id="datestart" class="form-control readonly-input" name="IssueDate" style="text-align: left;"readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-12 col-sm-12 mt-2">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-12 col-sm-12"style="display:flex; justify-content:right; align-items:center;">
                                                            <span>Expiration Date:</span>
                                                        </div>
                                                        <div class="col-lg-6 col-md-12 col-sm-12">
                                                            <input type="text" id="dateex" class="form-control readonly-input" name="Expiration" style="text-align: left;"readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="Quotation_ID" name="Quotation_ID" value="{{$Quotation_ID}}">
                                <div class="row mt-5">
                                    <div class="col-lg-3 col-md-3 col-sm-12">
                                        <select name="selectdata" id="select" class="select2" onchange="showselectInput()">
                                            <option value="Company">นามบริษัท</option>
                                            <option value="Guest">นามบุคคล</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="Companyshow" style="display: block">
                                    <div class="row mt-2" >
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <label class="labelcontact" for="">Customer Company</label>
                                            <button style="float: right;" type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ route('Company','index') }}'">
                                                <i class="fa fa-plus"></i> เพิ่มบริษัท</button>
                                            <select name="Company" id="Company" class="select2" onchange="companyContact()" required>
                                                <option value=""></option>
                                                @foreach($Company as $item)
                                                    <option value="{{ $item->Profile_ID }}">{{ $item->Company_Name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <label class="labelcontact" for="">Customer Contact</label>
                                            <button style="float: right; border: none; background-color: transparent;color:#fff;" type="button" class="btn" disabled>0</button>
                                            <input type="text" name="Company_Contact" id="Company_Contact" class="form-control">
                                            <input type="hidden" name="Company_Contact" id="Company_Contactname" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div id="Guestshow" style="display: none">
                                    <div class="row mt-2" >
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <label class="labelcontact" for="">Customer Guest </label>
                                            <button style="float: right" type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ route('guest','index') }}'"><i class="fa fa-plus"></i> เพิ่มลูกค้า</button>
                                            <select name="Guest" id="Guest" class="select2" onchange="GuestContact()" required>
                                                <option value=""></option>
                                                @foreach($Guest as $item)
                                                    <option value="{{ $item->Profile_ID }}">{{ $item->First_name }} {{$item->Last_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <hr class="mt-3 my-3" style="border: 1px solid #000">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" > No Check In Date</label>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <span for="chekin">Check In Date
                                        <input type="text" name="Checkin" id="Checkin" class="form-control readonly-input" readonly onchange="CheckDate()" required>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <span for="chekin">Check Out Date </span>
                                        <input type="text" name="Checkout" id="Checkout" class="form-control readonly-input" onchange="CheckDate()"  readonly required>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <span for="">จำนวน</span>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="Day" id="Day" placeholder="จำนวนวัน">
                                            <span class="input-group-text">Day</span>
                                            <input type="text" class="form-control" name="Night" id="Night" placeholder="จำนวนคืน">
                                            <span class="input-group-text">Night</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <span for="">จำนวนผู้เข้าพัก (ผู้ใหญ่/เด็ก)</span>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="Adult" id="Adult" placeholder="จำนวนผู้ใหญ่">
                                            <span class="input-group-text">ผู้ใหญ่</span>
                                            <input type="text" class="form-control" name="Children"id="Children" placeholder="จำนวนเด็ก">
                                            <span class="input-group-text">เด็ก</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <span  for="">Event</span>
                                        <select name="Mevent" id="Mevent" class="select2"  onchange="masterevent()" required>
                                            <option value=""></option>
                                            @foreach($Mevent as $item)
                                                <option value="{{ $item->id }}"{{$item->lavel == 1 ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <span  for="">Vat Type</span>
                                        <select name="Mvat" id="Mvat" class="select2"  onchange="mastervat()" required>
                                            @foreach($Mvat as $item)
                                                <option value="{{ $item->id }}"{{$item->lavel == 1 ? 'selected' : ''}}>{{ $item->name_th }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <span class="Freelancer_member" for="">Introduce By</span>
                                        <select name="Freelancer_member" id="Freelancer_member" class="select2" required disabled>
                                            <option value=""></option>
                                            @foreach($Freelancer_member as $item)
                                                <option value="{{ $item->Profile_ID }}">{{ $item->First_name }} {{ $item->Last_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <span  for="">Company Discount Contract</span>{{--ดึงของcompanyมาใส่--}}
                                        <div class="input-group">
                                            <span class="input-group-text">DC</span>
                                            <input type="text" class="form-control" name="Company_Discount" id="Company_Discount" aria-label="Amount (to the nearest dollar)" disabled>
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <span  for="">Company Commission</span>
                                        <div class="input-group">
                                            <input type="text" class="form-control"  name="Company_Commission_Rate_Code" disabled>
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <span  for="">User Discount </span>{{--ดึงของuserมาใส่--}}
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="Max_discount"  value="{{@Auth::user()->discount}}" placeholder="ส่วนลดคิดเป็น %" disabled>
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <span  for="">Special Discount</span>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="SpecialDiscount" id="SpecialDiscount"  placeholder="ส่วนลดคิดเป็น %" required>
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <span  for="">Discount Amount</span>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="DiscountAmount" id="DiscountAmount"  placeholder="ส่วนลดคิดเป็นบาท" required>
                                            <span class="input-group-text">Bath</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-xl">
                <div class="row clearfix">
                    <div class="col-md-12 col-12">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row mt-2">
                                    <div class="col-lg-7 col-md-12 col-sm-12" style=" border-right-style: solid  ; border-right-width: 2px;border-right-color:#109699">
                                        <b id="TiTlecompanyTable" class="com mt-2 my-2"style="font-size:18px">Company Information</b>
                                        <table id="companyTable">
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px; width:30%;font-weight: bold;color:#000;">Company Name :</b></td>
                                                <td>
                                                    <span id="Company_name" name="Company_name" ></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Company Address :</b></td>
                                                <td><span id="Address" ></span></td>

                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td><span id="Address2" ></span></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Company Number :</b></td>
                                                <td>
                                                    <span id="Company_Number"></span>
                                                    <b style="margin-left: 10px;color:#000;">Company Fax : </b><span id="Company_Fax"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Company Email :</b></td>
                                                <td><span id="Company_Email"></span></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Taxpayer Identification : </b></td>
                                                <td><span id="Taxpayer"></span></td>
                                            </tr>
                                        </table>
                                        <b id="TiTlecontractTable" class="com mt-2 my-2"style="font-size:18px">Personal Information</b>
                                        <table id="contractTable">
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Contact Name :</b></td>
                                                <td>
                                                    <span id="Company_contact"></span>
                                                    <b style="margin-left: 10px;color:#000;">Contact Number : </b><span id="Contact_Phone"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Contact Email : </b></td>
                                                <td><span id="Contact_Email"></span></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px;color:#fff;">Taxpayer Identification : </b></td>
                                                <td style="color: #fff"><span id="Taxpayer"></span></td>
                                            </tr>
                                        </table>
                                        <b id="TiTleguestTable" class="com mt-2 my-2"style="font-size:18px;display: none">Guest Information</b>
                                        <table id="guestTable" style="display: none">
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px; width:30%;font-weight: bold;color:#000;">Guest Name :</b></td>
                                                <td>
                                                    <span id="guest_name" name="guest_name" ></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Guest Address :</b></td>
                                                <td><span id="guestAddress" ></span></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td><span id="guestAddress2" ></span></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Guest Number :</b></td>
                                                <td>
                                                    <span id="guest_Number"></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Guest Email :</b></td>
                                                <td><span id="guest_Email"></span></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Identification Number : </b></td>
                                                <td><span id="guestTaxpayer"></span></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-lg-4 col-md-12 col-sm-12">
                                        <div><br><br><br><br></div>
                                        <div class="col-12 row" >
                                            <div class="col-lg-6">
                                                <p style="display: inline-block;font-weight: bold;font-size:16px">Check In :</p><br>
                                                <p style="display: inline-block;font-weight: bold;font-size:16px">Check Out :</p><br>
                                                <p style="display: inline-block;font-weight: bold;font-size:16px">Length of Stay :</p><br>
                                                <p style="display: inline-block;font-weight: bold;font-size:16px">Number of Guests :</p>
                                            </div>
                                            <div class="col-lg-6 mt-2">
                                                <p style="display: inline-block;"><span id="checkinpo" style="font-size:16px"></span></p><br>
                                                <p style="display: inline-block;"><span id="checkoutpo"style="font-size:16px"></span></p><br>
                                                <p style="display: inline-block;"><span id="daypo"style="font-size:16px"></span> <span id="nightpo"style="font-size:16px"></span></p><br>
                                                <p style="display: inline-block;"><span id="Adultpo"style="font-size:16px"></span><span id="Childrenpo"style="font-size:16px"></span></p>
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
                                    <div class="modal fade " id="exampleModalproduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header btn-color-green ">
                                                <h5 class="modal-title text-white" id="exampleModalLabel">Product</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="col-12 mt-3">
                                                    <div class="dropdown">
                                                        <button class="btn btn-outline-dark lift dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <span id="ProductName">ประเภท Product</span>
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
                                                <div class="col-12 mt-3" >
                                                    <h3>รายการที่เลือก</h3>
                                                    <table  class=" example4 ui striped table nowrap unstackable hover">
                                                        <thead >
                                                            <tr>
                                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 7%">#</th>
                                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 10%">รหัส</th>
                                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">รายการ</th>
                                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 11%">ราคา</th>
                                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 11%">หน่วย</th>
                                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 11%">คำสั่ง</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="product-list-select">

                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-12 mt-3">
                                                    <table id="mainselect1"class="example ui striped table nowrap unstackable hover">
                                                        <thead>
                                                            <tr>
                                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 10%"data-priority="1">#</th>
                                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 10%">รหัส</th>
                                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;"data-priority="1">รายการ</th>
                                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 10%"data-priority="1">ราคา</th>
                                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 10%">หน่วย</th>
                                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 5%"data-priority="1">คำสั่ง</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="product-list">
                                                        </tbody>
                                                    </table>
                                                    <div id="paginationContainer" class="pagination-container">
                                                        <button class="paginate-btn" data-page="prev">&laquo;</button>
                                                        <!-- ปุ่ม pagination จะถูกแทรกที่นี่ -->
                                                        <button class="paginate-btn" data-page="next">&raquo;</button>
                                                    </div>

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
                                <div  class=" mt-2">
                                    <table id="main" class=" example2 ui striped table nowrap unstackable " style="width:100%">
                                        <thead >
                                            <tr>
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;">No.</th>
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;"data-priority="1">Description</th>
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:1%;"></th>
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:10%;text-align:center">Quantity</th>
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">Unit</th>
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center">Price / Unit</th>
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:10%;text-align:center">Discount</th>
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center">Net Price / Unit</th>
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center"data-priority="1">Amount</th>
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center">Order</th>
                                            </tr>
                                        </thead>
                                        <tbody id="display-selected-items">

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
                                    <div class="col-lg-9 col-md-9 col-sm-12 mt-2" >
                                        <span >Notes or Special Comment</span>
                                        <textarea class="form-control mt-2"cols="30" rows="5"name="comment" id="comment" placeholder="Leave a comment here" id="floatingTextarea"></textarea>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12 " >
                                        <table class="table table-custom-borderless" id="PRICE_INCLUDE_VAT" style="display: none;">
                                            <tbody>
                                                <tr >
                                                    <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Subtotal</b></td>
                                                    <td style="text-align:left;width: 45%;font-size: 14px;"><span id="total-amount">0</span></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Special Discount</b></td>
                                                    <td style="text-align:left;width: 45%;font-size: 14px;">
                                                        <span id="sp">0</span>
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
                                        <table class="table table-custom-borderless" id="PRICE_EXCLUDE_VAT" style="display: none;">
                                            <tbody>
                                                <tr >
                                                    <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Subtotal</b></td>
                                                    <td style="text-align:left;width: 45%;font-size: 14px;"><span id="total-amountEXCLUDE">0</span></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Special Discount</b></td>
                                                    <td style="text-align:left;width: 45%;font-size: 14px;">
                                                        <span id="spEXCLUDE">0</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Subtotal less Discount</b></td>
                                                    <td style="text-align:left;width: 45%;font-size: 14px;"><span id="lessDiscountEXCLUDE">0</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table class="table table-custom-borderless "id="PRICE_PLUS_VAT" style="display: none;">
                                            <tbody>
                                                <tr >
                                                    <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Subtotal</b></td>
                                                    <td style="text-align:left;width: 45%;font-size: 14px;"><span id="total-amountpus">0</span></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row"style="text-align:right;width: 55%;font-size: 14px;"><b>Special Discount</b></td>
                                                    <td style="text-align:left;width: 45%;font-size: 14px;">
                                                        <span id="sppus">0</span>
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
                                <div class="col-12 row">
                                    <div class="col-9"></div>
                                    <div class="col-lg-3 col-md-3 col-sm-12">
                                        <table class="table table-custom-borderless" >
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
                                    <div class="col-9"></div>
                                    <div class="col-3 styled-hr"></div>
                                </div>
                                <div class="col-12 row">
                                    <div class="col-9">
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12" id="Pax" style="display: block">
                                        <table class="table table-custom-borderless" >
                                            <tbody>
                                                <tr>
                                                    <td style="text-align:right;width: 55%;font-size: 14px;"><b>Number of Guests :</b></td>
                                                    <td style="text-align:left;width: 45%;font-size: 14px;"><span id="PaxToTal">0</span> Adults
                                                        <input type="hidden" name="PaxToTalall" id="PaxToTalall">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align:right;width: 55%;font-size: 14px;"><b>Average per person :</b></td>
                                                    <td style="text-align:left;width: 45%;font-size: 14px;"><span id="Average">0</span> THB</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-12 mt-3">
                                    <div class="col-lg-4 col-md-6 col-sm-12 my-2">
                                        <strong class="com " style="font-size: 18px">Method of Payment</strong>
                                    </div>
                                    <label class="col-md-8 col-sm-12"id="Payment50" style="display: block" >
                                        Please make a 50% deposit within 7 days after confirmed. <br>
                                        Transfer to <strong> " Together Resort Limited Partnership "</strong> following banks details.<br>
                                        If you use transfer, Please inform Accounting / Finance Department Tel or LINE ID<span style="font-size: 18px"> @Together-resort</span><br>
                                        pay-in slip to number 032-708-888 every time for the correctness of payment allocation.<br>
                                    </label>
                                    <label class="col-md-8 col-sm-12"  id="Payment100" style="display: none">
                                        Please make a 100% deposit within 3 days after confirmed. <br>
                                        Transfer to <strong> " Together Resort Limited Partnership "</strong> following banks details.<br>
                                        If you use transfer, Please inform Accounting / Finance Department Tel or LINE ID<span style="font-size: 18px"> @Together-resort</span><br>
                                        pay-in slip to number 032-708-888 every time for the correctness of payment allocation.<br>
                                    </label>
                                    <div class="row">
                                        <div class="col-lg-8 col-md-6 col-sm-12">
                                            <div class="col-12  mt-2">
                                                <div class="row">
                                                    <div class="col-2 mt-3" style="display: flex;justify-content: center;align-items: center;">
                                                        <img src="{{ asset('/image/bank/SCB.jpg') }}" style="width: 60%;border-radius: 50%;"/>
                                                    </div>
                                                    <div class="col-10 mt-2">
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
                                        <button type="button" class="btn btn-secondary lift btn_modal btn-space" onclick="BACKtoEdit()">
                                            Cancel
                                        </button>
                                        <button type="button" class="btn btn-primary lift btn_modal btn-space" onclick="submitPreview()">
                                            Preview
                                        </button>
                                        <button type="submit" class="btn btn-color-green lift btn_modal" onclick="confirmSubmit(event)">Save</button>
                                    </div>
                                    <div class="col-4"></div>
                                </div>
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Please select an option"
            });
        });
        $(function() {
            // ฟอร์แมตวันที่ให้อยู่ในรูปแบบ dd/mm/yyyy
            $('#Checkin').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                locale: {
                    format: 'DD/MM/YYYY' // ฟอร์แมตเป็น dd/mm/yyyy
                }
            });
            $('#Checkin').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY'));
                CheckDate();
            });

        });
        $(function() {
            $('#Checkout').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                locale: {
                    format: 'DD/MM/YYYY' // ฟอร์แมตเป็น dd/mm/yyyy
                }
            });
            $('#Checkout').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY'));
                CheckDate();
            });

        });
        function CheckDate() {
            const checkinDateValue = moment(document.getElementById('Checkin').value, 'DD/MM/YYYY').format('YYYY-MM-DD');
            const checkoutDateValue = moment(document.getElementById('Checkout').value, 'DD/MM/YYYY').format('YYYY-MM-DD');
            const checkinDate = new Date(checkinDateValue);
            const checkoutDate = new Date(checkoutDateValue);
            if (checkoutDate > checkinDate) {
                const timeDiff = checkoutDate - checkinDate;
                const diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
                const totalDays = diffDays + 1; // รวม Check-in เป็นวันแรก
                const nights = diffDays;

                $('#Day').val(isNaN(totalDays) ? '0' : totalDays);
                $('#Night').val(isNaN(nights) ? '0' : nights);

                $('#checkinpo').text(moment(checkinDateValue).format('DD/MM/YYYY'));
                $('#checkoutpo').text(moment(checkoutDateValue).format('DD/MM/YYYY'));
                $('#daypo').text(totalDays + ' วัน');
                $('#nightpo').text(nights + ' คืน');
            } else if (checkoutDate.getTime() === checkinDate.getTime()) {
                const totalDays = 1;
                $('#Day').val(isNaN(totalDays) ? '0' : totalDays);
                $('#Night').val('0');

                $('#checkinpo').text(moment(checkinDateValue).format('DD/MM/YYYY'));
                $('#checkoutpo').text(moment(checkoutDateValue).format('DD/MM/YYYY'));
                $('#daypo').text(totalDays + ' วัน');
                $('#nightpo').text('0 คืน');
            } else {
                console.log("วัน Check-out ต้องมากกว่าวัน Check-in");
                $('#Day').val('0');
                $('#Night').val('0');
            }
        }

        function setMinDate() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('Checkin').setAttribute('min', today);
            document.getElementById('Checkout').setAttribute('min', today);
        }

        // เรียกใช้เมื่อโหลดหน้า
        setMinDate();

        document.addEventListener('DOMContentLoaded', setMinDate);
        $(function() {
            var start = moment();
            var end = moment().add(7, 'days');
            function cb(start, end) {
                $('#datestart').val(start.format('DD/MM/Y'));
                $('#dateex').val(end.format('DD/MM/Y'));
                $('#issue_date_document').text(start.format('DD/MM/Y'));
                $('#issue_date_document1').text(start.format('DD/MM/Y'));
            }
            $('#reportrange1').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    '3 Days': [moment(), moment().add(3, 'days')],
                    '7 Days': [moment(), moment().add(7, 'days')],
                    '15 Days': [moment(), moment().add(15, 'days')],
                    '30 Days': [moment(), moment().add(30, 'days')],
                }
            },
            cb);
            cb(start, end);
        });
        function showselectInput() {
            var select = document.getElementById("select");
            //------------------------บริษัท------------------
            var Companyshow = document.getElementById("Companyshow");
            var Company = document.getElementById("Company");
            var Company_Contact = document.getElementById("Company_Contact");
            var Company_Contactname = document.getElementById("Company_Contactname");
            // -----------------------ลูกค้า--------------------

            var Guest = document.getElementById("Guest");
            var Guestshow = document.getElementById("Guestshow");
            //-------------------ตาราง---------------------------
            var companyTable = document.getElementById("companyTable");
            var contractTable = document.getElementById("contractTable");
            var TiTlecompanyTable = document.getElementById("TiTlecompanyTable");
            var TiTlecontractTable = document.getElementById("TiTlecontractTable");
            var guestTable = document.getElementById("guestTable");
            var TiTleguestTable = document.getElementById("TiTleguestTable");


            if (select.value === "Company") {
                Companyshow.style.display = "block";
                Guestshow.style.display = "none";
                guestTable.style.display = "none";
                TiTleguestTable.style.display = "none";
                Company.disabled = false;
                Company_Contact.disabled = false;
                Company_Contactname.disabled = false;
                Guest.disabled = true;
                companyTable.style.display = "block";
                contractTable.style.display = "block";
                TiTlecompanyTable.style.display = "block";
                TiTlecontractTable.style.display = "block";
            } else {
                guestTable.style.display = "block";
                TiTleguestTable.style.display = "block";
                Guestshow.style.display = "block";
                Companyshow.style.display = "none";
                companyTable.style.display = "none";
                contractTable.style.display = "none";
                TiTlecompanyTable.style.display = "none";
                TiTlecontractTable.style.display = "none";
                Company.disabled = true;
                Company_Contact.disabled = true;
                Company_Contactname.disabled = true;
                Guest.disabled = false;
            }
        }
        ///company
        function companyContact() {
            var companyID = $('#Company').val();
            console.log(companyID);
            jQuery.ajax({
                type: "GET",
                url: "{!! url('/Proposal/create/company/" + companyID + "') !!}",
                datatype: "JSON",
                async: false,
                success: function(response) {
                    var fullName = response.data.First_name + ' ' + response.data.Last_name;
                    var fullid = response.data.id ;
                    if (response.Company_type.name_th === 'บริษัทจำกัด') {
                        var fullNameCompany = 'บริษัท' + ' ' + response.company.Company_Name + ' ' + 'จำกัด';
                    }
                    else if (response.Company_type.name_th === 'บริษัทมหาชนจำกัด') {
                        var fullNameCompany = 'บริษัท' + ' ' + response.company.Company_Name + ' ' + 'จำกัด'+' '+'(มหาชน)';
                    }
                    else if (response.Company_type.name_th === 'ห้างหุ้นส่วนจำกัด') {
                        var fullNameCompany = 'ห้างหุ้นส่วนจำกัด' + ' ' + response.company.Company_Name ;
                    }
                    var Address = response.company.Address + ' '+ 'ตำบล'+ response.Tambon.name_th;
                    var Address2 = 'อำเภอ'+response.amphures.name_th + ' ' + 'จังหวัด'+ response.province.name_th + ' ' + response.Tambon.Zip_Code;
                    var companyfax = response.company_fax.Fax_number;
                    var CompanyEmail = response.company.Company_Email;
                    var Discount_Contract_Rate = response.company.Discount_Contract_Rate;
                    var TaxpayerIdentification = response.company.Taxpayer_Identification;
                    var companyphone = response.company_phone.Phone_number;

                    var Contactphones =response.Contact_phones.Phone_number;
                    var Contactemail =response.data.Email;

                    console.log(response.data.First_name);


                    var formattedPhoneNumber = companyphone;


                    var formattedContactphones = Contactphones;
                    $('#Company_Contact').val(fullName).prop('disabled', true);
                    $('#Company_Discount').val(Discount_Contract_Rate);
                    $('#Company_Contactname').val(fullid);
                    $('#Company_name').text(fullNameCompany);
                    $('#Address').text(Address);
                    $('#Address2').text(Address2);
                    $('#Company_Number').text(formattedPhoneNumber);
                    $('#Company_Fax').text(companyfax);
                    $('#Company_Email').text(CompanyEmail);
                    $('#Taxpayer').text(TaxpayerIdentification);
                    $('#Company_contact').text(fullName);
                    $('#Contact_Phone').text(formattedContactphones);
                    $('#Contact_Email').text(Contactemail);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX request failed: ", status, error);
                }
            });
        }
        function GuestContact(){
            var Guest = $('#Guest').val();
            console.log(Guest);
            jQuery.ajax({
                type: "GET",
                url: "{!! url('/Proposal/create/Guest/" + Guest + "') !!}",
                datatype: "JSON",
                async: false,
                success: function(response) {
                    var prename = response.Company_type.name_th;
                    var fullName = prename +' '+response.data.First_name + ' ' + response.data.Last_name;
                    var Address = response.data.Address + ' '+ 'ตำบล'+ response.Tambon.name_th;
                    var Address2 = 'อำเภอ'+response.amphures.name_th + ' ' + 'จังหวัด'+ response.province.name_th + ' ' + response.Tambon.Zip_Code;
                    var Email = response.data.Email;
                    var Identification = response.data.Identification_Number;
                    var phone = response.phone.Phone_number;


                    var formattedPhoneNumber = phone;

                    $('#guest_name').text(fullName);
                    $('#guestAddress').text(Address);
                    $('#guestAddress2').text(Address2);
                    $('#guest_Number').text(formattedPhoneNumber);
                    $('#guest_Email').text(Email);
                    $('#guestTaxpayer').text(Identification);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX request failed: ", status, error);
                }
            });
        }

        //---------------------ส่วนข้อมูล--------
        document.getElementById('flexCheckChecked').addEventListener('change', function(event) {
            var isChecked = event.target.checked;
            var dateInput = document.getElementById('Checkin');
            var dateout = document.getElementById('Checkout');
            var Day = document.getElementById('Day');
            var Night = document.getElementById('Night');
            if (isChecked == true) {
                dateInput.disabled = true;
                dateout.disabled = true;
                Day.disabled = true;
                Night.disabled = true;
                dateInput.classList.add('disabled-input');
                dateout.classList.add('disabled-input');
                $('#checkinpo').text('No Check in date');
                $('#checkoutpo').text('-');
                $('#daypo').text('-');
                $('#nightpo').text(' ');
                $('#Checkin').val('');
                $('#Checkout').val('');
                $('#Day').val('');
                $('#Night').val('');
            } else {
                dateInput.disabled = false;
                dateout.disabled = false;
                Day.disabled = false;
                Night.disabled = false;
                dateInput.classList.remove('disabled-input');
                dateout.classList.remove('disabled-input');
                $('#Checkin').val('');
                $('#Checkout').val('');
                $('#Day').val('');
                $('#Night').val('');
            }
        });
        $(document).on('keyup', '#Children', function() {
            var Children =  Number($(this).val());
            $('#Childrenpo').text(' , '+ Children +' Children');
            totalAmost();
        });
        $(document).on('keyup', '#Adult', function() {
            var adult =  Number($(this).val());
            $('#Adultpo').text(adult +' Adult');
            totalAmost();
        });
        function masterevent() {
            var Mevent =$('#Mevent').val();
            if (Mevent == '43') {
                $('#Payment50').css('display', 'block');
                $('#Payment100').css('display', 'none');
            } else if (Mevent == '53') {
                $('#Payment50').css('display', 'none');
                $('#Payment100').css('display', 'block');
            }else if (Mevent == '54') {
                $('#Payment50').css('display', 'none');
                $('#Payment100').css('display', 'block');
            }
        }
        $(document).ready(function() {
            $('#PRICE_INCLUDE_VAT').css('display', 'block');
            $('#PRICE_EXCLUDE_VAT').css('display', 'none');
            $('#PRICE_PLUS_VAT').css('display', 'none');
            $('#Payment50').css('display', 'block');
            $('#Payment100').css('display', 'none');
        });
        function mastervat() {
            var Mvat =$('#Mvat').val();
            if (Mvat == '50') {
                $('#PRICE_INCLUDE_VAT').css('display', 'block');
                $('#PRICE_EXCLUDE_VAT').css('display', 'none');
                $('#PRICE_PLUS_VAT').css('display', 'none');
            }
            else if (Mvat == '52') {
                $('#PRICE_INCLUDE_VAT').css('display', 'none');
                $('#PRICE_EXCLUDE_VAT').css('display', 'none');
                $('#PRICE_PLUS_VAT').css('display', 'block');
            }else{
                $('#PRICE_INCLUDE_VAT').css('display', 'none');
                $('#PRICE_EXCLUDE_VAT').css('display', 'block');
                $('#PRICE_PLUS_VAT').css('display', 'none');
            }
        }
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });

        if (performance.navigation.type === 2) {
            // โหลดหน้าเว็บใหม่เมื่อกดย้อนกลับ
            sessionStorage.setItem('reloadAfterBack', 'true');
        }

        window.addEventListener('pageshow', function(event) {
            if (sessionStorage.getItem('reloadAfterBack')) {
                sessionStorage.removeItem('reloadAfterBack');
                window.location.reload();
            }
        });
    </script>
    <script>
        const table_name = ['mainselect1'];
        $(document).ready(function() {
            for (let index = 0; index < table_name.length; index++) {
                new DataTable('#'+table_name[index], {
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
            }
        });
        const table_name2 = ['main'];
        $(document).ready(function() {
            for (let index = 0; index < table_name2.length; index++) {
                new DataTable('#'+table_name2[index], {
                    searching: false,
                    paging: false,
                    info: false,
                    language: {
                        emptyTable: "",
                        zeroRecords: ""
                    },
                    columnDefs: [{
                        className: 'dtr-control',
                        orderable: false,
                        target: null,
                    }],
                    order:  false,
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    }
                });
                $('#'+table_name2[index] + ' thead th').removeClass('sorting sorting_asc sorting_desc');
            }
        });
        function fetchProducts(status) {
            if (status == 'all' ) {
                $('#ProductName').text('All Product');
            }else if (status == 'Room_Type') {
                $('#ProductName').text('Room');
            }
            else if (status == 'Banquet') {
                $('#ProductName').text('Banquet');
            }
            else if (status == 'Meals') {
                $('#ProductName').text('Meals');
            }
            else if (status == 'Entertainment') {
                $('#ProductName').text('Entertainment');
            }
            $('#ProductName').text();
            var table = $('#mainselect1').DataTable();
            var Quotation_ID = $('#Quotation_ID').val(); // Replace this with the actual ID you want to send
            var clickCounter = 1;
            $.ajax({
                url: '{{ route("Proposal.addProduct", ["Quotation_ID" => ":id"]) }}'.replace(':id', Quotation_ID),
                method: 'GET',
                data: {
                    value: status
                },
                success: function(response) {

                    if (response.products.length > 0) {
                        // Clear the existing rows
                        table.clear();
                        var num = 0;
                        var pageSize = 10; // กำหนดจำนวนแถวต่อหน้า
                        var currentPage = 1;
                        var totalItems = response.products.length;
                        var totalPages = Math.ceil(totalItems / pageSize);
                        var maxVisibleButtons = 3; // จำนวนปุ่มที่จะแสดง
                        function renderPage(page) {
                            table.clear();
                            let num = (page - 1) * pageSize + 1;
                            for (let i = (page - 1) * pageSize; i < page * pageSize && i < totalItems; i++) {
                                const data = response.products[i];
                                const productId = data.id;
                                var existingRowId = $('#tr-select-add' + productId).attr('id');
                                if ($('#' + existingRowId).val() == undefined) {
                                    table.row.add([
                                        num++,
                                        data.Product_ID,
                                        data.name_th,
                                        Number(data.normal_price).toLocaleString(),
                                        data.unit_name,
                                        `<button type="button" class="btn btn-color-green lift btn_modal select-button-product" id="product-${data.id}" value="${data.id}"><i class="fa fa-plus"></i></button>`
                                    ]).node().id = `row-${productId}`;
                                }
                            }
                            table.draw(false);
                            $('#mainselect1').DataTable().columns.adjust().responsive.recalc();
                            // Update active class for pagination buttons
                            $('.paginate-btn').removeClass('active');
                            $(`[data-page="${page}"]`).addClass('active');
                        }

                        function createPagination(totalPages, currentPage) {
                            $('#paginationContainer').html(`
                                <button class="paginate-btn" data-page="prev">&laquo;</button>
                            `);

                            var startPage = Math.max(1, currentPage - Math.floor(maxVisibleButtons / 2));
                            var endPage = Math.min(totalPages, startPage + maxVisibleButtons - 1);

                            if (startPage > 1) {
                                $('#paginationContainer').append(`<button class="paginate-btn" data-page="1">1</button>`);
                                if (startPage > 2) {
                                    $('#paginationContainer').append(`<button class="paginate-btn"  disabled>...</button>`);
                                }
                            }

                            for (let i = startPage; i <= endPage; i++) {
                                $('#paginationContainer').append(`<button class="paginate-btn" data-page="${i}">${i}</button>`);
                            }

                            if (endPage < totalPages) {
                                if (endPage < totalPages - 1) {
                                    $('#paginationContainer').append(`<button class="paginate-btn"disabled >...</button>`);
                                }
                                $('#paginationContainer').append(`<button class="paginate-btn" data-page="${totalPages}">${totalPages}</button>`);
                            }

                            $('#paginationContainer').append(`
                                <button class="paginate-btn" data-page="next">&raquo;</button>
                            `);
                        }

                        createPagination(totalPages, currentPage);
                        renderPage(currentPage);

                        // Handle page click
                        $(document).on('click', '.paginate-btn', function() {
                            var page = $(this).data('page');

                            if (page === 'prev') {
                                if (currentPage > 1) {
                                    currentPage--;
                                }
                            } else if (page === 'next') {
                                if (currentPage < totalPages) {
                                    currentPage++;
                                }
                            } else {
                                currentPage = parseInt(page);
                            }

                            createPagination(totalPages, currentPage);
                            renderPage(currentPage);
                        });
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
                    $('#row-' + product).prop('hidden',true);
                    $('tr .child').prop('hidden',true);
                    console.log(product);
                    if ($('#productselect' + product).length > 0) {
                        return;
                    }
                    $.ajax({
                        url: '{{ route("Proposal.addProductselect", ["Quotation_ID" => ":id"]) }}'.replace(':id', Quotation_ID),
                        method: 'GET',
                        data: {
                            value:product
                        },
                        success: function(response) {
                            $.each(response.products, function(index, val) {
                                var name = '';
                                var price = 0;
                                var rowNumber = $('#product-list-select tr:visible').length+1;
                                if ($('#productselect' + val.id).length > 0) {
                                    console.log("Product already exists after AJAX call: ", val.id);
                                    return;
                                }
                                if ($('#product-list' + val.Product_ID).length > 0) {
                                    console.log("Product already exists after AJAX call: ", val.Product_ID);
                                }

                                $('#product-list-select').append(
                                    '<tr id="tr-select-add' + val.id + '">' +
                                    '<td style="text-align:center;">' + rowNumber + '</td>' +
                                    '<td><input type="hidden" class="randomKey" name="randomKey" id="randomKey" value="' + val.Product_ID + '">' + val.Product_ID + '</td>' +
                                    '<td style="text-align:left;">' + val.name_en + '</td>' +
                                    '<td style="text-align:left;">' + Number(val.normal_price).toLocaleString() + '</td>' +
                                    '<td style="text-align:center;">' + val.unit_name + '</td>' +
                                    '<td style="text-align:center;"> <button type="button" class="Btn remove-button " style=" border: none;" value="' + val.id + '"><i class="fa fa-minus-circle text-danger fa-lg"></i></button></td>' +
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
                $('#product-list-select tr:visible').each(function(index) {
                    $(this).find('td:first-child').text(index+1); // เปลี่ยนเลขลำดับในคอลัมน์แรก
                });
                $('#display-selected-items tr').each(function(index) {
                    $(this).find('td:first-child').text(index); // เปลี่ยนเลขลำดับในคอลัมน์แรก
                });
            }
            $(document).on('click', '.remove-button', function() {
                console.log(1);
                var product = $(this).val();
                $('#tr-select-add' + product).remove();
                $('#row-' + product).prop('hidden',false);
                renumberRows();// ลบแถวที่มี id เป็น 'tr-select-add' + product
            });
            $(document).on('click', '.confirm-button', function() {
                var number = $('#randomKey').val();
                console.log(number);
                $.ajax({
                    url: '{{ route("Proposal.addProducttablecreatemain", ["Quotation_ID" => ":id"]) }}'.replace(':id', Quotation_ID),
                    method: 'GET',
                    data: {
                        value: "all"
                    },
                    success: function(response) {
                        $.each(response.products, function (key, val) {
                            $('#tr-select-add' + val.id).prop('hidden',true);
                            if ($('#productselect' + val.id).val() !== undefined) {
                                if ($('#display-selected-items #tr-select-addmain' + val.id).length === 0) {
                                    number += 1;
                                    var name = '';
                                    var price = 0;
                                    var normalPriceString = val.normal_price.replace(/[^0-9.]/g, ''); // ล้างค่าที่ไม่ใช่ตัวเลขและจุดทศนิยม
                                    var normalPrice = parseFloat(normalPriceString);
                                    var netDiscount = ((normalPrice)).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                    var normalPriceview = ((normalPrice)).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                                    var rowNumbemain = $('#display-selected-items tr').length;
                                    let discountInput;
                                    let quantity;
                                    var roleMenuDiscount = document.getElementById('roleMenuDiscount').value;
                                    var SpecialDiscount = document.getElementById('SpecialDiscount').value;
                                    var discountuser = document.getElementById('discountuser').value;
                                    var maximum_discount = val.maximum_discount;
                                    var valpax = val.pax;
                                    if (valpax == null) {
                                        valpax = 0;
                                    }
                                    if (SpecialDiscount >= 1) {
                                        if (roleMenuDiscount == 1) {
                                            discountInput = '<div class="input-group">' +
                                                        '<input class="discountmain form-control" type="text" id="discountmain' + number + '" name="discountmain[]" value="" min="0" rel="' + number + '" style="text-align:center;" ' +
                                                        'oninput="this.value = this.value.replace(/[^0-9]/g, \'\').slice(0, 10);' +
                                                        'if (parseFloat(this.value) > ' + SpecialDiscount + ') { this.value = ' + SpecialDiscount + '; }' +
                                                        'else if (parseFloat(this.value) > ' + val.maximum_discount + ') { this.value = ' + val.maximum_discount + '; }"' +
                                                        'required>' +
                                                        '<span class="input-group-text">%</span>' +
                                                        '</div>';
                                        } else {
                                            discountInput = '<div class="input-group">' +
                                                '<input class="discountmain form-control" type="text" id="discountmain' + number + '" name="discountmain[]" value=" " rel="' + number + '" style="text-align:center;" disabled ' +
                                                'oninput="if (parseFloat(this.value= this.value.replace(/[^0-9]/g, \'\').slice(0, 10)) > ' + val.maximum_discount + ') this.value = ' + val.maximum_discount + ';">' +
                                                '<span class="input-group-text">%</span>' +
                                                '</div>';
                                        }
                                    }
                                    else{
                                        discountInput = '<div class="input-group">' +
                                            '<input class="discountmain form-control" type="text" id="discountmain' + number + '" name="discountmain[]" value=" " rel="' + number + '" style="text-align:center;" ' +
                                            'oninput="if (parseFloat(this.value= this.value.replace(/[^0-9]/g, \'\').slice(0, 10)) > ' + val.maximum_discount + ') this.value = ' + val.maximum_discount + ';">' +
                                            '<span class="input-group-text">%</span>' +
                                            '</div>';
                                    }
                                    quantity = '<div class="input-group">' +
                                                '<input class="quantitymain form-control" type="text" id="quantitymain' + number + '" name="Quantitymain[]" value=" " rel="' + number + '" style="text-align:center;" ' +
                                                'oninput="if (parseFloat(this.value= this.value.replace(/[^0-9]/g, \'\').slice(0, 10)) > ' + val.NumberRoom + ') this.value = ' + val.NumberRoom + ';">' +
                                                '</div>';
                                    $('#display-selected-items').append(
                                        '<tr id="tr-select-addmain' + val.id + '">' +
                                        '<td style="text-align:center;">' + rowNumbemain + '</td>' +
                                        '<td style="text-align:left;"><input type="hidden" id="Product_ID" name="ProductIDmain[]" value="' + val.Product_ID + '">' + val.name_en +' '+'<span class="fa fa-info-circle" data-bs-toggle="tooltip" data-placement="top" title="' + val.maximum_discount +'%'+'"></span></td>' +
                                        '<td style="text-align:center; color:#fff"><input type="hidden"class="pax" id="pax'+ number +'" name="pax[]" value="' + val.pax + '"rel="' + number + '"><span  id="paxtotal' + number + '">' + valpax + '</span></td>' +
                                        '<td style="text-align:center;width:10%;">'+ quantity +'</td>' +
                                        '<td>' + val.unit_name + '</td>' +
                                        '<td style="text-align:center;"><input type="hidden" id="totalprice-unit-' + number + '" name="priceproductmain[]" value="' + val.normal_price + '">' + Number(val.normal_price).toLocaleString() + '</td>' +
                                        '<td>' + discountInput + '</td>' +
                                        '<td style="text-align:center;"><input type="hidden" id="net_discount-' + number + '" value="' + val.normal_price + '"><span id="netdiscount' + number + '">' + normalPriceview + '</span></td>' +
                                        '<td style="text-align:center;"><input type="hidden" id="allcounttotal-' + number + '" value=" ' + val.normal_price + '"><span id="allcount' + number + '">' + normalPriceview + '</span></td>' +
                                        '<td  style="text-align:center;"><button type="button" class="Btn remove-buttonmain" value="' + val.id + '"><i class="fa fa-minus-circle text-danger fa-lg"></i></button></td>' +
                                        '</tr>'
                                    );
                                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                                        return new bootstrap.Tooltip(tooltipTriggerEl)
                                    });
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
            $(document).ready(function() {
                totalAmost();
                $(document).on('click', '.remove-buttonmain', function() {
                    var product = $(this).val();
                    $('#tr-select-add' + product + ', #tr-select-addmain' + product).remove();

                    $('#display-selected-items tbody tr').each(function(index) {
                        // เปลี่ยนเลขลำดับใหม่
                        $(this).find('td:first').text(index+1);
                    });
                    renumberRows();
                    totalAmost();// ลบแถวที่มี id เป็น 'tr-select-add' + product
                });
            });
        }
        //----------------------------------------รายการ---------------------------
        $(document).ready(function() {
            $(document).on('keyup', '.quantitymain', function() {
                for (let i = 0; i < 50; i++) {
                    var number_ID = $(this).attr('rel');
                    var quantitymain =  Number($(this).val());
                    var discountmain =  $('#discountmain'+number_ID).val();
                    var paxmain = parseFloat($('#pax' + number_ID).val());
                    if (isNaN(paxmain)) {
                        paxmain = 0;
                    }
                    var pax = paxmain*quantitymain;
                    $('#paxtotal'+number_ID).text(pax);
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
                }
            });
            $(document).on('keyup', '.discountmain', function() {
                for (let i = 0; i < 50; i++) {
                    var number_ID = $(this).attr('rel');
                    var discountmain =  Number($(this).val());
                    console.log(discountmain);
                    var quantitymain =  $('#quantitymain'+number_ID).val();
                    console.log(quantitymain);
                    var number = Number($('#number-product').val());
                    var price = parseFloat($('#totalprice-unit-'+number_ID).val().replace(/,/g, ''));
                    var pricediscount =  (price*discountmain /100);
                    var allcount0 = price - pricediscount;
                    console.log(allcount0);
                    $('#netdiscount'+number_ID).text(allcount0.toLocaleString('th-TH', {minimumFractionDigits: 2}));
                    var pricenew = price*quantitymain
                    var pricediscount = pricenew - (pricenew*discountmain /100);
                    $('#allcount'+number_ID).text(pricediscount.toLocaleString('th-TH', {minimumFractionDigits: 2}));
                    // $('#allcount0'+number_ID).text(allcount0.toLocaleString('th-TH', {minimumFractionDigits: 2}));
                    totalAmost();

                }

            });
        });
        function totalAmost() {

            $(document).ready(function() {
                var typevat  = $('#Mvat').val();
                let allprice = 0;
                let lessDiscount = 0;
                let beforetax =0;
                let addedtax =0;
                let Nettotal =0;
                let paxtotal=0;
                let totalperson=0;
                let PaxToTalall=0;
                let priceArray = [];
                let pricedistotal = [];// เริ่มต้นตัวแปร allprice และ allpricedis ที่นอกลูป
                let Discount = [];
                var DiscountAmount = document.getElementById('DiscountAmount').value;
                $('#display-selected-items tr').each(function() {
                    let priceCell = $(this).find('td').eq(8);
                    let pricetotal = parseFloat(priceCell.text().replace(/,/g, '')) || 0;
                    var Discount = parseFloat(DiscountAmount);
                    let allpax = $(this).find('td').eq(2);
                    let pax = parseFloat(allpax.text().replace(/,/g, '')) || 0;
                    var rowCount = $('#display-selected-items tr').length;
                    if (typevat == '50') {
                        paxtotal +=pax;
                        PaxToTalall = paxtotal;
                        allprice += pricetotal;
                        lessDiscount = allprice-DiscountAmount;
                        beforetax= lessDiscount/1.07;
                        addedtax = lessDiscount-beforetax;
                        Nettotal= beforetax+addedtax;
                        totalperson = Nettotal/paxtotal;

                        $('#sp').text(isNaN(Discount) ? '0' : Discount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#total-amount').text(isNaN(allprice) ? '0' : allprice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#lessDiscount').text(isNaN(lessDiscount) ? '0' : lessDiscount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#Net-price').text(isNaN(beforetax) ? '0' : beforetax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#total-Vat').text(isNaN(addedtax) ? '0' : addedtax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#Net-Total').text(isNaN(Nettotal) ? '0' : Nettotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#Average').text(isNaN(totalperson) ? '0' : totalperson.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#PaxToTal').text(isNaN(paxtotal) ? '0' : paxtotal);
                        $('#PaxToTalall').val(isNaN(PaxToTalall) ? '0' : PaxToTalall);
                        if (paxtotal == 0) {
                            $('#Pax').css('display', 'none');
                        }else{
                            $('#Pax').css('display', 'block');
                        }
                    }
                    else if(typevat == '51')
                    {
                        paxtotal +=pax;
                        PaxToTalall = paxtotal;
                        allprice += pricetotal;
                        lessDiscount = allprice-DiscountAmount;
                        beforetax= lessDiscount;
                        addedtax =0;
                        Nettotal= beforetax;
                        totalperson = Nettotal/paxtotal;
                        $('#spEXCLUDE').text(isNaN(Discount) ? '0' : Discount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#total-amountEXCLUDE').text(isNaN(allprice) ? '0' : allprice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#lessDiscountEXCLUDE').text(isNaN(lessDiscount) ? '0' : lessDiscount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#Net-priceEXCLUDE').text(isNaN(beforetax) ? '0' : beforetax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#total-VatEXCLUDE').text(isNaN(addedtax) ? '0' : addedtax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#Net-Total').text(isNaN(Nettotal) ? '0' : Nettotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#Average').text(isNaN(totalperson) ? '0' : totalperson.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#PaxToTal').text(isNaN(paxtotal) ? '0' : paxtotal);
                        $('#PaxToTalall').val(isNaN(PaxToTalall) ? '0' : PaxToTalall);
                        if (paxtotal == 0) {
                            $('#Pax').css('display', 'none');
                        }else{
                            $('#Pax').css('display', 'block');
                        }
                    } else if(typevat == '52'){
                        paxtotal +=pax;
                        PaxToTalall = paxtotal;
                        allprice += pricetotal;
                        lessDiscount = allprice-DiscountAmount;
                        addedtax = lessDiscount*7/100;;
                        beforetax= lessDiscount+addedtax;
                        Nettotal= beforetax;
                        totalperson = Nettotal/paxtotal;


                        $('#sppus').text(isNaN(Discount) ? '0' : Discount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#total-amountpus').text(isNaN(allprice) ? '0' : allprice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#lessDiscountpus').text(isNaN(lessDiscount) ? '0' : lessDiscount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#Net-pricepus').text(isNaN(beforetax) ? '0' : beforetax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#total-Vatpus').text(isNaN(addedtax) ? '0' : addedtax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#Net-Total').text(isNaN(Nettotal) ? '0' : Nettotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#Average').text(isNaN(totalperson) ? '0' : totalperson.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#PaxToTal').text(isNaN(paxtotal) ? '0' : paxtotal);
                        $('#PaxToTalall').val(isNaN(PaxToTalall) ? '0' : PaxToTalall);
                        if (paxtotal == 0) {
                            $('#Pax').css('display', 'none');
                        }else{
                            $('#Pax').css('display', 'block');
                        }
                    }


                });
                function checkRowCount() {
                    var rowCount = $('#display-selected-items tr').not(':first').length;
                    if (rowCount === 0) {
                        var Count = $('#display-selected-items tr:last').length;
                        if (Count == 0 ) {
                            if (typevat == '50') {
                                $('#total-amount').text(0.00);
                                $('#lessDiscount').text(0.00);
                                $('#Net-price').text(0.00);
                                $('#total-Vat').text(0.00);
                                $('#Net-Total').text(0.00);
                                $('#Average').text(0.00);
                                $('#PaxToTal').text(0.00);
                            }else if(typevat == '51')
                            {
                                $('#total-amountEXCLUDE').text(0.00);
                                $('#lessDiscountEXCLUDE').text(0.00);
                                $('#Net-priceEXCLUDE').text(0.00);
                                $('#total-VatEXCLUDE').text(0.00);
                                $('#Net-Total').text(0.00);
                                $('#Average').text(0.00);
                                $('#PaxToTal').text(0.00);
                            } else if(typevat == '52'){
                                $('#total-amountpus').text(0.00);
                                $('#lessDiscountpus').text(0.00);
                                $('#Net-pricepus').text(0.00);
                                $('#total-Vatpus').text(0.00);
                                $('#Net-Total').text(0.00);
                                $('#Average').text(0.00);
                                $('#PaxToTal').text(0.00);
                            }
                        }
                    }
                }
                checkRowCount();
            });

        }
        totalAmost();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function BACKtoEdit(){
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
                    window.location.href = "{{ route('Proposal.index') }}";
                }
            });
        }
        function confirmSubmit(event) {
            event.preventDefault(); // Prevent the form from submitting
            var Quotation_ID = $('#Quotation_ID').val();
            var title = `คุณต้องการบันทึกข้อมูลรหัส ${Quotation_ID} ใช่หรือไม่?`;
            Swal.fire({
                title: title,
                // text: message,
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "บันทึกข้อมูล",
                cancelButtonText: "ยกเลิก",
                confirmButtonColor: "#2C7F7A",
                dangerMode: true
            }).then((result) => {
                if (result.isConfirmed) {

                    // สร้าง input แบบ hidden ใหม่
                    var input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "preview";
                    input.value = 0;

                    // เพิ่ม input ลงในฟอร์ม
                    document.getElementById("myForm").appendChild(input);
                    document.getElementById("myForm").removeAttribute('target');
                    document.getElementById("myForm").submit();
                }
            });
        }
        function submitPreview() {
            var previewValue = document.getElementById("preview").value;

            // สร้าง input แบบ hidden ใหม่
            var input = document.createElement("input");
            input.type = "hidden";
            input.name = "preview";
            input.value = 1;

            // เพิ่ม input ลงในฟอร์ม
            document.getElementById("myForm").appendChild(input);
            document.getElementById("myForm").setAttribute("target","_blank");
            document.getElementById("myForm").submit();
        }
    </script>
@endsection
