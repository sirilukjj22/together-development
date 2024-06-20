@extends('layouts.test')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<style>


    input[type=text],
    select {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input[type=tel],
    select {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input[type=tel1],
    select {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input[type="date"] {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        outline: none;
        font-size: 16px;
        background-color: #f8f8f8;
        /* เพิ่มสีพื้นหลัง */
    }

    input[type="number"] {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .button-guest button {
        background-color: #2D7F7B;
        color: white;
        border-color: #9a9a9a;
        border-style: solid;
        width: 50%;
        border-width: 1px;
        border-radius: 8px;
        margin-Top: 10px;
        text-align: center;

    }

    .button-g {
        background-color: #2D7F7B;
        color: whitesmoke;
        border-color: #9a9a9a;
        border-style: solid;
        width: 30%;
        border-width: 1px;
        border-radius: 8px;
        margin-Top: 10px;
        margin-Left: 1px;
        text-align: center;

    }

    .button-guest-end button {
        background-color: #dc3545;
        color: white;
        border-color: #9a9a9a;
        border-style: solid;
        width: 50%;
        border-width: 1px;
        border-radius: 8px;
        margin-Top: 10px;
        text-align: center;
        float: right;

    }

    .textarea {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        resize: none;
    }

    .add-phone {
        /* เพิ่มสไตล์ที่คุณต้องการในส่วนนี้ */
        color: #fff;
        background-color: #28a745;
        border-color: #28a745;
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1;
        cursor: pointer;
    }

    .add-phone:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    .add-phone:disabled {
        background-color: #6c757d;
        border-color: #6c757d;
        cursor: not-allowed;
    }

    .add-input {
        /* เพิ่มสไตล์ที่คุณต้องการในส่วนนี้ */
        color: #fff;
        width: auto;
        background-color: #28a745;
        border-color: #28a745;
        border-radius: 16px;
        cursor: pointer;
        float: right;
        padding: 5px 10px;
    }

    .phone-group ,.fax-group{
        width: 100%;
        height: 50px;
        margin-bottom: 10px;
    }

    .add-input:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    .add-input:disabled {
        background-color: #6c757d;
        border-color: #6c757d;
        cursor: not-allowed;
    }
    .addphone {
        /* เพิ่มสไตล์ที่คุณต้องการในส่วนนี้ */
        color: #fff;
        width: auto;
        background-color: #28a745;
        border-color: #28a745;
        border-radius: 16px;
        cursor: pointer;
        float: right;
        padding: 5px 10px;
    }
    .addphone:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    .addphone:disabled {
        background-color: #6c757d;
        border-color: #6c757d;
        cursor: not-allowed;
    }
    /* สไตล์สำหรับปุ่ม "Add Fax" */
    .add-fax {
        /* เพิ่มสไตล์ที่คุณต้องการในส่วนนี้ */
        color: #fff;
        background-color: #28a745;
        border-color: #28a745;
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        cursor: pointer;
    }

    .add-fax:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    .add-fax:disabled {
        background-color: #6c757d;
        border-color: #6c757d;
        cursor: not-allowed;
    }

    /* สไตล์เพิ่มเติมตามที่ต้องการ */
    .input-group {
        margin-bottom: 2%;
    }
    .inputgroup {
        margin-bottom: 2%;
    }
    .row label {
        margin: 0;
        margin-bottom: 5px;
    }

    .row input {
        margin: 0;
        margin-bottom: 2%;
    }

    .row select {
        margin: 0;
        margin-bottom: 2%;
    }

    .row .select2-selection {
        margin: 0 !important;
        margin-bottom: 2% !important;
    }

    .row .select2-selection .select2-selection__arrow {
        margin: 10px !important;
    }

    .remove-input,
    .remove-fax,
    .remove-phone,
    .remove-phoneCon{
        /* เพิ่มสไตล์ที่คุณต้องการในส่วนนี้ */
        color: #fff;
        background-color: #dc3545;
        /* สีแดง */
        border-color: #dc3545;
        /* สีเหลือง */
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        cursor: not-allowed;
    }

    .custom-accordion {
        border: 1px solid #ccc;
        margin-bottom: 20px;
        border-radius: 5px;
        /* เพิ่มขอบมนเข้าไป */
        overflow: hidden;
        /* ทำให้มีการคอยรับเส้นขอบ */
    }

    .custom-accordion input[type="checkbox"] {
        display: none;
    }

    .custom-accordion label {
        font-size: 18px;
        background-color: #f0f0f0;
        display: block;
        cursor: pointer;
        padding: 15px 20px;
    }

    .labelcontact {
        all: unset !important;
    }

    .labelcontact::before {
        all: unset !important;
    }

    .custom-accordion label::before {
        content: "\2610";
        /* Unicode for empty checkbox */
        margin-right: 10px;
        font-size: 24px;
    }

    .custom-accordion input[type="checkbox"]:checked+label::before {
        content: "\2611";
        /* Unicode for checked checkbox */
    }

    .custom-accordion-content {
        font-size: 16px;
        padding: 5% 10%;
        display: none;
        border-top: 1px solid #ccc;
        /* เพิ่มขอบด้านบน */
    }

    .custom-accordion input[type="checkbox"]:checked+label+.custom-accordion-content {
        display: block;
    }

    .select2-container {
        width: 100% !important;
    }

    .flex-container {
        display: flex;
        flex-direction: column;
    }

    .input-container {
        position: relative;
        width: 100%;
    }
    .inputcontainer{
        position: relative;
        width: 100%;
    }
    .inputcontainer .form-control {
        width: 100%;
        padding-right: 50px;
        margin: 0;
        /* Adjust based on button width */
        box-sizing: border-box;
    }
    .input-container .form-control {
        width: 100%;
        padding-right: 50px;
        margin: 0;
        /* Adjust based on button width */
        box-sizing: border-box;
    }
    .remove-phoneCon{
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        margin: auto;
        margin-top: 45px;
        margin-right: 1%;
        height: 30px;
        font-size: 16px;
        border: none;
        background: #dc3545;
        /* Adjust the button style as needed */
        padding: 0px 20px;
        cursor: pointer;
    }
    .remove-input {
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        margin: auto;
        margin-right: 1%;
        height: 30px;
        font-size: 16px;
        border: none;
        background: #dc3545;
        /* Adjust the button style as needed */
        padding: 0px 20px;
        cursor: pointer;
    }

    textarea {
        resize: none;
    }

    .email {
        border-radius: 5px;
        border: 1px solid #ccc;
        height: 50px;
        width: 100%;
    }

    .titleh1 {
        font-size: 32px;
    }

    .input-group {
        margin-bottom: 2%;
    }
    .inputgroup {
        margin-bottom: 2%;
    }
    .row {
        margin-bottom: 5px;
    }

    .row label {
        margin: 0;
        margin-bottom: 5px;
    }

    .row input {
        margin: 0;
        margin-bottom: 2%;
    }

    .form-select {
        height: 50px;
    }

    .row select {
        margin: 0;
        margin-bottom: 2%;
    }

    .row .select2-selection {
        margin: 0 !important;
        margin-bottom: 2% !important;
    }

    .row .select2-selection .select2-selection__arrow {
        margin: 10px !important;
    }

    .label2 {
        padding-top: 5px;
        float: left;
    }

    .datestyle {
        height: 50px !important;
        background-color: white;
    }

    .buttonstyle button {
        width: 10%;
        border: 1px solid #ccc;
        border-radius: 6px;
        margin-bottom: 10px;
    }

    .buttonstyle button:hover {
        background-color: #ccc;
    }
    .button-return {
    align-items: center;
    appearance: none;
    background-color: #6b6b6b;
    border-radius: 8px;
    border-style: none;
    box-shadow: rgba(0, 0, 0, 0.2) 0 3px 5px -1px,
      rgba(0, 0, 0, 0.14) 0 6px 10px 0, rgba(0, 0, 0, 0.12) 0 1px 18px 0;
    box-sizing: border-box;
    color: #ffffff;
    cursor: pointer;
    display: inline-flex;
    fill: currentcolor;
    font-size: 14px;
    font-weight: 500;
    height: 40px;
    justify-content: center;
    letter-spacing: 0.25px;
    line-height: normal;
    max-width: 100%;
    overflow: visible;
    padding: 2px 24px;
    position: relative;
    text-align: center;
    text-transform: none;
    transition: box-shadow 280ms cubic-bezier(0.4, 0, 0.2, 1),
      opacity 15ms linear 30ms, transform 270ms cubic-bezier(0, 0, 0.2, 1) 0ms;
    touch-action: manipulation;
    width: auto;
    will-change: transform, opacity;
    margin-left: 5px;
  }

  .button-return:hover {
    background-color: #ffffff !important;
    color: #000000;
    transform: scale(1.1);
  }
  .dtr-details {
        width: 100%;
    }

    .dtr-title {
        float: left;
        text-align: left;
        margin-right: 10px;
    }

    .dtr-data {
        display: block;
        text-align: right !important;
    }

    .dt-container .dt-paging .dt-paging-button {
        padding: 0 !important;
    }
    .btncontact{
        background-color: #109699 !important;
        color: white !important;
        text-align: center;
        border-radius: 8px;
        border-color: #9a9a9a;
        border-style: solid;
        border-width: 1px;
        width: 15%;
        height: 40px;
        padding-top: 6px;
        float: right;
        }
        .select2-container{
            width: 100% !important;
        }
  @media (max-width: 768px) {
       .flex-container{
        margin-top: 30px;
        width: 100%;
       }
       h1{
        margin-top:32px;
        }
        .btncontact{
            width: 40%;
        }
        .span{
         float: left;
        }
    }
</style>

<body>
    <div class="container-fluid border rounded-3 p-5 mt-3 bg-white" style="width: 98%;">
        <div class="row">
            <div class="col-9">
            <h1>Company / Agent</h1>
            </div>
            <div class="col-3">
                <input style="width:50%; float: right; margin-top:20px;"  type="text" id="Profile_ID" name="Profile_ID" maxlength="70" required value="{{$Profile_ID}}" disabled>
            </div>
        </div>
        <form id="myForm" action="{{url('/Company/Company_edit/Company_update/'.$Company->id)}}" method="POST" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div id="formContainer">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="Company_type">ประเภทบริษัท / Company Type</label>
                        <select name="Company_type" id="Company_type" class="form-select">
                            <option value=""></option>
                            @foreach($MCompany_type as $item)
                                <option value="{{ $item->id }}" {{$Company->Company_type == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-8 col-md-6 col-sm-12">
                        <label for="Company_Name">ชื่อบริษัท / Company Name</label>
                        <input type="text" id="Company_Name" name="Company_Name" maxlength="70" required value="{{$Company->Company_Name}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <label for="Branch">สาขา / Company Branch</label>
                    <input type="text" id="Branch" name="Branch" maxlength="70" required value="{{$Company->Branch}}">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <label for="Market">กลุ่มตลาด / Market</label>
                    <select name="Mmarket" id="Mmarket" class="form-select">
                        <option value=""></option>
                        @foreach($Mmarket as $item)
                            <option value="{{ $item->id }}" {{$Company->Market == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <label for="booking_channel">ช่องทางการจอง / Booking Channel</label>
                    <select name="booking_channel" id="booking_channel" class="select2">
                        @foreach($booking_channel as $item)
                            <option value="{{ $item->id }}" {{$Company->Booking_Channel == $item->id ? 'selected' : ''}}>{{ $item->name_en }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <label for="country">ประเทศ / Country</label>
                    <select name="countrydata" id="countrySelect" class="form-select" onchange="showcityInput()">
                        <option value="Thailand" {{$Company->Country == "Thailand" ? 'selected' : ''}}>ประเทศไทย</option>
                        <option value="Other_countries" {{$Company->Country == "Other_countries" ? 'selected' : ''}}>ประเทศอื่นๆ</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <label for="address">ที่อยู่ / Address</label>
                    <textarea type="text" id="address" name="address" rows="5" cols="25" class="textarea" aria-label="With textarea" required>{{$Company->Address}}</textarea>
                </div>
            </div>
            <div class="row">
                @if ($Company->Country === 'Other_countries')
                <div class="col-lg-3 col-md-6 col-sm-12" id="cityInput">
                    <label for="city">จังหวัด / Province</label>
                    <input type="text" id="city" name="city" value="{{$Other_City}}">
                </div>
                @else
                <div class="col-lg-3 col-md-6 col-sm-12" id="cityInput" style="display:none;">
                    <label for="city">จังหวัด / Province</label>
                    <input type="text" id="city" name="city">
                </div>
                @endif
                @if (($Company->Country === 'Thailand'))
                <div class="col-lg-3 col-md-6 col-sm-12" id="citythai" style="display:block;">
                    <label for="city">จังหวัด / Province</label>
                    <select name="province" id="province" class="select2" onchange="select_province()">
                        @foreach($provinceNames as $item)
                        <option value="{{ $item->id }}" {{$Company->City == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                        @endforeach
                    </select>
                </div>
                @else
                <div class="col-lg-3 col-md-6 col-sm-12" id="citythai" style="display:none;">
                    <label for="city">จังหวัด / Province</label>
                    <select name="province" id="province" class="select2" onchange="select_province()">
                        <option value=""></option>
                        @foreach($provinceNames as $item)
                        <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                @if ($Company->Country === 'Thailand')
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label for="Amphures">อำเภอ / District</label>
                    <select name="amphures" id="amphures" class="select2" onchange="select_amphures()">
                        @foreach($amphures as $item)
                        <option value="{{ $item->id }}" {{ $Company->Amphures == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                        @endforeach
                    </select>
                </div>
                @else
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label for="Amphures">อำเภอ / District</label>
                    <select name="amphures" id="amphures" class="select2" onchange="select_amphures()" disabled>
                        <option value=""></option>
                    </select>
                </div>
                @endif
                @if ($Company->Country === 'Thailand')
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label for="Tambon">ตำบล / Subdistrict </label>
                    <select name="Tambon" id="Tambon" class="select2" onchange="select_Tambon()">
                        @foreach($Tambon as $item)
                        <option value="{{ $item->id }}" {{ $Company->Tambon == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                        @endforeach
                    </select>
                </div>
                @else
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label for="Tambon">ตำบล / Subdistrict </label>
                    <select name="Tambon" id="Tambon" class="select2" onchange="select_Tambon()" disabled>
                        <option value=""></option>
                    </select>
                </div>
                @endif
                <div class="col-lg-3 col-md-6 col-sm-12">
                    @if ($Company->Country === 'Thailand')
                    <label for="zip_code">รหัสไปรษณีย์ / Postal Code</label><br>
                    <select name="zip_code" id="zip_code" class="select2">
                        @foreach($Zip_code as $item)
                        <option value="{{ $item->id }}" {{ $Company->Zip_Code == $item->zip_code ? 'selected' : '' }}>{{ $item->zip_code }}</option>
                        @endforeach
                    </select>
                </div>
                @else
                <div class="col-3">
                    <label for="zip_code">รหัสไปรษณีย์ / Postal Code</label><br>
                    <select name="zip_code" id="zip_code" class="select2" disabled>
                        <option value=""></option>
                    </select>
                </div>
                @endif
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div style="height: 40px;">
                            <label for="Company_Phone">
                                Company Phone number
                            </label>
                            <button style="float: right;" type="button" class="add-input" id="add-input">เพิ่มหมายเลขโทรศัพท์</button>
                        </div>
                        <div id="phone-inputs-container" class="flex-container">
                            @foreach($phoneDataArray as $index => $phone)
                            <div class="phone-group" style="position: relative;">
                                <input type="text" name="phone[]" class="form-control phone-input" maxlength="10" value="{{ $phone['Phone_number'] }}" data-index="{{ $index }}" data-old-value="{{ $phone['Phone_number'] }}">
                                <button type="button" class="remove-input">ลบ</button>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div style="height: 40px;">
                                <label for="Company_Fax">
                                    แฟกซ์ของบริษัท / Company Fax number
                                </label>
                                <button style="float: right;" type="button" class="add-input" id="add-fax">เพิ่มหมายเลขแฟกซ์</button>
                                </div>
                            <div id="fax-inputs-container" class="flex-container">
                                @foreach($faxArray as $index => $phone)
                                <div class="fax-group" style="position: relative;">
                                    <input type="text" name="fax[]" class="form-control fax-input" maxlength="11" value="{{ $phone['Fax_number'] }}" data-index="{{ $index }}" data-old-value="{{ $phone['Fax_number'] }}">
                                    <button type="button" class="remove-input">ลบ</button>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="Company_Email">ที่อยู่อีเมลของบริษัท / Company Email</label>
                        <input type="email" class="email" id="Company_Email" name="Company_Email" maxlength="70" required value="{{$Company->Company_Email}}">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="Company_Website">เว็บไซต์ของบริษัท / Company Website</label><br>
                        <input type="text" id="Company_Website" name="Company_Website" maxlength="70" required value="{{$Company->Company_Website}}">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="Taxpayer_Identification">เลขประจำตัวผู้เสียภาษี / Tax identification number</label><br>
                        <input type="text" id="Taxpayer_Identification" name="Taxpayer_Identification" maxlength="13" placeholder="เลขประจำตัวผู้เสียภาษี" required value="{{$Company->Taxpayer_Identification}}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="Discount_Contract_Rate">อัตราคิดลด / Discount Contract Rate</label><br>
                        <input type="text" id="Discount_Contract_Rate" name="Discount_Contract_Rate" maxlength="70" required value="{{$Company->Discount_Contract_Rate}}">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="contract_rate_start_date">Contract Rate Start Date</label><br>
                        <input class="datestyle" type="date" id="contract_rate_start_date" name="contract_rate_start_date" value="{{$Company->Contract_Rate_Start_Date}}">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="contract_rate_end_date">Contract Rate End Date</label><br>
                        <input class="datestyle" type="date" id="contract_rate_end_date" name="contract_rate_end_date" value="{{$Company->Contract_Rate_End_Date}}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <label for="Lastest_Introduce_By">แนะนำล่าสุดโดย / Lastest Introduce By</label><br>
                        <input type="text" id="Lastest_Introduce_By" name="Lastest_Introduce_By" maxlength="70" required value="{{$Company->Lastest_Introduce_By}}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-4"></div>
                    <div class="col-4" style="display:flex; justify-content:center; align-items:center;">
                        <button type="button" class="button-return" onclick="window.location.href='{{ route('Company.index') }}'" >{{ __('ย้อนกลับ') }}</button>
                        <button type="submit" class="button-10" style="background-color: #109699;"onclick="confirmSubmit(event)">บันทึกข้อมูล</button>
                    </div>
                    <div class="col-4"></div>
                </div>
            </form>
        <div style="border: 1px solid #2D7F7B;" class="mt-5"></div>
        <button type="button" class="btncontact  mt-5 my-3 "  data-toggle="modal"data-target="#createContart">เพิ่มตัวแทนบริษัท</button>
        <form enctype="multipart/form-data">
            @csrf
            <table id="example" class="table-hover nowarp" style="width:98%">
                <thead>
                    <tr>
                        <th style="text-align: center;">ลำดับ</th>
                        <th style="text-align: center;">รหัสโปรไฟล์</th>
                        <th>ชื่อองค์กร</th>
                        <th>สาขา</th>
                        <th>ชื่อผู้ใช้งาน</th>
                        <th>นามสกุลผู้ใช้งาน</th>
                        <th>สถานะการใช้งาน</th>
                        <th style="text-align: center;">คำสั่ง</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($representative))
                        @foreach ($representative as $key => $item)
                            <tr>
                                <td data-label="#">{{ $key + 1 }}</td>
                                <td data-label="รหัสลูกค้า">{{ $item->Profile_ID}}</td>
                                <td data-label="ตัวย่อ">{{ $item->Company_Name }}</td>
                                <td data-label="ตัวย่อ">{{ $item->Branch }}</td>
                                <td data-label="ชื่อผู้ใช้งาน">{{ $item->First_name }}</td>
                                <td data-label="นามสกุลผู้ใช้งาน">{{ $item->Last_name }}</td>
                                <td data-label="สถานะการใช้งาน">
                                    @if ($item->status === 1)
                                        <button type="button" class="button-1 status-toggle" data-id="{{ $item->id }}"data-status="{{ $item->status }} "data-company="{{ $Company->id }}">ใช้งาน</button>
                                    @else
                                        <button type="button" class="button-3 status-toggle " data-id="{{ $item->id }}" data-status="{{ $item->status }} "data-company="{{ $Company->id }}">ปิดใช้งาน</button>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown-a">
                                        <button class="button-18 button-17" type="button" data-toggle="dropdown">ทำรายการ
                                            <span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <li class="licolor"><a href="{{ url('/Company/edit/contact/editcontact/'.$Company->id.'/'.$item->id) }}">แก้ไขข้อมูล</a></li>
                                        </ul>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </form>
        <script>
            $(document).ready(function() {
            new DataTable('#example', {
                columnDefs: [
                    {
                        className: 'dtr-control',
                        orderable: true,
                        target: null
                    },
                    { width: '5%', targets: 0 },
                    { width: '10%', targets: 1 },
                    { width: '15%', targets: 2 },
                    { width: '15%', targets: 3 },
                    { width: '15%', targets: 4 },
                    { width: '15%', targets: 5 },
                    { width: '10%', targets: 6 },
                    { width: '10%', targets: 7 },
                ],
                order: [0, 'asc'],
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr'
                    }
                }

            });
            $('.select2').select2({
            width: '100%'
            });
        });
        </script>
        <div class="modal fade" id="createContart" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">เพิ่มตัวแทนบริษัท</h5>
                    </div>
                    <form  action="{{url('/Company/edit/contact/create/'.$Company->id)}}"  method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="col-12">
                                <div class=" row">
                                    <div class="col-lg-1 col-md-1 col-sm-12"></div>
                                    <div class="col-lg-2 col-md-6 col-sm-12" ><span for="prefix">Title</span><br>
                                        <select name="prefix" id="PrefaceSelect" class="form-select" required>
                                            <option value=""></option>
                                                @foreach($Mprefix as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                                @endforeach
                                        </select></div>
                                    <div class="col-lg-4 col-md-6 col-sm-12" ><span for="first_name">First Name</span>
                                        <input type="text" id="first_nameAgent" name="first_nameAgent"maxlength="70" required>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12" ><span for="last_name" >Last Name</span>
                                        <input type="text" id="last_nameAgent" name="last_nameAgent"maxlength="70" required>
                                    </div>
                                </div>
                                <div class=" row">
                                    <div class="col-lg-1 col-md-1 col-sm-12"></div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <span for="Country">Country</span>
                                        <select name="countrydataA" id="countrySelectA" class="form-select" onchange="showcityAInput()">
                                            <option value="Thailand">ประเทศไทย</option>
                                            <option value="Other_countries">ประเทศอื่นๆ</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12" id="cityInputA" style="display:none;">
                                        <span for="City">City</span>
                                        <input type="text" id="cityA" name="cityA">
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12" id="citythaiA" style="display:block;">
                                        <span for="City">City</span>
                                        <select name="provinceAgent" id="provinceAgent" class="form-select" onchange="provinceA()" style="width: 100%;">
                                            <option value=""></option>
                                            @foreach($provinceNames as $item)
                                                <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <span for="Amphures">Amphures</span>
                                        <select name="amphuresA" id="amphuresA" class="form-select" onchange="amphuresAgent()" >
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-1 col-md-6 col-sm-12"></div>
                                    <div class="col-lg-3 col-md-6 col-sm-12 ">
                                        <span for="Tambon">Tambon</span>
                                        <select name="TambonA" id ="TambonA" class="form-select" onchange="TambonAgent()" style="width: 100%;">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12 ">
                                        <span for="zip_code">zip_code</span>
                                        <select name="zip_codeA" id ="zip_codeA" class="form-select"  style="width: 100%;">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <span for="Email">Email</span>
                                        <input type="text" id="EmailAgent" name="EmailAgent"maxlength="70" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-1 col-md-6 col-sm-12"></div>
                                    <div class="col-lg-10 col-md-10 col-sm-12" >
                                        <span for="Address">Address</span>
                                        <textarea type="text" id="addressAgent" name="addressAgent" rows="3" cols="25" class="textarea" aria-label="With textarea" required></textarea>
                                    </div>
                                </div>

                                    <div class="row">
                                        <div class="col-lg-1 col-md-6 col-sm-12"></div>
                                        <div class="col-8">
                                            <label for="Phone_number">หมายเลขโทรศัพท์ / Phone number</label>
                                            <button type="button" class="add-phone" id="add-phone" data-target="phone-container">เพิ่มเบอร์โทรศัพท์</button>
                                        </div>
                                        <div id="phone-container" class="flex-container mt-2" style="margin-left: 65px">
                                            <div class="phone-group">
                                                <input type="text" name="phoneCon[]" class="form-control" style="width: 40%" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
                                                <button type="button" class="remove-phone">ลบ</button>
                                                <br>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        document.getElementById('add-phone').addEventListener('click', function() {
                                            var phoneContainer = document.getElementById('phone-container');
                                            var newPhoneGroup = phoneContainer.firstElementChild.cloneNode(true);
                                            newPhoneGroup.querySelector('input').value = '';
                                            phoneContainer.appendChild(newPhoneGroup);
                                            attachRemoveEvent(newPhoneGroup.querySelector('.remove-phone'));
                                        });

                                        function attachRemoveEvent(button) {
                                            button.addEventListener('click', function() {
                                                var phoneContainer = document.getElementById('phone-container');
                                                if (phoneContainer.childElementCount > 1) {
                                                    phoneContainer.removeChild(button.parentElement);
                                                }
                                            });
                                        }

                                        // Attach the remove event to the initial remove button
                                        attachRemoveEvent(document.querySelector('.remove-phone'));
                                    </script>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="button-return"data-dismiss="modal">Close</button>
                            <button type="submit" class="button-10" style="background-color: #109699;">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>














<script>

    function showcityAInput() {
            var countrySelectA = document.getElementById("countrySelectA");
            var cityInputA = document.getElementById("cityInputA");
            var citythaiA = document.getElementById("citythaiA");
            var amphuresSelect = document.getElementById("amphuresA");
            var tambonSelect = document.getElementById("TambonA");
            var zipCodeSelect = document.getElementById("zip_codeA");
            // เช็คค่าที่ถูกเลือกใน dropdown list เมือง
            if (countrySelectA.value === "Other_countries") {
                // ถ้าเลือก "Other_countries" แสดง input field สำหรับเมืองอื่นๆ และซ่อน input field สำหรับเมืองไทย
                cityInputA.style.display = "block";
                citythaiA.style.display = "none";
                // ปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
                amphuresSelect.disabled = true;
                tambonSelect.disabled = true;
                zipCodeSelect.disabled = true;
            } else if (countrySelectA.value === "Thailand"){
                // เช็คค่าที่ถูกเลือกใน dropdown list เมือง
                // ถ้าเลือก "Other_countries" แสดง input field สำหรับเมืองอื่นๆ และซ่อน input field สำหรับเมืองไทย
                cityInputA.style.display = "none";
                citythaiA.style.display = "block";
                // ปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
                amphuresSelect.disabled = false;
                tambonSelect.disabled = false;
                zipCodeSelect.disabled = false;

                // เรียกใช้ฟังก์ชัน select_amphures() เพื่อเปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
                amphuresAgent();
            }

        }

$(document).ready(function() {
    $('.status-toggle').click(function() {
        var button = $(this);
        var id = button.data('id');
        var status = button.data('status');
        var companyId = button.data('company');
        var newStatus = status === 1 ? 0 : 1; // Toggle status
        var token = "{{ csrf_token() }}"; // รับ CSRF token จาก Laravel

        // ทำ AJAX request
        $.ajax({
            type: 'POST',
            url: "{{ url('/Company/contact/change-status/') }}" + '/' + companyId,
            data: {
                _token: token, // เพิ่ม CSRF token ในข้อมูลของ request
                ids: id,
                status: newStatus
            },
            success: function(response) {
                // ปรับเปลี่ยนสถานะบนหน้าเว็บ
                console.log(response.Company);
                button.data('status', newStatus);
                if (newStatus == 1) {
                    // เปลี่ยนสถานะจากปิดเป็นเปิด
                    button.removeClass('button-3').addClass('button-1').html('ใช้งาน');
                } else {
                    // เปลี่ยนสถานะจากเปิดเป็นปิด
                    button.removeClass('button-1').addClass('button-3').html('ปิดใช้งาน');
                }
                Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                location.reload();
            }
        });
    });
});


//---------------------------------------------------2------------------------------
            function provinceA(){
            var provinceAgent = $('#provinceAgent').val();
            jQuery.ajax({
                type:   "GET",
                url:    "{!! url('/Company/amphuresA/"+provinceAgent+"') !!}",
                datatype:   "JSON",
                async:  false,
                success: function(result) {
                    jQuery('#amphuresA').children().remove().end();
                    console.log(result);
                    $('#amphuresA').append(new Option('', ''));
                    jQuery.each(result.data, function(key, value) {
                        var amphuresA = new Option(value.name_th,value.id);
                        //console.log(amphuresA);
                        $('#amphuresA').append(amphuresA);
                    });
                },
            })

        }
        function amphuresAgent(){
            var amphuresAgent  = $('#amphuresA').val();
            console.log(amphuresAgent);
            $.ajax({
                type:   "GET",
                url:    "{!! url('/Company/TambonA/"+amphuresAgent+"') !!}",
                datatype:   "JSON",
                async:  false,
                success: function(result) {
                   // console.log(result);
                    jQuery('#TambonA').children().remove().end();
                    $('#TambonA').append(new Option('', ''));
                    jQuery.each(result.data, function(key, value) {
                        var TambonA  = new Option(value.name_th,value.id);
                        $('#TambonA').append(TambonA );
                       // console.log(TambonA);
                    });
                },
            })
        }
        function TambonAgent(){
            var TambonAgent  = $('#TambonA').val();
            console.log(TambonAgent);
            $.ajax({
                type:   "GET",
                url:    "{!! url('/Company/districtsA/"+TambonAgent+"') !!}",
                datatype:   "JSON",
                async:  false,
                success: function(result) {
                    console.log(result);
                    jQuery('#zip_codeA').children().remove().end();
                    //console.log(result);
                    $('#zip_codeA').append(new Option('', ''));
                    jQuery.each(result.data, function(key, value) {
                        var zip_codeA  = new Option(value.zip_code,value.zip_code);
                        $('#zip_codeA').append(zip_codeA);
                        //console.log(zip_codeA);
                    });
                },
            })
        }
</script>
<script>

    function showcityInput() {
        var countrySelect = document.getElementById("countrySelect");
        var cityInput = document.getElementById("cityInput");
        var citythai = document.getElementById("citythai");
        var amphuresSelect = document.getElementById("amphures");
        var tambonSelect = document.getElementById("Tambon");
        var zipCodeSelect = document.getElementById("zip_code");

        // เช็คค่าที่ถูกเลือกใน dropdown list เมือง
        if (countrySelect.value === "Other_countries") {
            // ถ้าเลือก "Other_countries" แสดง input field สำหรับเมืองอื่นๆ และซ่อน input field สำหรับเมืองไทย
            cityInput.style.display = "block";
            citythai.style.display = "none";
            // ปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
            amphuresSelect.disabled = true;
            tambonSelect.disabled = true;
            zipCodeSelect.disabled = true;
        } else if (countrySelect.value === "Thailand") {
            // เช็คค่าที่ถูกเลือกใน dropdown list เมือง

            // ถ้าเลือก "Other_countries" แสดง input field สำหรับเมืองอื่นๆ และซ่อน input field สำหรับเมืองไทย
            cityInput.style.display = "none";
            citythai.style.display = "block";
            // ปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
            amphuresSelect.disabled = false;
            tambonSelect.disabled = false;
            zipCodeSelect.disabled = false;

            // เรียกใช้ฟังก์ชัน select_amphures() เพื่อเปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
            select_amphures();
        }
    }


    function select_province() {
        var provinceID = $('#province').val();
        jQuery.ajax({
            type: "GET",
            url: "{!! url('/Company/amphures/" + provinceID + "') !!}",
            datatype: "JSON",
            async: false,
            success: function(result) {
                jQuery('#amphures').children().remove().end();
                //ตัวแปร
                $('#amphures').append(new Option('', ''));
                jQuery.each(result.data, function(key, value) {
                    var amphures = new Option(value.name_th, value.id);
                    $('#amphures').append(amphures);
                });
            },
        })
    }

    function select_amphures() {
        var amphuresID = $('#amphures').val();
        $.ajax({
            type: "GET",
            url: "{!! url('/Company/Tambon/" + amphuresID + "') !!}",
            datatype: "JSON",
            async: false,
            success: function(result) {
                jQuery('#Tambon').children().remove().end();
                $('#Tambon').append(new Option('', ''));
                jQuery.each(result.data, function(key, value) {
                    var Tambon = new Option(value.name_th, value.id);
                    $('#Tambon ').append(Tambon);
                });
            },
        })
    }

    function select_Tambon() {
        var Tambon = $('#Tambon').val();
        $.ajax({
            type: "GET",
            url: "{!! url('/Company/districts/" + Tambon + "') !!}",
            datatype: "JSON",
            async: false,
            success: function(result) {
                jQuery('#zip_code').children().remove().end();
                $('#zip_code').append(new Option('', ''));
                jQuery.each(result.data, function(key, value) {
                    var zip_code = new Option(value.zip_code, value.zip_code);
                    $('#zip_code ').append(zip_code);
                });
            },
        })
    }

document.getElementById('add-input').addEventListener('click', function() {
    var container = document.getElementById('phone-inputs-container');
    var index = container.querySelectorAll('.phone-group').length;
    var newInputGroup = document.createElement('div');
    newInputGroup.classList.add('phone-group');
    newInputGroup.style.position = 'relative';
    newInputGroup.innerHTML = `
        <input type="text" name="phone[]" class="form-control phone-input" maxlength="10" value="" data-index="${index}" data-old-value="">
        <button type="button" class="remove-input">ลบ</button>
    `;
    container.appendChild(newInputGroup);
    addRemoveButtonListener(newInputGroup.querySelector('.remove-input'));
    addInputChangeListener(newInputGroup.querySelector('.phone-input'));
    updateWindowPhoneChanged();
});

document.getElementById('add-fax').addEventListener('click', function() {
    var container = document.getElementById('fax-inputs-container');
    var index = container.querySelectorAll('.fax-group').length;
    var newInputGroup = document.createElement('div');
    newInputGroup.classList.add('fax-group');
    newInputGroup.style.position = 'relative';
    newInputGroup.innerHTML = `
        <div class="input-container">
            <input type="text" name="fax[]" class="form-control fax-input" maxlength="11" value="" data-index="${index}" data-old-value="">
            <button type="button" class="remove-input">ลบ</button>
        </div>
    `;
    container.appendChild(newInputGroup);
    addRemoveButtonListener(newInputGroup.querySelector('.remove-input'));
    addInputChangeListener(newInputGroup.querySelector('.fax-input'));
    updateWindowPhoneChanged();
});

document.querySelectorAll('.remove-input').forEach(function(button) {
    addRemoveButtonListener(button);
});

document.querySelectorAll('.phone-input, .fax-input').forEach(function(input) {
    addInputChangeListener(input);
});

function addRemoveButtonListener(button) {
    button.addEventListener('click', function() {
        var container = button.closest('.phone-group, .fax-group');
        container.parentElement.removeChild(container);
        updateIndices();
        updateWindowPhoneChanged();
    });
}

function addInputChangeListener(input) {
    input.addEventListener('input', function() {
        if (input.value !== input.getAttribute('data-old-value')) {
            updateWindowPhoneChanged();
        }
    });
}

function updateIndices() {
    var phoneInputs = document.querySelectorAll('.phone-input');
    phoneInputs.forEach(function(input, index) {
        input.setAttribute('data-index', index);
        input.setAttribute('name', `phone[${index}]`);
    });

    var faxInputs = document.querySelectorAll('.fax-input');
    faxInputs.forEach(function(input, index) {
        input.setAttribute('data-index', index);
        input.setAttribute('name', `fax[${index}]`);
    });
}

function updateWindowPhoneChanged() {
    window.phoneChanged1 = 1;
    console.log('phoneChanged1:', window.phoneChanged1);
}

</script>
<script>
    function confirmSubmit(event) {
        event.preventDefault(); // Prevent the form from submitting
        var Company_Name = $('#Company_Name').val();
        var Branch = $('#Branch').val();
        var message = `หากบันทึกข้อมูลบริษัท ${Company_Name} สาขา ${Branch} หรือไม่`;
        Swal.fire({
            title: "คุณต้องการบันทึกใช่หรือไม่?",
            text: message,
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "บันทึกข้อมูล",
            cancelButtonText: "ยกเลิก",
            confirmButtonColor: "#28a745",
            dangerMode: true
        }).then((result) => {
            if (result.isConfirmed) {
                // If user confirms, submit the form
                document.getElementById("myForm").submit();
            }
        });
    }
    var alertMessage = "{{ session('alert_') }}";
    var alerterror = "{{ session('error_') }}";
    if (alertMessage) {
        // แสดง SweetAlert ทันทีเมื่อโหลดหน้าเว็บ
        Swal.fire({
            icon: 'success',
            title: alertMessage,
            showConfirmButton: false,
            timer: 1500
        });
    }
    if (alerterror) {
        Swal.fire({
            icon: 'error',
            title: alerterror,
            showConfirmButton: false,
            timer: 1500
        });
    }
</script>

@endsection

</html>
