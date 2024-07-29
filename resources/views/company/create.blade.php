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
                <small class="text-muted">Welcome to Create Company / Agent.</small>
                <h1 class="h4 mt-1">Create Company / Agent (เพิ่มบริษัทและตัวแทน)</h1>
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
                            <h1 class="h4 mt-1">บริษัทและตัวแทน</h1>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-12">
                            <input style=" float:right;" type="text" class="form-control" id="Profile_ID" name="Profile_ID" maxlength="70" required value="{{$N_Profile}}" disabled>
                        </div>
                    </div>
                    <form id="myForm" action="{{route('Company.save')}}" method="POST" >
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="Company_type">ประเภทบริษัท / Company Type</label>
                                <select name="Company_type" id="Company_type" class="form-select" required>
                                    <option value="" selected disabled>Company Type</option>
                                    @foreach($MCompany_type as $item)
                                    <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-8 col-md-6 col-sm-12">
                                <label for="Company_Name">ชื่อบริษัท / Company Name</label>
                                <input type="text" class="form-control" id="Company_Name" name="Company_Name" maxlength="70" placeholder="Company Name" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="Branch">สาขา / Company Branch</label>
                                <input type="text" id="Branch" class="form-control" name="Branch" maxlength="70" placeholder="Company Branch" required>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="Market" >กลุ่มตลาด / Market</label>
                                <select name="Mmarket" id="Mmarket"  class="select2" required>
                                    <option value="" selected disabled>กลุ่มตลาด</option>
                                    @foreach($Mmarket as $item)
                                    <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="booking_channel">ช่องทางการจอง / Booking Channel</label><br>
                                <select name="booking_channel" id="booking_channel" class="select2" required>
                                    <option value="" selected disabled>ช่องทางการจอง</option>
                                    @foreach($booking_channel as $item)
                                    <option value="{{ $item->id }}">{{ $item->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="country">ประเทศ / Country</label>
                                <select name="countrydata" id="countrySelect" class="form-select" onchange="showcityInput()" required>
                                    <option value="Thailand">ประเทศไทย</option>
                                    <option value="Other_countries">ประเทศอื่นๆ</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <label for="address">ที่อยู่ / Address</label>
                                <textarea  type="text" id="address" name="address" rows="5" cols="25" class="form-control" aria-label="With textarea" required></textarea>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-3 col-md-6 col-sm-12" id="cityInput" style="display:none;">
                                <label for="city">จังหวัด / Province</label>
                                <input type="text" class="form-control" id="city" name="city">
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12" id="citythai" style="display:block;">
                                <label for="city">จังหวัด / Province</label><br>
                                <select name="province" id="province" class="select2" onchange="select_province()" required>
                                    <option value="" selected disabled>เลือกจังหวัด</option>
                                    @foreach($provinceNames as $item)
                                    <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label for="Amphures">อำเภอ / District</label><br>
                                <select name="amphures" id="amphures" class="select2" onchange="select_amphures()" required>
                                    <option value="" selected disabled>เลือกอำเภอ</option>

                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label for="Tambon">ตำบล / Subdistrict </label><br>
                                <select name="Tambon" id="Tambon" class="select2" onchange="select_Tambon()" required>
                                    <option value="">เลือกตำบล</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label for="zip_code">รหัสไปรษณีย์ / Postal Code</label>
                                <select name="zip_code" id="zip_code" class="select2" required>
                                    <option value="">รหัสไปรษณีย์</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="Company_Phone" class="flex-container">
                                    โทรศัพท์บริษัท / Company Phone number
                                </label>
                                <button type="button" class="btn btn-color-green my-2" id="add-input">เพิ่มหมายเลขโทรศัพท์</button>
                                <div id="inputs-container">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control"  name="phone_company[]" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);"required>
                                        <button class="btn btn-outline-danger" type="button" id="remove-input"><i class="bi bi-x-circle" style="width:100%;"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="Company_Phone" class="flex-container">
                                    แฟกซ์ของบริษัท / Company Fax number
                                </label>
                                <button type="button" class="btn btn-color-green my-2" id="add-fax">เพิ่มหมายเลขแฟกซ์</button>
                                <div id="fax-container">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control"  name="fax[]" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);"required>
                                        <button class="btn btn-outline-danger" type="button" id="remove-fax"><i class="bi bi-x-circle" style="width:100%;"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="Company_Email">ที่อยู่อีเมลของบริษัท / Company Email</label>
                                <input type="text" class="form-control" id="Company_Email" name="Company_Email" maxlength="70" placeholder="Company Email" required>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="Company_Website">เว็บไซต์ของบริษัท / Company Website</label><br>
                                <input type="text" class="form-control" id="Company_Website" name="Company_Website" maxlength="70" placeholder="Company Website" required>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="Taxpayer_Identification">เลขประจำตัวผู้เสียภาษี / Tax identification number</label><br>
                                <input type="text" class="form-control" id="Taxpayer_Identification" name="Taxpayer_Identification" maxlength="13" placeholder="เลขประจำตัวผู้เสียภาษี" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="Discount_Contract_Rate">อัตราคิดลด / Discount Contract Rate</label><br>
                                <input type="text" placeholder="อัตราคิดลด" id="Discount_Contract_Rate" class="form-control" name="Discount_Contract_Rate" maxlength="70" disabled>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="contract_rate_start_date">Contract Rate Start Date</label><br>
                                <input type="date" id="contract_rate_start_date" class="form-control" name="contract_rate_start_date" onchange="Onclickreadonly()" disabled>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="contract_rate_end_date">Contract Rate End Date</label><br>
                                <input type="date" id="contract_rate_end_date" class="form-control" name="contract_rate_end_date" readonly disabled>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <label for="Lastest_Introduce_By">แนะนำล่าสุดโดย / Lastest Introduce By</label><br>
                                <input type="text" id="Lastest_Introduce_By" class="form-control" name="Lastest_Introduce_By" maxlength="70" required>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <label for="Lastest_Introduce_By">Company Commission</label><br>
                                <input type="text" id="Lastest_Introduce_By" class="form-control" name="Lastest_Introduce_By" maxlength="70" disabled>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="custom-accordion">
                                    <input type="checkbox" id="trigger1" />
                                    <label for="trigger1">ติดต่อ / Contact</label>
                                    <div class="custom-accordion-content">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                    <span for="Preface" style="padding: 5px;">คำนำหน้า / Title</span><br>
                                                    <select name="Preface" id="Mprefix" class="form-select">
                                                        <option value=""></option>
                                                        @foreach($Mprefix as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-7 col-md-7 col-sm-7"></div>
                                                <div class="col-lg-1 col-md-1 col-sm-12 ">
                                                    <span>{{$A_Profile}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <span >First Name</span>
                                                <input type="text" class="form-control" id="first_nameAgent" name="first_nameAgent" maxlength="70" required>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <span  >Last Name</span>
                                                <input type="text" class="form-control" id="last_nameAgent" name="last_nameAgent" maxlength="70" required>
                                            </div>
                                        </div>
                                        <div  class="row mt-2">
                                            <div class="col-lg-4 col-md-4 col-sm-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                                    <span class="form-check-label" for="flexRadioDefault1">
                                                        ที่อยู่ตามบริษัท
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-lg-4 col-md-6 col-sm-12">
                                                <span class="labelcontact" for="">Country</span>
                                                <select name="countrydataA" id="countrySelectA" class="form-select" onchange="showcityAInput()" required>
                                                    <option value="Thailand">ประเทศไทย</option>
                                                    <option value="Other_countries">ประเทศอื่นๆ</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-12" id="cityInputA" style="display:none;">
                                                <span class="labelcontact" for="">City</span>
                                                <input type="text" class="form-control" id="cityA" name="cityA">
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-12" id="citythaiA" style="display:block;">
                                                <span class="labelcontact" for="">City</span>
                                                <select name="provinceAgent" id="provinceAgent" class="select2" onchange="provinceA()" style="width: 100%;" required>
                                                    <option value=""></option>
                                                    @foreach($provinceNames as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-12">
                                                <span class="labelcontact" for="">Amphures</span>
                                                <select name="amphuresA" id="amphuresA" class="select2" onchange="amphuresAgent()" style="width: 100%;" required>
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-lg-4 col-md-6 col-sm-12">
                                                <span class="labelcontact" for="">Tambon</span>
                                                <select name="TambonA" id="TambonA" class="select2" onchange="TambonAgent()" style="width: 100%;" required>
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-12">
                                                <span class="labelcontact" for="">Zip code</span>
                                                <select name="zip_codeA" id="zip_codeA" class="select2" style="width: 100%;" required>
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-12">
                                                <span class="labelcontact" for="">Email</span>
                                                <input class="form-control" type="email" class="form-control" id="EmailAgent" name="EmailAgent" style="width: 100%;" maxlength="70" required>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col">
                                                <span class="labelcontact" for="">Address</span>
                                                <textarea type="text" id="addressAgent" name="addressAgent" rows="3" cols="25" class="form-control" aria-label="With textarea" required></textarea>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <span for="Company_Phone" class="flex-container">
                                                    โทรศัพท์/ Phone number
                                                </span>
                                                <button type="button" class="btn btn-color-green my-2" id="add-phone">เพิ่มหมายเลขโทรศัพท์</button>
                                                <div id="phone-container">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" id="phone-main" name="phone[]" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);"required>
                                                        <button class="btn btn-outline-danger" type="button" id="remove-phone" disabled><i class="bi bi-x-circle" style="width:100%;"></i></button>
                                                    </div>
                                                </div>
                                                <div id="add-phone-orther"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                        {{-- <input type="text" name="provinceB" id="provinceB">
                        <input type="hidden" name="">
                        <input type="hidden" name="">
                        <input type="hidden" name=""> --}}
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the radio buttons
        const radio1 = document.getElementById('flexRadioDefault1');

        radio1.addEventListener('change', function() {
            if (radio1.checked) {
                var countrySelect =$('#countrySelect').val();
                var province = document.getElementById('province').value;
                var amphures = document.getElementById('amphures').value;
                var Tambon = document.getElementById('Tambon').value;
                var zip_code = document.getElementById('zip_code').value;
                var address = document.getElementById('address').value;
                $('#countrySelectA').val(countrySelect);
                $('#addressAgent').val(address);
                jQuery.ajax({
                    type: "GET",
                    url: "{!! url('/Company/provinces/" + province + "') !!}",
                    datatype: "JSON",
                    async: false,
                    success: function(result) {
                        jQuery.each(result.data, function(key, value) {
                            var provinceA = new Option(value.name_th, value.id);
                            if (value.id == province) {
                                provinceA.selected = true;
                            }
                            $('#provinceAgent').append(provinceA);
                        });
                    },
                })
                jQuery.ajax({
                    type: "GET",
                    url: "{!! url('/Company/amphuresA/" + province + "') !!}",
                    datatype: "JSON",
                    async: false,
                    success: function(result) {
                        jQuery.each(result.data, function(key, value) {
                            var amphuresA = new Option(value.name_th, value.id);
                            if (value.id == amphures) {
                                amphuresA.selected = true;
                            }
                            console.log(amphuresA);
                            $('#amphuresA').append(amphuresA);
                        });
                    },
                })
                $.ajax({
                    type: "GET",
                    url: "{!! url('/Company/TambonA/" + amphures + "') !!}",
                    datatype: "JSON",
                    async: false,
                    success: function(result) {
                        jQuery.each(result.data, function(key, value) {
                            var TambonA = new Option(value.name_th, value.id);
                            if (value.id == Tambon) {
                                TambonA.selected = true;
                            }
                            $('#TambonA').append(TambonA);
                            // console.log(TambonA);
                        });
                    },
                })
                $.ajax({
                    type: "GET",
                    url: "{!! url('/Company/districtsA/" + Tambon + "') !!}",
                    datatype: "JSON",
                    async: false,
                    success: function(result) {
                        console.log(result);
                        jQuery.each(result.data, function(key, value) {
                            var zip_codeA = new Option(value.zip_code, value.zip_code);
                            if (value.zip_code == zip_code) {
                                zip_codeA.selected = true;
                            }
                            $('#zip_codeA').append(zip_codeA);
                            console.log(zip_codeA);
                        });
                    },
                })
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Please select an option"
        });
    });
    $(document).ready(function() {
        $('#trigger1').change(function() {
            var isChecked = $(this).is(':checked');
            var valueToSend = isChecked ? '1' : '';
            console.log(valueToSend); // ถ้า checkbox ถูกเลือก จะส่งค่า '1' ไป ถ้าไม่ถูกเลือก จะไม่ส่งค่าไป
            if (isChecked) {
                // ตรวจสอบว่ามีค่าใน Company_Name หรือไม่
                var Company_Name = $('#Company_Name').val();
                var Branch = $('#Branch').val();
                if (Company_Name.trim() === '') {

                    alert('กรุณากรอกข้อมูล Company Name ก่อน');
                    // ยกเลิกการเลือก checkbox ในกรณีที่ไม่มีค่าใน Company_Name
                    $(this).prop('checked', false);
                    return;
                }
            }
            $.ajax({
                url: '/Company/check/company', // Your Laravel route
                type: 'POST',
                data: {
                    Company_Name: Company_Name,
                    Branch: Branch,
                    _token: '{{ csrf_token() }}' // Ensure CSRF token is included
                },
                success: function(response) {

                    if (response && response.representative !== null) {
                        console.log(response.representative.prefix);
                        $('#AProfile_ID').val(response.representative.Profile_ID).prop('disabled', true);
                        $('#Mprefix').val(response.representative.prefix).trigger('change').prop('disabled', true);
                        $('#first_nameAgent').val(response.representative.First_name).prop('disabled', true);
                        $('#last_nameAgent').val(response.representative.Last_name).prop('disabled', true);
                        $('#countrySelectA').val(response.representative.Country).trigger('change').prop('disabled', true);
                        $('#provinceAgent').val(response.representative.City).trigger('change').prop('disabled', true);
                        $('#amphuresA').val(response.representative.Amphures).trigger('change').prop('disabled', true);

                        $('#TambonA').val(response.representative.Tambon).trigger('change').prop('disabled', true);
                        $('#zip_codeA').val(response.representative.Zip_Code).trigger('change').prop('disabled', true);
                        $('#addressAgent').val(response.representative.Address).prop('disabled', true);
                        $('#EmailAgent').val(response.representative.Email).prop('disabled', true);
                        $('#add-phone').prop('disabled', true);
                        $('#add-phone-orther').children().remove().end();
                       console.log(response.phone);
                       $.each(response.phone, function(key, val) {
                        // Disable the first phone input and set its value
                        if (key == 0) {
                            $('#phone-main').val(val.Phone_number).prop('disabled', true);
                            console.log(val.Phone_number);
                        } else {
                            // Create a new input element for additional phone numbers
                            var phoneInput = $('<input type="text" id="phone-' + key + '" name="phone[]" value="' + val.Phone_number + '" class="form-control mt-3" maxlength="10">');
                            phoneInput.prop('disabled', true);
                            // Disable the input fieldremove-input
                            $('#add-phone-orther').append(phoneInput);
                        }

                        // Optionally add a delete button for each phone input (uncomment if needed)
                        // $('#add-phone-orther').append('<button type="button" id="btn-delete-' + key + '" class="remove-phone" onclick="dele_phone(' + key + ')">ลบ</button>');
                    });


                        //console.log(response.representative);

                    } else if (response && response.CompanyCountA) {
                        $('#AProfile_ID').val(response.CompanyCountA).prop('disabled', true);
                    } else if (response && response.representative == null) {

                        $('#AProfile_ID').val('1');
                        $('#Mprefix').val('').prop('disabled', false);
                        $('#first_nameAgent').val('').prop('disabled', false);
                        $('#last_nameAgent').val('').prop('disabled', false);
                        $('#countrySelectA').val('').prop('disabled', false);
                        $('#provinceAgent').val('').prop('disabled', false);
                        $('#amphuresA').val('').prop('disabled', false);
                        $('#TambonA').val('').prop('disabled', false);
                        $('#zip_codeA').val('').prop('disabled', false);
                        $('#addressAgent').val('').prop('disabled', false);
                        $('#EmailAgent').val('').prop('disabled', false);
                        $('#add-phone').val('').prop('disabled', false);
                        $('#phone-main').val('').prop('disabled', false);
                        // Clear previous inputs and buttons
                        $('#add-phone-orther').empty();

                        $.each(response.phone, function(keyother, val) {
                            if (keyother == 1) {
                                $('#phone-main').val(val).prop('disabled', false);
                            } else {
                                var phoneInput = $('<input type="text" id="phone-' + keyother + '" name="phone[]" value="' + val + '" class="form-control" maxlength="10">');
                                $('#add-phone-orther').append(phoneInput);
                                $('#add-phone-orther').append('<button type="button" id="btn-delete-' + keyother + '" onclick="dele_phone(' + keyother + ')">ลบ</button>');
                            }
                        });

                    }
                },
                error: function(xhr) {
                    // Handle error response
                    console.error(xhr.responseText);
                }
            });

        });
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
        } else if (countrySelect.value === "Thailand") {
            // เช็คค่าที่ถูกเลือกใน dropdown list เมือง

            // ถ้าเลือก "Other_countries" แสดง input field สำหรับเมืองอื่นๆ และซ่อน input field สำหรับเมืองไทย
            cityInput.style.display = "none";
            citythai.style.display = "block";
            // ปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
            amphuresSelect.disabled = false;
            tambonSelect.disabled = false;
            zipCodeSelect.disabled = false;

            // เรียกใช้ฟังก์ชัน select_amphures() เพื่อเปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
            select_amphures();
        }
    }
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
                    $('#zip_code ').append(zip_code);
                });
            },
        })
    }
    //---------------------------------------------------2------------------------------
    function provinceA() {
        var provinceAgent = $('#provinceAgent').val();
        jQuery.ajax({
            type: "GET",
            url: "{!! url('/Company/amphuresA/" + provinceAgent + "') !!}",
            datatype: "JSON",
            async: false,
            success: function(result) {
                console.log(result);
                jQuery('#amphuresA').children().remove().end();
                $('#amphuresA').append(new Option('', ''));
                jQuery.each(result.data, function(key, value) {
                    var amphuresA = new Option(value.name_th, value.id);
                    $('#amphuresA').append(amphuresA);

                });
            },
        })

    }

    function amphuresAgent() {
        var amphuresAgent = $('#amphuresA').val();
        console.log(amphuresAgent);
        $.ajax({
            type: "GET",
            url: "{!! url('/Company/TambonA/" + amphuresAgent + "') !!}",
            datatype: "JSON",
            async: false,
            success: function(result) {
                jQuery('#TambonA').children().remove().end();
                $('#TambonA').append(new Option('', ''));
                jQuery.each(result.data, function(key, value) {
                    var TambonA = new Option(value.name_th, value.id);
                    $('#TambonA').append(TambonA);
                    // console.log(TambonA);
                });
            },
        })
    }

    function TambonAgent() {
        var TambonAgent = $('#TambonA').val();
        console.log(TambonAgent);
        $.ajax({
            type: "GET",
            url: "{!! url('/Company/districtsA/" + TambonAgent + "') !!}",
            datatype: "JSON",
            async: false,
            success: function(result) {
                console.log(result);
                jQuery('#zip_codeA').children().remove().end();
                //console.log(result);
                $('#zip_codeA').append(new Option('', ''));
                jQuery.each(result.data, function(key, value) {
                    var zip_codeA = new Option(value.zip_code, value.zip_code);
                    $('#zip_codeA').append(zip_codeA);
                    //console.log(zip_codeA);
                });
            },
        })
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const addButton = document.getElementById('add-input');
        const inputsContainer = document.getElementById('inputs-container');
        let inputCount = 1;

        function toggleButtons() {
            const removeButtons = inputsContainer.querySelectorAll('.remove-input');
            removeButtons.forEach(btn => btn.disabled = (inputCount === 1));
        }

        function createInputGroup() {
            const inputGroup = document.createElement('div');
            inputGroup.classList.add('input-group', 'mb-3');

            inputGroup.innerHTML = `
                <input type="text" class="form-control" name="phone_company[]" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
                <button class="btn btn-outline-danger remove-input" type="button"><i class="bi bi-x-circle" ></i></button>
            `;

            inputGroup.querySelector('.remove-input').addEventListener('click', function() {
                inputsContainer.removeChild(inputGroup);
                inputCount--;
                toggleButtons();
            });

            return inputGroup;
        }

        addButton.addEventListener('click', function() {
            inputsContainer.appendChild(createInputGroup());
            inputCount++;
            toggleButtons();
        });

        inputsContainer.querySelector('.remove-input').addEventListener('click', function() {
            inputsContainer.querySelector('.input-group').remove();
            inputCount--;
            toggleButtons();
        });

        toggleButtons(); // Initialize button states

    });
    //-------------------------fax-----------------------------
    document.addEventListener("DOMContentLoaded", function() {
        const addButton = document.getElementById('add-fax');
        const inputsContainer = document.getElementById('fax-container');
        let inputCount = 1;

        function toggleButtons() {
            const removeButtons = inputsContainer.querySelectorAll('.remove-fax');
            removeButtons.forEach(btn => btn.disabled = (inputCount === 1));
        }

        function createInputGroup() {
            const inputGroup = document.createElement('div');
            inputGroup.classList.add('input-group', 'mb-3');

            inputGroup.innerHTML = `
                <input type="text" class="form-control" name="fax[]" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
                <button class="btn btn-outline-danger remove-fax" type="button"><i class="bi bi-x-circle" ></i></button>
            `;

            inputGroup.querySelector('.remove-fax').addEventListener('click', function() {
                inputsContainer.removeChild(inputGroup);
                inputCount--;
                toggleButtons();
            });

            return inputGroup;
        }

        addButton.addEventListener('click', function() {
            inputsContainer.appendChild(createInputGroup());
            inputCount++;
            toggleButtons();
        });

        inputsContainer.querySelector('.remove-fax').addEventListener('click', function() {
            inputsContainer.querySelector('.input-group').remove();
            inputCount--;
            toggleButtons();
        });

        toggleButtons(); // Initialize button states

    });
    //-------------------------------------phone---------------------------------------
    document.addEventListener("DOMContentLoaded", function() {
        const addButton = document.getElementById('add-phone');
        const inputsContainer = document.getElementById('phone-container');
        let inputCount = 1;

        function toggleButtons() {
            const removeButtons = inputsContainer.querySelectorAll('.remove-phone');
            removeButtons.forEach(btn => btn.disabled = (inputCount === 1));
        }

        function createInputGroup() {
            const inputGroup = document.createElement('div');
            inputGroup.classList.add('input-group', 'mb-3');

            inputGroup.innerHTML = `
                <input type="text" class="form-control phone-input" name="phone[]" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
                <button class="btn btn-outline-danger remove-phone" type="button"><i class="bi bi-x-circle" style="width:100%;"></i></button>
            `;

            inputGroup.querySelector('.remove-phone').addEventListener('click', function() {
                inputsContainer.removeChild(inputGroup);
                inputCount--;
                toggleButtons();
            });

            return inputGroup;
        }

        addButton.addEventListener('click', function() {
            inputsContainer.appendChild(createInputGroup());
            inputCount++;
            toggleButtons();
        });

        inputsContainer.querySelector('.remove-phone').addEventListener('click', function() {
            inputsContainer.querySelector('.input-group').remove();
            inputCount--;
            toggleButtons();
        });

        toggleButtons(); // Initialize button states
    });
</script>
<script>
    function confirmSubmit(event) {
        event.preventDefault(); // Prevent the form from submitting

        var Company_Name = $('#Company_Name').val();
        var Branch = $('#Branch').val();

        // Check if Company_Name or Branch is empty
        if (!Company_Name || !Branch || !Company_type || !booking_channel || !address
            || !Mmarket || !addressAgent || !EmailAgent || !Lastest_Introduce_By || !contract_rate_end_date || !contract_rate_start_date
            || !Discount_Contract_Rate || !Taxpayer_Identification || !Company_Website || !Company_Email
        ) {
            // Display error message using Swal
            Swal.fire({
                title: "ข้อมูลไม่ครบถ้วน",
                text: "กรุณากรอกข้อมูลบริษัทและสาขาให้ครบถ้วน",
                icon: "error",
                confirmButtonText: "ตกลง",
                confirmButtonColor: "#dc3545"
            });
            return; // Stop further execution
        }

        var message = `หากบันทึกข้อมูลบริษัท ${Company_Name} สาขา ${Branch} หรือไม่`;
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
                console.log(1);
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
