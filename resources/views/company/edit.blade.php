@extends('layouts.masterLayout')
@php
    $excludeDatatable = false;
@endphp
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
                    <div class="span3">Edit Company / Agent</div>
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
                                <input style=" float:right;" type="text" class="form-control" id="Profile_ID" name="Profile_ID" maxlength="70" required value="{{$Profile_ID}}" disabled>
                            </div>
                        </div>
                        <form id="myForm" action="{{url('/Company/Company_edit/Company_update/'.$Company->id)}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row mt-2">
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <label for="Company_type">ประเภทบริษัท / Company Type</label>
                                    <select name="Company_type" id="Company_type" class="form-select">
                                        <option value=""></option>
                                        @foreach($MCompany_type as $item)
                                            <option value="{{ $item->id }}" {{$Company->Company_type == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-8 col-md-6 col-sm-12">
                                    <label for="Company_Name">ชื่อบริษัท / Company Name</label>
                                    <input type="text" id="Company_Name" class="form-control" name="Company_Name" maxlength="70" required value="{{$Company->Company_Name}}">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label for="Branch">สาขา / Company Branch <span>&nbsp;&nbsp;&nbsp; </span><input class="form-check-input" type="radio" name="flexRadioDefaultBranch" id="flexRadioDefaultBranch"> สำนักงานใหญ่</label>
                                    <input type="text" id="Branch" name="Branch" class="form-control" maxlength="70" required value="{{$Company->Branch}}">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label for="Market">กลุ่มตลาด / Market</label>
                                    <select name="Market" id="Mmarket" class="form-select">
                                        <option value=""></option>
                                        @foreach($Mmarket as $item)
                                            <option value="{{ $item->id }}" {{$Company->Market == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label for="booking_channel">ช่องทางการจอง / Booking Channel</label>
                                    <select name="Booking_Channel" id="booking_channel" class="select2">
                                        <option value=""></option>
                                        @foreach($booking_channel as $item)
                                            <option value="{{ $item->id }}" {{$Company->Booking_Channel == $item->id ? 'selected' : ''}}>{{ $item->name_en }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label for="country">ประเทศ / Country</label>
                                    <select name="Country" id="countrySelect" class="select2" onchange="showcityInput()">
                                        @foreach($country as $item)
                                            <option value="{{ $item->ct_nameENG }}" {{ $item->ct_nameENG == $Company->Country ? 'selected' : '' }}>
                                                {{ $item->ct_nameENG }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <label for="address">ที่อยู่ / Address</label>
                                    <textarea type="text" id="address" name="Address" rows="5" cols="25" class="form-control" aria-label="With textarea" required>{{$Company->Address}}</textarea>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <label for="city">จังหวัด / Province</label>
                                    <select name="City" id="province" class="select2" onchange="select_province()">
                                        @foreach($provinceNames as $item)
                                        <option value="{{ $item->id }}" {{$Company->City == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <label for="Amphures">อำเภอ / District</label>
                                    <select name="Amphures" id="amphures" class="select2" onchange="select_amphures()">
                                        @foreach($amphures as $item)
                                        <option value="{{ $item->id }}" {{ $Company->Amphures == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <label for="Tambon">ตำบล / Subdistrict </label>
                                    <select name="Tambon" id="Tambon" class="select2" onchange="select_Tambon()">
                                        @foreach($Tambon as $item)
                                        <option value="{{ $item->id }}" {{ $Company->Tambon == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <label for="zip_code">รหัสไปรษณีย์ / Postal Code</label><br>
                                    <select name="Zip_Code" id="zip_code" class="select2">
                                        @foreach($Zip_code as $item)
                                        <option value="{{ $item->zip_code }}" {{ $Company->Zip_Code == $item->zip_code ? 'selected' : '' }}>{{ $item->zip_code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label for="Company_Phone">
                                        Company Phone number
                                    </label>
                                    <button type="button" class="btn btn-color-green my-2 add-input" id="add-input">เพิ่มหมายเลขโทรศัพท์</button>
                                    <div id="phone-inputs-container" class="flex-container">
                                        @foreach($phoneDataArray as $index => $phone)
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control phone phone-input" name="phone[]" value="{{ formatPhoneNumber($phone['Phone_number']) }}" data-index="{{ $index }}" maxlength="12" data-old-value="{{ $phone['Phone_number'] }}" required>
                                                <button class="btn btn-outline-danger remove-input" type="button"><i class="bi bi-x-circle" style="width:100%;"></i></button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label for="Company_Fax" class="flex-container">
                                        แฟกซ์ของบริษัท / Company Fax number
                                    </label>
                                    <button type="button" class="btn btn-color-green my-2" id="add-fax">เพิ่มหมายเลขแฟกซ์</button>
                                    <div id="fax-inputs-container" class="flex-container">
                                        @foreach($faxArray as $index => $fax)
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control  phone fax-input" name="fax[]" value="{{ formatPhoneNumber($fax['Fax_number']) }}" data-index="{{ $index }}" maxlength="11" data-old-value="{{ $fax['Fax_number'] }}" required>
                                                <button class="btn btn-outline-danger remove-input" type="button"><i class="bi bi-x-circle" style="width:100%;"></i></button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <label for="Company_Email">ที่อยู่อีเมลของบริษัท / Company Email</label>
                                    <input type="email" class="email form-control" id="Company_Email"  name="Company_Email" maxlength="70" required value="{{$Company->Company_Email}}">
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <label for="Company_Website">เว็บไซต์ของบริษัท / Company Website</label><br>
                                    <input type="text" id="Company_Website" class="form-control" name="Company_Website" maxlength="70" required value="{{$Company->Company_Website}}">
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <label for="Taxpayer_Identification">เลขประจำตัวผู้เสียภาษี / Tax identification number</label><br>
                                    <input type="text" id="Taxpayer_Identification" class="form-control idcard" name="Taxpayer_Identification" maxlength="17" placeholder="เลขประจำตัวผู้เสียภาษี" required value="{{ formatIdCard($Company->Taxpayer_Identification) }}">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <label for="Discount_Contract_Rate">อัตราคิดลด / Discount Contract Rate</label><br>
                                    <input type="text" id="Discount_Contract_Rate" class="form-control" name="Discount_Contract_Rate" maxlength="70" disabled value="{{$Company->Discount_Contract_Rate}}">
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <label for="contract_rate_start_date">Contract Rate Start Date</label><br>
                                    <input class="form-control" type="date" id="contract_rate_start_date" name="contract_rate_start_date" value="{{$Company->Contract_Rate_Start_Date}}" disabled>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <label for="contract_rate_end_date">Contract Rate End Date</label><br>
                                    <input class="form-control" type="date" id="contract_rate_end_date" name="contract_rate_end_date" value="{{$Company->Contract_Rate_End_Date}}"disabled>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <label for="Lastest_Introduce_By">แนะนำล่าสุดโดย / Lastest Introduce By</label><br>
                                    <input type="text" id="Lastest_Introduce_By" class="form-control" name="Lastest_Introduce_By" maxlength="70" required value="{{$Company->Lastest_Introduce_By}}">
                                </div>
                            </div>
                            <div class="card border-0">
                                <div class="row mt-3">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="custom-accordion">
                                            <input type="checkbox" id="trigger1" />
                                            <label for="trigger1">ติดต่อ / Contact</label>
                                            <div class="custom-accordion-content">
                                                <div class="row my-3">
                                                    <div class="col-lg-10"></div>
                                                    <div class="col-lg-2  d-flex justify-content-end align-items-end">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                                List &nbsp;
                                                            </button>
                                                            <ul class="dropdown-menu border-0 shadow p-3">
                                                                <li>
                                                                    <a class="dropdown-item py-2 rounded" href="{{ url('/Company/index/contact/'.$Profile_ID) }}">All Contact</a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item py-2 rounded" data-bs-toggle="modal" data-bs-target="#CreateAgent">Create Contact</a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item py-2 rounded" href="{{ url('/Company/edit/contact/'.$representative->id) }}">Edit Contact</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                                        <span class="labelcontact" for="">Title</span>
                                                        <select name="Mprefix" id="Mprefix" class="form-select"disabled>
                                                            <option value=""></option>
                                                            @foreach($Mprefix as $item)
                                                            <option value="{{ $item->id }}" {{$representative->prefix == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        <span for="first_name">ชื่อ / First Name</span><br>
                                                        <input type="text" id="first_nameAgent"class="form-control" name="first_nameAgent" maxlength="70" disabled value="{{$representative->First_name}}">
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12"><span for="last_name">นามสกุล / Last Name</span><br>
                                                        <input type="text" id="last_nameAgent"class="form-control" name="last_nameAgent" maxlength="70" disabled value="{{$representative->Last_name}}">
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-12">
                                                        <span for="Address">ที่อยู่ / Address</span><br>
                                                        <textarea type="text" id="addressAgent" name="addressAgent" rows="3" cols="25" class="form-control" aria-label="With textarea" disabled>{{$representative->Address}}</textarea>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                        <span for="Country">ประเทศ / Country</span><br>
                                                        <select name="countrydataA" id="countrySelectA" class="form-select" onchange="showcityAInput()"disabled>
                                                            <option value="Thailand" {{$representative->Country == "Thailand" ? 'selected' : ''}}>{{$representative->Country}}</option>
                                                        </select>
                                                    </div>
                                                    @if (($representative->Country === 'Thailand'))
                                                        <div class="col-lg-4 col-md-6 col-sm-12" id="citythai" style="display:block;">
                                                            <span for="city">จังหวัด / Province</span><br>
                                                            <select name="province"  class="select22" disabled>

                                                                <option >{{ $provinceNamesA }}</option>

                                                            </select>
                                                        </div>
                                                    @else
                                                        <div class="col-lg-4 col-md-6 col-sm-12" id="citythai" style="display:none;">
                                                            <span for="city">จังหวัด / Province</span><br>
                                                            <select  class="select22" disabled>
                                                                <option value=""></option>

                                                                <option >{{ $provinceNamesA }}</option>

                                                            </select>
                                                        </div>
                                                    @endif
                                                    @if ($representative->Country === 'Thailand')
                                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                                            <span for="Amphures">อำเภอ / District</span><br>
                                                            <select class="select22" disabled>

                                                                <option >{{$amphuresA}}</option>

                                                            </select>
                                                        </div>
                                                    @else
                                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                                            <span for="Amphures">อำเภอ / District</span><br>
                                                            <select  class="select22"  disabled>
                                                                <option value=""></option>
                                                            </select>
                                                        </div>
                                                    @endif
                                                    @if ($representative->Country === 'Thailand')
                                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                                            <span for="Tambon">ตำบล / Subdistrict </span><br>
                                                            <select class="select22" disabled>
                                                            <option >{{ $TambonA }}</option>
                                                        </select>
                                                        </div>
                                                    @else
                                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                                            <span for="Tambon">ตำบล / Subdistrict </span><br>
                                                            <select class="select22" disabled>
                                                                <option value=""></option>
                                                            </select>
                                                        </div>
                                                    @endif
                                                    @if ($representative->Country === 'Thailand')
                                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                                            <span for="zip_code">รหัสไปรษณีย์ / Postal Code</span><br>
                                                            <select  class="select22"  disabled>
                                                                <option >{{ $Zip_codeA }}</option>
                                                            </select>
                                                        </div>
                                                    @else
                                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                                            <span for="zip_code">รหัสไปรษณีย์ / Postal Code</span><br>
                                                            <select  class="select22" disabled>
                                                                <option value=""></option>
                                                            </select>
                                                        </div>
                                                    @endif
                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                        <span for="Email">อีเมล / Email</span><br>
                                                        <input type="text" id="EmailAgent" class="form-control" name="EmailAgent" maxlength="70" required value="{{$representative->Email}}"disabled>
                                                    </div>
                                                </div>

                                                <div class="row mt-2">
                                                    <div class="col-12">
                                                        <span for="Phone_number">หมายเลขโทรศัพท์ / Phone Number</span>
                                                    </div>
                                                </div>
                                                <div id="phone-container" class="flex-container row">
                                                    <!-- Initial input fields -->
                                                    @foreach($phoneArray as $phone)
                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                        <div class="phone-group show mt-2">
                                                            <input type="text" name="phone[]" class="form-control" maxlength="10" value="{{ $phone['Phone_number'] }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required disabled>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-lg-3 col-sm-12"></div>
                                <div class="col-lg-6 col-sm-12 d-flex justify-content-center align-items-center">
                                    <button type="button" class="btn btn-secondary lift  btn-space"  onclick="window.location.href='{{ route('Company','index') }}'">{{ __('Back') }}</button>
                                    <button type="submit" class="btn btn-color-green lift " onclick="confirmSubmit(event)">Save</button>
                                </div>
                                <div class="col-lg-3 col-sm-12"></div>
                            </div>
                        </form>
                        <div class="modal fade" id="CreateAgent" tabindex="-1" aria-labelledby="PrenameModalCenterTitle" style="display: none;" aria-hidden="true">
                            <div class="modal-dialog  modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-color-green">
                                        <h5 class="modal-title text-white" id="PrenameModalCenterTitle">Add Contact</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{url('/Company/edit/contact/create/'.$Company->id)}}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="col-sm-12 col-12">
                                                <div id="TitleContact" >
                                                    <div class="col-sm-12 mt-2">
                                                        <div class="row">
                                                            <div class="col-sm-4 col-4" >
                                                                <span for="prefix">คำนำหน้า / Title</span>
                                                                <select name="prefix" id="PrefaceSelectContact" class="select22" required>
                                                                        <option value=""></option>
                                                                        @foreach($Mprefix as $item)
                                                                            <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                                                        @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-4 col-4">
                                                                <span for="first_name">ชื่อ / First Name</span>
                                                                <input type="text" id="first_nameContact" class="form-control" placeholder="ชื่อ" name="first_nameContact"maxlength="70"  required>
                                                            </div>
                                                            <div class="col-sm-4 col-4 ">
                                                                <span for="last_name" >นามสกุล / Last Name</span>
                                                                <input type="text" id="last_nameContact" class="form-control" placeholder="นามสกุล" name="last_nameContact"maxlength="70"  required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div  class="col-sm-12 col-12 row mt-2">
                                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2">
                                                            <span class="form-check-label" for="flexRadioDefault1">
                                                                ที่อยู่ตามบริษัท
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 mt-2">
                                                    <span for="Address">ที่อยู่ / Address</span>
                                                    <textarea type="text" id="addressContact" name="addressAgent" rows="3" cols="25" class="form-control" aria-label="With textarea" required></textarea>
                                                </div>

                                                <div class="col-sm-12 col-12">
                                                    <div class="row mt-2">
                                                        <div class="col-sm-4 col-4">
                                                            <span for="Country">ประเทศ / Country</span>
                                                            <select name="countrydataC" id="countrySelectContact" class="select22" onchange="showcityAInputContact()">
                                                                @foreach($country as $item)
                                                                    <option value="{{ $item->ct_nameENG }}" {{ $item->ct_nameENG == 'Thailand' ? 'selected' : '' }}>
                                                                        {{ $item->ct_nameENG }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-4 col-4" >
                                                            <span for="City">จังหวัด / Province</span>
                                                            <select name="cityC" id="provinceC" class="select22" onchange="provinceContact()" style="width: 100%;">
                                                                <option value=""></option>
                                                                @foreach($provinceNames as $item)
                                                                    <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-4 col-4">
                                                            <span for="Amphures">อำเภอ / District</span>
                                                            <select name="amphuresC" id="amphuresC" class="select22" onchange="amphuresContact()" >
                                                                <option value=""></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-sm-4 col-4">
                                                            <span for="Tambon">ตำบล / Subdistrict</span>
                                                            <select name="TambonC" id ="TambonC" class="select22" onchange="TambonContact()" style="width: 100%;">
                                                                <option value=""></option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-4 col-4">
                                                            <span for="zip_code">รหัสไปรษณีย์ / Postal Code</span>
                                                            <select name="zip_codeC" id ="zip_codeC" class="select22"  style="width: 100%;">
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-4 col-4">
                                                            <span for="Email">อีเมล์ / Email</span>
                                                            <input type="text" id="EmailContact" class="form-control"placeholder="อีเมล์" name="EmailAgent"maxlength="70" required>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                                            <span for="Company_Phone" class="flex-container">
                                                                โทรศัพท์/ Phone number
                                                            </span>
                                                            <button type="button" class="btn btn-color-green my-2" id="add-phoneContact">เพิ่มหมายเลขโทรศัพท์</button>
                                                        </div>
                                                        <div id="phone-containerContact" class="flex-container row">
                                                            <!-- Initial input fields -->
                                                            <div class="col-lg-4 col-md-6 col-sm-12 mt-2">
                                                                <div class="input-group show">
                                                                    <input type="text" name="phoneContact[]" class="form-control phone" maxlength="14" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
                                                                    <button type="button" class="btn btn-outline-danger remove-phoneContact"><i class="bi bi-x-circle" style="width:100%;"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <script>
                                                        document.addEventListener('DOMContentLoaded', function() {
                                                        // ส่วนของกดสำนักงานใหญ่
                                                        const radio1 = document.getElementById('flexRadioDefault2');
                                                        radio1.addEventListener('change', function() {
                                                            if (radio1.checked) {
                                                                var countrySelect =$('#countrySelect').val();
                                                                console.log(countrySelect);

                                                                $('#countrySelectContact').val(countrySelect).trigger('change');
                                                                var province = document.getElementById('province').value;
                                                                var amphures = document.getElementById('amphures').value;
                                                                var Tambon = document.getElementById('Tambon').value;
                                                                var zip_code = document.getElementById('zip_code').value;
                                                                var address = document.getElementById('addressAgent').value;

                                                                if (countrySelect === 'Thailand') {

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
                                                                                $('#provinceC').append(provinceA);
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
                                                                                $('#amphuresC').append(amphuresA);
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
                                                                                $('#TambonC').append(TambonA);
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
                                                                                $('#zip_codeC').append(zip_codeA);
                                                                                console.log(zip_codeA);
                                                                            });
                                                                        },
                                                                    })
                                                                } else {
                                                                    $('#provinceC, #amphuresC, #TambonC, #zip_codeC').empty();
                                                                    $('#provinceC, #amphuresA, #TambonC, #zip_codeC').prop('disabled', true); // ปิดการใช้งาน
                                                                }
                                                                $('#addressContact').val(address);

                                                            }
                                                        });

                                                    });
                                                        function showcityAInputContact() {
                                                            var countrySelectA = document.getElementById("countrySelectContact");
                                                            var province = document.getElementById("provinceC");
                                                            var amphuresSelect = document.getElementById("amphuresC");
                                                            var tambonSelect = document.getElementById("TambonC");
                                                            var zipCodeSelect = document.getElementById("zip_codeC");
                                                            // เช็คค่าที่ถูกเลือกใน dropdown list เมือง
                                                            if (countrySelectA.value !== "Thailand") {
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
                                                                amphuresContact();
                                                                provinceContact();
                                                                TambonContact();
                                                                $('#zip_codeContact').empty();
                                                            }
                                                        }
                                                        function provinceContact(){
                                                            var provinceAgentT = $('#provinceC').val();
                                                            console.log(provinceAgentT);

                                                            jQuery.ajax({
                                                                type:   "GET",
                                                                url:    "{!! url('/Company/amphures/Contact/"+provinceAgentT+"') !!}",
                                                                datatype:   "JSON",
                                                                async:  false,
                                                                success: function(result) {
                                                                    jQuery('#amphuresC').children().remove().end();
                                                                    console.log(result);
                                                                    $('#amphuresC').append(new Option('', ''));
                                                                    jQuery.each(result.data, function(key, value) {
                                                                        var amphuresA = new Option(value.name_th,value.id);
                                                                        //console.log(amphuresA);
                                                                        $('#amphuresC').append(amphuresA);
                                                                    });
                                                                },
                                                            })

                                                        }
                                                        function amphuresContact(){
                                                            var amphuresAgent  = $('#amphuresC').val();
                                                            console.log(amphuresAgent);
                                                            $.ajax({
                                                                type:   "GET",
                                                                url:    "{!! url('/Company/Tambon/Contact/"+amphuresAgent+"') !!}",
                                                                datatype:   "JSON",
                                                                async:  false,
                                                                success: function(result) {
                                                                console.log(result);
                                                                    jQuery('#TambonC').children().remove().end();
                                                                    $('#TambonC').append(new Option('', ''));
                                                                    jQuery.each(result.data, function(key, value) {
                                                                        var TambonA  = new Option(value.name_th,value.id);
                                                                        $('#TambonC').append(TambonA );
                                                                    console.log(TambonA);
                                                                    });
                                                                },
                                                            })
                                                        }
                                                        function TambonContact(){
                                                            var TambonAgent  = $('#TambonC').val();
                                                            console.log(TambonAgent);
                                                            $.ajax({
                                                                type:   "GET",
                                                                url:    "{!! url('/Company/districts/Contact/"+TambonAgent+"') !!}",
                                                                datatype:   "JSON",
                                                                async:  false,
                                                                success: function(result) {
                                                                    console.log(result);
                                                                    jQuery('#zip_codeC').children().remove().end();
                                                                    //console.log(result);
                                                                    $('#zip_codeC').append(new Option('', ''));
                                                                    jQuery.each(result.data, function(key, value) {
                                                                        var zip_codeA  = new Option(value.zip_code,value.zip_code);
                                                                        $('#zip_codeC').append(zip_codeA);
                                                                        //console.log(zip_codeA);
                                                                    });
                                                                },
                                                            })
                                                        }
                                                        { //phone tax
                                                            function formatPhoneNumberCon(value) {
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
                                                            document.getElementById('add-phoneContact').addEventListener('click', function() {
                                                                var phoneContainer = document.getElementById('phone-containerContact');
                                                                var newCol = document.createElement('div');
                                                                newCol.classList.add('col-lg-4', 'col-md-6', 'col-sm-12', 'mt-2');
                                                                newCol.innerHTML = `
                                                                    <div class="input-group show">
                                                                        <input type="text" name="phoneContact[]" class="form-control" maxlength="12" oninput="formatAndUpdate(this)" required>
                                                                        <button type="button" class="btn btn-outline-danger remove-phoneContact"><i class="bi bi-x-circle" style="width:100%;"></i></button>
                                                                    </div>
                                                                `;
                                                                phoneContainer.appendChild(newCol);

                                                                // Add the show class after a slight delay to trigger the transition
                                                                setTimeout(function() {
                                                                    newCol.querySelector('.input-group').classList.add('show');
                                                                }, 10);

                                                                attachRemoveEvent(newCol.querySelector('.remove-phoneContact'));
                                                            });
                                                            function formatAndUpdate(input) {
                                                                const formattedValue = formatPhoneNumberCon(input.value);
                                                                input.value = formattedValue;
                                                            }
                                                            // Function to attach the remove event to the remove buttons
                                                            function attachRemoveEvent(button) {
                                                                button.addEventListener('click', function() {
                                                                    var phoneContainer = document.getElementById('phone-containerContact');
                                                                    if (phoneContainer.childElementCount > 1) {
                                                                        phoneContainer.removeChild(button.closest('.col-lg-4, .col-md-6, .col-sm-12'));
                                                                    }
                                                                });
                                                            }

                                                            // Attach the remove event to the initial remove buttons
                                                            document.querySelectorAll('.remove-phoneContact').forEach(function(button) {
                                                                attachRemoveEvent(button);
                                                            });
                                                        }
                                                        { //Branch
                                                            document.addEventListener('DOMContentLoaded', function() {
                                                                // Get the radio buttons
                                                                const Branch = document.getElementById('flexRadioDefaultContact');

                                                                Branch.addEventListener('change', function() {
                                                                    if (Branch.checked) {
                                                                        $('#BranchContact').val('สำนักงานใหญ่');
                                                                    }
                                                                });
                                                            });
                                                        }
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary lift" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-color-green lift" id="btn-save">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-sm-12 col-12">
                <ul class="nav nav-tabs px-3 border-bottom-0" role="tablist">
                    <li class="nav-item" id="nav1"><a class="nav-link active" data-bs-toggle="tab" href="#nav-Tax" role="tab" onclick="nav($id='nav1')">Additional Company Tax Invoice</a></li>{{--ประวัติการแก้ไข--}}
                    <li class="nav-item" id="nav2"><a class="nav-link " data-bs-toggle="tab" href="#nav-Visit" onclick="nav($id='nav2')" role="tab">Lastest Visit info</a></li>{{--QUOTAION--}}
                    <li class="nav-item" id="nav3"><a class="nav-link" data-bs-toggle="tab" href="#nav-Billing" onclick="nav($id='nav3')" role="tab">Billing Folio info </a></li>{{--เอกสารออกบิล--}}
                    <li class="nav-item" id="nav4"><a class="nav-link " data-bs-toggle="tab" href="#nav-Contract" onclick="nav($id='nav4')" role="tab">Contract Rate Document</a></li>{{--Doc. number--}}
                    <li class="nav-item" id="nav5"><a class="nav-link " data-bs-toggle="tab" href="#nav-Freelancer" onclick="nav($id='nav5')" role="tab">Latest Freelancer By</a></li>{{--ชื่อ คนแนะนำ ครั้งต่อครั้ง ต่อ เอกสาร--}}
                    <li class="nav-item" id="nav6"><a class="nav-link" data-bs-toggle="tab" href="#nav-Commission" onclick="nav($id='nav6')" role="tab">Lastest Freelancer Commission</a></li>{{--% (Percentage) ครั้งต่อครั้ง ต่อ เอกสาร--}}
                    <li class="nav-item" id="nav7"><a class="nav-link" data-bs-toggle="tab" href="#nav-User" onclick="nav($id='nav7')" role="tab">User logs</a></li>{{--ประวัติการแก้ไข--}}
                </ul>
                <div class="card p-4 mb-4">
                    <div class="tab-content">
                        <div class="tab-pane fade  show active" id="nav-Tax" role="tabpanel" rel="0">
                            <div class="row my-3">
                                <div class="col-lg-10"></div>
                                <div class="col-lg-2  d-flex justify-content-end align-items-end">
                                    <button type="button" class="btn btn-color-green lift btn_modal"  data-bs-toggle="modal" data-bs-target="#CreateCompany"><i class="fa fa-plus"></i> Add tax invoice</button>
                                </div>
                            </div>
                            <div style="min-height: 70vh;" class="mt-2">
                                <table id="company-TaxTable" class="table-together table-style">
                                    <thead>
                                        <tr>
                                            <th class="text-center" data-priority="1">No</th>
                                            <th class="text-center" data-priority="1">Profile_ID</th>
                                            <th  data-priority="1">Company/Individual</th>
                                            <th class="text-center">Branch</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($company_tax))
                                            @foreach ($company_tax as $key => $item)
                                                    <tr>
                                                        <td style="text-align: center;">{{ $key + 1 }}</td>
                                                        <td style="text-align: center;">{{ $item->ComTax_ID }}</td>
                                                        @if ($item->Tax_Type == 'Company')
                                                            <td style="white-space:wrap">{{ $item->Companny_name }}</td>
                                                        @else
                                                            <td style="white-space:wrap">{{ $item->first_name }} {{ $item->last_name }}</td>
                                                        @endif
                                                        <td style="text-align: center;">{{ $item->BranchTax }}</td>
                                                        <td style="text-align: center;">
                                                            <input type="hidden" id="status" value="{{ $item->status }}">
                                                            @if ($item->status == 1)
                                                                <button type="button" class="btn btn-light-success btn-sm" value="{{ $item->id }}" onclick="btnstatusTax({{ $item->id }})">ใช้งาน</button>
                                                            @else
                                                                <button type="button" class="btn btn-light-danger btn-sm" value="{{ $item->id }}" onclick="btnstatusTax({{ $item->id }})">ปิดใช้งาน</button>
                                                            @endif
                                                        </td>
                                                        @php
                                                            $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                            $canViewProposal = @Auth::user()->roleMenuView('Company / Agent', Auth::user()->id);
                                                            $canEditProposal = @Auth::user()->roleMenuEdit('Company / Agent', Auth::user()->id);
                                                        @endphp
                                                        <td style="text-align: center;">
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                                <ul class="dropdown-menu border-0 shadow p-3">
                                                                    @if ($rolePermission > 0)
                                                                        @if ($canViewProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Company/viewTax/'.$item->id) }}">View</a></li>
                                                                        @endif
                                                                        @if ($canEditProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Company/editTax/'.$item->id) }}">Edit</a></li>
                                                                        @endif
                                                                    @else
                                                                        @if ($canViewProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Company/viewTax/'.$item->id) }}">View</a></li>
                                                                        @endif
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal fade" id="CreateCompany" tabindex="-1" aria-labelledby="PrenameModalCenterTitle" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog  modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-color-green">
                                            <h5 class="modal-title text-white" id="PrenameModalCenterTitle">Add tax invoice</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{url('/Company/save/Tax/'.$Company->id)}}" method="POST">
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
                                                                    <span for="Company_type_tax">ประเภทบริษัท / Company Type</span>
                                                                    <select name="Company_type_tax" id="Company_type_tax" class="select21" required>
                                                                        <option value=""></option>
                                                                        @foreach($MCompany_type as $item)
                                                                            <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-sm-6 col-6">
                                                                    <span for="Company_Name">ชื่อบริษัท / Company Name</span>
                                                                    <input type="text" id="Company_Name_tax" class="form-control" placeholder="ชื่อบริษัท" name="Company_Name_tax" maxlength="70" required>
                                                                </div>
                                                                <div class="col-sm-6 col-6 mt-2">
                                                                    <span for="Branch">สาขา / Company Branch <span>&nbsp;&nbsp;&nbsp;&nbsp; </span><input class="form-check-input" type="radio" name="flexRadioDefaultBranchTax" id="flexRadioDefaultBranchTax"> สำนักงานใหญ่</span>
                                                                    <input type="text" id="BranchTax" name="BranchTax" placeholder="สาขาบริษัท" class="form-control" maxlength="70" required>
                                                                </div>
                                                                <div class="col-sm-6 col-6 mt-2">
                                                                    <span for="Identification">เลขประจำตัวผู้เสียภาษี / Tax identification number</span><br>
                                                                    <input type="text" id="IdentificationCompany" class="form-control idcard" name="Identification" maxlength="17" placeholder="เลขประจำตัวผู้เสียภาษี" required >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="Title" style="display: none;">
                                                        <div class="col-sm-12 mt-2">
                                                            <div class="row">
                                                                <div class="col-sm-6 col-6" >
                                                                    <span for="prefix">คำนำหน้า / Title</span>
                                                                    <select name="prefix" id="PrefaceSelectCom" class="select21" disabled required>
                                                                            <option value=""></option>
                                                                            @foreach($Mprefix as $item)
                                                                                <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                                                            @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-sm-6 col-6">
                                                                    <span for="first_name">ชื่อ / First Name</span>
                                                                    <input type="text" id="first_nameCom" class="form-control" placeholder="ชื่อ" name="first_nameCom"maxlength="70" disabled required>
                                                                </div>
                                                                <div class="col-sm-6 col-6 mt-2">
                                                                    <span for="last_name" >นามสกุล / Last Name</span>
                                                                    <input type="text" id="last_nameCom" class="form-control" placeholder="นามสกุล" name="last_nameCom"maxlength="70" disabled required>
                                                                </div>
                                                                <div class="col-sm-6 col-6 mt-2">
                                                                    <span for="Identification">เลขบัตรประจำตัวประชาชน / Identification number</span><br>
                                                                    <input type="text" id="IdentificationName" class="form-control idcard" name="Identification" maxlength="13" placeholder="เลขประจำตัวผู้เสียภาษี"disabled required >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-12">
                                                        <span for="Address">ที่อยู่ / Address</span>
                                                        <textarea type="text" id="addressAgent" name="addressAgent" rows="3" cols="25" class="form-control" aria-label="With textarea" required></textarea>
                                                    </div>
                                                    <div class="col-sm-12 col-12">
                                                        <div class="row mt-2">
                                                            <div class="col-sm-4 col-4">
                                                                <span for="Country">ประเทศ / Country</span>
                                                                <select name="countrydataA" id="countrySelectA" class="select21" onchange="showcityAInputTax()">
                                                                    @foreach($country as $item)
                                                                        <option value="{{ $item->ct_nameENG }}" {{ $item->ct_nameENG == 'Thailand' ? 'selected' : '' }}>
                                                                            {{ $item->ct_nameENG }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-4 col-4" >
                                                                <span for="City">จังหวัด / Province</span>
                                                                <select name="cityA" id="provincetax" class="select21" onchange="provinceTax()" style="width: 100%;">
                                                                    <option value=""></option>
                                                                    @foreach($provinceNames as $item)
                                                                        <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-4 col-4">
                                                                <span for="Amphures">อำเภอ / District</span>
                                                                <select name="amphuresA" id="amphuresT" class="select21" onchange="amphuresTax()" >
                                                                    <option value=""></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="col-sm-4 col-4">
                                                                <span for="Tambon">ตำบล / Subdistrict</span>
                                                                <select name="TambonA" id ="TambonT" class="select21" onchange="TambonTax()" style="width: 100%;">
                                                                    <option value=""></option>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-4 col-4">
                                                                <span for="zip_code">รหัสไปรษณีย์ / Postal Code</span>
                                                                <select name="zip_codeA" id ="zip_codeT" class="select21"  style="width: 100%;">
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-4 col-4">
                                                                <span for="Email">อีเมล์ / Email</span>
                                                                <input type="text" id="EmailAgent" class="form-control"placeholder="อีเมล์" name="EmailAgent"maxlength="70" required>
                                                            </div>
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
                                                                        <input type="text" name="phoneTax[]" class="form-control phone" maxlength="14" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
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
                                                            { //phone tax
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

                                                                document.getElementById('add-phoneTax').addEventListener('click', function() {
                                                                    var phoneContainer = document.getElementById('phone-containerTax');
                                                                    var newCol = document.createElement('div');
                                                                    newCol.classList.add('col-lg-4', 'col-md-6', 'col-sm-12', 'mt-2');
                                                                    newCol.innerHTML = `
                                                                        <div class="input-group show">
                                                                            <input type="text" name="phoneTax[]" class="form-control phone-input" maxlength="12" oninput="formatAndUpdate(this)" required>
                                                                            <button type="button" class="btn btn-outline-danger remove-phoneTax"><i class="bi bi-x-circle" style="width:100%;"></i></button>
                                                                        </div>
                                                                    `;
                                                                    phoneContainer.appendChild(newCol);

                                                                    // Add the show class after a slight delay to trigger the transition
                                                                    setTimeout(function() {
                                                                        newCol.querySelector('.input-group').classList.add('show');
                                                                    }, 10);

                                                                    attachRemoveEvent(newCol.querySelector('.remove-phoneTax'));
                                                                });

                                                                // Function to format and update the phone number input field
                                                                function formatAndUpdate(input) {
                                                                    const formattedValue = formatPhoneNumber(input.value);
                                                                    input.value = formattedValue;
                                                                }

                                                                // Function to attach the remove event to the remove buttons
                                                                function attachRemoveEvent(button) {
                                                                    button.addEventListener('click', function() {
                                                                        var phoneContainer = document.getElementById('phone-containerTax');
                                                                        if (phoneContainer.childElementCount > 1) {
                                                                            phoneContainer.removeChild(button.closest('.col-lg-4, .col-md-6, .col-sm-12'));
                                                                        }
                                                                    });
                                                                }

                                                                // Attach the remove event to the initial remove buttons
                                                                document.querySelectorAll('.remove-phoneTax').forEach(function(button) {
                                                                    attachRemoveEvent(button);
                                                                });

                                                            }
                                                            { //Branch
                                                                document.addEventListener('DOMContentLoaded', function() {
                                                                    // Get the radio buttons
                                                                    const Branch = document.getElementById('flexRadioDefaultBranchTax');

                                                                    Branch.addEventListener('change', function() {
                                                                        if (Branch.checked) {
                                                                            $('#BranchTax').val('สำนักงานใหญ่');
                                                                        }
                                                                    });
                                                                });
                                                            }
                                                        </script>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary lift" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-color-green lift" id="btn-save">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-Visit" role="tabpanel" rel="0">
                            <div style="min-height: 70vh;" class="mt-2">
                                <table id="company-VisitTable" class="table-together table-style">

                                    <thead>
                                        <tr>
                                            <th class="text-center" data-priority="1">No</th>
                                            <th class="text-center" data-priority="1">ID</th>
                                            <th data-priority="1">Company</th>
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
                                                <td style="white-space:wrap">{{ @$item->company->Company_Name}}</td>
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

                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade "id="nav-Billing" role="tabpanel" rel="0">
                            <div style="min-height: 70vh;" class="mt-2">

                                <table id="company-BillingTable" class="table-together table-style">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;"data-priority="1">No</th>
                                            <th data-priority="1">Receipt ID</th>
                                            <th>Proposal ID</th>
                                            <th data-priority="1">Company / Individual</th>
                                            <th>Payment Date</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Operated By</th>
                                            <th class="text-center">Document status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($Billing))
                                            @foreach ($Billing as $key => $item)
                                            <tr>
                                                <td style="text-align: center;">
                                                    {{$key +1}}
                                                </td>
                                                <td>{{ $item->Receipt_ID}}</td>
                                                <td>{{ $item->Quotation_ID}}</td>
                                                <td style="white-space:wrap">{{$item->fullname}}</td>
                                                <td>{{ $item->paymentDate }}</td>
                                                <td style="text-align: center;">
                                                    {{ number_format($item->document_amount) }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ @$item->userOperated->name }}
                                                </td>
                                                <td style="text-align: center;">
                                                    <span class="badge rounded-pill bg-success">Confirm</span>
                                                </td>
                                                @php
                                                    $CreateBy = Auth::user()->id;
                                                    $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                    $canViewProposal = @Auth::user()->roleMenuView('Billing Folio', Auth::user()->id);
                                                    $canEditProposal = @Auth::user()->roleMenuEdit('Billing Folio', Auth::user()->id);
                                                @endphp
                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            @if ($canViewProposal == 1)
                                                                <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Document/BillingFolio/Proposal/invoice/view/'.$item->id) }}">Export</a></li>
                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/invoice/log/'.$item->id) }}">LOG</a></li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <div class="tab-pane fade "id="nav-Contract" role="tabpanel" rel="0">

                        </div>

                        <div class="tab-pane fade" id="nav-User" role="tabpanel" rel="0">
                            <div style="min-height: 70vh;" class="mt-2">

                                <table id="company-LogTable" class="table-together table-style">
                                    <thead>
                                        <tr>
                                            <th  class="text-center"data-priority="1">No</th>
                                            <th >Category</th>
                                            <th  class="text-center"data-priority="1">Type</th>
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
                                                <td style="text-align: left;white-space:wrap;" >

                                                    <b style="color:#0000FF ">{{$item->Category}}</b>
                                                    @foreach($contentArray as $contentItem)
                                                        <div style="white-space:wrap">{{ $contentItem }}</div>
                                                    @endforeach
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Get the radio buttons
        const Branch = document.getElementById('flexRadioDefaultBranch');

        Branch.addEventListener('change', function() {
            if (Branch.checked) {
                $('#Branch').val('สำนักงานใหญ่');
            }
        });
    });
    $(document).ready(function() {
        var countrySelect = $('#countrySelect');
        var TaxSelectA = countrySelect.val(); // ใช้ jQuery เพื่อเลือก element
        var amphuresSelect = document.getElementById("amphures");
        var tambonSelect = document.getElementById("Tambon");
        var zipCodeSelect = document.getElementById("zip_code");
        var province = document.getElementById("province");

        if (TaxSelectA != "Thailand") {
            // ปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
            amphuresSelect.disabled = true;
            tambonSelect.disabled = true;
            zipCodeSelect.disabled = true;
            province.disabled = true;

        } else if (TaxSelectA == "Thailand"){
            // ปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
            amphuresSelect.disabled = false;
            tambonSelect.disabled = false;
            zipCodeSelect.disabled = false;
            province.disabled = false;
        }
    });
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
    function showcityInput() {
        var countrySelect = document.getElementById("countrySelect");
        var amphuresSelect = document.getElementById("amphures");
        var tambonSelect = document.getElementById("Tambon");
        var zipCodeSelect = document.getElementById("zip_code");
        var province = document.getElementById("province");

        // เช็คค่าที่ถูกเลือกใน dropdown list เมือง
        if (countrySelect.value !== "Thailand") {
            amphuresSelect.disabled = true;
            tambonSelect.disabled = true;
            zipCodeSelect.disabled = true;
            province.disabled = true;
        } else if (countrySelect.value === "Thailand") {

            amphuresSelect.disabled = false;
            tambonSelect.disabled = false;
            zipCodeSelect.disabled = false;
            province.disabled = false;
            select_amphures();
            select_province();
            select_Tambon();
            $('#zip_code').empty();
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
    function confirmSubmit(event) {
        event.preventDefault(); // Prevent the form from submitting
        var Company_Name = $('#Company_Name').val();
        var Branch = $('#Branch').val();
        var message = `Do you want to save the company data for ${Company_Name}, branch ${Branch}?`;
        Swal.fire({
            title: "Do you want to save the data?",
            text: message,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "Cancel",
            confirmButtonColor: "#2C7F7A",
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
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        function formatPhoneNumber(value) {
            // ลบตัวอักษรที่ไม่ใช่ตัวเลขออก
            value = value.replace(/\D/g, "");
            let formattedValue = "";

            // ฟอร์แมตหมายเลขโทรศัพท์
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

        function updatePhoneInput(event) {
            const input = event.target;
            input.value = formatPhoneNumber(input.value);
        }

        // ฟังก์ชันที่ใช้ในการเพิ่มฟิลด์ใหม่
        document.getElementById('add-input').addEventListener('click', function() {
            var container = document.getElementById('phone-inputs-container');
            var index = container.querySelectorAll('.input-group').length;
            var newInputGroup = document.createElement('div');
            newInputGroup.classList.add('input-group', 'mb-3');
            newInputGroup.innerHTML = `
                <input type="text" name="phone[]" class="form-control phone-input" maxlength="12" data-index="${index}" data-old-value="">
                <button type="button" class="btn btn-outline-danger remove-input"><i class="bi bi-x-circle" style="width:100%;"></i></button>
            `;
            container.appendChild(newInputGroup);
            addRemoveButtonListener(newInputGroup.querySelector('.remove-input'));
            addInputChangeListener(newInputGroup.querySelector('.phone-input'));
            updateIndices();
        });

        // ฟังก์ชันที่ใช้ในการเพิ่มฟิลด์แฟกซ์
        document.getElementById('add-fax').addEventListener('click', function() {
            var container = document.getElementById('fax-inputs-container');
            var index = container.querySelectorAll('.input-group').length;
            var newInputGroup = document.createElement('div');
            newInputGroup.classList.add('input-group', 'mb-3');
            newInputGroup.innerHTML = `
                <input type="text" name="fax[]" class="form-control fax-input" maxlength="11" data-index="${index}" data-old-value="">
                <button type="button" class="btn btn-outline-danger remove-input"><i class="bi bi-x-circle" style="width:100%;"></i></button>
            `;
            container.appendChild(newInputGroup);
            addRemoveButtonListener(newInputGroup.querySelector('.remove-input'));
            addInputChangeListener(newInputGroup.querySelector('.fax-input'));
            updateIndices();
        });

        // เพิ่ม event listener ให้กับปุ่มลบที่มีอยู่
        document.querySelectorAll('.remove-input').forEach(function(button) {
            addRemoveButtonListener(button);
        });

        // เพิ่ม event listener ให้กับ input field ที่มีอยู่
        document.querySelectorAll('.phone-input').forEach(function(input) {
            addInputChangeListener(input);
        });

        // ฟังก์ชันที่ใช้ในการเพิ่ม event listener ให้กับปุ่มลบ
        function addRemoveButtonListener(button) {
            button.addEventListener('click', function() {
                var container = button.closest('.input-group');
                container.parentElement.removeChild(container);
                updateIndices();
            });
        }

        // ฟังก์ชันที่ใช้ในการเพิ่ม event listener ให้กับ input field
        function addInputChangeListener(input) {
            input.addEventListener('input', updatePhoneInput);
        }

        // ฟังก์ชันที่ใช้ในการอัปเดตดัชนีของ input fields
        function updateIndices() {
            var phoneInputs = document.querySelectorAll('.phone-input');
            phoneInputs.forEach(function(input, index) {
                input.setAttribute('data-index', index);
                input.setAttribute('name', `phone[${index}]`);
            });

            var faxInputs = document.querySelectorAll('.fax-input');
            faxInputs.forEach(function(input, index) {
                input.setAttribute('data-index', index);
                input.setAttribute('name', `fax[${index}]`);
            });
        }
    });
</script>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<!-- dataTable -->
<script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.2/js/dataTables.semanticui.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.semanticui.js"></script>
<script src="{{ asset('assets/js/table-together.js') }}"></script>
    <script>


        function btnstatusTax(id) {
            jQuery.ajax({
                type: "GET",
                url: "{!! url('/company/change-status/tax/" + id + "') !!}",
                datatype: "JSON",
                async: false,
                success: function(result) {
                    Swal.fire('Data has been successfully saved!', '', 'success');
                    location.reload();
                },
            });
        }
        function btnstatusContact(id) {
            jQuery.ajax({
                type: "GET",
                url: "{!! url('/company/change-status/Contact/" + id + "') !!}",
                datatype: "JSON",
                async: false,
                success: function(result) {
                    Swal.fire('Data has been successfully saved!', '', 'success');
                    location.reload();
                },
            });
        }

    </script>
    <script>
         function nav(id) {
            $.fn.dataTable
            .tables({ visible: true, api: true })
            .columns.adjust()
            .responsive.recalc();
        }
    </script>
@endsection
