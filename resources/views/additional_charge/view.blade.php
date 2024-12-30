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

    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">View Additional Charge</div>
                </div>
                <div class="col-auto">

                </div>
            </div> <!-- .row end -->
        </div>
    </div>
    <form id="myForm" action="{{url('/Document/Additional/Charge/update/'.$Quotation->id)}}" method="POST">
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
                                        <select name="additional_type" id="additional_type" class="additional_type select2" disabled>
                                            <option value="H/G" {{$Quotation->additional_type == 'H/G' ? 'selected' : ''}}>H/G Online</option>
                                            <option value="Cash" {{$Quotation->additional_type == 'Cash' ? 'selected' : ''}}>Partial payment and complimentary</option>
                                            <option value="Cash Manual" {{$Quotation->additional_type == 'Cash Manual' ? 'selected' : ''}}>Manual Partial payment and complimentary</option>
                                        </select>
                                    </div>
                                    <div id="Cash_Manualinput" class="col-lg-8 col-md-12 col-sm-12  mt-2 Cash_Manualinput " style="display: none">
                                        <div class="col-lg-12 col-md-12 col-sm-12 row">
                                            <div class="col-lg-4 col-md-12 col-sm-12">
                                                <label for="">Payment</label>
                                                <input type="text" name="Cash" id="Cash" class="form-control Cash" value="{{$Quotation->Cash}}" disabled>
                                            </div>
                                            <div class="col-lg-4 col-md-12 col-sm-12">
                                                <label for="">Complimentary</label>
                                                <input type="text" name="Complimentary" id="Complimentary"class="form-control Complimentary" value="{{$Quotation->Complimentary}}" disabled>
                                            </div>
                                            <div class="col-lg-4 col-md-12 col-sm-12">
                                                <label for="">Total</label>
                                                <input type="text" name="totalComplimentary" id="totalComplimentary" class="form-control totalComplimentary" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        $(document).ready(function() {
                                            var countrySelect = $('#additional_type');
                                                var select = countrySelect.val();
                                                var CashinputManual = document.getElementById('Cash_Manualinput');
                                                var Complimentary = parseFloat($('#Complimentary').val().replace(/,/g, '')) || 0;
                                                var Cash = parseFloat($('#Cash').val().replace(/,/g, ''))|| 0;
                                                if (select === 'Cash Manual') {
                                                    Cash_Manualinput.style.display = "Block";
                                                    var totalComplimentary = Complimentary+Cash;
                                                    $('#totalComplimentary').val(totalComplimentary.toLocaleString('th-TH'));
                                                }
                                            // Listen for change on all elements with class 'paymentType'
                                            $('.additional_type').on('change', function() {
                                                var selectedType = $(this).val();
                                                var parentContainer = $(this).closest('.payment-container'); // Find the parent container
                                                // Hide all payment method sections within this specific container
                                                parentContainer.find('.cashInput, .Cash_Manualinput').hide();
                                                const CashinputManual = document.getElementById('Cash_Manualinput');
                                                const inputsManual = CashinputManual.querySelectorAll("input");
                                                // Show the relevant section based on the selected payment type
                                                inputsManual.forEach(input => input.disabled = false);
                                                if (selectedType === 'Cash Manual') {
                                                    parentContainer.find('.Cash_Manualinput').show();
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
                                    <table id="main" class=" example2 ui striped table nowrap unstackable p-0 " style="width:100%">
                                        <thead >
                                            <tr>
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;text-align:center;width:10%">No.</th>
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:50%;text-align:center;"data-priority="1">Description</th>
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
                                                        <td style="text-align:left;vertical-align: middle;">{{$item->Detail}} </td>
                                                        <td class="Quantity" data-value="{{$item->Amount}}" style="text-align:right;">
                                                            {{number_format($item->Amount)}}
                                                            <input type="hidden" id="quantity{{$var}}" name="Amount[]" rel="{{$var}}" style="text-align:center;vertical-align: middle;"class="quantity-input form-control" value="{{$item->Amount}} "oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
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
                                                    <img src="{{ asset('assets/images/' . $settingCompany->image) }}" style="width: 70%;">
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
            CheckDateAdditional();
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
                        var rowNumbemain = $('#display-selected-items tr').length;
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
                                            num++,
                                            data.code,
                                            data.description,
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
                    $(this).find('td:first-child').text(index +1); // เปลี่ยนเลขลำดับในคอลัมน์แรก
                    console.log($(this).find('td:first-child'));

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
                            $('#main').DataTable().destroy();
                            if ($('#productselect' + val.id).val() !== undefined) {
                                if ($('#display-selected-items #tr-select-addmain' + val.id).length === 0) {
                                    number += 1;
                                    var name = '';
                                    var rowNumbemain = $('#display-selected-items tr').length +1;
                                    console.log(rowNumbemain);

                                    quantity = '<div class="input-group">' +
                                                '<input class="Amount form-control" type="text" id="Amount' + number + '" name="Amount[]" value="" rel="' + number + '" style="text-align:center;">' +
                                                '</div>';
                                    // $('#main').DataTable().destroy();


                                    $('#display-selected-items').append(
                                        '<tr id="tr-select-addmain' + val.id + '">' +
                                        '<td style="text-align:center;width:10%;vertical-align: middle;"><input type="hidden" id="Code" name="Code[]" value="' + val.code + '">' + rowNumbemain + '</td>' +
                                        '<td style="text-align:left;width:50%;vertical-align: middle;">'+ val.description +'</td>' +
                                        '<td style="text-align:center;width:10%;">'+ quantity +'</td>' +
                                        '<td  style="text-align:center;width:20%;vertical-align: middle;"><button type="button" class="Btn remove-buttonmain" value="' + val.id + '"><i class="fa fa-minus-circle text-danger fa-lg"></i></button></td>' +
                                        '</tr>'
                                    );

                                    $('#display-selected-items tr.parent.dt-hasChild.odd').remove();
                                    $('#display-selected-items tr.odd').remove();
                                    $('#main').DataTable({
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

                $(document).on('click', '.remove-buttonmain', function() {
                    var product = $(this).val();
                    console.log(product);

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
        }

        $(document).on('click', '.remove-button1', function() {
            var productId = $(this).val();
            // $('#main').DataTable().destroy();
            var table2 = $('#main').DataTable();
            var row = table2.row($(this).parents('tr'));
            var irow = $(this).closest('tr.child').prev();
            table2.row(irow).remove().draw();
            row.remove();
            table2.draw();

            $('#tr-select-main' + productId).remove();
            $('#display-selected-items tr').each(function(index) {
                $(this).find('td:first').text(index+1); // Change the text of the first cell to be the new sequence number
            });

            // Optionally, call a function to update totals after removing a row
            if (typeof totalAmost === 'function') {
                totalAmost();
            }
        });
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
            $(document).on('keyup', '.quantity-input', function() {
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
                let PaxToTalall=0;


                $('#display-selected-items tr').each(function() {
                    let priceCell = $(this).find('.Amount').val();
                    let pricetotal = parseFloat(priceCell) || 0;
                    let priceCellMain = $(this).find('.quantity-input').val();
                    let pricetotalMain = parseFloat(priceCellMain) || 0;
                    if (typevat == '50') {
                        console.log(pricetotal);
                        allprice += pricetotal+pricetotalMain;

                        beforetax = allprice/1.07;
                        addedtax = allprice-allprice/1.07;
                        $('#total-amount').text(isNaN(allprice) ? '0' : allprice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#Net-price').text(isNaN(beforetax) ? '0' : beforetax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#total-Vat').text(isNaN(addedtax) ? '0' : addedtax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#Net-Total').text(isNaN(Nettotal) ? '0' : allprice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    }else if(typevat == '51')
                    {
                        allprice += pricetotal+pricetotalMain;
                        console.log(allprice);

                        $('#total-amountEXCLUDE').text(isNaN(allprice) ? '0' : allprice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#Net-Total').text(isNaN(Nettotal) ? '0' : allprice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                    } else if(typevat == '52'){
                        allprice += pricetotal+pricetotalMain;

                        beforetax = allprice/1.07;
                        addedtax = allprice-allprice/1.07;
                        $('#total-amountpus').text(isNaN(allprice) ? '0' : allprice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#total-Vatpus').text(isNaN(addedtax) ? '0' : addedtax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                        $('#Net-Total').text(isNaN(Nettotal) ? '0' : allprice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
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
                    window.location.href = "{{ route('Additional.index') }}";
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
    </script>
@endsection
