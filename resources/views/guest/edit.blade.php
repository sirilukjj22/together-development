@extends('layouts.masterLayout')
<style>
    .select2{
        margin: 4px 0;
        border: 1px solid #ffffff;
        border-radius: 4px;
        box-sizing: border-box;
    }
    .phone-group {
        position: relative;
    }

    .phone-group input {
        width: 100%;
        padding-right: 40px;
        /* space for the remove button */
    }
    .btn-space {
        margin-right: 10px; /* ปรับขนาดช่องว่างตามต้องการ */
    }
</style>
@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to Edit Guest.</small>
                <h1 class="h4 mt-1">Edit Guest (แก้ไขลูกค้า)</h1>
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
                            <h1 class="h4 mt-1">GUEST</h1>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-12">
                            <input style=" float:right;" type="text" class="form-control" id="Profile_ID" name="Profile_ID" maxlength="70" required value="{{$Guest->Profile_ID}}" disabled>
                        </div>
                    </div>
                    <form action="{{url('/guest/edit/update/'.$Guest->id)}}" method="POST">
                        @csrf
                        <div class="row mt-3">
                            <div class="col-lg-2 col-md-2 col-sm-12">
                                <label for="Preface" >คำนำหน้า / Title</label><br>
                                <select name="Preface" id="PrefaceSelect" class="form-select">
                                    <option value=""></option>
                                    @foreach($prefix as $item)
                                    <option value="{{ $item->id }}"{{$Guest->preface == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-5 col-md-4 col-sm-12">
                                <label for="first_name">ชื่อจริง / First Name</label><br>
                                <input type="text"class="form-select" placeholder="First Name" id="first_name" name="first_name" maxlength="70"  value="{{$Guest->First_name}}" required>
                            </div>
                            <div class="col-lg-5 col-md-4 col-sm-12"><label for="last_name">นามสกุล / Last Name</label><br>
                                <input type="text"class="form-select" placeholder="Last Name" id="last_name" name="last_name" maxlength="70" value="{{$Guest->Last_name}}" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-6 col-md-6 col-sm-12"><label for="country">ประเทศ / Country</label><br>
                                <select name="countrydata" id="countrySelect" class="form-select" onchange="showcityInput()">
                                    <option value="Thailand" {{$Guest->Country == "Thailand" ? 'selected' : ''}}>ประเทศไทย</option>
                                    <option value="Other_countries" {{$Guest->Country == "Other_countries" ? 'selected' : ''}}>ประเทศอื่นๆ</option>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12" id="cityInput" style="display:none;">
                                <label for="city">จังหวัด / Province</label><br>
                                <input type="text" id="city" name="city"  class="form-select" value="{{$Other_City}}">
                            </div>
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
                        <div class="row mt-2">
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
                        <div class="row mt-2">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <label for="address">ที่อยู่ / Address</label><br>
                                <textarea type="text" id="address" name="address" rows="5" cols="25" class="textarea form-control" aria-label="With textarea" required>{{$Guest->Address}}</textarea>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <span for="Company_Phone" class="flex-container">
                                    โทรศัพท์/ Phone number
                                </span>
                                <button type="button" class="btn btn-color-green my-2" id="add-phone">เพิ่มหมายเลขโทรศัพท์</button>
                            </div>
                            <div id="phone-container" class="flex-container row">
                                @foreach($phoneDataArray as $phone)
                                <div class="col-lg-4 col-md-6 col-sm-12 mt-2">
                                    <div class="input-group show">
                                        <input type="text" name="phone[]" class="form-control"value="{{ $phone['Phone_number'] }}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
                                        <button type="button" class="btn btn-outline-danger remove-phone"><i class="bi bi-x-circle" style="width:100%;"></i></button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="email">อีเมล / Email</label><br>
                                <input class="email form-control" type="text" id="email" name="email" maxlength="70" value="{{$Guest->Email}}" required>
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
                                <label for="identification_number">หมายเลขประจำตัว / Identification Number</label><br>
                                <input type="text" class="form-control" id="identification_number" name="identification_number" value="{{$Guest->Identification_Number}}" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="contract_rate_start_date">Contract Rate Start Date</label><br>
                                <div class="datestyle"><input class="form-control" type="date" id="contract_rate_start_date" name="contract_rate_start_date" onchange="Onclickreadonly()"  value="{{$Guest->Contract_Rate_Start_Date}}"></div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="contract_rate_end_date">Contract Rate End Date</label><br>
                                <div class="datestyle">
                                <input type="date" class="form-control" id="contract_rate_end_date" name="contract_rate_end_date" readonly value="{{$Guest->Contract_Rate_End_Date}}">
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
                                <div class="input-group">
                                    <input type="text" class="form-control" id="discount_contract_rate" name="discount_contract_rate" oninput="checkInput()" min="0" max="100" value="{{$Guest->Discount_Contract_Rate}}">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <label for="latest_introduced_by">แนะนำล่าสุดโดย / Latest Introduced By</label><br>
                                <input type="text" class="form-control" id="latest_introduced_by" name="latest_introduced_by" value="{{$Guest->Lastest_Introduce_By}}">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-3 col-sm-12"></div>
                            <div class="col-lg-6 col-sm-12 d-flex justify-content-center align-items-center">
                                <button type="button" class="btn btn-secondary lift  btn-space"  onclick="window.location.href='{{ route('guest.index') }}'">{{ __('ย้อนกลับ') }}</button>
                                <button type="submit" class="btn btn-color-green lift ">บันทึกข้อมูล</button>
                            </div>
                            <div class="col-lg-3 col-sm-12"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('assets/js/jquery.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Please select an option"
        });
    });
    $(document).ready(function() {
        $('#identification_number').mask('0-0000-00000-00-0');
    });
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
            amphuresSelect.disabled = false;
            tambonSelect.disabled = false;
            zipCodeSelect.disabled = false;
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
</script>
<script>
    document.getElementById('add-phone').addEventListener('click', function() {
        var phoneContainer = document.getElementById('phone-container');
        var newCol = document.createElement('div');
        newCol.classList.add('col-lg-4', 'col-md-6', 'col-sm-12');
        newCol.innerHTML = `
            <div class="input-group mt-2">
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
@endsection
