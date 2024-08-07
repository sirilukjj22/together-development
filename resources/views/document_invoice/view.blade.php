@extends('layouts.masterLayout')
@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to View Proforma Invoice.</small>
                <h1 class="h4 mt-1">View Proforma Invoice</h1>
            </div>
        </div>
    </div>
@endsection
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
</style>
@section('content')
<div class="container" style="font-family: Arial, sans-serif;">
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
                                <b class="titleQuotation" style="font-size: 24px;color:rgba(45, 127, 123, 1);">Profoma Invoice</b>
                                <span class="titleQuotation">Profoma Invoice ID : {{$InvoiceID}}</span>
                                <div  style="background: #fff; cursor: pointer; padding: 5px 10px; width: 100%;" >
                                    <div class="col-12 col-md-12 col-sm-12">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-12 col-sm-12"style="display:flex; justify-content:right; align-items:center;">
                                                <span>Issue Date:</span>
                                            </div>
                                            <div class="col-lg-4 col-md-12 col-sm-12" id="reportrange1">
                                                <input type="text" id="datestart" class="form-control" name="IssueDate" style="text-align: left;" value="{{$IssueDate}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-12 col-sm-12 mt-2">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-12 col-sm-12"style="display:flex; justify-content:right; align-items:center;">
                                                <span>Expiration Date:</span>
                                            </div>
                                            <div class="col-lg-4 col-md-12 col-sm-12">
                                                <input type="text" id="dateex" class="form-control" name="Expiration" style="text-align: left;"value="{{$Expiration}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-7 col-md-12 col-sm-12 mt-3" style=" border-right-style: solid  ; border-right-width: 2px;border-right-color:#109699">
                            <b class="com"><span style="font-size: 18px">Customer Information</span></b>
                            <table>
                                <tr>
                                    <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Company Name :</b></td>
                                    <td>
                                        <span>{{$comtypefullname}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Company Address :</b></td>
                                    <td><span>{{$Company->Address}} {{'ตำบล' . $TambonID->name_th}} {{'อำเภอ' .$amphuresID->name_th}}</span></td>

                                </tr>
                                <tr>
                                    <td></td>
                                    <td><span id="Address2"> {{'จังหวัด' .$provinceNames->name_th}} {{$TambonID->Zip_Code}}</span></td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Company Number :</b></td>
                                    <td>
                                        <span id="Company_Number">{{ substr($company_phone->Phone_number, 0, 3) }}-{{ substr($company_phone->Phone_number, 3, 3) }}-{{ substr($company_phone->Phone_number, 6) }}</span>
                                        <b style="margin-left: 10px;color:#000;">Company Fax : </b  ><span id="Company_Fax">
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
                            <div>
                                <b class="com my-2" style="font-size: 18px">Personal Information</b>
                            </div>
                            <table>
                                <tr>
                                    <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Contact Name :</b></td>
                                    <td>
                                        <span id="Contact_name" name="Contact_name" >{{$Contact_name->First_name}} {{$Contact_name->Last_name}}</span>
                                        <b style="margin-left: 10px;color:#000;">Contact Email :</b  ><span id="Company_Fax"> {{$Contact_name->Email}}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Contact Number :</b></td>
                                    <td>
                                        <span id="Company_Number">{{ substr($Contact_phone->Phone_number, 0, 3) }}-{{ substr($Contact_phone->Phone_number, 3, 3) }}-{{ substr($Contact_phone->Phone_number, 6) }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div><br><br><br><br></div>
                            <div class="col-12 row" >
                                <table>
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
                                            <span id="Company_Number">
                                                @if ($day == null)
                                                    -
                                                @else
                                                    {{$day}} วัน {{$night}} คืน
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Number of Guests :</b></td>
                                        <td>
                                            <span id="Company_Number">
                                                @if ($adult == null)
                                                    -
                                                @else
                                                    {{$adult}} Adult , {{$children}} Children
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px"><b style="margin-left: 2px;color:#000;">Valid :</b></td>
                                        <td>
                                            <input type="date" class="form-control" name="valid" id="valid" value="{{$valid}}" disabled>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="styled-hr"></div>
                    <input type="hidden" name="eventformat" id="eventformat" value="{{$Quotation->eventformat}}">
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
                                    <td style="text-align:left">Proposal ID : {{$QuotationID}}
                                    @if ($payment)
                                        {{ number_format((float) $payment, 2, '.', ',') }}
                                    @else
                                        {{$paymentPercent}}%
                                    @endif
                                    of {{$Nettotal}} THB กรุณาชำระมัดจำ งวดที่ <input type="text" name="Deposit"  style="width: 2%"  id="Deposit" value="{{$Deposit}}" disabled></td>
                                    <td style="text-align:right"><span id="Subtotal">{{ number_format($Subtotal, 2) }}</span>฿ <input type="hidden" name="Nettotal" id="Nettotal" value="{{$Nettotal}}"></td>
                                </tr>
                                <tr>
                                    <td><br></td>
                                    <td style="text-align:right">Subtotal :</td>
                                    <td style="text-align:right"><span id="SubtotalAll">{{ number_format($Subtotal, 2) }}</span>฿</td>
                                </tr>
                                <tr>
                                    <td><br></td>
                                    <td style="text-align:right">Price Before Tax :</td>
                                    <td style="text-align:right"><span id="Before">{{ number_format($before, 2) }}</span>฿</td>
                                </tr>
                                <tr>
                                    <td><br></td>
                                    <td style="text-align:right">Value Added Tax :</td>
                                    <td style="text-align:right"><span id="Added">{{ number_format($addtax, 2) }}</span>฿</td>
                                </tr>
                                <tr>
                                    <td><br></td>
                                    <td style="text-align:right">Net Total :</td>
                                    <td style="text-align:right"><span id="Total">{{ $formattedNumber }}</span>฿</td>
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
                        <div class="col-4">
                            <input type="hidden" name="InvoiceID"id="InvoiceID" value="{{$InvoiceID}}">
                            <input type="hidden" name="QuotationID" id="QuotationID" value="{{$QuotationID}}">
                            <input type="hidden" name="company"  id="company" value="{{$CompanyID}}">
                            <input type="hidden" name="balance"  id="balance">
                            <input type="hidden" name="Deposit"  id="Deposit" value="{{$Deposit}}">
                        </div>
                        <div class="col-4 "  style="display:flex; justify-content:center; align-items:center;">
                            <button type="button" class="btn btn-secondary lift btn_modal btn-space" onclick="window.location.href='{{ route('invoice.index') }}'">
                                Back
                            </button>
                        </div>
                        <div class="col-4"></div>
                    </div>
                </form>
                <input type="hidden" name="preview" value="1" id="preview">
            </div>
        </div>
    </div>
</div>
@endsection
