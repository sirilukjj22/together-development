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
@section('content')
    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <small class="text-muted">Welcome to Create Guest.</small>
                    <div class=""><span class="span1">Create Guest (เพิ่มลูกค้า)</span></div>
                </div>
            </div> <!-- .row end -->
        </div>
    </div>

    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
            <div class="row align-items-center mb-2" >
                @if (session("success"))
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">บันทึกสำเร็จ!</h4>
                    <hr>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
                @endif
                <div class="col">
                    <ol class="breadcrumb d-inline-flex bg-transparent p-0 m-0">
                        <li></li>
                        <li></li>
                        <li></li>
                    </ol>
                </div>
            </div> <!-- Row end  -->
        </div> <!-- Row end  -->
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-11 col-md-11 col-sm-12"></div>
                                <div class="col-lg-1 col-md-1 col-sm-12">
                                    <input style=" float:right;" type="text" class="form-control" id="Profile_ID" name="Profile_ID" maxlength="70" required value="{{$N_Profile}}" disabled>
                                </div>
                            </div>
                            <form action="{{route('saveguest')}}" method="POST">
                                @csrf
                                <div class="row mt-3">
                                    <div class="col-lg-2 col-md-2 col-sm-12">
                                        <label for="Preface" >คำนำหน้า / Title</label><br>
                                        <select name="Preface" id="PrefaceSelect" class="select2" required>
                                            <option value=""></option>
                                            @foreach($prefix as $item)
                                            <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-12">
                                        <label for="first_name">ชื่อจริง / First Name</label><br>
                                        <input type="text"class="form-control" placeholder="First Name" id="first_name" name="first_name" maxlength="70" required>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-12"><label for="last_name">นามสกุล / Last Name</label><br>
                                        <input type="text" class="form-control" placeholder="Last Name" id="last_name" name="last_name" maxlength="70" required>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-6 col-md-6 col-sm-12"><label for="country">ประเทศ / Country</label><br>
                                        <select name="countrydata" id="countrySelect" class="select2" onchange="showcityInput()">
                                            @foreach($country as $item)
                                                <option value="{{ $item->ct_nameENG }}" {{ $item->ct_nameENG == 'Thailand' ? 'selected' : '' }}>
                                                    {{ $item->ct_nameENG }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12" id="citythai">
                                        <label for="city">จังหวัด / Province</label><br>
                                        <select name="province" id="province" class="select2" onchange="select_province()">
                                            <option value=""></option>
                                            @foreach($provinceNames as $item)
                                            <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2">
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
                                <div class="row mt-2">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <label for="address">ที่อยู่ / Address</label><br>
                                        <textarea type="text" id="address" name="address" rows="5" cols="25" class="textarea form-control" aria-label="With textarea" required></textarea>
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
                                        <!-- Initial input fields -->
                                        <div class="col-lg-4 col-md-6 col-sm-12 mt-2">
                                            <div class="input-group show">
                                                <input type="text" name="phone[]" class="form-control phone" maxlength="14" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
                                                <button type="button" class="btn btn-outline-danger remove-phone"><i class="bi bi-x-circle" style="width:100%;"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="email">อีเมล / Email</label><br>
                                        <input class="email form-control" type="text" id="email" name="email" maxlength="70" required>
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
                                        <input type="text" class="form-control idcard" id="identification_number" name="identification_number"maxlength="17" placeholder="เลขประจำตัวผู้เสียภาษี" required>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="contract_rate_start_date">Contract Rate Start Date</label><br>
                                        <div class="datestyle"><input class="form-control" type="date" id="contract_rate_start_date" name="contract_rate_start_date" onchange="Onclickreadonly()"disabled></div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="contract_rate_end_date">Contract Rate End Date</label><br>
                                        <div class="datestyle">
                                        <input type="date" class="form-control" id="contract_rate_end_date" name="contract_rate_end_date" disabled>
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
                                            <input type="text" class="form-control" id="discount_contract_rate" name="discount_contract_rate" oninput="checkInput()" min="0" max="100" disabled>
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <label for="latest_introduced_by">แนะนำล่าสุดโดย / Latest Introduced By</label><br>
                                        <input type="text" class="form-control" id="latest_introduced_by" name="latest_introduced_by">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-3 col-sm-12"></div>
                                    <div class="col-lg-6 col-sm-12 d-flex justify-content-center align-items-center">
                                        <button type="button" class="btn btn-secondary lift  btn-space"  onclick="window.location.href='{{ route('guest','index') }}'">{{ __('ย้อนกลับ') }}</button>
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
    </div>

    @include('script.script')
    <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/formatNumber.js')}}"></script>
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
            var province = document.getElementById("province");
            var amphuresSelect = document.getElementById("amphures");
            var tambonSelect = document.getElementById("Tambon");
            var zipCodeSelect = document.getElementById("zip_code");

            // เช็คค่าที่ถูกเลือกใน dropdown list เมือง
            if (countrySelect.value !== "Thailand") {

                province.disabled = true;
                // ปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
                amphuresSelect.disabled = true;
                tambonSelect.disabled = true;
                zipCodeSelect.disabled = true;
            } else {
                // ถ้าไม่เลือก "Other_countries" ซ่อน input field สำหรับเมืองอื่นๆ และแสดง input field สำหรับเมืองไทย
                province.disabled = false;
                amphuresSelect.disabled = false;
                tambonSelect.disabled = false;
                zipCodeSelect.disabled = false;
                select_amphures();
                select_province();
                select_Tambon();
                $('#zip_code').empty();
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
        function formatPhoneNumber(value) {
            value = value.replace(/\D/g, ""); // เอาตัวอักษรที่ไม่ใช่ตัวเลขออก
            let formattedValue = "";

            if (value.length > 0) {
                formattedValue += value.substring(0, 3); // xxx
            }
            if (value.length > 3) {
                formattedValue += "-" + value.substring(3, 6); // xxx-xxx
            }
            if (value.length > 6) {
                formattedValue += "-" + value.substring(6, 10); // xxx-xxx-xxxx
            }

            return formattedValue;
        }
        document.getElementById('add-phone').addEventListener('click', function() {
            var phoneContainer = document.getElementById('phone-container');
            var newCol = document.createElement('div');
            newCol.classList.add('col-lg-4', 'col-md-6', 'col-sm-12');
            newCol.innerHTML = `
                <div class="input-group mt-2">
                    <input type="text" name="phone[]" class="form-control" maxlength="14" oninput="formatAndUpdate(this)" required>
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
        function formatAndUpdate(input) {
            const formattedValue = formatPhoneNumber(input.value);
            input.value = formattedValue;
        }
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
