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
    .styled-hr {
        border: none; /* เอาขอบออก */
        border: 1px solid #2D7F7B; /* กำหนดระยะห่างด้านล่าง */
    }
    .com {
        display: inline-block;  /* ทำให้ border-bottom มีความยาวเท่ากับข้อความ */
        border-bottom: 2px solid #2D7F7B;  /* กำหนดเส้นใต้ */
        padding-bottom: 5px;
        font-size: 20px;
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
    .custom-radio {
      appearance: none;
      -webkit-appearance: none;
      width: 15px;
      height: 15px;
      border: 2px solid #2C7F7A;
      border-radius: 50%;
      outline: none;
      background-color: #fff;
      position: relative;
      cursor: pointer;
    }

    /* ตั้งค่าตอนที่ checked */
    .custom-radio:checked {
      background-color: #2C7F7A;
    }


    .custom-radio:checked::before {
      content: '\2714';
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 10px;
      color: #fff;
    }.custom-radio {
      appearance: none;
      -webkit-appearance: none;
      width: 15px;
      height: 15px;
      border: 2px solid #2C7F7A;
      border-radius: 50%;
      outline: none;
      background-color: #fff;
      position: relative;
      cursor: pointer;
    }

    /* ตั้งค่าตอนที่ checked */
    .custom-radio:checked {
      background-color: #2C7F7A;
    }


    .custom-radio:checked::before {
      content: '\2714';
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 10px;
      color: #fff;
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
                    <small class="text-muted">Welcome to Generate Proforma Invoice.</small>
                    <div class=""><span class="span1">Generate Proforma Invoice</span></div>
                </div>
            </div> <!-- .row end -->
        </div>
    </div>

    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
            <div class="row align-items-center mb-2" >
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
            </div> <!-- Row end  -->
        </div> <!-- Row end  -->
        <div class="row clearfix">
            <div class="col-sm-12 col-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <form id="myForm" action="{{url('/Document/invoice/update/revised/'.$invoice->id)}}" method="POST">
                            @csrf
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
                                        <div class="PROPOSAL col-lg-7" >
                                            <div class="row">
                                                <b class="titleQuotation" style="font-size: 24px;color:rgb(255, 255, 255);">Profoma Invoice</b>
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
                                                        <input type="text" id="datestart" class="form-control readonly-input" name="IssueDate" value="{{$IssueDate}}" style="text-align: left;"readonly >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-12 col-sm-12 mt-2">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-12 col-sm-12"style="display:flex; justify-content:right; align-items:center;">
                                                        <span>Expiration Date:</span>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <input type="text" id="dateex" class="form-control readonly-input" name="Expiration"value="{{$Expiration}}" style="text-align: left;"readonly >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-lg-7 col-md-12 col-sm-12" style=" border-right-style: solid  ; border-right-width: 2px;border-right-color:#109699">
                                    @if ($Quotation->type_Proposal == 'Company')
                                        <b id="TiTlecompanyTable" class="com mt-2 my-2"style="font-size:18px">Company Information</b>
                                        <table id="companyTable" >
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px; width:30%;font-weight: bold;color:#000;">Company Name :</b></td>
                                                <td>
                                                    <span id="Company_name" name="Company_name" >{{$comtypefullname}}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Company Address :</b></td>
                                                <td><span id="Address" >
                                                    @if ($TambonID)
                                                        {{'ตำบล' . $TambonID->name_th}} {{'อำเภอ' .$amphuresID->name_th}}
                                                    @endif
                                                </span></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td><span id="Address2" >
                                                    @if ($TambonID)
                                                        {{'จังหวัด' .$provinceNames->name_th}} {{$TambonID->Zip_Code}}
                                                    @endif
                                                </span></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Company Number :</b></td>
                                                <td>
                                                    <span id="Company_Number">{{ $company_phone->Phone_number }}</span>
                                                    <b style="margin-left: 10px;color:#000;">Company Fax : </b><span id="Company_Fax">
                                                        @if (is_object($company_fax) && property_exists($company_fax, 'Fax_number'))
                                                            <span id="Company_Fax">{{ $company_fax->Fax_number }}</span>
                                                        @else
                                                            <span id="Company_Fax">-</span>
                                                        @endif
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Company Email :</b></td>
                                                <td><span id="Company_Email">{{$Company->Company_Email}}</span></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Taxpayer Identification : </b></td>
                                                <td><span id="Taxpayer">{{$Company->Taxpayer_Identification}}</span></td>
                                            </tr>
                                        </table>
                                        <b id="TiTlecontractTable" class="com mt-2 my-2"style="font-size:18px">Personal Information</b>
                                        <table id="contractTable">
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Contact Name :</b></td>
                                                <td>
                                                    <span id="Company_contact">{{$Contact_name->First_name}} {{$Contact_name->Last_name}}</span>
                                                    <b style="margin-left: 10px;color:#000;">Contact Number : </b><span id="Contact_Phone">{{ $Contact_phone->Phone_number}}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Contact Email : </b></td>
                                                <td><span id="Contact_Email">{{$Contact_name->Email}}</span></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px;color:#fff;">Taxpayer Identification : </b></td>
                                                <td style="color: #fff"><span id="Taxpayer"></span></td>
                                            </tr>
                                        </table>
                                    @else

                                        <b id="TiTleguestTable" class="com mt-2 my-2"style="font-size:18px;">Guest Information</b>
                                        <table id="guestTable">
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px; width:30%;font-weight: bold;color:#000;">Guest Name :</b></td>
                                                <td>
                                                    <span id="guest_name" name="guest_name" >{{$Company->First_name}} {{$Company->Last_name}}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Guest Address :</b></td>
                                                <td>
                                                    <span id="Address">{{$Company->Address}}
                                                        @if ($TambonID)
                                                            {{'ตำบล' . $TambonID->name_th}} {{'อำเภอ' .$amphuresID->name_th}}
                                                        @endif
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <span id="Address2" >
                                                        @if ($TambonID)
                                                            {{'จังหวัด' .$provinceNames->name_th}} {{$TambonID->Zip_Code}}
                                                        @endif
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Guest Number :</b></td>
                                                <td>
                                                    <span id="guest_Number">{{ $company_phone->Phone_number}}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Guest Email :</b></td>
                                                <td><span id="guest_Email">{{$Company->Email}}</span></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Identification Number : </b></td>
                                                <td><span id="guestTaxpayer">{{$Company->Identification_Number}}</span></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px;color:#fff;">Taxpayer Identification : </b></td>
                                                <td style="color: #fff"><span id="Taxpayer"></span></td>
                                            </tr>
                                        </table>
                                    @endif
                                </div>
                                <div class="col-lg-4 col-md-12 col-sm-12">
                                    <div><br><br><br><br></div>
                                    <div class="col-12 row" >
                                        <table style="margin-left: 20px;" >
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Check In :</b></td>
                                                <td>
                                                    <span id="Contact_name" name="Contact_name" >{{$checkin}}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Check Out : </b></td>
                                                <td>
                                                    <span id="Company_Number">{{$checkout}}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Length of Stay :</b></td>
                                                <td>
                                                    <span id="Company_Number">{{$Quotation->day}} วัน {{$Quotation->night}} คืน</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Number of Guests :</b></td>
                                                <td>
                                                    <span id="Company_Number">{{$Quotation->adult}} Adult , {{$Quotation->children}} Children</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Valid :</b></td>
                                                <td>
                                                    <input type="text" name="valid" id="valid" class="form-control " value="{{$valid}}" required>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="styled-hr"></div>
                            <input type="hidden" name="eventformat" id="eventformat" value="{{$Quotation->eventformat}}">
                            <div class="row mt-2">
                                <div class="col-lg-6">
                                    <label for="Payment">Payment by (%) Remaining 100%</label>
                                    <div class="input-group">
                                        <div class="input-group-text">
                                            <input class="custom-radio mt-0" type="radio" value="0" id="radio0" name="paymentRadio" onclick="togglePaymentFields()">
                                        </div>
                                        <input type="number" class="form-control" id="Payment0" name="PaymentPercent" min="1" max="100" disabled oninput="validateInput(this)"value="{{$invoice->paymentPercent}}">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="Payment by (THB)">Payment by (THB)</label>
                                    <div class="input-group">
                                        <div class="input-group-text">
                                            <input class="custom-radio mt-0" type="radio" value="1" id="radio1" name="paymentRadio"  onclick="togglePaymentFields()">
                                        </div>
                                        <input type="text" class="form-control" id="Payment1" name="Payment" disabled oninput="validateInput1(this)" value="{{$invoice->payment}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <table class=" table table-hover align-middle mb-0" style="width:100%">
                                    <thead >
                                        <tr>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:10%;text-align:center">No.</th>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">Description</th>
                                            <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:10%;text-align:center">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="display-selected-items">
                                        <tr>
                                            <td style="text-align:center">1</td>
                                            <td style="text-align:left">Proposal ID : {{$QuotationID}}  <span id="Amount" style="display: none;"></span>
                                                <span id="Amount1" style="display: none;"></span> กรุณาชำระมัดจำ งวดที่ <input type="hidden" name="Deposit" style="width:2%;border-radius:5px;padding:2px 5px"  id="Deposit" value="{{$Deposit}}" disabled>{{$Deposit}}</td>
                                            <td style="text-align:right"><span id="Subtotal"></span>฿ <input type="hidden" name="Nettotal" id="Nettotal" value="{{$Quotation->Nettotal}}"></td>
                                        </tr>
                                        <tr>
                                            <td><br></td>
                                            <td style="text-align:right">Subtotal :</td>
                                            <td style="text-align:right"><span id="SubtotalAll"></span>฿</td>
                                        </tr>
                                        <tr>
                                            <td><br></td>
                                            <td style="text-align:right">Price Before Tax :</td>
                                            <td style="text-align:right"><span id="Before"></span>฿</td>
                                        </tr>
                                        <tr>
                                            <td><br></td>
                                            <td style="text-align:right">Value Added Tax :</td>
                                            <td style="text-align:right"><span id="Added"></span>฿</td>
                                        </tr>
                                        <tr>
                                            <td><br></td>
                                            <td style="text-align:right">Net Total :</td>
                                            <td style="text-align:right"><span id="Total"></span>฿</td>
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
                                            <br><br>
                                            <span>{{@Auth::user()->name}}</span>
                                            <span id="issue_date_document">{{$IssueDate}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row mt-5">
                                <div class="col-12 row mt-5">
                                    <div class="col-4">
                                        <input type="hidden" name="InvoiceID"id="InvoiceID" value="{{$InvoiceID}}">
                                        <input type="hidden" name="QuotationID" id="QuotationID" value="{{$QuotationID}}">
                                        <input type="hidden" name="company"  id="company" value="{{$CompanyID}}">
                                        <input type="hidden" name="balance"  id="balance">
                                        <input type="hidden" name="sum"  id="sum">
                                        <input type="hidden" name="Deposit"  id="Deposit" value="{{$Deposit}}">
                                        <input type="hidden" name="selecttype"  id="selecttype" value="{{$Quotation->type_Proposal}}">
                                        <input type="hidden" name="Refler_ID"  id="Refler_ID" value="{{$Refler_ID}}">
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
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    </script>
    <script>
         $(function() {
            // ฟอร์แมตวันที่ให้อยู่ในรูปแบบ dd/mm/yyyy
            $('#valid').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                autoApply: true,
                locale: {
                    format: 'DD/MM/YYYY' // ฟอร์แมตเป็น dd/mm/yyyy
                }
            });
            $('#valid').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY'));
            });

        });
        function validateInput(input) {
            if (input.value > 100) {
                input.value = 100;
            }
            $('#Amount').text(input.value + '%');
        }
        function validateInput1(input) {
            var Nettotal = parseFloat(document.getElementById('Nettotal').value.replace(/,/g, '')) || 0; // ดึง Nettotal และจัดการจุลภาค
            var inputValue = input.value.replace(/,/g, ''); // ลบจุลภาคออกจากค่าที่กรอก

            if (inputValue) {
                var numericValue = parseFloat(inputValue); // แปลงค่าเป็นตัวเลข
                if (numericValue > Nettotal) {
                    numericValue = Nettotal;
                    input.value = Nettotal; // ถ้าค่าที่กรอกมากกว่า Nettotal ให้ใช้ Nettotal แทน
                }

                // จัดรูปแบบตัวเลขให้มีจุดทศนิยม 2 ตำแหน่งและคั่นหลักพันด้วยจุลภาค
                var formattedValue = numericValue.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                $('#Amount1').text(formattedValue); // แสดงค่าที่จัดรูปแบบแล้ว
            } else {
                $('#Amount1').text(''); // ถ้า input ว่าง ให้แสดงเป็นค่าว่าง
            }
        }


        $(document).on('keyup', '#Payment0', function() {
            var Payment0 =  Number($(this).val());
            var Nettotal = parseFloat(document.getElementById('Nettotal').value);
            let Subtotal =0;
            let total =0;
            let addtax = 0;
            let before = 0;
            let balance =0;
            Subtotal = (Nettotal*Payment0)/100;
            total = Subtotal/1.07;
            addtax = Subtotal-total;
            before = Subtotal-addtax;
            balance = Nettotal-Subtotal;
            $('#Subtotal').text(isNaN(Subtotal) ? '0' : Subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#SubtotalAll').text(isNaN(Subtotal) ? '0' : Subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#Added').text(isNaN(addtax) ? '0' : addtax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#Before').text(isNaN(before) ? '0' : before.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#Total').text(isNaN(Subtotal) ? '0' : Subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#balance').val(balance);
            $('#sum').val(Subtotal);

        });
        $(document).on('keyup', '#Payment1', function() {
            var Payment1 =  Number($(this).val());
            var Nettotal = parseFloat(document.getElementById('Nettotal').value);
            let Subtotal =0;
            let total =0;
            let addtax = 0;
            let before = 0;
            let balance =0;
            Subtotal = Payment1;
            total = Subtotal;
            addtax = 0;
            before = Subtotal;
            balance = Nettotal-Subtotal;
            console.log(balance);
            $('#Subtotal').text(isNaN(Subtotal) ? '0' : Subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#SubtotalAll').text(isNaN(Subtotal) ? '0' : Subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#Added').text(isNaN(addtax) ? '0' : addtax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#Before').text(isNaN(before) ? '0' : before.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#Total').text(isNaN(Subtotal) ? '0' : Subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#balance').val(balance);
            $('#sum').val(Subtotal);
        });
        function togglePaymentFields() {
            var radio0 = document.getElementById('radio0');
            var radio1 = document.getElementById('radio1');
            var payment0 = document.getElementById('Payment0');
            var payment1 = document.getElementById('Payment1');
            var amount = document.getElementById('Amount');
            var amount1 = document.getElementById('Amount1');
            if (radio0.checked) {
                payment0.disabled = false;
                payment1.disabled = true;
                amount.style.display = 'inline';
                amount1.style.display = 'none';
                payment1.value=" ";
            } else if (radio1.checked) {
                payment0.disabled = true;
                payment1.disabled = false;
                amount.style.display = 'none';
                amount1.style.display = 'inline';
                payment0.value=" ";
            }
        }
        $(document).ready(function() {
            var radio0 = document.getElementById('radio0');
            var radio1 = document.getElementById('radio1');
            var payment0 = document.getElementById('Payment0');
            var payment1 = document.getElementById('Payment1');
            var amount = document.getElementById('Amount');
            var amount1 = document.getElementById('Amount1');
            if (payment0.value !== "") {
                document.getElementById('radio0').checked = true;
                payment0.disabled = false;
                payment1.disabled = true;
                amount.style.display = 'inline';
                amount1.style.display = 'none';
                payment1.value=" ";
            } else if (payment1.value !== "") {
                document.getElementById('radio1').checked = true;
                payment0.disabled = true;
                payment1.disabled = false;
                amount.style.display = 'none';
                amount1.style.display = 'inline';
                payment0.value=" ";
            }
            payment();
        });
        function payment() {
            var payment0 = document.getElementById('Payment0');
            var payment1 = document.getElementById('Payment1');
            if (payment0.value !== "") {
            var Payment0 =  parseFloat(document.getElementById('Payment0').value);
            var Nettotal = parseFloat(document.getElementById('Nettotal').value);
            let Subtotal =0;
            let total =0;
            let addtax = 0;
            let before = 0;
            let balance =0;
            Subtotal = (Nettotal*Payment0)/100;
            total = Subtotal/1.07;
            addtax = Subtotal-total;
            before = Subtotal-addtax;
            balance = Nettotal-Subtotal;
            $('#Subtotal').text(isNaN(Subtotal) ? '0' : Subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#SubtotalAll').text(isNaN(Subtotal) ? '0' : Subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#Added').text(isNaN(addtax) ? '0' : addtax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#Before').text(isNaN(before) ? '0' : before.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#Total').text(isNaN(Subtotal) ? '0' : Subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#balance').val(balance);
            $('#sum').val(Subtotal);
            } else if (payment1.value !== "") {
            var Payment1 =  parseFloat(document.getElementById('Payment1').value);
            var Nettotal = parseFloat(document.getElementById('Nettotal').value);
            let Subtotal =0;
            let total =0;
            let addtax = 0;
            let before = 0;
            let balance =0;
            Subtotal = Payment1;
            total = Subtotal;
            addtax = 0;
            before = Subtotal;
            balance = Nettotal-Subtotal;
            console.log(balance);
            $('#Subtotal').text(isNaN(Subtotal) ? '0' : Subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#SubtotalAll').text(isNaN(Subtotal) ? '0' : Subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#Added').text(isNaN(addtax) ? '0' : addtax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#Before').text(isNaN(before) ? '0' : before.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#Total').text(isNaN(Subtotal) ? '0' : Subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            $('#balance').val(balance);
            $('#sum').val(Subtotal);
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
