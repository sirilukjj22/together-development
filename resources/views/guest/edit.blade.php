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
                    <small class="text-muted">Welcome to Edit Guest.</small>
                    <div class=""><span class="span1">Edit Guest (แก้ไขลูกค้า)</span></div>
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
                @if (session("error"))
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">บันทึกไม่สำเร็จ!</h4>
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
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-11 col-md-11 col-sm-12"></div>
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
                                        <input type="text" class="form-control" placeholder="First Name" id="first_name" name="first_name" maxlength="70"  value="{{$Guest->First_name}}" required>
                                    </div>
                                    <div class="col-lg-5 col-md-4 col-sm-12"><label for="last_name">นามสกุล / Last Name</label><br>
                                        <input type="text"class="form-control" placeholder="Last Name" id="last_name" name="last_name" maxlength="70" value="{{$Guest->Last_name}}" required>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-6 col-md-6 col-sm-12"><label for="country">ประเทศ / Country</label><br>
                                        <select name="countrydata" id="countrySelect" class="select2" onchange="showcityInput()">
                                            @foreach($country as $item)
                                                <option value="{{ $item->ct_nameENG }}" {{ $item->ct_nameENG == $Guest->Country ? 'selected' : '' }}>
                                                    {{ $item->ct_nameENG }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if (($Guest->Country === 'Thailand'))
                                    <div class="col-lg-6 col-md-6 col-sm-12" >
                                        <label for="city">จังหวัด / Province</label>
                                        <select name="province" id="province" class="select2" onchange="select_province()">
                                            @foreach($provinceNames as $item)
                                            <option value="{{ $item->id }}" {{$Guest->City == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @else
                                    <div class="col-lg-6 col-md-6 col-sm-12" >
                                        <label for="city">จังหวัด / Province</label>
                                        <select name="province" id="province" class="select2" onchange="select_province()"disabled>
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
                                            <option value="{{ $item->zip_code }}" {{ $Guest->Zip_Code == $item->zip_code ? 'selected' : '' }}>{{ $item->zip_code }}</option>
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
                                    <div id="phone-containerN" class="flex-container row">
                                        @foreach($phoneDataArray as $phone)
                                        <div class="col-lg-4 col-md-6 col-sm-12 mt-2">
                                            <div class="input-group show">
                                                <input type="text" name="phone[]" class="form-control phone" value="{{ formatPhoneNumber($phone['Phone_number']) }}" maxlength="12" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
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
                                        <input type="text" class="form-control" id="identification_number idcard" name="identification_number" value="{{ formatIdCard($Guest->Identification_Number) }}" required>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="contract_rate_start_date">Contract Rate Start Date</label><br>
                                        <div class="datestyle"><input class="form-control" type="date" id="contract_rate_start_date" name="contract_rate_start_date" onchange="Onclickreadonly()"  value="{{$Guest->Contract_Rate_Start_Date}}"disabled></div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="contract_rate_end_date">Contract Rate End Date</label><br>
                                        <div class="datestyle">
                                        <input type="date" class="form-control" id="contract_rate_end_date" name="contract_rate_end_date" readonly value="{{$Guest->Contract_Rate_End_Date}}"disabled>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="discount_contract_rate">Discount Contract Rate (%)</label><br>
                                        <script>
                                            function checkInput() {
                                                var input = document.getElementById("discount_contract_rate");
                                                if (input.value > 100) {
                                                    input.value = 100;
                                                }
                                            }
                                        </script>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="discount_contract_rate" name="discount_contract_rate" oninput="checkInput()" min="0" max="100" value="{{$Guest->Discount_Contract_Rate}}" disabled>
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
        <div class="row clearfix">
            <div class="col-sm-12 col-12">
                <ul class="nav nav-tabs px-3 border-bottom-0" role="tablist">
                    <li class="nav-item" id="nav1"><a class="nav-link active" data-bs-toggle="tab" onclick="nav($id='nav1')" href="#nav-Tax" role="tab">Additional Guest Tax Invoice</a></li>{{--ประวัติการแก้ไข--}}
                    <li class="nav-item" id="nav2"><a class="nav-link " data-bs-toggle="tab" onclick="nav($id='nav2')" href="#nav-Visit" role="tab">Lastest Visit info</a></li>{{--QUOTAION--}}
                    <li class="nav-item" id="nav3"><a class="nav-link" data-bs-toggle="tab" onclick="nav($id='nav3')" href="#nav-Billing" role="tab">Billing Folio info </a></li>{{--เอกสารออกบิล--}}
                    <li class="nav-item" id="nav4"><a class="nav-link " data-bs-toggle="tab" onclick="nav($id='nav4')" href="#nav-Contract" role="tab">Contract Rate Document</a></li>{{--Doc. number--}}
                    <li class="nav-item" id="nav5"><a class="nav-link " data-bs-toggle="tab" onclick="nav($id='nav5')" href="#nav-Freelancer" role="tab">Latest Freelancer By</a></li>{{--ชื่อ คนแนะนำ ครั้งต่อครั้ง ต่อ เอกสาร--}}
                    <li class="nav-item" id="nav6"><a class="nav-link" data-bs-toggle="tab" onclick="nav($id='nav6')" href="#nav-Commission" role="tab">Lastest Freelancer Commission</a></li>{{--% (Percentage) ครั้งต่อครั้ง ต่อ เอกสาร--}}
                    <li class="nav-item" id="nav7"><a class="nav-link" data-bs-toggle="tab" onclick="nav($id='nav7')" href="#nav-User" role="tab">User logs</a></li>{{--ประวัติการแก้ไข--}}
                </ul>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane fade  show active" id="nav-Tax" role="tabpanel" rel="0">
                                <div class="row my-3">
                                    <div class="col-lg-10"></div>
                                    <div class="col-lg-2  d-flex justify-content-end align-items-end">
                                        <button type="button" class="btn btn-color-green lift btn_modal"  data-bs-toggle="modal" data-bs-target="#CreateCompany"><i class="fa fa-plus"></i> Add tax invoice</button>
                                    </div>
                                    <div class="modal fade" id="CreateCompany" tabindex="-1" aria-labelledby="PrenameModalCenterTitle" style="display: none;" aria-hidden="true">
                                        <div class="modal-dialog  modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-color-green">
                                                    <h5 class="modal-title text-white" id="PrenameModalCenterTitle">Add tax invoice</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{url('/guest/save/cover/'.$Guest->id)}}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="col-sm-12 col-12">
                                                            <div class="col-sm-4 col-4">
                                                                <span for="Country">Add Tax</span>
                                                                <select name="TaxSelectA" id="TaxSelectA" class="select21" onchange="showTaxInput()">
                                                                    <option value="Company">Company</option>
                                                                    <option value="Individual">Individual</option>
                                                                </select>
                                                            </div>
                                                            <div id="Com" style="display: block">
                                                                <div class="col-sm-12 mt-2">
                                                                    <div class="row">
                                                                        <div class="col-sm-6 col-6">
                                                                            <label for="Company_type_tax">ประเภทบริษัท / Company Type</label>
                                                                            <select name="Company_type_tax" id="Company_type_tax" class="select21" required>
                                                                                <option value=""></option>
                                                                                @foreach($MCompany_type as $item)
                                                                                    <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-sm-6 col-6">
                                                                            <label for="Company_Name">ชื่อบริษัท / Company Name</label>
                                                                            <input type="text" id="Company_Name_tax" class="form-control" name="Company_Name_tax" maxlength="70" required>
                                                                        </div>
                                                                        <div class="col-sm-6 col-6 mt-2">
                                                                            <label for="Branch">สาขา / Company Branch</label>
                                                                            <input type="text" id="BranchTax" name="BranchTax" class="form-control" maxlength="70" required>
                                                                        </div>
                                                                        <div class="col-sm-6 col-6 mt-2">
                                                                            <label for="Identification">เลขประจำตัวผู้เสียภาษี / Tax identification number</label><br>
                                                                            <input type="text" id="IdentificationCompany" class="form-control idcard" name="Identification" maxlength="17" placeholder="เลขประจำตัวผู้เสียภาษี" required >
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="Title" style="display: none;">
                                                                <div class="col-sm-12 mt-2">
                                                                    <div class="row">
                                                                        <div class="col-sm-6 col-6" >
                                                                            <label for="prefix">คำนำหน้า / Title</label>
                                                                            <select name="prefix" id="PrefaceSelectCom" class="select21" disabled required>
                                                                                    <option value=""></option>
                                                                                    @foreach($Mprefix as $item)
                                                                                        <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                                                                    @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-sm-6 col-6">
                                                                            <label for="first_name">First Name</label>
                                                                            <input type="text" id="first_nameCom" class="form-control" name="first_nameCom"maxlength="70" disabled required>
                                                                        </div>
                                                                        <div class="col-sm-6 col-6 mt-2">
                                                                            <label for="last_name" >Last Name</label>
                                                                            <input type="text" id="last_nameCom" class="form-control" name="last_nameCom"maxlength="70" disabled required>
                                                                        </div>
                                                                        <div class="col-sm-6 col-6 mt-2">
                                                                            <label for="Identification">เลขบัตรประจำตัวประชาชน / Identification number</label><br>
                                                                            <input type="text" id="IdentificationName" class="form-control idcard" name="Identification" maxlength="17" placeholder="เลขประจำตัวผู้เสียภาษี"disabled required >
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12 col-12">
                                                                <div class="row mt-2">
                                                                    <div class="col-sm-4 col-4">
                                                                        <span for="Country">Country</span>
                                                                        <select name="countrydataA" id="countrySelectA" class="select21" onchange="showcityAInputTax()">
                                                                            @foreach($country as $item)
                                                                                <option value="{{ $item->ct_nameENG }}" {{ $item->ct_nameENG == 'Thailand' ? 'selected' : '' }}>
                                                                                    {{ $item->ct_nameENG }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-sm-4 col-4" >
                                                                        <span for="City">City</span>
                                                                        <select name="cityA" id="provincetax" class="select21" onchange="provinceTax()" style="width: 100%;">
                                                                            <option value=""></option>
                                                                            @foreach($provinceNames as $item)
                                                                                <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-sm-4 col-4">
                                                                        <span for="Amphures">Amphures</span>
                                                                        <select name="amphuresA" id="amphuresT" class="select21" onchange="amphuresTax()" >
                                                                            <option value=""></option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-2">
                                                                    <div class="col-sm-4 col-4">
                                                                        <span for="Tambon">Tambon</span>
                                                                        <select name="TambonA" id ="TambonT" class="select21" onchange="TambonTax()" style="width: 100%;">
                                                                            <option value=""></option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-sm-4 col-4">
                                                                        <span for="zip_code">zip_code</span>
                                                                        <select name="zip_codeA" id ="zip_codeT" class="select21"  style="width: 100%;">
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-sm-4 col-4">
                                                                        <span for="Email">Email</span>
                                                                        <input type="text" id="EmailAgent" class="form-control" name="EmailAgent"maxlength="70" required>
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <span for="Address">Address</span>
                                                                    <textarea type="text" id="addressAgent" name="addressAgent" rows="3" cols="25" class="form-control" aria-label="With textarea" required></textarea>
                                                                </div>
                                                                <div class="row mt-2">
                                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                                        <span for="Company_Phone" class="flex-container">
                                                                            โทรศัพท์/ Phone number
                                                                        </span>
                                                                        <button type="button" class="btn btn-color-green my-2" id="add-phoneTax">เพิ่มหมายเลขโทรศัพท์</button>
                                                                    </div>
                                                                    <div id="phone-containerTax" class="flex-container row">
                                                                        <!-- Initial input fields -->
                                                                        <div class="col-lg-4 col-md-6 col-sm-12 mt-2">
                                                                            <div class="input-group show">
                                                                                <input type="text" name="phoneTax[]" class="form-control phone" maxlength="12" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
                                                                                <button type="button" class="btn btn-outline-danger remove-phoneTax"><i class="bi bi-x-circle" style="width:100%;"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <script>
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
                                                                        var IdentificationCompany = document.getElementById("IdentificationCompany");
                                                                        var IdentificationName = document.getElementById("IdentificationName");


                                                                        if (TaxSelectA.value === "Company") {
                                                                            // ถ้าเลือก "Other_countries" แสดง input field สำหรับเมืองอื่นๆ และซ่อน input field สำหรับเมืองไทย
                                                                            Com.style.display = "block";
                                                                            Title.style.display = "none";
                                                                            // citythaiA.style.display = "none";
                                                                            // ปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
                                                                            Company_Name_tax.disabled = false;
                                                                            Company_type_tax.disabled = false;
                                                                            BranchTax.disabled = false;
                                                                            PrefaceSelectCom.disabled = true;
                                                                            first_nameCom.disabled = true;
                                                                            last_nameCom.disabled = true;
                                                                            IdentificationCompany.disabled= false;
                                                                            IdentificationName.disabled= true;
                                                                        } else if (TaxSelectA.value === "Individual"){
                                                                            Title.style.display = "block";
                                                                            // เช็คค่าที่ถูกเลือกใน dropdown list เมือง
                                                                            // ถ้าเลือก "Other_countries" แสดง input field สำหรับเมืองอื่นๆ และซ่อน input field สำหรับเมืองไทย
                                                                            Com.style.display = "none";
                                                                            // ปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
                                                                            Company_Name_tax.disabled = true;
                                                                            Company_type_tax.disabled = true;
                                                                            BranchTax.disabled = true;
                                                                            PrefaceSelectCom.disabled = false;
                                                                            first_nameCom.disabled = false;
                                                                            last_nameCom.disabled = false;
                                                                            IdentificationCompany.disabled= true;
                                                                            IdentificationName.disabled= false;
                                                                        }
                                                                    }
                                                                    function showcityAInputTax() {
                                                                        var countrySelectA = document.getElementById("countrySelectA");
                                                                        var province = document.getElementById("provincetax");
                                                                        var citythaiA = document.getElementById("citythaiA");
                                                                        var amphuresSelect = document.getElementById("amphuresT");
                                                                        var tambonSelect = document.getElementById("TambonT");
                                                                        var zipCodeSelect = document.getElementById("zip_codeT");
                                                                        // เช็คค่าที่ถูกเลือกใน dropdown list เมือง
                                                                        if (countrySelectA.value !== "Thailand") {
                                                                            // ถ้าเลือก "Other_countries" แสดง input field สำหรับเมืองอื่นๆ และซ่อน input field สำหรับเมืองไทย
                                                                            province.disabled = true;

                                                                            // ปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
                                                                            amphuresSelect.disabled = true;
                                                                            tambonSelect.disabled = true;
                                                                            zipCodeSelect.disabled = true;
                                                                        } else if (countrySelectA.value === "Thailand"){
                                                                            // เช็คค่าที่ถูกเลือกใน dropdown list เมือง
                                                                            // ถ้าเลือก "Other_countries" แสดง input field สำหรับเมืองอื่นๆ และซ่อน input field สำหรับเมืองไทย
                                                                            province.disabled = false;
                                                                            // ปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
                                                                            amphuresSelect.disabled = false;
                                                                            tambonSelect.disabled = false;
                                                                            zipCodeSelect.disabled = false;

                                                                            // เรียกใช้ฟังก์ชัน select_amphures() เพื่อเปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
                                                                            amphuresT();
                                                                        }
                                                                    }
                                                                    function provinceTax(){
                                                                        var provinceAgentT = $('#provincetax').val();
                                                                        console.log(provinceAgentT);

                                                                        jQuery.ajax({
                                                                            type:   "GET",
                                                                            url:    "{!! url('/guest/amphuresT/"+provinceAgentT+"') !!}",
                                                                            datatype:   "JSON",
                                                                            async:  false,
                                                                            success: function(result) {
                                                                                jQuery('#amphuresT').children().remove().end();
                                                                                console.log(result);
                                                                                $('#amphuresT').append(new Option('', ''));
                                                                                jQuery.each(result.data, function(key, value) {
                                                                                    var amphuresA = new Option(value.name_th,value.id);
                                                                                    //console.log(amphuresA);
                                                                                    $('#amphuresT').append(amphuresA);
                                                                                });
                                                                            },
                                                                        })

                                                                    }
                                                                    function amphuresTax(){
                                                                        var amphuresAgent  = $('#amphuresT').val();
                                                                        console.log(amphuresAgent);
                                                                        $.ajax({
                                                                            type:   "GET",
                                                                            url:    "{!! url('/guest/TambonT/"+amphuresAgent+"') !!}",
                                                                            datatype:   "JSON",
                                                                            async:  false,
                                                                            success: function(result) {
                                                                            // console.log(result);
                                                                                jQuery('#TambonT').children().remove().end();
                                                                                $('#TambonT').append(new Option('', ''));
                                                                                jQuery.each(result.data, function(key, value) {
                                                                                    var TambonA  = new Option(value.name_th,value.id);
                                                                                    $('#TambonT').append(TambonA );
                                                                                // console.log(TambonA);
                                                                                });
                                                                            },
                                                                        })
                                                                    }
                                                                    function TambonTax(){
                                                                        var TambonAgent  = $('#TambonT').val();
                                                                        console.log(TambonAgent);
                                                                        $.ajax({
                                                                            type:   "GET",
                                                                            url:    "{!! url('/guest/districtT/"+TambonAgent+"') !!}",
                                                                            datatype:   "JSON",
                                                                            async:  false,
                                                                            success: function(result) {
                                                                                console.log(result);
                                                                                jQuery('#zip_codeT').children().remove().end();
                                                                                //console.log(result);
                                                                                $('#zip_codeT').append(new Option('', ''));
                                                                                jQuery.each(result.data, function(key, value) {
                                                                                    var zip_codeA  = new Option(value.zip_code,value.zip_code);
                                                                                    $('#zip_codeT').append(zip_codeA);
                                                                                    //console.log(zip_codeA);
                                                                                });
                                                                            },
                                                                        })
                                                                    }
                                                                </script>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary lift" data-bs-dismiss="modal">ยกเลิก</button>
                                                        <button type="submit" class="btn btn-color-green lift" id="btn-save">สร้าง</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="min-height: 70vh;" class="mt-2">
                                        <table id="guest-TaxTable" class="example2 ui striped table nowrap unstackable hover">
                                            <caption class="caption-top">
                                                    <div class="flex-end-g2">
                                                        <label class="entriespage-label">entries per page :</label>
                                                        <select class="entriespage-button" id="search-per-page-guest-Tax" onchange="getPageTax(1, this.value, 'guest-Tax')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                            <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "guest-Tax" ? 'selected' : '' }}>10</option>
                                                            <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "guest-Tax" ? 'selected' : '' }}>25</option>
                                                            <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "guest-Tax" ? 'selected' : '' }}>50</option>
                                                            <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "guest-Tax" ? 'selected' : '' }}>100</option>
                                                        </select>
                                                        <input class="search-button search-data-guest-Tax" id="guest-Tax" style="text-align:left;" placeholder="Search" />
                                                    </div>
                                            </caption>
                                            <thead>
                                                <tr>
                                                    <th class="text-center" data-priority="1">No</th>
                                                    <th class="text-center" data-priority="1">Profile_ID</th>
                                                    <th class="text-center" data-priority="1">Company/Individual</th>
                                                    <th class="text-center">Branch</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($guesttax))
                                                    @foreach ($guesttax as $key => $item)
                                                    <tr>
                                                        <td style="text-align: center;">{{ $key + 1 }}</td>
                                                        <td style="text-align: center;">{{ $item->GuestTax_ID }}</td>
                                                        @if ($item->Tax_Type == 'Company')
                                                            <td >{{ $item->Company_name }}</td>
                                                        @else
                                                            <td >{{ $item->first_name.' '.$item->last_name }} </td>
                                                        @endif
                                                        <td style="text-align: center;">{{ $item->BranchTax }}</td>
                                                        <td style="text-align: center;">
                                                            <input type="hidden" id="status" value="{{ $item->status }}">
                                                            @if ($item->status == 1)
                                                                <button type="button" class="btn btn-light-success btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ใช้งาน</button>
                                                            @else
                                                                <button type="button" class="btn btn-light-danger btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ปิดใช้งาน</button>
                                                            @endif
                                                        </td>
                                                        @php
                                                            $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                            $canViewProposal = @Auth::user()->roleMenuView('Guest', Auth::user()->id);
                                                            $canEditProposal = @Auth::user()->roleMenuEdit('Guest', Auth::user()->id);
                                                        @endphp
                                                        <td style="text-align: center;">
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                                <ul class="dropdown-menu border-0 shadow p-3">
                                                                    @if ($rolePermission > 0)
                                                                        @if ($canViewProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/guest/Tax/view/'.$item->id) }}">View</a></li>
                                                                        @endif
                                                                        @if ($canEditProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/guest/Tax/edit/'.$item->id) }}">Edit</a></li>
                                                                        @endif
                                                                    @else
                                                                        @if ($canViewProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/guest/Tax/view/'.$item->id) }}">View</a></li>
                                                                        @endif
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                            <input type="hidden" id="profile-guest-Tax" name="profile-guest" value="{{$Guest->Profile_ID}}">
                                            <input type="hidden" id="get-total-guest-Tax" value="{{ $guesttax->total() }}">
                                            <input type="hidden" id="currentPage-guest-Tax" value="1">
                                            <caption class="caption-bottom">
                                                <div class="md-flex-bt-i-c">
                                                    <p class="py2" id="guest-Tax-showingEntries">{{ showingEntriesTableTax($guesttax, 'guest-Tax') }}</p>
                                                        <div id="guest-Tax-paginate">
                                                            {!! paginateTableTax($guesttax, 'guest-Tax') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                                        </div>
                                                </div>
                                            </caption>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-Visit" role="tabpanel" rel="0">
                                <div style="min-height: 70vh;" class="mt-2">
                                    <table id="guest-VisitTable" class="example2 ui striped table nowrap unstackable hover">
                                        <caption class="caption-top">
                                            <div>
                                                <div class="flex-end-g2">
                                                    <label class="entriespage-label">entries per page :</label>
                                                    <select class="entriespage-button" id="search-per-page-guest-Visit" onchange="getPageVisit(1, this.value, 'guest-Visit')">
                                                        <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "guest-Visit" ? 'selected' : '' }}>10</option>
                                                        <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "guest-Visit" ? 'selected' : '' }}>25</option>
                                                        <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "guest-Visit" ? 'selected' : '' }}>50</option>
                                                        <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "guest-Visit" ? 'selected' : '' }}>100</option>
                                                    </select>
                                                    <input class="search-button search-data-guest-Visit" id="guest-Visit" style="text-align:left;" placeholder="Search" />
                                                </div>
                                        </caption>
                                        <thead>
                                            <tr>
                                                <th class="text-center" data-priority="1">No</th>
                                                <th class="text-center" data-priority="1">ID</th>
                                                <th data-priority="1">Individual</th>
                                                <th class="text-center">Issue Date</th>
                                                <th class="text-center">Expiration Date</th>
                                                <th class="text-center">Check In</th>
                                                <th class="text-center">Check Out</th>
                                                <th class="text-center">Discount </th>
                                                <th class="text-center">Operated By</th>
                                                <th class="text-center">Document status</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($Quotation))
                                                @foreach ($Quotation as $key => $item)
                                                <tr>
                                                    <td style="text-align: center;">
                                                        {{$key +1}}
                                                    </td>
                                                    <td style="text-align: center;">{{ $item->Quotation_ID }}</td>
                                                    <td>{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                    <td style="text-align: center;">{{ $item->issue_date }}</td>
                                                    <td style="text-align: center;">{{ $item->Expirationdate }}</td>
                                                    @if ($item->checkin)
                                                    <td style="text-align: center;">{{ $item->checkin }}</td>
                                                    <td style="text-align: center;">{{ $item->checkout }}</td>
                                                    @else
                                                    <td style="text-align: center;">-</td>
                                                    <td style="text-align: center;">-</td>
                                                    @endif
                                                    <td style="text-align: center;">
                                                        @if ($item->SpecialDiscountBath	== 0)
                                                            -
                                                        @else
                                                            {{$item->SpecialDiscountBath}}
                                                        @endif
                                                    </td>
                                                    <td >{{ @$item->userOperated->name }}</td>
                                                    <td style="text-align: center;">
                                                        @if($item->status_guest == 1)
                                                            <span class="badge rounded-pill bg-success">Approved</span>
                                                        @else
                                                            @if($item->status_document == 0)
                                                                <span class="badge rounded-pill bg-danger">Cancel</span>
                                                            @elseif ($item->status_document == 1)
                                                                <span class="badge rounded-pill "style="background-color: #FF6633	">Pending</span>
                                                            @elseif ($item->status_document == 2)
                                                                <span class="badge rounded-pill bg-warning">Awaiting Approva</span>
                                                            @elseif ($item->status_document == 3)
                                                                <span class="badge rounded-pill "style="background-color: #FF6633">Pending</span>
                                                            @elseif ($item->status_document == 4)
                                                                <span class="badge rounded-pill "style="background-color:#1d4ed8">Reject</span>
                                                            @elseif ($item->status_document == 6)
                                                                <span class="badge rounded-pill "style="background-color: #FF6633">Pending</span>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                            <ul class="dropdown-menu border-0 shadow p-3">
                                                                <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Proposal/Quotation/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                        <input type="hidden" id="profile-guest-Visit" name="profile-company" value="{{$Guest->Profile_ID}}">
                                        <input type="hidden" id="get-total-guest-Visit" value="{{ $Quotation->total() }}">
                                        <input type="hidden" id="currentPage-guest-Visit" value="1">
                                        <caption class="caption-bottom">
                                            <div class="md-flex-bt-i-c">
                                                <p class="py2" id="guest-Visit-showingEntries">{{ showingEntriesTableVisit($Quotation, 'guest-Visit') }}</p>
                                                    <div id="guest-Visit-paginate">
                                                        {!! paginateTableVisit($Quotation, 'guest-Visit') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                                    </div>
                                            </div>
                                        </caption>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-Billing" role="tabpanel" rel="0">
                            </div>
                            <div class="tab-pane fade "id="nav-Contract" role="tabpanel" rel="0">
                            </div>
                            <div class="tab-pane fade" id="nav-Freelancer" role="tabpanel" rel="0">
                            </div>
                            <div class="tab-pane fade" id="nav-Commission" role="tabpanel" rel="0">
                            </div>
                            <div class="tab-pane fade" id="nav-User" role="tabpanel" rel="0">
                                <div style="min-height: 70vh;" class="mt-2">
                                    <caption class="caption-top">
                                        <div class="flex-end-g2">
                                            <label class="entriespage-label">entries per page :</label>
                                            <select class="entriespage-button" id="search-per-page-guest" onchange="getPage(1, this.value, 'guest')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "guest" ? 'selected' : '' }}>10</option>
                                                <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "guest" ? 'selected' : '' }}>25</option>
                                                <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "guest" ? 'selected' : '' }}>50</option>
                                                <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "guest" ? 'selected' : '' }}>100</option>
                                            </select>
                                            <input class="search-button search-data-log" id="guest" style="text-align:left;" placeholder="Search" />
                                        </div>
                                    </caption>
                                    <table id="guestTable" class="example ui striped table nowrap unstackable hover">
                                        <thead>
                                            <tr>
                                                <th  class="text-center">No</th>
                                                <th >Category</th>
                                                <th  class="text-center">Type</th>
                                                <th  class="text-center">Created_by</th>
                                                <th  class="text-center">Created Date</th>
                                                <th  class="text-center">Content</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($log))
                                                @foreach($log as $key => $item)
                                                <tr>
                                                    <td style="text-align: center;">{{$key +1 }}</td>
                                                    <td style="text-align: left;">{{$item->Category}}</td>
                                                    <td style="text-align: center;">{{$item->type}}</td>
                                                    <td style="text-align: center;">{{@$item->userOperated->name}}</td>
                                                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                                                    @php
                                                        // แยกข้อมูล content ออกเป็น array
                                                        $contentArray = explode('+', $item->content);
                                                    @endphp
                                                    <td style="text-align: left;">

                                                        <b style="color:#0000FF ">{{$item->Category}}</b>
                                                        @foreach($contentArray as $contentItem)
                                                            <div>{{ $contentItem }}</div>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    <input type="hidden" id="profile-guest" name="profile-guest" value="{{$Guest->Profile_ID}}">
                                    <input type="hidden" id="get-total-guest" value="{{ $log->total() }}">
                                    <input type="hidden" id="currentPage-guest" value="1">
                                    <caption class="caption-bottom">
                                        <div class="md-flex-bt-i-c">
                                            <p class="py2" id="guest-showingEntries">{{ showingEntriesTable($log, 'guest') }}</p>
                                            <div id="guest-paginate">
                                                {!! paginateTable($log, 'guest') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                            </div>
                                        </div>
                                    </caption>
                                </div>
                            </div>
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
            $('.select21').select2({
                dropdownParent: $("#CreateCompany"),
                placeholder: "Please select an option"
            });
            $('.select22').select2({
                dropdownParent: $("#CreateAgent"),
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
            var phoneContainer = document.getElementById('phone-containerN');
            var newCol = document.createElement('div');
            newCol.classList.add('col-lg-4', 'col-md-6', 'col-sm-12');
            newCol.innerHTML = `
                <div class="input-group mt-2">
                    <input type="text" name="phone[]" class="form-control" maxlength="12" oninput="formatAndUpdate(this)" required>
                    <button type="button" class="btn btn-outline-danger remove-phone"><i class="bi bi-x-circle" style="width:100%;"></i></button>
                </div>
            `;
            phoneContainer.appendChild(newCol);

            setTimeout(function() {
                newCol.querySelector('.input-group').classList.add('show');
            }, 10);

            attachRemoveEventPhone(newCol.querySelector('.remove-phone'));
        });
        function formatAndUpdate(input) {
            const formattedValue = formatPhoneNumber(input.value);
            input.value = formattedValue;
        }
        function attachRemoveEventPhone(button) {
            button.addEventListener('click', function() {
                var phoneContainer = document.getElementById('phone-containerN');
                if (phoneContainer.childElementCount > 1) {
                    phoneContainer.removeChild(button.closest('.col-lg-4, .col-md-6, .col-sm-12'));
                }
            });
        }

        // Attach the remove event to the initial remove buttons
        document.querySelectorAll('.remove-phone').forEach(function(button) {
            attachRemoveEventPhone(button);
        });

        // Function 2
        document.getElementById('add-phoneTax').addEventListener('click', function() {
            var phoneContainerTax = document.getElementById('phone-containerTax');
            var newColTax = document.createElement('div');
            newColTax.classList.add('col-lg-4', 'col-md-6', 'col-sm-12');
            newColTax.innerHTML = `
                <div class="input-group mt-2">
                    <input type="text" name="phoneTax[]" class="form-control" maxlength="12" oninput="formatAndUpdate(this)" required>
                    <button type="button" class="btn btn-outline-danger remove-phoneTax"><i class="bi bi-x-circle" style="width:100%;"></i></button>
                </div>
            `;
            phoneContainerTax.appendChild(newColTax);

            setTimeout(function() {
                newColTax.querySelector('.input-group').classList.add('show');
            }, 10);

            attachRemoveEventPhoneTax(newColTax.querySelector('.remove-phoneTax'));
        });
        function formatAndUpdate(input) {
            const formattedValue = formatPhoneNumber(input.value);
            input.value = formattedValue;
        }
        function attachRemoveEventPhoneTax(button) {
            button.addEventListener('click', function() {
                var phoneContainerTax = document.getElementById('phone-containerTax');
                if (phoneContainerTax.childElementCount > 1) {
                    phoneContainerTax.removeChild(button.closest('.col-lg-4, .col-md-6, .col-sm-12'));
                }
            });
        }

        // Attach the remove event to the initial remove buttons
        document.querySelectorAll('.remove-phoneTax').forEach(function(button) {
            attachRemoveEventPhoneTax(button);
        });

    </script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- dataTable -->
<script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.2/js/dataTables.semanticui.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.semanticui.js"></script>
<script type="text/javascript" src="{{ asset('assets/helper/searchTableLogGuest.js')}}"></script>
    <script>
       const table_name = ['guest-TaxTable','guestTable','guest-VisitTable'];
        $(document).ready(function() {
            for (let index = 0; index < table_name.length; index++) {
                console.log(table_name);

                new DataTable('#'+table_name[index], {
                    searching: false,
                    paging: false,
                    info: false,
                    columnDefs: [{
                        className: 'dtr-control',
                        orderable: true,
                        target: null,
                    }],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    }
                });
            }
        });
        function nav(id) {
            for (let index = 0; index < table_name.length; index++) {
                $('#'+table_name[index]).DataTable().destroy();
                new DataTable('#'+table_name[index], {
                    searching: false,
                    paging: false,
                    info: false,
                    columnDefs: [{
                        className: 'dtr-control',
                        orderable: true,
                        target: null,
                    }],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    }
                });
            }
        }
        $(document).on('keyup', '.search-data-log', function () {
            var id = $(this).attr('id');
            var search_value = $(this).val();
            var table_name = id+'Table';
            var guest_profile = $('#profile-guest').val();
            var type_status = $('#status').val();
            var total = parseInt($('#get-total-'+id).val());
            var getUrl = window.location.pathname;
            console.log(search_value);


                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                    url: '/logguest-search-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        search_value: search_value,
                        table_name: table_name,
                        guest_profile: guest_profile,
                        status: type_status,
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                },
                "initComplete": function (settings,json){

                    if ($('#'+id+'Table .dataTable_empty').length == 0) {
                        var count = $('#'+id+'Table tr').length - 1;
                    }else{
                        var count = 0;
                    }
                    if (search_value == '') {
                        count_total = total;
                    }else{
                        count_total = count;
                    }
                    $('#'+id+'-paginate').children().remove().end();
                    $('#'+id+'-showingEntries').text(showingEntriesSearch(1,count_total, id));
                    $('#'+id+'-paginate').append(paginateSearch(count_total, id, getUrl));
                },
                    columnDefs: [
                                { targets: [0, 2, 3,4], className: 'dt-center td-content-center' },
                    ],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    },
                    columns: [
                        { data: 'id', "render": function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                        { data: 'Category' },
                        { data: 'type' },
                        { data: 'Created_by' },
                        { data: 'created_at' },
                        { data: 'Content' },
                    ],

                });




            document.getElementById(id).focus();
        });
        $(document).on('keyup', '.search-data-guest-Tax', function () {
            var id = $(this).attr('id');
            var search_value = $(this).val();
            var table_name = id+'Table';
            var guest_profile = $('#profile-guest').val();
            var type_status = $('#status').val();
            var total = parseInt($('#get-total-'+id).val());
            var getUrl = window.location.pathname;
                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                    url: '/tax-guest-search-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        search_value: search_value,
                        table_name: table_name,
                        guest_profile: guest_profile,
                        status: type_status,
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                },
                    "initComplete": function (settings,json){

                        if ($('#'+id+'Table .dataTable_empty').length == 0) {
                            var count = $('#'+id+'Table tr').length - 1;
                        }else{
                            var count = 0;
                        }
                        if (search_value == '') {
                            count_total = total;
                        }else{
                            count_total = count;
                        }
                        $('#'+id+'-paginate').children().remove().end();
                        $('#'+id+'-showingEntries').text(showingEntriesSearchTax(1,count_total, id));
                        $('#'+id+'-paginate').append(paginateSearchTax(count_total, id, getUrl));
                    },
                    columnDefs: [
                                { targets: [0, 1, 3,4,5], className: 'dt-center td-content-center' },
                    ],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    },
                    columns: [
                        { data: 'id', "render": function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                        { data: 'Profile_ID_TAX' },
                        { data: 'Company/Individual' },
                        { data: 'Branch' },
                        { data: 'Status' },
                        { data: 'Order' },
                    ],

                });
            document.getElementById(id).focus();
        });
        $(document).on('keyup', '.search-data-guest-Visit', function () {
            var id = $(this).attr('id');
            var search_value = $(this).val();
            var table_name = id+'Table';
            var guest_profile = $('#profile-guest-Visit').val();
            var type_status = $('#status').val();
            var total = parseInt($('#get-total-'+id).val());
            var getUrl = window.location.pathname;

                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                    url: '/Visit-guest-search-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        search_value: search_value,
                        table_name: table_name,
                        guest_profile: guest_profile,
                        status: type_status,
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                },
                    "initComplete": function (settings,json){

                        if ($('#'+id+'Table .dataTable_empty').length == 0) {
                            var count = $('#'+id+'Table tr').length - 1;
                        }else{
                            var count = 0;
                        }
                        if (search_value == '') {
                            count_total = total;
                        }else{
                            count_total = count;
                        }
                        $('#'+id+'-paginate').children().remove().end();
                        $('#'+id+'-showingEntries').text(showingEntriesSearchVisit(1,count_total, id));
                        $('#'+id+'-paginate').append(paginateSearchVisit(count_total, id, getUrl));
                    },
                    columnDefs: [
                                { targets: [0, 1, 3, 4, 5, 6, 7, 8, 9,10], className: 'dt-center td-content-center' },
                    ],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    },
                    columns: [
                        { data: 'id', "render": function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                        { data: 'ID' },
                        { data: 'Company' },
                        { data: 'IssueDate' },
                        { data: 'ExpirationDate' },
                        { data: 'CheckIn' },
                        { data: 'CheckOut' },
                        { data: 'Discount' },
                        { data: 'OperatedBy' },
                        { data: 'Documentstatus' },
                        { data: 'Order' },

                    ],

                });
            document.getElementById(id).focus();
        });
    </script>

    <script>

        function btnstatus(id) {
            jQuery.ajax({
                type: "GET",
                url: "{!! url('/guest/change-status/tax/" + id + "') !!}",
                datatype: "JSON",
                async: false,
                success: function(result) {
                    Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                    location.reload();
                },
            });
        }
    </script>
@endsection
