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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

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
        .select2-container{
            width: 100% !important;
        }

        .add-phone {
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
            background-color: #28a745;
            border-color: #28a745;
            border-radius: 0.25rem;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
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
            margin-bottom: 8px;
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
            border-bottom: 1px solid #ccc;
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
            padding: 20px;
            display: none;
            border-top: 1px solid #ccc;
            /* เพิ่มขอบด้านบน */
        }

        .custom-accordion input[type="checkbox"]:checked+label+.custom-accordion-content {
            display: block;
        }

        .button1 {
            background-color: white;
            border: 2px solid #ccc;
            color: black;
            padding: 0px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            border-width: 1px;
            border-radius: 8px;
            transition-duration: 0.4s;
            cursor: pointer;
        }

        .button1:hover {
            background-color: #555;
            color: white;
        }

        .button1.clicked {
            background-color: green;
            color: white;
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
    .label2{
        padding-top: 5px;
        float: left;
    }
    .datestyle {
        height: 50px !important;
        background-color: white;
    }

    .buttonstyle button{
        width: 10%;
        border: 1px solid #ccc;
        border-radius: 6px;
        margin-bottom: 10px;
    }
    .buttonstyle button:hover{
        background-color: #ccc;
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
    </style>
    <div class="container">
        <div class="row">
            <div class="titleh1 col-6">
                <h1>Company (องค์กร)</h1>
            </div>
            <div class="col-6">
                <input style="width:30%; float: right;" type="text" id="Profile_ID" name="Profile_ID" maxlength="70" required value="{{$representative_ID}}" disabled>
            </div>
        </div>

        <div class="row buttonstyle">
            <div class="col-lg-12 col-md-6 col-sm-12" id="add-contact-cc">
                <button class="button1" onclick="window.location.href = '{{ url('/Company/edit/'.$Company->id) }}'">Company</button>
            </div>
            <div class="col-lg-12 col-md-6 col-sm-12" id="add-contact-c">
                <button class="button1"onclick="window.location.href = '{{ url('/Company/edit/contact/'.$Company->id) }}'">Contact</button>
            </div>
            <div class="col-lg-12 col-md-6 col-sm-12" id="add-contact-d">
                <button class="button1"onclick="window.location.href = '{{ url('/Company/edit/contact/detail/'.$Company->id) }}'">Detail</button>
            </div>
        </div>
    <form id="myForm" action="{{url('/Company/edit/contact/editcontact/update/'.$Company->id.'/'.$representative->id)}}" method="POST">
    {!! csrf_field() !!}
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <label class="labelcontact" for="">Title</label>
                <select name="Mprefix" id="Mprefix" class="form-select">
                    <option value=""></option>
                    @foreach($Mprefix as $item)
                    <option value="{{ $item->id }}" {{$representative->prefix == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12"><label for="first_name">First Name</label><br>
                <input type="text" id="first_nameAgent" name="first_nameAgent" maxlength="70" required value="{{$representative->First_name}}">
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12"><label for="last_name">Last Name</label><br>
                <input type="text" id="last_nameAgent" name="last_nameAgent" maxlength="70" required value="{{$representative->Last_name}}">
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-12">
                <label for="Country">Country</label><br>
                <select name="countrydataA" id="countrySelectA" class="form-select" onchange="showcityAInput()">
                    <option value="Thailand" {{$representative->Country == "Thailand" ? 'selected' : ''}}>ประเทศไทย</option>
                    <option value="Other_countries" {{$representative->Country == "Other_countries" ? 'selected' : ''}}>ประเทศอื่นๆ</option>
                </select>
            </div>
        @if ($representative->Country === 'Other_countries')
            <div class="col-lg-4 col-md-6 col-sm-12" id="cityInput">
                <label for="city">City</label><br>
                <input type="text" id="city" name="city" value="{{$Other_City}}">
            </div>
            @else
            <div class="col-lg-4 col-md-6 col-sm-12" id="cityInput" style="display:none;">
                <label for="city">City</label><br>
                <input type="text" id="city" name="city">
            </div>
            @endif
            @if (($representative->Country === 'Thailand'))
            <div class="col-lg-4 col-md-6 col-sm-12" id="citythai" style="display:block;">
                <label for="city">City</label><br>
                <select name="province" id="province" class="select2" onchange="select_province()">
                    @foreach($provinceNames as $item)
                    <option value="{{ $item->id }}" {{$representative->City == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                    @endforeach
                </select>
            </div>
            @else
            <div class="col-lg-4 col-md-6 col-sm-12" id="citythai" style="display:none;">
                <label for="city">City</label><br>
                <select name="province" id="province" class="select2" onchange="select_province()">
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
                <select name="amphures" id="amphures" class="select2" onchange="select_amphures()">
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
                    <select name="Tambon" id="Tambon" class="select2" onchange="select_Tambon()">
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
                <select name="zip_code" id="zip_code" class="select2">
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
                <input type="text" id="EmailAgent" name="EmailAgent" maxlength="70" required value="{{$representative->Email}}">
            </div>
            </div>

            <div class="row">
            <div class="col-12">
                <label for="Address">Address</label><br>
                <textarea type="text" id="addressAgent" name="addressAgent" rows="3" cols="25" class="textarea" aria-label="With textarea" required>{{$representative->Address}}</textarea>
            </div>
            </div>


            <div class="row">
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
            <div class="col-6">
            <div class="button-guest-end">
                    <button type="button" class="btn" onclick="window.location.href='{{ route('Company.index') }}'">{{ __('ย้อนกลับ') }}</button>
                </div>
            </div>
            <div class="col-6">
                <div class="button-guest">
                    <button type="submit" class="btn" onclick="confirmSubmit(event)">ตกลง</button>
                </div>
            </div>
        </div>


    </form>
    </div>
    <!-- ปิด -->







    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });


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


        //---------------------------------------------------2------------------------------
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
                        console.log(zip_code);
                        $('#zip_code ').append(zip_code);
                    });
                },
            })
        }
        //Company_Phone
    </script>
    <script>
        function confirmSubmit(event) {
            event.preventDefault(); // Prevent the form from submitting
            var Company_Name = $('#first_nameAgent').val();
            var Branch = $('#last_nameAgent').val();
            var message = `ต้องการบันทึกข้อมูลของคุณ ${Company_Name} ${Branch} หรือไม่`;
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
