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
            </div> <!-- .row end -->
        </div>
    </div>

    <form id="myForm" action="{{url('/Document/Additional/Charge/save/'.$Quotation->id)}}" method="POST">
        @csrf
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
                                                    <b class="titleQuotation" style="font-size: 20px;color:rgb(255, 255, 255);">ADDITIONAL CHARGE</b>
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
                                <input type="hidden" id="select" name="select" value="{{$Quotation->type_Proposal}}">
                                <div class="row mt-2">
                                    @if ($Selectdata == 'Company')
                                        <div class="proposal-cutomer-detail" >
                                            <ul>
                                            <b class="font-upper com">Company Information</b>
                                            <li class="mt-3">
                                                <b>Company Name</b>
                                                <span id="Company_name">{{$fullName}}</span>
                                            </li>
                                            <li>
                                                <b>Company Address</b>
                                                <span id="Address">{{$Address}}   {{'ตำบล '.$TambonID->name_th}} {{'อำเภอ '.$amphuresID->name_th}} {{'จังหวัด '.$provinceNames->name_th}} {{$TambonID->Zip_Code}}</span>
                                                <b></b>
                                            </li>
                                            <span class="wrap-full">
                                                <li >
                                                    <b>Company Number</b>
                                                    <span id="Company_Number">{{$phone->Phone_number}}</span>
                                                </li>
                                                <li >
                                                    <b>Company Fax</b>
                                                    <span id="Company_Fax">{{$Fax_number}}</span>
                                                </li>
                                            </span>
                                            <li>
                                                <b>Company Email</b>
                                                <span id="Company_Email">{{$Email}}</span>
                                            </li>
                                            <li>
                                                <b>Taxpayer Identification</b>
                                                <span id="Taxpayer" >{{$Taxpayer_Identification}}</span>
                                            </li>
                                            <li> </li>
                                            <b class="font-upper com">Personal Information</b>
                                            <li class="mt-3">
                                                <b>Contact Name</b>
                                                <span id="Company_contact">{{$Contact_Name}}</span>
                                            </li>
                                            <li >
                                                <b>Contact Number</b>
                                                <span id="Contact_Phone">{{$Contact_phone->Phone_number}}</span>
                                            </li>
                                            <li>
                                                <b>Contact Email</b>
                                                <span id="Contact_Email" >{{$Quotation->checkin}}</span>
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
                                                <span style="display: flex"><p id="daypo" class="m-0"> </p><p id="nightpo" class="m-0"> </p></span>
                                            </li>
                                            <li>
                                                <b>Number of Guests</b>
                                                <span style="display: flex"><p id="Adultpo" class="m-0"> </p><p id="Childrenpo" class="m-0"> </p></span>
                                            </li>
                                            </ul>
                                        </div>
                                    @else
                                        <div class="proposal-cutomer-detail" >
                                            <ul>
                                            <b class="font-upper com">Guest Information</b>
                                            <li class="mt-3">
                                                <b>Guest  Name</b>
                                                <span id="guest_name">{{$fullName}}</span>
                                            </li>
                                            <li>
                                                <b>Guest  Address</b>
                                                <span id="guestAddress">{{$Address}}   {{'ตำบล '.$TambonID->name_th}} {{'อำเภอ '.$amphuresID->name_th}} {{'จังหวัด '.$provinceNames->name_th}} {{$TambonID->Zip_Code}}</span>
                                                <b></b>
                                            </li>

                                            <li >
                                                <b>Guest  Number</b>
                                                <span id="guest_Number">{{$phone->Phone_number}}</span>
                                            </li>

                                            <li>
                                                <b>Guest  Email</b>
                                                <span id="guest_Email">{{$Email}}</span>
                                            </li>
                                            <li>
                                                <b>Identification Number</b>
                                                <span id="guestTaxpayer" >{{$Taxpayer_Identification}}</span>
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
                                    @endif
                                    <div class="styled-hr"></div>
                                </div>
                                <div class="payment-container row mt-2">
                                    <div class="col-lg-4 col-md-12 col-sm-12 mt-2">
                                        <label for="">Document Type Additional</label>
                                        <select name="additional_type" id="additional_type" class="additional_type select2" >
                                            <option value="H/G">H/G Online</option>
                                            <option value="Cash">Partial payment and complimentary</option>
                                            <option value="Cash Manual">Manual Partial payment and complimentary</option>
                                        </select>
                                    </div>
                                    <div id="Cashinput" class="col-lg-4 col-md-12 col-sm-12 mt-2 Cashinput" style="display: none">
                                        <label for="">Payment Type</label>
                                        <select  id="typePayment" name="typePayment" class="select2">
                                            @foreach ($complimentary as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="Cash_Manualinput" class="col-lg-8 col-md-12 col-sm-12 row mt-2 Cash_Manualinput" style="display: none">
                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                            <label for="">Payment</label>
                                            <input type="text" name="Cash" id="Cash" class="form-control Cash">
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                            <label for="">Complimentary</label>
                                            <input type="text" name="Complimentary" id="Complimentary"class="form-control Complimentary">
                                        </div>
                                        <div class="col-lg-4 col-md-12 col-sm-12">
                                            <label for="">Total</label>
                                            <input type="text" name="totalComplimentary" id="totalComplimentary" class="form-control totalComplimentary" readonly>
                                        </div>
                                    </div>
                                    <script>
                                        $(document).ready(function() {
                                            // Listen for change on all elements with class 'paymentType'
                                            $('.additional_type').on('change', function() {
                                                var selectedType = $(this).val();
                                                var parentContainer = $(this).closest('.payment-container'); // Find the parent container
                                                // Hide all payment method sections within this specific container
                                                parentContainer.find('.cashInput, .Cash_Manualinput').hide();
                                                const Cashinput = document.getElementById('Cashinput');
                                                const inputs = Cashinput.querySelectorAll("select");
                                                const CashinputManual = document.getElementById('Cash_Manualinput');
                                                const inputsManual = CashinputManual.querySelectorAll("input");
                                                // Show the relevant section based on the selected payment type
                                                inputs.forEach(input => input.disabled = false);
                                                if (selectedType === 'Cash') {
                                                    parentContainer.find('.cashInput').show();
                                                    inputs.forEach(input => input.disabled = false);
                                                    inputsManual.forEach(input => input.disabled = true);
                                                } else if (selectedType === 'Cash Manual') {
                                                    parentContainer.find('.Cash_Manualinput').show();
                                                    inputs.forEach(input => input.disabled = true);
                                                    inputsManual.forEach(input => input.disabled = false);
                                                }
                                            });
                                            $(document).on('keyup', '.Cash', function() {
                                                var Cash =  Number($(this).val());
                                                var Complimentary = parseFloat($('#Complimentary').val().replace(/,/g, '')) || 0;
                                                var totalComplimentary = Complimentary+Cash;
                                                $('#totalComplimentary').val(totalComplimentary.toLocaleString('th-TH'));
                                            });
                                            $(document).on('keyup', '.Complimentary', function() {
                                                var Complimentary =  Number($(this).val());
                                                var Cash = parseFloat($('#Cash').val().replace(/,/g, ''))|| 0;
                                                var totalComplimentary = Complimentary+Cash;
                                                $('#totalComplimentary').val(totalComplimentary.toLocaleString('th-TH'));
                                            });
                                        });
                                    </script>
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
                                                            <li><a class="dropdown-item py-2 rounded" data-value="Other"onclick="fetchProducts('Other')">Other </a></li>
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
                                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width: 10%">คำสั่ง</th>
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
                                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width: 10%"data-priority="1">คำสั่ง</th>
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
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:10%">No.</th>
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:50%"data-priority="1">Description</th>
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:10%"data-priority="1">Amount</th>
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:10%"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="display-selected-items">

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
                                    <div class="col-12 mt-3">
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <strong class="com" style="font-size: 18px">Method of Payment</strong>
                                        </div>
                                        <span class="col-md-8 col-sm-12">
                                        <br>
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
                                                @php
                                                    $id = Auth::user()->id;
                                                    $user =  DB::table('users')->where('id',$id)
                                                    ->first();
                                                @endphp
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
                                                    @if ($user->signature)
                                                        <img src="/upload/signature/{{$user->signature}}" style="width: 70%;"/>
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
                                                Cancel
                                            </button>
                                            <input type="hidden" id="NettotalCheck" name="NettotalCheck">
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
            $('.select2').select2({
                placeholder: "Please select an option"
            });
            var dayview = @json($Quotation->day);
            var nightview = @json($Quotation->night);

            var day = dayview ? dayview : '-';
            var night = nightview ? nightview : '-';
            var adult ={{$Quotation->adult}};
            var children ={{$Quotation->children ? $Quotation->children : 0 }};
            $('#Adultpo').text(adult +' Adult');
            $('#Adultpoguest').text(adult +' Adult');

            $('#Childrenpo').text(' , '+ children +' Children');
            $('#Childrenpoguest').text(' , '+ children +' Children');
            if (day == '-') {
                $('#daypo').text('-');
                $('#daypoguest').text('-');
            }else{
                $('#daypo').text(day + ' วัน');
                $('#nightpo').text(night + ' คืน');

                $('#daypoguest').text(day + ' วัน');
                $('#nightpoguest').text(night + ' คืน');
            }

            var checkinDate = @json($Quotation->checkin);
            var checkoutDate = @json($Quotation->checkout);
            if (checkinDate == null) {
                $('#checkinpo').text('No Check In Date');
                $('#checkinpoguest').text('No Check In Date');
                $('#checkoutpo').text('-');
                $('#checkoutpoguest').text('-');
            }else{
                $('#checkinpo').text(checkinDate);
                $('#checkoutpo').text(checkoutDate);

                $('#checkinpoguest').text(checkinDate);
                $('#checkoutpoguest').text(checkoutDate);
            }
            //----------------ส่วนบน---------------
            var countrySelect = $('#select');
            var select = countrySelect.val();


            //------------------------บริษัท------------------
            var Companyshow = document.getElementById("Companyshow");
            var Company = document.getElementById("Company");
            // -----------------------ลูกค้า--------------------

            var Guest = document.getElementById("Guest");
            var Guestshow = document.getElementById("Guestshow");
            //-------------------ตาราง---------------------------
            var companyTable = document.getElementById("companyTable");

            var guestTable = document.getElementById("guestTable");
            if (select === "Company") {
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
            }else if (status == 'Other'){
                $('#ProductName').text('Other');
            }
            $('#ProductName').text();
            var table = $('#mainselect1').DataTable();
            var Quotation_ID = $('#Quotation_ID').val(); // Replace this with the actual ID you want to send
            var clickCounter = 1;

            let productDataArray = [];

            // ดึงข้อมูลจากตาราง
            document.querySelectorAll('tr[id^="tr-select-main"]').forEach(function(row) {
                let productID = row.querySelector('input[name="CheckProduct[]"]').value;

                // เก็บข้อมูลในอาเรย์
                productDataArray.push({
                    productID: productID,
                });
            });
            console.log(productDataArray);

            document.querySelector('input[name="hiddenProductData"]').value = JSON.stringify(productDataArray);

            $.ajax({
                url: '{{ route("BillingFolioOver.addProduct", ["Quotation_ID" => ":id"]) }}'.replace(':id', status),
                method: 'GET',
                data: {
                    value: status
                },
                success: function(response) {
                    console.log(response);
                    if (response.products.length > 0) {
                        // Clear the existing rows
                        table.clear();
                        var rowNumbemain = $('#display-selected-items tr').length - 1;
                        console.log(rowNumbemain);
                        var pageSize = 10; // กำหนดจำนวนแถวต่อหน้า
                        var currentPage = 1;
                        var totalItems = response.products.length;
                        var totalPages = Math.ceil(totalItems / pageSize);
                        var maxVisibleButtons = 3; // จำนวนปุ่มที่จะแสดง
                        let hiddenProductData = document.getElementById('hiddenProductData').value;
                        let productDataArrayRetrieved = JSON.parse(hiddenProductData);
                        let productIDsArray = productDataArrayRetrieved.map(product => product.productID);
                        function renderPage(page) {
                            table.clear();
                            let num = rowNumbemain + (page - 1) * pageSize + 1;
                            for (let i = (page - 1) * pageSize; i < page * pageSize && i < totalItems; i++) {
                                const data = response.products[i];
                                const productId = data.id;
                                const productCode = data.code;
                                var existingRowId = $('#tr-select-add' + productId).attr('id');
                                if ($('#' + existingRowId).val() == undefined) {
                                    if (!productIDsArray.includes(productCode)) {
                                        table.row.add([
                                            `<td style="text-align: center">${num++}</td>`,
                                            `<td style="text-align: center">${data.code}</td>`,
                                            `<td style="text-align: center">${data.description}</td>`,
                                            `<td style="display: flex; justify-content: center; align-items: center">
                                                <button type="button" class="btn btn-color-green lift btn_modal select-button-product" id="product-${data.id}" value="${data.id}">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </td>`
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
                        url: '{{ route("BillingFolioOver.addProductselect", ["Quotation_ID" => ":id"]) }}'.replace(':id', product),
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
                                if ($('#product-list' + val.code).length > 0) {
                                    console.log("Product already exists after AJAX call: ", val.code);
                                }

                                $('#product-list-select').append(
                                    '<tr id="tr-select-add' + val.id + '">' +
                                    '<td style="text-align:center;">' + rowNumber + '</td>' +
                                    '<td><input type="hidden" class="randomKey" name="randomKey" id="randomKey" value="' + val.code + '">' + val.code + '</td>' +
                                    '<td style="text-align:left;">' + val.description + '</td>' +
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
                    url: '{{ route("BillingFolioOver.addProducttablecreatemain", ["Quotation_ID" => ":id"]) }}'.replace(':id', Quotation_ID),
                    method: 'GET',
                    data: {
                        value: "all"
                    },
                    success: function(response) {
                        console.log(response);

                        $.each(response.products, function (key, val) {
                            $('#tr-select-add' + val.id).prop('hidden',true);
                            if ($('#productselect' + val.id).val() !== undefined) {
                                if ($('#display-selected-items #tr-select-addmain' + val.id).length === 0) {
                                    number += 1;
                                    var name = '';
                                    var rowNumbemain = $('#display-selected-items tr').length;
                                    quantity = '<div class="input-group">' +
                                                '<input class="Amount form-control" type="text" id="Amount' + number + '" name="Amount[]" value="" rel="' + number + '" style="text-align:center;">' +
                                                '</div>';
                                    $('#display-selected-items').append(
                                        '<tr id="tr-select-addmain' + val.id + '">' +
                                        '<td style="text-align:center;width:10%;vertical-align: middle;"><input type="hidden" id="Code" name="Code[]" value="' + val.code + '">' + rowNumbemain + '</td>' +
                                        '<td style="text-align:left;width:50%;vertical-align: middle;">'+ val.description +'</td>' +
                                        '<td style="text-align:center;width:10%;">'+ quantity +'</td>' +
                                        '<td  style="text-align:center;width:20%;vertical-align: middle;"><button type="button" class="Btn remove-buttonmain" value="' + val.id + '"><i class="fa fa-minus-circle text-danger fa-lg"></i></button></td>' +
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
            $(document).on('keyup', '.Amount', function() {
                for (let i = 0; i < 50; i++) {
                    var number_ID = $(this).attr('rel');
                    var unitmain =  Number($(this).val());
                    console.log(unitmain);

                    totalAmost();
                }
            });
            totalAmost();
        });
        function totalAmost() {
            $(document).ready(function() {
                var typevat  = {{$Quotation->vat_type}};
                let allprice = 0;
                let lessDiscount = 0;
                let beforetax =0;
                let addedtax =0;
                let Nettotal =0;
                let totalperson=0;
                let priceArray = [];
                let pricedistotal = [];// เริ่มต้นตัวแปร allprice และ allpricedis ที่นอกลูป
                var Adult  = parseFloat($('#Adult').val());
                var Children  = parseFloat($('#Children').val());
                let PaxToTalall=0;
                var discountElement  = $('#DiscountAmount').val();
                $('#display-selected-items tr').each(function() {
                    let priceCell = $(this).find('.Amount').val();
                    let pricetotal = parseFloat(priceCell) || 0;
                    console.log(typevat);
                    if (typevat == '50') {
                        allprice += pricetotal;

                        beforetax = allprice/1.07;
                        addedtax = allprice-allprice/1.07;
                        $('#total-amount').text(isNaN(allprice) ? '0' : allprice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#Net-price').text(isNaN(beforetax) ? '0' : beforetax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#total-Vat').text(isNaN(addedtax) ? '0' : addedtax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#Net-Total').text(isNaN(Nettotal) ? '0' : allprice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#NettotalCheck').val(isNaN(allprice) ? '0' : allprice);
                    }else if(typevat == '51')
                    {
                        allprice += pricetotal;
                        console.log(allprice);

                        $('#total-amountEXCLUDE').text(isNaN(allprice) ? '0' : allprice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#Net-Total').text(isNaN(Nettotal) ? '0' : allprice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#NettotalCheck').val(isNaN(allprice) ? '0' : allprice);
                    } else if(typevat == '52'){
                        allprice += pricetotal;

                        beforetax = allprice/1.07;
                        addedtax = allprice-allprice/1.07;
                        $('#total-amountpus').text(isNaN(allprice) ? '0' : allprice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#total-Vatpus').text(isNaN(addedtax) ? '0' : addedtax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#Net-Total').text(isNaN(Nettotal) ? '0' : allprice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#NettotalCheck').val(isNaN(allprice) ? '0' : allprice);
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
                    window.location.href = "{{ route('Additional.index') }}";
                }
            });
        }
        function confirmSubmit(event) {
            event.preventDefault(); // Prevent the form from submitting
            var Quotationold = $('#Quotationold').val();
            var Quotation_ID = $('#Quotation_ID').val();
            var NettotalCheck = $('#NettotalCheck').val();
            var totalComplimentary =parseFloat($('#totalComplimentary').val().replace(/,/g, ''));
            var additional_type = $('#additional_type').val();
            if (additional_type == 'Cash Manual') {
                if (NettotalCheck - totalComplimentary == 0) {
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
                }else{
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "กรุณากรองค่าให้ตรง",
                    });
                }
            }else{
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
        }
    </script>
@endsection
