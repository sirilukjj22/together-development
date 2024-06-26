@extends('layouts.masterLayout')
<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<style>
    .center-content {
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
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
    .datestyle {
        height: 50px !important;
        background-color: white;
    }
    .titleh2{
        font-size: 26px;
    }
    .titleh1 {
        font-size: 24px;
    }
    textarea {
        resize: none;
    }
     .datestyle {
        height: 50px !important;
        background-color: white;
    }

    .edit-button {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: rgb(0, 0, 0);
        border: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.164);
        cursor: pointer;
        transition-duration: 0.3s;
        overflow: hidden;
        position: relative;
        text-decoration: none !important;
    }

    .edit-svgIcon {
        width: 17px;
        transition-duration: 0.3s;
    }

    .edit-svgIcon path {
        fill: white;
    }

    .edit-button:hover {
        width: 120px;
        border-radius: 50px;
        transition-duration: 0.3s;
        background-color: rgb(45, 127, 123, 1);
        align-items: center;
    }

    .edit-button:hover .edit-svgIcon {
        width: 20px;
        transition-duration: 0.3s;
        transform: translateY(60%);
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        transform: rotate(360deg);
    }

    .edit-button::before {
        display: none;
        content: "Edit";
        color: white;
        transition-duration: 0.3s;
        font-size: 2px;
    }

    .edit-button:hover::before {
        display: block;
        padding-right: 10px;
        font-size: 13px;
        opacity: 1;
        transform: translateY(0px);
        transition-duration: 0.3s;
    }
</style>

@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to Template A1.</small>
                <h1 class="h4 mt-1">Template A1 (แบบฟอร์ม A1)</h1>
            </div>
        </div>
    </div>
@endsection


@section('content')
<div class="container">
    <div  class="center-content">
        <div class="row">
            <h3>Setup template</h3>
            <span style="color: #959292">for Together Resort Limited Partnership</span>
            <div class="center-content mt-2">
                <form id="myForm" action="{{route('Template.save')}}" method="POST">
                    {!! csrf_field() !!}
                    <span style="font-size: 14px;margin-right: 60px;">Choose Template</span>
                    <div class="row center-content mt-2 ">
                        <select class="form-select" name="Template" style="width: 100%">
                            <option value="A1">Template A1</option>
                            {{-- <option value="2">Template A2</option>
                            <option value="3">Template A3</option> --}}
                        </select>
                    </div>
                    <button type="submit" class="btn btn-color-green lift text-white mt-3" >confirm</button>
                </form>
            </div>
        </div>
    </div>
    <button class="edit-button" style="float: right" onclick="editTemplate()">
        <svg class="edit-svgIcon" viewBox="0 0 512 512">
            <path d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"></path>
        </svg>
    </button>
</div>
<div style="display:block;" id="SHOW">
    <div class="container mt-3">
        <div class="row clearfix">
            <div class="col-sm-12 col-12">
                <div class="card p-4 mb-4">
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
                                            <td  scope="row"style="text-align:left;width: 20% " ><samp style="font-weight: bold;">Subject :</samp></td>
                                            <td style="text-align:left;"><samp>ขอเสนอราคาค่าที่พัก อาหาร สัมมนา และ กิจกรรม</samp></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <input type="hidden" id="Quotation_ID" name="Quotation_ID" value="">
                            <div class="col-6 col-md-6 col-sm-12">
                                <table class="table table-borderless" >
                                    <tbody>
                                        <tr><th style="width: 50%"></th>
                                            <td  scope="row"style="text-align:left;width: 15% " ><samp style="font-weight: bold;">Date :</samp></td>
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
                                            <td  scope="row"style="text-align:left;width: 40% " class="ml-2"><samp class="ml-2 com" style="font-weight: bold;font-size: 18px;">Company Information</samp></td>
                                        </tr>
                                        <tr>
                                            <td  scope="row"style="text-align:left;"><samp class="ml-4 " style="font-weight: bold;">Company Name :</samp></td>
                                            <td style="text-align:left;">
                                                <p id="Company_name" name="Company_name" style="display: inline-block;">บริษัท โจฮ์นัน เอฟ เทค (ประเทศไทย) จำกัด</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td  scope="row" style="text-align:left;"class="ml-4"><samp class="ml-4" style="font-weight: bold;">Company Address :</samp></td>
                                            <td style="text-align:left;">
                                                69 หมู่ 4 เขตประกอบการอุตสาหกรรมเหมราช สระบุรี ตำบลบัวลอย อำเภอหนองแค จังหวัดสระบุรี 18140
                                            </td>
                                        </tr>
                                        <tr>
                                            <td  scope="row" style="text-align:left;"class="ml-4"><samp class="ml-4" style="font-weight: bold;">Company Email :</samp></td>
                                            <td style="text-align:left;">
                                                cardmmory@gmail.com
                                            </td>
                                        </tr>
                                        <tr>
                                            <td  scope="row" style="text-align:left;"class="ml-4"><samp class="ml-4" style="font-weight: bold;">Company Number :</samp></td>
                                            <td style="text-align:left;">
                                                086-219-8292
                                            </td>
                                        </tr>
                                        <tr>
                                            <td scope="row" style="text-align:left;"class="ml-4"><samp class="ml-4" style="font-weight: bold;">Company Fax :</samp></td>
                                            <td style="text-align:left;">
                                                -
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
                                            <td  scope="row"><samp class="com" style="font-weight: bold;font-size: 18px;">Contact Information</samp></td>
                                        </tr>
                                        <tr>
                                            <td  scope="row"style="text-align:left;"><samp class="ml-4" style="font-weight: bold;">Contact Name  :</samp></td>
                                            <td style="text-align:left;">
                                                คุณกุ้ง
                                            </td>
                                        </tr>
                                        <tr>
                                            <td  scope="row" style="text-align:left;"><samp class="ml-4" style="font-weight: bold;">Contact Email  :</samp></td>
                                            <td style="text-align:left;">
                                                cardmmory@gmail.com
                                            </td>
                                        </tr>
                                        <tr>
                                            <td  scope="row" style="text-align:left;"><samp class="ml-4" style="font-weight: bold;">Contact Number : </samp></td>
                                            <td style="text-align:left;">
                                                086-219-8292
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
                                        12/02/2024 - 22/02/2024 (1 วัน 0 คืน)
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
                                        สัมมนา และ จัดเลี้ยง
                                    </td>
                                </tr>
                                <tr>
                                    <td  scope="row" style="text-align:left;"><samp>จำนวน</samp></td>
                                    <td style="text-align:left;">
                                        2 ท่าน
                                    </td>
                                </tr>
                                <tr>
                                    <td  scope="row" style="text-align:left;"><samp style="font-weight: bold;">Remark :</samp></td>
                                    <td style="text-align:left;">
                                        <p  style="display: inline-block;">เอกสารฉบับนี้ เป็นเพียงการเสนอราคาเท่านั้นยังมิได้ทำการจองแต่อย่างใดทั้งสิ้น</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-12">
                        <samp>การจองห้องพัก</samp>
                        <div class="row">
                            <div class="col-8 ml-5">
                                {!! $Reservation_show->name_th !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <samp>เงื่อนไขการจ่ายเงิน</samp>
                        <div class="row">
                            <div class="col-8 ml-5">
                                {!! $Paymentterms->name_th !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <samp>หมายเหตุ</samp>
                        <div class="row">
                            <div class="col-8 ml-5">
                                {!! $note->name_th !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-sm-12 col-12">
                <div class="card p-4 mb-4">
                    <div style="display:block;" id="SHOW">
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
                                                <td  scope="row"style="text-align:left;width: 20% " ><samp style="font-weight: bold;">Subject :</samp></td>
                                                <td style="text-align:left;"><samp>ขอเสนอราคาค่าที่พัก อาหาร สัมมนา และ กิจกรรม</samp></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <input type="hidden" id="Quotation_ID" name="Quotation_ID" value="">
                                <div class="col-6 col-md-6 col-sm-12">
                                    <table class="table table-borderless" >
                                        <tbody>
                                            <tr><th style="width: 50%"></th>
                                                <td  scope="row"style="text-align:left;width: 15% " ><samp style="font-weight: bold;">Date :</samp></td>
                                                <td style="text-align:left;">{{ $date->format('d/m/Y H:i:s') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="styled-hr"></div>
                            <div class="col-12 mt-3">
                                <samp>การยกเลิกและการเปลี่ยนแปลงการจอง</samp>
                                <div class="row">
                                    <div class="col-8 ml-5">
                                        {!! $Cancellations->name_th !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <samp>อภินันทนาการทางรีสอร์ท</samp>
                                <div class="row">
                                    <div class="col-8 ml-5">
                                        {!! $Complimentary->name_th !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <samp>ทางรีสอร์ทขอสงวนสิทธิ์แก่ผู้ใช้บริการดังนี</samp>
                                <div class="row">
                                    <div class="col-8 ml-5">
                                        {!! $All_rights_reserved->name_th !!}
                                    </div>
                                </div>
                            </div>
                            <div class="styled-hr mt-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="display:none;" id="edit">
    <form action="{{route('Template.savesheet')}}" method="POST" enctype="multipart/form-data">
    @csrf
        <div class="container mt-3">
            <div class="row clearfix">
                <div class="col-sm-12 col-12">
                    <div class="card p-4 mb-4">
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
                                                <td  scope="row"style="text-align:left;width: 20% " ><samp style="font-weight: bold;">Subject :</samp></td>
                                                <td style="text-align:left;"><samp>ขอเสนอราคาค่าที่พัก อาหาร สัมมนา และ กิจกรรม</samp></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <input type="hidden" id="Quotation_ID" name="Quotation_ID" value="">
                                <div class="col-6 col-md-6 col-sm-12">
                                    <table class="table table-borderless" >
                                        <tbody>
                                            <tr><th style="width: 50%"></th>
                                                <td  scope="row"style="text-align:left;width: 15%" ><samp style="font-weight: bold;">Date :</samp></td>
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
                                                <td  scope="row"style="text-align:left;width: 40% " class="ml-2"><samp class="ml-2 com" style="font-weight: bold;font-size: 18px;">Company Information</samp></td>
                                            </tr>
                                            <tr>
                                                <td  scope="row"style="text-align:left;"><samp class="ml-4 " style="font-weight: bold;">Company Name :</samp></td>
                                                <td style="text-align:left;">
                                                    <p id="Company_name" name="Company_name" style="display: inline-block;">บริษัท โจฮ์นัน เอฟ เทค (ประเทศไทย) จำกัด</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td  scope="row" style="text-align:left;"class="ml-4"><samp class="ml-4" style="font-weight: bold;">Company Address :</samp></td>
                                                <td style="text-align:left;">
                                                    69 หมู่ 4 เขตประกอบการอุตสาหกรรมเหมราช สระบุรี ตำบลบัวลอย อำเภอหนองแค จังหวัดสระบุรี 18140
                                                </td>
                                            </tr>
                                            <tr>
                                                <td  scope="row" style="text-align:left;"class="ml-4"><samp class="ml-4" style="font-weight: bold;">Company Email :</samp></td>
                                                <td style="text-align:left;">
                                                    cardmmory@gmail.com
                                                </td>
                                            </tr>
                                            <tr>
                                                <td  scope="row" style="text-align:left;"class="ml-4"><samp class="ml-4" style="font-weight: bold;">Company Number :</samp></td>
                                                <td style="text-align:left;">
                                                    086-219-8292
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row" style="text-align:left;"class="ml-4"><samp class="ml-4" style="font-weight: bold;">Company Fax :</samp></td>
                                                <td style="text-align:left;">
                                                    -
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
                                                <td  scope="row"><samp class="com" style="font-weight: bold;font-size: 18px;">Contact Information</samp></td>
                                            </tr>
                                            <tr>
                                                <td  scope="row"style="text-align:left;"><samp class="ml-4" style="font-weight: bold;">Contact Name  :</samp></td>
                                                <td style="text-align:left;">
                                                    คุณกุ้ง
                                                </td>
                                            </tr>
                                            <tr>
                                                <td  scope="row" style="text-align:left;"><samp class="ml-4" style="font-weight: bold;">Contact Email  :</samp></td>
                                                <td style="text-align:left;">
                                                    cardmmory@gmail.com
                                                </td>
                                            </tr>
                                            <tr>
                                                <td  scope="row" style="text-align:left;"><samp class="ml-4" style="font-weight: bold;">Contact Number : </samp></td>
                                                <td style="text-align:left;">
                                                    086-219-8292
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
                                            12/02/2024 - 22/02/2024 (1 วัน 0 คืน)
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
                                            สัมมนา และ จัดเลี้ยง
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  scope="row" style="text-align:left;"><samp>จำนวน</samp></td>
                                        <td style="text-align:left;">
                                            2 ท่าน
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  scope="row" style="text-align:left;"><samp style="font-weight: bold;">Remark :</samp></td>
                                        <td style="text-align:left;">
                                            <p  style="display: inline-block;">เอกสารฉบับนี้ เป็นเพียงการเสนอราคาเท่านั้นยังมิได้ทำการจองแต่อย่างใดทั้งสิ้น</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12 mt-2">
                            <samp>การจองห้องพัก</samp>
                            <div class="row mt-2">
                                <div class="col-10 ml-5">
                                    <textarea id="Reservation" name="Reservation">
                                        {!! $Reservation_show->name_th !!}
                                    </textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <samp>เงื่อนไขการจ่ายเงิน</samp>
                            <div class="row mt-2">
                                <div class="col-10 ml-5">
                                    <textarea id="Paymentterms" name="Paymentterms">
                                        {!! $Paymentterms->name_th !!}
                                    </textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <samp>หมายเหตุ</samp>
                            <div class="row mt-2">
                                <div class="col-10 ml-5">
                                    <textarea id="note" name="note">
                                        {!! $note->name_th !!}
                                    </textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-sm-12 col-12">
                    <div class="card p-4 mb-4">
                        <div style="display:block;" id="SHOW">
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
                                                    <td  scope="row"style="text-align:left;width: 20% " ><samp style="font-weight: bold;">Subject :</samp></td>
                                                    <td style="text-align:left;"><samp>ขอเสนอราคาค่าที่พัก อาหาร สัมมนา และ กิจกรรม</samp></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-6 col-md-6 col-sm-12">
                                        <table class="table table-borderless" >
                                            <tbody>
                                                <tr><th style="width: 50%"></th>
                                                    <td  scope="row"style="text-align:left;width: 15% " ><samp style="font-weight: bold;">Date :</samp></td>
                                                    <td style="text-align:left;">{{ $date->format('d/m/Y H:i:s') }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="styled-hr"></div>
                                <div class="col-12 mt-2">
                                    <samp>อภินันทนาการทางรีสอร์ท</samp>
                                    <div class="row mt-2">
                                        <div class="col-10 ml-5">
                                            <textarea id="Complimentary" name="Complimentary">
                                                {!! $Complimentary->name_th !!}
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <samp>ทางรีสอร์ทขอสงวนสิทธิ์แก่ผู้ใช้บริการดังนี้</samp>
                                    <div class="row mt-2">
                                        <div class="col-10 ml-5">
                                            <textarea name="All_rights_reserved" id="All_rights_reserved"style="width: 100%">
                                                {!! $All_rights_reserved->name_th !!}
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="styled-hr mt-3"></div>
                                <div class="col-12 row mt-5">
                                    <div class="col-4"></div>
                                    <div class="col-4 "  style="display:flex; justify-content:center; align-items:center;">
                                        <button type="submit" class="btn btn-primary mt-3 lift btn_modal" >บันทึกข้อมูล</button>
                                    </div>
                                    <div class="col-4"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $('#Paymentterms').summernote();
    $('#Reservation').summernote();
    $('#note').summernote();
    $('#Cancellations').summernote();
    $('#Complimentary').summernote();
    $('#All_rights_reserved').summernote();
</script>
<script>
    function editTemplate() {
        console.log(1);
        var showDiv = document.getElementById('SHOW');
        var editDiv = document.getElementById('edit');

        if (showDiv.style.display === 'none') {
            showDiv.style.display = 'block';
            editDiv.style.display = 'none';
        } else {
            showDiv.style.display = 'none';
            editDiv.style.display = 'block';
        }
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
@endsection
