@extends('layouts.test')

@section('content')


<style>
    .container {
        margin-top: 40px;
        background-color: white;
        padding: 5% 5%;
        overflow-x: hidden;
    }

    table {
        width: 100%;
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
        width: 35%;
        background-color: #28a745;
        border-color: #28a745;
        border-radius: 16px;
        padding: 0.5%;
        cursor: pointer;
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
    .label2{
        padding-top: 5px;
        float: left;
    }
    .datestyle {
        height: 50px !important;
        background-color: white;
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
        margin-bottom: 5px;
    }

    .input-container .form-control {
        width: 100%;
        padding-right: 50px;
        margin: 0;
        /* Adjust based on button width */
        box-sizing: border-box;
    }

    .input-container .remove-input {
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        margin: auto;
        margin-right: 1%;
        height: 60%;
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
        border-radius: 8px;
        border: 1px solid #aaa;
        height: 50px;
    }

    .titleh1 {
        font-size: 32px;
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

<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        }); //ประเภทรายได้ทั้งหมด in Search
        $('.select2-1').select2(); //หมายเลขบัญชีทั้งหมด in Search
        $('.select2-2').select2({
            dropdownParent: $('#exampleModalCenter5') // Ensure the dropdown is appended to the modal
        });
        $('.select2-3').select2({
            dropdownParent: $('#exampleModalCenter2') // Ensure the dropdown is appended to the modal
        });
    });
</script>

    <div class="container-fluid border rounded-3 p-5 mt-3 bg-white" style="width: 98%;">
        <div class="row">
            <div class="titleh1 col-9">
                <h1>Company (องค์กร)</h1>
            </div>
            <div class="col-3">
                <input style="width:50%; float: right;" type="text" id="Profile_ID" name="Profile_ID" maxlength="70" required value="{{$N_Profile}}" disabled>
            </div>
        </div>

        <form id="myForm" action="{{route('Company.save')}}" method="POST">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label for="Company_type">ประเภทบริษัท / Company Type</label>
                    <select name="Company_type" id="Company_type" class="form-select">
                        <option value="" selected disabled>Company Type</option>
                        @foreach($MCompany_type as $item)
                        <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-8 col-md-6 col-sm-12">
                    <label for="Company_Name">ชื่อบริษัท / Company Name</label>
                    <input type="text" id="Company_Name" name="Company_Name" maxlength="70" placeholder="Company Name" required>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <label for="Branch">สาขา / Company Branch</label>
                    <input type="text" id="Branch" name="Branch" maxlength="70" placeholder="Company Branch" required>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <label for="Market" >กลุ่มตลาด / Market</label>
                    <select name="Mmarket" id="Mmarket"  class="select2"required>
                        <option value=""></option>
                        @foreach($Mmarket as $item)
                        <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <label for="booking_channel">ช่องทางการจอง / Booking Channel</label><br>
                    <select name="booking_channel" id="booking_channel" class="select2" required>
                        <option value=""></option>
                        @foreach($booking_channel as $item)
                        <option value="{{ $item->id }}">{{ $item->name_en }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <label for="country">ประเทศ / Country</label>
                    <select name="countrydata" id="countrySelect" class="form-select" onchange="showcityInput()" required>
                        <option value="Thailand">ประเทศไทย</option>
                        <option value="Other_countries">ประเทศอื่นๆ</option>
                    </select>
                </div>
            </div>


            <div class="row">
                <div class="col">
                    <label for="address">ที่อยู่ / Address</label>
                    <textarea style="margin: 0 0 1% 0;" type="text" id="address" name="address" rows="5" cols="25" class="textarea" aria-label="With textarea" required></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12" id="cityInput" style="display:none;">
                    <label for="city">จังหวัด / Province</label>
                    <input type="text" id="city" name="city">
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12" id="citythai" style="display:block;">
                    <label for="city">จังหวัด / Province</label><br>
                    <select name="province" id="province" class="select2" onchange="select_province()" required>
                        <option value="" selected disabled>เลือกจังหวัด</option>
                        @foreach($provinceNames as $item)
                        <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label for="Amphures">อำเภอ / District</label><br>
                    <select name="amphures" id="amphures" class="select2" onchange="select_amphures()" required>
                        <option value="" selected disabled>เลือกอำเภอ</option>

                    </select>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label for="Tambon">ตำบล / Subdistrict </label><br>
                    <select name="Tambon" id="Tambon" class="select2" onchange="select_Tambon()" required>
                        <option value="">เลือกตำบล</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label for="zip_code">รหัสไปรษณีย์ / Postal Code</label>
                    <select name="zip_code" id="zip_code" class="select2" required>
                        <option value="">รหัสไปรษณีย์</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="label2">
                    <label for="Company_Phone" class="flex-container">
                        โทรศัพท์บริษัท / Company Phone number
                    </label>
                    </div>

                    <button style="float: right; margin-bottom:1%;" type="button" class="add-input" id="add-input">เพิ่มหมายเลขโทรศัพท์</button>
                    <div id="inputs-container">
                        <div class="input-group">
                            <div class="input-container">
                                <input type="text" name="phone_company[]" class="form-control" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
                                <button type="button" class="remove-input" disabled>ลบ</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="label2">
                    <label for="Company_Fax" class="flex-container">
                        แฟกซ์ของบริษัท / Company Fax number
                    </label>
                    </div>

                    <button style="float: right; margin-bottom:1%;" type="button" class="add-input" id="add-fax">เพิ่มหมายเลขแฟกซ์</button>
                    <div id="fax-container">
                        <div class="fax-group input-group">
                            <div class="input-container">
                                <input type="text" name="fax[]" class="form-control" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);" required>
                                <button type="button" class="remove-input" disabled>ลบ</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label for="Company_Email">ที่อยู่อีเมลของบริษัท / Company Email</label>
                    <input type="text" id="Company_Email" name="Company_Email" maxlength="70" placeholder="Company Email" required>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label for="Company_Website">เว็บไซต์ของบริษัท / Company Website</label><br>
                    <input type="text" id="Company_Website" name="Company_Website" maxlength="70" placeholder="Company Website" required>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label for="Taxpayer_Identification">เลขประจำตัวผู้เสียภาษี / Tax identification number</label><br>
                    <input type="text" id="Taxpayer_Identification" name="Taxpayer_Identification" maxlength="13" placeholder="เลขประจำตัวผู้เสียภาษี" required>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label for="Discount_Contract_Rate">อัตราคิดลด / Discount Contract Rate</label><br>
                    <input type="text" id="Discount_Contract_Rate" name="Discount_Contract_Rate" maxlength="70" required>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label for="contract_rate_start_date">Contract Rate Start Date</label><br>
                    <input type="date" id="contract_rate_start_date" name="contract_rate_start_date" onchange="Onclickreadonly()" required>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label for="contract_rate_end_date">Contract Rate End Date</label><br>
                    <input type="date" id="contract_rate_end_date" name="contract_rate_end_date" readonly required>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <label for="Lastest_Introduce_By">แนะนำล่าสุดโดย / Lastest Introduce By</label><br>
                    <input type="text" id="Lastest_Introduce_By" name="Lastest_Introduce_By" maxlength="70" required>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="custom-accordion">
                        <input type="checkbox" id="trigger1" />
                        <label for="trigger1">ติดต่อ / Contact</label>
                        <div class="custom-accordion-content">
                            <!-- <div class="row col-lg-12 col-md-12 col-sm-12">
                                <div class="col-lg-10 col-md-8 col-sm-0"></div>
                                <div class="col-lg-2 col-md-4 col-sm-12"><input type="text" id="AProfile_ID" name="Profile_ID" maxlength="70" required value="{{$A_Profile}}" disabled></div>
                            </div> -->
                            <div class="row justify-content-between">
                                <div class="col-lg-auto col-md-auto col-sm-auto col-6">
                                    <label class="labelcontact" for="">Title</label>
                                    <select name="Mprefix" id="Mprefix" class="form-select">
                                        <option value=""></option>
                                        @foreach($Mprefix as $item)
                                        <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-auto col-md-auto col-sm-auto col-6">
                                    <label class="labelcontact" for="">&nbsp;</label>
                                    <input type="text" id="AProfile_ID" name="Profile_ID" maxlength="70" required value="{{$A_Profile}}" disabled>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label class="labelcontact" for="">First Name</label>
                                    <input type="text" id="first_nameAgent" name="first_nameAgent" maxlength="70" required>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label class="labelcontact" for="">Last Name</label>
                                    <input type="text" id="last_nameAgent" name="last_nameAgent" maxlength="70" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <label class="labelcontact" for="">Country</label>
                                    <select name="countrydataA" id="countrySelectA" class="form-select" onchange="showcityAInput()" required>
                                        <option value="Thailand">ประเทศไทย</option>
                                        <option value="Other_countries">ประเทศอื่นๆ</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12" id="cityInputA" style="display:none;">
                                    <label class="labelcontact" for="">City</label>
                                    <input type="text" id="cityA" name="cityA">
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12" id="citythaiA" style="display:block;">
                                    <label class="labelcontact" for="">City</label>
                                    <select name="provinceAgent" id="provinceAgent" class="select2" onchange="provinceA()" style="width: 100%;" required>
                                        <option value=""></option>
                                        @foreach($provinceNames as $item)
                                        <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <label class="labelcontact" for="">Amphures</label>
                                    <select name="amphuresA" id="amphuresA" class="select2" onchange="amphuresAgent()" style="width: 100%;" required>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <label class="labelcontact" for="">Tambon</label>
                                    <select name="TambonA" id="TambonA" class="select2" onchange="TambonAgent()" style="width: 100%;" required>
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <label class="labelcontact" for="">Zip code</label>
                                    <select name="zip_codeA" id="zip_codeA" class="select2" style="width: 100%;" required>
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <label class="labelcontact" for="">Email</label>
                                    <input class="email" type="email" id="EmailAgent" name="EmailAgent" style="width: 100%;" maxlength="70" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <label class="labelcontact" for="">Address</label>
                                    <textarea type="text" id="addressAgent" name="addressAgent" rows="3" cols="25" class="textarea" aria-label="With textarea" required></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label class="labelcontact" style="width: 50%; float:left;" for="Company_Phone" class="flex-container">
                                        Phone number
                                    </label>
                                    <button style="float: right; margin-bottom:1%;" type="button" class="add-phone" id="add-phone">เพิ่มหมายเลขโทรศัพท์</button>
                                    <div id="phone-inputs-container">
                                        <div class="phone-input-group input-group">
                                            <div class="input-container">
                                                <input type="text" name="phone[]" id="phone-main"class="form-control" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
                                                <button type="button" class="remove-input" id="remove-input" disabled>ลบ</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="add-phone-orther"></div>
                                </div>
                            </div>
                        </div>
                    </div>
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
            {{-- <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12"><br>
                    <ul class="nav nav-tabs">
                        <li li class="nav-item">
                            <a class="nav-link" onclick="openHistory(event, 'Summary_Visit_info')" id="defaultOpen">Summary Visit</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" onclick="openHistory(event, 'Lastest_Visit_info')">Lastest Visit </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " onclick="openHistory(event, 'Billing Folio info')">Billing Folio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" onclick="openHistory(event, 'Latest Freelancer By')">Latest Freelancer By</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " onclick="openHistory(event, 'Lastest Freelancer Commission')">Lastest Freelancer Commission</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " onclick="openHistory(event, 'Contract Rate Document')">Contract Rate Document</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " onclick="openHistory(event, 'User logs')">User logs</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div id="Summary_Visit_info" class="tabcontent">
                <table id="example6" class="masterdisplay" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>หัวตาราง 1</th>
                            <th>หัวตาราง 2</th>
                            <th>หัวตาราง 3</th>
                            <th>หัวตาราง 4</th>
                            <th>หัวตาราง 5</th>
                            <th>หัวตาราง 1</th>
                            <th>หัวตาราง 2</th>
                            <th>หัวตาราง 3</th>
                            <th>หัวตาราง 4</th>
                            <th>หัวตาราง 5</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr class="my-row">
                            <td data-label="ข้อมูล 1">ข้อมูล 1</td>
                            <td data-label="ข้อมูล 2">ข้อมูล 2</td>
                            <td data-label="ข้อมูล 3">ข้อมูล 3</td>
                            <td data-label="ข้อมูล 4">ข้อมูล 4</td>
                            <td data-label="ข้อมูล 5">ข้อมูล 5</td>
                            <td data-label="ข้อมูล 1">ข้อมูล 1</td>
                            <td data-label="ข้อมูล 2">ข้อมูล 2</td>
                            <td data-label="ข้อมูล 3">ข้อมูล 3</td>
                            <td data-label="ข้อมูล 4">ข้อมูล 4</td>
                            <td data-label="ข้อมูล 5">ข้อมูล 5</td>
                    </tbody>
                </table>
            </div>
            <div id="Lastest_Visit_info" class="tabcontent">
                <table id="example6" class="masterdisplay">
                    <thead>
                        <tr>
                            <th>หัวตาราง 1</th>
                            <th>หัวตาราง 2</th>
                            <th>หัวตาราง 3</th>
                            <th>หัวตาราง 4</th>
                            <th>หัวตาราง 5</th>
                            <th>หัวตาราง 1</th>
                            <th>หัวตาราง 2</th>
                            <th>หัวตาราง 3</th>
                            <th>หัวตาราง 4</th>
                            <th>หัวตาราง 5</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr class="my-row">
                            <td data-label="ข้อมูล 1">ข้อมูล 1</td>
                            <td data-label="ข้อมูล 2">ข้อมูล 2</td>
                            <td data-label="ข้อมูล 3">ข้อมูล 3</td>
                            <td data-label="ข้อมูล 4">ข้อมูล 4</td>
                            <td data-label="ข้อมูล 5">ข้อมูล 5</td>
                            <td data-label="ข้อมูล 1">ข้อมูล 1</td>
                            <td data-label="ข้อมูล 2">ข้อมูล 2</td>
                            <td data-label="ข้อมูล 3">ข้อมูล 3</td>
                            <td data-label="ข้อมูล 4">ข้อมูล 4</td>
                            <td data-label="ข้อมูล 5">ข้อมูล 5</td>
                    </tbody>
                </table>
            </div> --}}


    </div>
</div>




</form>

<script>
    $(document).ready(function() {
        new DataTable('#example6 , #example7', {

        });
    });

    $(document).ready(function() {
        $('#trigger1').change(function() {
            var isChecked = $(this).is(':checked');
            var valueToSend = isChecked ? '1' : '';
            console.log(valueToSend); // ถ้า checkbox ถูกเลือก จะส่งค่า '1' ไป ถ้าไม่ถูกเลือก จะไม่ส่งค่าไป
            if (isChecked) {
                // ตรวจสอบว่ามีค่าใน Company_Name หรือไม่
                var Company_Name = $('#Company_Name').val();
                var Branch = $('#Branch').val();
                if (Company_Name.trim() === '') {

                    alert('กรุณากรอกข้อมูล Company Name ก่อน');
                    // ยกเลิกการเลือก checkbox ในกรณีที่ไม่มีค่าใน Company_Name
                    $(this).prop('checked', false);
                    return;
                }
            }
            $.ajax({
                url: '/Company/check/company', // Your Laravel route
                type: 'POST',
                data: {
                    Company_Name: Company_Name,
                    Branch: Branch,
                    _token: '{{ csrf_token() }}' // Ensure CSRF token is included
                },
                success: function(response) {

                    if (response && response.representative !== null) {
                        $('#AProfile_ID').val(response.representative.Profile_ID).prop('disabled', true);
                        $('#Mprefix').val(response.representative.prefix).trigger('change').prop('disabled', true);
                        $('#first_nameAgent').val(response.representative.First_name).prop('disabled', true);
                        $('#last_nameAgent').val(response.representative.Last_name).prop('disabled', true);
                        $('#countrySelectA').val(response.representative.Country).trigger('change').prop('disabled', true);
                        $('#provinceAgent').val(response.representative.City).trigger('change').prop('disabled', true);
                        $('#amphuresA').val(response.representative.Amphures).trigger('change').prop('disabled', true);

                        $('#TambonA').val(response.representative.Tambon).trigger('change').prop('disabled', true);
                        $('#zip_codeA').val(response.representative.Zip_Code).trigger('change').prop('disabled', true);
                        $('#addressAgent').val(response.representative.Address).prop('disabled', true);
                        $('#EmailAgent').val(response.representative.Email).prop('disabled', true);
                        $('#add-phone').prop('disabled', true);
                        $('#add-phone-orther').children().remove().end();
                       console.log(response.phone);
                       $.each(response.phone, function(key, val) {
                        // Disable the first phone input and set its value
                        if (key == 0) {
                            $('#phone-main').val(val.Phone_number).prop('disabled', true);
                            console.log(val.Phone_number);
                        } else {
                            // Create a new input element for additional phone numbers
                            var phoneInput = $('<input type="text" id="phone-' + key + '" name="phone[]" value="' + val.Phone_number + '" class="form-control" maxlength="10">');
                            phoneInput.prop('disabled', true);
                            // Disable the input fieldremove-input
                            $('#add-phone-orther').append(phoneInput);
                        }

                        // Optionally add a delete button for each phone input (uncomment if needed)
                        // $('#add-phone-orther').append('<button type="button" id="btn-delete-' + key + '" class="remove-phone" onclick="dele_phone(' + key + ')">ลบ</button>');
                    });


                        //console.log(response.representative);

                    } else if (response && response.CompanyCountA) {
                        $('#AProfile_ID').val(response.CompanyCountA).prop('disabled', true);
                    } else if (response && response.representative == null) {
                        console.log(response.representative);
                        $('#AProfile_ID').val('1');
                        $('#Mprefix').val('').prop('disabled', false);
                        $('#first_nameAgent').val('').prop('disabled', false);
                        $('#last_nameAgent').val('').prop('disabled', false);
                        $('#countrySelectA').val('').prop('disabled', false);
                        $('#provinceAgent').val('').prop('disabled', false);
                        $('#amphuresA').val('').prop('disabled', false);
                        $('#TambonA').val('').prop('disabled', false);
                        $('#zip_codeA').val('').prop('disabled', false);
                        $('#addressAgent').val('').prop('disabled', false);
                        $('#EmailAgent').val('').prop('disabled', false);
                        $('#add-phone').val('').prop('disabled', false);
                        $('#phone-main').val('').prop('disabled', false);
                        // Clear previous inputs and buttons
                        $('#add-phone-orther').empty();

                        $.each(response.phone, function(keyother, val) {
                            if (keyother == 1) {
                                $('#phone-main').val(val).prop('disabled', false);
                            } else {
                                var phoneInput = $('<input type="text" id="phone-' + keyother + '" name="phone[]" value="' + val + '" class="form-control" maxlength="10">');
                                $('#add-phone-orther').append(phoneInput);
                                $('#add-phone-orther').append('<button type="button" id="btn-delete-' + keyother + '" onclick="dele_phone(' + keyother + ')">ลบ</button>');
                            }
                        });

                    }
                },
                error: function(xhr) {
                    // Handle error response
                    console.error(xhr.responseText);
                }
            });

        });
    });
</script>
<script>
    window.onload = function() {
        document.getElementById("defaultOpen").click(); // เปิดแท็บ Summary Visit info เมื่อหน้าโหลด
        document.getElementById("lastestOpen").click(); // เปิดแท็บ Lastest Visit info เมื่อหน้าโหลด
    }

    function dele_phone(id) {
        // หากต้องการลบ input field ที่อยู่ใกล้เคียงกับปุ่ม "ลบ" ให้ใช้ jQuery เพื่อหา element ที่ต้องการลบ
        $('#phone-' + id).remove().end();
        $('#btn-delete-' + id).remove().end();
    }



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
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Please select an option"
        });

    });


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
        } else if (countrySelectA.value === "Thailand") {
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
    //---------------------------------------------------2------------------------------
    function provinceA() {
        var provinceAgent = $('#provinceAgent').val();
        jQuery.ajax({
            type: "GET",
            url: "{!! url('/Company/amphuresA/" + provinceAgent + "') !!}",
            datatype: "JSON",
            async: false,
            success: function(result) {
                jQuery('#amphuresA').children().remove().end();

                $('#amphuresA').append(new Option('', ''));
                jQuery.each(result.data, function(key, value) {
                    var amphuresA = new Option(value.name_th, value.id);
                    //console.log(amphuresA);
                    $('#amphuresA').append(amphuresA);
                    console.log(amphuresA);
                });
            },
        })

    }

    function amphuresAgent() {
        var amphuresAgent = $('#amphuresA').val();
        console.log(amphuresAgent);
        $.ajax({
            type: "GET",
            url: "{!! url('/Company/TambonA/" + amphuresAgent + "') !!}",
            datatype: "JSON",
            async: false,
            success: function(result) {
                // console.log(result);
                jQuery('#TambonA').children().remove().end();
                $('#TambonA').append(new Option('', ''));
                jQuery.each(result.data, function(key, value) {
                    var TambonA = new Option(value.name_th, value.id);
                    $('#TambonA').append(TambonA);
                    // console.log(TambonA);
                });
            },
        })
    }

    function TambonAgent() {
        var TambonAgent = $('#TambonA').val();
        console.log(TambonAgent);
        $.ajax({
            type: "GET",
            url: "{!! url('/Company/districtsA/" + TambonAgent + "') !!}",
            datatype: "JSON",
            async: false,
            success: function(result) {
                console.log(result);
                jQuery('#zip_codeA').children().remove().end();
                //console.log(result);
                $('#zip_codeA').append(new Option('', ''));
                jQuery.each(result.data, function(key, value) {
                    var zip_codeA = new Option(value.zip_code, value.zip_code);
                    $('#zip_codeA').append(zip_codeA);
                    //console.log(zip_codeA);
                });
            },
        })
    }
    //Company_Phone
    document.addEventListener("DOMContentLoaded", function() {
        const addButton = document.getElementById('add-input');
        const inputsContainer = document.getElementById('inputs-container');
        let inputCount = 1;

        function toggleButtons() {
            const removeButtons = inputsContainer.querySelectorAll('.remove-input');
            removeButtons.forEach(btn => btn.disabled = (inputCount === 1));
        }

        function createInputGroup() {
            const inputGroup = document.createElement('div');
            inputGroup.classList.add('input-group');

            inputGroup.innerHTML = `
            <div class="input-container">
                <input type="text" name="phone_company[]" class="form-control" maxlength="10"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
                <button type="button" class="remove-input">ลบ</button>
            </div>
        `;

            inputGroup.querySelector('.remove-input').addEventListener('click', function() {
                inputsContainer.removeChild(inputGroup);
                inputCount--;
                toggleButtons();
            });

            return inputGroup;
        }

        addButton.addEventListener('click', function() {
                inputsContainer.appendChild(createInputGroup());
                inputCount++;
                toggleButtons();
        });

        inputsContainer.querySelector('.remove-input').addEventListener('click', function() {
            inputsContainer.querySelector('.input-group').remove();
            inputCount--;
            toggleButtons();
        });

        toggleButtons(); // Initialize button states
    });


    //fax
    document.addEventListener("DOMContentLoaded", function() {
        const addButton = document.getElementById('add-fax');
        const faxContainer = document.getElementById('fax-container');
        let faxCount = 1;

        function toggleButtons() {
            const removeButtons = faxContainer.querySelectorAll('.remove-input');
            removeButtons.forEach(btn => btn.disabled = (faxCount === 1));
        }

        function createFaxGroup() {
            const faxGroup = document.createElement('div');
            faxGroup.classList.add('fax-group', 'input-group');

            faxGroup.innerHTML = `
            <div class="input-container">
                <input type="text" name="fax[]" class="form-control" maxlength="11"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);" required>
                <button type="button" class="remove-input">ลบ</button>
            </div>
        `;

            faxGroup.querySelector('.remove-input').addEventListener('click', function() {
                faxContainer.removeChild(faxGroup);
                faxCount--;
                toggleButtons();
            });

            return faxGroup;
        }

        addButton.addEventListener('click', function() {
                faxContainer.appendChild(createFaxGroup());
                faxCount++;
                toggleButtons();
        });

        faxContainer.querySelector('.remove-input').addEventListener('click', function() {
            faxContainer.querySelector('.fax-group').remove();
            faxCount--;
            toggleButtons();
        });

        toggleButtons(); // Initialize button states
    });
    //phene_contract
    document.addEventListener("DOMContentLoaded", function() {
        const addPhoneButton = document.getElementById('add-phone');
        const phoneInputsContainer = document.getElementById('phone-inputs-container');
        let phoneInputCount = 1;

        function togglePhoneButtons() {
            const removeButtons = phoneInputsContainer.querySelectorAll('.remove-input');
            removeButtons.forEach(button => button.disabled = (phoneInputCount === 1));
        }

        function createPhoneInputGroup() {
            const phoneInputGroup = document.createElement('div');
            phoneInputGroup.classList.add('input-group');

            phoneInputGroup.innerHTML = `
            <div class="input-container">
                <input type="text" name="phone[]" class="form-control" maxlength="10"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
                <button type="button" class="remove-input">ลบ</button>
            </div>
        `;

            phoneInputGroup.querySelector('.remove-input').addEventListener('click', function() {
                phoneInputsContainer.removeChild(phoneInputGroup);
                phoneInputCount--;
                togglePhoneButtons();
            });

            return phoneInputGroup;
        }

        addPhoneButton.addEventListener('click', function() {
                phoneInputsContainer.appendChild(createPhoneInputGroup());
                phoneInputCount++;
                togglePhoneButtons();
        });

        phoneInputsContainer.querySelector('.remove-input').addEventListener('click', function() {
            phoneInputsContainer.querySelector('.input-group').remove();
            phoneInputCount--;
            togglePhoneButtons();
        });

        togglePhoneButtons(); // Initialize button states
    });


    function Onclickreadonly() {
        var startDate = document.getElementById('contract_rate_start_date').value;
        if (startDate !== '') {
            // หากมีค่า กำหนด input field ที่มี id เป็น contract_rate_end_date เป็น readonly
            document.getElementById('contract_rate_end_date').readOnly = false;
        } else {
            // หากไม่มีค่า กำหนด input field ที่มี id เป็น contract_rate_end_date เป็น readonly
            document.getElementById('contract_rate_end_date').readOnly = true;
        }
    }
</script>
<script>
     function confirmSubmit(event) {
        event.preventDefault(); // Prevent the form from submitting

        var Company_Name = $('#Company_Name').val();
        var Branch = $('#Branch').val();

        // Check if Company_Name or Branch is empty
        if (!Company_Name || !Branch || !Company_type || !booking_channel || !address
            || !Mmarket || !addressAgent || !EmailAgent || !Lastest_Introduce_By || !contract_rate_end_date || !contract_rate_start_date
            || !Discount_Contract_Rate || !Taxpayer_Identification || !Company_Website || !Company_Email
        ) {
            // Display error message using Swal
            Swal.fire({
                title: "ข้อมูลไม่ครบถ้วน",
                text: "กรุณากรอกข้อมูลบริษัทและสาขาให้ครบถ้วน",
                icon: "error",
                confirmButtonText: "ตกลง",
                confirmButtonColor: "#dc3545"
            });
            return; // Stop further execution
        }

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
