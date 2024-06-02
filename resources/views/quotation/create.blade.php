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
    .input-group-text {
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
    .titleh2{
        font-size: 26px;
    }
    .titleh1 {
        font-size: 32px;
    }
    .image-container {
        display: flex;
        justify-content: flex-start;
    }
    .image-container img {
        width: 25%;
    }
    .input-group-text.custom-span {
        width: 15px; /* ความกว้างที่ต้องการ */
        height: 50px; /* ความสูงที่ต้องการ */
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }
    .input-group-text.custom-span-1 {
        width: 15px; /* ความกว้างที่ต้องการ */
        height: 51px; /* ความสูงที่ต้องการ */
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }
    .input-group-text.custom-span-2 {
        width: 50px; /* ความกว้างที่ต้องการ */
        height: 49px; /* ความสูงที่ต้องการ */
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }
    .datestyle {
        height: 50px !important;
        background-color: white;
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

<div class="container">
    <div class="row">
        <div class="col image-container">
            <img src="{{ asset('assets2/images/logo_crop.png') }}"/>
            <div class="mt-5 ml-2">
                <p class="titleh1">Together Resort Limited Partnership</p>
                <p>168 Moo 2 Kaengkrachan Phetchaburi 76170</p>
                <p>Tel : 032-708-888,098-393-944-4 Fax : </p>
                <p>Email : reservation@together-resort.com Website : www.together-resort.com</p>
            </div>
        </div>
        <div class="col-5">
            <p style="font-size: 18px; float: right;">Quotation No : {{$Quotation_ID}}</p><input type="hidden" id="Quotation_ID" name="Quotation_ID" value="{{$Quotation_ID}}">
        </div>
    </div>
    <div>
        <div class="titleh1 col-7 mt-5">
            <h1>Quotation</h1>
        </div>
    </div>
    <form id="myForm" action="{{route('Company.save')}}" method="POST">
        {!! csrf_field() !!}
        <div class="col-12 mt-3">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <label class="labelcontact" for="">Customer Company</label>
                    <select name="Company" id="Company" class="select2" onchange="companyContact()">
                        <option value=""></option>
                        @foreach($Company as $item)
                            <option value="{{ $item->Profile_ID }}">{{ $item->Company_Name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <label class="labelcontact" for="">Customer Contact</label>
                    <select name="Company_Contact" id="Company_Contact" class="select2">
                        <option value=""></option>
                    </select>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <a style="font-size: 18px; float: right;"  onclick="window.location.href='{{ route('Company.index') }}'">+Add Company</a>
                </div>
            </div>
        </div>
        <div class="col-12 mt-3">
            <div class="row">
                <div class="titleh2 col-7 mt-5 my-3">
                    <h1>Customer Information</h1>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <label class="Company_name" for="">Company Name</label>
                    <input type="text" id="Company_name" name="Company_name" maxlength="70" disabled>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label class="Company_contact" for="">Contact Name</label>
                    <input type="text" id="Company_contact" name="Company_contact" maxlength="70" disabled>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label class="Contact_Phone" for="">Contact Phone</label>
                    <input type="text" id="Contact_Phone" name="Contact_Phone" maxlength="70" disabled>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <label class="Company_Address" for="">Company Address</label>
                    <textarea type="text" id="Company_Address" name="Company_Address" rows="3" cols="25" class="textarea" aria-label="With textarea" disabled></textarea>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label class="Company_Email" for="">Company Email</label>
                    <input type="text" id="Company_Email" name="Company_Email" maxlength="70" disabled>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label class="Company_Website" for="">Company Website</label>
                    <input type="text" id="Company_Website" name="Company_Website" maxlength="70" disabled>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label class="Company_Number" for="">Company Number</label>
                    <input type="text" id="Company_Number" name="Company_Number" maxlength="70" disabled>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label class="Company_Fax" for="">Company Fax</label>
                    <input type="text" id="Company_Fax" name="Company_Fax" maxlength="70" disabled>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <label class="Company_Fax" for="">Valid</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control mt-2" placeholder="Recipient's username"  aria-label="Recipient's username" aria-describedby="basic-addon2">
                        <span class="input-group-text custom-span" id="basic-addon2" style="">Days</span>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <label class="Taxpayer_Identification" for="">Taxpayer Identification Number</label>
                    <input type="text" id="Taxpayer_Identification" name="Taxpayer_Identification" maxlength="70" disabled>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label class="Select_a_date" for="">Select a date</label>
                    <select name="Select_a_date" id="Select_a_date" class="form-select" required onchange="toggleDateInput()">
                        <option value="No_date" id="No_date">ไม่ระบุวันที่ (Date not specified)</option>
                        <option value="Yes_date" id="Yes_date">ระบุวันที่ (Specify date)</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label for="contract_rate_start_date">Contract Rate Start Date</label><br>
                    <div class="datestyle"><input type="date" id="contract_rate_start_date" name="contract_rate_start_date" readonly  onchange="Onclickreadonly()"></div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label for="contract_rate_end_date">Contract Rate End Date</label><br>
                    <div class="datestyle">
                    <input type="date" id="contract_rate_end_date" name="contract_rate_end_date" readonly>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <label for="">จำนวน</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control mt-2" placeholder="Username" aria-label="Username">
                        <span class="input-group-text custom-span-1" id="basic-addon2">Day</span>
                        <input type="text" class="form-control mt-2" placeholder="Server" aria-label="Server">
                        <span class="input-group-text custom-span-1" id="basic-addon2">Night</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label  for="">Adult</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control mt-2" name="Adult" aria-describedby="basic-addon2">
                        <span class="input-group-text custom-span-2" id="basic-addon2" style="">Person</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label  for="">Children</label>
                    <div class="input-group ">
                        <input type="text" class="form-control mt-2" name="Children" aria-describedby="basic-addon2">
                        <span class="input-group-text custom-span-2" id="basic-addon2" style="">Person</span>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <label  for="">Max discount (Your permission has max discount 10.00 %)</label>
                    <div class="input-group ">
                        <input type="text" class="form-control mt-2" name="Max_discount" aria-describedby="basic-addon2">
                        <span class="input-group-text custom-span-2" id="basic-addon2" style="">%</span>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <label  for="">Company Rate Code</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text custom-span-2">DC</span>
                        <input type="text" class="form-control mt-2" aria-label="Amount (to the nearest dollar)">
                        <span class="input-group-text custom-span-2">%</span>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <label class="labelcontact" for="">Freelance Affiliate</label>
                    <select name="Company" id="Company" class="select2">
                        <option value="">ไม่มี</option>
                        {{-- @foreach($Company as $item)
                            <option value="{{ $item->Profile_ID }}">{{ $item->Company_Name }}</option>
                        @endforeach --}}
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label  for="">Company Commission Rate Code</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control mt-2" name="Adult" aria-describedby="basic-addon2">
                        <span class="input-group-text custom-span-2" id="basic-addon2" style="">%</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label  for="">Place</label>
                    <input type="text" class="form-control mt-2" aria-label="Amount (to the nearest dollar)">
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label  for="">Event Format</label>
                    <select name="Company" id="Company" class="select2" >
                        <option value=""></option>
                        {{-- @foreach($Company as $item)
                            <option value="{{ $item->Profile_ID }}">{{ $item->Company_Name }}</option>
                        @endforeach --}}
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label  for="">Vat Type</label>
                    <select name="Vat_Type" id="Vat_Type" class="select2" >
                        <option value="VAT_IN">VAT IN</option>
                        <option value="VAT_OUT">VAT OUT</option>
                    </select>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
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
    function toggleDateInput() {
        var selectElement = document.getElementById('Select_a_date');
        var dateInput = document.getElementById('contract_rate_start_date');
        if (selectElement.value === 'Yes_date') {
            dateInput.removeAttribute('readonly');
        } else {
            dateInput.setAttribute('readonly', true);
        }
    }

    function companyContact() {
    var companyID = $('#Company').val();
    console.log(companyID);
    jQuery.ajax({
        type: "GET",
        url: "{!! url('/Quotation/create/company/Contact/" + companyID + "') !!}",
        datatype: "JSON",
        async: false,
        success: function(result) {
            jQuery('#Company_Contact').children().remove().end();
            $('#Company_Contact').append(new Option('', ''));
            jQuery.each(result.data, function(key, value) {
                var optionText = `${value.First_name} ${value.Last_name}`; // รวมชื่อกับนามสกุล
                var optionValue = value.Profile_ID; // ใช้ Profile_ID เป็นค่า
                var option = new Option(optionText, optionValue);
                $('#Company_Contact').append(option);
                console.log(option);
            });
        },
        error: function(xhr, status, error) {
            console.error("AJAX request failed: ", status, error);
        }
    });
    jQuery.ajax({
        type: "GET",
        url: "{!! url('/Quotation/create/company/" + companyID + "') !!}",
        datatype: "JSON",
        async: false,
        success: function(response) {
            console.log(response.Company);
            console.log(response.Contact_name);
            console.log(response.Company.Company_Name);
            var fullName = `${response.Contact_name.First_name} ${response.Contact_name.Last_name}`;
            console.log(fullName);
            console.log(response.company_fax);
            $('#Company_name').val(response.Company.Company_Name).prop('disabled', true);
            $('#Company_contact').val(fullName).prop('disabled', true);
            $('#Contact_Phone').val(response.Contact_phone.Phone_number).prop('disabled', true);
            $('#Contact_Phone').mask('000-000-0000');
            $('#Company_Address').val(response.Company.Address).prop('disabled', true);
            $('#Company_Email').val(response.Company.Company_Email).prop('disabled', true);
            $('#Company_Website').val(response.Company.Company_Website).prop('disabled', true);
            $('#Company_Number').val(response.company_phone.Phone_number).prop('disabled', true);
            $('#Company_Number').mask('000-000-0000');
            $('#Company_Fax').val(response.company_fax.Fax_number).prop('disabled', true);
            $('#Taxpayer_Identification').val(response.Company.Taxpayer_Identification).prop('disabled', true);
        },
        error: function(xhr, status, error) {
        console.error("AJAX request failed: ", status, error);
        }
    });
}

</script>
@endsection
