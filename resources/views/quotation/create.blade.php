@extends('layouts.test')

@section('content')

<style>
    .container {
        margin-top: 40px;
        background-color: white;
        padding: 2% 2%;
        overflow-x: hidden;
    }
    .btn-animate-submit {
        display: inline-block;
        padding: 10px 20px;
        font-size: 16px;
        color: white;
        background-color: #2D7F7B;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.3s;
    }

    .btn-animate-submit:hover {
        background-color: #2D7F7B;
        transform: scale(1.1);
    }

    .btn-animate-submit:active {
        transform: scale(0.9);
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

    .select2-container {
        width: 100% !important;
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
        font-size: 24px;
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

    .image-container .titleh1 {
        font-size: 1.2em;
        font-weight: bold;
        margin-bottom: 10px;
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
    }
    .input-group-text-Adult {
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        width: 80px;
        height: 50px;
        border: 1px solid #ccc;
        background-color: #e8e8e8; /* เพิ่มเซมิโคลอนที่นี่ */
        border-radius: 4px;
    }

    .input-group-text.custom-span-1 {
        width: 15px; /* ความกว้างที่ต้องการ */
        height: 50px; /* ความสูงที่ต้องการ */
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        background-color: #e8e8e8;
    }

    .datestyle {
        height: 50px !important;
        background-color: white;
    }
    .quotation-container {
        display: flex;
        flex-direction: row;
        align-items: center;
        text-align: center;  /* ปรับความสูงตามที่ต้องการ */
    }

    .quotation-number {
        font-size: 36px;
        margin: 0;
        margin-right: 5px;
        display: table-cell;
        vertical-align: middle;
        color:#109699;
    }
    .quotation-id {
        font-size: 18px;
        margin: 0;
        margin-right: 5px;
        display: table-cell;
        vertical-align: middle;
    }
    @media (max-width: 768px) {
        .quotation-container {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
    }
    .Customer-Information-container {
        display: flex;
        flex-direction: row;
        align-items: center;
        text-align: left;
    }

    .Customer-Information-container  .info {
        margin-top: 0;
    }

    .Customer-Information-container  .info p {
        margin: 5px 0;
    }
    .row p {
        margin: 0; /* ลบ margin ที่เกิดจากการใช้งานของบราวเซอร์ */
    }
    .Contact-Information-container {
        display: flex;
        flex-direction: row;
        text-align: left;
    }

    .Contact-Information-container  .info {
        margin-top: 0;
    }

    .Contact-Information-container  .info p {
        margin: 5px 0;
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
    <div class=" col-12">
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
            <div class="col-lg-2 col-md-12 col-sm-12"></div>
            <div class="col-lg-2 col-md-12 col-sm-12 quotation-container">
                <div class="row">
                    <p class="quotation-number">Quotation </p>
                    <p class="quotation-id ">{{$Quotation_ID}}</p>
                    <input type="hidden" id="Quotation_ID" name="Quotation_ID" value="{{$Quotation_ID}}">
                    <p class="quotation-id">Issue date : {{$Issue_date}}</p>
                    <p class="quotation-id">Valid Until : {{$Valid_Until}}</p>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="titleh1 col-7 mt-5">
            <h1>Quotation</h1>
        </div>
    </div>
    <form id="myForm" action="{{route('MEvent.save')}}" method="POST">
        {!! csrf_field() !!}
        <input type="hidden" id="Quotation_ID" name="Quotation_ID" value="{{$Quotation_ID}}">
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


        <hr class="mt-3 my-3" style="border: 1px solid #000">
        <div class="col-12 mt-3">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label class="Select_a_date" for="">Select a date</label>
                    <select name="Select_a_date" id="Select_a_date" class="form-select" required onchange="toggleDateInput()">
                        <option value="No_date" id="No_date">ไม่ระบุวันที่ (Date not specified)</option>
                        <option value="Yes_date" id="Yes_date">ระบุวันที่ (Specify date)</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label for="Check_In_Date">Check in date</label><br>
                    <div class="datestyle"><input type="date" id="Check_In_Date" name="Check_In_Date" readonly  onchange="Onclickreadonly()"></div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label for="Check_Out_Date">Check out date</label><br>
                    <div class="datestyle">
                    <input type="date" id="Check_Out_Date" name="Check_Out_Date" readonly>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <label for="">จำนวน</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control mt-2" name="Day" placeholder="จำนวนวัน" aria-label="Username" >
                        <span class="input-group-text custom-span-1" id="basic-addon2"  >Day</span>
                        <input type="text" class="form-control mt-2" name="Night" placeholder="จำนวนคืน" aria-label="Server" >
                        <span class="input-group-text custom-span-1" id="basic-addon2">Night</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label  for="">Adult</label>
                    <div class="input-group mb-3" >
                        <input type="text" class="form-control mt-2" name="Adult" placeholder="จำนวนผู้ใหญ่" aria-describedby="basic-addon2">
                        <span class="input-group-text-Adult mt-2" id="basic-addon2" >Person</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label  for="">Children</label>
                    <div class="input-group ">
                        <input type="text" class="form-control mt-2" name="Children" placeholder="จำนวนเด็ก" aria-describedby="basic-addon2">
                        <span class="input-group-text-Adult mt-2" id="basic-addon2" >Person</span>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <label  for="">Max discount </label> <label style="color: #dc3545">(Your permission has max discount 10.00 %)</label>
                    <div class="input-group ">
                        <input type="text" class="form-control" name="Max_discount" aria-describedby="basic-addon2">
                        <span class="input-group-text-Adult" id="basic-addon2" >%</span>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <label  for="">Company Rate Code</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text-Adult " id="basic-addon2" >DC</span>
                        <input type="text" class="form-control" name="Company_Rate_Code" aria-label="Amount (to the nearest dollar)">
                        <span class="input-group-text-Adult " id="basic-addon2" >%</span>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label class="Freelancer_member" for="">Freelance Affiliate</label>
                    <select name="Freelancer_member" id="Freelancer_member" class="select2">
                        <option value=""></option>
                        @foreach($Freelancer_member as $item)
                            <option value="{{ $item->Profile_ID }}">{{ $item->First_name }}{{ $item->Last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label  for="">Company Commission Rate Code</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control " name="Company_Commission_Rate_Code" aria-describedby="basic-addon2">
                        <span class="input-group-text-Adult " id="basic-addon2" >%</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label  for="">Place</label>
                    <input type="text" class="form-control " aria-label="Amount (to the nearest dollar)">
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label  for="">Event Format</label>
                    <select name="Mevent" id="Mevent" class="select2" >
                        <option value=""></option>
                        @foreach($Mevent as $item)
                            <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label  for="">Vat Type</label>
                    <select name="Vat_Type" id="Vat_Type" class="select2" >
                        <option value="VAT_IN">VAT IN</option>
                        <option value="VAT_OUT">VAT OUT</option>
                    </select>
                </div>
                <div class="col-lg-12 col-md-6 col-sm-12 d-flex justify-content-center">
                    <button type="submit" class="btn btn-animate-submit" >
                        {{ __('+ Product') }}
                    </button>
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

}

</script>
@endsection
