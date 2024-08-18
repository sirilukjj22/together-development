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
                <small class="text-muted">Welcome to View Company Tax Invoice.</small>
                <h1 class="h4 mt-1">View Company Tax Invoice(ดูบริษัท)</h1>
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
                    <div class="row mt-2">
                        <div class="col-sm-4 col-4">
                            <span for="Country">Add Tax</span>
                            <select name="TaxSelectA" id="TaxSelectA" class="form-select" onchange="showTaxInput()" disabled>
                                <option value="Company"{{$viewTax->Tax_Type == "Company" ? 'selected' : ''}}>Company</option>
                                <option value="Individual"{{$viewTax->Tax_Type == "Individual" ? 'selected' : ''}}>Individual</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" id="Com" style="display: block">
                        <div class="row">
                            <div class="col-sm-6 col-6">
                                <label for="Company_type_tax">ประเภทบริษัท / Company Type</label>
                                <select name="Company_type_tax" id="Company_type_tax" class="form-select" disabled>
                                    <option value=""></option>
                                    @foreach($MCompany_type as $item)
                                        <option value="{{ $item->id }}" {{$viewTax->Company_type == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 col-6">
                                <label for="Company_Name">ชื่อบริษัท / Company Name</label>
                                <input type="text" id="Company_Name_tax" class="form-control" name="Company_Name_tax" maxlength="70" value="{{$viewTax->Companny_name}}" disabled>
                            </div>
                            <div class="col-sm-6 col-6 mt-2">
                                <label for="Branch">สาขา / Company Branch <input class="form-check-input" type="radio" name="flexRadioDefaultBranchTax" id="flexRadioDefaultBranchTax"disabled> สำนักงานใหญ่</label>
                                <input type="text" id="BranchTax" name="BranchTax" class="form-control" maxlength="70" required value="{{$viewTax->BranchTax}}" disabled>
                            </div>
                            <div class="col-sm-6 col-6 mt-2">
                                <label for="Taxpayer_Identification">เลขประจำตัวผู้เสียภาษี / Tax identification number</label><br>
                                <input type="text" id="Taxpayer_Identification" class="form-control" name="Taxpayer_Identification" maxlength="13" placeholder="เลขประจำตัวผู้เสียภาษี" value="{{$viewTax->Taxpayer_Identification}}" disabled>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="Company_id" class="form-control" name="Company_id" maxlength="70"  value="{{$viewTax->Profile_ID}}"disabled>
                    <div class="row mt-2" id="Title" style="display: none;">
                        <div class="row">
                            <div class="col-sm-6 col-6" >
                                <span for="prefix">คำนำหน้า / Title</span>
                                <select name="prefix" id="PrefaceSelectCom" class="form-select" disabled>
                                        <option value=""></option>
                                        @foreach($Mprefix as $item)
                                            <option value="{{ $item->id }}"{{$viewTax->Company_type == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                        @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 col-6">
                                <span for="first_name">First Name</span>
                                <input type="text" id="first_nameCom" class="form-control" name="first_nameCom"maxlength="70" disabled value="{{$viewTax->first_name}}">
                            </div>
                            <div class="col-sm-6 col-6 mt-2">
                                <span for="last_name" >Last Name</span>
                                <input type="text" id="last_nameCom" class="form-control" name="last_nameCom"maxlength="70" disabled value="{{$viewTax->last_name}}">
                            </div>
                            <div class="col-sm-6 col-6 mt-2">
                                <label for="Taxpayer_Identification">เลขบัตรประจำตัวประชาชน / Identification number</label><br>
                                <input type="text" id="Taxpayer_Identification" class="form-control" name="Taxpayer_Identification" maxlength="13" placeholder="เลขประจำตัวผู้เสียภาษี" disabled value="{{$viewTax->Taxpayer_Identification}}">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-4 col-4">
                            <span for="Country">Country</span>
                            <select name="countrydataA" id="countrySelectA" class="form-select" onchange="showcityAInput()"disabled>
                                <option value="Thailand" {{$viewTax->Country == "Thailand" ? 'selected' : ''}}>ประเทศไทย</option>
                                <option value="Other_countries" {{$viewTax->Country == "Other_countries" ? 'selected' : ''}}>ประเทศอื่นๆ</option>
                            </select>
                        </div>
                        <div class="col-sm-4 col-4" id="cityInputA" style="display:none;">
                            <span for="City">City</span>
                            <input type="text" id="cityA" class="form-control" name="cityA" value="{{$Other_City}}" disabled>
                        </div>
                        <div class="col-sm-4 col-4" id="citythaiA" style="display:block;">
                            <span for="City">City</span>
                            <select name="provinceAgent" id="provinceAgent" class="form-select" onchange="provinceA()" style="width: 100%;" disabled>
                                <option value=""></option>
                                @foreach($provinceNames as $item)
                                    <option value="{{ $item->id }}"{{$viewTax->City == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4 col-4">
                            <span for="Amphures">Amphures</span>
                            <select name="amphuresA" id="amphuresA" class="form-select" onchange="amphuresAgent()" disabled>
                                @foreach($amphures as $item)
                                    <option value="{{ $item->id }}" {{ $viewTax->Amphures == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-4 col-4">
                            <span for="Tambon">Tambon</span>
                            <select name="TambonA" id ="TambonA" class="form-select" onchange="TambonAgent()" style="width: 100%;"disabled>
                                @foreach($Tambon as $item)
                                    <option value="{{ $item->id }}" {{ $viewTax->Tambon == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4 col-4">
                            <span for="zip_code">zip_code</span>
                            <select name="zip_codeA" id ="zip_codeA" class="form-select"  style="width: 100%;"disabled>
                                @foreach($Zip_code as $item)
                                    <option value="{{ $item->id }}" {{ $viewTax->Zip_Code == $item->zip_code ? 'selected' : '' }}>{{ $item->zip_code }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4 col-4">
                            <span for="Email">Email</span>
                            <input type="text" id="EmailAgent" class="form-control" name="EmailAgent"maxlength="70" disabled value="{{$viewTax->Company_Email}}">
                        </div>
                    </div>
                    <div>
                        <span for="Address">Address</span>
                        <textarea type="text" id="addressAgent" name="addressAgent" rows="3" cols="25" class="form-control" aria-label="With textarea" disabled>{{$viewTax->Address}}</textarea>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-8 col-8">
                            <label for="Phone_number">หมายเลขโทรศัพท์ / Phone Number</label>
                            <button type="button" class="add-phone btn btn-color-green" id="add-phone" data-target="phone-container" disabled>เพิ่มเบอร์โทรศัพท์</button>
                        </div>
                        <div id="phone-container" class="flex-container row">
                            <!-- Initial input fields -->
                            @foreach($phonetaxDataArray as $phone)
                            <div class="col-lg-4 col-md-6 col-sm-12 mt-3">
                                <div class="input-group show">
                                    <input type="text" name="phone[]" class="form-control" maxlength="10" value="{{ $phone['Phone_number'] }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" disabled>
                                    <button type="button" class="btn btn-outline-danger remove-phone"disabled><i class="bi bi-x-circle" style="width:100%;"></i></button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-3 col-sm-12"></div>
                        <div class="col-lg-6 col-sm-12 d-flex justify-content-center align-items-center">
                            <button type="button" class="btn btn-secondary lift  btn-space"  onclick="window.location.href='{{url('/Company/edit/view/'.$CompanyID)}}'">{{ __('ย้อนกลับ') }}</button>
                        </div>
                        <div class="col-lg-3 col-sm-12"></div>
                    </div>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the radio buttons
        const Branch = document.getElementById('flexRadioDefaultBranchTax');

        Branch.addEventListener('change', function() {
            if (Branch.checked) {
                $('#BranchTax').val('สำนักงานใหญ่');
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        var TaxSelectA = $('#TaxSelectA');
        var TaxSelectA = TaxSelectA.val(); // ใช้ jQuery เพื่อเลือก element
        console.log(TaxSelectA); // ใช้ jQuery val() เพื่อดึงค่า
        var Company_type_tax = document.getElementById("Company_type_tax");
        var Company_Name_tax = document.getElementById("Company_Name_tax");
        var BranchTax = document.getElementById("BranchTax");
        var Title = document.getElementById("Title");
        var Com = document.getElementById("Com");
        var PrefaceSelectCom = document.getElementById("PrefaceSelectCom");
        var first_nameCom = document.getElementById("first_nameCom");
        var last_nameCom = document.getElementById("last_nameCom");

        if (TaxSelectA == "Company") {
            // ถ้าเลือก "Other_countries" แสดง input field สำหรับเมืองอื่นๆ และซ่อน input field สำหรับเมืองไทย
            Com.style.display = "block";
            Title.style.display = "none";
            // citythaiA.style.display = "none";
            // ปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
            Company_Name_tax.disabled = true;
            Company_type_tax.disabled = true;
            BranchTax.disabled = true;
            PrefaceSelectCom.disabled = true;
            first_nameCom.disabled = true;
            last_nameCom.disabled = true;

        } else if (TaxSelectA == "Individual"){
            Title.style.display = "block";
            // เช็คค่าที่ถูกเลือกใน dropdown list เมือง
            // ถ้าเลือก "Other_countries" แสดง input field สำหรับเมืองอื่นๆ และซ่อน input field สำหรับเมืองไทย
            Com.style.display = "none";
            // ปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
            Company_Name_tax.disabled = true;
            Company_type_tax.disabled = true;
            BranchTax.disabled = true;
            PrefaceSelectCom.disabled = true;
            first_nameCom.disabled = true;
            last_nameCom.disabled = true;
        }
    });

    // function showTaxInput() {
    //     var TaxSelectA = document.getElementById("TaxSelectA");
    //     var Company_type_tax = document.getElementById("Company_type_tax");
    //     var Company_Name_tax = document.getElementById("Company_Name_tax");
    //     var BranchTax = document.getElementById("BranchTax");
    //     var Title = document.getElementById("Title");
    //     var Com = document.getElementById("Com");
    //     var PrefaceSelectCom = document.getElementById("PrefaceSelectCom");
    //     var first_nameCom = document.getElementById("first_nameCom");
    //     var last_nameCom = document.getElementById("last_nameCom");


    //     if (TaxSelectA.value === "Company") {
    //         // ถ้าเลือก "Other_countries" แสดง input field สำหรับเมืองอื่นๆ และซ่อน input field สำหรับเมืองไทย
    //         Com.style.display = "block";
    //         Title.style.display = "none";
    //         // citythaiA.style.display = "none";
    //         // ปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
    //         Company_Name_tax.disabled = false;
    //         Company_type_tax.disabled = false;
    //         BranchTax.disabled = false;
    //         PrefaceSelectCom.disabled = true;
    //         first_nameCom.disabled = true;
    //         last_nameCom.disabled = true;

    //     } else if (TaxSelectA.value === "Individual"){
    //         Title.style.display = "block";
    //         // เช็คค่าที่ถูกเลือกใน dropdown list เมือง
    //         // ถ้าเลือก "Other_countries" แสดง input field สำหรับเมืองอื่นๆ และซ่อน input field สำหรับเมืองไทย
    //         Com.style.display = "none";
    //         // ปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
    //         Company_Name_tax.disabled = true;
    //         Company_type_tax.disabled = true;
    //         BranchTax.disabled = true;
    //         PrefaceSelectCom.disabled = false;
    //         first_nameCom.disabled = false;
    //         last_nameCom.disabled = false;
    //     }
    // }
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
        } else if (countrySelectA.value === "Thailand"){
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
    function provinceA(){
        var provinceAgent = $('#provinceAgent').val();
        jQuery.ajax({
            type:   "GET",
            url:    "{!! url('/Company/amphuresA/"+provinceAgent+"') !!}",
            datatype:   "JSON",
            async:  false,
            success: function(result) {
                jQuery('#amphuresA').children().remove().end();
                console.log(result);
                $('#amphuresA').append(new Option('', ''));
                jQuery.each(result.data, function(key, value) {
                    var amphuresA = new Option(value.name_th,value.id);
                    //console.log(amphuresA);
                    $('#amphuresA').append(amphuresA);
                });
            },
        })

    }
    function amphuresAgent(){
        var amphuresAgent  = $('#amphuresA').val();
        console.log(amphuresAgent);
        $.ajax({
            type:   "GET",
            url:    "{!! url('/Company/TambonA/"+amphuresAgent+"') !!}",
            datatype:   "JSON",
            async:  false,
            success: function(result) {
            // console.log(result);
                jQuery('#TambonA').children().remove().end();
                $('#TambonA').append(new Option('', ''));
                jQuery.each(result.data, function(key, value) {
                    var TambonA  = new Option(value.name_th,value.id);
                    $('#TambonA').append(TambonA );
                // console.log(TambonA);
                });
            },
        })
    }
    function TambonAgent(){
        var TambonAgent  = $('#TambonA').val();
        console.log(TambonAgent);
        $.ajax({
            type:   "GET",
            url:    "{!! url('/Company/districtsA/"+TambonAgent+"') !!}",
            datatype:   "JSON",
            async:  false,
            success: function(result) {
                console.log(result);
                jQuery('#zip_codeA').children().remove().end();
                //console.log(result);
                $('#zip_codeA').append(new Option('', ''));
                jQuery.each(result.data, function(key, value) {
                    var zip_codeA  = new Option(value.zip_code,value.zip_code);
                    $('#zip_codeA').append(zip_codeA);
                    //console.log(zip_codeA);
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
@endsection
