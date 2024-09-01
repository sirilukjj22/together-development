@extends('layouts.masterLayout')
<style>
    .select2{

        margin: 4px 0;
        border: 1px solid #ffffff;
        border-radius: 4px;
        box-sizing: border-box;
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
        border-top: 1px solid #ffffff;
        /* เพิ่มขอบด้านบน */
    }

    .custom-accordion input[type="checkbox"]:checked+label+.custom-accordion-content {
        display: block;
    }
    .btn-space {
        margin-right: 10px; /* ปรับขนาดช่องว่างตามต้องการ */
    }
</style>
@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to Edit Agent.</small>
                <h1 class="h4 mt-1">Edit Agent (ตัวแทนบริษัท)</h1>
            </div>
        </div>
    </div>
@endsection
@section('content')
<div class="container">
    <div class="container mt-3">
        <div class="row clearfix">
            <div class="col-sm-12 col-12">
                <div class="card p-4 mb-4">
                    <div class="row">
                        <div class="col-lg-11 col-md-11 col-sm-12">
                            <h1 class="h4 mt-1">ตัวแทนบริษัท</h1>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-12">
                            <input style=" float:right;" type="text" class="form-control" id="Profile_ID" name="Profile_ID" maxlength="70" required value="{{$representative_ID}}" disabled>
                        </div>
                    </div>
                    <form id="myForm" action="{{url('/Company/edit/contact/editcontact/update/'.$Company->id.'/'.$representative->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <div class="row mt-2">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <label class="labelcontact" for="">คำนำหน้า / Title</label>
                                <select name="prefix" id="Mprefix" class="form-select">
                                    <option value=""></option>
                                    @foreach($Mprefix as $item)
                                    <option value="{{ $item->id }}" {{$representative->prefix == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-5 col-md-6 col-sm-12">
                                <label for="first_name">First Name</label>
                                <input type="text" id="first_nameAgent" class="form-control" name="First_name" maxlength="70" required value="{{$representative->First_name}}">
                            </div>
                            <div class="col-lg-5 col-md-6 col-sm-12">
                                <label for="last_name">Last Name</label>
                                <input type="text" id="last_nameAgent" class="form-control" name="Last_name" maxlength="70" required value="{{$representative->Last_name}}">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="Country">Country</label><br>
                                <select name="Country" id="countrySelectA" class="form-select" onchange="showcityAInput()">
                                    <option value="Thailand" {{$representative->Country == "Thailand" ? 'selected' : ''}}>ประเทศไทย</option>
                                    <option value="Other_countries" {{$representative->Country == "Other_countries" ? 'selected' : ''}}>ประเทศอื่นๆ</option>
                                </select>
                            </div>
                            <div class="col-sm-4 col-4" id="cityInput" style="display:none;">
                                <span for="City">City</span>
                                <input type="text" id="City" class="form-control" name="City" value="{{$Other_City}}" >
                            </div>
                            <div class="col-sm-4 col-4" id="citythai" style="display:block;">
                                <span for="City">City</span>
                                <select name="City" id="province" class="form-select" onchange="select_province()" style="width: 100%;" >
                                    <option value=""></option>
                                    @foreach($provinceNames as $item)
                                        <option value="{{ $item->id }}"{{$representative->City == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="Amphures">Amphures</label><br>
                                <select name="Amphures" id="amphures" class="select2" onchange="select_amphures()">
                                    @foreach($amphures as $item)
                                    <option value="{{ $item->id }}" {{ $representative->Amphures == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="Tambon">Tambon </label><br>
                                <select name="Tambon" id="Tambon" class="select2" onchange="select_Tambon()">
                                @foreach($Tambon as $item)
                                <option value="{{ $item->id }}" {{ $representative->Tambon == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                @endforeach
                            </select>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="zip_code">Zip Code</label><br>
                                <select name="Zip_Code" id="zip_code" class="select2">
                                    @foreach($Zip_code as $item)
                                    <option value="{{ $item->zip_code }}" {{ $representative->Zip_Code == $item->zip_code ? 'selected' : '' }}>{{ $item->zip_code }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="Email">Email</label><br>
                                <input type="text" id="EmailAgent" class="form-control" name="Email" maxlength="70" required value="{{$representative->Email}}">
                            </div>
                        </div>
                        <div class="mt-2">
                            <label for="Address">Address</label><br>
                            <textarea type="text" id="addressAgent" name="Address" rows="3" cols="25" class="form-control" aria-label="With textarea" required>{{$representative->Address}}</textarea>
                        </div>
                        <div class="mt-2">
                            <label for="Phone_number">หมายเลขโทรศัพท์ / Phone Number</label>
                            <button type="button" class="add-phone btn btn-color-green" id="add-phone" data-target="phone-container">เพิ่มเบอร์โทรศัพท์</button>
                            <div id="phone-container" class="flex-container row">
                                <!-- Initial input fields -->
                                @foreach($phoneDataArray as $phone)
                                <div class="col-lg-4 col-md-6 col-sm-12 mt-3">
                                    <div class="input-group show">
                                        <input type="text" name="phone[]" class="form-control" maxlength="10" value="{{ $phone['Phone_number'] }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
                                        <button type="button" class="btn btn-outline-danger remove-phone"><i class="bi bi-x-circle" style="width:100%;"></i></button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-3 col-sm-12"></div>
                            <div class="col-lg-6 col-sm-12 d-flex justify-content-center align-items-center">
                                <button type="button" class="btn btn-secondary lift  btn-space"  onclick="window.location.href='{{ route('Company.index') }}'">{{ __('ย้อนกลับ') }}</button>
                                <button type="submit" class="btn btn-color-green lift " onclick="confirmSubmit(event)">บันทึกข้อมูล</button>
                            </div>
                            <div class="col-lg-3 col-sm-12"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Please select an option"
        });
    });
</script>
{{-- ส่วนที่อยู่ --}}
<script>
    $(document).ready(function() {
        var TaxSelectA = $('#countrySelectA');
        var TaxSelectA = TaxSelectA.val(); // ใช้ jQuery เพื่อเลือก element
        var cityInputA = document.getElementById("cityInput");
        var citythaiA = document.getElementById("citythai");
        var amphuresSelect = document.getElementById("amphures");
        var tambonSelect = document.getElementById("Tambon");
        var zipCodeSelect = document.getElementById("zip_code");
        var province = document.getElementById("province");
        var City = document.getElementById("City");
        if (TaxSelectA == "Other_countries") {
            Com.style.display = "block";
            Title.style.display = "none";
            // citythaiA.style.display = "none";
            // ปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
            cityInputA.style.display = "block";
            citythaiA.style.display = "none";
            // ปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
            amphuresSelect.disabled = true;
            tambonSelect.disabled = true;
            zipCodeSelect.disabled = true;
            province.disabled = true;
            City.disabled = false;
        } else if (TaxSelectA == "Thailand"){
            cityInputA.style.display = "none";
            citythaiA.style.display = "block";
            // ปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
            amphuresSelect.disabled = false;
            tambonSelect.disabled = false;
            zipCodeSelect.disabled = false;
            province.disabled = false;
            City.disabled = true;
        }
    });
    function showcityAInput() {
        var countrySelectA = document.getElementById("countrySelectA");
        var cityInputA = document.getElementById("cityInput");
        var citythaiA = document.getElementById("citythai");
        var amphuresSelect = document.getElementById("amphures");
        var tambonSelect = document.getElementById("Tambon");
        var zipCodeSelect = document.getElementById("zip_code");
        var province = document.getElementById("province");
        var City = document.getElementById("City");

        // เช็คค่าที่ถูกเลือกใน dropdown list เมือง
        if (countrySelectA.value === "Other_countries") {
            // ถ้าเลือก "Other_countries" แสดง input field สำหรับเมืองอื่นๆ และซ่อน input field สำหรับเมืองไทย
            cityInputA.style.display = "block";
            citythaiA.style.display = "none";
            // ปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
            amphuresSelect.disabled = true;
            tambonSelect.disabled = true;
            zipCodeSelect.disabled = true;
            province.disabled = true;
            City.disabled = false;
        } else if (countrySelectA.value === "Thailand") {
            // เช็คค่าที่ถูกเลือกใน dropdown list เมือง

            // ถ้าเลือก "Other_countries" แสดง input field สำหรับเมืองอื่นๆ และซ่อน input field สำหรับเมืองไทย
            cityInputA.style.display = "none";
            citythaiA.style.display = "block";
            // ปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
            amphuresSelect.disabled = false;
            tambonSelect.disabled = false;
            zipCodeSelect.disabled = false;
            province.disabled = false;
            City.disabled = true;

            // เรียกใช้ฟังก์ชัน select_amphures() เพื่อเปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
            amphuresAgent();
        }
    }
</script>
{{-- ส่วนของที่อยู่ --}}
<script>
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
</script>
{{-- ส่วนของโทรศัพท์และแฟกต์ --}}
<script>
    document.getElementById('add-phone').addEventListener('click', function() {
        var phoneContainer = document.getElementById('phone-container');
        var newCol = document.createElement('div');
        newCol.classList.add('col-lg-4', 'col-md-6', 'col-sm-12');
        newCol.innerHTML = `
            <div class="input-group mt-3">
                <input type="text" name="phone[]" class="form-control" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
                <button type="button" class="btn btn-outline-danger remove-phone"><i class="bi bi-x-circle" style="width:100%;"></i></button>
            </div>
        `;
        phoneContainer.appendChild(newCol);

        // Add the show class after a slight delay to trigger the transition
        setTimeout(function() {
            newCol.querySelector('.input-group').classList.add('show');
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
