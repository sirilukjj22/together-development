@extends('layouts.masterLayout')
<style>
    .profile-pic {
        color: transparent;
        transition: all 0.3s ease;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        transition: all 0.3s ease;
    }
    .profile-pic input {
        display: none;
    }
    .profile-pic img {
        position: absolute;
        object-fit: cover;
        width: 165px;
        height: 165px;
        box-shadow: 0 0 10px 0 rgba(255, 255, 255, 0.35);
        border-radius: 100px;
        z-index: 0;
        border: 2px solid #999999;
    }
    .profile-pic .-label {
        cursor: pointer;
        height: 165px;
        width: 165px;
    }
    .profile-pic:hover .-label {
        display: flex;
        justify-content: center;
        align-items: center;

        z-index: 10000;
        color: #fafafa;
        transition: background-color 0.2s ease-in-out;
        border-radius: 100px;
        margin-bottom: 0;
    }
    .profile-pic span {
        display: inline-flex;
        padding: 0.2em;
        height: 2em;
    }
    .btn-space {
        margin-right: 10px;
    }
    .file-link img {
            width: 35%;
    }

    /* สำหรับหน้าจอที่มีความกว้างน้อยกว่า 576px (เช่น โทรศัพท์มือถือ) */
    @media (max-width: 576px) {
        .file-link img, .file-link2 img {
                width: 30%; /* ปรับขนาดภาพให้เล็กลง */
            }
        .file-link, .file-link2 {
            display: flex;
            justify-content: center; /* จัดให้อยู่ตรงกลางแนวนอน */
            align-items: center; /* จัดให้อยู่ตรงกลางแนวตั้ง */
        }
    }

    /* สำหรับหน้าจอที่มีความกว้างตั้งแต่ 576px ขึ้นไป */
    @media (min-width: 576px) {
        .file-link img, .file-link2 img {
            margin-top: 10px;
            width: 100%;
        }
    }
    @media (min-width: 1476px) {
        .file-link img, .file-link2 img {
            width: 65%;
        }
    }
</style>



@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to Freelancer View.</small>
                <h1 class="h4 mt-1">Freelancer View (ดูรายละเอียดตัวแทน)</h1>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container">
    <div class="row clearfix">
        <div class="col-sm-12 col-12">
            <form action="{{url('/Freelancer/check/update/'.$Freelancer_checked->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card p-4 mb-4">
                    <div class="row">
                        <div class="col-sm-5 col-5"></div>
                        <div class="col-sm-2 col-2">
                            <div class="profile-pic" >
                                <label class="-label" for="file">
                                </label>
                                <img src="{{ asset($Freelancer_checked->Imagefreelan) }}" id="output" width="200" />
                            </div>
                        </div>
                        <div class="col-sm-5 col-5"></div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-2 col-md-2 col-sm-12">
                            <label for="Preface" >คำนำหน้า / Title</label><br>
                            <select name="Preface" id="PrefaceSelect" class="form-select" disabled>
                                <option value=""></option>
                                @foreach($prefix as $item)
                                <option value="{{ $item->id }}"{{$Freelancer_checked->prefix == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-5 col-md-4 col-sm-12">
                            <label for="first_name">ชื่อจริง / First Name</label><br>
                            <input type="text"class="form-control" placeholder="First Name" id="first_name" name="first_name" maxlength="70"  value="{{$Freelancer_checked->First_name}}" disabled>
                        </div>
                        <div class="col-lg-5 col-md-4 col-sm-12"><label for="last_name">นามสกุล / Last Name</label><br>
                            <input type="text" class="form-control" placeholder="Last Name" id="last_name" name="last_name" maxlength="70" value="{{$Freelancer_checked->Last_name}}" disabled>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-4 col-md-6 col-sm-12" >
                            <label for="booking_channel">ช่องทางการจอง / Booking Channel</label><br>
                            <select name="booking_channel[]" id="booking_channel" class="select2" multiple disabled>
                                @php
                                    $booking = explode(',',$Freelancer_checked->Booking_Channel);
                                @endphp
                                @foreach($booking_channel as $item)
                                    <option value="{{ $item->id }}" {{ in_array($item->id, $booking) ? 'selected' : '' }}>
                                        {{ $item->name_en }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12"><label for="country">ประเทศ / Country</label><br>
                            <select name="countrydata" id="countrySelect" class="form-select" onchange="showcityInput()" disabled>
                                <option value="Thailand"{{$Freelancer_checked->Country == "Thailand" ? 'selected' : ''}}>ประเทศไทย</option>
                                <option value="Other_countries"{{$Freelancer_checked->Country == "Other_countries" ? 'selected' : ''}}>ประเทศอื่นๆ</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12" id="cityInput" style="display:none;">
                            <label for="city">จังหวัด / Province</label><br>
                            <input type="text" id="city" name="city"  value="{{$Freelancer_checked->Other_City}}" disabled>
                        </div>
                        @if (($Freelancer_checked->Country === 'Thailand'))
                            <div class="col-lg-4 col-md-6 col-sm-12" id="citythai" style="display:block;">
                                <label for="city">จังหวัด / Province</label><br>
                                <select name="province" id = "province" class="select2" onchange="select_province()"style="border: 1px solid #000" disabled>
                                    <option value=""></option>
                                    @foreach($provinceNames as $item)
                                        <option value="{{ $item->id }}" {{$Freelancer_checked->City == $item->id ? 'selected' : ''}} >{{ $item->name_th }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="col-lg-4 col-md-6 col-sm-12" id="citythai" style="display:block;"disabled>
                                <label for="city">จังหวัด / Province</label><br>
                                <select name="province" id = "province" class="select2" onchange="select_province()"style="border: 1px solid #000"disabled>
                                    <option value=""></option>
                                    @foreach($provinceNames as $item)
                                        <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
                    <div class="row mt-2">
                        @if (($Freelancer_checked->Country === 'Thailand'))
                            <div class="col-lg-4 col-md-4  col-sm-12">
                                <label for="Amphures">อำเภอ / District</label><br>
                                <select name="amphures" id = "amphures" class="select2"  onchange="select_amphures()"disabled>
                                    @foreach($amphures as $item)
                                        <option value="{{ $item->id }}" {{ $Freelancer_checked->Amphures == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="Tambon">ตำบล / Sub-district</label><br>
                                <select name="Tambon" id ="Tambon" class="select2" onchange="select_Tambon()"disabled>
                                    @foreach($amphures as $item)
                                        <option value="{{ $item->id }}" {{ $Freelancer_checked->Tambon == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="zip_code">รหัสไปรษณีย์ / Postal Code</label><br>
                                <select name="zip_code" id ="zip_code" class="select2"disabled >
                                    @foreach($Zip_code as $item)
                                        <option value="{{ $item->id }}" {{ $Freelancer_checked->Zip_Code == $item->zip_code ? 'selected' : '' }}>{{ $item->zip_code }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="col-lg-4 col-md-4  col-sm-12">
                                <label for="Amphures">อำเภอ / District</label><br>
                                <select name="amphures" id = "amphures" class="select2"  onchange="select_amphures()"disabled>
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="Tambon">ตำบล / Sub-district</label><br>
                                <select name="Tambon" id ="Tambon" class="select2" onchange="select_Tambon()"disabled>
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <label for="zip_code">รหัสไปรษณีย์ / Postal Code</label><br>
                                <select name="zip_code" id ="zip_code" class="select2"disabled >
                                    <option value=""></option>
                                </select>
                            </div>
                        @endif
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-12 col-md-12 col-sm-12" >
                            <label for="address">ที่อยู่ / Address</label><br>
                            <textarea type="text" id="address" name="address" rows="3" cols="35" class="form-control" aria-label="With textarea" disabled>{{$Freelancer_checked->Address}}</textarea>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-4 col-md-4 col-sm-12" >
                            <label for="email">อีเมล / Email</label><br>
                            <input type="text" class="form-control" id="email" name="email"maxlength="70" value="{{$Freelancer_checked->Email}}" disabled>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12" >
                            <label for="Birthday">วันเดือนปีเกิด / Birthday</label><br>
                            <input type="date" class="form-control" id="Birthday" name="Birthday" value="{{$Freelancer_checked->Birthday}}" disabled>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12" >
                            <label for="First_day_work">วันที่เริ่มทำงาน / First day of work</label><br>
                            <input type="date" class="form-control" id="First_day_work" name="First_day_work" value="{{$Freelancer_checked->First_day_work}}" disabled>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <span for="Company_Phone" class="flex-container">
                                โทรศัพท์/ Phone number
                            </span>
                        </div>
                        <div id="phone-container" class="flex-container row">
                            <!-- Initial input fields -->
                            @foreach($phoneArray as $phone)
                                <div class="col-lg-4 col-md-6 col-sm-12 mt-2">
                                    <div class="input-group show">
                                        <input type="text" name="phone[]" class="form-control"value="{{ $phone['Phone_number'] }}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" disabled>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-6 col-md-6 col-sm-12" >
                            <label for="identification_number">หมายเลขประจำตัว / Identification Number</label><br>
                            <input type="text" class="form-control" id="identification_number" name="identification_number"maxlength="13" value="{{$Freelancer_checked->Identification_number}}" disabled>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-12">
                            <label for="Identification_Number_file">เอกสารหมายเลขประจำตัว / Identification File </label><br>
                            <input type="file" id="Identification_Number_file" class="form-control" name="Identification_Number_file" accept="image/jpeg, image/png, image/svg" disabled>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-12 mt-2">
                            <a href="{{ asset($Freelancer_checked->Identification_file) }}" target="_blank" class="file-link2">
                                <img src="{{ asset('assets/images/open.png') }}" alt="Card image" >
                            </a>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label for="Bank_number">Bank Account Number</label><br>
                            <input type="text" id="Bank_number" name="Bank_number"maxlength="10" class="form-control" value="{{$Freelancer_checked->Bank_number}}" disabled>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label for="Account_Name">Bank Account Name</label><br>
                            <input type="text" id="Account_Name" name="Account_Name"maxlength="70" class="form-control" value="{{$Freelancer_checked->Bank_account_Name}}" disabled>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label for="Bank">Bank</label><br>
                            <select name="Mbank" id="Mbank" class="select2"disabled>
                                @foreach($Mbank as $item)
                                    <option value="{{ $item->id }}"{{$Freelancer_checked->Mbank == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-5 col-sm-12">
                            <label for="Bank_file">Bank File</label><br>
                            <input type="file" id="Bank_file" name="Bank_file" class="form-control" accept="image/jpeg, image/png, image/svg" disabled  >
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-12 mt-2 ">
                            <a href="{{ asset($Freelancer_checked->Bank_file) }}" target="_blank" class="file-link">
                                <img src="{{ asset('assets/images/open.png') }}" alt="Card image">
                            </a>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-3 col-sm-12"></div>
                        <div class="col-lg-6 col-sm-12 d-flex justify-content-center align-items-center">
                            <button type="button" class="btn btn-secondary lift"  onclick="window.location.href='{{ route('freelancer.index') }}'">{{ __('ย้อนกลับ') }}</button>
                        </div>
                        <div class="col-lg-3 col-sm-12"></div>
                    </div>
                </div> <!-- .card end -->
            </form>
        </div>
    </div> <!-- .row end -->
</div>


@include('script.script')

<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Please select an option"
        });
    });
    $('#first_name').on('keyup',function(){
        var first_name = $(this).val();
        var last_name =$('#last_name').val();
        $('#Account_Name').val(first_name+" "+last_name);
        console.log(last_name);
    });
    $('#last_name').on('keyup',function(){
        var first_name = $('#first_name').val();
        var last_name = $(this).val();
        $('#Account_Name').val(first_name+" "+last_name);
        console.log(first_name);
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
            // เรียกใช้ฟังก์ชัน select_amphures() เพื่อเปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
            select_amphures();
        }
    }

    function Onclickreadonly(){
        var startDate = document.getElementById('contract_rate_start_date').value;
        if (startDate !== '') {
            // หากมีค่า กำหนด input field ที่มี id เป็น contract_rate_end_date เป็น readonly
            document.getElementById('contract_rate_end_date').readOnly = false;
        } else {
            // หากไม่มีค่า กำหนด input field ที่มี id เป็น contract_rate_end_date เป็น readonly
            document.getElementById('contract_rate_end_date').readOnly = true;
        }
    }
    //----------------------------------------------------------------------------
    function select_province(){
        var provinceID = $('#province').val();
        jQuery.ajax({
            type:   "GET",
            url:    "{!! url('/Freelancer/checked/create/amphures/"+provinceID+"') !!}",
            datatype:   "JSON",
            async:  false,
            success: function(result) {
                jQuery('#amphures').children().remove().end();
                //ตัวแปร
                $('#amphures').append(new Option('', ''));
                jQuery.each(result.data, function(key, value) {
                    var amphures = new Option(value.name_th,value.id);
                    $('#amphures').append(amphures);
                    console.log(amphures);
                });
            },
        })
    }

    function select_amphures(){
        var amphuresID  = $('#amphures').val();
        $.ajax({
            type:   "GET",
            url:    "{!! url('/Freelancer/checked/create/Tambon/"+amphuresID+"') !!}",
            datatype:   "JSON",
            async:  false,
            success: function(result) {
                jQuery('#Tambon').children().remove().end();
                $('#Tambon').append(new Option('', ''));
                jQuery.each(result.data, function(key, value) {
                    var Tambon  = new Option(value.name_th,value.id);
                    $('#Tambon ').append(Tambon );
                });
            },
        })
    }
    function select_Tambon(){
        var Tambon  = $('#Tambon').val();
        $.ajax({
            type:   "GET",
            url:    "{!! url('/Freelancer/checked/create/districts/"+Tambon+"') !!}",
            datatype:   "JSON",
            async:  false,
            success: function(result) {
                jQuery('#zip_code').children().remove().end();
                $('#zip_code').append(new Option('', ''));
                jQuery.each(result.data, function(key, value) {
                    var zip_code  = new Option(value.zip_code,value.zip_code);
                    $('#zip_code ').append(zip_code );
                });
            },
        })
    }
    var loadFile = function (event) {
        var image = document.getElementById("output");
        image.src = URL.createObjectURL(event.target.files[0]);
    };
</script>
<script>
    document.getElementById('add-phone').addEventListener('click', function() {
        var phoneContainer = document.getElementById('phone-container');
        var newCol = document.createElement('div');
        newCol.classList.add('col-lg-12', 'col-md-6', 'col-sm-12');
        newCol.innerHTML = `
            <div class="phone-group show">
                <input type="text" name="phone[]" class="form-control" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
                <button type="button" class="remove-phoneed">ลบ</button>
            </div>
        `;
        phoneContainer.appendChild(newCol);

        // Add the show class after a slight delay to trigger the transition
        setTimeout(function() {
            newCol.querySelector('.phone-group').classList.add('show');
        }, 10);

        attachRemoveEvent(newCol.querySelector('.remove-phoneed'));
    });

    function attachRemoveEvent(button) {
        button.addEventListener('click', function() {
            var phoneContainer = document.getElementById('phone-container');
            if (phoneContainer.childElementCount > 1) {
                phoneContainer.removeChild(button.closest('.col-lg-12'));
            }
        });
    }

    // Attach the remove event to both initial remove buttons
    document.querySelectorAll('.remove-phone').forEach(function(button) {
        attachRemoveEvent(button);
    });
    document.querySelectorAll('.remove-phoneed').forEach(function(button) {
        attachRemoveEvent(button);
    });
</script>
@endsection
