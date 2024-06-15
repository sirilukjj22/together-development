@extends('layouts.test')
@section('content')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<style>
    .container {
        margin-top: 40px;
        background-color: white;
        padding: 2% 2%;
        overflow-x: hidden;
    }
    .btn-animate-submit {
        display: inline-block;
        padding: 10px 20px;
        font-size: 16px;
        color: white;
        background-color: #2D7F7B;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.3s;
    }

    .btn-animate-submit:hover {
        background-color: #2D7F7B;
        transform: scale(1.1);
    }

    .btn-animate-submit:active {
        transform: scale(0.9);
    }
    table {
        width: 100%;
    }

    input[type=text],
    select {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    .input-group-text {
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    input[type=tel],
    select {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input[type=tel1],
    select {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input[type="date"] {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        outline: none;
        font-size: 16px;
        background-color: #f8f8f8;
        /* เพิ่มสีพื้นหลัง */
    }

    input[type="number"] {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    .button-guest-end button {
        background-color: #dc3545;
        color: white;
        border-color: #9a9a9a;
        border-style: solid;
        width: 50%;
        border-width: 1px;
        border-radius: 8px;
        margin-Top: 10px;
        text-align: center;
        float: right;
    }
    .textarea {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    /* สไตล์เพิ่มเติมตามที่ต้องการ */
    .row {
        margin-bottom: 5px;
    }

    .row label {
        margin: 0;
        margin-bottom: 5px;
    }

    .row input {
        margin: 0;
        margin-bottom: 2%;
    }

    .form-select {
        height: 50px;
    }

    .row select {
        margin: 0;
        margin-bottom: 2%;
    }

    .row .select2-selection {
        margin: 0 !important;
        margin-bottom: 2% !important;
    }

    .row .select2-selection .select2-selection__arrow {
        margin: 10px !important;
    }
    .label2{
        padding-top: 5px;
        float: left;
    }
    .datestyle {
        height: 50px !important;
        background-color: white;
    }

    .select2-container {
        width: 100% !important;
    }

    textarea {
        resize: none;
    }

    .email {
        border-radius: 8px;
        border: 1px solid #aaa;
        height: 50px;

    }
    .titleh2{
        font-size: 26px;
    }
    .titleh1 {
        font-size: 24px;
    }
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
    .input-group-text-Adult {
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        width: 80px;
        height: 50px;
        border: 1px solid #ccc;
        background-color: #e8e8e8; /* เพิ่มเซมิโคลอนที่นี่ */
        border-radius: 4px;
    }

    .input-group-text.custom-span-1 {
        width: 15px; /* ความกว้างที่ต้องการ */
        height: 50px; /* ความสูงที่ต้องการ */
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        background-color: #e8e8e8;
    }

    .datestyle {
        height: 50px !important;
        background-color: white;
    }
    .quotation-container {
        display: flex;
        flex-direction: row;
        align-items: center;
        text-align: center;  /* ปรับความสูงตามที่ต้องการ */
    }

    .quotation-number {
        font-size: 36px;
        margin: 0;
        margin-right: 5px;
        display: table-cell;
        vertical-align: middle;
        color:#109699;
    }
    .quotation-id {
        font-size: 18px;
        margin: 0;
        margin-right: 5px;
        display: table-cell;
        vertical-align: middle;
    }
    @media (max-width: 768px) {
        .quotation-container {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
    }
    .label {
        font-weight: bold;
    }
    .ml-2 {
        margin-left: 10px;
    }
    .ml-4 {
        margin-left: 20px;
    }
    .ml-5{
        margin-left: 90px;
    }
    .table-borderless {
            border-collapse: collapse; /* หรือ separate */
            border: none;
        }
        .table-borderless th,
        .table-borderless td,
        .table-borderless thead,
        .table-borderless tbody,
        .table-borderless tr {
            border: none !important;
        }
        .styled-hr {
            border: none; /* เอาขอบออก */
            border: 3px solid #109699; /* กำหนดระยะห่างด้านล่าง */
        }
        .com {
        display: inline-block;  /* ทำให้ border-bottom มีความยาวเท่ากับข้อความ */
            border-bottom: 2px solid #2D7F7B;  /* กำหนดเส้นใต้ */
            padding-bottom: 5px;

    }
    .button-return {
    align-items: center;
    appearance: none;
    background-color: #6b6b6b;
    border-radius: 8px;
    border-style: none;
    box-shadow: rgba(0, 0, 0, 0.2) 0 3px 5px -1px,
      rgba(0, 0, 0, 0.14) 0 6px 10px 0, rgba(0, 0, 0, 0.12) 0 1px 18px 0;
    box-sizing: border-box;
    color: #ffffff;
    cursor: pointer;
    display: inline-flex;
    fill: currentcolor;
    font-size: 14px;
    font-weight: 500;
    height: 40px;
    justify-content: center;
    letter-spacing: 0.25px;
    line-height: normal;
    max-width: 100%;
    overflow: visible;
    padding: 2px 24px;
    position: relative;
    text-align: center;
    text-transform: none;
    transition: box-shadow 280ms cubic-bezier(0.4, 0, 0.2, 1),
      opacity 15ms linear 30ms, transform 270ms cubic-bezier(0, 0, 0.2, 1) 0ms;
    touch-action: manipulation;
    width: auto;
    will-change: transform, opacity;
    margin-left: 5px;
  }

  .button-return:hover {
    background-color: #ffffff !important;
    color: #000000;
    transform: scale(1.1);
  }
</style>

<form id="myForm" action="{{url('/Quotation/company/document/sheet/'.$Quotation->id)}}" method="POST"enctype="multipart/form-data">
    {!! csrf_field() !!}
    <div class="container-fluid border rounded-3 p-5 mt-3 bg-white" style="width: 98%;">
        <div class=" col-12">
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
            </div>
        </div>
        <div class="col-12 mt-5" >
            <div class="row">
                <div class="col-6 col-md-6 col-sm-12">
                    <table class="table table-borderless" >
                        <tbody>
                            <tr >
                                <td  scope="row"style="text-align:left;width: 25% " ><samp style="font-weight: bold;">Subject :</samp></td>
                                <td style="text-align:left;"><samp>ขอเสนอราคาค่าที่พัก อาหาร สัมมนา และ กิจกรรม</samp></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <input type="hidden" id="Quotation_ID" name="Quotation_ID" value="{{$Quotation->Quotation_ID}}">
                <div class="col-6 col-md-6 col-sm-12">
                    <table class="table table-borderless" >
                        <tbody>
                            <tr><th style="width: 50%"></th>
                                <td  scope="row"style="text-align:left;width: 10% " ><samp style="font-weight: bold;">Date :</samp></td>
                                <td style="text-align:left;">{{ $date->format('d/m/Y H:i:s') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="styled-hr"></div>
            <div class="row">
                <div class="col-6 col-md-6 col-sm-12">
                    <table class="table table-borderless" >
                        <tbody>
                            <tr>
                                <td  scope="row"style="text-align:left;width: 35% " class="ml-2"><samp class="ml-2 com" style="font-weight: bold;font-size: 18px;">Company Information</samp></td>
                            </tr>
                            <tr>
                                <td  scope="row"style="text-align:left;"><samp class="ml-4 " style="font-weight: bold;">Company Name :</samp></td>
                                <td style="text-align:left;">
                                    @if ($Company_type->name_th === 'บริษัทจำกัด')
                                    <p id="Company_name" name="Company_name" style="display: inline-block;">บริษัท {{ $Company_ID->Company_Name }} จำกัด</p>
                                    @elseif ($Company_type->name_th === 'บริษัทมหาชนจำกัด')
                                        <p id="Company_name" name="Company_name" style="display: inline-block;">บริษัท {{ $Company_ID->Company_Name }} จำกัด (มหาชน)</p>
                                    @elseif ($Company_type->name_th === 'ห้างหุ้นส่วนจำกัด')
                                        <p id="Company_name" name="Company_name" style="display: inline-block;">ห้างหุ้นส่วนจำกัด {{ $Company_ID->Company_Name }}</p>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td  scope="row" style="text-align:left;"class="ml-4"><samp class="ml-4" style="font-weight: bold;">Company Address :</samp></td>
                                <td style="text-align:left;">
                                    <p id="Company_Address" name="Company_Address" style="display: inline-block;">{{$Company_ID->Address}}</p>
                                    <p id="Tambon" name="Tambon" style="display: inline-block;" >{{'ตำบล' . $TambonID->name_th}}</p>
                                    <p id="Amphures" name="Amphures" style="display: inline-block;">{{'อำเภอ' .$amphuresID->name_th}}</p>
                                    <p id="City" name="City" style="display: inline-block;">{{'จังหวัด' .$provinceNames->name_th}}</p>
                                    <p id="Zip_Code" name="Zip_Code" style="display: inline-block;">{{$TambonID->Zip_Code}}</p>
                                </td>
                            </tr>
                            <tr>
                                <td  scope="row" style="text-align:left;"class="ml-4"><samp class="ml-4" style="font-weight: bold;">Company Email :</samp></td>
                                <td style="text-align:left;">
                                    <p id="Company_Email" name="Company_Email" style="display: inline-block;">{{$Company_ID->Company_Email}}</p>
                                </td>
                            </tr>
                            <tr>
                                <td  scope="row" style="text-align:left;"class="ml-4"><samp class="ml-4" style="font-weight: bold;">Company Number :</samp></td>
                                <td style="text-align:left;">
                                    <p id="Company_Number" name="Company_Number" style="display: inline-block;">{{ substr($company_phone->Phone_number, 0, 3) }}-{{ substr($company_phone->Phone_number, 3, 3) }}-{{ substr($company_phone->Phone_number, 6) }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td scope="row" style="text-align:left;"class="ml-4"><samp class="ml-4" style="font-weight: bold;">Company Fax :</samp></td>
                                <td style="text-align:left;">
                                    <p id="Company_Fax" name="Company_Fax" style="display: inline-block;">{{$company_fax->Fax_number}}</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-6 col-md-6 col-sm-12">
                    <table class="table table-borderless" >
                        <tbody>
                            <tr><td style="text-align:left;width: 35% "><br></td></tr>
                            <tr><td><br></td></tr>
                            <tr>
                                <td  scope="row"><samp class="com" style="font-weight: bold;font-size: 18px;">Personal Information</samp></td>
                            </tr>
                            <tr>
                                <td  scope="row"style="text-align:left;"><samp class="ml-4" style="font-weight: bold;">Name  :</samp></td>
                                <td style="text-align:left;">
                                    <p id="Company_contact" name="Company_contact" style="display: inline-block;">คุณ{{$Contact_name->First_name}} {{$Contact_name->Last_name}}</p>
                                </td>
                            </tr>
                            <tr>
                                <td  scope="row" style="text-align:left;"><samp class="ml-4" style="font-weight: bold;">Email  :</samp></td>
                                <td style="text-align:left;">
                                    <p id="Contact_Email" name="Contact_Email" style="display: inline-block;">{{$Contact_name->Email}}</p>
                                </td>
                            </tr>
                            <tr>
                                <td  scope="row" style="text-align:left;"><samp class="ml-4" style="font-weight: bold;">Contact Number : </samp></td>
                                <td style="text-align:left;">
                                    <p id="Contact_Phone" name="Contact_Phone" style="display: inline-block;">{{ substr($Contact_phone->Phone_number, 0, 3) }}-{{ substr($Contact_phone->Phone_number, 3, 3) }}-{{ substr($Contact_phone->Phone_number, 6) }}</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <span> โรงแรม ทูเก็ตเตอร์ รีสอร์ท แก่งกระจาน ขอแสดงความขอบคุณที่ท่านเลือก โรงแรม ทูเก็ตเตอร์ รีสอร์ท แก่งกระจาน</span><br>
        <span>ให้ได้รับใช้ท่านในการสำรองห้องพักและการจัดงาน ทางโรงแรมขอเสนอราคาพิเศษ ให้กับหน่วยงานของท่าน ดังนี้</span>
        <div class="col-8 ">
            <table class="table table-borderless" >
                <tbody>
                    <tr><td style="text-align:left;width: 20% ">รายละเอียดการจัดงาน</td></tr>
                    <tr>
                        <td  scope="row"style="text-align:left;"><samp>วันที่</samp></td>
                        <td style="text-align:left;">
                            <p id="Company_contact" name="Company_contact" style="display: inline-block;">{{$Quotation->checkin}} - {{$Quotation->checkout}} ( {{$Quotation->day}} วัน {{$Quotation->night}} คืน)</p>
                        </td>
                    </tr>
                    <tr>
                        <td  scope="row" style="text-align:left;"><samp>สถานที่</samp></td>
                        <td style="text-align:left;">
                            <p id="Contact_Email" name="Contact_Email" style="display: inline-block;">โรงแรม ทูเก็ตเตอร์ รีสอร์ท แก่งกระจาน</p>
                        </td>
                    </tr>
                    <tr>
                        <td  scope="row" style="text-align:left;"><samp >รูปแบบการจัดงาน </samp></td>
                        <td style="text-align:left;">
                            <p id="Contact_Phone" name="Contact_Phone" style="display: inline-block;">{{$eventformat->name_th}}</p>
                        </td>
                    </tr>
                    <tr>
                        <td  scope="row" style="text-align:left;"><samp>จำนวน</samp></td>
                        <td style="text-align:left;">
                            <p id="Contact_Phone" name="Contact_Phone" style="display: inline-block;">{{ $Quotation->adult + $Quotation->children }} ท่าน</p>
                        </td>
                    </tr>
                    <tr>
                        <td  scope="row" style="text-align:left;"><samp style="font-weight: bold;">Remark :</samp></td>
                        <td style="text-align:left;">
                            <p id="Contact_Phone" name="Contact_Phone" style="display: inline-block;">เอกสารฉบับนี้ เป็นเพียงการเสนอราคาเท่านั้นยังมิได้ทำการจองแต่อย่างใดทั้งสิ้น</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <samp>การจองห้องพัก</samp>
            <div class="row">
                @if(isset($Reservation_show) && $Reservation_show->name_th !== null)
                    <div class="col-8 ml-5">
                        <textarea id="Reservation" name="Reservation">{{ $Reservation_show->name_th }}</textarea>
                    </div>
                @else
                    <div class="col-8 ml-5">
                        <textarea id="Reservation" name="Reservation"></textarea>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-12">
            <samp>เงื่อนไขการจ่ายเงิน</samp>
            <div class="row">
                <div class="col-8 ml-5">
                    @if(isset($Paymentterms) && $Paymentterms->name_th !== null)
                    <div class="col-12 ">
                        <textarea id="summernote" name="Paymentterms">
                            {!! $Paymentterms->name_th !!}
                        </textarea>
                    </div>
                    @else
                        <div class="col-12 ">
                            <textarea id="summernote" name="Paymentterms"></textarea>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-12">
            <samp>หมายเหตุ</samp>
            <div class="row">
                @if(isset($note) && $note->name_th !== null)
                    <div class="col-8 ml-5">
                        <textarea id="note" name="note">
                            {!! $note->name_th !!}
                        </textarea>
                    </div>
                @else
                    <div class="col-8 ml-5">
                        <textarea id="note" name="note"></textarea>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="container-fluid border rounded-3 p-5 mt-3 bg-white" style="width: 98%;">
        <div class="col-12">
            <samp>การยกเลิกและการเปลี่ยนแปลงการจอง</samp>
            <div class="row">
                @if(isset($Cancellations) && $Cancellations->name_th !== null)
                    <div class="col-8 ml-5">
                        <textarea id="Cancellations" name="Cancellations">
                            {!! $Cancellations->name_th !!}
                        </textarea>
                    </div>
                @else
                    <div class="col-8 ml-5">
                        <textarea id="Cancellations" name="Cancellations"></textarea>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-12">
            <samp>อภินันทนาการทางรีสอร์ท</samp>
            <div class="row">
                @if(isset($Complimentary) && $Complimentary->name_th !== null)
                    <div class="col-8 ml-5">
                        <textarea id="Complimentary" name="Complimentary">
                            {!! $Complimentary->name_th !!}
                        </textarea>
                    </div>
                @else
                    <div class="col-8 ml-5">
                        <textarea id="Complimentary" name="Complimentary"></textarea>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-12">
            <samp>ทางรีสอร์ทขอสงวนสิทธิ์แก่ผู้ใช้บริการดังนี</samp>
            <div class="row">
                @if(isset($All_rights_reserved) && $All_rights_reserved->name_th !== null)
                    <div class="col-8 ml-5">
                        <textarea id="All_rights_reserved" name="All_rights_reserved">
                            {!! $All_rights_reserved->name_th !!}
                        </textarea>
                    </div>
                @else
                    <div class="col-8 ml-5">
                        <textarea id="All_rights_reserved" name="All_rights_reserved"></textarea>
                    </div>
                @endif
            </div>
            <button type="button" class="button-return" target="_bank" onclick="window.location.href='{{ url('/Quotation/Quotation/cover/document/PDF/'.$Quotation->id) }}'" >ดู PDF</button>
        </div>
        <div class="styled-hr mt-3"></div>
        <div class="col-12 row mt-5">
            <div class="col-4"></div>
            <div class="col-4 "  style="display:flex; justify-content:center; align-items:center;">
                <button type="button" class="button-return" onclick="window.location.href='{{ route('Quotation.index') }}'" >{{ __('ย้อนกลับ') }}</button>
                <button type="submit" class="button-10" style="background-color: #109699;">บันทึกใบหน้า</button>
            </div>
            <div class="col-4"></div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    $('#summernote').summernote();
    $('#Reservation').summernote();
    $('#note').summernote();
    $('#Cancellations').summernote();
    $('#Complimentary').summernote();
    $('#All_rights_reserved').summernote();
});
</script>
@endsection
