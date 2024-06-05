 @extends('layouts.test')

@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai+Looped:wght@100;200;300;400;500;600;700;800;900&family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</head>

<body>

</body>

</html>

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
        margin-bottom: 1%;
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
        flex-direction: row;
        flex-wrap: wrap;
    }

    .phone-group {
        position: relative;
    }

    .phone-group input {
        width: 100%;
        padding-right: 40px;
        /* space for the remove button */
    }

    .remove-phone {
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        margin: auto;
        margin-right: 6%;
        margin-top: 10px;
        height: 50%;
        font-size: 14px !important;
        border: none;
        background: #dc3545;
        /* Adjust the button style as needed */
        padding: 0px 20px;
        cursor: pointer;
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
    .select2-selection--multiple {
    border-color: #ccc !important;
    height: 50px;
  }
  .datestyle input{
    height: 50px !important;
    background-color: white;
  }
</style>
    <div class="container">
        <div class="row">
            <div class="titleh1 col-lg-6 col-md-6 col-sm-6">
                <h1>GUEST</h1>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <input style="width:50%; float:right;" type="text" id="Profile_ID" name="Profile_ID" maxlength="70" required value="{{$N_Profile}}" disabled>
            </div>
        </div>

        <form action="{{route('saveguest')}}" method="POST">
            {!! csrf_field() !!}
            <div class="row" style="margin-top: 1%;">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label for="Preface">คำนำหน้า / Title</label><br>
                    <select name="Preface" id="PrefaceSelect" class="form-select">
                        <option value=""></option>
                        @foreach($prefix as $item)
                        <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12"><label for="first_name">ชื่อจริง / First Name</label><br>
                    <input type="text" placeholder="First Name" id="first_name" name="first_name" maxlength="70" required>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12"><label for="last_name">นามสกุล / Last Name</label><br>
                    <input type="text" placeholder="Last Name" id="last_name" name="last_name" maxlength="70" required>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12"><label for="country">ประเทศ / Country</label><br>
                    <select name="countrydata" id="countrySelect" class="form-select" onchange="showcityInput()">
                        <option value="Thailand">ประเทศไทย</option>
                        <option value="Other_countries">ประเทศอื่นๆ</option>
                    </select>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12" id="cityInput" style="display:none;">
                    <label for="city">จังหวัด / Province</label><br>
                    <input type="text" id="city" name="city">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12" id="citythai" style="display:block;">
                    <label for="city">จังหวัด / Province</label><br>
                    <select name="province" id="province" class="select2" onchange="select_province()">
                        <option value=""></option>
                        @foreach($provinceNames as $item)
                        <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label for="Amphures">อำเภอ / District</label><br>
                    <select name="amphures" id="amphures" class="select2" onchange="select_amphures()">
                        <option value=""></option>

                    </select>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label for="Tambon">ตำบล / Sub-district </label><br>
                    <select name="Tambon" id="Tambon" class="select2" onchange="select_Tambon()">
                        <option value=""></option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label for="zip_code">รหัสไปรษณีย์ / Postal Code</label><br>
                    <select name="zip_code" id="zip_code" class="select2">
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <label for="address">ที่อยู่ / Address</label><br>
                    <textarea type="text" id="address" name="address" rows="5" cols="35" class="textarea" aria-label="With textarea" required></textarea>
                </div>

            </div>

            <div class="row">
                <div class="col-12">
                    <label for="Phone_number">หมายเลขโทรศัพท์ / Phone number</label>
                    <button type="button" class="add-phone" id="add-phone" data-target="phone-container">เพิ่มเบอร์โทรศัพท์</button>
                </div>
            </div>
            <div id="phone-container" class="flex-container row">
                <!-- Initial input field -->
                <div class="col-lg-4 col-md-6 col-sm-12 phone-group">
                    <input type="text" name="phone[]" class="form-control" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
                    <button type="button" class="remove-phone">ลบ</button>
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
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label for="email">อีเมล / Email</label><br>
                    <input type="text" id="email" name="email" maxlength="70" required>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label for="booking_channel">ช่องทางการจอง / Booking Channel</label><br>
                    <select name="booking_channel[]" id="booking_channel" class="select2" multiple>
                        <option value=""></option>
                        @foreach($booking_channel as $item)
                        <option value="{{ $item->id }}">{{ $item->name_en }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label for="identification_number">หมายเลขประจำตัว / Identification Number</label><br>
                    <input type="text" id="identification_number" name="identification_number" required>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label for="contract_rate_start_date">Contract Rate Start Date</label><br>
                    <div class="datestyle"><input type="date" id="contract_rate_start_date" name="contract_rate_start_date" onchange="Onclickreadonly()"></div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label for="contract_rate_end_date">Contract Rate End Date</label><br>
                    <div class="datestyle">
                    <input type="date" id="contract_rate_end_date" name="contract_rate_end_date" readonly>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label for="discount_contract_rate">Discount Contract Rate (%)</label><br>
                    <script>
                        function checkInput() {
                            var input = document.getElementById("discount_contract_rate");
                            if (input.value > 100) {
                                input.value = 100; // กำหนดค่าใหม่เป็น 100
                            }
                        }
                    </script>
                    <input type="number" id="discount_contract_rate" name="discount_contract_rate" oninput="checkInput()" min="0" max="100">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <label for="latest_introduced_by">แนะนำล่าสุดโดย / Latest Introduced By</label><br>
                    <input type="text" id="latest_introduced_by" name="latest_introduced_by">
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                    <div class="button-guest-end">
                        <button type="button" class="btn" onclick="window.location.href='{{ route('guest.index') }}'">{{ __('ย้อนกลับ') }}</button>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                    <div class="button-guest">
                        <button type="submit" class="btn">ตกลง</button>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top: 5%;">
                <div class="col-12">
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
                            <a class="nav-link " onclick="openHistory(event, 'Contract_Rate_Document')">Contract Rate Document</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " onclick="openHistory(event, 'User logs')">User logs</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
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
                    </div>
                </div>
            </div>





    </div>





</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
<link rel="stylesheet" href="dataTables.dataTables.css">
<script>
    $(document).ready(function() {
        new DataTable('#example6 , #example7', {

        });
    });
    $(document).ready(function() {
        $('#identification_number').mask('0-0000-00000-00-0');
    });
</script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Please select an option"
        });
    });

    window.onload = function() {
        document.getElementById("defaultOpen").click(); // เปิดแท็บ Summary Visit info เมื่อหน้าโหลด
        document.getElementById("lastestOpen").click();
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
        $('.select2').select2();
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
        } else {
            // ถ้าไม่เลือก "Other_countries" ซ่อน input field สำหรับเมืองอื่นๆ และแสดง input field สำหรับเมืองไทย
            cityInput.style.display = "none";
            citythai.style.display = "block";
            // เรียกใช้ฟังก์ชัน select_amphures() เพื่อเปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
            select_amphures();
        }
    }

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

    function select_province() {
        var provinceID = $('#province').val();
        jQuery.ajax({
            type: "GET",
            url: "{!! url('/guest/amphures/" + provinceID + "') !!}",
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
            url: "{!! url('/guest/Tambon/" + amphuresID + "') !!}",
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
            url: "{!! url('/guest/districts/" + Tambon + "') !!}",
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
    // document.addEventListener("DOMContentLoaded", function() {
    //     const addButton = document.getElementById('add-phone');
    //     const removeButton = document.querySelector('.remove-phone');
    //     const phoneContainer = document.getElementById('phone-container');

    //     let phoneCount = 1;

    //     addButton.addEventListener('click', function() {
    //         console.log('Add button clicked');
    //         const phoneGroup = document.createElement('div');
    //         phoneGroup.classList.add('phone-group');
    //         phoneGroup.innerHTML = `
    //         <input type="text" name="phone[]" class="form-control" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
    //     `;
    //         phoneContainer.appendChild(phoneGroup);
    //         phoneCount++;
    //     });

    //     removeButton.addEventListener('click', function() {
    //         console.log('Remove button clicked');
    //         if (phoneCount > 1) {
    //             const phoneGroups = phoneContainer.querySelectorAll('.phone-group');
    //             const lastPhoneGroup = phoneGroups[phoneGroups.length - 1];
    //             phoneContainer.removeChild(lastPhoneGroup);
    //             phoneCount--;
    //         }
    //     });
    // });
</script>























@if(Session::has('error'))
<script>
    swal({
        title: "{{ Session::get('error') }}",
        icon: "error",
        customClass: {
            title: "my-swal-title" // กำหนดคลาสใหม่สำหรับข้อความหัวเรื่อง
        }
    });

    // แสดงการแจ้งเตือน (error) ด้วย JavaScript โดยใช้ค่าจาก Controller
    var msg = "{{ $msg ?? '' }}"; // กำหนดค่า msg จาก Controller
    if (msg) {
        alert(msg);
    }
</script>
 @endif
@if(Session::has('alert'))
<script>
    swal({
        title: "{{ Session::get('alert') }}",
        icon: "success",
        customClass: {
            title: "my-swal-title" // กำหนดคลาสใหม่สำหรับข้อความหัวเรื่อง
        }
    });

    // แสดงการแจ้งเตือน (alert) ด้วย JavaScript โดยใช้ค่าจาก Controller
    var msg = "{{ $msg ?? '' }}"; // กำหนดค่า msg จาก Controller
    if (msg) {
        alert(msg);
    }
</script>
@endif

@endsection
