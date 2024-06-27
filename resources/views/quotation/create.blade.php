@extends('layouts.test')
@section('content')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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
    .calendar-icon {
        background: #fff no-repeat center center;
        width: 50px; /* หรือขนาดที่คุณต้องการ */
        height: 50px;
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
<form id="myForm" action="{{route('MEvent.save')}}" method="POST">
    {!! csrf_field() !!}
<div class="container-fluid border rounded-3 p-5 mt-3 bg-white" style="width: 98%;">
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
            <div class="col-lg-1 col-md-12 col-sm-12"></div>
            <div class="col-lg-3 col-md-12 col-sm-12 quotation-container">
                <div class="row">
                    <p class="quotation-number">Quotation</p>
                    <p class="quotation-id ">{{$Quotation_ID}}</p>
                    <input type="hidden" id="Quotation_ID" name="Quotation_ID" value="{{$Quotation_ID}}">
                    <div id="reportrange1" style="background: #fff; cursor: pointer; padding: 5px 10px; width: 100%;" >
                        <div class="col-12 ">
                            <div class="row">
                                <div class="col-6"style="display:flex; justify-content:right; align-items:center;">
                                    <span>Issue Date:</span>
                                </div>
                                <div class="col-6">
                                    <input type="text" id="datestart" name="IssueDate" style="text-align: left;"readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 ">
                            <div class="row">
                                <div class="col-6"style="display:flex; justify-content:right; align-items:center;">
                                    <span>Expiration Date:</span>
                                </div>
                                <div class="col-lg-6">
                                    <input type="text" id="dateex" name="Expiration" style="text-align: left;"readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>

    </div>

        <div class="row">
            <div class="titleh1 col-lg-6 col-md-6 col-sm-12 mt-5">
                <h1>Quotation</h1>
            </div>
        </div>

        <input type="hidden" id="Quotation_ID" name="Quotation_ID" value="{{$Quotation_ID}}">
        <div class="col-12 mt-3">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <label class="labelcontact" for="">Customer Company</label>
                    <select name="Company" id="Company" class="select2" onchange="companyContact()" required>
                        <option value=""></option>
                        @foreach($Company as $item)
                            <option value="{{ $item->Profile_ID }}">{{ $item->Company_Name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <label class="labelcontact" for="">Customer Contact</label>
                    <select name="Company_Contact" id="Company_Contact" class="select2"required>
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
            <div class="row" >
                <div class="col-lg-4 col-md-6 col-sm-12 ">
                    <div id="reportrange" style="background: #fff; cursor: pointer; padding:10px; width: 100%;border-radius: 5px;">
                        <div class="col-lg-12 col-md-6 col-sm-12">
                            <div class="row">
                                <div class="col-lg-5 col-md-6 col-sm-12">
                                    <label  for="">Check in date</label>
                                    <input type="text" id="date2" name="Checkin" readonly >
                                </div>
                                <div class="col-lg-5 col-md-6 col-sm-12">
                                    <label  for="">Check out date</label>
                                    <input type="text" id="date3" name="Checkout" readonly>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12 mt-4"style="display:flex; justify-content:center;">
                                    <img src="{{ asset('assets2/images/calendar.png') }}" class="calendar-icon " id="calendarIcon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label for="">จำนวน</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control mt-2" name="Day" placeholder="จำนวนวัน" aria-label="Username"required >
                        <span class="input-group-text custom-span-1" id="basic-addon2"  >Day</span>
                        <input type="text" class="form-control mt-2" name="Night" placeholder="จำนวนคืน" aria-label="Server" required>
                        <span class="input-group-text custom-span-1" id="basic-addon2">Night</span>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <label  for="">Adult</label>
                    <div class="input-group mb-3" >
                        <input type="text" class="form-control mt-2" name="Adult" placeholder="จำนวนผู้ใหญ่" aria-describedby="basic-addon2"required>
                        <span class="input-group-text-Adult mt-2" id="basic-addon2" >Person</span>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-12">
                    <label  for="">Children</label>
                    <div class="input-group ">
                        <input type="text" class="form-control mt-2" name="Children" placeholder="จำนวนเด็ก" aria-describedby="basic-addon2"required>
                        <span class="input-group-text-Adult mt-2" id="basic-addon2" >Person</span>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <label  for="">Max discount </label> <label style="color: #dc3545">(Your permission has max discount 10.00 %)</label>
                    <div class="input-group ">
                        <input type="text" class="form-control" name="Max_discount" aria-describedby="basic-addon2" required>
                        <span class="input-group-text-Adult" id="basic-addon2" >%</span>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <label  for="">Company Rate Code</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text-Adult " id="basic-addon2" >DC</span>
                        <input type="text" class="form-control" name="Company_Rate_Code" aria-label="Amount (to the nearest dollar)" required>
                        <span class="input-group-text-Adult " id="basic-addon2" >%</span>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label class="Freelancer_member" for="">Freelance Affiliate</label>
                    <select name="Freelancer_member" id="Freelancer_member" class="select2" required>
                        <option value=""></option>
                        @foreach($Freelancer_member as $item)
                            <option value="{{ $item->Profile_ID }}">{{ $item->First_name }}{{ $item->Last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label  for="">Company Commission Rate Code</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control " name="Company_Commission_Rate_Code" aria-describedby="basic-addon2" required>
                        <span class="input-group-text-Adult " id="basic-addon2" >%</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label  for="">Place</label>
                    <input type="text" class="form-control " name="place" aria-label="Amount (to the nearest dollar)" required>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label  for="">Event Format</label>
                    <select name="Mevent" id="Mevent" class="select2" required>
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
<script type="text/javascript">
    $(function() {
        var start = moment();
        var end = moment().add(7, 'days');
        function cb(start, end, label) {
        if (label === 'ไม่ระบุ') {
            $('#date2').val('');
            $('#date3').val('');
        } else {
            $('#date2').val(start.format('DD/MM/YYYY'));
            $('#date3').val(end.format('DD/MM/YYYY'));
        }
    }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                '7 Days': [moment(), moment().add(7, 'days')],
                '15 Days': [moment(), moment().add(15, 'days')],
                '30 Days': [, moment().add(30, 'days')],
                'ไม่ระบุ': [null, null]
            }
        },
        cb);
        $('#calendarIcon').on('click', function() {
        $('#reportrange').daterangepicker(daterangepickerOptions, cb);
        $('#reportrange').data('daterangepicker').show();
    });
        cb(start, end);
    });
</script>

<script type="text/javascript">
    $(function() {

        var start = moment();
        var end = moment().add(7, 'days');

        function cb(start, end) {
            $('#datestart').val(start.format('DD/MM/Y'));
            $('#dateex').val(end.format('DD/MM/Y'));
        }


        $('#reportrange1').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
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
