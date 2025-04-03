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
    .bg-together {
        border-radius: 10px;
        padding: 0.5rem 1rem;
        color: white;
        background-color: rgb(73, 184, 179);
        background-image: linear-gradient(
            to right,
            rgba(12, 67, 67, 0.862),
            rgba(8, 97, 92, 0.685)
        );
        box-shadow: inset 2px 2px 3px rgba(255, 255, 255, 0.6),
            inset -2px -2px 3px rgba(0, 0, 0, 0.6);
    }

    .wrap-fieldSet-create {
        margin-top: 1em;
        border-radius: 10px;
        box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px,
            rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
        padding: 10px;
        display: grid;
        gap: 0.5em;
        background: linear-gradient(to bottom, #2d6a67, #0f3b46);
    }

    .wrap-fieldSet-create .fieldset {
        flex-grow: 1;
        background: #f1f7f6;
        border-radius: 7px;
        max-width: 100%;
    }

    .wrap-fieldSet-create .info-list {
        list-style: none;
        padding: 0.5em;
        display: flex;
        flex-wrap: wrap;

        gap: 0.3em;
        margin-bottom: 0px;
        height: 100%;
    }

    .wrap-fieldSet-create .info-list > section {
        border-radius: 7px;
        flex-grow: 1;
    }

    .wrap-fieldSet-create .info-list > section.bd {
        border: 1px solid rgba(144, 214, 175, 0.375);
        padding: 5px;
    }

    .wrap-fieldSet-create .info-list li {
        font-size: 0.9rem;
        flex: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 3px;
        width: 100%;
        height: 45px;
    }

    .wrap-fieldSet-create .info-list li input,
    .wrap-fieldSet-create .info-list li select {
        height: 2em;
    }

    .wrap-fieldSet-create .info-list.neww li {
        display: grid;
        grid-template-columns: 115px 1fr;
        /* border-bottom: 1px solid rgb(231, 235, 225); */
        max-width: 100%;
    }

    .wrap-fieldSet-create .info-label,
    .wrap-fieldSet-create .form-label {
        font-weight: 550;
        color: #2e2a2a;
    }
    .wrap-fieldSet-create .form-select,
    .wrap-fieldSet-create .form-control {
        padding: 2px 5px;
        font-size: 0.9rem;
    }

    .wrap-fieldSet-create .info-label::after {
        content: ":";
        margin: 2px 0.3em;
    }

    .wrap-fieldSet-create .info-label.no-semi::after {
        content: "";
    }

    .wrap-fieldSet-create .top-doc-saleman {
        display: grid;
        grid-template-columns: 7.5fr 3fr;
        gap: 0.3em;
        /* padding: 0.5em; */
        /* background: linear-gradient(to bottom, white, white); */
        /* background: linear-gradient(to bottom, #bac8c7, #e3ecee); */
        /* background: linear-gradient(to bottom, #e7f0ef77, #e1e2e233); */

        border-radius: 7px;
    }

    @media (max-width: 1400px) {
        .wrap-fieldSet-create .top-doc-saleman {
            grid-template-columns: 1fr;
        }
    }

    .wrap-fieldSet-create .top-doc-saleman > div {
        background-color: rgb(2, 41, 39);
        background-image: linear-gradient(
            to top,
            rgba(12, 67, 67, 0.862),
            rgba(8, 97, 92, 0.685)
        );
        border-radius: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgb(240, 247, 245);
        padding: 0.5em 0.5em;
        font-weight: 500;
    }

    @media (max-width: 600px) {
    .wrap-fieldSet-create .top-doc-saleman > div {
        flex-grow: 1;
    }
    }

    .wrap-fieldSet-create .top-doc-saleman > div:nth-child(1) {
    flex-grow: 1;
    }
    @media (max-width: 800px) {
    .sm-width-100 {
        width: 100%;
    }
    }

    .grid-sm-flex {
        display: grid;
        grid-template-columns: 3.5fr 4.7fr;
        gap: 0.3em;
    }
    @media (max-width: 1200px) {
    .grid-sm-flex {
        display: flex;
        flex-wrap: wrap;
    }
    }

    @media (min-width: 1400px) {
    .wrap-dis {
        display: grid;
        grid-template-columns: 3fr 5fr 2fr;
        width: 100%;
    }
    }

    .wrap-dis {
        display: grid;
        grid-template-columns: 5fr 2fr;
        gap: 0.3em;
        padding: 0.5em;
        border-top-left-radius: 7px;
        border-top-right-radius: 7px;
    }

    .wrap-dis li {
    /* border: red 1px solid; */
        display: flex;
    }

    @media (max-width: 1000px) {
    .wrap-dis {
        display: flex;
        justify-content: end;
        flex-wrap: wrap;
    }

    .wrap-dis > div:nth-child(1) {
        min-width: 350px;
    }

    .wrap-dis > div:nth-child(1) > div {
        min-width: 340px;
        flex-grow: 1;
    }
    }
    .bg-disable-grey {
        background-color: rgb(237, 237, 237);
        pointer-events: none;
    }
    img.rounded-avatar {
        border-radius: 50%;
        width: 30px;
        height: 30px;
        object-fit: cover;
        margin-right: 0.4em;
    }
</style>
@section('content')
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">View Deposit Invoice</div>
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
        <div class="wrap-fieldSet-create">
            <div class="top-doc-saleman">
                <div class="d-flex justify-content-around flex-wrap gap-1">
                    <div>Document No : {{$DepositID}}</div>
                    <div>Reference No : {{$QuotationID}} </div>
                </div>
                <div> User Account :
                    @if ($user->firstname)
                        <span> {{$user->firstname}} {{$user->lastname}}</span>
                    @endif
                </div>
            </div>
            <div class="wrap-dis p-0">
                <div class="grid-sm-flex">
                    <fieldset class="fieldset">
                        <div class="info-list neww flex-column">
                            <li>
                                <label for="customerNameInput" class="form-label text-nowrap m-0 rrr">Customer Name</label>
                                <div class="d-flex align-items-center">
                                <input type="text" class="form-control" id="customerNameInput" placeholder="Select Customer" value="{{$name}}" readonly style="flex-grow: 1;" />
                                <!-- Search button -->
                                </div>
                            </li>
                            <li>
                                <span class="info-label">Additional Company</span>
                                <select name="Guest" id="Guest" class="select2 " onchange="data()" disabled>
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
                            </li>
                            <li>
                                <label for="customerName" class="form-label text-nowrap m-0">Description</label>
                                <select class="form-select bg-disable-grey" id="customerName" disabled>
                                <option value="y" style="font-size: 0.9em">Deposit Invoice</option>
                                </select>
                            </li>
                            <li>
                                <label class="form-label">VAT Type</label>
                                <select name="Mvat" id="Mvat" class="select2 bg-disable-grey" disabled >
                                    @foreach($Mvat as $item)
                                        <option value="{{ $item->id }}"{{$vat_type_num == $item->id ? 'selected' : ''}} >{{ $item->name_th }} </option>
                                    @endforeach
                                </select>
                            </li>
                            <li class="border-top">
                                <label for="customerName" class="form-label text-nowrap m-0">Total Amount</label>
                                <input class="form-control bg-disable-grey" type="text" value="{{ number_format($Nettotal, 2) }}" readonly/>
                                <input class="form-control bg-disable-grey" type="hidden" id="amountPD" value="{{$Nettotal}}" readonly/>
                                <input class="form-control bg-disable-grey" type="hidden" id="depositam" value="{{$amdeposit}}" readonly/>
                            </li>
                            <li>
                                <label for="customerName" class="form-label text-nowrap m-0">Payment</label>
                                <div class="d-flex gap-2">
                                    <div class="discount-container" style="max-width:160px">
                                        <input type="text" class="form-control" id="discount" value="{{$Payment}}" disabled>
                                    </div>
                                    <div class="discount-container flex-grow-1" style="min-width:75px">
                                        <select id="discountType"  class="form-select" disabled>
                                            <option value="percent">%</option>
                                            <option value="amount">THB</option>
                                        </select>
                                    </div>
                                </div>
                            </li>
                            <li class="border-top">
                                <label for="customerName" class="form-label text-nowrap m-0">Deposit Amount</label>
                                <input class="form-control bg-disable-grey" type="text" name="totaldeposit" id="totaldeposit"   value="{{ number_format($Payment, 2) }}" readonly/>
                            </li>
                        </div>
                    </fieldset>
                    <fieldset class="fieldset">
                        <ul class="info-list">
                            <section class="bd">
                                <div class="flex-grow-1">
                                <li>
                                    <span class="info-label">Customer ID</span>
                                    <span class="info-value" id="nameID">{{$Company_ID}}</span>
                                </li>
                                <li>
                                    <span class="info-label">Customer Name</span>
                                    <span class="info-value" id="name">{{$fullName}}</span>
                                </li>
                                <li>
                                    <span class="info-label">Address</span>
                                    <span class="info-value" id="Address">{{$address}}</span>
                                </li>
                                <li>
                                    <span class="info-label">Telephone</span>
                                    <span class="info-value" id="Number">{{$phone->Phone_number}}</span>
                                </li>
                                <li>
                                    <span class="info-label">Tax ID</span>
                                    <span class="info-value" id="Taxpayer">{{$Identification}}</span>
                                </li>
                                </div>
                            </section>
                        </ul>
                    </fieldset>
                </div>
                <fieldset class="fieldset ">
                    <ul class="info-list">
                        <section class="bd">
                            <div class="flex-grow-1">
                                <li>
                                    <span class="info-label">Issue Date</span>
                                    <span class="info-value" id="reportrange1"><input type="text" id="datestart" class="form-control " name="IssueDate" style="text-align: left;" value="{{ $Issue_date }}"disabled></span>
                                </li>
                                <li>
                                    <span class="info-label">Expire Date</span>
                                    <span class="info-value"> <input type="text" id="dateex" class="form-control " name="Expiration" style="text-align: left;"  value="{{ $ExpirationDate }}"disabled></span>
                                </li>
                                <li>
                                    <span class="info-label">Payment Terms</span>
                                    <span class="info-value" id="Terms"></span>
                                </li>
                            </div>
                        </section>
                    </ul>
                </fieldset>
            </div>
        </div>
        <div class="row clearfix mt-5">
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
                                            <b class="titleQuotation" style="font-size: 20px;color:rgb(255, 255, 255);">Deposit Invoice</b>
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
                                                    <span>Proposal ID :</span>
                                                </div>
                                                <div class="col-lg-6 col-md-12 col-sm-12">
                                                    <span>{{$QuotationID}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12 col-sm-12 mt-2">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-12 col-sm-12"style="display:flex; justify-content:right; align-items:center;">
                                                    <span>Issue Date :</span>
                                                </div>
                                                <div class="col-lg-6 col-md-12 col-sm-12">
                                                    <span id="datestarttext"style="text-align: left;"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12 col-sm-12 mt-2">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-12 col-sm-12"style="display:flex; justify-content:right; align-items:center;">
                                                    <span>Expiration Date :</span>
                                                </div>
                                                <div class="col-lg-6 col-md-12 col-sm-12">
                                                    <span id="dateextext"   style="text-align: left;"></span>
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
                                    <li class="mt-3">
                                        <b>Guest Name</b>
                                        <span id="namea4">{{$fullName}}</span>
                                    </li>
                                    <li>
                                        <b>Address</b>
                                        <span id="Addressa4">{{$address}} </span>
                                        <b></b>
                                    </li>
                                    <li>
                                        <b>Email</b>
                                        <span id="Emaila4">{{$Email}}</span>
                                    </li>
                                    <li>
                                        <b>Tax ID/Gst Pass</b>
                                        <span id="Taxpayera4" >{{$Identification}}</span>
                                    </li>
                                    <li>
                                        <b>Phone Number</b>
                                        <span id="Numbera4" >{{$phone->Phone_number}}</span>
                                    </li>
                                    <li> </li>
                                    </ul>
                                    <ul>
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
                                    <li></li>
                                    </ul>
                                </div>

                        </div>
                        <div class="styled-hr"></div>
                        <div class="row mt-4">
                            <table class="table-revenueEditBill" style="width:100%">
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
                                        <td style="text-align:right"><span id="Subtotal"></span>  THB </td>
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
                            </div>
                            <div class="col-4"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

        var cashamount = parseFloat(document.getElementById('discount').value);
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
        let Payment = {{ $Payment }}; // ดึงค่าตัวแปรจาก PHP
        let discountType = document.getElementById("discountType");

        if (Payment > 100) {
            discountType.value = "amount"; // เลือก THB
        } else {
            discountType.value = "percent"; // เลือก %
        }
    });
    $(function() {
        var start = moment();


        var end = moment().add(7, 'days');
        function cb(start, end) {
            $('#datestart').val(start.format('DD/MM/Y'));
            $('#dateex').val(end.format('DD/MM/Y'));
            $('#issue_date_document').text(start.format('DD/MM/Y'));
            $('#issue_date_document1').text(start.format('DD/MM/Y'));

            var daysSelected = end.diff(start, 'days');
            $('#Terms').text(daysSelected+' วัน');

            $('#datestarttext').text(start.format('DD/MM/Y'));
            $('#dateextext').text(end.format('DD/MM/Y'));
        }
        console.log(1);
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
    function validateInput(input) {
        input.value = input.value.replace(/[^0-9]/g, '');
        var discount = $('#discountType').val();
        var amountPD = $('#amountPD').val();
        let amount = 0;
        if (discount == 'amount') {
            if (parseFloat(input.value) > amountPD) {
                input.value = amountPD; // ถ้าค่าที่กรอกมากกว่า Nettotal ให้ใช้ Nettotal แทน
            }
            amount =  parseFloat(input.value);
        }else{
            if (parseFloat(input.value) >= 100 ) {
                input.value = 100;
            }
            amount = (amountPD*input.value)/100;
        }
        $('#totaldeposit').val(amount)
        var vat_type = parseFloat(document.getElementById('vat_type').value);
        let Subtotal =0;
        let total =0;
        let addtax = 0;
        let before = 0;
        let balance =0;
        if (vat_type == 51) {
            Subtotal =  amount;
            total = Subtotal;
            addtax = 0;
            before = Subtotal;

        }else{
            Subtotal =  amount;
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
    }
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
                $('#nameID').text(id);
                $('#namea4').text(fullname);
                $('#Addressa4').text(Address);
                $('#Emaila4').text(email);
                $('#Taxpayera4').text(TaxpayerIdentification);
                $('#Numbera4').text(phone);

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
                    // If user confirms, redirect to the correct route
                    window.location.href = "{!! route('BillingFolio.CheckPI', $ids) !!}";
                }
            });
        }

    </script>
@endsection
