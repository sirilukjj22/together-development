@extends('layouts.masterLayout')
<style>
    .form-select{
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
                    <div class="span3">View Company / Agent</div>
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
                                <select name="booking_channel" id="booking_channel" class="form-select"disabled>
                                    @foreach($booking_channel as $item)
                                        <option value="{{ $item->id }}" {{$Company->Booking_Channel == $item->id ? 'selected' : ''}}>{{ $item->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label for="country">ประเทศ / Country</label>
                                <select name="countrydata" id="countrySelect" class="form-select" onchange="showcityInput()"disabled>
                                    <option value="Thailand" {{$Company->Country == "Thailand" ? 'selected' : ''}}>{{$Company->Country}}</option>
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
                            @if (($Company->Country === 'Thailand'))
                                <div class="col-lg-3 col-md-6 col-sm-12" id="citythai" style="display:block;">
                                    <label for="city">จังหวัด / Province</label>
                                    <select name="province" id="province" class="form-select" onchange="select_province()"disabled>
                                        @foreach($provinceNames as $item)
                                        <option value="{{ $item->id }}" {{$Company->City == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <div class="col-lg-3 col-md-6 col-sm-12" id="citythai" style="display:none;">
                                    <label for="city">จังหวัด / Province</label>
                                    <select name="province" id="province" class="form-select" onchange="select_province()"disabled>
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
                                    <select name="amphures" id="amphures" class="form-select" onchange="select_amphures()"disabled>
                                        @foreach($amphures as $item)
                                        <option value="{{ $item->id }}" {{ $Company->Amphures == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <label for="Amphures">อำเภอ / District</label>
                                    <select name="amphures" id="amphures" class="form-select" onchange="select_amphures()" disabled>
                                        <option value=""></option>
                                    </select>
                                </div>
                            @endif
                            @if ($Company->Country === 'Thailand')
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <label for="Tambon">ตำบล / Subdistrict </label>
                                    <select name="Tambon" id="Tambon" class="form-select" onchange="select_Tambon()"disabled>
                                        @foreach($Tambon as $item)
                                        <option value="{{ $item->id }}" {{ $Company->Tambon == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <label for="Tambon">ตำบล / Subdistrict </label>
                                    <select name="Tambon" id="Tambon" class="form-select" onchange="select_Tambon()" disabled>
                                        <option value=""></option>
                                    </select>
                                </div>
                            @endif
                                <div class="col-lg-3 col-md-6 col-sm-12">
                            @if ($Company->Country === 'Thailand')
                                    <label for="zip_code">รหัสไปรษณีย์ / Postal Code</label><br>
                                    <select name="zip_code" id="zip_code" class="form-select"disabled>
                                        @foreach($Zip_code as $item)
                                        <option value="{{ $item->id }}" {{ $Company->Zip_Code == $item->zip_code ? 'selected' : '' }}>{{ $item->zip_code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <div class="col-3">
                                    <label for="zip_code">รหัสไปรษณีย์ / Postal Code</label><br>
                                    <select name="zip_code" id="zip_code" class="form-select" disabled>
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
                            <div class="row mt-3">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="custom-accordion">
                                        <input type="checkbox" id="trigger1" />
                                        <label for="trigger1">ติดต่อ / Contact</label>
                                        <div class="custom-accordion-content">
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
                                                    <span for="first_name">First Name</span><br>
                                                    <input type="text" id="first_nameAgent"class="form-control" name="first_nameAgent" maxlength="70" disabled value="{{$representative->First_name}}">
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12"><span for="last_name">Last Name</span><br>
                                                    <input type="text" id="last_nameAgent"class="form-control" name="last_nameAgent" maxlength="70" disabled value="{{$representative->Last_name}}">
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <span for="Address">Address</span><br>
                                                    <textarea type="text" id="addressAgent" name="addressAgent" rows="3" cols="25" class="form-control" aria-label="With textarea" disabled>{{$representative->Address}}</textarea>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <span for="Country">Country</span><br>
                                                    <select name="countrydataA" id="countrySelectA" class="form-select" onchange="showcityAInput()"disabled>
                                                        <option value="Thailand" {{$representative->Country == "Thailand" ? 'selected' : ''}}>{{$representative->Country}}</option>
                                                    </select>
                                                </div>
                                                @if (($representative->Country === 'Thailand'))
                                                    <div class="col-lg-4 col-md-6 col-sm-12" id="citythai" style="display:block;">
                                                        <span for="city">City</span><br>
                                                        <select name="province" id="province" class="form-select" onchange="select_province()"disabled>
                                                            @foreach($provinceNames as $item)
                                                            <option value="{{ $item->id }}" {{$representative->City == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @else
                                                    <div class="col-lg-4 col-md-6 col-sm-12" id="citythai" style="display:none;">
                                                        <span for="city">City</span><br>
                                                        <select name="province" id="province" class="form-select" onchange="select_province()"disabled>
                                                            <option value=""></option>
                                                            @foreach($provinceNames as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif
                                                @if ($representative->Country === 'Thailand')
                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                        <span for="Amphures">Amphures</span><br>
                                                        <select name="amphures" id="amphures" class="form-select" onchange="select_amphures()"disabled>
                                                            @foreach($amphures as $item)
                                                            <option value="{{ $item->id }}" {{ $representative->Amphures == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @else
                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                        <span for="Amphures">Amphures</span><br>
                                                        <select name="amphures" id="amphures" class="form-select" onchange="select_amphures()" disabled>
                                                            <option value=""></option>
                                                        </select>
                                                    </div>
                                                @endif
                                                @if ($representative->Country === 'Thailand')
                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                        <span for="Tambon">Tambon </span><br>
                                                        <select name="Tambon" id="Tambon" class="form-select" onchange="select_Tambon()"disabled>
                                                        @foreach($Tambon as $item)
                                                        <option value="{{ $item->id }}" {{ $representative->Tambon == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                                        @endforeach
                                                    </select>
                                                    </div>
                                                @else
                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                        <span for="Tambon">Tambon </span><br>
                                                        <select name="Tambon" id="Tambon" class="form-select" onchange="select_Tambon()" disabled>
                                                            <option value=""></option>
                                                        </select>
                                                    </div>
                                                @endif
                                                @if ($representative->Country === 'Thailand')
                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                        <span for="zip_code">Zip Code</span><br>
                                                        <select name="zip_code" id="zip_code" class="form-select"  disabled>
                                                            @foreach($Zip_code as $item)
                                                            <option value="{{ $item->id }}" {{ $representative->Zip_Code == $item->zip_code ? 'selected' : '' }}>{{ $item->zip_code }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @else
                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                        <span for="zip_code">Zip Code</span><br>
                                                        <select name="zip_code" id="zip_code" class="form-select" disabled>
                                                            <option value=""></option>
                                                        </select>
                                                    </div>
                                                @endif
                                                <div class="col-lg-4 col-md-6 col-sm-12">
                                                    <span for="Email">Email</span><br>
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
                            <div class="col-4"></div>
                            <div class="col-4" style="display:flex; justify-content:center; align-items:center;">
                                <button type="button" class="btn btn-secondary lift " onclick="window.location.href='{{ route('Company','index') }}'" >{{ __('ย้อนกลับ') }}</button>
                            </div>
                            <div class="col-4"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
