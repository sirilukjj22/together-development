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
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Edit Tax Guest</div>
                </div>
                <div class="col-auto">
                </div>
            </div> <!-- .row end -->
        </div>
    </div>
    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
            <div class="row align-items-center mb-2" >
                @if (session("success"))
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">Save successful.</h4>
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
                    <div class="card p-4 mb-4">
                        <div class="row">
                            <div class="col-lg-11 col-md-11 col-sm-12"></div>
                            <div class="col-lg-1 col-md-1 col-sm-12">
                                <input style=" float:right;" type="text" class="form-control" id="Profile_ID" name="Profile_ID" maxlength="70" required value="{{$Profile_ID}}" disabled>
                            </div>
                        </div>
                        <form action="{{url('/guest/tax/edit/update/'.$Guest->id)}}" method="POST">
                            @csrf
                            <div class="row mt-2">
                                <div class="col-sm-4 col-4">
                                    <span for="Country">Add Tax</span>
                                    <select name="Tax_Type" id="TaxSelectA" class="select2" onchange="showTaxInput()" >
                                        <option value="Company"{{$Guest->Tax_Type == "Company" ? 'selected' : ''}}>Company</option>
                                        <option value="Individual"{{$Guest->Tax_Type == "Individual" ? 'selected' : ''}}>Individual</option>
                                    </select>
                                </div>
                            </div>
                            <div id="com" style="display: block" >
                                <div class="row mt-2">
                                    <div class="col-sm-2 col-2">
                                        <label for="Company_type_tax">ประเภทบริษัท / Company Type</label>
                                        <select name="Company_type" id="Company_type" class="select2" required disabled>
                                            <option value=""></option>
                                            @foreach($MCompany_type as $item)
                                                <option value="{{ $item->id }}"{{$Guest->Company_type == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-5 col-5">
                                        <label for="Company_Name">ชื่อบริษัท / Company Name</label>
                                        <input type="text" id="Company_Name" class="form-control" name="Company_name" maxlength="70" value="{{$Guest->Company_name}}" >
                                    </div>
                                    <div class="col-sm-5 col-5 ">
                                        <label for="Branch">สาขา / Company Branch</label>
                                        <input type="text" id="Branch" name="Branch" class="form-control" maxlength="70" required value="{{$Guest->BranchTax}}" >
                                    </div>
                                </div>
                            </div>
                            <div id="Individual" style="display: none">
                                <div class="row mt-2">
                                    <div class="col-sm-2 col-2" >
                                        <span for="prefix">คำนำหน้า / Title</span>
                                        <select name="Company_type" id="Preface" class="select2" required disabled>
                                                <option value=""></option>
                                                @foreach($prefix as $item)
                                                    <option value="{{ $item->id }}"{{$Guest->Company_type == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-5 col-5">
                                        <span for="first_name">ชื่อ / First Name</span>
                                        <input type="text" id="first_name" class="form-control" name="first_name"maxlength="70"  value="{{$Guest->first_name}}"disabled>
                                    </div>
                                    <div class="col-sm-5 col-5">
                                        <span for="last_name" >นามสกุล / Last Name</span>
                                        <input type="text" id="last_name" class="form-control" name="last_name"maxlength="70"  value="{{$Guest->last_name}}"disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-6 col-6">
                                    <span for="Taxpayer_Identification">เลขบัตรประจำตัวประชาชน / Identification number</span>
                                    <input type="text" id="Taxpayer_Identification" class="form-control idcard" name="Taxpayer_Identification" maxlength="17" placeholder="เลขประจำตัวผู้เสียภาษี"  value="{{ formatIdCard($Guest->Taxpayer_Identification) }}">
                                </div>
                                <div class="col-sm-6 col-6">
                                    <span for="Email">อีเมล์ / Email</span>
                                    <input type="text" id="Email" class="form-control" name="Company_Email"maxlength="70"  value="{{$Guest->Company_Email}}">
                                </div>
                            </div>
                            <div class="mt-2">
                                <span for="Address">ที่อยู่ / Address</span>
                                <textarea type="text" id="Address" name="Address" rows="3" cols="25" class="form-control" aria-label="With textarea" >{{$Guest->Address}}</textarea>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-6 col-6">
                                    <span for="Country">ประเทศ / Country</span>
                                    <select name="Country" id="countrySelect" class="select2" onchange="showcityAInput()">
                                        @foreach($country as $item)
                                            <option value="{{ $item->ct_nameENG }}" {{ $item->ct_nameENG == $Guest->Country ? 'selected' : '' }}>
                                                {{ $item->ct_nameENG }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6 col-6">
                                    <span for="City">จังหวัด / City</span>
                                    <select name="City" id="province" class="select2" onchange="select_province()" style="width: 100%;" >
                                        <option value=""></option>
                                        @foreach($provinceNames as $item)
                                            <option value="{{ $item->id }}"{{$Guest->City == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-4 col-4">
                                    <span for="Amphures">อำเภอ / Amphures</span>
                                    <select name="Amphures" id="amphures" class="select2" onchange="select_amphures()" >
                                        @foreach($amphures as $item)
                                            <option value="{{ $item->id }}" {{ $Guest->Amphures == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4 col-4">
                                    <span for="Tambon">ตำบล / Tambon</span>
                                    <select name="Tambon" id ="Tambon" class="select2" onchange="select_Tambon()" style="width: 100%;">
                                        @foreach($Tambon as $item)
                                            <option value="{{ $item->id }}" {{ $Guest->Tambon == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4 col-4">
                                    <span for="Zip_Code">รหัสไปรษณีย์ / Zip_code</span>
                                    <select name="Zip_Code" id ="zip_code" class="select2"  style="width: 100%;">
                                        @foreach($Zip_code as $item)
                                            <option value="{{ $item->zip_code }}" {{ $Guest->Zip_Code == $item->zip_code ? 'selected' : '' }}>{{ $item->zip_code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-8 col-8">
                                    <label for="Phone_number">หมายเลขโทรศัพท์ / Phone Number</label>
                                    <button type="button" class="add-phone btn btn-color-green" id="add-phone" data-target="phone-container" >เพิ่มเบอร์โทรศัพท์</button>
                                </div>
                                <div id="phone-container" class="flex-container row">
                                    <!-- Initial input fields -->
                                    @foreach($phoneDataArray as $phone)
                                    <div class="col-lg-4 col-md-6 col-sm-12 mt-3">
                                        <div class="input-group show">
                                            <input type="text" name="phoneCom[]" class="form-control phone" maxlength="12" value="{{ formatPhoneNumber($phone['Phone_number']) }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                                            <button type="button" class="btn btn-outline-danger remove-phone"><i class="bi bi-x-circle" style="width:100%;"></i></button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-lg-3 col-sm-12"></div>
                                <div class="col-lg-6 col-sm-12 d-flex justify-content-center align-items-center">
                                    <button type="button" class="btn btn-secondary lift  btn-space" onclick="window.location.href='{{url('/guest/edit/'.$ID)}}'">{{ __('ย้อนกลับ') }}</button>
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

    @include('script.script')
    <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/formatNumber.js')}}"></script>
    <script>
        $(document).ready(function() {
            var TaxSelectA = $('#TaxSelectA');
            var TaxSelectA = TaxSelectA.val();
            var company = document.getElementById("com");
            var Individual = document.getElementById("Individual");
            var Preface = document.getElementById("Preface");
            var first_name = document.getElementById("first_name");
            var last_name = document.getElementById("last_name");
            var Company_type = document.getElementById("Company_type");
            var Company_Name = document.getElementById("Company_Name");
            var Branch = document.getElementById("Branch");
            if (TaxSelectA == "Company") {
                console.log(TaxSelectA);
                company.style.display = "block";
                Individual.style.display = "none";
                Preface.disabled = true;
                first_name.disabled = true;
                last_name.disabled = true;
                Company_type.disabled = false;
                Company_Name.disabled = false;
                Branch.disabled = false;
            }
            else if (TaxSelectA == "Individual"){
                console.log(TaxSelectA);
                company.style.display = "none";
                Individual.style.display = "block";
                Preface.disabled = false;
                first_name.disabled = false;
                last_name.disabled = false;
                Company_type.disabled = true;
                Company_Name.disabled = true;
                Branch.disabled = true;
            }
        });
        function showTaxInput() {
            var TaxSelectA = document.getElementById("TaxSelectA");
            var company = document.getElementById("com");
            var Individual = document.getElementById("Individual");
            var Preface = document.getElementById("Preface");
            var first_name = document.getElementById("first_name");
            var last_name = document.getElementById("last_name");
            var Company_type = document.getElementById("Company_type");
            var Company_Name = document.getElementById("Company_Name");
            var Branch = document.getElementById("Branch");


            if (TaxSelectA.value === "Company") {
                console.log(TaxSelectA);
                company.style.display = "block";
                Individual.style.display = "none";
                Preface.disabled = true;
                first_name.disabled = true;
                last_name.disabled = true;
                Company_type.disabled = false;
                Company_Name.disabled = false;
                Branch.disabled = false;
            } else if (TaxSelectA.value === "Individual"){
                console.log(TaxSelectA);
                company.style.display = "none";
                Individual.style.display = "block";
                Preface.disabled = false;
                first_name.disabled = false;
                last_name.disabled = false;
                Company_type.disabled = true;
                Company_Name.disabled = true;
                Branch.disabled = true;
            }
        }
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
            if (countrySelect.value === "Other_countries") {

                province.disabled = true;
                amphuresSelect.disabled = true;
                tambonSelect.disabled = true;
                zipCodeSelect.disabled = true;
            } else {

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
                <div class="input-group mt-3">
                    <input type="text" name="phoneCom[]" class="form-control" maxlength="12" oninput="formatAndUpdate(this)" required>
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
