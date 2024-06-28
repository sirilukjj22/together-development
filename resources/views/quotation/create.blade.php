@extends('layouts.masterLayout')
<style>
.image-container {
    display: flex;
    flex-direction: row;
    align-items: center;
    text-align: left;
}
.image-container img.logo {
    width: 15%; /* กำหนดขนาดคงที่ */
    height: auto;
    margin-right: 20px;
}

.image-container .info {
    margin-top: 0;
}

.image-container .info p {
    margin: 5px 0;
}

.image-container .titleh1 {
    font-size: 1.2em;
    font-weight: bold;
    margin-bottom: 10px;
}
.titleQuotation{
    display:flex;
    justify-content:center;
    align-items:center;
}
.select2{
    margin: 4px 0;
    border: 1px solid #ffffff;
    border-radius: 4px;
    box-sizing: border-box;
}
.calendar-icon {
    background: #fff no-repeat center center;
    vertical-align: middle;
    margin-right: 5px;
    transition: background-color 0.3s, transform 0.3s;/* ระยะห่างจากข้อความ */
}
.calendar-icon:hover {
    background: #fff ;
    transform: scale(1.1);
}
.calendar-icon:active {
    transform: scale(0.9);
}
@media (max-width: 768px) {
    .image-container {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .image-container img.logo {
        margin-bottom: 20px;
        width: 50%;
    }
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
                    <form id="myForm" action="{{route('Quotation.save')}}" method="POST">
                    @csrf
                        <div class="row">
                            <div class="col-lg-8 col-md-12 col-sm-12 image-container">
                                <img src="{{ asset('assets2/images/logo_crop.png') }}" alt="Together Resort Logo" class="logo"/>
                                <div class="info">
                                    <p class="titleh1">Together Resort Limited Partnership</p>
                                    <p>168 Moo 2 Kaengkrachan Phetchaburi 76170</p>
                                    <p>Tel : 032-708-888, 098-393-944-4 Fax :</p>
                                    <p>Email : reservation@together-resort.com Website : www.together-resort.com</p>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <div class="row">
                                    <b class="titleQuotation" style="font-size: 24px;color:rgba(45, 127, 123, 1);">Quotation </b>
                                    <span class="titleQuotation">{{$Quotation_ID}}</span>
                                    <input type="hidden" id="Quotation_ID" name="Quotation_ID" value="{{$Quotation_ID}}">
                                    <div id="reportrange1" style="background: #fff; cursor: pointer; padding: 5px 10px; width: 100%;" >
                                        <div class="col-12 col-md-12 col-sm-12">
                                            <div class="row">
                                                <div class="col-6"style="display:flex; justify-content:right; align-items:center;">
                                                    <span>Issue Date:</span>
                                                </div>
                                                <div class="col-6">
                                                    <input type="text" id="datestart" class="form-control" name="IssueDate" style="text-align: left;"readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-12 col-sm-12 mt-2">
                                            <div class="row">
                                                <div class="col-6"style="display:flex; justify-content:right; align-items:center;">
                                                    <span>Expiration Date:</span>
                                                </div>
                                                <div class="col-lg-6">
                                                    <input type="text" id="dateex" class="form-control" name="Expiration" style="text-align: left;"readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="Quotation_ID" name="Quotation_ID" value="{{$Quotation_ID}}">
                        <div class="row mt-5">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label class="labelcontact" for="">Customer Company</label>
                                <select name="Company" id="Company" class="select2" onchange="companyContact()" required>
                                    <option value=""></option>
                                    @foreach($Company as $item)
                                        <option value="{{ $item->Profile_ID }}">{{ $item->Company_Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label class="labelcontact" for="">Customer Contact</label>
                                <select name="Company_Contact" id="Company_Contact" class="select2"required>
                                    <option value=""></option>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 mt-2">
                                <button style="float: right;" type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ route('Company.index') }}'">
                                    <i class="fa fa-plus"></i> เพิ่มบริษัท</button>
                            </div>
                        </div>
                        <hr class="mt-3 my-3" style="border: 1px solid #000">
                        <div class="row mt-2">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="chekin">Check In / Out Date </label>
                                <div class="form-control" id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #eeeeee; width: 100%">
                                    <i class="fa fa-calendar" id="reportrange2"></i>&nbsp;
                                    <span style="width: 100%"></span>
                                    <input type="hidden" name="Checkin" id="Checkin">
                                    <input type="hidden" name="Checkout"id="Checkout">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="">จำนวน</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="Day" placeholder="จำนวนวัน">
                                    <span class="input-group-text">Day</span>
                                    <input type="text" class="form-control" name="Night" placeholder="จำนวนคืน">
                                    <span class="input-group-text">Night</span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="">จำนวนผู้เข้าพัก (ผู้ใหญ่/เด็ก)</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="Adult"  placeholder="จำนวนผู้ใหญ่">
                                    <span class="input-group-text">ผู้ใหญ่</span>
                                    <input type="text" class="form-control" name="Children" placeholder="จำนวนเด็ก">
                                    <span class="input-group-text">เด็ก</span>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label  for="">Max discount </label> <label style="color: #dc3545">(Your permission has max discount 10.00 %)</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="Max_discount" placeholder="ส่วนลดคิดเป็น %" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label  for="">Company Rate Code</label>
                                <div class="input-group">
                                    <span class="input-group-text">DC</span>
                                    <input type="text" class="form-control" name="Company_Rate_Code" aria-label="Amount (to the nearest dollar)">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label class="Freelancer_member" for="">Freelance Affiliate</label>
                                <select name="Freelancer_member" id="Freelancer_member" class="select2" required>
                                    <option value=""></option>
                                    @foreach($Freelancer_member as $item)
                                        <option value="{{ $item->Profile_ID }}">{{ $item->First_name }}{{ $item->Last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label  for="">Company Commission Rate Code</label>
                                <div class="input-group">
                                    <input type="text" class="form-control"  name="Company_Commission_Rate_Code" >
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label  for="">Place</label>
                                <input type="text" class="form-control " name="place" aria-label="Amount (to the nearest dollar)" required>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label  for="">Event Format</label>
                                <select name="Mevent" id="Mevent" class="select2" required>
                                    <option value=""></option>
                                    @foreach($Mevent as $item)
                                        <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <label  for="">Vat Type</label>
                                <select name="Vat_Type" id="Vat_Type" class="select2" >
                                    <option value="VAT_IN">VAT IN</option>
                                    <option value="VAT_OUT">VAT OUT</option>
                                </select>
                            </div>
                            <div class="col-lg-12 col-md-6 col-sm-12 d-flex justify-content-center mt-3">
                                <button type="submit" class="btn btn-color-green lift btn_modal" >
                                ต่อไป
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" defer></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Please select an option"
        });
    });
</script>
<script type="text/javascript">
    $(function() {
        var start = moment();
        var end = moment().add(7, 'days');
        function cb(start, end) {
            $('#reportrange span').html(start.format('D/MM/YYYY') + ' - ' + end.format('D/MM/YYYY'));
            $('#Checkin').val(start.format('D/MM/YYYY'));
            $('#Checkout').val(end.format('D/MM/YYYY'));
        }
        $('#reportrange2').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                '7 Days': [moment(), moment().add(7, 'days')],
                '15 Days': [moment(), moment().add(15, 'days')],
                '30 Days': [moment(), moment().add(30, 'days')],
            }
        },
        cb);
        cb(start, end);
    });
</script>
<script type="text/javascript">
    $(function() {
        var start = moment();
        var end = moment().add(7, 'days');
        function cb(start, end) {
            $('#datestart').val(start.format('DD/MM/Y'));
            $('#dateex').val(end.format('DD/MM/Y'));
        }
        $('#reportrange1').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                '7 Days': [moment(), moment().add(7, 'days')],
                '15 Days': [moment(), moment().add(15, 'days')],
                '30 Days': [moment(), moment().add(30, 'days')],
            }
        },
        cb);
        cb(start, end);
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
    function toggleDateInput() {
        var selectElement = document.getElementById('Select_a_date');
        var dateInput = document.getElementById('contract_rate_start_date');
        if (selectElement.value === 'Yes_date') {
            dateInput.removeAttribute('readonly');
        } else {
            dateInput.setAttribute('readonly', true);
        }
    }
    function companyContact() {
        var companyID = $('#Company').val();
        console.log(companyID);
        jQuery.ajax({
            type: "GET",
            url: "{!! url('/Quotation/create/company/Contact/" + companyID + "') !!}",
            datatype: "JSON",
            async: false,
            success: function(result) {
                jQuery('#Company_Contact').children().remove().end();
                $('#Company_Contact').append(new Option('', ''));
                jQuery.each(result.data, function(key, value) {
                    var optionText = `${value.First_name} ${value.Last_name}`; // รวมชื่อกับนามสกุล
                    var optionValue = value.Profile_ID; // ใช้ Profile_ID เป็นค่า
                    var option = new Option(optionText, optionValue);
                    $('#Company_Contact').append(option);
                    console.log(option);
                });
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed: ", status, error);
            }
        });
    }
</script>
@endsection
