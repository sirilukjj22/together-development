@extends('layouts.test')

@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai+Looped:wght@100;200;300;400;500;600;700;800;900&family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <!-- เพิ่มลิงก์ CSS ของ Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
    <!-- ลิงก์ JavaScript ของ jQuery -->

    <!-- ลิงก์ JavaScript ของ Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
</head>
<style>
    .container {
        margin-top: 40px;
        background-color: white;
        padding: 5% 5%;
        overflow-x: hidden;
    }

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
    .remove-phone {
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

    .input-container .form-control {
        width: 100%;
        padding-right: 50px;
        margin: 0;
        /* Adjust based on button width */
        box-sizing: border-box;
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
</style>

<body>



    <div class="container-fluid border rounded-3 p-5 mt-3 bg-white" style="width: 98%;">
        <div class="row">
            <div class="titleh1 col-9">
                <h1>Company (องค์กร)</h1>
            </div>
            <div class="col-3">
                <input style="width:50%; float: right;" type="text" id="Profile_ID" name="Profile_ID" maxlength="70" required value="{{$Profile_ID}}" disabled>
            </div>
        </div>

        <div class="row buttonstyle">
            <div class="col-lg-12 col-md-6 col-sm-12" id="add-contact-cc">
                <button class="button1" onclick="window.location.href = '{{ url('/Company/edit/'.$Company->id) }}'">Company</button>
            </div>
            <div class="col-lg-12 col-md-6 col-sm-12" id="add-contact-c">
                <button class="button1" onclick="confirmRedirectC()">Contact</button>
            </div>
            <div class="col-lg-12 col-md-6 col-sm-12" id="add-contact-d">
                <button class="button1" onclick="confirmDatail()">Detail</button>
            </div>
        </div>




        <form id="myForm" action="{{url('/Company/Company_edit/Company_update/'.$Company->id)}}" method="POST">
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
            </div>
        </form>
</body>
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


// document.getElementById('add-input').addEventListener('click', function() {
//     var container = document.getElementById('phone-inputs-container');
//     var index = container.querySelectorAll('.phone-group').length;
//     var newInputGroup = document.createElement('div');
//     newInputGroup.classList.add('phone-group');
//     newInputGroup.style.position = 'relative';
//     newInputGroup.innerHTML = `
//         <input type="text" name="phone[]" class="form-control phone-input" maxlength="10" value="" data-index="${index}" data-old-value="">
//         <button type="button" class="remove-input">ลบ</button>
//     `;
//     container.appendChild(newInputGroup);
//     addRemoveButtonListener(newInputGroup.querySelector('.remove-input'));
// });


// document.getElementById('add-fax').addEventListener('click', function() {
//     var container = document.getElementById('fax-inputs-container');
//     var index = container.querySelectorAll('.fax-group').length;
//     var newInputGroup = document.createElement('div');
//     newInputGroup.classList.add('fax-group');
//     newInputGroup.style.position = 'relative';
//     newInputGroup.innerHTML = `
//         <div class="input-container">
//             <input type="text" name="fax[]" class="form-control fax-input" maxlength="11" value="" data-index="${index}" data-old-value="">
//             <button type="button" class="remove-input">ลบ</button>
//         </div>
//     `;
//     container.appendChild(newInputGroup);
//     addRemoveButtonListener(newInputGroup.querySelector('.remove-input'));
// });

// document.querySelectorAll('.remove-input').forEach(function(button) {
//     addRemoveButtonListener(button);
// });

// function addRemoveButtonListener(button) {
//     button.addEventListener('click', function() {
//         var container = button.closest('.phone-group, .fax-group');
//         container.parentElement.removeChild(container);
//         updateIndices();
//     });
// }

// function updateIndices() {
//     var phoneInputs = document.querySelectorAll('.phone-input');
//     phoneInputs.forEach(function(input, index) {
//         input.setAttribute('data-index', index);
//         input.setAttribute('name', `phone[${index}]`);
//     });

//     var faxInputs = document.querySelectorAll('.fax-input');
//     faxInputs.forEach(function(input, index) {
//         input.setAttribute('data-index', index);
//         input.setAttribute('name', `fax[${index}]`);
//     });
// }
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



    $(document).ready(function() {
        const inputIds = [
            'Company_type', 'Company_Name', 'Branch', 'Mmarket', 'booking_channel',
            'countrydata', 'address', 'city', 'province', 'amphures', 'Tambon',
            'zip_code', 'Company_Email', 'Company_Website', 'Taxpayer_Identification',
            'Discount_Contract_Rate', 'contract_rate_start_date', 'contract_rate_end_date',
            'Lastest_Introduce_By'
        ];

        function handleInputChange(event) {
            const target = event.target;
            console.log(`${target.id} มีการเปลี่ยนแปลงเป็น:`, target.value);
            window.checkcompany = 1;
            if (['Discount_Contract_Rate', 'contract_rate_end_date', 'Lastest_Introduce_By'].includes(target.id)) {
                window.checkcompanyDetail = 1;
            }
            console.log(window.checkcompany);
        }

        inputIds.forEach(function(id) {
            $(`#${id}`).on('change', handleInputChange);
        });
    });

    function confirmRedirectC() {
        if (window.checkcompany === 1 || window.checkcompanyDetail === 1) {
            console.log(window.phoneChanged1);
            Swal.fire({
                title: 'คุณบันทึกข้อมูลที่แก้ไขหรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ใช่',
                cancelButtonText: 'ไม่'
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log("Form is about to be submitted.");
                    submitFormWithAjax(myForm).then(() => {
                        console.log("Form submitted."); // เพิ่ม log หลังการส่งฟอร์ม
                        window.location.href = '{{ url('/Company/edit/contact/'.$Company->id) }}'; // เปลี่ยนหน้า
                    });

                } else {
                    console.log("User cancelled the form submission.");
                    window.location.href = '{{ url('/Company/edit/contact/'.$Company->id) }}';
                }
            });
        } else {
            confirmphoneContact();
        }

    }

    function confirmDatail() {
        console.log('Detaile = 1');
        if (window.checkcompany === 1 || window.checkcompanyDetail === 1) {
            console.log(window.phoneChanged1);
            Swal.fire({
                title: 'คุณบันทึกข้อมูลที่แก้ไขหรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ใช่',
                cancelButtonText: 'ไม่'
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log("Form is about to be submitted.");
                    submitFormWithAjax(myForm).then(() => {
                        console.log("Form submitted."); // เพิ่ม log หลังการส่งฟอร์ม
                        window.location.href = '{{ url('/Company/edit/contact/detail/'.$Company->id) }}'; // เปลี่ยนหน้า
                    });

                } else {
                    console.log("User cancelled the form submission.");
                    window.location.href = '{{ url('/Company/edit/contact/detail/'.$Company->id) }}';
                }
            });
        } else {
            confirmphoneDetail();
        }

    }

    function confirmphoneDetail() {
        // แสดงข้อความแจ้งเตือน
        if (window.phoneChanged1 === 1) {
            console.log(window.phoneChanged1);
            Swal.fire({
                title: 'คุณบันทึกข้อมูลที่แก้ไขหรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ใช่',
                cancelButtonText: 'ไม่'
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log("Form is about to be submitted.");
                    submitFormWithAjax(myForm).then(() => {
                        console.log("Form submitted."); // เพิ่ม log หลังการส่งฟอร์ม
                        window.location.href = '{{ url(' /Company/edit/contact/detail/'.$Company->id) }}'; // เปลี่ยนหน้า
                    });
                }
            });
        } else {
            confirmfaxDetail();
        }
    }

    function confirmphoneContact() {
        // แสดงข้อความแจ้งเตือน

        if (window.phoneChanged1 === 1) {
            console.log(window.phoneChanged1);
            Swal.fire({
                title: 'คุณบันทึกข้อมูลที่แก้ไขหรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ใช่',
                cancelButtonText: 'ไม่'
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log("Form is about to be submitted.");
                    submitFormWithAjax(myForm).then(() => {
                        console.log("Form submitted."); // เพิ่ม log หลังการส่งฟอร์ม
                        window.location.href = '{{ url('/Company/edit/contact/'.$Company->id) }}'; // เปลี่ยนหน้า
                    });
                }
            });
        } else {
            confirmfaxContact();
        }

    }

    function submitFormWithAjax(form) {
        return new Promise((resolve, reject) => {
            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();
            xhr.open("POST", form.action, true);
            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    resolve(xhr.response);
                } else {
                    reject({
                        status: xhr.status,
                        statusText: xhr.statusText
                    });
                }
            };
            xhr.onerror = function() {
                reject({
                    status: xhr.status,
                    statusText: xhr.statusText
                });
            };
            xhr.send(formData);
        });
    }
    //-----------------------

    //     document.addEventListener("DOMContentLoaded", function() {
    //         const addButton = document.getElementById('add-fax');
    //         const removeButton = document.querySelector('.remove-fax');
    //         const faxContainer = document.getElementById('fax-container');

    //         // Initialize faxCount with a starting value, assuming you start with one fax input
    //         let faxCount = 1;
    //         let faxChanges = {};

    //         function handleFaxInputChange(event) {
    //             const inputValue = event.target.value;
    //             const index = event.target.getAttribute('data-index');
    //             const oldValue = event.target.getAttribute('data-old-value');
    //             const changed = inputValue !== oldValue ? 1 : 0;

    //             faxChanges[index] = changed;
    //             console.log(`Fax number in input ${index} changed:`, changed);

    //             // Update global change flags
    //             window.phoneChanged1 = 1;
    //             window.phoneChanged0 = 0;
    //             console.log('phoneChanged1:', window.phoneChanged1);
    //             console.log('phoneChanged0:', window.phoneChanged0);
    //         }

    //         function addFaxInput() {
    //             const faxGroup = document.createElement('div');
    //             faxGroup.classList.add('fax-group');
    //             const newIndex = faxCount; // Use faxCount as index
    //             faxGroup.innerHTML = `
    //             <input type="text" name="fax[]" class="form-control fax-input" maxlength="11" value="{{ $phone['Fax_number'] }}" data-index="${newIndex}" data-old-value="">
    // `;
    //             faxContainer.appendChild(faxGroup);
    //             faxCount++;
    //             updateButtonStates();
    //             console.log('Added new fax input');
    //             document.querySelector(`input[data-index="${newIndex}"]`).addEventListener('input', handleFaxInputChange);

    //             // Update global change flags
    //             window.phoneChanged1 = 1;
    //             window.phoneChanged0 = 0;
    //             console.log('phoneChanged1:', window.phoneChanged1);
    //             console.log('phoneChanged0:', window.phoneChanged0);
    //         }

    //         function removeFaxInput() {
    //             if (faxCount > 1) {
    //                 const faxGroups = faxContainer.querySelectorAll('.fax-group');
    //                 const lastFaxGroup = faxGroups[faxGroups.length - 1];
    //                 faxContainer.removeChild(lastFaxGroup);
    //                 faxCount--;
    //                 updateButtonStates();
    //                 console.log('Removed last fax input');

    //                 // Update global change flags
    //                 window.phoneChanged1 = 1;
    //                 window.phoneChanged0 = 0;
    //                 console.log('phoneChanged1:', window.phoneChanged1);
    //                 console.log('phoneChanged0:', window.phoneChanged0);
    //             }
    //         }

    //         function updateButtonStates() {
    //             removeButton.disabled = (faxCount <= 1);
    //         }

    //         addButton.addEventListener('click', addFaxInput);
    //         removeButton.addEventListener('click', removeFaxInput);

    //         // Attach event listeners to the initial inputs
    //         document.querySelectorAll('.fax-input').forEach(input => {
    //             input.addEventListener('input', handleFaxInputChange);
    //         });
    //     });



    function confirmfaxContact() {
        // แสดงข้อความแจ้งเตือน
        if (window.faxChanged1 === 1) {
            console.log(window.faxChanged1);
            Swal.fire({
                title: 'คุณบันทึกข้อมูลที่แก้ไขหรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ใช่',
                cancelButtonText: 'ไม่'
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log("Form is about to be submitted.");
                    submitFormWithAjax(myForm).then(() => {
                        console.log("Form submitted."); // เพิ่ม log หลังการส่งฟอร์ม
                        window.location.href = '{{ url('/Company/edit/contact/'.$Company->id) }}'; // เปลี่ยนหน้า
                    });
                }
            });
        } else {
            window.location.href = '{{ url('/Company/edit/contact/'.$Company->id) }}';
        }
    }

    function confirmfaxDetail() {
        // แสดงข้อความแจ้งเตือน
        if (window.faxChanged1 === 1) {
            console.log(window.faxChanged1);
            Swal.fire({
                title: 'คุณบันทึกข้อมูลที่แก้ไขหรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ใช่',
                cancelButtonText: 'ไม่'
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log("Form is about to be submitted.");
                    submitFormWithAjax(myForm).then(() => {
                        console.log("Form submitted."); // เพิ่ม log หลังการส่งฟอร์ม
                        window.location.href = '{{ url('/Company/edit/contact/detail/'.$Company->id) }}'; // เปลี่ยนหน้า
                    });
                }
            });
        } else {
            window.location.href = '{{ url('/Company/edit/contact/detail/'.$Company->id) }}';
        }
    }

    function submitFormWithAjax(form) {
        return new Promise((resolve, reject) => {
            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();
            xhr.open("POST", form.action, true);
            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    resolve(xhr.response);
                } else {
                    reject({
                        status: xhr.status,
                        statusText: xhr.statusText
                    });
                }
            };
            xhr.onerror = function() {
                reject({
                    status: xhr.status,
                    statusText: xhr.statusText
                });
            };
            xhr.send(formData);
        });
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
