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
                <small class="text-muted">Welcome to Edit Company / Agent.</small>
                <h1 class="h4 mt-1">Edit Company / Agent (แก้ไขบริษัทและตัวแทน)</h1>
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
                                <label for="Branch">สาขา / Company Branch</label>
                                <input type="text" id="Branch" name="Branch" class="form-control" maxlength="70" required value="{{$Company->Branch}}">
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="Market">กลุ่มตลาด / Market</label>
                                <select name="Mmarket" id="Mmarket" class="form-select">
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
                                <select name="booking_channel" id="booking_channel" class="select2">
                                    @foreach($booking_channel as $item)
                                        <option value="{{ $item->id }}" {{$Company->Booking_Channel == $item->id ? 'selected' : ''}}>{{ $item->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="country">ประเทศ / Country</label>
                                <select name="countrydata" id="countrySelect" class="form-select" onchange="showcityInput()">
                                    <option value="Thailand" {{$Company->Country == "Thailand" ? 'selected' : ''}}>ประเทศไทย</option>
                                    <option value="Other_countries" {{$Company->Country == "Other_countries" ? 'selected' : ''}}>ประเทศอื่นๆ</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <label for="address">ที่อยู่ / Address</label>
                                <textarea type="text" id="address" name="address" rows="5" cols="25" class="form-control" aria-label="With textarea" required>{{$Company->Address}}</textarea>
                            </div>
                        </div>
                        <div class="row mt-2">
                            @if ($Company->Country === 'Other_countries')
                                <div class="col-lg-3 col-md-6 col-sm-12" id="cityInput">
                                    <label for="city">จังหวัด / Province</label>
                                    <input type="text" id="city" name="city" value="{{$Other_City}}">
                                </div>
                            @else
                                <div class="col-lg-3 col-md-6 col-sm-12" id="cityInput" style="display:none;">
                                    <label for="city">จังหวัด / Province</label>
                                    <input type="text" id="city" name="city">
                                </div>
                            @endif
                            @if (($Company->Country === 'Thailand'))
                                <div class="col-lg-3 col-md-6 col-sm-12" id="citythai" style="display:block;">
                                    <label for="city">จังหวัด / Province</label>
                                    <select name="province" id="province" class="select2" onchange="select_province()">
                                        @foreach($provinceNames as $item)
                                        <option value="{{ $item->id }}" {{$Company->City == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <div class="col-lg-3 col-md-6 col-sm-12" id="citythai" style="display:none;">
                                    <label for="city">จังหวัด / Province</label>
                                    <select name="province" id="province" class="select2" onchange="select_province()">
                                        <option value=""></option>
                                        @foreach($provinceNames as $item)
                                        <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            @if ($Company->Country === 'Thailand')
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <label for="Amphures">อำเภอ / District</label>
                                    <select name="amphures" id="amphures" class="select2" onchange="select_amphures()">
                                        @foreach($amphures as $item)
                                        <option value="{{ $item->id }}" {{ $Company->Amphures == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <label for="Amphures">อำเภอ / District</label>
                                    <select name="amphures" id="amphures" class="select2" onchange="select_amphures()" disabled>
                                        <option value=""></option>
                                    </select>
                                </div>
                            @endif
                            @if ($Company->Country === 'Thailand')
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <label for="Tambon">ตำบล / Subdistrict </label>
                                    <select name="Tambon" id="Tambon" class="select2" onchange="select_Tambon()">
                                        @foreach($Tambon as $item)
                                        <option value="{{ $item->id }}" {{ $Company->Tambon == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <label for="Tambon">ตำบล / Subdistrict </label>
                                    <select name="Tambon" id="Tambon" class="select2" onchange="select_Tambon()" disabled>
                                        <option value=""></option>
                                    </select>
                                </div>
                            @endif
                            @if ($Company->Country === 'Thailand')
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <label for="zip_code">รหัสไปรษณีย์ / Postal Code</label><br>
                                    <select name="zip_code" id="zip_code" class="select2">
                                        @foreach($Zip_code as $item)
                                        <option value="{{ $item->id }}" {{ $Company->Zip_Code == $item->zip_code ? 'selected' : '' }}>{{ $item->zip_code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <div class="col-3">
                                    <label for="zip_code">รหัสไปรษณีย์ / Postal Code</label><br>
                                    <select name="zip_code" id="zip_code" class="select2" disabled>
                                        <option value=""></option>
                                    </select>
                                </div>
                            @endif
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label for="Company_Phone">
                                        Company Phone number
                                    </label>
                                    <button  type="button" class="btn btn-color-green my-2 add-input" id="add-input">เพิ่มหมายเลขโทรศัพท์</button>
                                <div id="phone-inputs-container" class="flex-container">
                                    @foreach($phoneDataArray as $index => $phone)
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="phone-main" name="phone[]" value="{{ $phone['Phone_number'] }}" data-index="{{ $index }}" data-old-value="{{ $phone['Phone_number'] }}"required>
                                            <button class="btn btn-outline-danger remove-input" type="button" id="remove-input"><i class="bi bi-x-circle" style="width:100%;"></i></button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="Company_Phone" class="flex-container">
                                    แฟกซ์ของบริษัท / Company Fax number
                                </label>
                                <button type="button" class="btn btn-color-green my-2" id="add-fax">เพิ่มหมายเลขแฟกซ์</button>
                                <div id="fax-inputs-container" class="flex-container">
                                    @foreach($faxArray as $index => $phone)
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control"  name="fax[]" maxlength="10"  value="{{ $phone['Fax_number'] }}" data-index="{{ $index }}" data-old-value="{{ $phone['Fax_number'] }}" required>
                                            <button class="btn btn-outline-danger remove-input" type="button" id="remove-input"><i class="bi bi-x-circle" style="width:100%;"></i></button>
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
                                <input type="text" id="Taxpayer_Identification" class="form-control" name="Taxpayer_Identification" maxlength="13" placeholder="เลขประจำตัวผู้เสียภาษี" required value="{{$Company->Taxpayer_Identification}}">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="Discount_Contract_Rate">อัตราคิดลด / Discount Contract Rate</label><br>
                                <input type="text" id="Discount_Contract_Rate" class="form-control" name="Discount_Contract_Rate" maxlength="70" required value="{{$Company->Discount_Contract_Rate}}">
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="contract_rate_start_date">Contract Rate Start Date</label><br>
                                <input class="form-control" type="date" id="contract_rate_start_date" name="contract_rate_start_date" value="{{$Company->Contract_Rate_Start_Date}}">
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="contract_rate_end_date">Contract Rate End Date</label><br>
                                <input class="form-control" type="date" id="contract_rate_end_date" name="contract_rate_end_date" value="{{$Company->Contract_Rate_End_Date}}">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <label for="Lastest_Introduce_By">แนะนำล่าสุดโดย / Lastest Introduce By</label><br>
                                <input type="text" id="Lastest_Introduce_By" class="form-control" name="Lastest_Introduce_By" maxlength="70" required value="{{$Company->Lastest_Introduce_By}}">
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
                    <div style="border: 1px solid #2D7F7B;" class="mt-5"></div>
                    <div class="row mt-5 my-3">
                        <div class="col-lg-10"></div>
                        <div class="col-lg-2  d-flex justify-content-end align-items-end">
                            <button type="button" class="btn btn-color-green lift btn_modal"  data-bs-toggle="modal" data-bs-target="#CreateConttact"><i class="fa fa-plus"></i> เพิ่มตัวแทนบริษัท</button>
                        </div>
                    </div>
                    <form enctype="multipart/form-data" class="row g-3 basic-form" id="form-id2">
                        @csrf
                        <input type="hidden" name="category" value="company">
                        <table class="myDataTableProductItem table table-hover align-middle mb-0" style="width:100%">
                            <thead>
                                <tr>
                                    <th>เรียงลำดับ</th>
                                    <th>รหัสโปรไฟล์</th>
                                    <th>ชื่อองค์กร</th>
                                    <th>สาขา</th>
                                    <th>ชื่อและนามสกุลผู้ใช้งาน</th>
                                    <th class="text-center">สถานะการใช้งาน</th>
                                    <th class="text-center">คำสั่ง</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($representative))
                                    @foreach ($representative as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->Profile_ID }}</td>
                                        <td>{{ $item->Company_Name }}</td>
                                        <td>{{ $item->Branch }}</td>
                                        <td>{{ $item->First_name }} {{ $item->Last_name }}</td>
                                        <td style="text-align: center;">
                                            @if ($item->status == 1)
                                            <button type="button" class="btn btn-light-success btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ใช้งาน</button>
                                            @else
                                                <button type="button" class="btn btn-light-danger btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ปิดใช้งาน</button>
                                            @endif
                                        </td>
                                        <td style="text-align: center;">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">ทำรายการ &nbsp;</button>
                                                <ul class="dropdown-menu border-0 shadow p-3">
                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Company/edit/contact/editcontact/'.$Company->id.'/'.$item->id) }}">แก้ไขรายการ</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                            </tbody>
                        </table>
                    </form>
                    <div class="modal fade" id="CreateConttact" tabindex="-1" aria-labelledby="PrenameModalCenterTitle"
                    style="display: none;" aria-hidden="true">
                        <div class="modal-dialog  modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-color-green">
                                    <h5 class="modal-title text-white" id="PrenameModalCenterTitle">เพิ่มตัวแทนบริษัท</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="col-12">
                                            <div class="card-body">
                                                <form action="{{url('/Company/edit/contact/create/'.$Company->id)}}" method="POST" enctype="multipart/form-data" class="row g-3 basic-form" id="form-id">
                                                    @csrf
                                                    <div class="col-sm-12 col-12">
                                                        <div class="row">
                                                            <div class="col-sm-2 col-2">
                                                                <span for="prefix">คำนำหน้า / Title</span>
                                                                <select name="prefix" id="PrefaceSelect" class="form-select" required>
                                                                    <option value=""></option>
                                                                        @foreach($Mprefix as $item)
                                                                            <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                                                        @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-5 col-5">
                                                                <span for="first_name">First Name</span>
                                                                <input type="text" id="first_nameAgent" class="form-control" name="first_nameAgent"maxlength="70" required>
                                                            </div>
                                                            <div class="col-sm-5 col-5">
                                                                <span for="last_name" >Last Name</span>
                                                                <input type="text" id="last_nameAgent" class="form-control" name="last_nameAgent"maxlength="70" required>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="col-sm-4 col-4">
                                                                <span for="Country">Country</span>
                                                                <select name="countrydataA" id="countrySelectA" class="form-select" onchange="showcityAInput()">
                                                                    <option value="Thailand">ประเทศไทย</option>
                                                                    <option value="Other_countries">ประเทศอื่นๆ</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-4 col-4" id="cityInputA" style="display:none;">
                                                                <span for="City">City</span>
                                                                <input type="text" id="cityA" class="form-control" name="cityA">
                                                            </div>
                                                            <div class="col-sm-4 col-4" id="citythaiA" style="display:block;">
                                                                <span for="City">City</span>
                                                                <select name="provinceAgent" id="provinceAgent" class="form-select" onchange="provinceA()" style="width: 100%;">
                                                                    <option value=""></option>
                                                                    @foreach($provinceNames as $item)
                                                                        <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-4 col-4">
                                                                <span for="Amphures">Amphures</span>
                                                                <select name="amphuresA" id="amphuresA" class="form-select" onchange="amphuresAgent()" >
                                                                    <option value=""></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="col-sm-4 col-4">
                                                                <span for="Tambon">Tambon</span>
                                                                <select name="TambonA" id ="TambonA" class="form-select" onchange="TambonAgent()" style="width: 100%;">
                                                                    <option value=""></option>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-4 col-4">
                                                                <span for="zip_code">zip_code</span>
                                                                <select name="zip_codeA" id ="zip_codeA" class="form-select"  style="width: 100%;">
                                                                    <option value=""></option>
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
                                                            <div class="col-sm-8 col-8">
                                                                <label for="Phone_number">หมายเลขโทรศัพท์ / Phone number</label>
                                                                <button type="button" class="add-phone btn btn-color-green my-2" id="add-phone" data-target="phone-container">เพิ่มเบอร์โทรศัพท์</button>
                                                            </div>
                                                            <div class="col-sm-6 col-6">
                                                                <div class="row">
                                                                    <div id="phone-container">
                                                                        <div class="input-group mb-3">
                                                                            <input type="text" class="form-control" id="phone-main" name="phoneCon[]" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);"required>
                                                                            <button class="btn btn-outline-danger remove-phone" type="button" id="remove-phone"><i class="bi bi-x-circle" style="width:100%;"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <script>
                                                                document.getElementById('add-phone').addEventListener('click', function() {
                                                                    var phoneContainer = document.getElementById('phone-container');
                                                                    var newPhoneGroup = phoneContainer.firstElementChild.cloneNode(true);
                                                                    newPhoneGroup.querySelector('input').value = '';
                                                                    phoneContainer.appendChild(newPhoneGroup);
                                                                    attachRemoveEvent(newPhoneGroup.querySelector('.remove-phone'));
                                                                });

                                                                function attachRemoveEvent(button) {
                                                                    button.addEventListener('click', function() {
                                                                        var phoneContainer = document.getElementById('phone-container');
                                                                        if (phoneContainer.childElementCount > 1) {
                                                                            phoneContainer.removeChild(button.parentElement);
                                                                        }
                                                                    });
                                                                }

                                                                // Attach the remove event to the initial remove button
                                                                attachRemoveEvent(document.querySelector('.remove-phone'));
                                                            </script>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary lift" data-bs-dismiss="modal">ยกเลิก</button>
                                                        <button type="submit" class="btn btn-color-green lift" id="btn-save">สร้าง</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <script>
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
                                    </div>
                                </div>
                            </div>
                        </div>
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
    function btnstatus(id) {
        jQuery.ajax({
            type: "GET",
            url: "{!! url('/Company/contact/change-status/" + id + "') !!}",
            datatype: "JSON",
            async: false,
            success: function(result) {
                Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                location.reload();
            },
        });
    }
</script>
{{-- ส่วนบริษัท --}}
<script>
    function confirmSubmit(event) {
        event.preventDefault(); // Prevent the form from submitting
        var Company_Name = $('#Company_Name').val();
        var Branch = $('#Branch').val();
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
{{-- ส่วนของที่อยู่ --}}
<script>
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
</script>
{{-- ส่วนของโทรศัพท์และแฟกต์ --}}
<script>
    document.getElementById('add-input').addEventListener('click', function() {
        var container = document.getElementById('phone-inputs-container');
        var index = container.querySelectorAll('.input-group').length;
        var newInputGroup = document.createElement('div');
        newInputGroup.classList.add('input-group');
        newInputGroup.innerHTML = `
        <div class="input-group mb-3">
            <input type="text" name="phone[]" class="form-control phone-input" maxlength="10" value="" data-index="${index}" data-old-value="">
            <button type="button" class="btn btn-outline-danger remove-input" id="remove-input"><i class="bi bi-x-circle" style="width:100%;"></i></button>
        </div>
        `;
        container.appendChild(newInputGroup);
        addRemoveButtonListener(newInputGroup.querySelector('.remove-input'));
        addInputChangeListener(newInputGroup.querySelector('.phone-input'));
        updateWindowPhoneChanged();
    });

    document.getElementById('add-fax').addEventListener('click', function() {
        var container = document.getElementById('fax-inputs-container');
        var index = container.querySelectorAll('.input-group').length;
        var newInputGroup = document.createElement('div');
        newInputGroup.classList.add('input-group');
        newInputGroup.innerHTML = `
            <div class="input-group mb-3">
                <input type="text" name="fax[]" class="form-control fax-input" maxlength="11" value="" data-index="${index}" data-old-value="">
                <button class="btn btn-outline-danger remove-input" type="button" id="remove-input"><i class="bi bi-x-circle" style="width:100%;"></i></button>
            </div>
        `;
        container.appendChild(newInputGroup);
        addRemoveButtonListener(newInputGroup.querySelector('.remove-input'));
        addInputChangeListener(newInputGroup.querySelector('.fax-input'));
        updateWindowPhoneChanged();
    });

    document.querySelectorAll('.remove-input').forEach(function(button) {
        addRemoveButtonListener(button);
    });

    document.querySelectorAll('.phone-input, .fax-input').forEach(function(input) {
        addInputChangeListener(input);
    });

    function addRemoveButtonListener(button) {
        button.addEventListener('click', function() {
            var container = button.closest('.input-group, .input-group');
            container.parentElement.removeChild(container);
            updateIndices();
            updateWindowPhoneChanged();
        });
    }

    function addInputChangeListener(input) {
        input.addEventListener('input', function() {
            if (input.value !== input.getAttribute('data-old-value')) {
                updateWindowPhoneChanged();
            }
        });
    }

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

    function updateWindowPhoneChanged() {
        window.phoneChanged1 = 1;
        console.log('phoneChanged1:', window.phoneChanged1);
    }
</script>
@endsection
