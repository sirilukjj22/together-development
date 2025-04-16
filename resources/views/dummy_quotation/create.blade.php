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
        padding-bottom: 2px;
        font-size: 18px;
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
    td.today {
        background-color: transparent !important; /* ไม่ให้มีสีพื้นหลัง */
    }
</style>
@section('content')
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Create Dummy Proposal</div>
                </div>
                <div class="col-auto">
                </div>
            </div> <!-- .row end -->
        </div>
    </div>
    <form id="myForm" action="{{route('DummyQuotation.save')}}" method="POST">
    @csrf
        <div id="content-index" class="body d-flex py-lg-4 py-3">
            <div class="container-xl">
                <div class="row clearfix">
                    <div class="col-md-12 col-12">
                        <div class="card p-4 mb-4">
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
                                                <b class="titleQuotation" style="font-size: 20px;color:rgb(255, 255, 255);">Dummy Proposal</b>
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
                                <div class="row">
                                    <div class="col-lg-2 col-md-12 col-sm-12">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" > No Check In Date</label>
                                    </div>
                                    <div class="col-lg-10 col-md-12 col-sm-12" style="float: right">
                                        <span><b> Date Type : </b><span id="calendartext" style="font-size: 16px;color:rgb(0, 0, 0);"></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <span for="chekin">Check In Date
                                    <div class="input-group">

                                        <input type="text" name="Checkin" id="Checkin" class="form-control readonly-input" readonly  required>
                                        <input type="hidden" id="inputmonth" name="inputmonth" value="">
                                        <input type="hidden" id="Date_type" name="Date_type" value="">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="border-radius:  0  5px 5px  0 ">
                                                <i class="fas fa-calendar-alt"></i> <!-- ไอคอนปฏิทิน -->
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <span for="chekin">Check Out Date </span>
                                    <div class="input-group"  >
                                        <input type="text" name="Checkout" id="Checkout" class="form-control readonly-input"   readonly required>
                                        <input type="hidden" id="checkmonth" name="checkmonth" value="">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"style="border-radius:  0  5px 5px  0 ">
                                                <i class="fas fa-calendar-alt"></i> <!-- ไอคอนปฏิทิน -->
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <span for="">จำนวน</span>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="Day" id="Day" placeholder="จำนวนวัน" readonly>
                                        <span class="input-group-text">Day</span>
                                        <input type="text" class="form-control" name="Night" id="Night" placeholder="จำนวนคืน" readonly>
                                        <span class="input-group-text">Night</span>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <span class="star-red" style="display:none" id="Adultred" for="">จำนวนผู้เข้าพัก (ผู้ใหญ่/เด็ก) </span>
                                    <span  style="display:block" id="Adultblack" for="">จำนวนผู้เข้าพัก (ผู้ใหญ่/เด็ก) </span>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="Adult" id="Adult" placeholder="จำนวนผู้ใหญ่" @required(true)>
                                        <span class="input-group-text">ผู้ใหญ่</span>
                                        <input type="text" class="form-control" name="Children"id="Children" placeholder="จำนวนเด็ก" @required(true)>
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
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <span  for="">Company Commission</span>
                                    <div class="input-group">
                                        <input type="text" class="form-control"  name="Company_Commission_Rate_Code" disabled>
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
                                                <input class="form-control" type="text" name="Add_discount" id="Add_discount" value="" placeholder="ส่วนลดเพิ่มเติมคิดเป็น %"
                                                        oninput="if (parseFloat(this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)) > {{ Auth::user()->additional_discount }}) this.value = {{ Auth::user()->additional_discount }};"
                                                        onchange="adddis()">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <span  for="">Total User Discount</span>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="SpecialDiscount" id="SpecialDiscount"   placeholder="ส่วนลดคิดเป็น %" readonly>
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
                                            var User_discount = document.getElementById('User_discount').value;
                                            var Add_discount = document.getElementById('Add_discount').value;
                                            var total = User_discount+Add_discount;
                                            $('#SpecialDiscount').val(total);
                                        });
                                    </script>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <span  for="">Special Discount</span>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="DiscountAmount" id="DiscountAmount"  placeholder="ส่วนลดคิดเป็นบาท" required disabled>
                                        <span class="input-group-text">Bath</span>
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
                        <div class="card p-4 mb-4">
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
                                    <b class="font-upper com">Personal Information</b>
                                    <li >
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
                                        <span id="checkinpo"></span>
                                    </li>
                                    <li>
                                        <b>Check Out</b>
                                        <span id="checkoutpo"></span>
                                    </li>
                                    <li>
                                        <b>Length of Stay</b>
                                        <span style="display: flex"><p id="daypo" class="m-0"> </p><p id="nightpo" class="m-0"> </p></span>
                                    </li>
                                    <li>
                                        <b>Number of Guests</b>
                                        <span style="display: flex"><p id="Adultpo" class="m-0"> </p><p id="Childrenpo" class="m-0"> </p></span>
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
                                        <span id="checkinpoguest"></span>
                                    </li>
                                    <li>
                                        <b>Check Out</b>
                                        <span id="checkoutpoguest"></span>
                                    </li>
                                    <li>
                                        <b>Length of Stay</b>
                                        <span style="display: flex"><p id="daypoguest" class="m-0"> </p><p id="nightpoguest" class="m-0"> </p></span>
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
                                                <table id="mainselecttwo"  class=" example ui striped table nowrap unstackable hover">
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
                                <table id="main" class="example ui striped table nowrap unstackable">
                                    <thead >
                                        <tr>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;">No.</th>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;"data-priority="1">Description</th>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:1%;"></th>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:12%;">Quantity</th>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:12%;">Unit</th>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;">Price / Unit</th>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:12%;">Discount</th>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;">Net Price / Unit</th>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:5%;"data-priority="1">Amount</th>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:4%;"></th>
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
                            <div class="wrap-b">
                                <div class="kw" >
                                    <span >Notes or Special Comment</span>
                                    <textarea class="form-control mt-2"cols="30" rows="5"name="comment" id="comment" placeholder="Leave a comment here" id="floatingTextarea"></textarea>
                                </div>
                                <div class="lek" >
                                    <div class="proposal-number-cutomer-detail" id="PRICE_INCLUDE_VAT">
                                        <ul>
                                            {{-- <li class="mt-3">
                                                <b>Subtotal</b>
                                                <span id="total-amount"></span>
                                            </li> --}}
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
                                        @php
                                            $id = Auth::user()->id;
                                            $user =  DB::table('users')->where('id',$id)
                                            ->first();
                                        @endphp
                                        <div class="col-lg-2 centered-content">
                                            <span>ผู้ออกเอกสาร (ผู้ขาย)</span><br>
                                            @if ($user->signature)
                                                <img src="/upload/signature/{{$user->signature}}" style="width: 50%;"/>
                                            @endif
                                            @if ($user->firstname)
                                                <span>{{$user->firstname}} {{$user->lastname}}</span>
                                            @endif
                                            <span id="issue_date_document"></span>
                                        </div>
                                        <div class="col-lg-2 centered-content">
                                            <span>ผู้อนุมัติเอกสาร (ผู้ขาย)</span><br>
                                            <img src="/boss.png" style="width: 70%;"/>
                                            <span>Sopida Thuphom</span>
                                            <span id="issue_date_document1"></span>
                                        </div>
                                        <div class="col-lg-2 centered-content">
                                            <span>ตราประทับ (ผู้ขาย)</span>
                                            <img src="{{ asset('assets/images/' . $settingCompany->image) }}" style="width: 50%;">
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
                                    <button type="submit" class="btn btn-color-green lift btn_modal" onclick="confirmSubmit(event)">Save</button>
                                </div>
                                <div class="col-4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <input type="hidden" id="create" name="create">
    <input type="hidden" id="allRowsDataInput" name="allRowsData">
    <input type="hidden" id="allRowsDataInputSelect" name="allRowsDataSelect">
    <input type="hidden" name="preview" value="1" id="preview">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/js/daterangepicker.min.js')}}" defer></script>
    <script type="text/javascript" src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css')}}" />
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- dataTable -->

    <!-- dataTable -->
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.semanticui.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.semanticui.js"></script>
    <script>
        $(document).on('keyup', '#Children', function() {
            var Children =  Number($(this).val());
            $('#Childrenpo').text(' , '+ Children +' Children');
            $('#Childrenpoguest').text(' , '+ Children +' Children');
            totalAmost();
        });
        $(function() {
            // ฟอร์แมตวันที่ให้อยู่ในรูปแบบ dd/mm/yyyy
            $('#Checkin').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                autoApply: true,
                minDate: moment().startOf('day'),
                locale: {
                    format: 'DD/MM/YYYY' // ฟอร์แมตเป็น dd/mm/yyyy
                }
            });
            $('#Checkin').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY'));
                var currentMonthIndex = picker.startDate.month(); // จะได้หมายเลขเดือน (0-11)
                $('#inputmonth').val(currentMonthIndex + 1); // บันทึกใน input โดยเพิ่ม 1 เพื่อให้เป็น 1-12 แทน
                CheckDate();
            });
            $(document).on('wheel', function(e) {
                // Check if the date picker is open
                if ($('.daterangepicker').is(':visible')) {
                    // Close the date picker
                    $('.daterangepicker').hide();
                }
            });
            month();

        });
        $(function() {
            $('#Checkout').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                autoApply: true,
                minDate: moment().startOf('day'),
                locale: {
                    format: 'DD/MM/YYYY' // ฟอร์แมตเป็น dd/mm/yyyy
                }
            });
            $('#Checkout').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY'));
                CheckDate();
            });
            $(document).on('wheel', function(e) {
                // Check if the date picker is open
                if ($('.daterangepicker').is(':visible')) {
                    // Close the date picker
                    $('.daterangepicker').hide();
                }
            });
        });
        function CheckDate() {
            var CheckinNew = document.getElementById('Checkin').value;
            var CheckoutNew = document.getElementById('Checkout').value;

            var momentCheckinNew = moment(CheckinNew, 'DD/MM/YYYY');
            var momentCheckoutNew = moment(CheckoutNew, 'DD/MM/YYYY');

            // Retrieve the full month names
            var daymonthName = momentCheckinNew.format('MMMM');  // Full month name like January
            var endmonthName = momentCheckoutNew.format('MMMM'); // Full month name like January

            // Retrieve the full day names
            var dayName = momentCheckinNew.format('dddd'); // Full day name like Monday
            var enddayName = momentCheckoutNew.format('dddd'); // Full day name like Monday

            // Calculate the difference in months
            var monthDiff = momentCheckoutNew.diff(momentCheckinNew, 'months');
            $('#checkmonth').val(monthDiff);
            function getWeekNumber(d) {
                const date = new Date(d.getTime());
                date.setHours(0, 0, 0, 0);
                // ย้ายไปวันพฤหัสในสัปดาห์นี้ เพื่อความแม่นยำของ ISO week
                date.setDate(date.getDate() + 3 - ((date.getDay() + 6) % 7));
                const week1 = new Date(date.getFullYear(), 0, 4);
                return 1 + Math.round(((date - week1) / 86400000 - 3 + ((week1.getDay() + 6) % 7)) / 7);
            }
            const weekdayList = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'];
            const weekenddayList = ['Monday', 'Tuesday', 'Wednesday'];
            const weekendList = ['Thursday', 'Friday', 'Saturday'];
            const weekdayendList = ['Thursday', 'Friday', 'Saturday', 'Sunday'];
            const startWeek = getWeekNumber(momentCheckinNew.toDate());
            const endWeek = getWeekNumber(momentCheckoutNew.toDate());
            const isSameWeek = startWeek === endWeek && momentCheckinNew.year() === momentCheckoutNew.year();
            const weekDifference = Math.abs(startWeek - endWeek);
            if (['Thursday'].includes(dayName)) {
                if (enddayName === 'Friday'&& isSameWeek || dayName == enddayName && isSameWeek) {
                    $('#calendartext').text("Weekday");
                    $('#Date_type').val("Weekday");
                }else if (enddayName === 'Saturday' && isSameWeek) {
                    $('#calendartext').text("Weekday-Weekend");
                    $('#Date_type').val("Weekday-Weekend");
                }else if (weekDifference == 0  && enddayName === 'Sunday' ) {
                    $('#calendartext').text("Weekend");
                    $('#Date_type').val("Weekend");
                }else if (weekdayList.includes(enddayName) && !isSameWeek) {
                    if (weekDifference == 1 && enddayName === 'Sunday' || weekDifference == 1 &&  dayName == enddayName) {
                        $('#calendartext').text("Weekday-Weekend");
                        $('#Date_type').val("Weekday-Weekend");
                    }else if (weekDifference > 1  ) {
                        $('#calendartext').text("Weekday-Weekend");
                        $('#Date_type').val("Weekday-Weekend");
                    }else{
                        $('#calendartext').text("Weekend");
                        $('#Date_type').val("Weekend");
                    }
                }else{
                    $('#calendartext').text("Weekday-Weekend");
                    $('#Date_type').val("Weekday-Weekend");
                }
            }
            else if (weekdayList.includes(dayName)) {
                if (dayName == 'Sunday') {
                    if (enddayName === 'Saturday'&& isSameWeek) {
                        $('#calendartext').text("Weekday-Weekend");
                        $('#Date_type').val("Weekday-Weekend");
                    }else if(weekdayList.includes(dayName)&& !isSameWeek){
                        if (weekDifference == 1 && enddayName === 'Saturday' || enddayName === 'Sunday') {
                            $('#calendartext').text("Weekday-Weekend");
                            $('#Date_type').val("Weekday-Weekend");
                        }else if (weekDifference > 1) {
                            $('#calendartext').text("Weekday-Weekend");
                            $('#Date_type').val("Weekday-Weekend");
                        }else{
                            console.log(2);

                            $('#calendartext').text("Weekday");
                            $('#Date_type').val("Weekday");
                        }
                    }
                }else{
                    if (weekdayList.includes(dayName)&& isSameWeek) {
                        if (weekDifference == 0 && enddayName === 'Saturday' || enddayName === 'Sunday') {
                            $('#calendartext').text("Weekday-Weekend");
                            $('#Date_type').val("Weekday-Weekend");
                        }else{
                            console.log(3);
                            $('#calendartext').text("Weekday");
                            $('#Date_type').val("Weekday");
                        }
                    }else{
                        $('#calendartext').text("Weekday-Weekend");
                        $('#Date_type').val("Weekday-Weekend");
                    }
                }
            }else if (weekendList.includes(dayName)) {
                if (weekDifference == 0 && enddayName === 'Saturday' || weekDifference == 0 &&enddayName === 'Sunday' || weekDifference == 0 && dayName == enddayName) {
                    $('#calendartext').text("Weekend");
                    $('#Date_type').val("Weekend");
                }else if (weekDifference == 1 && weekenddayList.includes(enddayName)){
                    $('#calendartext').text("Weekend-Weekday");
                    $('#Date_type').val("Weekend-Weekday");
                }else if (weekDifference == 1 && weekdayendList.includes(enddayName)){
                    $('#calendartext').text("Weekend-Weekday");
                    $('#Date_type').val("Weekend-Weekday");
                }else if(weekDifference > 1 ){
                    $('#calendartext').text("Weekend-Weekday");
                    $('#Date_type').val("Weekend-Weekday");
                }
            }

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
                $('#checkinpoguest').text(moment(checkinDateValue).format('DD/MM/YYYY'));
                $('#checkoutpoguest').text(moment(checkoutDateValue).format('DD/MM/YYYY'));
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
                $('#checkinpoguest').text(moment(checkinDateValue).format('DD/MM/YYYY'));
                $('#checkoutpoguest').text(moment(checkoutDateValue).format('DD/MM/YYYY'));

                $('#daypo').text(totalDays + ' วัน');
                $('#nightpo').text('0 คืน');
                $('#daypoguest').text(totalDays + ' วัน');
                $('#nightpoguest').text('0 คืน');
            } else {
                if (CheckoutNew) {

                    $('#Day').val('0');
                    $('#Night').val('0');
                    $('#Checkin').val(moment(checkinDateValue).format('DD/MM/YYYY'));
                    $('#Checkout').val('');
                }
            }
            console.log(checkinDateValue);

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
    </script>
    <script>
        function month() {
            var checkmonthValue = document.getElementById('checkmonth').value; // ค่าจาก input checkmonth
            var inputmonth = document.getElementById('inputmonth').value; // ค่าจาก input inputmonth
            var CheckinNew = document.getElementById('Checkin').value;
            var CheckoutNew = document.getElementById('Checkout').value;
            var start = moment(); // เริ่มที่วันที่ปัจจุบัน
            var end; // ประกาศตัวแปร end

            if (!CheckinNew || !CheckoutNew) {
                start = moment(); // เริ่มที่วันนี้
                end = moment().add(7, 'days');
            }else{
                var currentMonthIndex = start.month();
                var monthDiff = inputmonth - currentMonthIndex;
                // ถ้าเดือนปัจจุบันมากกว่าหรือเท่ากับเป้าหมายเดือน
                if (monthDiff < 0) {
                    monthDiff += 12; // เพิ่ม 12 เดือนถ้าข้ามปี
                }
                console.log(monthDiff);

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
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Please select an option"
            });
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
        ///company
        function companyContact() {
            var companyID = $('#Company').val();
            console.log(companyID);
            jQuery.ajax({
                type: "GET",
                url: "{!! url('/Dummy/Proposal/create/company/" + companyID + "') !!}",
                datatype: "JSON",
                async: false,
                success: function(response) {
                    var prename = response.prename.name_th;
                    var fullName = prename+response.data.First_name + ' ' + response.data.Last_name;
                    var fullid = response.data.id ;
                    console.log(response.data);
                    if (response.Company_type.name_th === 'บริษัทจำกัด') {
                        var fullNameCompany = 'บริษัท' + ' ' + response.company.Company_Name + ' ' + 'จำกัด';
                    }
                    else if (response.Company_type.name_th === 'บริษัทมหาชนจำกัด') {
                        var fullNameCompany = 'บริษัท' + ' ' + response.company.Company_Name + ' ' + 'จำกัด'+' '+'(มหาชน)';
                    }
                    else if (response.Company_type.name_th === 'ห้างหุ้นส่วนจำกัด') {
                        var fullNameCompany = 'ห้างหุ้นส่วนจำกัด' + ' ' + response.company.Company_Name ;
                    }else{
                        var fullNameCompany =  response.Company_type.name_th+ response.company.Company_Name ;
                    }
                    var Address = response.company.Address + ' '+ 'ตำบล'+ response.Tambon.name_th + ' '+'อำเภอ'+response.amphures.name_th + ' ' + 'จังหวัด'+ response.province.name_th + ' ' + response.Tambon.Zip_Code;
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
                url: "{!! url('/Dummy/Proposal/create/Guest/" + Guest + "') !!}",
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
            totalAmost();
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
                    ordering:false,
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
        const table_name2 = ['main','mainselecttwo'];
        $(document).ready(function() {
            for (let index = 0; index < table_name2.length; index++) {
                new DataTable('#'+table_name2[index], {
                    searching: false,
                    paging: false,
                    info: false,
                    ordering:false,
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
            let allRowsData = []; // ตัวแปรเก็บข้อมูลทั้งหมด
            $('#main tbody tr').each(function() {
                // สำหรับแต่ละแถวใน tbody
                let rowData = {
                    rowHtml: $(this)[0].outerHTML,  // เก็บข้อมูล HTML ทั้งแถว
                    id : $(this).find('input[name="productid"]').val(),
                    Product_ID: $(this).find('input[name="ProductIDmain[]"]').val(),
                    Product_Name: $(this).find('td').eq(1).text(), // ข้อความใน <td> ที่ 2 (ชื่อสินค้า)
                    Pax: $(this).find('.pax').val(),
                    Quantity: $(this).find('.quantitymain').val(),
                    Unit: $(this).find('.unitmain').val(),
                    Price: $(this).find('input[name="priceproductmain[]"]').val(),
                    Discount: $(this).find('.discountmain').val(),
                };
                if (
                    rowData.Product_ID
                ) {
                    // เพิ่มข้อมูลของแถวนี้เข้าไปใน allRowsData หากค่าครบถ้วน
                    allRowsData.push(rowData);
                    $("#create").val(1);
                }
            });
            $('#allRowsDataInput').val(JSON.stringify(allRowsData));
            console.log($('#allRowsDataInput').val());
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
                url: '{{ route("Proposal.addProduct", ["Quotation_ID" => ":id"]) }}'.replace(':id', status),
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
                                let create = $('#create').val();

                                if (!create) {
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
                                if (create) {
                                    console.log($('#tr-select-addmain' +data.id).length);
                                    if ($('#tr-select-addmain' +data.id).length == 0) {
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

        }
        $(document).ready(function() {
            $(document).on('click', '.select-button-product', function() {
                var product = $(this).val() ;
                $('#row-' + product).prop('hidden',true);
                $('tr .child').prop('hidden',true);
                $.ajax({
                    url: '{{ route("Proposal.addProductselect", ["Quotation_ID" => ":id"]) }}'.replace(':id', product),
                    method: 'GET',
                    data: {
                        value:product
                    },
                    success: function(response) {
                        $('#mainselecttwo').DataTable().destroy();
                        var rowNumber = $('#product-list-select tr').length+1;
                        $('#product-list-select').append(
                            '<tr id="tr-select-add' + response.products.id + '">' +
                            '<td style="text-align:center;">' + rowNumber + '</td>' +
                            '<td><input type="hidden" class="randomKey" name="randomKey" id="randomKey" value="' + response.products.Product_ID + '">' + response.products.Product_ID + '</td>' +
                            '<td style="text-align:left;">' + response.products.name_en + '</td>' +
                            '<td style="text-align:left;">' + Number(response.products.normal_price).toLocaleString() + '</td>' +
                            '<td style="text-align:center;">' + response.products.unit_name + '</td>' +
                            '<td style="text-align:center;"> <button type="button" class="Btn remove-button " style=" border: none;" value="' + response.products.id + '"><i class="fa fa-minus-circle text-danger fa-lg"></i></button></td>' +
                            '<input type="hidden" id="productselect" name="productselect" value="' + response.products.id + '">' +
                            '</tr>'
                        );
                        $('#mainselecttwo').DataTable({
                            searching: false,
                            paging: false,
                            info: false,
                            ordering:false,
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
                        let allRowsData = []; // ตัวแปรเก็บข้อมูลทั้งหมด
                        $('#mainselecttwo tbody tr').each(function() {
                            // สำหรับแต่ละแถวใน tbody
                            let rowData = {
                                id : $(this).find('input[name="productselect').val(),
                            };
                            if (
                                rowData.id
                            ) {
                                // เพิ่มข้อมูลของแถวนี้เข้าไปใน allRowsData หากค่าครบถ้วน
                                allRowsData.push(rowData);
                            }
                        });
                        $('#allRowsDataInputSelect').val(JSON.stringify(allRowsData));
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });
        });

        $(document).on('click', '.confirm-button', function() {
            var all = 'all';
            $.ajax({
                url: '{{ route("Proposal.addProducttablecreatemain", ["Quotation_ID" => ":id"]) }}'.replace(':id', all),
                method: 'GET',
                data: {
                    value: "all"
                },
                success: function(response) {
                    let table = $('#main').DataTable();
                    table.clear().draw();
                    var allRowsDataInput = $('#allRowsDataInput').val();
                    var create = $('#create').val();
                    var allRowsDataInputSelectValue = $('#allRowsDataInputSelect').val();
                    console.log(allRowsDataInput);
                    if (create == 1) {
                        console.log(0);

                        let parsedArray = JSON.parse(allRowsDataInput);
                        console.log(parsedArray);
                        var number = parsedArray.Product_ID;
                        $('#main').DataTable().destroy();
                        var rowNumbemain = $('#display-selected-items tr').length + 1;
                        parsedArray.forEach(item => {
                            // เพิ่มแถวที่มีค่า input ที่กรอกไว้
                            let newRow = $(item.rowHtml);  // สร้างแถวใหม่จาก HTML ที่เก็บไว้

                            // กำหนดค่าให้กับ input fields
                            newRow.find('input[name="productid"]').val(item.id);
                            newRow.find('input[name="ProductIDmain[]"]').val(item.Product_ID);
                            newRow.find('.pax').val(item.Pax);
                            newRow.find('.quantitymain').val(item.Quantity);
                            newRow.find('.unitmain').val(item.Unit);
                            newRow.find('input[name="priceproductmain[]"]').val(item.Price);
                            newRow.find('.discountmain').val(item.Discount);

                            // เพิ่มแถวเข้าไปใน #display-selected-items
                            $('#display-selected-items').append(newRow);
                        });
                        $('#main').DataTable({
                            searching: false,
                            paging: false,
                            info: false,
                            ordering:false,
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
                    }
                      // เช่น '[{"id":1},{"id":2}]'
                    if (allRowsDataInputSelectValue) {
                        let parsedArray = JSON.parse(allRowsDataInputSelectValue);  // แปลง JSON เป็นอาร์เรย์
                        let matchingProducts = response.products.filter(product =>
                            parsedArray.some(item => Number(item.id) === Number(product.id))  // ใช้ Number เพื่อแปลงเป็นตัวเลข
                        );
                        console.log(matchingProducts);

                        $.each(matchingProducts, function(key, val) {
                                var number = val.Product_ID;
                                var name = '';
                                var price = 0;
                                var normalPriceString = val.normal_price.replace(/[^0-9.]/g, ''); // ล้างค่าที่ไม่ใช่ตัวเลขและจุดทศนิยม
                                var normalPrice = parseFloat(normalPriceString);
                                var netDiscount = ((normalPrice)).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                var normalPriceview = ((normalPrice)).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                let discountInput;
                                let quantity;
                                var roleMenuDiscount = document.getElementById('roleMenuDiscount').value;
                                var SpecialDiscount = document.getElementById('SpecialDiscount').value;
                                var Add_discount = parseFloat(document.getElementById('Add_discount').value) || 0;
                                var User_discount = parseFloat(document.getElementById('User_discount').value) || 0;
                                var maximum_discount = val.maximum_discount;
                                let unit;
                                var valpax = val.pax;
                                if (valpax == null) {
                                    valpax = 0;
                                }
                                 discountInput = '<div class="input-group">' +
                                                        '<input class="discountmain form-control" type="text" id="discountmain' + number + '" name="discountmain[]" value="" rel="' + number + '" style="text-align:center;">' +
                                                        '<span class="input-group-text">%</span>' +
                                                        '</div>';

                                quantity = '<div class="input-group">' +
                                            '<input class="quantitymain form-control" type="text" id="quantitymain' + number + '" name="Quantitymain[]" value="" rel="' + number + '" style="text-align:center;" ' +
                                            'oninput="if (parseFloat(this.value= this.value.replace(/[^0-9]/g, \'\').slice(0, 10)) > ' + val.NumberRoom + ') this.value = ' + val.NumberRoom + ';">' +
                                            '<span class="input-group-text">'+ val.unit_name +'</span>' +
                                            '</div>';
                                unit = '<div class="input-group">' +
                                        '<input class="unitmain form-control" type="text" id="unitmain' + number + '" name="Unitmain[]" value="" rel="' + number + '" style="text-align:center;" ' +
                                        'oninput="this.value = this.value.replace(/[^0-9]/g, \'\').slice(0, 10);">' +
                                        '<span class="input-group-text">' + val.quantity_name + '</span>' +
                                        '</div>';
                                $('#main').DataTable().destroy();
                                var rowNumbemain = $('#display-selected-items tr').length + 1;
                                $('#display-selected-items').append(
                                    '<tr id="tr-select-addmain' + val.id + '">' +
                                    '<td style="text-align:center;"><input type="hidden" id="productid" name="productid" value="' + val.id + '">' + rowNumbemain + '</td>' +
                                    '<td style="text-align:left;"><input type="hidden" id="Product_ID" name="ProductIDmain[]" value="' + val.Product_ID + '">' + val.name_en +
                                    '<span class="fa fa-info-circle" data-bs-toggle="tooltip" data-placement="top" title="' + val.maximum_discount + '%"></span><input type="hidden" id="max-' + number + '" value="' + val.maximum_discount + '"></td>' +
                                    '<td style="text-align:center; color:#fff"><input type="hidden"class="pax" id="pax'+ number +'" name="pax[]" value="' + val.pax + '"rel="' + number + '"><span  id="paxtotal-' + number + '">' + valpax + '</span></td>' +
                                    '<td style="text-align:center;width:12%;">' + quantity + '</td>' +
                                    '<td style="text-align:center;width:12%;">' + unit + '</td>' +
                                    '<td style="text-align:center;"><input type="hidden" id="totalprice-unit-' + number + '" name="priceproductmain[]" value="' + val.normal_price + '">' + Number(val.normal_price).toLocaleString() + '</td>' +
                                    '<td style="text-align:center;width:12%;">' + discountInput + '</td>' +
                                    '<td style="text-align:center;"><input type="hidden" id="net_discount-' + number + '" value="' + val.normal_price + '"><span id="netdiscount' + number + '">' + normalPriceview + '</span></td>' +
                                    '<td style="text-align:center;"><input type="hidden" id="allcounttotal-' + number + '" value="' + val.normal_price + '"><span id="allcount' + number + '">' + normalPriceview + '</span></td>' +
                                    '<td style="text-align:center;"><button type="button" class="Btn remove-buttonmain" style=" border: none;"    value="' + val.id + '"><i class="fa fa-minus-circle text-danger fa-lg"></i></button></td>' +
                                    '</tr>'
                                );
                                $('#main').DataTable({
                                    searching: false,
                                    paging: false,
                                    info: false,
                                    ordering:false,
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
                                let table = $('#mainselecttwo').DataTable();  // เรียก DataTable ที่ต้องการ
                                table.clear().draw();
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });

            $('#exampleModalproduct').modal('hide');

        });

        $(document).ready(function() {
            $(document).on('click', '.remove-buttonmain', function() {
                var product = $(this).val();
                console.log(product);

                $('#display-selected-items tr.child').remove();
                $('#tr-select-add' + product + ', #tr-select-addmain' + product).remove();

                $('#display-selected-items tbody tr').each(function(index) {
                    // เปลี่ยนเลขลำดับใหม่
                    $(this).find('td:first').text(index+1);
                });
                renumberRows();
                totalAmost();// ลบแถวที่มี id เป็น 'tr-select-add' + product

            });
            totalAmost();
        });
        function renumberRows() {
            $('#product-list-select tr:visible').each(function(index) {
                $(this).find('td:first-child').text(index+1); // เปลี่ยนเลขลำดับในคอลัมน์แรก
            });
            $('#display-selected-items tr').each(function(index) {
                $(this).find('td:first-child').text(index + 1 ); // เปลี่ยนเลขลำดับในคอลัมน์แรก
            });
        }
        $(document).on('click', '.remove-button', function() {
            console.log(1);
            let table = $('#mainselecttwo').DataTable();
            var product = $(this).val();
            console.log(product);

            let row = $('#tr-select-add' + product);
            $('#product-list-select tr.child').remove();
            table.row(row).remove().draw();
            let allRowsData = []; // ตัวแปรเก็บข้อมูลทั้งหมด
            $('#mainselecttwo tbody tr').each(function() {
                // สำหรับแต่ละแถวใน tbody
                let rowData = {
                    id : $(this).find('input[name="productselect').val(),
                };
                if (
                    rowData.id
                ) {
                    // เพิ่มข้อมูลของแถวนี้เข้าไปใน allRowsData หากค่าครบถ้วน
                    allRowsData.push(rowData);
                }
            });
            $('#allRowsDataInputSelect').val(JSON.stringify(allRowsData));
            console.log($('#allRowsDataInputSelect').val());
            $('#row-' + product).prop('hidden',false);
            renumberRows();// ลบแถวที่มี id เป็น 'tr-select-add' + product
        });
        //----------------------------------------รายการ---------------------------
        $(document).ready(function() {});
            $(document).on('keyup', '.quantitymain', function() {
                for (let i = 0; i < 50; i++) {
                    var number_ID = $(this).attr('rel');
                    console.log(number_ID);
                    var quantitymain =  Number($(this).val());
                    var discountmain =  $('#discountmain'+number_ID).val();
                    var unitmain =  $('#unitmain'+number_ID).val();
                    var paxmain = parseFloat($('#pax' + number_ID).val());
                    if (isNaN(paxmain)) {
                        paxmain = 0;
                    }
                    console.log($('#pax' + number_ID).val());
                    var pax = paxmain*quantitymain;
                    console.log(pax);

                    $('#paxtotal-'+number_ID).text(pax);
                    var number = Number($('#number-product').val());
                    var price = parseFloat($('#totalprice-unit-'+number_ID).val().replace(/,/g, ''));
                    var pricenew = quantitymain*unitmain*price


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
                    var SpecialDiscount = parseFloat(document.getElementById('SpecialDiscount').value) || 0;;
                    var Add_discount = parseFloat(document.getElementById('Add_discount').value) || 0;
                    var User_discount = parseFloat(document.getElementById('User_discount').value) || 0;
                    var number_ID = $(this).attr('rel');
                    var discount =  Number($(this).val());
                    var max =  $('#max-'+number_ID).val();
                    if (discount >= max) {
                        if (max > SpecialDiscount) {
                            var discountmain =  SpecialDiscount;
                        }else{
                            var discountmain =  max;
                        }
                    }else if (discount > SpecialDiscount) {
                        if (SpecialDiscount > max) {
                            var discountmain =  max;
                        }else{
                            var discountmain =  SpecialDiscount;
                        }
                    }else{
                        var discountmain =  discount;
                    }
                    if (discountmain !== 0) {
                        $(this).val(discountmain);
                    } else {
                        $(this).val(''); // Clears the input if discountmain is 0
                    }
                    var quantitymain =  $('#quantitymain'+number_ID).val();
                    var unitmain =  $('#unitmain'+number_ID).val();

                    var number = Number($('#number-product').val());
                    var price = parseFloat($('#totalprice-unit-'+number_ID).val().replace(/,/g, ''));


                   var pricenew = quantitymain*unitmain*price


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
            $(document).on('keyup', '.DiscountAmount', function() {
                var DiscountAmount =  Number($(this).val());
                if (DiscountAmount) {
                    $('#Special').css('display', 'grid');
                    $('#Subtotal').css('display', 'grid');
                    document.getElementById('Preview').disabled = true;
                }else{
                    $('#Special').css('display', 'none');
                    $('#Subtotal').css('display', 'none');
                    document.getElementById('Preview').disabled = false;
                }
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

                        if (Discount) {
                            $('#Special').css('display', 'grid');
                            $('#Subtotal').css('display', 'grid');
                        }
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
                        if (Discount) {
                            $('#Special11').css('display', 'grid');
                            $('#Subtotal11').css('display', 'grid');
                        }
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
                        addedtax = lessDiscount*7/100;
                        beforetax= lessDiscount+addedtax;
                        Nettotal= beforetax;
                        totalperson = Nettotal/paxtotal;

                        console.log(Discount);
                        if (Discount) {
                            $('#Special1').css('display', 'grid');
                            $('#Subtotal1').css('display', 'grid');
                        }
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
                title: "คุณต้องการย้อนกลับใช่หรือไม่?",
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
                    window.location.href = "{{ route('DummyQuotation.index') }}";
                }
            });
        }
        function confirmSubmit(event) {
            event.preventDefault(); // Prevent the form from submitting
            var Quotation_ID = $('#Quotation_ID').val();
            var Adult = $('#Adult').val();
            var Children = $('#Children').val();
            var title = `คุณต้องการบันทึกข้อมูลรหัส ${Quotation_ID} ใช่หรือไม่?`;
            if (!Adult || !Children) {
                // Display error message using Swal
                Swal.fire({
                    title: "ข้อมูลไม่ครบถ้วน",
                    text: "กรุณากรอกข้อมูลให้ครบถ้วน",
                    icon: "error",
                    confirmButtonText: "ตกลง",
                    confirmButtonColor: "#dc3545"
                });
                $('#Adultred').css('display', 'block');
                $('#Adultblack').css('display', 'none');
                return; // Stop further execution
            }else{
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
