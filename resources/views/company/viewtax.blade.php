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
                        <div class="row mt-2">
                            <div class="col-sm-4 col-4">
                                <span for="Country">Add Tax</span>
                                <select name="TaxSelectA" id="TaxSelectA" class="select2" onchange="showTaxInput()" disabled>
                                    @foreach($country as $item)
                                        <option value="{{ $item->ct_nameENG }}" {{ $item->ct_nameENG == $viewTax->Country ? 'selected' : '' }}>
                                            {{ $item->ct_nameENG }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if ($viewTax->Tax_Type == "Company")
                            <div class="row mt-2" id="Com">
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
                        @else
                            <div class="row mt-2" id="Title">
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
                                    <span for="first_name">ชื่อ / First Name</span>
                                    <input type="text" id="first_nameCom" class="form-control" name="first_nameCom"maxlength="70" disabled value="{{$viewTax->first_name}}">
                                </div>
                                <div class="col-sm-6 col-6 mt-2">
                                    <span for="last_name" >นามสกุล / Last Name</span>
                                    <input type="text" id="last_nameCom" class="form-control" name="last_nameCom"maxlength="70" disabled value="{{$viewTax->last_name}}">
                                </div>
                                <div class="col-sm-6 col-6 mt-2">
                                    <span for="Taxpayer_Identification">เลขบัตรประจำตัวประชาชน / Identification number</span>
                                    <input type="text" id="Taxpayer_Identification" class="form-control" name="Taxpayer_Identification" maxlength="13" placeholder="เลขประจำตัวผู้เสียภาษี" disabled value="{{$viewTax->Taxpayer_Identification}}">
                                </div>
                            </div>
                        @endif
                        <div class="row mt-2">
                            <div class="col-sm-4 col-4">
                                <span for="Country">ประเทศ / Country</span>
                                <select name="countrydataA" id="countrySelectA" class="select2" onchange="showcityAInput()"disabled>
                                    <option value="Thailand" {{$viewTax->Country == "Thailand" ? 'selected' : ''}}>ประเทศไทย</option>
                                    <option value="Other_countries" {{$viewTax->Country == "Other_countries" ? 'selected' : ''}}>ประเทศอื่นๆ</option>
                                </select>
                            </div>
                            <div class="col-sm-4 col-4" id="citythaiA" style="display:block;">
                                <span for="City">จังหวัด / Province</span>
                                <select name="provinceAgent" id="provinceAgent" class="select2" onchange="provinceA()" style="width: 100%;" disabled>
                                    <option value=""></option>
                                    @foreach($provinceNames as $item)
                                        <option value="{{ $item->id }}"{{$viewTax->City == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4 col-4">
                                <span for="Amphures">อำเภอ / District</span>
                                <select name="amphuresA" id="amphuresA" class="select2" onchange="amphuresAgent()" disabled>
                                    @foreach($amphures as $item)
                                        <option value="{{ $item->id }}" {{ $viewTax->Amphures == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-4 col-4">
                                <span for="Tambon">ตำบล / Subdistrict</span>
                                <select name="TambonA" id ="TambonA" class="select2" onchange="TambonAgent()" style="width: 100%;"disabled>
                                    @foreach($Tambon as $item)
                                        <option value="{{ $item->id }}" {{ $viewTax->Tambon == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4 col-4">
                                <span for="zip_code">รหัสไปรษณีย์ / Postal Code</span>
                                <select name="zip_codeA" id ="zip_codeA" class="select2"  style="width: 100%;"disabled>
                                    @foreach($Zip_code as $item)
                                        <option value="{{ $item->id }}" {{ $viewTax->Zip_Code == $item->zip_code ? 'selected' : '' }}>{{ $item->zip_code }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4 col-4">
                                <span for="Email">อีเมล์ / Email</span>
                                <input type="text" id="EmailAgent" class="form-control" name="EmailAgent"maxlength="70" disabled value="{{$viewTax->Company_Email}}">
                            </div>
                        </div>
                        <div class="mt-2">
                            <span for="Address">ที่อยู่ / Address</span>
                            <textarea type="text" id="addressAgent" name="addressAgent" rows="3" cols="25" class="form-control" aria-label="With textarea" disabled>{{$viewTax->Address}}</textarea>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-8 col-8">
                                <span for="Phone_number">หมายเลขโทรศัพท์ / Phone Number</span>
                            </div>
                            <div id="phone-container" class="flex-container row">
                                @foreach($phonetaxDataArray as $phone)
                                <div class="col-lg-4 col-md-6 col-sm-12 mt-3">
                                    <div class="input-group show">
                                        <input type="text" name="phone[]" class="form-control" maxlength="12" value="{{ formatPhoneNumber($phone['Phone_number']) }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" disabled>
                                        <button type="button" class="btn btn-outline-danger remove-phone"disabled><i class="bi bi-x-circle" style="width:100%;"></i></button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-3 col-sm-12"></div>
                            <div class="col-lg-6 col-sm-12 d-flex justify-content-center align-items-center">
                                <button type="button" class="btn btn-secondary lift  btn-space"  onclick="window.location.href='{{url('/Company/edit/'.$CompanyID)}}'">{{ __('Back') }}</button>
                            </div>
                            <div class="col-lg-3 col-sm-12"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Please select an option"
            });
        });
    </script>
@endsection
