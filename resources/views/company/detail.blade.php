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
                    <div class="row mt-2">
                        <div class="col-lg-11 col-md-11 col-sm-12">
                            <h1 class="h4 mt-1">บริษัทและตัวแทน</h1>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-12">
                            <input style=" float:right;" type="text" class="form-control" id="Profile_ID" name="Profile_ID" maxlength="70" required  value="{{$Profile_ID}}" disabled>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label for="Company_type">ประเภทบริษัท / Company Type</label>
                            <select name="Company_type" id="Company_type" class="form-select"disabled>
                                <option value=""></option>
                                @foreach($MCompany_type as $item)
                                    <option value="{{ $item->id }}" {{$Company->Company_type == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-8 col-md-6 col-sm-12">
                            <label for="Company_Name">ชื่อบริษัท / Company Name</label>
                            <input type="text" id="Company_Name" class="form-control" name="Company_Name" maxlength="70" required value="{{$Company->Company_Name}}"disabled>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <label for="Branch">สาขา / Company Branch</label>
                            <input type="text" class="form-control" id="Branch" name="Branch" maxlength="70" required value="{{$Company->Branch}}"disabled>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <label for="Market">กลุ่มตลาด / Market</label>
                            <select name="Mmarket" id="Mmarket" class="form-select"disabled>
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
                            <select name="booking_channel" id="booking_channel" class="select2"disabled>
                                @foreach($booking_channel as $item)
                                    <option value="{{ $item->id }}" {{$Company->Booking_Channel == $item->id ? 'selected' : ''}}>{{ $item->name_en }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <label for="country">ประเทศ / Country</label>
                            <select name="countrydata" id="countrySelect" class="form-select" onchange="showcityInput()"disabled>
                                <option value="Thailand" {{$Company->Country == "Thailand" ? 'selected' : ''}}>ประเทศไทย</option>
                                <option value="Other_countries" {{$Company->Country == "Other_countries" ? 'selected' : ''}}>ประเทศอื่นๆ</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <label for="address">ที่อยู่ / Address</label>
                            <textarea type="text" id="address" name="address" rows="5" cols="25" class="form-control" aria-label="With textarea" disabled>{{$Company->Address}}</textarea>
                        </div>
                    </div>
                    <div class="row mt-2">
                        @if ($Company->Country === 'Other_countries')
                            <div class="col-lg-3 col-md-6 col-sm-12" id="cityInput"disabled>
                                <label for="city">จังหวัด / Province</label>
                                <input type="text" id="city" name="city" value="{{$Other_City}}">
                            </div>
                        @else
                            <div class="col-lg-3 col-md-6 col-sm-12" id="cityInput" style="display:none;"disabled>
                                <label for="city">จังหวัด / Province</label>
                                <input type="text" id="city" name="city">
                            </div>
                        @endif
                        @if (($Company->Country === 'Thailand'))
                            <div class="col-lg-3 col-md-6 col-sm-12" id="citythai" style="display:block;">
                                <label for="city">จังหวัด / Province</label>
                                <select name="province" id="province" class="select2" onchange="select_province()"disabled>
                                    @foreach($provinceNames as $item)
                                    <option value="{{ $item->id }}" {{$Company->City == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="col-lg-3 col-md-6 col-sm-12" id="citythai" style="display:none;">
                                <label for="city">จังหวัด / Province</label>
                                <select name="province" id="province" class="select2" onchange="select_province()"disabled>
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
                                <select name="amphures" id="amphures" class="select2" onchange="select_amphures()"disabled>
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
                                <select name="Tambon" id="Tambon" class="select2" onchange="select_Tambon()"disabled>
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
                            <div class="col-lg-3 col-md-6 col-sm-12">
                        @if ($Company->Country === 'Thailand')
                                <label for="zip_code">รหัสไปรษณีย์ / Postal Code</label><br>
                                <select name="zip_code" id="zip_code" class="select2"disabled>
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
                            @foreach($phoneDataArray as $index => $phone)
                            <div class="phone-group mt-2" style="position: relative;">
                                <input type="text" name="phone[]" class="form-control phone-input" maxlength="10" value="{{ $phone['Phone_number'] }}" data-index="{{ $index }}" data-old-value="{{ $phone['Phone_number'] }}"disabled>
                            </div>
                            @endforeach
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <label for="Company_Fax">
                                แฟกซ์ของบริษัท / Company Fax number
                            </label>
                            @foreach($faxArray as $index => $phone)
                                <div class="fax-group mt-2" style="position: relative;">
                                    <input type="text" name="fax[]" class="form-control fax-input" maxlength="11" value="{{ $phone['Fax_number'] }}" data-index="{{ $index }}" data-old-value="{{ $phone['Fax_number'] }}" disabled>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label for="Company_Email">ที่อยู่อีเมลของบริษัท / Company Email</label>
                            <input type="email"id="Company_Email" class="form-control" name="Company_Email" maxlength="70" required value="{{$Company->Company_Email}}"disabled>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label for="Company_Website">เว็บไซต์ของบริษัท / Company Website</label><br>
                            <input type="text" id="Company_Website" name="Company_Website" class="form-control" maxlength="70" required value="{{$Company->Company_Website}}"disabled>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label for="Taxpayer_Identification">เลขประจำตัวผู้เสียภาษี / Tax identification number</label><br>
                            <input type="text" id="Taxpayer_Identification" class="form-control" name="Taxpayer_Identification" maxlength="13" placeholder="เลขประจำตัวผู้เสียภาษี" required value="{{$Company->Taxpayer_Identification}}"disabled>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label for="Discount_Contract_Rate">อัตราคิดลด / Discount Contract Rate</label><br>
                            <input type="text" id="Discount_Contract_Rate" class="form-control" name="Discount_Contract_Rate" maxlength="70" required value="{{$Company->Discount_Contract_Rate}}"disabled>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label for="contract_rate_start_date">Contract Rate Start Date</label><br>
                            <input  class="form-control" type="date" id="contract_rate_start_date" name="contract_rate_start_date" value="{{$Company->Contract_Rate_Start_Date}}"disabled>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label for="contract_rate_end_date">Contract Rate End Date</label><br>
                            <input  class="form-control" type="date" id="contract_rate_end_date" name="contract_rate_end_date" value="{{$Company->Contract_Rate_End_Date}}"disabled>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <label for="Lastest_Introduce_By">แนะนำล่าสุดโดย / Lastest Introduce By</label><br>
                            <input type="text" class="form-control" id="Lastest_Introduce_By" name="Lastest_Introduce_By" maxlength="70" required value="{{$Company->Lastest_Introduce_By}}"disabled>
                        </div>
                    </div>
                    <div style="border: 1px solid #2D7F7B;" class="mt-5"></div>
                    <div class="card border-0">
                        <div class="card-body" id="heading3">
                            <h6 class="mb-0 py-2" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="true" aria-controls="faq3"><span <span class="fw-bold"></span>รายละเอียดตัวแทนองค์กร</h6>
                        </div>
                        <div id="faq3" class="collapse" aria-labelledby="heading3" data-parent="#accordionExample">
                            <div class="card-body border-top">
                                <div class="row mt-2">
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <label class="labelcontact" for="">Title</label>
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
                                        <label for="first_name">First Name</label><br>
                                        <input type="text" id="first_nameAgent"class="form-control" name="first_nameAgent" maxlength="70" disabled value="{{$representative->First_name}}">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12"><label for="last_name">Last Name</label><br>
                                        <input type="text" id="last_nameAgent"class="form-control" name="last_nameAgent" maxlength="70" disabled value="{{$representative->Last_name}}">
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="Country">Country</label><br>
                                        <select name="countrydataA" id="countrySelectA" class="form-select" onchange="showcityAInput()"disabled>
                                            <option value="Thailand" {{$representative->Country == "Thailand" ? 'selected' : ''}}>ประเทศไทย</option>
                                            <option value="Other_countries" {{$representative->Country == "Other_countries" ? 'selected' : ''}}>ประเทศอื่นๆ</option>
                                        </select>
                                    </div>
                                    @if ($representative->Country === 'Other_countries')
                                        <div class="col-lg-4 col-md-6 col-sm-12" id="cityInput">
                                            <label for="city">City</label><br>
                                            <input type="text" id="city" name="city" value="{{$Other_City}}"disabled>
                                        </div>
                                    @else
                                        <div class="col-lg-4 col-md-6 col-sm-12" id="cityInput" style="display:none;">
                                            <label for="city">City</label><br>
                                            <input type="text" id="city" name="city"disabled>
                                        </div>
                                    @endif
                                    @if (($representative->Country === 'Thailand'))
                                        <div class="col-lg-4 col-md-6 col-sm-12" id="citythai" style="display:block;">
                                            <label for="city">City</label><br>
                                            <select name="province" id="province" class="select2" onchange="select_province()"disabled>
                                                @foreach($provinceNames as $item)
                                                <option value="{{ $item->id }}" {{$representative->City == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @else
                                        <div class="col-lg-4 col-md-6 col-sm-12" id="citythai" style="display:none;">
                                            <label for="city">City</label><br>
                                            <select name="province" id="province" class="select2" onchange="select_province()"disabled>
                                                <option value=""></option>
                                                @foreach($provinceNames as $item)
                                                <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    @if ($representative->Country === 'Thailand')
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <label for="Amphures">Amphures</label><br>
                                            <select name="amphures" id="amphures" class="select2" onchange="select_amphures()"disabled>
                                                @foreach($amphures as $item)
                                                <option value="{{ $item->id }}" {{ $representative->Amphures == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @else
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <label for="Amphures">Amphures</label><br>
                                            <select name="amphures" id="amphures" class="select2" onchange="select_amphures()" disabled>
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    @endif
                                    @if ($representative->Country === 'Thailand')
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <label for="Tambon">Tambon </label><br>
                                            <select name="Tambon" id="Tambon" class="select2" onchange="select_Tambon()"disabled>
                                            @foreach($Tambon as $item)
                                            <option value="{{ $item->id }}" {{ $representative->Tambon == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                    @else
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <label for="Tambon">Tambon </label><br>
                                            <select name="Tambon" id="Tambon" class="select2" onchange="select_Tambon()" disabled>
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    @endif
                                    @if ($representative->Country === 'Thailand')
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <label for="zip_code">Zip Code</label><br>
                                            <select name="zip_code" id="zip_code" class="select2"disabled>
                                                @foreach($Zip_code as $item)
                                                <option value="{{ $item->id }}" {{ $representative->Zip_Code == $item->zip_code ? 'selected' : '' }}>{{ $item->zip_code }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @else
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <label for="zip_code">Zip Code</label><br>
                                            <select name="zip_code" id="zip_code" class="select2" disabled>
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    @endif
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="Email">Email</label><br>
                                        <input type="text" id="EmailAgent" class="form-control" name="EmailAgent" maxlength="70" required value="{{$representative->Email}}"disabled>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <label for="Address">Address</label><br>
                                        <textarea type="text" id="addressAgent" name="addressAgent" rows="3" cols="25" class="form-control" aria-label="With textarea" disabled>{{$representative->Address}}</textarea>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <label for="Phone_number">หมายเลขโทรศัพท์ / Phone Number</label>
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
                                <div class="row">
                                    <div class="col-4"></div>
                                    <div class="col-4" style="display:flex; justify-content:center; align-items:center;">
                                        <button type="button" class="btn btn-secondary lift " onclick="window.location.href='{{ route('Company.index') }}'" >{{ __('ย้อนกลับ') }}</button>
                                    </div>
                                    <div class="col-4"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
