@extends('layouts.test')

@section('content')

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

    <div class="container-fluid border rounded-3 p-5 mt-3 bg-white" style="width: 98%;">
        <div class="row">
            <div class="col-9">
            <h1>Company / Agent</h1>
            </div>
            <div class="col-3">
                <input style="width:50%; float: right; margin-top:20px;"  type="text" id="Profile_ID" name="Profile_ID" maxlength="70" required value="{{$Profile_ID}}" disabled>
            </div>
        </div>
            <div id="formContainer">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="Company_type">ประเภทบริษัท / Company Type</label>
                        <select name="Company_type" id="Company_type" class="form-select"disabled>
                            <option value=""></option>
                            @foreach($MCompany_type as $item)
                                <option value="{{ $item->id }}" {{$Company->Company_type == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-8 col-md-6 col-sm-12">
                        <label for="Company_Name">ชื่อบริษัท / Company Name</label>
                        <input type="text" id="Company_Name" name="Company_Name" maxlength="70" required value="{{$Company->Company_Name}}"disabled>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <label for="Branch">สาขา / Company Branch</label>
                    <input type="text" id="Branch" name="Branch" maxlength="70" required value="{{$Company->Branch}}"disabled>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <label for="Market">กลุ่มตลาด / Market</label>
                    <select name="Mmarket" id="Mmarket" class="form-select"disabled>
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
                    <select name="booking_channel" id="booking_channel" class="select2"disabled>
                        @foreach($booking_channel as $item)
                            <option value="{{ $item->id }}" {{$Company->Booking_Channel == $item->id ? 'selected' : ''}}>{{ $item->name_en }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <label for="country">ประเทศ / Country</label>
                    <select name="countrydata" id="countrySelect" class="form-select" onchange="showcityInput()"disabled>
                        <option value="Thailand" {{$Company->Country == "Thailand" ? 'selected' : ''}}>ประเทศไทย</option>
                        <option value="Other_countries" {{$Company->Country == "Other_countries" ? 'selected' : ''}}>ประเทศอื่นๆ</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <label for="address">ที่อยู่ / Address</label>
                    <textarea type="text" id="address" name="address" rows="5" cols="25" class="textarea" aria-label="With textarea" disabled>{{$Company->Address}}</textarea>
                </div>
            </div>
            <div class="row">
                @if ($Company->Country === 'Other_countries')
                <div class="col-lg-3 col-md-6 col-sm-12" id="cityInput"disabled>
                    <label for="city">จังหวัด / Province</label>
                    <input type="text" id="city" name="city" value="{{$Other_City}}">
                </div>
                @else
                <div class="col-lg-3 col-md-6 col-sm-12" id="cityInput" style="display:none;"disabled>
                    <label for="city">จังหวัด / Province</label>
                    <input type="text" id="city" name="city">
                </div>
                @endif
                @if (($Company->Country === 'Thailand'))
                <div class="col-lg-3 col-md-6 col-sm-12" id="citythai" style="display:block;">
                    <label for="city">จังหวัด / Province</label>
                    <select name="province" id="province" class="select2" onchange="select_province()"disabled>
                        @foreach($provinceNames as $item)
                        <option value="{{ $item->id }}" {{$Company->City == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                        @endforeach
                    </select>
                </div>
                @else
                <div class="col-lg-3 col-md-6 col-sm-12" id="citythai" style="display:none;">
                    <label for="city">จังหวัด / Province</label>
                    <select name="province" id="province" class="select2" onchange="select_province()"disabled>
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
                    <select name="amphures" id="amphures" class="select2" onchange="select_amphures()"disabled>
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
                    <select name="Tambon" id="Tambon" class="select2" onchange="select_Tambon()"disabled>
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
                    <select name="zip_code" id="zip_code" class="select2"disabled>
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
                                <input type="text" name="phone[]" class="form-control phone-input" maxlength="10" value="{{ $phone['Phone_number'] }}" data-index="{{ $index }}" data-old-value="{{ $phone['Phone_number'] }}"disabled>

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
                                    <input type="text" name="fax[]" class="form-control fax-input" maxlength="11" value="{{ $phone['Fax_number'] }}" data-index="{{ $index }}" data-old-value="{{ $phone['Fax_number'] }}" disabled>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="Company_Email">ที่อยู่อีเมลของบริษัท / Company Email</label>
                        <input type="email" class="email" id="Company_Email" name="Company_Email" maxlength="70" required value="{{$Company->Company_Email}}"disabled>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="Company_Website">เว็บไซต์ของบริษัท / Company Website</label><br>
                        <input type="text" id="Company_Website" name="Company_Website" maxlength="70" required value="{{$Company->Company_Website}}"disabled>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="Taxpayer_Identification">เลขประจำตัวผู้เสียภาษี / Tax identification number</label><br>
                        <input type="text" id="Taxpayer_Identification" name="Taxpayer_Identification" maxlength="13" placeholder="เลขประจำตัวผู้เสียภาษี" required value="{{$Company->Taxpayer_Identification}}"disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="Discount_Contract_Rate">อัตราคิดลด / Discount Contract Rate</label><br>
                        <input type="text" id="Discount_Contract_Rate" name="Discount_Contract_Rate" maxlength="70" required value="{{$Company->Discount_Contract_Rate}}"disabled>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="contract_rate_start_date">Contract Rate Start Date</label><br>
                        <input class="datestyle" type="date" id="contract_rate_start_date" name="contract_rate_start_date" value="{{$Company->Contract_Rate_Start_Date}}"disabled>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="contract_rate_end_date">Contract Rate End Date</label><br>
                        <input class="datestyle" type="date" id="contract_rate_end_date" name="contract_rate_end_date" value="{{$Company->Contract_Rate_End_Date}}"disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <label for="Lastest_Introduce_By">แนะนำล่าสุดโดย / Lastest Introduce By</label><br>
                        <input type="text" id="Lastest_Introduce_By" name="Lastest_Introduce_By" maxlength="70" required value="{{$Company->Lastest_Introduce_By}}"disabled>
                    </div>
                </div>
        <div style="border: 1px solid #2D7F7B;" class="mt-5"></div>
        <div class="card border-0">
            <div class="card-body" id="heading3">
                <h6 class="mb-0 py-2" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="true" aria-controls="faq3"><span <span class="fw-bold"></span>รายละเอียดตัวแทนองค์กร</h6>
            </div>

            <div id="faq3" class="collapse" aria-labelledby="heading3" data-parent="#accordionExample">
                <div class="card-body border-top">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label class="labelcontact" for="">Title</label>
                            <select name="Mprefix" id="Mprefix" class="form-select"disabled>
                                <option value=""></option>
                                @foreach($Mprefix as $item)
                                <option value="{{ $item->id }}" {{$representative->prefix == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12"><label for="first_name">First Name</label><br>
                            <input type="text" id="first_nameAgent" name="first_nameAgent" maxlength="70" disabled value="{{$representative->First_name}}">
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12"><label for="last_name">Last Name</label><br>
                            <input type="text" id="last_nameAgent" name="last_nameAgent" maxlength="70" disabled value="{{$representative->Last_name}}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label for="Country">Country</label><br>
                            <select name="countrydataA" id="countrySelectA" class="form-select" onchange="showcityAInput()"disabled>
                                <option value="Thailand" {{$representative->Country == "Thailand" ? 'selected' : ''}}>ประเทศไทย</option>
                                <option value="Other_countries" {{$representative->Country == "Other_countries" ? 'selected' : ''}}>ประเทศอื่นๆ</option>
                            </select>
                        </div>
                    @if ($representative->Country === 'Other_countries')
                        <div class="col-lg-4 col-md-6 col-sm-12" id="cityInput">
                            <label for="city">City</label><br>
                            <input type="text" id="city" name="city" value="{{$Other_City}}"disabled>
                        </div>
                        @else
                        <div class="col-lg-4 col-md-6 col-sm-12" id="cityInput" style="display:none;">
                            <label for="city">City</label><br>
                            <input type="text" id="city" name="city"disabled>
                        </div>
                        @endif
                        @if (($representative->Country === 'Thailand'))
                        <div class="col-lg-4 col-md-6 col-sm-12" id="citythai" style="display:block;">
                            <label for="city">City</label><br>
                            <select name="province" id="province" class="select2" onchange="select_province()"disabled>
                                @foreach($provinceNames as $item)
                                <option value="{{ $item->id }}" {{$representative->City == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                @endforeach
                            </select>
                        </div>
                        @else
                        <div class="col-lg-4 col-md-6 col-sm-12" id="citythai" style="display:none;">
                            <label for="city">City</label><br>
                            <select name="province" id="province" class="select2" onchange="select_province()"disabled>
                                <option value=""></option>
                                @foreach($provinceNames as $item)
                                <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        @if ($representative->Country === 'Thailand')
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label for="Amphures">Amphures</label><br>
                            <select name="amphures" id="amphures" class="select2" onchange="select_amphures()"disabled>
                                @foreach($amphures as $item)
                                <option value="{{ $item->id }}" {{ $representative->Amphures == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                @endforeach
                            </select>
                        </div>
                         @else
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label for="Amphures">Amphures</label><br>
                            <select name="amphures" id="amphures" class="select2" onchange="select_amphures()" disabled>
                                <option value=""></option>
                            </select>
                        </div>
                        @endif

                        </div>

                        <div class="row">
                             @if ($representative->Country === 'Thailand')
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="Tambon">Tambon </label><br>
                                <select name="Tambon" id="Tambon" class="select2" onchange="select_Tambon()"disabled>
                                @foreach($Tambon as $item)
                                <option value="{{ $item->id }}" {{ $representative->Tambon == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                @endforeach
                            </select>
                            </div>
                             @else
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label for="Tambon">Tambon </label><br>
                            <select name="Tambon" id="Tambon" class="select2" onchange="select_Tambon()" disabled>
                                <option value=""></option>
                            </select>
                        </div>
                        @endif
                        @if ($representative->Country === 'Thailand')
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label for="zip_code">Zip Code</label><br>
                            <select name="zip_code" id="zip_code" class="select2"disabled>
                                @foreach($Zip_code as $item)
                                <option value="{{ $item->id }}" {{ $representative->Zip_Code == $item->zip_code ? 'selected' : '' }}>{{ $item->zip_code }}</option>
                                @endforeach
                            </select>
                        </div>
                        @else
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label for="zip_code">Zip Code</label><br>
                            <select name="zip_code" id="zip_code" class="select2" disabled>
                                <option value=""></option>
                            </select>
                        </div>
                        @endif
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label for="Email">Email</label><br>
                            <input type="text" id="EmailAgent" name="EmailAgent" maxlength="70" required value="{{$representative->Email}}"disabled>
                        </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <label for="Address">Address</label><br>
                                <textarea type="text" id="addressAgent" name="addressAgent" rows="3" cols="25" class="textarea" aria-label="With textarea" disabled>{{$representative->Address}}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label for="Phone_number">หมายเลขโทรศัพท์ / Phone Number</label>
                            </div>
                        </div>
                        <div id="phone-container" class="flex-container row">
                            <!-- Initial input fields -->
                            @foreach($phoneArray as $phone)
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="phone-group show">
                                    <input type="text" name="phone[]" class="form-control" maxlength="10" value="{{ $phone['Phone_number'] }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required disabled>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div style="border: 1px solid #2D7F7B;" class="mt-5"></div>
            <div class="mt-4">
                <div class="col-12 row" >
                    <div class="col-1"style="  text-align: center; margin-Top: 20px;">Search</div>
                    <div class="col-3">
                        <input type="text" class="form-control" name="search" placeholder="search"/>
                    </div>

                    <div class="col-4"></div>

                </div>
                <div class="col-12 row mt-3">
                    <ul class="nav nav-tabs">
                        <li   li class="nav-item">
                            <a class="nav-link" onclick="openHistory(event, 'Summary_Visit_info')" id="defaultOpen">Summary Visit</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" onclick="openHistory(event, 'Lastest_Visit_info')" >Lastest Visit </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " onclick="openHistory(event, 'Billing Folio info')" >Billing Folio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"  onclick="openHistory(event, 'Latest Freelancer By')">Latest Freelancer By</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " onclick="openHistory(event, 'Lastest Freelancer Commission')" >Lastest Freelancer Commission</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " onclick="openHistory(event, 'Contract Rate Document')" >Contract Rate Document</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " onclick="openHistory(event, 'User logs')" >User logs</a>
                        </li>
                    </ul>
                    <div id="Summary_Visit_info" class="tabcontent">
                        <div class="row">
                            <table class="table" >
                                <thead>
                                    <tr>
                                        <th scope="col"class="text-center">#</th>
                                       <th scope="col"class="text-center">NO. Quotation</th>
                                        <th scope="col"class="text-center">Document date</th>
                                        <th scope="col"class="text-center">Room Rev.</th>
                                        <th scope="col"class="text-center">F&B Rev.</th>
                                        <th scope="col"class="text-center">Sqa </th>
                                        <th scope="col"class="text-center">Banquest</th>
                                        <th scope="col"class="text-center">Other Rev.</th>
                                        <th scope="col"class="text-center">Total Rev.</th>
                                        <th scope="col"class="text-center">Pax</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td scope="col"class="text-center">1</td>
                                        <td scope="col"class="text-center">Q6702005</td>
                                        <td scope="col"class="text-center">25/2/2024</td>
                                        <td scope="col"class="text-center">7,225.00</td>
                                        <td scope="col"class="text-center">0.00</td>
                                        <td scope="col"class="text-center">0.00</td>
                                        <td scope="col"class="text-center">0.00</td>
                                        <td scope="col"class="text-center">0.00</td>
                                        <td scope="col"class="text-center">7,225.00</td>
                                        <td scope="col"class="text-center">7</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="Lastest_Visit_info" class="tabcontent mt-3">
                        <div class="row" >
                            <table class="table">
                                    <thead class="table-active">
                                        <tr>
                                            <th scope="col"class="text-center">ชื่อสถาบัน</th>
                                            <th scope="col"class="text-center">คณะ</th>
                                            <th scope="col"class="text-center">สาขา</th>
                                            <th scope="col"class="text-center">เกรดเฉลี่ย</th>
                                            <th scope="col"class="text-center">จบการศึกษา</th>
                                            <th scope="col"class="text-center">วุฒิการศึกษา </th>
                                            <th scope="col"class="text-center">ประเภทสถาบัน</th>
                                            <th scope="col"class="text-center">ตัวเลือก</th>
                                        </tr>
                                    </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4"></div>
                <div class="col-4" style="display:flex; justify-content:center; align-items:center;">
                    <button type="button" class="button-return" onclick="window.location.href='{{ route('Company.index') }}'" >{{ __('ย้อนกลับ') }}</button>
                </div>
                <div class="col-4"></div>
            </div>
        </div>
    </div>
    <script>
        window.onload = function() {
            document.getElementById("defaultOpen").click(); // เปิดแท็บ Summary Visit info เมื่อหน้าโหลด
            document.getElementById("lastestOpen").click();}
        function openHistory(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("nav-link");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
            }
            $(document).ready(function(){
                $('.select2').select2();
        });
    </script>
@endsection
