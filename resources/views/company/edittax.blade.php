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
        overflow: hidden;
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
        margin-right: 10px;
        font-size: 24px;
    }

    .custom-accordion input[type="checkbox"]:checked+label::before {
        content: "\2611";
    }

    .custom-accordion-content {
        font-size: 16px;
        padding: 5% 10%;
        display: none;
        border-top: 1px solid #ffffff;
    }

    .custom-accordion input[type="checkbox"]:checked+label+.custom-accordion-content {
        display: block;
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
                    <div class="span3">View Additional Company Tax Invoice</div>
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
                @if (session("error"))
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Save failed!</h4>
                        <hr>
                        <p class="mb-0">{{ session('error') }}</p>
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
                                <input style=" float:right;" type="text" class="form-control" id="Profile_ID" name="Profile_ID" maxlength="70" required value="{{$ComTax_ID}}" disabled>
                            </div>
                        </div>
                        <form id="myForm" action="{{url('/Company/editTax/update/'.$CompanyID.'/'.$viewTax->id)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                            <div class="row mt-2">
                                <div class="col-sm-4 col-4">
                                    <span for="Country">Add Tax</span>
                                    <select name="Tax_Type" id="TaxSelectA" class="select2" onchange="showTaxInput()" >
                                        <option value="Company"{{$viewTax->Tax_Type == "Company" ? 'selected' : ''}}>Company</option>
                                        <option value="Individual"{{$viewTax->Tax_Type == "Individual" ? 'selected' : ''}}>Individual</option>
                                    </select>
                                </div>
                            </div>
                            <div id="Com" style="display: block">
                                <div class="row mt-2">
                                    <div class="col-sm-6 col-6">
                                        <label for="Company_type_tax">ประเภทบริษัท / Company Type</label>
                                        <select name="Company_type" id="Company_type_tax" class="select2" >
                                            <option value=""></option>
                                            @foreach($MCompany_type as $item)
                                                <option value="{{ $item->id }}" {{$viewTax->Company_type == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-6">
                                        <label for="Company_Name">ชื่อบริษัท / Company Name</label>
                                        <input type="text" id="Company_Name_tax" class="form-control" name="Companny_name" maxlength="70" value="{{$viewTax->Companny_name}}" >
                                    </div>
                                    <div class="col-sm-6 col-6 mt-2">
                                        <label for="Branch">สาขา / Company Branch <span>&nbsp;&nbsp;&nbsp; </span><input class="form-check-input" type="radio" name="flexRadioDefaultBranchTax" id="flexRadioDefaultBranchTax"> สำนักงานใหญ่</label>
                                        <input type="text" id="BranchTax" name="BranchTax" class="form-control" maxlength="70" required value="{{$viewTax->BranchTax}}" >
                                    </div>
                                    <div class="col-sm-6 col-6 mt-2">
                                        <span for="Taxpayer_Identification">เลขประจำตัวผู้เสียภาษี / Tax identification number</span><br>
                                        <input type="text" id="Taxpayer_Identification" class="form-control idcard" name="Taxpayer_Identification" maxlength="17" placeholder="เลขประจำตัวผู้เสียภาษี" value="{{ formatIdCard($viewTax->Taxpayer_Identification) }}" >
                                    </div>
                                </div>
                            </div>
                            <div id="Title" style="display: none;">
                                <div class="row mt-2">
                                    <div class="col-sm-6 col-6" >
                                        <span for="prefix">คำนำหน้า / Title</span>
                                        <select name="Company_type" id="PrefaceSelectCom" class="select2" >
                                                <option value=""></option>
                                                @foreach($Mprefix as $item)
                                                    <option value="{{ $item->id }}"{{$viewTax->Company_type == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-6">
                                        <span for="first_name">ชื่อ / First Name</span>
                                        <input type="text" id="first_nameCom" class="form-control" name="first_name"maxlength="70"  value="{{$viewTax->first_name}}">
                                    </div>
                                    <div class="col-sm-6 col-6 mt-2">
                                        <span for="last_name" >นามสกุล / Last Name</span>
                                        <input type="text" id="last_nameCom" class="form-control" name="last_name"maxlength="70"  value="{{$viewTax->last_name}}">
                                    </div>
                                    <div class="col-sm-6 col-6 mt-2">
                                        <span for="Taxpayer_Identification">เลขบัตรประจำตัวประชาชน / Identification number</span><br>
                                        <input type="text" id="Taxpayer_Identification" class="form-control" name="Taxpayer_Identification idcard" maxlength="17" placeholder="เลขประจำตัวผู้เสียภาษี"  value="{{ formatIdCard($viewTax->Taxpayer_Identification) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <span for="Address">ที่อยู่ / Address</span>
                                <textarea type="text" id="addressAgent" name="Address" rows="3" cols="25" class="form-control" aria-label="With textarea" >{{$viewTax->Address}}</textarea>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-4 col-4">
                                    <span for="Country">ประเทศ / Country</span>
                                    <select name="Country" id="countrySelectA" class="select2" onchange="showcityAInput()">
                                        @foreach($country as $item)
                                            <option value="{{ $item->ct_nameENG }}" {{ $item->ct_nameENG == $viewTax->Country ? 'selected' : '' }}>
                                                {{ $item->ct_nameENG }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4 col-4">
                                    <span for="City">จังหวัด / Province</span>
                                    <select name="City" id="provinceAgent" class="select2" onchange="provinceA()" style="width: 100%;" >
                                        <option value=""></option>
                                        @foreach($provinceNames as $item)
                                            <option value="{{ $item->id }}"{{$viewTax->City == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4 col-4">
                                    <span for="Amphures">อำเภอ / District</span>
                                    <select name="Amphures" id="amphuresA" class="select2" onchange="amphuresAgent()" >
                                        @foreach($amphures as $item)
                                            <option value="{{ $item->id }}" {{ $viewTax->Amphures == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-4 col-4">
                                    <span for="Tambon">ตำบล / Subdistrict</span>
                                    <select name="Tambon" id ="TambonA" class="select2" onchange="TambonAgent()" style="width: 100%;">
                                        @foreach($Tambon as $item)
                                            <option value="{{ $item->id }}" {{ $viewTax->Tambon == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4 col-4">
                                    <span for="Zip_Code">รหัสไปรษณีย์ / Postal Code</span>
                                    <select name="Zip_Code" id ="zip_codeA" class="select2"  style="width: 100%;">
                                        @foreach($Zip_code as $item)
                                            <option value="{{ $item->zip_code }}" {{ $viewTax->Zip_Code == $item->zip_code ? 'selected' : '' }}>{{ $item->zip_code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4 col-4">
                                    <span for="Email">อีเมล์ / Email</span>
                                    <input type="text" id="EmailAgent" class="form-control" name="Company_Email"maxlength="70"  value="{{$viewTax->Company_Email}}">
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-sm-8 col-8">
                                    <label for="Phone_number">หมายเลขโทรศัพท์ / Phone Number</label>
                                    <button type="button" class="add-phone btn btn-color-green" id="add-phone" data-target="phone-container" >เพิ่มเบอร์โทรศัพท์</button>
                                </div>
                                <div id="phone-container" class="flex-container row">
                                    <!-- Initial input fields -->
                                    @foreach($phonetaxDataArray as $phone)
                                    <div class="col-lg-4 col-md-6 col-sm-12 mt-3">
                                        <div class="input-group show">
                                            <input type="text" name="phoneCom[]" class="form-control phone" maxlength="14" value="{{ formatPhoneNumber($phone['Phone_number']) }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                                            <button type="button" class="btn btn-outline-danger remove-phone"><i class="bi bi-x-circle" style="width:100%;"></i></button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-3 col-sm-12"></div>
                                <div class="col-lg-6 col-sm-12 d-flex justify-content-center align-items-center">
                                    <button type="button" class="btn btn-secondary lift  btn-space"  onclick="window.location.href='{{url('/Company/edit/'.$CompanyID)}}'">{{ __('Back') }}</button>
                                    <button type="submit" class="btn btn-color-green lift " >Save</button>
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
    <script type="text/javascript" src="{{ asset('assets/js/formatNumber.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Please select an option"
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const Branch = document.getElementById('flexRadioDefaultBranchTax');
            Branch.addEventListener('change', function() {
                if (Branch.checked) {
                    $('#BranchTax').val('สำนักงานใหญ่');
                }
            });
        });
        $(document).ready(function() {
            var TaxSelectA = $('#TaxSelectA');
            var TaxSelectA = TaxSelectA.val(); // ใช้ jQuery เพื่อเลือก element
            var Company_type_tax = document.getElementById("Company_type_tax");
            var Company_Name_tax = document.getElementById("Company_Name_tax");
            var BranchTax = document.getElementById("BranchTax");
            var Title = document.getElementById("Title");
            var Com = document.getElementById("Com");
            var PrefaceSelectCom = document.getElementById("PrefaceSelectCom");
            var first_nameCom = document.getElementById("first_nameCom");
            var last_nameCom = document.getElementById("last_nameCom");

            if (TaxSelectA == "Company") {
                Com.style.display = "block";
                Title.style.display = "none";
                Company_Name_tax.disabled = false;
                Company_type_tax.disabled = false;
                BranchTax.disabled = false;
                PrefaceSelectCom.disabled = true;
                first_nameCom.disabled = true;
                last_nameCom.disabled = true;

            } else if (TaxSelectA == "Individual"){
                Title.style.display = "block";
                Com.style.display = "none";
                Company_Name_tax.disabled = true;
                Company_type_tax.disabled = true;
                BranchTax.disabled = true;
                PrefaceSelectCom.disabled = false;
                first_nameCom.disabled = false;
                last_nameCom.disabled = false;
            }

            var countrySelectA = $('#countrySelectA');
            var countrySelectA = countrySelectA.val();
            var amphuresSelect = document.getElementById("amphuresA");
            var tambonSelect = document.getElementById("TambonA");
            var zipCodeSelect = document.getElementById("zip_codeA");
            var provinceAgent = document.getElementById("provinceAgent");
            if (countrySelectA != "Thailand") {
                amphuresSelect.disabled = true;
                tambonSelect.disabled = true;
                zipCodeSelect.disabled = true;
                provinceAgent.disabled = true;
            } else if (countrySelectA == "Thailand"){
                amphuresSelect.disabled = false;
                tambonSelect.disabled = false;
                zipCodeSelect.disabled = false;
                provinceAgent.disabled = false;
            }
        });
        function showTaxInput() {
            var TaxSelectA = document.getElementById("TaxSelectA");
            var Company_type_tax = document.getElementById("Company_type_tax");
            var Company_Name_tax = document.getElementById("Company_Name_tax");
            var BranchTax = document.getElementById("BranchTax");
            var Title = document.getElementById("Title");
            var Com = document.getElementById("Com");
            var PrefaceSelectCom = document.getElementById("PrefaceSelectCom");
            var first_nameCom = document.getElementById("first_nameCom");
            var last_nameCom = document.getElementById("last_nameCom");


            if (TaxSelectA.value === "Company") {
                Com.style.display = "block";
                Title.style.display = "none";
                Company_Name_tax.disabled = false;
                Company_type_tax.disabled = false;
                BranchTax.disabled = false;
                PrefaceSelectCom.disabled = true;
                first_nameCom.disabled = true;
                last_nameCom.disabled = true;

            } else if (TaxSelectA.value === "Individual"){
                Title.style.display = "block";
                Com.style.display = "none";
                Company_Name_tax.disabled = true;
                Company_type_tax.disabled = true;
                BranchTax.disabled = true;
                PrefaceSelectCom.disabled = false;
                first_nameCom.disabled = false;
                last_nameCom.disabled = false;
            }
        }
        function showcityAInput() {
            var countrySelectA = document.getElementById("countrySelectA");
            var amphuresSelect = document.getElementById("amphuresA");
            var tambonSelect = document.getElementById("TambonA");
            var zipCodeSelect = document.getElementById("zip_codeA");
            var provinceAgent = document.getElementById("provinceAgent");
            if (countrySelectA.value !== "Thailand") {
                amphuresSelect.disabled = true;
                tambonSelect.disabled = true;
                zipCodeSelect.disabled = true;
                provinceAgent.disabled = true;
            } else if (countrySelectA.value === "Thailand"){
                amphuresSelect.disabled = false;
                tambonSelect.disabled = false;
                zipCodeSelect.disabled = false;
                provinceAgent.disabled = false;
                select_amphures();
                provinceA();
                TambonAgent();
                $('#zip_codeA').empty();
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
        function formatPhoneNumber(value) {
            value = value.replace(/\D/g, ""); // Remove non-numeric characters
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
            // Function to format phone number


            var phoneContainer = document.getElementById('phone-container');
            var newCol = document.createElement('div');
            newCol.classList.add('col-lg-4', 'col-md-6', 'col-sm-12');
            newCol.innerHTML = `
                <div class="input-group mt-3 show">
                    <input type="text" name="phoneCom[]" class="form-control phone" maxlength="12" oninput="formatAndUpdate(this)" required>
                    <button type="button" class="btn btn-outline-danger remove-phone"><i class="bi bi-x-circle" style="width:100%;"></i></button>
                </div>
            `;
            phoneContainer.appendChild(newCol);

            // Add the show class after a slight delay to trigger the transition
            setTimeout(function() {
                newCol.querySelector('.input-group').classList.add('show');
            }, 10);

            // Attach the remove event to the new button
            attachRemoveEvent(newCol.querySelector('.remove-phone'));
        });

        // Function to format and update the input field as the user types
        function formatAndUpdate(input) {
            const formattedValue = formatPhoneNumber(input.value);
            input.value = formattedValue;
        }

        // Function to attach the remove event to a button
        function attachRemoveEvent(button) {
            button.addEventListener('click', function() {
                var phoneContainer = document.getElementById('phone-container');
                if (phoneContainer.childElementCount > 1) {
                    var colDiv = button.closest('.col-lg-4, .col-md-6, .col-sm-12'); // Correct container selector
                    if (colDiv) {
                        phoneContainer.removeChild(colDiv);
                    }
                }
            });
        }

        // Attach the remove event to existing buttons on page load
        document.querySelectorAll('.remove-phone').forEach(function(button) {
            attachRemoveEvent(button);
        });

    </script>
@endsection
