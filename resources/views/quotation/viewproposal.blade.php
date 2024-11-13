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
                    <small class="text-muted">Welcome to View Proposal to Send Email.</small>
                    <div class=""><span class="span1">View Proposal to Send Email (ดูเอกสารข้อเสนอเพื่อส่งอีเมล)</span></div>
                </div>
            </div> <!-- .row end -->
        </div>
    </div>
    <form id="myForm"  method="POST">
        @csrf
        <div id="content-index" class="body d-flex py-lg-4 py-3">
            <div class="container-xl">
                <div class="row clearfix">
                    <div class="col-md-12 col-12">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-7 col-md-12 col-sm-12 image-container">
                                        <img src="{{ asset('assets/images/' . $settingCompany->image) }}" alt="Together Resort Logo" class="logo"/>
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
                                    <div class="col-lg-5 col-md-12 col-sm-12">
                                        <div class="row">
                                            <div class="col-lg-4"></div>
                                            <div class="PROPOSAL col-lg-7" style="margin-left: 5px">
                                                <div class="row">
                                                    <b class="titleQuotation" style="font-size: 20px;color:rgb(255, 255, 255);">Proposal</b>
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
                                                            <input type="text" id="datestart" class="form-control " name="IssueDate" style="text-align: left;" value="{{$Quotation->issue_date}}"readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-12 col-sm-12 mt-2">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-12 col-sm-12"style="display:flex; justify-content:right; align-items:center;">
                                                            <span>Expiration Date:</span>
                                                        </div>
                                                        <div class="col-lg-6 col-md-12 col-sm-12">
                                                            <input type="text" id="dateex" class="form-control " name="Expiration" style="text-align: left;"value="{{$Quotation->Expirationdate}}"readonly>
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
                                        <select name="selectdata" id="select" class="select2" onchange="showselectInput()" disabled>
                                            <option value="Company"{{$Quotation->type_Proposal == "Company" ? 'selected' : ''}}>นามบริษัท</option>
                                            <option value="Guest"{{$Quotation->type_Proposal == "Guest" ? 'selected' : ''}}>นามบุคคล</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="Companyshow" style="display: block"disabled>
                                    <div class="row mt-2" >
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <label class="labelcontact" for="">Customer Company</label>
                                            <select name="Company" id="Company" class="select2" onchange="companyContact()" disabled>
                                                <option value=""></option>
                                                @foreach($Company as $item)
                                                    <option value="{{ $item->Profile_ID }}"{{$Quotation->Company_ID == $item->Profile_ID ? 'selected' : ''}}>{{ $item->Company_Name }}</option>
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
                                            <select name="Guest" id="Guest" class="select2" onchange="GuestContact()" disabled>
                                                <option value=""></option>
                                                @foreach($Guest as $item)
                                                    <option value="{{ $item->Profile_ID }}"{{$Quotation->Company_ID == $item->Profile_ID ? 'selected' : ''}}>{{ $item->First_name }} {{$item->Last_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <hr class="mt-3 my-3" style="border: 1px solid #000">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12 col-sm-12">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" > No Check In Date</label>
                                        </div>
                                        <div class="col-lg-6 col-md-12 col-sm-12" style="float: right">
                                            <span><b> Date Type : </b><span id="calendartext" style="font-size: 16px;color:rgb(0, 0, 0);"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <span for="chekin">Check In Date </span>
                                        <input type="text" name="Checkin" id="Checkin" class="form-control " value="{{$Quotation->checkin}}" readonly onchange="CheckDate()" disabled>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <span for="chekin">Check Out Date </span>
                                        <input type="text" name="Checkout" id="Checkout" class="form-control"value="{{$Quotation->checkout}}" onchange="CheckDate()"  readonly disabled>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <span for="">จำนวน</span>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="Day" id="Day" placeholder="จำนวนวัน"value="{{$Quotation->day}}"readonly disabled @disabled(true)>
                                            <span class="input-group-text">Day</span>
                                            <input type="text" class="form-control" name="Night" id="Night" placeholder="จำนวนคืน"value="{{$Quotation->night}}"readonly disabled  @disabled(true)>
                                            <span class="input-group-text">Night</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <span for="">จำนวนผู้เข้าพัก (ผู้ใหญ่/เด็ก)</span>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="Adult" id="Adult" placeholder="จำนวนผู้ใหญ่"value="{{$Quotation->adult}}"disabled>
                                            <span class="input-group-text">ผู้ใหญ่</span>
                                            <input type="text" class="form-control" name="Children"id="Children" placeholder="จำนวนเด็ก"value="{{$Quotation->children}}"disabled>
                                            <span class="input-group-text">เด็ก</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <span  for="">Event</span>
                                        <select name="Mevent" id="Mevent" class="select2"  onchange="masterevent()" disabled>
                                            <option value=""></option>
                                            @foreach($Mevent as $item)
                                                <option value="{{ $item->id }}"{{$Quotation->eventformat == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <span  for="">Vat Type</span>
                                        <select name="Mvat" id="Mvat" class="select2"  onchange="mastervat()" disabled>
                                            @foreach($Mvat as $item)
                                                <option value="{{ $item->id }}"{{$Quotation->vat_type == $item->id ? 'selected' : ''}}>{{ $item->name_th }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <span class="Freelancer_member" for="">Introduce By</span>
                                        <select name="Freelancer_member" id="Freelancer_member" class="select2" required disabled>
                                            <option value=""></option>
                                            @foreach($Freelancer_member as $item)
                                                <option value="{{ $item->Profile_ID }}"{{$Quotation->freelanceraiffiliate == $item->Profile_ID ? 'selected' : ''}}>{{ $item->First_name }} {{ $item->Last_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <span  for="">Company Discount Contract</span>{{--ดึงของcompanyมาใส่--}}
                                        <div class="input-group">
                                            <span class="input-group-text">DC</span>
                                            <input type="text" class="form-control" name="Company_Discount" id="Company_Discount" aria-label="Amount (to the nearest dollar)"value="{{$Quotation->ComRateCode}}" disabled>
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <span  for="">Company Commission</span>
                                        <div class="input-group">
                                            <input type="text" class="form-control"  name="Company_Commission_Rate_Code" value="{{$Quotation->commissionratecode}}"disabled>
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-12 col-sm-12">
                                                <span  for="">User Discount </span>{{--ดึงของuserมาใส่--}}
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="User_discount"value="{{@Auth::user()->discount}}" id="User_discount" placeholder="ส่วนลดคิดเป็น %" readonly>
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-12 col-sm-12">
                                                <span  for=""> Additional Discount</span>{{--ดึงของuserมาใส่--}}
                                                <div class="input-group">
                                                    <input class="form-control" type="text" name="Add_discount" id="Add_discount" value="{{$Quotation->additional_discount}}" placeholder="ส่วนลดเพิ่มเติมคิดเป็น %"
                                                            oninput="if (parseFloat(this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)) > {{ Auth::user()->additional_discount }}) this.value = {{ Auth::user()->additional_discount }};"
                                                            onchange="adddis()" disabled>
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <span  for="">Total User Discount</span>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="SpecialDiscount" id="SpecialDiscount"   placeholder="ส่วนลดคิดเป็น %" readonly disabled>
                                            <span class="input-group-text">%</span>
                                        </div>
                                        <script>
                                            function adddis() {
                                                // Get the discount values from the input fields
                                                var User_discount = parseFloat(document.getElementById('User_discount').value) || 0;
                                                var Add_discount = parseFloat(document.getElementById('Add_discount').value) || 0;

                                                // Calculate the total discount
                                                var total = User_discount + Add_discount;


                                                // Set the total discount to the SpecialDiscount field
                                                document.getElementById('SpecialDiscount').value = total.toFixed(2); // Keep two decimal places
                                            }
                                            $(document).ready(function() {

                                                    var User_discount = parseFloat(document.getElementById('User_discount').value) || 0;
                                                    var Add_discount = parseFloat(document.getElementById('Add_discount').value) || 0;
                                                    var total = User_discount+Add_discount;
                                                    $('#SpecialDiscount').val(total);

                                            });
                                        </script>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <span  for="">Discount Amount</span>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="DiscountAmount" id="DiscountAmount"  placeholder="ส่วนลดคิดเป็นบาท"value="{{$Quotation->SpecialDiscountBath}}" disabled required>
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
                                    <div class="proposal-cutomer-detail" id="companyTable">
                                        <ul>
                                        <b class="font-upper com">Company Information</b>
                                        <li class="mt-3">
                                            <b>Company Name</b>
                                            <span id="Company_name"></span>
                                        </li>
                                        <li>
                                            <b>Company Address</b>
                                            <span id="Address"></span>
                                            <b></b>
                                        </li>
                                        <span class="wrap-full">
                                            <li >
                                                <b>Company Number</b>
                                                <span id="Company_Number"></span>
                                            </li>
                                            <li >
                                                <b>Company Fax</b>
                                                <span id="Company_Fax"></span>
                                            </li>
                                        </span>
                                        <li>
                                            <b>Company Email</b>
                                            <span id="Company_Email"></span>
                                        </li>
                                        <li>
                                            <b>Taxpayer Identification</b>
                                            <span id="Taxpayer" ></span>
                                        </li>
                                        <li> </li>
                                        <b class="font-upper com ">Personal Information</b>
                                        <li class="mt-3">
                                            <b>Contact Name</b>
                                            <span id="Company_contact"></span>
                                        </li>
                                        <li >
                                            <b>Contact Number</b>
                                            <span id="Contact_Phone"></span>
                                        </li>
                                        <li>
                                            <b>Contact Email</b>
                                            <span id="Contact_Email" ></span>
                                        </li>
                                        <li></li>
                                        </ul>
                                        <ul>
                                        <li> </li>
                                        <li></li>
                                        <li> </li>
                                        <li></li>
                                        <li> </li>
                                        <li></li>
                                        <li>
                                            <b>Check In</b>
                                            <span id="checkinpo">{{$Quotation->checkin}}</span>
                                        </li>
                                        <li>
                                            <b>Check Out</b>
                                            <span id="checkoutpo">{{$Quotation->checkout}}</span>
                                        </li>
                                        <li>
                                            <b>Length of Stay</b>
                                            <span style="display: flex"><p id="daypo" class="m-0"> </p> <p id="nightpo" class="m-0"> </p></span>
                                        </li>
                                        <li>
                                            <b>Number of Guests</b>
                                            <span style="display: flex"><p id="Adultpo" class="m-0"> </p> <p id="Childrenpo" class="m-0"> </p></span>
                                        </li>

                                        </ul>

                                    </div>
                                    <div class="proposal-cutomer-detail" id="guestTable" style="display: none">
                                        <ul>
                                        <b class="font-upper com">Guest Information</b>
                                        <li class="mt-3">
                                            <b>Guest  Name</b>
                                            <span id="guest_name"></span>
                                        </li>


                                        <li>
                                            <b>Guest  Address</b>
                                            <span id="guestAddress"></span>
                                            <b></b>
                                        </li>

                                        <li >
                                            <b>Guest  Number</b>
                                            <span id="guest_Number"></span>
                                        </li>

                                        <li>
                                            <b>Guest  Email</b>
                                            <span id="guest_Email"></span>
                                        </li>
                                        <li>
                                            <b>Identification Number</b>
                                            <span id="guestTaxpayer" ></span>
                                        </li>
                                        <li> </li>
                                        <li></li>
                                        </ul>

                                        <ul>
                                            <li> </li>
                                            <li></li>
                                            <li> </li>
                                        <li></li>
                                        <li> </li>
                                        <li></li>
                                        <li>
                                            <b>Check In</b>
                                            <span id="checkinpoguest">{{$Quotation->checkin}}</span>
                                        </li>
                                        <li>
                                            <b>Check Out</b>
                                            <span id="checkoutpoguest">{{$Quotation->checkout}}</span>
                                        </li>
                                        <li>
                                            <b>Length of Stay</b>
                                            <span style="display: flex"><p id="daypoguest" class="m-0">{{$Quotation->day}} </p><p id="nightpoguest" class="m-0"> </p></span>
                                        </li>
                                        <li>
                                            <b>Number of Guests</b>
                                            <span style="display: flex"><p id="Adultpoguest" class="m-0"> </p><p id="Childrenpoguest" class="m-0"> </p></span>
                                        </li>
                                        </ul>
                                    </div>
                                    <div class="styled-hr"></div>
                                </div>
                                <div class="mt-2">
                                    <strong>ขอเสนอราคาและเงื่อนไขสำหรับท่าน ดังนี้ <br> We are pleased to submit you the following desctibed here in as price,items and terms stated :</strong>
                                </div>
                                <div class="row mt-2">
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
                                <div class="row mt-2">
                                    <table id="main" class=" example2 ui striped table nowrap unstackable " style="width:100%">
                                        <thead >
                                            <tr>
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;">No.</th>
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;"data-priority="1">Description</th>
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:1%;"></th>
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:12%;text-align:center">Quantity</th>
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:12%">Unit</th>
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center">Price / Unit</th>
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:12%;text-align:center">Discount</th>
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center">Net Price / Unit</th>
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center"data-priority="1">Amount</th>
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="display-selected-items">
                                            @if (!empty($selectproduct))
                                                @foreach ($selectproduct as $key => $item)
                                                    @foreach ($unit as $singleUnit)
                                                    @foreach ($quantity as $singleQuantity)
                                                        @if($singleUnit->id == @$item->product->unit)
                                                            @if($singleQuantity->id == @$item->product->quantity)
                                                            @php
                                                            $var = $item->Product_ID;
                                                            @endphp
                                                            <tr id="tr-select-main{{$item->Product_ID}}">
                                                                <input type="hidden" id="CheckProduct" name="CheckProduct[]" value="{{$item->Product_ID}}">
                                                                <td style="text-align:center;"><input type="hidden" id="ProductID" name="ProductIDmain[]" value="{{$item->Product_ID}}">{{$key+1}}</td>
                                                                <td style="text-align:left;">{{@$item->product->name_th}} <span class="fa fa-info-circle" data-bs-toggle="tooltip" data-placement="top" title="{{@$item->product->maximum_discount}} %"></span></td>
                                                                <td style="color: #fff">
                                                                    <input type="hidden" id="pax{{$var}}" name="pax[]" value="{{$item->pax}}" rel="{{$var}}">
                                                                    <span id="paxtotal{{$var}}">{{ floatval($item->pax) * floatval($item->Quantity) }}</span>
                                                                </td>
                                                                <td class="Quantity" data-value="{{$item->Quantity}}" style="text-align:center;">
                                                                    <div class="input-group">
                                                                        <input type="text" id="quantity{{$var}}" name="Quantitymain[]" rel="{{$var}}" style="text-align:center;"class="quantity-input form-control" value="{{$item->Quantity}} "oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" disabled>
                                                                        <span class="input-group-text">{{ $singleUnit->name_th }}</span>
                                                                    </div>
                                                                </td>
                                                                <td class="unit" style="text-align:center;">
                                                                    <div class="input-group">
                                                                        <input type="text" id="unit{{$var}}" name="Unitmain[]" rel="{{$var}}" style="text-align:center;"class="unit-input form-control" value="{{$item->Unit}} "oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);"disabled>
                                                                        <span class="input-group-text">{{ $singleQuantity->name_th }}</span>
                                                                    </div>
                                                                </td>
                                                                <td class="priceproduct" data-value="{{$item->priceproduct}}"style="text-align:center;"><input type="hidden" id="totalprice-unit{{$var}}" name="priceproductmain[]" value="{{$item->priceproduct}}">{{ number_format($item->priceproduct) }}</td>
                                                                <td class="discount"style="text-align:center;">
                                                                    @if ($item->discount)
                                                                        <div class="input-group">
                                                                            <input type="text" id="discount{{$var}}" name="discountmain[]" rel="{{$var}}"style="text-align:center;" class="discount-input form-control" value="{{$item->discount}}"oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);"disabled>
                                                                            <input type="hidden" id="maxdiscount{{$var}}" name="maxdiscount[]" rel="{{$var}}" class=" form-control" value="{{$item->product->maximum_discount}}">
                                                                            <span class="input-group-text">%</span>
                                                                        </div>
                                                                    @else
                                                                        <div class="input-group">
                                                                            <input type="hidden" id="discount{{$var}}" name="discountmain[]" rel="{{$var}}"style="text-align:center;" class="discount-input form-control" value="{{$item->discount}}"oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);"disabled>
                                                                            <input type="hidden" id="maxdiscount{{$var}}" name="maxdiscount[]" rel="{{$var}}" class=" form-control" value="{{$item->product->maximum_discount}}">
                                                                        </div>
                                                                    @endif
                                                                </td>
                                                                <td class="net-price"style="text-align:center;" ><span id="net_discount{{$var}}">{{ number_format($item->netpriceproduct, 2, '.', ',') }}</span></td>
                                                                <td class="item-total"style="text-align:center;"><span id="all-total{{$var}}">{{ number_format($item->totaldiscount, 2, '.', ',') }}</span></td>
                                                                <td style="text-align:center;">

                                                                </td>
                                                            </tr>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                    @endforeach
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    @if (@Auth::user()->roleMenuDiscount('Proposal',Auth::user()->id) == 1)
                                        <input type="hidden" name="roleMenuDiscount" id="roleMenuDiscount" value="1">
                                    @else
                                        <input type="hidden" name="roleMenuDiscount" id="roleMenuDiscount" value="0">
                                    @endif
                                    <input type="hidden" id="paxold" name="paxold" value="{{$Quotation->TotalPax}}">
                                    <input type="hidden" name="discountuser" id="discountuser" value="{{@Auth::user()->discount}}">
                                    <div class="wrap-b">
                                        <div class="kw" >
                                            <span >Notes or Special Comment</span>
                                            <textarea class="form-control mt-2"cols="30" rows="5"name="comment" id="comment" placeholder="Leave a comment here" id="floatingTextarea"></textarea>
                                        </div>
                                        <div class="lek" >
                                            <div class="proposal-number-cutomer-detail" id="PRICE_INCLUDE_VAT">
                                                <ul>
                                                    <li class="mt-3">
                                                        <b>Subtotal</b>
                                                        <span id="total-amount"></span>
                                                    </li>
                                                    <li class="mt-3">
                                                        <b>Price Before Tax</b>
                                                        <span id="Net-price"></span>
                                                    </li>
                                                    <li class="mt-3">
                                                        <b>Value Added Tax</b>
                                                        <span id="total-Vat"></span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="proposal-number-cutomer-detail" id="PRICE_EXCLUDE_VAT" style="display: none;">
                                                <ul>
                                                    <li class="mt-3">
                                                        <b>Subtotal</b>
                                                        <span id="total-amountEXCLUDE"></span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="proposal-number-cutomer-detail" id="PRICE_PLUS_VAT" style="display: none;">
                                                <ul>
                                                    <li class="mt-3">
                                                        <b>Subtotal</b>
                                                        <span id="total-amountpus"></span>
                                                    </li>
                                                    <li class="mt-3">
                                                        <b>Value Added Tax</b>
                                                        <span id="total-Vatpus"></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-end" >
                                        <b class="text-center text-white p-2" style="font-size: 14px; background-color: #2D7F7B; border-radius: 5px; " ><p class="mr-2" style="width:260px;" >Net Total <span id="Net-Total">0</span></p></b>
                                    </div>
                                    <div class="wrap-b">
                                        <div class="kw">
                                        </div>

                                        <div class="lek mt-3" style="border-top:2px solid #2D7F7B;">
                                            <div class="proposal-number-cutomer-detail" id="Pax">
                                                <ul>
                                                    <li class="mt-3" >
                                                        <b>Number of Guests</b>
                                                        <span><span id="PaxToTal"></span><span> Adults</span> </span>
                                                        <input type="hidden" name="PaxToTalall" id="PaxToTalall">
                                                    </li>
                                                    <li class="mt-3">
                                                        <b>Average per person</b>
                                                        <span><span id="Average"></span> THB</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <strong class="com" style="font-size: 18px">Method of Payment</strong>
                                        </div>
                                        <span class="col-md-8 col-sm-12"id="Payment50" style="display: block" >
                                            Please make a 50% deposit within 7 days after confirmed. <br>
                                            Transfer to <strong> " Together Resort Limited Partnership "</strong> following banks details.<br>
                                            If you use transfer, Please inform Accounting / Finance Department Tel or LINE ID<span style="font-size: 18px"> @Together-resort</span><br>
                                            pay-in slip to number 032-708-888 every time for the correctness of payment allocation.<br>
                                        </span>
                                        <span class="col-md-8 col-sm-12"  id="Payment100" style="display: none">
                                            Please make a 100% deposit within 3 days after confirmed. <br>
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
                                                Back
                                            </button>
                                            <button type="button" class="btn btn-primary lift btn_modal btn-space" onclick="view({{$id}})">
                                                Send Email
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
        </div>
    </form>
    <input type="hidden" name="preview" value="1" id="preview">
    <input type="hidden" name="hiddenProductData" id="hiddenProductData">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="{{ asset('assets/js/daterangepicker.min.js')}}" defer></script>
    <script type="text/javascript" src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css')}}" />
    <script type="text/javascript">
        $(document).ready(function() {
            const checkinDate = moment(document.getElementById('Checkin').value, 'DD/MM/YYYY');
            const checkoutDate = moment(document.getElementById('Checkout').value, 'DD/MM/YYYY');
            var flexCheckChecked = document.getElementById('flexCheckChecked');
            var dayName = checkinDate.format('dddd'); // Format to get the day name
            var enddayName = checkoutDate.format('dddd'); // Format to get the day name


            if (['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'].includes(dayName)) {
                if (dayName === 'Thursday' && enddayName === 'Saturday') {
                    $('#calendartext').text("Weekday-Weekend");
                    $('#inputcalendartext').val("Weekday-Weekend");
                    flexCheckChecked.disabled = true;
                }else{
                    $('#calendartext').text("Weekday");
                    $('#inputcalendartext').val("Weekday");
                    flexCheckChecked.disabled = true;
                }
            } else if (['Friday','Saturday','Sunday'].includes(dayName)) {
                if (dayName === 'Saturday' && enddayName === 'Monday') {
                    $('#calendartext').text("Weekday-Weekend");
                    $('#inputcalendartext').val("Weekday-Weekend");
                    flexCheckChecked.disabled = true;
                }else{
                    $('#calendartext').text("Weekend");
                    $('#inputcalendartext').val("Weekend");
                    flexCheckChecked.disabled = true;
                }
            }
        });

        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Please select an option"
            });
            var dayview = @json($Quotation->day);
            var nightview = @json($Quotation->night);

            var day = dayview ? dayview : '-';
            var night = nightview ? nightview : '-';

            console.log(day, night);
            var adult ={{$Quotation->adult}};
            var children ={{$Quotation->children ? $Quotation->children : 0 }};
            $('#Adultpo').text(adult +' Adult');
            $('#Adultpoguest').text(adult +' Adult');

            $('#Childrenpo').text(' , '+ children +' Children');
            $('#Childrenpoguest').text(' , '+ children +' Children');

            $('#daypo').text(day + ' วัน');
            $('#nightpo').text(night + ' คืน');

            $('#daypoguest').text(day + ' วัน');
            $('#nightpoguest').text(night + ' คืน');
            //----------------ส่วนบน---------------
            var countrySelect = $('#select');
            var select = countrySelect.val();

            var select = document.getElementById("select");
            //------------------------บริษัท------------------
            var Companyshow = document.getElementById("Companyshow");
            var Company = document.getElementById("Company");
            // -----------------------ลูกค้า--------------------

            var Guest = document.getElementById("Guest");
            var Guestshow = document.getElementById("Guestshow");
            //-------------------ตาราง---------------------------
            var companyTable = document.getElementById("companyTable");

            var guestTable = document.getElementById("guestTable");
            if (select.value === "Company") {
                Companyshow.style.display = "Block";
                Guestshow.style.display = "none";
                guestTable.style.display = "none";
                Company.disabled = false;
                Company_Contact.disabled = false;
                Company_Contactname.disabled = false;
                Guest.disabled = true;
                companyTable.style.display = "flex";
                companyContact();
            } else {
                guestTable.style.display = "flex";
                Guestshow.style.display = "Block";
                Companyshow.style.display = "none";
                companyTable.style.display = "none";
                Company.disabled = true;
                Company_Contact.disabled = true;
                Company_Contactname.disabled = true;
                Guest.disabled = false;
                GuestContact();
            }
        });
        function showselectInput() {
            var select = document.getElementById("select");
           //------------------------บริษัท------------------
            var Companyshow = document.getElementById("Companyshow");
            var Company = document.getElementById("Company");
            // -----------------------ลูกค้า--------------------

            var Guest = document.getElementById("Guest");
            var Guestshow = document.getElementById("Guestshow");
            //-------------------ตาราง---------------------------
            var companyTable = document.getElementById("companyTable");

            var guestTable = document.getElementById("guestTable");
            if (select.value === "Company") {
                Companyshow.style.display = "Block";
                Guestshow.style.display = "none";
                guestTable.style.display = "none";
                Company.disabled = false;
                Company_Contact.disabled = false;
                Company_Contactname.disabled = false;
                Guest.disabled = true;
                companyTable.style.display = "flex";
            } else {
                guestTable.style.display = "flex";
                Guestshow.style.display = "Block";
                Companyshow.style.display = "none";
                companyTable.style.display = "none";
                Company.disabled = true;
                Company_Contact.disabled = true;
                Company_Contactname.disabled = true;
                Guest.disabled = false;
            }
        }
        function companyContact() {
            var companyID = $('#Company').val();
            console.log(companyID);
            jQuery.ajax({
                type: "GET",
                url: "{!! url('/Proposal/create/company/" + companyID + "') !!}",
                datatype: "JSON",
                async: false,
                success: function(response) {
                    var prename = response.prename.name_th;
                    var fullName = prename+response.data.First_name + ' ' + response.data.Last_name;


                    var fullid = response.data.id ;
                    if (response.Company_type.name_th === 'บริษัทจำกัด') {
                        var fullNameCompany = 'บริษัท' + ' ' + response.company.Company_Name + ' ' + 'จำกัด';
                    }
                    else if (response.Company_type.name_th === 'บริษัทมหาชนจำกัด') {
                        var fullNameCompany = 'บริษัท' + ' ' + response.company.Company_Name + ' ' + 'จำกัด'+' '+'(มหาชน)';
                    }
                    else if (response.Company_type.name_th === 'ห้างหุ้นส่วนจำกัด') {
                        var fullNameCompany = 'ห้างหุ้นส่วนจำกัด' + ' ' + response.company.Company_Name ;
                    }else{
                        var fullNameCompany = response.Company_type.name_th + response.company.Company_Name ;
                    }
                    var Address = response.company.Address + ' '+ 'ตำบล'+ response.Tambon.name_th + ' '+' อำเภอ'+response.amphures.name_th + ' ' + 'จังหวัด'+ response.province.name_th + ' ' + response.Tambon.Zip_Code;
                    var companyfax = response.company_fax.Fax_number;
                    var CompanyEmail = response.company.Company_Email;
                    var Discount_Contract_Rate = response.company.Discount_Contract_Rate;
                    var TaxpayerIdentification = response.company.Taxpayer_Identification;
                    var companyphone = response.company_phone.Phone_number;

                    var Contactphones =response.Contact_phones.Phone_number;
                    var Contactemail =response.data.Email;
                    var formattedPhoneNumber = companyphone;


                    var formattedContactphones = Contactphones;
                    $('#Company_Contact').val(fullName).prop('disabled', true);
                    $('#Company_Discount').val(Discount_Contract_Rate);
                    $('#Company_Contactname').val(fullid);
                    $('#Company_name').text(fullNameCompany);
                    $('#Address').text(Address);
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
                    var Address = response.data.Address + ' '+ 'ตำบล'+ response.Tambon.name_th+' '+' อำเภอ'+response.amphures.name_th + ' ' + 'จังหวัด'+ response.province.name_th + ' ' + response.Tambon.Zip_Code;
                    var Email = response.data.Email;
                    var Identification = response.data.Identification_Number;
                    var phone = response.phone.Phone_number;


                    var formattedPhoneNumber = phone;

                    $('#guest_name').text(fullName);
                    $('#guestAddress').text(Address);
                    $('#guest_Number').text(formattedPhoneNumber);
                    $('#guest_Email').text(Email);
                    $('#guestTaxpayer').text(Identification);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX request failed: ", status, error);
                }
            });
        }
        $(document).ready(function() {
            var dateInput = document.getElementById('Checkin');
            var dateout = document.getElementById('Checkout');
            var Day = document.getElementById('Day');
            var Night = document.getElementById('Night');
            var flexCheckChecked = document.getElementById('flexCheckChecked');

            // ตรวจสอบค่า Checkin และตั้งค่า disabled และ flexCheckChecked
            function updateFields() {
                var Checkin = dateInput.value;

                if (Checkin === "" || Checkin === null) {
                    dateInput.disabled = true;
                    dateout.disabled = true;
                    Day.disabled = true;
                    Night.disabled = true;
                    flexCheckChecked.checked = true;
                    flexCheckChecked.disabled = true;
                    dateInput.classList.add('disabled-input');
                    dateout.classList.add('disabled-input');

                    $('#calendartext').text('No Check in date');
                    $('#checkinpo').text('No Check in date');// ตั้งค่า flexCheckChecked เป็น checked
                    $('#checkoutpo').text('-');
                    $('#checkinpoguest').text('No Check in date');// ตั้งค่า flexCheckChecked เป็น checked
                    $('#checkoutpoguest').text('-');
                    $('#daypo').text('-');
                    $('#nightpo').text(' ');
                    $('#daypoguest').text('-');
                    $('#nightpoguest').text(' ');
                } else {
                    dateInput.classList.remove('disabled-input');
                    dateout.classList.remove('disabled-input');
                    dateInput.disabled = false;
                    dateout.disabled = false;
                    Day.disabled = false;
                    Night.disabled = false;
                    flexCheckChecked.checked = false;
                // ตั้งค่า flexCheckChecked เป็น unchecked
                }
            }

            // เรียกใช้ updateFields เมื่อโหลดเริ่มต้น
            updateFields();

            // ตั้งค่าการเปลี่ยนแปลงสำหรับ flexCheckChecked
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
                    $('#checkinpoguest').text('No Check in date');
                    $('#checkoutpoguest').text('-');
                    $('#daypo').text('-');
                    $('#nightpo').text(' ');
                    $('#Checkin').val('');
                    $('#Checkout').val('');
                    $('#Day').val('');
                    $('#Night').val('');
                    $('#calendartext').text('-');
                    month();
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
        });
        $(document).on('keyup', '#Children', function() {
            var Children =  Number($(this).val());
            $('#Childrenpo').text(' , '+ Children +' Children');
            $('#Childrenpoguest').text(' , '+ Children +' Children');
            totalAmost();
        });
        $(document).on('keyup', '#Adult', function() {
            var adult =  Number($(this).val());
            $('#Adultpo').text(adult +' Adult');
            $('#Adultpoguest').text(adult +' Adult');
            totalAmost();
        });
        $(document).on('keyup', '#DiscountAmount', function() {
            var DiscountAmount =  Number($(this).val());
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
            } else if (Mevent == '54'){
                $('#Payment50').css('display', 'none');
                $('#Payment100').css('display', 'block');
            }else{
                $('#Payment50').css('display', 'block');
                $('#Payment100').css('display', 'none');
            }
        });
    </script>
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        $(function() {
            var checkinDate = document.getElementById('inputcalendartext').value;
            const checkoutDate = moment(document.getElementById('Checkout').value, 'DD/MM/YYYY');

            var enddayName = checkoutDate.format('dddd');
            var DiscountAmount = document.getElementById('DiscountAmount').value;
            var Add_discount = document.getElementById('Add_discount').value;
            $('#Checkin').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                autoApply: true,
                minDate: moment().startOf('day'),
                locale: {
                    format: 'DD/MM/YYYY' // ฟอร์แมตเป็น DD/MM/YYYY
                },
                isInvalidDate: function(date) {
                    if (checkinDate == 'Weekday') {
                        if (checkinDate === 'Weekday' && ['Friday','Saturday','Sunday'].includes(date.format('dddd'))) {
                            return true; // ไม่ให้เลือกวันในช่วงนี้
                        }
                    }else if (checkinDate == 'Weekend') {
                        if (checkinDate === 'Weekend' && ['Sunday','Monday', 'Tuesday', 'Wednesday', 'Thursday'].includes(date.format('dddd'))) {
                            return true; // ไม่ให้เลือกวันในช่วงนี้
                        }
                    }else if (checkinDate == 'Weekday-Weekend' && enddayName == 'Saturday'|| enddayName == 'Monday') {
                        if (checkinDate === 'Weekday-Weekend' && ['Monday','Sunday', 'Tuesday', 'Wednesday', 'Friday'].includes(date.format('dddd'))) {
                            return true; // ไม่ให้เลือกวัน
                        }
                    }
                }
            });
            $('#Checkin').on('apply.daterangepicker', function(ev, picker) {
                var datefirst = picker.startDate.format('DD/MM/YYYY');
                $(this).val(datefirst);
                $('#CheckinNew').val(datefirst);
                var currentMonthIndex = picker.startDate.month(); // จะได้หมายเลขเดือน (0-11)
                $('#inputmonth').val(currentMonthIndex + 1);
                CheckDateAdditional();
            });
        });
        $(function() {
            var checkinValue = document.getElementById('Checkin').value;
            var DiscountAmount = document.getElementById('DiscountAmount').value;
            var Add_discount = document.getElementById('Add_discount').value;
            $('#Checkout').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                autoApply: true,
                minDate: moment().startOf('day'),
                locale: {
                    format: 'DD/MM/YYYY' // ฟอร์แมตเป็น dd/mm/yyyy
                },
                isInvalidDate: function(date) {
                    var CheckinNew = document.getElementById('CheckinNew').value;
                    var checkDate = document.getElementById('inputcalendartext').value;
                    var momentCheckinNew = moment(CheckinNew, 'DD/MM/YYYY');
                    var indayName = momentCheckinNew.format('dddd'); // รับค่าเป็นชื่อวัน
                    if (checkDate === 'Weekday') {
                        if (indayName === 'Thursday') {
                            if ([ 'Saturday'].includes(date.format('dddd'))) {
                                return true;
                            }
                        }else{
                            return false;
                        }
                    } else if (checkDate === 'Weekend') {
                        if (indayName === 'Friday') {
                            return false;
                        }else{
                            if ([ 'Monday'].includes(date.format('dddd'))) {
                                return true;
                            }
                        }
                    } else if (checkDate === 'Weekday-Weekend'){
                        if (indayName === 'Thursday') {
                            if (['Monday', 'Sunday', 'Tuesday', 'Wednesday', 'Friday', 'Thursday'].includes(date.format('dddd'))) {
                                return true;
                            }
                        } else {
                            if (['Saturday', 'Sunday', 'Tuesday', 'Wednesday', 'Friday', 'Thursday'].includes(date.format('dddd'))) {
                                return true;
                            }
                        }
                    }else{
                        if (['Saturday', 'Sunday','Monday', 'Tuesday', 'Wednesday', 'Friday', 'Thursday'].includes(date.format('dddd'))) {
                            return true;
                        }
                    }
                }
            });
            $('#Checkout').on('apply.daterangepicker', function(ev, picker) {
                var dateend = picker.startDate.format('DD/MM/YYYY');
                $(this).val(dateend);
                $('#CheckoutNew').val(dateend);

                var checkDate = document.getElementById('inputcalendartext').value;
                var CheckinNew = document.getElementById('CheckinNew').value;

                // แปลงวันที่ CheckinNew และ dateend เป็น moment object
                var datefirst = moment(CheckinNew, 'DD/MM/YYYY');
                var dateendMoment = moment(dateend, 'DD/MM/YYYY');

                // ตรวจสอบว่า checkinDate คือ 'Weekday-Weekend'
                if (checkDate === 'Weekday-Weekend') {
                    // ตรวจสอบว่า datefirst และ dateend ถูกต้อง
                    if (datefirst.isValid() && dateendMoment.isValid()) {
                        // คำนวณความแตกต่างระหว่าง datefirst และ dateend เป็นจำนวนวัน
                        var diffDays = dateendMoment.diff(datefirst, 'days');

                        // เช็คว่าห่างกันไม่เกิน 3 วันหรือไม่
                        if (diffDays <= 3) {
                            console.log('วันห่างกันไม่เกิน 3 วัน');
                            // คุณสามารถทำสิ่งที่ต้องการได้ที่นี่ เช่น อนุญาตให้เลือกวันที่
                        } else {
                            alert('วันสิ้นสุดไม่สามารถห่างจากวันเริ่มต้นเกิน 3 วันได้');
                            // เพิ่มโค้ดสำหรับการแสดงข้อผิดพลาด หรือการแจ้งเตือน
                        }
                    } else {
                        console.error('วันที่ไม่ถูกต้อง');
                    }
                }
                var daymonthName = datefirst.format('MMMM'); // ชื่อเดือนเต็ม เช่น January, February
                var endmonthName = dateendMoment.format('MMMM');   // ชื่อเดือนเต็ม เช่น January, February
                var monthDiff = dateendMoment.diff(datefirst, 'months');
                var month;

                if (daymonthName === endmonthName) {
                    month = monthDiff; // เดือนเดียวกัน
                } else {
                    month = monthDiff + 1; // ข้ามเดือน
                }

                $('#checkmonth').val(month);
                CheckDateAdditional();
            });
        });
        function CheckDateAdditional() {
            var CheckinNew = document.getElementById('CheckinNew').value;
            var CheckoutNew = document.getElementById('CheckoutNew').value;
            var momentCheckinNew = moment(CheckinNew, 'DD/MM/YYYY');
            var momentCheckoutNew = moment(CheckoutNew, 'DD/MM/YYYY');
            const checkinDateValue = momentCheckinNew.format('YYYY-MM-DD');
            const checkoutDateValue = momentCheckoutNew.format('YYYY-MM-DD');
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
                $('#daypoguest').text(totalDays + ' วัน');
                $('#nightpoguest').text(nights + ' คืน');
            } else if (checkoutDate.getTime() === checkinDate.getTime()) {
                const totalDays = 1;
                $('#Day').val(isNaN(totalDays) ? '0' : totalDays);
                $('#Night').val('0');

                $('#checkinpo').text(moment(checkinDateValue).format('DD/MM/YYYY'));
                $('#checkoutpo').text(moment(checkoutDateValue).format('DD/MM/YYYY'));
                $('#daypo').text(totalDays + ' วัน');
                $('#nightpo').text('0 คืน');
                $('#daypoguest').text(totalDays + ' วัน');
                $('#nightpoguest').text('0 คืน');
            } else {
                if (CheckoutNew) {
                    alert('วัน Check-out ต้องมากกว่าวัน Check-in');
                    $('#Day').val('0');
                    $('#Night').val('0');
                    $('#Checkin').val('');
                    $('#Checkout').val('');
                }
            }

            month();
        }

        function setMinDate() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('Checkin').setAttribute('min', today);
            document.getElementById('Checkout').setAttribute('min', today);
        }

        // เรียกใช้เมื่อโหลดหน้า
        setMinDate();
        document.addEventListener('DOMContentLoaded', setMinDate);
        function month() {
            var checkmonthValue = document.getElementById('checkmonth').value; // ค่าจาก input checkmonth
            var inputmonth = document.getElementById('inputmonth').value; // ค่าจาก input inputmonth
            var start = moment(); // เริ่มที่วันที่ปัจจุบัน
            var end; // ประกาศตัวแปร end
            var currentMonthIndex = start.month();
            var monthDiff = inputmonth - currentMonthIndex;
              // ถ้าเดือนปัจจุบันมากกว่าหรือเท่ากับเป้าหมายเดือน
            if (monthDiff < 0) {
                monthDiff += 12; // เพิ่ม 12 เดือนถ้าข้ามปี
            }

            if (monthDiff <= 1) {
                start = moment(); // เริ่มที่วันนี้
                end = moment().add(7, 'days'); // สิ้นสุดอีก 7 วัน
            } else if (monthDiff >= 2 && monthDiff < 3 ) {
                start = moment(); // เริ่มที่วันนี้
                end = moment().add(15, 'days'); // สิ้นสุดอีก 15 วัน
            } else {
                start = moment(); // เริ่มที่วันนี้
                end = moment().add(30, 'days'); // สิ้นสุดอีก 30 วัน
            }

            function cb(start, end) {
                $('#datestart').val(start.format('DD/MM/Y')); // แสดงวันที่เริ่มต้น
                $('#dateex').val(end.format('DD/MM/Y')); // แสดงวันที่สิ้นสุด
            }

            // ตั้งค่า daterangepicker
            $('#reportrange1').daterangepicker({
                start: start,
                end: end,
                ranges: {
                    '3 Days': [moment(), moment().add(3, 'days')],
                    '7 Days': [moment(), moment().add(7, 'days')],
                    '15 Days': [moment(), moment().add(15, 'days')],
                    '30 Days': [moment(), moment().add(30, 'days')],
                },
                autoApply: true, // ใช้เพื่อไม่ต้องกด Apply
            }, cb);

            cb(start, end); // เรียก callback ทันทีหลังจากตั้งค่าเริ่มต้น
        }
    </script>
    <script>
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
        function submitPreview() {
            var previewValue = document.getElementById("preview").value;

            // สร้าง input แบบ hidden ใหม่
            var input = document.createElement("input");
            input.type = "hidden";
            input.name = "preview";
            input.value = previewValue;

            // เพิ่ม input ลงในฟอร์ม
            document.getElementById("myForm").appendChild(input);
            document.getElementById("myForm").setAttribute("target","_blank");
            document.getElementById("myForm").submit();
        }
        $(document).on('click', '.remove-button1', function() {
                var productId = $(this).val();
                var table2 = $('#main').DataTable();
                var row = table2.row($(this).parents('tr'));
                var irow = $(this).closest('tr.child').prev();
                table2.row(irow).remove().draw();
                row.remove();
                table2.draw();

                $('#trselectmain' + productId).remove();
                $('#display-selected-items tr').each(function(index) {
                    $(this).find('td:first').text(index+1); // Change the text of the first cell to be the new sequence number
                });

                // Optionally, call a function to update totals after removing a row
                if (typeof totalAmost === 'function') {
                    totalAmost();
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

        //----------------------------------------รายการ---------------------------
        $(document).ready(function() {
            $(document).on('keyup', '.quantitymain', function() {
                for (let i = 0; i < 50; i++) {
                    var number_ID = $(this).attr('rel');
                    var quantitymain =  Number($(this).val());
                    var discountmain =  $('#discountmain'+number_ID).val();
                    var unitmain =  $('#unitmain'+number_ID).val();
                    var paxmain = parseFloat($('#pax' + number_ID).val());
                    if (isNaN(paxmain)) {
                        paxmain = 0;
                    }
                    var pax = paxmain*quantitymain;
                    $('#paxtotal'+number_ID).text(pax);
                    var number = Number($('#number-product').val());
                    var price = parseFloat($('#totalprice-unit-'+number_ID).val().replace(/,/g, ''));
                    var pricenew = quantitymain*unitmain*price
                    console.log(discountmain);

                    if (discountmain === "" || discountmain == 0) {
                        var pricediscount = pricenew - (pricenew*discountmain /100);
                        $('#allcount'+number_ID).text(pricediscount.toLocaleString('th-TH', {minimumFractionDigits: 2}));
                        var pricediscount =  (price*discountmain /100);
                        var allcount0 = price - pricediscount;// ถ้าเป็นค่าว่างหรือ 0 ให้ค่าเป็น 1
                        $('#netdiscount'+number_ID).text(allcount0.toLocaleString('th-TH', {minimumFractionDigits: 2}));
                    }else{
                        var pricediscount = pricenew - (pricenew*discountmain /100);
                        $('#allcount'+number_ID).text(pricediscount.toLocaleString('th-TH', {minimumFractionDigits: 2}));
                        var allcount0 = price-(price*discountmain /100);// ถ้าเป็นค่าว่างหรือ 0 ให้ค่าเป็น 1
                        $('#netdiscount'+number_ID).text(allcount0.toLocaleString('th-TH', {minimumFractionDigits: 2}));
                    }
                    // $('#allcount0'+number_ID).text(allcount0.toLocaleString('th-TH', {minimumFractionDigits: 2}));
                    totalAmost();
                }
            });
            $(document).on('keyup', '.discountmain', function() {
                for (let i = 0; i < 50; i++) {
                    var number_ID = $(this).attr('rel');
                    var discountmain =  Number($(this).val());

                    var quantitymain =  $('#quantitymain'+number_ID).val();
                    var unitmain =  $('#unitmain'+number_ID).val();

                    var number = Number($('#number-product').val());
                    var price = parseFloat($('#totalprice-unit-'+number_ID).val().replace(/,/g, ''));


                   var pricenew = quantitymain*unitmain*price
                    console.log(discountmain);

                    if (discountmain === "" || discountmain == 0) {
                        var pricediscount = pricenew - (pricenew*discountmain /100);
                        $('#allcount'+number_ID).text(pricediscount.toLocaleString('th-TH', {minimumFractionDigits: 2}));
                        var pricediscount =  (price*discountmain /100);
                        var allcount0 = price - pricediscount;// ถ้าเป็นค่าว่างหรือ 0 ให้ค่าเป็น 1
                        $('#netdiscount'+number_ID).text(allcount0.toLocaleString('th-TH', {minimumFractionDigits: 2}));
                    }else{
                        var pricediscount = pricenew - (pricenew*discountmain /100);
                        $('#allcount'+number_ID).text(pricediscount.toLocaleString('th-TH', {minimumFractionDigits: 2}));
                        var allcount0 = price-(price*discountmain /100);// ถ้าเป็นค่าว่างหรือ 0 ให้ค่าเป็น 1
                        $('#netdiscount'+number_ID).text(allcount0.toLocaleString('th-TH', {minimumFractionDigits: 2}));
                    }
                    totalAmost();

                }

            });
            $(document).on('keyup', '.unitmain', function() {
                for (let i = 0; i < 50; i++) {
                    var number_ID = $(this).attr('rel');
                    var unitmain =  Number($(this).val());
                    var quantitymain =  $('#quantitymain'+number_ID).val();
                    var discountmain =  $('#discountmain'+number_ID).val();
                    var number = Number($('#number-product').val());
                    var price = parseFloat($('#totalprice-unit-'+number_ID).val().replace(/,/g, ''));
                    console.log(number_ID);

                    var pricenew = quantitymain*unitmain*price
                    console.log(discountmain);

                    if (discountmain === "" || discountmain == 0) {
                        var pricediscount = pricenew - (pricenew*discountmain /100);
                        $('#allcount'+number_ID).text(pricediscount.toLocaleString('th-TH', {minimumFractionDigits: 2}));
                        var pricediscount =  (price*discountmain /100);
                        var allcount0 = price - pricediscount;// ถ้าเป็นค่าว่างหรือ 0 ให้ค่าเป็น 1
                        $('#netdiscount'+number_ID).text(allcount0.toLocaleString('th-TH', {minimumFractionDigits: 2}));
                    }else{
                        var pricediscount = pricenew - (pricenew*discountmain /100);
                        $('#allcount'+number_ID).text(pricediscount.toLocaleString('th-TH', {minimumFractionDigits: 2}));
                        var allcount0 = price-(price*discountmain /100);// ถ้าเป็นค่าว่างหรือ 0 ให้ค่าเป็น 1
                        $('#netdiscount'+number_ID).text(allcount0.toLocaleString('th-TH', {minimumFractionDigits: 2}));
                    }


                    totalAmost();
                }
            });
            $(document).on('keyup', '.quantity-input', function() {
                for (let i = 0; i < 50; i++) {
                    var number_ID = $(this).attr('rel');
                    var quantitymain =  Number($(this).val());
                    var discountmain =  parseFloat($('#discount'+number_ID).val().replace(/,/g, ''));
                    var unitmain =  parseFloat($('#unit'+number_ID).val().replace(/,/g, ''));
                    var price = parseFloat($('#totalprice-unit'+number_ID).val().replace(/,/g, ''));
                    var pricenew = quantitymain*unitmain*price
                    console.log(discountmain);
                    if (discountmain === " " || discountmain == 0) {
                        var allcount0 = price;
                        $('#net_discount'+number_ID).text(allcount0.toLocaleString('th-TH', {minimumFractionDigits: 2}));
                        var pricediscount = pricenew;
                        $('#all-total'+number_ID).text(pricediscount.toLocaleString('th-TH', {minimumFractionDigits: 2}));
                    }else{
                        var pricediscount = pricenew - (pricenew*discountmain /100);
                        $('#all-total'+number_ID).text(pricediscount.toLocaleString('th-TH', {minimumFractionDigits: 2}));
                        var pricediscount =  (price*discountmain /100);
                        var allcount0 = price - pricediscount;
                        $('#net_discount'+number_ID).text(allcount0.toLocaleString('th-TH', {minimumFractionDigits: 2}));
                    }
                    var paxmain = parseFloat($('#pax' + number_ID).val());
                    if (isNaN(paxmain)) {
                        paxmain = 0;
                    }
                    var pax = paxmain*quantitymain;
                    $('#paxtotal'+number_ID).text(pax);
                    totalAmost();
                }
            });
            $(document).on('keyup', '.discount-input', function() {
                for (let i = 0; i < 50; i++) {
                    var number_ID = $(this).attr('rel');
                    var discountmain =  Number($(this).val().trim());

                    // ถ้าค่าเป็นค่าว่างหรือไม่ใช่ตัวเลข จะตั้งค่าเป็น 0
                    if (isNaN(discountmain) || $(this).val().trim() === "") {
                        discountmain = 0; // ตั้งค่าเป็น 0 ถ้าค่าว่าง
                    }

                    var maxdiscount = parseFloat($('#maxdiscount'+number_ID).val().replace(/,/g, ''));
                    var User_discount = parseFloat(document.getElementById('User_discount').value) || 0;
                    var Add_discount = parseFloat(document.getElementById('Add_discount').value) || 0;
                    var SpecialDiscount = User_discount + Add_discount;
                    var quantitymain = parseFloat($('#quantity'+number_ID).val().replace(/,/g, ''));
                    var price = parseFloat($('#totalprice-unit'+number_ID).val().replace(/,/g, ''));
                    var unitmain = parseFloat($('#unit'+number_ID).val().replace(/,/g, ''));
                    var pricenew = quantitymain * unitmain * price;

                    // ถ้าไม่มีการกรอกค่า (หรือค่าเป็น 0) ให้แสดงราคาเต็ม
                    if (discountmain === 0) {
                        var allcount0 = price;
                        $('#net_discount'+number_ID).text(allcount0.toLocaleString('th-TH', {minimumFractionDigits: 2}));
                        var pricediscount = pricenew;
                        $('#all-total'+number_ID).text(pricediscount.toLocaleString('th-TH', {minimumFractionDigits: 2}));
                    } else {
                        // ตรวจสอบว่าค่าส่วนลดเกิน maxdiscount หรือ SpecialDiscount หรือไม่
                        if (discountmain > SpecialDiscount) {
                            if (SpecialDiscount > maxdiscount) {
                                discountmain = maxdiscount;
                                $(this).val(discountmain); // บังคับค่าเป็น maxdiscount
                            }else{
                                discountmain = SpecialDiscount;
                                $(this).val(discountmain); // บังคับค่าเป็น maxdiscount
                            }
                        }else{
                            if (discountmain > maxdiscount) {
                                discountmain = maxdiscount;
                                $(this).val(discountmain); // บังคับค่าเป็น maxdiscount
                            }
                        }
                        console.log(discountmain);

                        var pricediscount = pricenew - (pricenew * discountmain / 100);
                        $('#all-total'+number_ID).text(pricediscount.toLocaleString('th-TH', {minimumFractionDigits: 2}));
                        var pricediscount = (price * discountmain / 100);
                        var allcount0 = price - pricediscount;
                        $('#net_discount'+number_ID).text(allcount0.toLocaleString('th-TH', {minimumFractionDigits: 2}));
                    }

                    var paxmain = parseFloat($('#pax' + number_ID).val());
                    if (isNaN(paxmain)) {
                        paxmain = 0;
                    }
                    var pax = paxmain * quantitymain;
                    $('#paxtotal'+number_ID).text(pax);
                    totalAmost();
                }
            });


            $(document).on('keyup', '.unit-input', function() {
                for (let i = 0; i < 50; i++) {
                    var number_ID = $(this).attr('rel');
                    var unitmain =  Number($(this).val());
                    var discountmain =  parseFloat($('#discount'+number_ID).val().replace(/,/g, ''));
                    var quantitymain  =  parseFloat($('#quantity'+number_ID).val().replace(/,/g, ''));
                    var price = parseFloat($('#totalprice-unit'+number_ID).val().replace(/,/g, ''));
                    var pricenew = quantitymain*unitmain*price;

                    console.log(discountmain);

                    if (discountmain === " " || discountmain == 0 ||  discountmain == null) {
                        console.log(1);
                        var allcount0 = price;
                        $('#net_discount'+number_ID).text(allcount0.toLocaleString('th-TH', {minimumFractionDigits: 2}));
                        var pricediscount = pricenew;
                        $('#all-total'+number_ID).text(pricediscount.toLocaleString('th-TH', {minimumFractionDigits: 2}));
                    }else{
                        var pricediscount = pricenew - (pricenew*discountmain /100);
                        $('#all-total'+number_ID).text(pricediscount.toLocaleString('th-TH', {minimumFractionDigits: 2}));
                        var pricediscount =  (price*discountmain /100);
                        var allcount0 = price - pricediscount;
                        $('#net_discount'+number_ID).text(allcount0.toLocaleString('th-TH', {minimumFractionDigits: 2}));
                    }
                    totalAmost();
                }
            });
            $(document).on('keyup', '.DiscountAmount', function() {
                var DiscountAmount =  Number($(this).val());
                if (DiscountAmount) {
                    $('#Special').css('display', 'table-row');
                    $('#Subtotal').css('display', 'table-row');
                }else{
                    $('#Special').css('display', 'none');
                    $('#Subtotal').css('display', 'none');
                }

            });
            totalAmost();
        });
        function totalAmost() {
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
                let Discount = 0;
                let paxtotal=0;
                let PaxToTalall=0;
                var discountElement  = $('#DiscountAmount').val();
                $('#display-selected-items tr').each(function() {
                    let priceCell = $(this).find('td').eq(8);
                    let pricetotal = parseFloat(priceCell.text().replace(/,/g, '')) || 0;
                    var Discount = parseFloat(discountElement)|| 0;
                    let allpax = $(this).find('td').eq(2);
                    let pax = parseFloat(allpax.text());
                        if (isNaN(pax)) { // ตรวจสอบว่าค่าที่แปลงเป็น NaN หรือไม่
                            pax = 0; // แปลง NaN เป็น 0
                        }
                    if (typevat == '50') {
                        paxtotal +=pax;
                        PaxToTalall = paxtotal;
                        allprice += pricetotal;
                        lessDiscount = allprice-Discount;
                        beforetax= lessDiscount/1.07;
                        addedtax = lessDiscount-beforetax;
                        Nettotal= beforetax+addedtax;
                        totalperson = Nettotal/paxtotal;

                        $('#total-amount').text(isNaN(allprice) ? '0' : allprice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#sp').text(isNaN(Discount) ? '0' : Discount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
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
                        lessDiscount = allprice-Discount;
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
                        lessDiscount = allprice-Discount;
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

            });
        }
        totalAmost();
    </script>
    <script>
        function BACKtoEdit(){
            event.preventDefault();
            Swal.fire({
                title: "คุณต้องการยกเลิกใช่หรือไม่?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "ตกลง",
                cancelButtonText: "ยกเลิก",
                confirmButtonColor: "#2C7F7A",
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
            var Quotationold = $('#Quotationold').val();
            var Quotation_ID = $('#Quotation_ID').val();
            var message = `หากบันทึกข้อมูลใบข้อเสนอรหัส ${Quotationold} ทำการยกเลิกใบข้อเสนอ`;
            var title = `คุณต้องการบันทึกข้อมูลรหัส ${Quotation_ID} ใช่หรือไม่?`;
            Swal.fire({
                title: title,
                text: message,
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "บันทึกข้อมูล",
                cancelButtonText: "ยกเลิก",
                confirmButtonColor: "#2C7F7A",
                dangerMode: true
            }).then((result) => {
                if (result.isConfirmed) {
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
        function view(id){
            event.preventDefault();
            Swal.fire({
                title: "คุณต้องการส่งอีเมล์ใช่หรือไม่?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "ตกลง",
                cancelButtonText: "ยกเลิก",
                confirmButtonColor: "#28a745",
                dangerMode: true
            }).then((result) => {
                if (result.isConfirmed) { // ตรวจสอบว่าเลือก "ตกลง" ก่อนที่จะเปลี่ยนเส้นทาง
                    window.location.href = "/Proposal/send/email/" + id;
                }
            });
        }
    </script>
@endsection
