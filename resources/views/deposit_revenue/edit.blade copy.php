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
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Edit Deposit Revenue</div>
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
                    <h4 class="alert-heading">Save successful.</h4>
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
        <form id="myForm" action="{{url('/Deposit/update/'.$deposit->id)}}" method="POST">
            @csrf
            <div class="container-xl">
                <div class="row clearfix">
                    <div class="col-sm-12 col-12">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="col-sm-12 col-ml-12 col-12">
                                    <div class="row">
                                        <div class="col-sm-8 col-ml-12 col-12">
                                            <label for="" class="star-red">Guest Name</label>
                                            <select name="Guest" id="Guest" class="select2" onchange="data()" required>
                                                <option value="{{$name_ID}}">{{$name}}</option>
                                                @foreach($datasub as $item)
                                                    @if ($type == 'Company')
                                                        <option value="{{ $item->ComTax_ID }}" {{$company == $item->ComTax_ID ? 'selected' : ''}}>
                                                            @php
                                                                $comtype = DB::table('master_documents')
                                                                    ->where('id', $item->Company_type)
                                                                    ->first();

                                                                if ($comtype) {
                                                                    if ($comtype->name_th == "บริษัทจำกัด") {
                                                                        $name = "บริษัท " . $item->Companny_name . " จำกัด";
                                                                    } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                                                                        $name = "บริษัท " . $item->Companny_name . " จำกัด (มหาชน)";
                                                                    } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                                                                        $name = "ห้างหุ้นส่วนจำกัด " . $item->Companny_name;
                                                                    } else {
                                                                        $name = $comtype->name_th . ($item->Companny_name ?? ( $item->first_name . " " . $item->last_name));
                                                                    }
                                                                }
                                                            @endphp
                                                            {{ $name }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $item->GuestTax_ID }}"{{$company == $item->GuestTax_ID ? 'selected' : ''}}>
                                                            @php
                                                                $comtype = DB::table('master_documents')
                                                                    ->where('id', $item->Company_type)
                                                                    ->first();

                                                                if ($comtype) {
                                                                    if ($comtype->name_th == "บริษัทจำกัด") {
                                                                        $name = "บริษัท " . $item->Company_name . " จำกัด";
                                                                    } elseif ($comtype->name_th == "บริษัทมหาชนจำกัด") {
                                                                        $name = "บริษัท " . $item->Company_name . " จำกัด (มหาชน)";
                                                                    } elseif ($comtype->name_th == "ห้างหุ้นส่วนจำกัด") {
                                                                        $name = "ห้างหุ้นส่วนจำกัด " . $item->Company_name;
                                                                    } else {
                                                                        $name = $comtype->name_th . ($item->Company_name ?? ( $item->first_name . " " . $item->last_name));
                                                                    }
                                                                }
                                                            @endphp
                                                            {{ $name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-4 col-ml-12 col-12">
                                            <label for="cashAmount">Amount</label>
                                            <input type="text" id="Amount" name="cashAmount" class="cashAmount form-control" placeholder="Enter cash amount"oninput="this.value = this.value.replace(/[^0-9]/g, '')"  value="{{$Payment}}">
                                        </div>
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
                                                    <b class="titleQuotation" style="font-size: 20px;color:rgb(255, 255, 255);">Invoice / Deposit</b>
                                                    <b  class="titleQuotation" style="font-size: 16px;color:rgb(255, 255, 255);">{{$DepositID}}</b>
                                                </div>
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
                                                            <input type="text" id="datestart" class="form-control readonly-input" name="IssueDate" style="text-align: left;" value="{{ $Issue_date }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-12 col-sm-12 mt-2">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-12 col-sm-12"style="display:flex; justify-content:right; align-items:center;">
                                                            <span>Expiration Date:</span>
                                                        </div>
                                                        <div class="col-lg-6 col-md-12 col-sm-12">
                                                            <input type="text" id="dateex" class="form-control readonly-input" name="Expiration" style="text-align: left;" value="{{ $ExpirationDate }}"readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">

                                        <div class="proposal-cutomer-detail" id="companyTable" >
                                            <ul>
                                            <b class="font-upper com">Invoice / Deposit</b>
                                            <li class="mt-3">
                                                <b>Guest Name</b>
                                                <span id="name">{{$fullName}}</span>
                                            </li>
                                            <li>
                                                <b>Address</b>
                                                <span id="Address">{{$address}} </span>
                                                <b></b>
                                            </li>
                                            <li>
                                                <b>Email</b>
                                                <span id="Email">{{$Email}}</span>
                                            </li>
                                            <li>
                                                <b>Tax ID/Gst Pass</b>
                                                <span id="Taxpayer" >{{$Identification}}</span>
                                            </li>
                                            <li>
                                                <b>Phone Number</b>
                                                <span id="Number" >{{$phone->Phone_number}}</span>
                                            </li>
                                            <li> </li>
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
                                                    กรุณาชำระเงินเงินมัดจำ อ้างอิงเอกสาร : {{$QuotationID}} </span> ครั้งที่ {{$Deposit}}
                                                </td>
                                                <td style="text-align:right"><span id="Subtotal"></span>   THB </td>
                                            </tr>
                                            <tr>
                                                <td><br></td>
                                                <td style="text-align:right">Subtotal :</td>
                                                <td style="text-align:right"><span id="SubtotalAll"></span> THB</td>
                                            </tr>
                                            <tr>
                                                <td><br></td>
                                                <td style="text-align:right">Price Before Tax :</td>
                                                <td style="text-align:right"><span id="Before"></span>THB</td>
                                            </tr>
                                            <tr>
                                                <td><br></td>
                                                <td style="text-align:right">Value Added Tax :</td>
                                                <td style="text-align:right"><span id="Added"></span> THB</td>
                                            </tr>
                                            <tr>
                                                <td><br></td>
                                                <td style="text-align:right">Net Total :</td>
                                                <td style="text-align:right"><span id="Total"></span>  THB</td>
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

                                        <input type="hidden" id="Deposit" name="Deposit" value="{{$Deposit}}">
                                        <input type="hidden" class="form-control" id="idfirst" value="{{$name_ID}}" />
                                        <input type="hidden" name="QuotationID" id="QuotationID" value="{{$QuotationID}}">
                                        <input type="hidden" name="sum"  id="sum">
                                        <input type="hidden" id="total" name="total" >
                                        <input type="hidden" class="form-control" id="fullname" name="fullname" value="{{$fullName}}" />
                                        <input type="hidden" class="form-control" id="nameid" name="nameid" value="{{$name_ID}}"/>
                                        <input type="hidden" id="DepositID" name="DepositID" value="{{$DepositID}}">
                                    </div>
                                    <div class="col-4 "  style="display:flex; justify-content:center; align-items:center;">
                                        <button type="button" class="btn btn-secondary lift btn_modal btn-space" onclick="BACKtoEdit()">
                                            Back
                                        </button>

                                        <button type="button" class="btn btn-color-green lift btn_modal"  onclick="submitsave(event)">Save</button>
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
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Please select an option"
        });
        $('.select2Com').select2({
            placeholder: "Please select an option"
        });

        var cashamount = parseFloat(document.getElementById('Amount').value);
        var vat_type = parseFloat(document.getElementById('vat_type').value);
        let Subtotal =0;
        let total =0;
        let addtax = 0;
        let before = 0;
        let balance =0;
        if (vat_type == 51) {
            Subtotal =  cashamount;
            total = Subtotal;
            addtax = 0;
            before = Subtotal;

        }else{
            Subtotal =  cashamount;
            total = Subtotal/1.07;
            addtax = Subtotal-total;
            before = Subtotal-addtax;

        }

        $('#Subtotal').text(isNaN(Subtotal) ? '0' : Subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        $('#SubtotalAll').text(isNaN(Subtotal) ? '0' : Subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        $('#Added').text(isNaN(addtax) ? '0' : addtax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        $('#Before').text(isNaN(before) ? '0' : before.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        $('#Total').text(isNaN(Subtotal) ? '0' : Subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));

        $('#sum').val(Subtotal);
    });
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
    function data() {
        var idcheck = $('#Guest').val();
        var nameID = document.getElementById('idfirst').value;
        var companyTable = document.getElementById('companyTable');
        var guestTable = document.getElementById('guestTable');
        if (idcheck) {
            id = idcheck;
        }else{
            id = nameID;
        }
        jQuery.ajax({
            type: "GET",
            url: "{!! url('/Document/deposit_revenue/Data/" + id + "') !!}",
            datatype: "JSON",
            async: false,
            success: function(response) {
                var phone = response.phone.Phone_number;
                var Selectdata = response.Selectdata;
                var fullname = response.fullname;
                var email = response.email;
                var Address = response.Address + ' '+ 'ตำบล'+ response.Tambon.name_th + ' '+'อำเภอ'+response.amphures.name_th + ' ' + 'จังหวัด'+ response.province.name_th + ' ' + response.Tambon.Zip_Code;
                var TaxpayerIdentification = response.Identification;
                console.log(Selectdata);
                $('#fullname').val(fullname);
                $('#name').text(fullname);
                $('#Address').text(Address);
                $('#Email').text(email);
                $('#Taxpayer').text(TaxpayerIdentification);
                $('#Number').text(phone);

                $('#nameid').val(id);
                if (Selectdata == 'Company') {
                    companyTable.style.display = 'flex';
                    guestTable.style.display = 'none';
                }else{
                    companyTable.style.display = 'none';
                    guestTable.style.display = 'flex';
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed: ", status, error);
            }
        });
    }
    $(document).on('keyup', '#Amount', function() {
        var cashamount =  Number($(this).val());
        var vat_type = parseFloat(document.getElementById('vat_type').value);
        let Subtotal =0;
        let total =0;
        let addtax = 0;
        let before = 0;
        let balance =0;
        if (vat_type == 51) {
            Subtotal =  cashamount;
            total = Subtotal;
            addtax = 0;
            before = Subtotal;

        }else{
            Subtotal =  cashamount;
            total = Subtotal/1.07;
            addtax = Subtotal-total;
            before = Subtotal-addtax;

        }

        $('#Subtotal').text(isNaN(Subtotal) ? '0' : Subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        $('#SubtotalAll').text(isNaN(Subtotal) ? '0' : Subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        $('#Added').text(isNaN(addtax) ? '0' : addtax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        $('#Before').text(isNaN(before) ? '0' : before.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        $('#Total').text(isNaN(Subtotal) ? '0' : Subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));

        $('#sum').val(Subtotal);
    });
    function BACKtoEdit(){
        event.preventDefault();
        Swal.fire({
            title: "Do you want to go back?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "Cancel",
            confirmButtonColor: "#2C7F7A",
            dangerMode: true
        }).then((result) => {
            if (result.isConfirmed) {
                console.log(1);
                // If user confirms, submit the form
                window.location.href = "{{ route('Deposit.index') }}";
            }
        });
    }
    function submitsave(event) {
        event.preventDefault();
        Swal.fire({
            title: "You want to save information, right?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "Cancel",
            confirmButtonColor: "#2C7F7A",
            dangerMode: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("myForm").submit();
            }
        });
    }
    </script>
@endsection
