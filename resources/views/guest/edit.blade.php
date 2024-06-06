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

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

</body>

</html>


<style>
    .container {
        width: 100%;
        display: block;
        margin: auto;
        margin-top: 40px;
        background-color: white;
        padding: 5% 10%;
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
        width: 50%;
        border-radius: 8px;
        margin-Top: 10px;
        text-align: center;

    }

    .button-guest-end button {
        background-color: #dc3545;
        color: white;
        width: 50%;
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
        margin-top: 1%;
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

    .select2-container {
        width: 100% !important;
    }

    .phone-group {
        position: relative;
    }

    .phone-group input {
        width: 100%;
        padding-right: 40px;
        /* space for the remove button */
    }

    .remove-input,
    .remove-phone {
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        margin: auto;
        margin-right: 3%;
        margin-top: 10px;
        height: 60%;
        font-size: 14px !important;
        border: none;
        background: #dc3545;
        /* Adjust the button style as needed */
        padding: 0px 20px;
        cursor: pointer;
    }

    .select2-selection--multiple {
        border-color: #ccc !important;
        min-height: 50px !important;
        height: auto;
        margin-top: 8px;
    }

    .datestyle input {
        height: 50px !important;
        background-color: white;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="usertopic">
                <h1>Guest (ลูกค้า)</h1>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div style="float: right;" class="col-lg-6 col-md-6 col-sm-12">
                <label for="Profile_ID">Profile ID</label>
                <input type="text" id="Profile_ID" name="Profile_ID" maxlength="70" required value="{{$Guest->Profile_ID}}" disabled>
            </div>
        </div>
        <form action="{{url('/guest/edit/update/'.$Guest->id)}}" method="POST">
            {!! csrf_field() !!}

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label for="Preface">คำนำหน้า / Title</label><br>
                    <select name="Preface" id="PrefaceSelect" class="form-select">
                        <option value=""></option>
                        @foreach($prefix as $item)
                        <option value="{{ $item->id }}" {{$Guest->preface == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <label for="first_name">ชื่อจริง / First Name</label><br>
            <input type="text" id="first_name" name="first_name" maxlength="70" required value="{{$Guest->First_name}}">
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12"><label for="last_name">นามสกุล / Last Name</label><br>
            <input type="text" id="last_name" name="last_name" maxlength="70" required value="{{$Guest->Last_name}}">
        </div>
    </div>


    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <label for="country">ประเทศ / Country</label>
            <select name="countrydata" id="countrySelect" class="form-select" onchange="showcityInput()">
                <option value="Thailand" {{$Guest->Country == "Thailand" ? 'selected' : ''}}>ประเทศไทย</option>
                <option value="Other_countries" {{$Guest->Country == "Other_countries" ? 'selected' : ''}}>ประเทศอื่นๆ</option>
            </select>
        </div>
        @if ($Guest->Country === 'Other_countries')
        <div class="col-lg-6 col-md-6 col-sm-12" id="cityInput">
            <label for="city">จังหวัด / Province</label>
            <input type="text" id="city" name="city" value="{{$Other_City}}">
        </div>
        @else
        <div class="col-lg-6 col-md-6 col-sm-12" id="cityInput" style="display:none;">
            <label for="city">จังหวัด / Province</label>
            <input type="text" id="city" name="city">
        </div>
        @endif
        @if (($Guest->Country === 'Thailand'))
        <div class="col-lg-6 col-md-6 col-sm-12" id="citythai" style="display:block;">
            <label for="city">จังหวัด / Province</label>
            <select name="province" id="province" class="select2" onchange="select_province()">
                @foreach($provinceNames as $item)
                <option value="{{ $item->id }}" {{$Guest->City == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                @endforeach
            </select>
        </div>
        @else
        <div class="col-lg-6 col-md-6 col-sm-12" id="citythai" style="display:none;">
            <label for="city">จังหวัด / Province</label>
            <select name="province" id="province" class="select2" onchange="select_province()">
                <option value=""></option>
                @foreach($provinceNames as $item)
                <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                @endforeach
            </select>
        </div>
        @endif
    </div>


    <div class="row">
        <div class="col-12">
            <label for="address">ที่อยู่ / Address</label><br>
            <textarea type="text" id="address" name="address" rows="5" cols="35" class="textarea" aria-label="With textarea" required>{{$Guest->Address}}</textarea>
        </div>
    </div>

    <div class="row">
        @if ($Guest->Country === 'Thailand')
        <div class="col-lg-4 col-md-6 col-sm-12">
            <label for="Amphures">อำเภอ / District</label>
            <select name="amphures" id="amphures" class="select2" onchange="select_amphures()">
                @foreach($amphures as $item)
                <option value="{{ $item->id }}" {{ $Guest->Amphures == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                @endforeach
            </select>
        </div>
        @else
        <div class="col-lg-4 col-md-6 col-sm-12">
            <label for="Amphures">อำเภอ / District</label>
            <select name="amphures" id="amphures" class="select2" onchange="select_amphures()" disabled>
                <option value=""></option>
            </select>
        </div>
        @endif


        @if ($Guest->Country === 'Thailand')
        <div class="col-lg-4 col-md-6 col-sm-12">
            <label for="Tambon">ตำบล / Sub-district </label><br>
            <select name="Tambon" id="Tambon" class="select2" onchange="select_Tambon()">
                @foreach($Tambon as $item)
                <option value="{{ $item->id }}" {{ $Guest->Tambon == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                @endforeach
            </select>
        </div>
        @else
        <div class="col-lg-4 col-md-6 col-sm-12">
            <label for="Tambon">ตำบล / Sub-district </label><br>
            <select name="Tambon" id="Tambon" class="select2" onchange="select_Tambon()" disabled>
                <option value=""></option>
            </select>
        </div>
        @endif



        @if ($Guest->Country === 'Thailand')
        <div class="col-lg-4 col-md-6 col-sm-12">
            <label for="zip_code">รหัสไปรษณีย์ / Postal Code</label><br>
            <select name="zip_code" id="zip_code" class="select2">
                @foreach($Zip_code as $item)
                <option value="{{ $item->id }}" {{ $Guest->Zip_Code == $item->zip_code ? 'selected' : '' }}>{{ $item->zip_code }}</option>
                @endforeach
            </select>
        </div>
        @else
        <div class="col-lg-4 col-md-6 col-sm-12">
            <label for="zip_code">รหัสไปรษณีย์ / Postal Code</label><br>
            <select name="zip_code" id="zip_code" class="select2" disabled>
                <option value=""></option>
            </select>
        </div>
        @endif

    </div>


    <div class="row">
        <div class="col-12">
            <label for="Phone_number">หมายเลขโทรศัพท์ / Phone Number</label>
            <button type="button" class="add-phone" id="add-phone" data-target="phone-container">เพิ่มเบอร์โทรศัพท์</button>
        </div>
    </div>
    <div id="phone-container" class="flex-container row">
        <!-- Initial input fields -->
        @foreach($phoneDataArray as $phone)
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="phone-group show">
                <input type="text" name="phone[]" class="form-control" maxlength="10" value="{{ $phone['Phone_number'] }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
                <button type="button" class="remove-phone">ลบ</button>
            </div>
        </div>
        @endforeach
    </div>

    <script>
        document.getElementById('add-phone').addEventListener('click', function() {
            var phoneContainer = document.getElementById('phone-container');
            var newCol = document.createElement('div');
            newCol.classList.add('col-lg-4', 'col-md-6', 'col-sm-12');
            newCol.innerHTML = `
        <div class="phone-group">
            <input type="text" name="phone[]" class="form-control" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
            <button type="button" class="remove-phone">ลบ</button>
        </div>
    `;
            phoneContainer.appendChild(newCol);

            // Add the show class after a slight delay to trigger the transition
            setTimeout(function() {
                newCol.querySelector('.phone-group').classList.add('show');
            }, 10);

            attachRemoveEvent(newCol.querySelector('.remove-phone'));
        });

        function attachRemoveEvent(button) {
            button.addEventListener('click', function() {
                var phoneContainer = document.getElementById('phone-container');
                if (phoneContainer.childElementCount > 1) {
                    phoneContainer.removeChild(button.closest('.col-lg-4, .col-md-6, .col-sm-12'));
                }
            });
        }

        // Attach the remove event to the initial remove buttons
        document.querySelectorAll('.remove-phone').forEach(function(button) {
            attachRemoveEvent(button);
        });
    </script>
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-12">
            <label for="email">อีเมล / Email</label><br>
            <input type="text" id="email" name="email" maxlength="70" required value="{{$Guest->Email}}">
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <label for="booking_channel">ช่องทางการจอง / Booking Channel</label><br>
            <select name="booking_channel[]" id="booking_channel" class="select2" multiple>
                @php
                $booking = explode(',',$Guest->Booking_Channel);
                @endphp
                @foreach($booking_channel as $item)
                <option value="{{ $item->id }}" {{ in_array($item->id, $booking) ? 'selected' : '' }}>
                    {{ $item->name_en }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <label for="identification_number">หมายเลขประจำตัว / Identification Number</label>
            <input type="text" id="identification_number" name="identification_number" required value="{{$Guest->Identification_Number}}">
        </div>

    </div>



    <div class="row">
        <div class="datestyle col-lg-4 col-md-6 col-sm-12">
            <label for="contract_rate_start_date">Contract Rate Start Date:</label>
            <input type="date" id="contract_rate_start_date" name="contract_rate_start_date" onchange="Onclickreadonly()" value="{{$Guest->Contract_Rate_Start_Date}}">
        </div>
        <div class="datestyle col-lg-4 col-md-6 col-sm-12">
            <label for="contract_rate_end_date">Contract Rate End Date</label>
            <input type="date" id="contract_rate_end_date" name="contract_rate_end_date" readonly value="{{$Guest->Contract_Rate_End_Date}}">
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <label for="discount_contract_rate">Discount Contract Rate (%)</label>
            <script>
                function checkInput() {
                    var input = document.getElementById("discount_contract_rate");
                    if (input.value > 100) {
                        input.value = 100; // กำหนดค่าใหม่เป็น 100
                    }
                }
            </script>
            <input type="number" id="discount_contract_rate" name="discount_contract_rate" oninput="checkInput()" min="0" max="100" value="{{$Guest->Discount_Contract_Rate}}">
        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <label for="latest_introduced_by">แนะนำล่าสุดโดย / Latest Introduced By</label><br>
            <input type="text" id="latest_introduced_by" name="latest_introduced_by" value="{{$Guest->Lastest_Introduce_By}}">
        </div>
    </div>


    <div class="row">
        <div class="col-6">
            <div class="button-guest-end">
                <button type="button" class="btn" onclick="window.location.href='{{ route('guest.index') }}'">{{ __('ย้อนกลับ') }}</button>
            </div>
        </div>
        <div class="col-6">
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





<script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
<link rel="stylesheet" href="dataTables.dataTables.css">


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
    $(document).ready(function() {
        $('#identification_number').mask('0-0000-00000-00-0');
    });
    $(document).ready(function() {
        new DataTable('#example6 , #example7', {

        });
    });
</script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
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
