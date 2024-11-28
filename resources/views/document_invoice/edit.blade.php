@extends('layouts.masterLayout')
<style>
@media screen and (max-width: 500px) {
    .mobileHidden {
    display: none;
    }

    .mobileLabelShow {
    display: inline;
    }

    #mobileshow {
    margin-top: 60px;
    }
}
.table-revenue-detail {
    display: none;
    margin: 1rem 0;
    padding: 1rem;
    background-color: aliceblue;
    border-radius: 7px;
    color: white;
    min-height: 20rem;
    }
    .modal-dialog-custom-90p {
        min-width: 50%;
    }

</style>
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
                    <small class="text-muted">Welcome to Generate Proforma Invoice.</small>
                    <div class=""><span class="span1">Generate Proforma Invoice</span></div>
                </div>
                <div class="col-auto">

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
                <div class="col">
                    <ol class="breadcrumb d-inline-flex bg-transparent p-0 m-0">
                        <li></li>
                        <li></li>
                        <li></li>
                    </ol>
                </div>
            </div> <!-- Row end  -->
        </div> <!-- Row end  -->
        <form id="myForm" action="{{url('/Document/invoice/update/revised/'.$invoice->id)}}" method="POST">
        @csrf
            <div class="container-xl">
                <div class="row clearfix">
                    <div class="col-sm-12 col-12 pi">
                        <div class="card mb-3">
                            <div class="card-body">
                                <section class="card-container bg-card-container">
                                    <section class="card2 gradient-bg">
                                        <div class="card-content bg-card-content-white" class="card-content">
                                            <h5 class="card-title center">Client Details</h5>
                                            <ul class="card-list-withColon">
                                                <li>
                                                <span>Guest Name</span>
                                                @if ($Selectdata == 'Company')
                                                    <span> - </span>
                                                @else
                                                    <span>{{$fullName}}</span>
                                                @endif
                                                </li>
                                                <li>
                                                <span>Company</span>
                                                @if ($Selectdata == 'Company')
                                                    <span>{{$fullName}}</span>
                                                @else
                                                    <span> - </span>
                                                @endif
                                                </li>
                                                <li>
                                                    <span>Tax ID/Gst Pass</span>
                                                    <span>{{$Identification ?? '-'}}</span>
                                                </li>
                                                <li>
                                                    <span>Address</span>
                                                    <span>{{$address}}</span>
                                                </li>
                                                <li>
                                                    <span>Check In Date</span>
                                                    <span>{{$Quotation->checkin ?? 'No Check In Date'}}</span>
                                                </li>
                                                <li>
                                                    <span>Check Out Date</span>
                                                    <span>{{$Quotation->checkout ?? '-'}}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </section>
                                    <section class="card2 card-circle">
                                        <div class="tech-circle-container mx-4" style="background-color: #135d58;">
                                            <div class="outer-glow-circle"></div>
                                            <div class="circle-content">
                                                <p class="circle-text">
                                                <p class="f-w-bold fs-3">{{ number_format($Quotation->Nettotal + $Additional_Nettotal - $totalinvoice , 2, '.', ',') }}</p>
                                                <span class="subtext fs-6" >Total Amount</span>
                                                </p>
                                            </div>
                                            <div class="outer-ring">
                                                <div class="rotating-dot"></div>
                                            </div>
                                        </div>
                                    </section>
                                <section class="card2 gradient-bg">
                                <div class="card-content3 bg-card-content-white">
                                    <div class="card-title center" style="position: relative;"><span>Folio </span></div>
                                    <ul class="card-list-between">
                                        <span>
                                            <li class="pr-3">
                                                <span >Proposal ID ({{$QuotationID}})</span>
                                                <span class=" hover-effect i  f-w-bold " style="color: #438985;" > {{ number_format($Quotation->Nettotal, 2, '.', ',') }}</span>
                                            </li>
                                            @if ($Additional_ID)
                                                <li class="pr-3">
                                                    <span >Additional ID ({{$Additional_ID}})</span>
                                                    <span class=" hover-effect i f-w-bold" style="color: #438985;">{{ number_format($Additional_Nettotal, 2, '.', ',') }}</span>
                                                </li>
                                            @endif
                                            @if ($invoices)
                                                @foreach ( $invoices as $item)
                                                <li class="pr-3">
                                                    <span >Invoice ID ({{$item->Invoice_ID}})</span>
                                                    <span class=" text-danger i f-w-bold"> - {{ number_format($item->sumpayment, 2, '.', ',') }}</span>
                                                </li>
                                                @endforeach
                                            @endif
                                        </span>
                                    </ul>
                                    <li class="outstanding-amount">
                                        <span class="f-w-bold">Outstanding Amount &nbsp;:</span>
                                        <span class="text-success f-w-bold"> {{ number_format($Quotation->Nettotal + $Additional_Nettotal - $totalinvoice, 2, '.', ',') }}</span>
                                        <input type="hidden" id="amount" name="amount" value="{{$Quotation->Nettotal + $Additional_Nettotal - $totalinvoice}}">
                                    </li>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <b for="Payment">Payment by (%) Remaining 100%</b>
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <input class="custom-radio mt-0" type="radio" value="0" id="radio0" name="paymentRadio" onclick="togglePaymentFields()">
                                            </div>
                                            <input type="number" class="form-control" id="Payment0" name="PaymentPercent" min="1" max="100" disabled oninput="validateInput(this)">
                                            <span class="input-group-text">%</span>
                                            <input type="hidden" id="Amount">
                                        </div>
                                        <script>
                                            function validateInput(input) {
                                                var amount = document.getElementById('amount').value;
                                                if (parseFloat(input.value) > 100 ) {
                                                    input.value = 100;
                                                }
                                                var vat_type = parseFloat(document.getElementById('vat_type').value);
                                                let Subtotal =0;
                                                let total =0;
                                                let addtax = 0;
                                                let before = 0;
                                                let balance =0;
                                                if (vat_type == 51) {
                                                    Subtotal = (amount*input.value)/100;
                                                    total = Subtotal;
                                                    addtax = 0;
                                                    before = Subtotal;
                                                    balance = Subtotal;
                                                }else{
                                                    Subtotal = (amount*input.value)/100;
                                                    total = Subtotal/1.07;
                                                    addtax = Subtotal-total;
                                                    before = Subtotal-addtax;
                                                    balance = amount-Subtotal;
                                                }

                                                $('#Subtotal').text(isNaN(Subtotal) ? '0' : Subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                                                $('#SubtotalAll').text(isNaN(Subtotal) ? '0' : Subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                                                $('#Added').text(isNaN(addtax) ? '0' : addtax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                                                $('#Before').text(isNaN(before) ? '0' : before.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                                                $('#Total').text(isNaN(Subtotal) ? '0' : Subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                                                $('#balance').val(balance);
                                                $('#sum').val(Subtotal);
                                            }
                                        </script>
                                    </div>
                                    <div class="col-lg-4">
                                        <b for="Payment by (THB)">Payment by (THB)</b>
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <input class="custom-radio mt-0" type="radio" value="1" id="radio1" name="paymentRadio"  onclick="togglePaymentFields()">
                                            </div>
                                            <input type="number" class="form-control" id="Payment1" name="Payment" value="{{$invoice->payment}}" disabled oninput="validateInput1(this)">
                                            <input type="hidden" id="Amount1">
                                        </div>
                                        <script>
                                            function validateInput1(input) {
                                                var Nettotal = parseFloat(document.getElementById('amount').value.replace(/,/g, '')) || 0; // ดึง Nettotal และจัดการจุลภาค
                                                if (parseFloat(input.value)) {
                                                    if ( input.value  > Nettotal) {
                                                        input.value = Nettotal; // ถ้าค่าที่กรอกมากกว่า Nettotal ให้ใช้ Nettotal แทน
                                                    }
                                                }
                                                var vat_type = parseFloat(document.getElementById('vat_type').value);
                                                let Subtotal =0;
                                                let total =0;
                                                let addtax = 0;
                                                let before = 0;
                                                let balance =0;
                                                if (vat_type == 51) {
                                                    Subtotal =  parseFloat(input.value);
                                                    total = Subtotal;
                                                    addtax = 0;
                                                    before = Subtotal;
                                                    balance = Nettotal-Subtotal;
                                                }else{
                                                    Subtotal =  parseFloat(input.value);
                                                    total = Subtotal/1.07;
                                                    addtax = Subtotal-total;
                                                    before = Subtotal-addtax;
                                                    balance = Nettotal-Subtotal;
                                                }

                                                $('#Subtotal').text(isNaN(Subtotal) ? '0' : Subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                                                $('#SubtotalAll').text(isNaN(Subtotal) ? '0' : Subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                                                $('#Added').text(isNaN(addtax) ? '0' : addtax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                                                $('#Before').text(isNaN(before) ? '0' : before.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                                                $('#Total').text(isNaN(Subtotal) ? '0' : Subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                                                $('#balance').val(balance);
                                                $('#sum').val(Subtotal);
                                            }
                                        </script>
                                    </div>
                                    <div class="col-lg-4">
                                        <b>Valid</b>
                                        <input type="text" name="valid" id="valid" class="form-control " value="{{$valid}}"  required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-sm-12 col-12">
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
                                            <div class="PROPOSAL col-lg-7" style="transform: translateX(6px)" >
                                                <div class="row">
                                                    <b class="titleQuotation" style="font-size: 20px;color:rgb(255, 255, 255);">Profoma Invoice</b>
                                                    <b  class="titleQuotation" style="font-size: 16px;color:rgb(255, 255, 255);">{{$InvoiceID}}</b>
                                                </div>
                                                <input type="hidden" id="Quotation_ID" name="Quotation_ID" value="{{$InvoiceID}}">
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
                                                            <input type="text" id="datestart" class="form-control readonly-input" name="IssueDate"value="{{$IssueDate}}" style="text-align: left;"readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-12 col-sm-12 mt-2">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-12 col-sm-12"style="display:flex; justify-content:right; align-items:center;">
                                                            <span>Expiration Date:</span>
                                                        </div>
                                                        <div class="col-lg-6 col-md-12 col-sm-12">
                                                            <input type="text" id="dateex" class="form-control readonly-input" name="Expiration" value="{{$Expiration}}" style="text-align: left;"readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    @if ($Selectdata == 'Company')
                                        <div class="proposal-cutomer-detail" id="companyTable">
                                            <ul>
                                            <b class="font-upper com">Company Information</b>
                                            <li class="mt-3">
                                                <b>Company Name</b>
                                                <span id="Company_name">{{$fullName}}</span>
                                            </li>
                                            <li>
                                                <b>Company Address</b>
                                                <span id="Address">{{$address}} </span>
                                                <b></b>
                                            </li>
                                            <span class="wrap-full">
                                                <li >
                                                    <b>Company Number</b>
                                                    <span id="Company_Number">{{ $phone->Phone_number }}</span>
                                                </li>
                                                <li >
                                                    <b>Company Fax</b>
                                                    <span id="Company_Fax">
                                                        <span id="Company_Fax">{{ $Fax_number }}</span>
                                                    </span>
                                                </li>
                                            </span>
                                            <li>
                                                <b>Company Email</b>
                                                <span id="Company_Email">{{$Email}}</span>
                                            </li>
                                            <li>
                                                <b>Taxpayer Identification</b>
                                                <span id="Taxpayer" >{{$Identification}}</span>
                                            </li>
                                            <li> </li>
                                            <b class="font-upper com">Personal Information</b>
                                            <li >
                                                <b>Contact Name</b>
                                                <span id="Company_contact">{{$Contact_Name}}</span>
                                            </li>
                                            <li >
                                                <b>Contact Number</b>
                                                <span id="Contact_Phone">{{ $Contact_phone->Phone_number}}</span>
                                            </li>
                                            <li>
                                                <b>Contact Email</b>
                                                <span id="Contact_Email" >{{$Contact_Email}}</span>
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
                                                <span id="checkinpo">{{$Quotation->checkin ?? 'No Check In Date'}}</span>
                                            </li>
                                            <li>
                                                <b>Check Out</b>
                                                <span id="checkoutpo">{{$Quotation->checkout ?? ' '}}</span>
                                            </li>
                                            <li>
                                                <b>Length of Stay</b>
                                                <span style="display: flex"><p id="daypo" class="m-0">{{$Quotation->day}} วัน</p> <p id="nightpo" class="m-0"> {{' , '.$Quotation->night}} คืน </p></span>
                                            </li>
                                            <li>
                                                <b>Number of Guests</b>
                                                <span style="display: flex"><p id="Adultpo" class="m-0">{{$Quotation->adult}} Adult </p><p id="Childrenpo" class="m-0">{{' , '.$Quotation->children}}  Children</p></span>
                                            </li>
                                            </ul>
                                        </div>
                                    @else
                                        <div class="proposal-cutomer-detail" id="guestTable" >
                                            <ul>
                                            <b class="font-upper com">Guest Information</b>
                                            <li class="mt-3">
                                                <b>Guest  Name</b>
                                                <span id="guest_name">{{$fullName}}</span>
                                            </li>


                                            <li>
                                                <b>Guest  Address</b>
                                                <span id="guestAddress">{{$address}}</span>
                                                <b></b>
                                            </li>

                                            <li >
                                                <b>Guest  Number</b>
                                                <span id="guest_Number">{{ $phone->Phone_number}}</span>
                                            </li>

                                            <li>
                                                <b>Guest  Email</b>
                                                <span id="guest_Email">{{$Email}}</span>
                                            </li>
                                            <li>
                                                <b>Identification Number</b>
                                                <span id="guestTaxpayer" >{{$Identification}}</span>
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
                                                <span id="checkinpoguest">{{$Quotation->checkin ?? 'No Check In Date'}}</span>
                                            </li>
                                            <li>
                                                <b>Check Out</b>
                                                <span id="checkoutpoguest">{{$Quotation->checkout ?? ' '}}</span>
                                            </li>
                                            <li>
                                                <b>Length of Stay</b>
                                                <span style="display: flex"><p id="daypoguest" class="m-0">{{$Quotation->day}} วัน </p><p id="nightpoguest" class="m-0"> {{' , '.$Quotation->night}} คืน  </p></span>
                                            </li>
                                            <li>
                                                <b>Number of Guests</b>
                                                <span style="display: flex"><p id="Adultpoguest" class="m-0"> {{$Quotation->adult}} Adult </p><p id="Childrenpoguest" class="m-0">{{' , '.$Quotation->children}}  Children </p></span>
                                            </li>

                                            </ul>

                                        </div>
                                    @endif
                                </div>
                                <div class="styled-hr"></div>
                                <div class="row mt-4">
                                    <table class=" table table-hover align-middle mb-0" style="width:100%">
                                        <thead >
                                            <tr>
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:10%;text-align:center">No.</th>
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">Description</th>
                                                <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:15%;text-align:center">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody id="display-selected-items">
                                            <tr>
                                                <td style="text-align:center">1</td>
                                                <td style="text-align:left">
                                                    Proposal ID : {{$QuotationID}} </span> กรุณาชำระมัดจำ งวดที่ {{$Deposit}}
                                                </td>
                                                <td style="text-align:right"><span id="Subtotal"></span> THB </td>
                                            </tr>
                                            <tr>
                                                <td><br></td>
                                                <td style="text-align:right">Subtotal :</td>
                                                <td style="text-align:right"><span id="SubtotalAll"></span> THB</td>
                                            </tr>
                                            <tr>
                                                <td><br></td>
                                                <td style="text-align:right">Price Before Tax :</td>
                                                <td style="text-align:right"><span id="Before"></span> THB</td>
                                            </tr>
                                            <tr>
                                                <td><br></td>
                                                <td style="text-align:right">Value Added Tax :</td>
                                                <td style="text-align:right"><span id="Added"></span> THB</td>
                                            </tr>
                                            <tr>
                                                <td><br></td>
                                                <td style="text-align:right">Net Total :</td>
                                                <td style="text-align:right"><span id="Total"></span> THB</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-12 mt-3">
                                    <div class="col-lg-4 col-md-6 col-sm-12 my-1">
                                        <strong class="com" style="font-size: 18px">FULL PAYMENT AFTER RESERVATION</strong>
                                    </div>
                                    <span class="col-md-8 col-sm-12"id="Payment50" style="display: block" >
                                        Transfer to <strong> " Together Resort Limited Partnboership "</strong> following banks details.<br>
                                        If you use transfer, Please inform Accounting / Finance Department Tel or LINE ID<span style="font-size: 18px"> @Together-resort</span><br>
                                        pay-in slip to number 032-708-888 every time for the correctness of payment allocation.<br>
                                    </span>
                                    <div class="row">
                                        <div class="col-lg-8 col-md-6 col-sm-12">
                                            <div class="col-12  mt-2">
                                                <div class="row">
                                                    <div class="col-2 mt-2" style="display: flex;justify-content: center;align-items: center;">
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
                                    <div class="col-12 my-4">
                                        <div class="row">
                                            <div class="col-lg-2 centered-content"></div>
                                            <div class="col-lg-2 centered-content"></div>
                                            <div class="col-lg-2 centered-content"></div>
                                            <div class="col-lg-2 centered-content"></div>
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
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 row mt-5">
                                    <div class="col-4">
                                        <input type="hidden"id="document_type" name="document_type" value="PD">
                                        <input type="hidden" id="Deposit" name="Deposit" value="{{$Deposit}}">
                                        <input type="hidden" name="InvoiceID"id="InvoiceID" value="{{$InvoiceID}}">
                                        <input type="hidden" name="QuotationID" id="QuotationID" value="{{$QuotationID}}">
                                        <input type="hidden" name="sum"  id="sum">
                                    </div>
                                    <div class="col-4 "  style="display:flex; justify-content:center; align-items:center;">
                                        <button type="button" class="btn btn-secondary lift btn_modal btn-space" onclick="BACKtoEdit()">
                                            Cancel
                                        </button>
                                        <button type="button" class="btn btn-primary lift btn_modal btn-space" onclick="submitPreview()">
                                            Preview
                                        </button>
                                        <button type="button" class="btn btn-color-green lift btn_modal"  onclick="submitsave()">save</button>
                                    </div>
                                    <div class="col-4"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
    <input type="hidden" id="vat_type" name="vat_type" value="{{$vat_type}}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- dataTable -->
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.semanticui.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.semanticui.js"></script>
    <script type="text/javascript" src="{{ asset('assets/js/daterangepicker.min.js')}}" defer></script>
    <script type="text/javascript" src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css')}}" />
    <script type="text/javascript">

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
        $(function() {
            // ฟอร์แมตวันที่ให้อยู่ในรูปแบบ dd/mm/yyyy
            $('#valid').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                autoApply: true,
                minDate: moment().startOf('day'),
                locale: {
                    format: 'DD/MM/YYYY' // ฟอร์แมตเป็น dd/mm/yyyy
                }
            });
            $('#valid').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY'));
            });
            $(document).on('wheel', function(e) {
                // Check if the date picker is open
                if ($('.daterangepicker').is(':visible')) {
                    // Close the date picker
                    $('.daterangepicker').hide();
                }
            });
        });
        $(document).ready(function() {
            var radio0 = document.getElementById('radio0');
            var radio1 = document.getElementById('radio1');
            var payment0 = document.getElementById('Payment0');
            var payment1 = document.getElementById('Payment1');

            // ตรวจสอบว่าทุกองค์ประกอบมีอยู่ใน DOM

            console.log(0);
            if (payment0.value !== "") {
                document.getElementById('radio0').checked = true;
                payment0.disabled = false;
                payment1.disabled = true;
                payment1.value = ""; // ล้างค่า
                payment();
            } else if (payment1.value !== "") {
                document.getElementById('radio1').checked = true;
                payment0.disabled = true;
                payment1.disabled = false;
                payment0.value = ""; // ล้างค่า
                payment();
            }

        });
        function payment() {
            var vat_type = parseFloat(document.getElementById('vat_type').value);
            var Payment1 = parseFloat(document.getElementById('Payment1').value);
            var Nettotal = parseFloat(document.getElementById('amount').value.replace(/,/g, '')) || 0;
            console.log(Payment1);

            let Subtotal =0;
            let total =0;
            let addtax = 0;
            let before = 0;
            let balance =0;
            if (vat_type == 51) {
                Subtotal =  parseFloat(Payment1);
                total = Subtotal;
                addtax = 0;
                before = Subtotal;
                balance = Nettotal-Subtotal;
            }else{
                Subtotal =  parseFloat(Payment1);
                total = Subtotal/1.07;
                addtax = Subtotal-total;
                before = Subtotal-addtax;
                balance = Nettotal-Subtotal;
            }

            $('#Subtotal').text(isNaN(Subtotal) ? '0' : Subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#SubtotalAll').text(isNaN(Subtotal) ? '0' : Subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#Added').text(isNaN(addtax) ? '0' : addtax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#Before').text(isNaN(before) ? '0' : before.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#Total').text(isNaN(Subtotal) ? '0' : Subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#balance').val(balance);
            $('#sum').val(Subtotal);
        }
    </script>
    <script>
        const table_name = ['main'];
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
        $(document).ready(function() {
            var table = $(".table-together").DataTable({
                paging: false,
                searching: false,
                ordering: true,
                info: false,
                responsive: {
                    details: {
                    type: "column",
                    target: "tr"
                    }
                }
            });
            function adjustDataTable() {
                $.fn.dataTable
                .tables({
                visible: true,
                api: true,
                })
                .columns.adjust()
                .responsive.recalc();
            }
            $("#ModalProposalSummary").on("shown.bs.modal", adjustDataTable);
            $('#ModalProposalSummary details').on('toggle', function() {
                if (this.open) {
                    adjustDataTable();
                }
            });
        });
        document.getElementById("switchButton").addEventListener("click", function () {
            const defaultContent = document.getElementById("defaultContent");
            const toggleContent = document.getElementById("toggleContent");

            if (defaultContent.style.display === "none") {
                defaultContent.style.display = "block";
                toggleContent.style.display = "none";
                this.innerHTML = "&#8644;";
            } else {
                defaultContent.style.display = "none";
                toggleContent.style.display = "block";
                this.innerHTML = "&#8646;";
            }
        });

        function togglePaymentFields() {
            var radio0 = document.getElementById('radio0');
            var radio1 = document.getElementById('radio1');
            var payment0 = document.getElementById('Payment0');
            var payment1 = document.getElementById('Payment1');

            // ตรวจสอบว่าทุกองค์ประกอบมีอยู่ใน DOM
            if (!radio0 || !radio1 || !payment0 || !payment1) {
                console.warn('Some elements are missing in the DOM.');
                return;
            }

            if (radio0.checked) {
                payment0.disabled = false;
                payment1.disabled = true;
                payment1.value = ""; // ล้างค่า
            } else if (radio1.checked) {
                payment0.disabled = true;
                payment1.disabled = false;
                payment0.value = ""; // ล้างค่า
            }
        }

        function submitsave() {
            console.log(1);
            document.getElementById("myForm").removeAttribute('target');
            // สร้าง input แบบ hidden ใหม่
            var input = document.createElement("input");
            input.type = "hidden";
            input.name = "save";
            input.value = 1;

            // เพิ่ม input ลงในฟอร์ม

            document.getElementById("myForm").appendChild(input);
            document.getElementById("myForm").submit();
        }
        function submitPreview() {
            console.log(1);
            document.getElementById("myForm").removeAttribute('target');
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
        function BACKtoEdit(){
            event.preventDefault();
            Swal.fire({
                title: "คุณต้องการยกเลิกใช่หรือไม่?",
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "ตกลง",
                cancelButtonText: "ยกเลิก",
                confirmButtonColor: "#28a745",
                dangerMode: true
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log(1);
                    // If user confirms, submit the form
                    window.location.href = "{{ route('invoice.index') }}";
                }
            });
        }
    </script>

@endsection
