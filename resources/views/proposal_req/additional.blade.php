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
    td.today {
        background-color: transparent !important; /* ไม่ให้มีสีพื้นหลัง */
    }
</style>
@section('content')
    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <small class="text-muted">Welcome to Additional.</small>
                    <div class=""><span class="span1">Additional</span></div>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-secondary lift btn_modal btn-space" onclick="BACKtoEdit()">
                        Back
                    </button>
                    <div class="btn-group">
                        <button type="button" class="btn btn-color-green text-white  dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Select &nbsp;</button>
                        <ul class="dropdown-menu border-0 shadow p-3">
                            @if ($Quotation->correct >= 1 )
                                <li><a href="{{ asset($path.$Additional_ID.'-'.$Quotation->correct.".pdf") }}"  class="dropdown-item py-2 rounded" target="_blank" >PDF</a></li>
                            @else
                                <li><a href="{{ asset($path.$Additional_ID.".pdf") }}" class="dropdown-item py-2 rounded" target="_blank" >PDF</a></li>
                            @endif
                            <li><a class="dropdown-item py-2 rounded" onclick="Appovel({{$Quotation->id}})">Appovel</a></li>
                            <li><a class="dropdown-item py-2 rounded" onclick="Reject({{$Quotation->id}})">Reject</a></li>
                        </ul>
                    </div>
                </div>
            </div> <!-- .row end -->
        </div>
    </div>
    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
            <div class="row align-items-center mb-2" >
                @if (session("success"))
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">บันทึกสำเร็จ!</h4>
                    <hr>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
                @endif
                @if (session("error"))
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">บันทึกไม่สำเร็จ!</h4>
                        <hr>
                        <p class="mb-0">{{ session('error') }}</p>
                    </div>
                @endif
                <div class="col">
                    <ol class="breadcrumb d-inline-flex bg-transparent p-0 m-0">
                        <li></li>
                        <li></li>
                        <li></li>
                    </ol>
                </div>
                <div class="col-auto">

                </div>
            </div> <!-- Row end  -->
        </div> <!-- Row end  -->
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-8 col-md-12 col-sm-12 image-container">
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
                                <div class="col-lg-4 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-4"></div>
                                        <div class="PROPOSAL col-lg-7" style="margin-left: 5px">
                                            <div class="row">
                                                <b class="titleQuotation" style="font-size: 24px;color:rgb(255, 255, 255);">ADDITIONAL CHARGE</b>
                                                <b  class="titleQuotation" style="font-size: 16px;color:rgb(255, 255, 255);">{{$Quotation_IDoverbill}}</b>
                                            </div>
                                            <input type="hidden" id="Quotation_ID" name="Quotation_ID" value="{{$Quotation_ID}}">
                                            <input type="hidden" id="Additional_ID" name="Additional_ID" value="{{$Quotation_IDoverbill}}">
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
                                                        <input type="text" id="datestart" class="form-control readonly-input" name="IssueDate" style="text-align: left;" value="{{$Quotation->issue_date}}"disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 col-sm-12 mt-2">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-12 col-sm-12"style="display:flex; justify-content:right; align-items:center;">
                                                        <span>Expiration Date:</span>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <input type="text" id="dateex" class="form-control readonly-input" name="Expiration" style="text-align: left;"value="{{$Quotation->Expirationdate}}"disabled>
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
                            <div id="Companyshow" style="display: block">
                                <div class="row mt-2" >
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <label class="labelcontact" for="">Customer Company</label>
                                        <select name="Company" id="Company" class="select2" onchange="companyContact()" required>
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
                                        <button style="float: right" type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ route('guest','index') }}'"><i class="fa fa-plus"></i> เพิ่มลูกค้า</button>
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
                                        <input type="text" name="Checkin" id="Checkin" class="form-control readonly-input" value="{{$Quotation->checkin}}"  readonly  disabled>
                                        <input type="hidden" id="inputmonth" name="inputmonth" value="">
                                        <input type="hidden" id="inputcalendartext" name="inputcalendartext" value="">
                                        <input type="hidden" id="Date_type" name="Date_type" value="">
                                        <input type="hidden" id="CheckinNew" name="CheckinNew" value="{{$Quotation->checkin}}">
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
                                        <input type="text" name="Checkout" id="Checkout" class="form-control readonly-input" value="{{$Quotation->checkout}}"  readonly disabled>
                                        <input type="hidden" id="checkmonth" name="checkmonth" value="">
                                        <input type="hidden" id="CheckoutNew" name="CheckoutNew" value="{{$Quotation->checkout}}">
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
                                        <input type="text" class="form-control" name="Day" id="Day" placeholder="จำนวนวัน"value="{{$Quotation->day}}" @readonly(true)>
                                        <span class="input-group-text">Day</span>
                                        <input type="text" class="form-control" name="Night" id="Night" placeholder="จำนวนคืน"value="{{$Quotation->night}}" @readonly(true)>
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
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <span  for="">Company Commission</span>
                                    <div class="input-group">
                                        <input type="text" class="form-control"  name="Company_Commission_Rate_Code" value="{{$Quotation->commissionratecode}}"disabled>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12">
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
                                                        onchange="adddis()"readonly>
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
                                        <input type="number" class="DiscountAmount form-control" name="DiscountAmount" id="DiscountAmount"  placeholder="ส่วนลดคิดเป็นบาท"value="{{$Quotation->SpecialDiscountBath}}" disabled>
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
                                    <div class="col-lg-12 col-md-12 col-sm-12 row" >
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <p style="display: inline-block;font-weight: bold;font-size:16px">Check In :</p><br>
                                            <p style="display: inline-block;font-weight: bold;font-size:16px">Check Out :</p><br>
                                            <p style="display: inline-block;font-weight: bold;font-size:16px">Length of Stay :</p><br>
                                            <p style="display: inline-block;font-weight: bold;font-size:16px">Number of Guests :</p>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 mt-2">
                                            @if ($Quotation->checkin == null)
                                                <p style="display: inline-block;"><span id="checkinpo">-</span></p><br>
                                                <p style="display: inline-block;"><span id="checkoutpo">-</span></p><br>
                                            @else
                                                <p style="display: inline-block;"><span >{{$Quotation->checkin}}</span></p><br>
                                                <p style="display: inline-block;"><span >{{$Quotation->checkout}}</span></p><br>
                                            @endif
                                            @if ($Quotation->day == null)
                                                <p style="display: inline-block;"><span id="daypo">-</span><span id="nightpo"></span></P><br>
                                            @else
                                                <p style="display: inline-block;"><span >{{$Quotation->day}}</span> วัน <span >{{$Quotation->night}}</span> คืน</p><br>
                                            @endif
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
                                <table id="main" class=" example2 ui striped table nowrap unstackable " style="width:100%">
                                    <thead >
                                        <tr>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:10%">No.</th>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:10%"data-priority="1">Code</th>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:50%"data-priority="1">Description</th>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:10%"data-priority="1">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="display-selected-items">
                                        @if (!empty($selectproduct))
                                            @foreach ($selectproduct as $key => $item)
                                                @php
                                                $var = $item->Code;
                                                @endphp
                                                <tr id="tr-select-main{{$item->Code}}">
                                                    <input type="hidden" id="CheckProduct" name="CheckProduct[]" value="{{$item->Code}}">
                                                    <td style="text-align:center;vertical-align: middle;"><input type="hidden" id="ProductID" name="Code[]" value="{{$item->Code}}">{{$key+1}}</td>
                                                    <td style="text-align:center;vertical-align: middle;">{{$item->Code}} </td>
                                                    <td style="text-align:left;vertical-align: middle;">{{$item->Detail}} </td>
                                                    <td class="Quantity" data-value="{{$item->Amount}}" style="text-align:center;">
                                                        <input type="text" id="quantity{{$var}}" name="Amount[]" rel="{{$var}}" style="text-align:center;vertical-align: middle;"class="quantity-input form-control" value="{{$item->Amount}} "oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" disabled>
                                                    </td>

                                                </tr>
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
                                <div class="col-12 row ">
                                    <div class="col-lg-9 col-md-8 col-sm-12 mt-2" >
                                        <span >Notes or Special Comment</span>
                                        <textarea class="form-control mt-2"cols="30" rows="5"name="comment" id="comment" placeholder="Leave a comment here" id="floatingTextarea">{{$Quotation->comment}}</textarea>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-12 " >
                                        <table class="table table-custom-borderless" >
                                            <tbody>
                                                <tr >
                                                    <td scope="row"style="text-align:right;width: 70%;font-size: 14px;"><b>Subtotal</b></td>
                                                    <td style="text-align:left;width: 30%;font-size: 14px;"><span id="total-amount">0</span></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row"style="text-align:right;width: 70%;font-size: 14px;"><b>Price Before Tax</b></td>
                                                    <td style="text-align:left;width: 30%;font-size: 14px;"><span id="Net-price">0</span></td>
                                                </tr>
                                                <tr>
                                                    <td scope="row" style="text-align:right;width: 70%;font-size: 14px;"><b>Value Added Tax</b></td>
                                                    <td style="text-align:left;width: 30%;font-size: 14px;"><span id="total-Vat">0</span></td>
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
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <strong class="com" style="font-size: 18px">Method of Payment</strong>
                                    </div>
                                    <span class="col-md-8 col-sm-12 mt-1"id="Payment50" style="display: block" >
                                        Please make a 50% deposit within 7 days after confirmed. <br>
                                        Transfer to <strong> " Together Resort Limited Partnership "</strong> following banks details.<br>
                                        If you use transfer, Please inform Accounting / Finance Department Tel or LINE ID<span style="font-size: 18px"> @Together-resort</span><br>
                                        pay-in slip to number 032-708-888 every time for the correctness of payment allocation.<br>
                                    </span>
                                    <span class="col-md-8 col-sm-12 mt-1"  id="Payment100" style="display: none">
                                        Please make a 100% deposit within 3 days after confirmed. <br>
                                        Transfer to <strong> " Together Resort Limited Partnership "</strong> following banks details.<br>
                                        If you use transfer, Please inform Accounting / Finance Department Tel or LINE ID<span style="font-size: 18px"> @Together-resort</span><br>
                                        pay-in slip to number 032-708-888 every time for the correctness of payment allocation.<br>
                                    </span>
                                    <div class="row">
                                        <div class="col-lg-8 col-md-6 col-sm-12">
                                            <div class="col-lg-12 col-md-12 col-sm-12  mt-2">
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
                                <div class="col-lg-12 col-md-12 col-sm-12 mt-2">
                                    <div class="col-lg-4 col-md-12 col-sm-12">
                                        <strong class="titleh1">รับรอง</strong>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 my-2">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form id="myFormApprove" action="{{route('ProposalReq.Approve')}}" method="POST">
        @csrf
        <input type="hidden" name="approved_id" id="approved_id" value="{{$Additional_ID}}">
    </form>
    <form id="myForm" action="{{route('ProposalReq.Reject')}}" method="POST">
        @csrf
        <input type="hidden" name="approved_id" id="approved_id" value="{{$Additional_ID}}">
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
            var flexCheckChecked = document.getElementById("flexCheckChecked");
            var dayName = checkinDate.format('dddd'); // Format to get the day name
            var enddayName = checkoutDate.format('dddd'); // Format to get the day name
            flexCheckChecked.disabled = true;

            if (['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'].includes(dayName)) {
                if (dayName === 'Thursday' && enddayName === 'Saturday') {
                    $('#calendartext').text("Weekday-Weekend");

                    $('#inputcalendartext').val("Weekday-Weekend");
                }else{
                    $('#calendartext').text("Weekday");
                    $('#inputcalendartext').val("Weekday");
                }
            } else if (['Friday','Saturday','Sunday'].includes(dayName)) {
                if (dayName === 'Saturday' && enddayName === 'Monday') {
                    $('#calendartext').text("Weekday-Weekend");
                    $('#inputcalendartext').val("Weekday-Weekend");
                }else{
                    $('#calendartext').text("Weekend");
                    $('#inputcalendartext').val("Weekend");
                }
            }
        });

        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Please select an option"
            });
            //----------------ส่วนบน---------------
            var countrySelect = $('#select');
            var select = countrySelect.val();
            var Companyshow = document.getElementById("Companyshow");
            var Company = document.getElementById("Company");
            var Company_Contact = document.getElementById("Company_Contact");
            var Company_Contactname = document.getElementById("Company_Contactname");
            var Guest = document.getElementById("Guest");
            var Guestshow = document.getElementById("Guestshow");
            var TiTlecompanyTable = document.getElementById("TiTlecompanyTable");
            var TiTlecontractTable = document.getElementById("TiTlecontractTable");
            var guestTable = document.getElementById("guestTable");
            var TiTleguestTable = document.getElementById("TiTleguestTable");
            if (select == "Company") {
                Companyshow.style.display = "block";
                Guestshow.style.display = "none";
                guestTable.style.display = "none";
                TiTleguestTable.style.display = "none";
                Company.disabled = true;
                Company_Contact.disabled = true;
                Company_Contactname.disabled = true;
                Guest.disabled = true;
                companyTable.style.display = "block";
                contractTable.style.display = "block";
                TiTlecompanyTable.style.display = "block";
                TiTlecontractTable.style.display = "block";
                companyContact();
            }else{
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
                Guest.disabled = true;
                GuestContact();
            }

            var countrySelect = $('#DiscountAmount');
            var select = countrySelect.val();
            if (select) {
                $('#Special').css('display', 'table-row');
                $('#Subtotal').css('display', 'table-row');
            }else{
                $('#Special').css('display', 'none');
                $('#Subtotal').css('display', 'none');
            }
        });
        function companyContact() {
            console.log(1);
            var companyID = $('#Company').val();

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
                    }else{
                        var fullNameCompany = response.Company_type.name_th + response.company.Company_Name ;
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
        function GuestContact() {
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
                    dateInput.classList.add('disabled-input');
                    dateout.classList.add('disabled-input');
                    $('#checkinpo').text('No Check in date');// ตั้งค่า flexCheckChecked เป็น checked
                    $('#checkoutpo').text('-');
                    $('#daypo').text('-');
                    $('#nightpo').text(' ');
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
            flexCheckChecked.addEventListener('change', function(event) {
                var isChecked = event.target.checked;

                if (isChecked) {
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

        //----------------------------------------รายการ---------------------------

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
                var Adult  = $('#Adult').val();
                var Children  = $('#Children').val();
                let PaxToTalall=0;
                var discountElement  = $('#DiscountAmount').val();
                $('#display-selected-items tr').each(function() {
                    let priceCell = $(this).find('.Amount').val();
                    let pricetotal = parseFloat(priceCell) || 0;
                    let priceCellMain = $(this).find('.quantity-input').val();
                    let pricetotalMain = parseFloat(priceCellMain) || 0;
                    paxtotal =  Adult+Children;
                    allprice += pricetotal+pricetotalMain;
                    totalperson = allprice/paxtotal;
                    beforetax = allprice/1.07;
                    addedtax = allprice-allprice/1.07;
                    $('#total-amount').text(isNaN(allprice) ? '0' : allprice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    $('#Net-price').text(isNaN(beforetax) ? '0' : beforetax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    $('#total-Vat').text(isNaN(addedtax) ? '0' : addedtax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    $('#Net-Total').text(isNaN(Nettotal) ? '0' : allprice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    $('#Average').text(isNaN(totalperson) ? '0' : totalperson.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    $('#PaxToTal').text(isNaN(paxtotal) ? '0' : paxtotal);
                    $('#PaxToTalall').val(isNaN(PaxToTalall) ? '0' : PaxToTalall);
                    if (paxtotal == 0) {
                        $('#Pax').css('display', 'none');
                    }else{
                        $('#Pax').css('display', 'block');
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
                title: "คุณต้องการย้อนกลับใช่หรือไม่?",
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
                    window.location.href = "{{ route('ProposalReq.index') }}";
                }
            });
        }
        function Appovel(id) {
            var Additional_ID = $('#Additional_ID').val();
            Swal.fire({
                title: `คุณต้องการ Approve รหัส ${Additional_ID} เอกสารใช่หรือไม่?`,
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "บันทึกข้อมูล",
                cancelButtonText: "ยกเลิก",
                confirmButtonColor: "#2C7F7A",
                dangerMode: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('myFormApprove').submit();
                }
            });
        }
        function Reject(id) {
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
    </script>
@endsection
