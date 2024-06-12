@extends('layouts.test')

@section('content')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        width: 50%;
        padding: 1px 10px;
        margin: 20px 0;
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
        width: 50%;
        padding: 1px 10px;
        margin: 20px 0;
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
    .com {
        display: inline-block;  /* ทำให้ border-bottom มีความยาวเท่ากับข้อความ */
            border-bottom: 2px solid #2D7F7B;  /* กำหนดเส้นใต้ */
            padding-bottom: 5px;
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
        color: #ffffff;
    }
    .profile {
        font-size: 24px;
        margin: 0;
        margin-right: 5px;
        display: table-cell;
        vertical-align: middle;
        color: #ffffff;
    }
    .quotation-id {
        font-size: 18px;
        margin: 0;
        margin-right: 5px;
        color: #ffffff;
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
    .Customer-Information-container {
        display: flex;
        flex-direction: row;
        align-items: center;
        text-align: left;
    }

    .Customer-Information-container  .info {
        margin-top: 0;
    }

    .Customer-Information-container  .info p {
        margin: 5px 0;
    }
    .row p {
        margin: 0; /* ลบ margin ที่เกิดจากการใช้งานของบราวเซอร์ */
    }
    .Contact-Information-container {


        text-align: left;
    }

    .Contact-Information-container  .info {
        margin-top: 0;
    }

    .Contact-Information-container  .info p {
        margin: 5px 0;
    }
    .Customer-Information-container {
        display: flex;
        flex-direction: row;
        align-items: center;
        text-align: left;
    }

    .Customer-Information-container  .info {
        margin-top: 0;
    }

    .Customer-Information-container  .info p {
        margin: 5px 0;
    }
    .room_revenue{
        width: 80%;
    }
    .room_revenue {
        background: #fff no-repeat center center;
        width: 90%;
        vertical-align: middle;
        margin-right: 5px;
        transition: background-color 0.3s, transform 0.3s;/* ระยะห่างจากข้อความ */
    }
    .room_revenue:hover {
        background: #fff ;
        transform: scale(1.1);
    }
    .room_revenue:active {
        transform: scale(0.9);
    }
    .Foodrevenue {
        background: #fff no-repeat center center;
        width: 90%;
        vertical-align: middle;
        margin-right: 5px;
        transition: background-color 0.3s, transform 0.3s;/* ระยะห่างจากข้อความ */
    }
    .Foodrevenue:hover {
        background: #fff ;
        transform: scale(1.1);
    }
    .Foodrevenue:active {
        transform: scale(0.9);
    }
    .MeetingRooom{
        background: #fff no-repeat center center;
        width: 80%;
        vertical-align: middle;
        margin-right: 5px;
        transition: background-color 0.3s, transform 0.3s;/* ระยะห่างจากข้อความ */
    }
    .MeetingRooom:hover {
        background: #fff ;
        transform: scale(1.1);
    }
    .MeetingRooom:active {
        transform: scale(0.9);
    }
    .EntertainmentRooom{
        background: #fff no-repeat center center;
        width: 60%;
        vertical-align: middle;
        margin-right: 5px;
        transition: background-color 0.3s, transform 0.3s;/* ระยะห่างจากข้อความ */
    }
    .EntertainmentRooom:hover {
        background: #fff ;
        transform: scale(1.1);
    }
    .EntertainmentRooom:active {
        transform: scale(0.9);

    }
    .Btn {
  background-color: transparent;
  position: relative;
  border: none;
}

.Btn::after {
  content: 'delete';
  position: absolute;
  top: -130%;
  left: 50%;
  transform: translateX(-50%);
  width: fit-content;
  height: fit-content;
  background-color: rgb(168, 7, 7);
  padding: 4px 8px;
  border-radius: 5px;
  transition: .2s linear;
  transition-delay: .2s;
  color: white;
  text-transform: uppercase;
  font-size: 12px;
  opacity: 0;
  visibility: hidden;
}

.icon {
  transform: scale(1.2);
  transition: .2s linear;
}

.Btn:hover > .icon {
  transform: scale(1.5);
}

.Btn:hover > .icon path {
  fill: rgb(168, 7, 7);
}

.Btn:hover::after {
  visibility: visible;
  opacity: 1;
  top: -160%;
}
.button-11 {
    align-items: center;
    appearance: none;
    background-color: #109699;
    border-radius: 8px;
    border-style: none;
    box-shadow: rgba(0, 0, 0, 0.2) 0 3px 5px -1px,
      rgba(0, 0, 0, 0.14) 0 6px 10px 0, rgba(0, 0, 0, 0.12) 0 1px 18px 0;
    box-sizing: border-box;
    color: #ffffff;
    cursor: pointer;
    display: flex;
    fill: currentcolor;
    font-size: 28px;
    font-weight: 500;
    height: 38px;
    justify-content: center;
    letter-spacing: 0.25px;
    line-height: normal;
    max-width: 100%;
    overflow: visible;
    padding: 2px 12px;
    position: relative;
    text-align: center;
    text-transform: none;
    transition: box-shadow 280ms cubic-bezier(0.4, 0, 0.2, 1),
      opacity 15ms linear 30ms, transform 270ms cubic-bezier(0, 0, 0.2, 1) 0ms;
    touch-action: manipulation;
    width: auto;
    will-change: transform, opacity;
    margin-left: 5px;
    margin-top: 10px;
    padding: 10px;
    padding-top: 0px;

  }

  .button-11:hover {
    background-color: #ffffff !important;
    color: #000000;
    transform: scale(1.1);
  }
  .total {
    text-align: right;
    position: relative;
    margin-left: 24px !important;
    }


.frame{
    display: flex;
    justify-content: center;
        border: 2px solid #109699; /* ใส่กรอบขอบดำขนาด 2px */
        padding: 10px; /* เพิ่มช่องว่างด้านในของกรอบ */
        background-color: #109699;
        border-radius: 10px;
       /* กำหนดสีพื้นหลังเป็นสีเทาอ่อน */
    }
    .frameinfo{
        border: 2px solid #080808; /* ใส่กรอบขอบดำขนาด 2px */
        padding: 10px; /* เพิ่มช่องว่างด้านในของกรอบ */
        background-color: #ffffff;
        border-radius: 10px;
       /
    }
    textarea {
            box-sizing: border-box;
            width: 100%; /* Full width */
            max-width: 100%;
            padding: 10px; /* Add padding for better readability */
            font-family: Arial, sans-serif; /* Use a clean font */
            font-size: 16px; /* Adjust font size */
            line-height: 1.5; /* Adjust line height for better readability */
            border: 1px solid #ccc; /* Add a light border */
            border-radius: 5px; /* Rounded corners */
            resize: vertical; /* Allow vertical resize only */
            transition: border-color 0.3s, box-shadow 0.3s; /* Smooth transition for focus effects */
        }

        /* Style for textarea on focus */
        textarea:focus {
            border-color: #66afe9; /* Blue border on focus */
            box-shadow: 0 0 8px rgba(102, 175, 233, 0.6); /* Blue glow on focus */
            outline: none; /* Remove default outline */
        }
        .styled-hr {
            background-color: #0a4c49; /* กำหนดสีพื้นหลัง */
            height: 3px; /* กำหนดความหนา */
            border: none; /* เอาขอบออก */
            margin-top: 3rem; /* กำหนดระยะห่างด้านบน */
            margin-bottom: 3rem; /* กำหนดระยะห่างด้านล่าง */
        }
        .border-end {
            border-right: 2px solid #2D7F7B; /* กำหนดสี ความหนา และลักษณะของขอบขวา */
            padding-right: 10px; /* เพิ่ม padding เพื่อให้ขอบชัดเจนขึ้น */
        }
</style>
<form action="" method="POST"enctype="multipart/form-data">
    @csrf
    <div class="container">
        <div class=" col-12">
            <div class="row">
            <div class="col-lg-8 col-md-12 col-sm-12 image-container">
                <img src="{{ asset('assets2/images/logo_crop.png') }}" alt="Together Resort Logo" class="logo"/>
                <div class="info ">
                    <p class="titleh1">Together Resort Limited Partnership</p>
                    <p>168 Moo 2 Kaengkrachan Phetchaburi 76170</p>
                    <p>Tel : 032-708-888, 098-393-944-4 Fax :</p>
                    <p>Email : reservation@together-resort.com Website : www.together-resort.com</p>
                    <p></p>
                </div>
            </div>
                <div class="col-lg-1 col-md-12 col-sm-12"></div>
                <div class="col-lg-3 col-md-12 col-sm-12 quotation-container">
                    <div class="row ">
                        <div class="frame">
                            <p class="quotation-number">PROPOSAL</p>
                        </div>
                        <div class="frameinfo" style="margin-top: 25px !important;">
                            <p class="quotation-id " name="Quotation_ID" value="{{ $Quotation->Quotation_ID }}"><p>Proposal ID: {{ $Quotation->Quotation_ID }}</p></p>
                            <p class="quotation-id " name="IssueDate" value="{{ $Quotation->issue_date }}"><p>Issue Date: {{ $Quotation->issue_date }}</p></p>
                            <p class="quotation-id "  name="ExpirationDate" value="{{ $Quotation->ExpirationDate }}"><p>Expiration Date: {{ $Quotation->ExpirationDate }}</p></p>
                        </div>
                        <input type="hidden" id="Quotation_ID" name="Quotation_ID" value="{{$Quotation->Quotation_ID}}">
                        <input type="hidden" id="Quotation_ID" name="IssueDate" value="{{ $Quotation->issue_date }}">
                        <input type="hidden" id="Quotation_ID" name="ExpirationDate" value="{{ $Quotation->ExpirationDate }}">
                    </div>
                </div>
            </div>
            <div class="col-2 frame ">
                <p class="profile">Profile ID {{ $Quotation->Company_ID }} </p><br>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12  Contact-Information-container border-end border-dark" >
                    <div class="info">
                        <p class="com">Company Information</p>
                        <div class="row">
                            <div>
                                @if ($Company_type->name_th === 'บริษัทจำกัด')
                                    <p id="Company_name" name="Company_name" style="display: inline-block;font-weight: bold;">บริษัท {{ $Company_ID->Company_Name }} จำกัด</p>
                                @elseif ($Company_type->name_th === 'บริษัทมหาชนจำกัด')
                                    <p id="Company_name" name="Company_name" style="display: inline-block;font-weight: bold;">บริษัท {{ $Company_ID->Company_Name }} จำกัด (มหาชน)</p>
                                @elseif ($Company_type->name_th === 'ห้างหุ้นส่วนจำกัด')
                                    <p id="Company_name" name="Company_name" style="display: inline-block;font-weight: bold;">ห้างหุ้นส่วนจำกัด {{ $Company_ID->Company_Name }}</p>
                                @endif

                            </div>
                            <div>
                                <p id="Company_Address" name="Company_Address" style="display: inline-block;font-weight: bold;">{{$Company_ID->Address}}</p>
                                <p id="Tambon" name="Tambon" style="display: inline-block;font-weight: bold;" >{{'ตำบล' . $TambonID->name_th}}</p>
                                <p id="Amphures" name="Amphures" style="display: inline-block;font-weight: bold;">{{'อำเภอ' .$amphuresID->name_th}}</p>
                                <p id="City" name="City" style="display: inline-block;font-weight: bold;">{{'จังหวัด' .$provinceNames->name_th}}</p>
                                <p id="Zip_Code" name="Zip_Code" style="display: inline-block;font-weight: bold;">{{$TambonID->Zip_Code}}</p>
                            </div>
                            <div class="col-12 row">
                                <div class="col-4">
                                    <p style="display: inline-block;font-weight: bold;">Tel :</p>
                                    <p id="Company_Number" name="Company_Number" style="display: inline-block;font-weight: bold;">{{$company_phone->Phone_number}}</p>
                                </div>
                                <div class="col-4">
                                    <p style="display: inline-block;font-weight: bold;">Fax :</p>
                                    <p id="Company_Fax" name="Company_Fax" style="display: inline-block;font-weight: bold;">{{$company_fax->Fax_number}}</p>
                                </div>
                            </div>
                            <div>
                                <p style="display: inline-block;font-weight: bold;">Email :</p>
                                <p id="Company_Email" name="Company_Email" style="display: inline-block;font-weight: bold;">{{$Company_ID->Company_Email}}</p>
                            </div>
                            <div>
                                <p style="display: inline-block;font-weight: bold;">Taxpayer Identification Number :</p>
                                <p id="Taxpayer_Identification" name="Taxpayer_Identification" style="display: inline-block;font-weight: bold;">{{$Company_ID->Taxpayer_Identification}}</p>
                            </div>
                            <div>
                                <br><br>
                            </div>
                            <div>
                                <p class="com">Personal Information</p>
                            </div>
                            <div class="col-12 row">
                                <div class="col-5">
                                    <p style="display: inline-block;font-weight: bold;">Name :</p>
                                    <p id="Company_contact" name="Company_contact" style="display: inline-block;font-weight: bold;">คุณ{{$Contact_name->First_name}} {{$Contact_name->Last_name}}</p>
                                </div>
                                <div class="col-6">
                                    <p style="display: inline-block;font-weight: bold;">Tel :</p>
                                    <p id="Contact_Phone" name="Contact_Phone" style="display: inline-block;font-weight: bold;">{{$Contact_phone->Phone_number}}</p>
                                </div>
                                <div>
                                    <p style="display: inline-block;font-weight: bold;">Email :</p>
                                    <p id="Contact_Email" name="Contact_Email" style="display: inline-block;font-weight: bold;">{{$Contact_name->Email}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 Contact-Information-container" >
                    <div class="info">
                            <div><br><br><br><br></div>
                            <div class="col-12 row" >

                                <div class="col-lg-4 " style="text-align: right;">
                                    <p style="display: inline-block;font-weight: bold;">Check In :</p><br>
                                    <p style="display: inline-block;font-weight: bold;">Check Out :</p><br>
                                    <p style="display: inline-block;font-weight: bold;">Length of Stay :</p><br>
                                    <p style="display: inline-block;font-weight: bold;">Number of Guests :</p>
                                </div>
                                <div class="col-lg-5">
                                    <p style="display: inline-block;font-weight: bold;">{{$Quotation->checkin}}</p><br>
                                    <p style="display: inline-block;font-weight: bold;">{{$Quotation->checkout}}</p><br>
                                    <p style="display: inline-block;font-weight: bold;">{{$Quotation->day}} วัน {{$Quotation->night}} คืน</p><br>
                                    <p style="display: inline-block;font-weight: bold;">{{$Quotation->adult}} Adult , {{$Quotation->adult}} Children</p>
                                </div>
                            </div>

                    </div>
                </div>
            </div>
            <hr class="styled-hr mt-3 my-3" style="background-color: #2D7F7B;">
            <div>
                <strong>ขอเสนอราคาและเงื่อนไขสำหรับท่าน ดังนี้ <br> We are pleased to submit you the following desctibed here in as price,items and terms stated :</strong>
            </div>
            <div class="row mt-3">
                <div class="col-lg-2 col-md-2 col-sm-12">
                    <button type="button" id="addproduct" class="btn button-17 button-18" data-bs-toggle="modal" data-bs-target="#exampleModalproduct" onclick="fetchProducts('all')">
                        + Add Product
                    </button><br>
                    <div class="modal fade" id="exampleModalproduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Product</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="col-12 mt-3">
                                    <div class="dropdown-center">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            ประเภท Product
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="">
                                            <a class="dropdown-item" style="color: #000;" data-value="all" onclick="fetchProducts('all')">All Product</a>
                                            <a class="dropdown-item" style="color: #000;" data-value="Room_Type"onclick="fetchProducts('Room_Type')">Room</a>
                                            <a class="dropdown-item" style="color: #000;" data-value="Banquet"onclick="fetchProducts('Banquet')">Banquet</a>
                                            <a class="dropdown-item" style="color: #000;" data-value="Meals"onclick="fetchProducts('Meals')">Meal</a>
                                            <a class="dropdown-item" style="color: #000;" data-value="Entertainment"onclick="fetchProducts('Entertainment')">Entertainment</a>
                                        </div>
                                    </div>
                                </div>
                                <hr class="mt-3 my-3" style="border: 1px solid #000">
                                <table  class="table table-bordered ">
                                    <thead  class="table-dark">
                                        <tr>
                                            <th scope="col" style="width: 10%">#</th>
                                            <th scope="col"style="width: 10%">รหัส</th>
                                            <th scope="col">รายการ</th>
                                            <th scope="col"style="width: 10%">หน่วย</th>
                                            <th scope="col"style="width: 10%">ราคา</th>
                                            <th scope="col"style="width: 5%">คำสั่ง</th>
                                        </tr>
                                    </thead>
                                    <tbody id="product-list">

                                    </tbody>
                                </table>
                                <div class="col-4 mt-3" >
                                        <ul class="pagination" id="pagination"></ul>
                                </div>
                                <div class="col-12 mt-3">
                                    <h3>รายการที่เลือก</h3>
                                    <table  class="table table-bordered" style="width:100%">
                                        <thead class="table-dark">
                                            <tr>
                                                <th style="width: 10%;">รหัส</th>
                                                <th>รายการ</th>
                                                <th scope="col" style="width: 10%;">หน่วย</th>
                                                <th scope="col"style="width: 10%">ราคา</th>
                                                <th style="width: 5%;">คำสั่ง</th>
                                            </tr>
                                        </thead>
                                        <tbody id="product-list-select">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="button-10 confirm-button" style="background-color: #109699;" id="confirm-button"data-bs-dismiss="modal">Save</button>
                            </div>
                        </div>
                        </div>
                        <div id="modalOverlay" class="modal-overlay"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 row mt-3">
                <table  class="table table-bordered" id="display-selected-items">
                    <thead  class="table-dark">
                        <tr>
                            <th style="width: 5%;">No.</th>
                            <th style="width: 30%;">Description</th>
                            <th style="width: 10%;">Quantity </th>
                            <th style="width: 5%;">Unit </th>
                            <th scope="col"style="width: 10%">Price / Unit </th>
                            <th scope="col"style="width: 12%">Discount  (%)</th>
                            <th style="width: 15%;">Net price/Unit</th>
                            <th style="width: 8%;">Amount</th>
                            <th style="width: 5%;">Order</th>
                        </tr>
                    </thead>
                    <tbody id="display-selected-items">

                        @if (!empty($selectproduct))
                            @foreach ($selectproduct as $key => $item)
                                @foreach ($unit as $singleUnit)
                                    @if($singleUnit->id == @$item->product->unit)
                                        <tr  id="tr-select-addmain{{$item->product->id}}">
                                            <input type="hidden" id="tr-select-addmain{{$key+1}}" name="tr-select-addmain" value="{{$item->id}}">
                                            <td><input type="hidden" id="ProductID" name="ProductID[]" value="{{$key+1}}">{{$key+1}}</td>
                                            <td style="text-align:left;"><input type="hidden" id="Productname_th" name="Productname_th" value="{{@$item->product->name_th}}">{{@$item->product->name_th}}</td>
                                            <td class="Quantity" data-value="{{$item->Quantity}}"><input type="hidden" id="Quantity" name="Quantity" value="{{$item->Quantity}}">{{$item->Quantity}}</td>
                                            <td ><input type="hidden" id="unitname_th" name="unitname_th" value="{{ $singleUnit->name_th }}">{{ $singleUnit->name_th }}</td>
                                            <td class="priceproduct" data-value="{{$item->priceproduct}}"><input type="hidden" id="totalprice-unit{{$key+1}}" name="priceproduct" value="{{$item->priceproduct}}">{{ number_format($item->priceproduct, 2, '.', ',') }}</td>
                                            <td class="discount"><input type="hidden" id="discount" name="discount" value="{{$item->discount}}">{{$item->discount}}</td>
                                            <td class="net-price"><input type="hidden" id="net_discount{{$key+1}}" name="net_discount" value="{{$item->netpriceproduct}}">{{ number_format($item->netpriceproduct, 2, '.', ',') }}</td>
                                            <td class="item-total"><input type="hidden" id="allcounttotal{{$key+1}}" name="allcounttotal" value="{{$item->totalpriceproduct}}">{{ number_format($item->totalpriceproduct, 2, '.', ',') }}</td>
                                            <td>
                                                <button type="button" class="Btn remove-button1">
                                                    <svg viewBox="0 0 15 17.5" height="17.5" width="15" xmlns="http://www.w3.org/2000/svg" class="icon">
                                                        <path transform="translate(-2.5 -1.25)" d="M15,18.75H5A1.251,1.251,0,0,1,3.75,17.5V5H2.5V3.75h15V5H16.25V17.5A1.251,1.251,0,0,1,15,18.75ZM5,5V17.5H15V5Zm7.5,10H11.25V7.5H12.5V15ZM8.75,15H7.5V7.5H8.75V15ZM12.5,2.5h-5V1.25h5V2.5Z" id="Fill"></path>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div >
                <div class="col-12 row ">
                    <div class="col-lg-8 col-md-12 col-sm-12">
                        <span class="my-2">Notes or Special Comment</span>
                        <textarea class="mt-2" name="comment" id="comment" cols="30" rows="5">{{$Quotation->comment}}</textarea>
                    </div>
                    <div class="col-lg-2 col-md-12 col-sm-12 total" >
                        <span>Total Amount</span><br>
                        <span>Discount (%)</span><br>
                        <span>Net price</span><br>
                        <span>Value Added Tax</span><br>
                        <span>Net Total (฿)</span><br>
                        <span>Average per person </span><br>
                    </div>
                    <div class="col-lg-1 col-md-12 col-sm-12 total" >
                        <span id="total-amount"></span><br>
                        <span id="total-Discount"></span><br>
                        <span id="Net-price"></span><br>
                        <span id="total-Vat"></span><br>
                        <span id="Net-Total"></span>
                        <span id="Average" name="Average"></span>
                    </div>
                </div>
            <div class="col-12 mt-3">
                <input type="hidden" name="adult" id="adult" value="{{$Quotation->adult}}">
                <input type="hidden" name="children" id="children" value="{{$Quotation->children}}">
            </div>
            <div class="col-12 mt-3">
                <div class="col-4">
                    <strong class="titleh1">Method of Payment</strong>
                </div>
                <hr class="styled-hr mt-2 my-3">
                <span>
                    Please make a 50% deposit within 7 days after confirmed. <br>
                     Transfer to <strong> " Together Resort Limited Partnership "</strong> following banks details.<br>
                    If you use transfer, Please inform Accounting / Finance Department Tel or LINE ID<span style="font-size: 18px"> @Together-resort</span><br>
                    pay-in slip to number 032-708-888 every time for the correctness of payment allocation.<br>
                </span>
                <div class="row">
                    <div class="col-lg-8 col-md-6 col-sm-12">
                        <div class="col-12  mt-2">
                            <div class="row">
                                <div class="col-2 mt-3" style="display: flex;justify-content: center;align-items: center;">
                                    <img src="{{ asset('/image/bank/SCB.jpg') }}" style="width: 80%;border-radius: 50%;"/>
                                </div>
                                <div class="col-7 mt-3">
                                    <strong>The Siam Commercial Bank Public Company Limited <br>Bank Account No. 708-226791-3<br>Tha Yang - Phetchaburi Branch (Savings Account)</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="styled-hr mt-2 my-3">
            <div class="col-12 row mt-5">
                <div class="col-4"></div>
                <div class="col-4 "  style="display:flex; justify-content:center; align-items:center;">
                    <button type="submit" class="button-10" style="background-color: #109699;">บันทึกใบเสนอราคา</button>
                </div>
                <div class="col-4"></div>
            </div>
        </div>
    </div>
</form>
<input type="hidden" id="number-product" value="{{count($selectproduct)}}">
<script>
    function fetchProducts(status) {
        console.log(status);
        var Quotation_ID = '{{ $Quotation->Quotation_ID }}'; // Replace this with the actual ID you want to send
        $.ajax({
            url: '{{ route("Quotation.addProducttable", ["Quotation_ID" => ":id"]) }}'.replace(':id', Quotation_ID),
            method: 'GET',
            data: {
                value: status

            },
            success: function(response) {
                $('#product-list').children().remove().end();

                $.each(response.products, function (key, val) {
                    var name = '';
                    var price = 0;
                    var keyNo = parseInt(key + 1);

                    $('#product-list').append(
                        '<tr id="tr-select' + val.Product_ID + '">' +
                        '<td><input type="hidden" class="keyNo" name="keyNo" value="' + keyNo + '">' + keyNo + '</td>' +
                        '<td>' + val.Product_ID + '</td>' +
                        '<td style="text-align:left;">' + val.name_en + '</td>' +
                        '<td style="text-align: right;">' + val.unit_name + '</td>' +
                        '<td>' + val.normal_price + '</td>' +
                        '<td><button type="button" style="background-color: #109699; display: block; margin: 0 auto;" class="button-11 select-button-product" value="' + val.id + '">+</button></td>' +
                        '</tr>'
                    );

                    // Update key value after appending the row

                });

            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
$(document).on('click','.select-button-product',function() {
    var product = $(this).val();
    if ($('#productselect' + product).length > 0) {
        return; // ถ้ามีแล้วไม่ต้องทำอะไร
    }
    $.ajax({
        url: '{{ route("Quotation.addProducttableselect", ["Quotation_ID" => ":id"]) }}'.replace(':id', Quotation_ID),
        method: 'GET',
        data: {
            value:product

        },
        success: function(response) {
            if (!response.products || response.products.length === 0) {
                console.log("No products found");
                return;
            }  // ตรวจสอบข้อมูลที่ได้รับจาก response
            $.each(response.products, function (key, val) {
                console.log('Key:', key);  // ตรวจสอบค่าของ key ในแต่ละรอบ
                var name = '';
                var price = 0;

                $('#product-list-select').append(
                    '<tr id="tr-select-add' + val.id + '">' +
                    '<td>' + key+ + '</td>' +
                    '<td style="text-align:left;">' + val.name_en + '</td>' +
                    '<td style="text-align: right;">' + val.unit_name + '</td>' +
                    '<td>' + val.normal_price + '</td>' +
                    '<td><button type="button" class="Btn remove-button" value="' + val.id + '"><svg viewBox="0 0 15 17.5" height="17.5" width="15" xmlns="http://www.w3.org/2000/svg" class="icon"><path transform="translate(-2.5 -1.25)" d="M15,18.75H5A1.251,1.251,0,0,1,3.75,17.5V5H2.5V3.75h15V5H16.25V17.5A1.251,1.251,0,0,1,15,18.75ZM5,5V17.5H15V5Zm7.5,10H11.25V7.5H12.5V15ZM8.75,15H7.5V7.5H8.75V15ZM12.5,2.5h-5V1.25h5V2.5Z" id="Fill"></path></svg></button></td>' +
                    '<input type="hidden" id="productselect' + val.id + '" value="' + val.id + '">' +
                    '</tr>'
                );
            });
        },

        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
});
$(document).on('click', '.remove-button', function() {
    var product = $(this).val();
    $('#tr-select-add' + product).remove(); // ลบแถวที่มี id เป็น 'tr-select-add' + product
});
$(document).on('click', '.confirm-button', function() {
    var number = Number($('#number-product').val());
    $.ajax({
        url: '{{ route("Quotation.addProducttablemain", ["Quotation_ID" => ":id"]) }}'.replace(':id', Quotation_ID),
        method: 'GET',
        data: {
            value: "all"
        },
        success: function(response) {
            $.each(response.products, function (key, val) {
                if ($('#productselect'+val.id).val()!==undefined) {
                    if ($('#display-selected-items #tr-select-addmain' + val.id).length === 0) {
                        number +=1;
                        var name = '';
                        var price = 0;
                        var normalPriceString = val.normal_price.replace(/[^0-9.]/g, ''); // ล้างค่าที่ไม่ใช่ตัวเลขและจุดทศนิยม
                        var normalPrice = parseFloat(normalPriceString);
                        console.log('normalPrice:', normalPrice);
                        var netDiscount = (normalPrice - (normalPrice * 0.01)).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        $('#display-selected-items').append(
                            '<tr id="tr-select-addmain'+val.id+'">' +
                            '<td>' + val.Product_ID + '</td>' +
                            '<td style="text-align:left;">' + val.name_en + '</td>' +
                            '<td><input class="quantitymain" type="text" id="quantitymain" name="quantitymain" value="1" min="1" rel="'+ number +'"style="text-align:center;"></td>'+
                            '<td >' + val.unit_name + '</td>' +
                            '<td><input type="hidden" id="totalprice-unit-'+ number+'" name="price-unit" value="'+ val.normal_price +'">' + val.normal_price + '</td>' +
                            '<td><input class="discountmain" type="text" id="discountmain" name="discountmain" value="1" min="1" res="'+ number +'"style="text-align:center;"></td>'+
                            '<td><input type="hidden" id="net_discount-'+ number+'" name="net_discount" value="'+ val.normal_price +'"><span id="netdiscount'+ number +'">'+ netDiscount +'</span></td>'+
                            '<td><input type="hidden" id="allcounttotal-'+ number+'" name="allcounttotal" value="'+ val.normal_price +'"><span id="allcount'+ number +'">'+  val.normal_price +'</span></td>'+
                            '<td><button type="button" class="Btn remove-buttonmain" value="'+ val.id +'"><svg viewBox="0 0 15 17.5" height="17.5" width="15" xmlns="http://www.w3.org/2000/svg" class="icon"><path transform="translate(-2.5 -1.25)" d="M15,18.75H5A1.251,1.251,0,0,1,3.75,17.5V5H2.5V3.75h15V5H16.25V17.5A1.251,1.251,0,0,1,15,18.75ZM5,5V17.5H15V5Zm7.5,10H11.25V7.5H12.5V15ZM8.75,15H7.5V7.5H8.75V15ZM12.5,2.5h-5V1.25h5V2.5Z" id="Fill"></path></svg></button></td>'+
                            '</tr>'
                        );
                    }

                }

            });
            $('#number-product').val(number);
            totalAmost();
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
});
$(document).on('click', '.remove-buttonmain', function() {
    var product = $(this).val();

    $('#tr-select-add' + product).remove();
    $('#tr-select-addmain' + product).remove();
    totalAmost();// ลบแถวที่มี id เป็น 'tr-select-add' + product
});

$(document).ready(function() {

    // Function to calculate totals
    function calculateTotals() {
        totalAmount = 0;
        totalDiscount = 0;
        Netprice = 0;
        Vat = 0;
        NetTotal = 0;

        totalperson =0;
        $('table tbody').find('tr').each(function() {
            var priceproduct = parseInt($(this).find('.priceproduct').text().replace(/,/g, '')) || 0;
            var Quantity = parseInt($(this).find('.Quantity').data('value')) || 0;
            var netprice = parseFloat($(this).find('.net-price').text().replace(/,/g, '')) || 0;
            var adultValue = parseFloat(document.getElementById('adult').value);
            var childrenValue = parseFloat(document.getElementById('children').value);
            var itemtotal = Quantity * priceproduct;
            var Discount = itemtotal - netprice;
            var vat = netprice * 7 / 100;
            Vat += isNaN(vat) ? 0 : vat;
            Netprice += isNaN(netprice) ? 0 : netprice;
            totalDiscount += isNaN(Discount) ? 0 : Discount;
            totalAmount += isNaN(itemtotal) ? 0 : itemtotal;// Accumulate totalAmount correctly
            NetTotal = Netprice + Vat;
            var person =adultValue+childrenValue;
            totalperson = NetTotal/person;
            console.log(totalperson);
        });
        function formatNumber(number) {
            return new Intl.NumberFormat('th-TH', { minimumFractionDigits: 2 }).format(number);
        }
        $('#total-amount').text(isNaN(totalAmount) ? '0' : formatNumber(totalAmount));
        $('#total-Discount').text(isNaN(totalDiscount) ? '0' : formatNumber(totalDiscount));
        $('#Net-price').text(isNaN(Netprice) ? '0' : formatNumber(Netprice));
        $('#total-Vat').text(isNaN(Vat) ? '0' : formatNumber(Vat));
        $('#Net-Total').text(isNaN(NetTotal) ? '0' : formatNumber(NetTotal));
        $('#Average').text(isNaN(totalperson) ? '0' : formatNumber(totalperson));

    }

    // Initial calculation
    calculateTotals();
    // Listen for input changes
    $('table tbody').on('input', '.Quantity, .priceproduct, .net-price, .item-total', function() {
        var total = calculateTotals();
    });

    // Remove button click handler
    $(document).on('click', '.remove-button1', function() {
        $(this).closest('tr').remove(); // Remove the row
        calculateTotals();
        totalAmost();// Recalculate totals after removing row
    });
    totalAmost();

});

$(document).on('keyup', '.quantitymain', function() {
    var quantitymain =  Number($(this).val());
    var discountmain =  Number($('.discountmain').val());
    var number_ID = $(this).attr('rel');
    var number = Number($('#number-product').val());
    var price = parseFloat($('#totalprice-unit-'+number_ID).val().replace(/,/g, ''));
    var pricenew = price*quantitymain
    $('#allcount'+number_ID).text(pricenew.toLocaleString('th-TH', {minimumFractionDigits: 2}));
    var pricediscount = pricenew - (pricenew*discountmain /100);
    $('#netdiscount'+number_ID).text(pricediscount.toLocaleString('th-TH', {minimumFractionDigits: 2}));
    totalAmost();
});
$(document).on('keyup', '.discountmain', function() {
    var discountmain =  Number($(this).val());
    var quantitymain =  Number($('.quantitymain').val());
    var number_ID = $(this).attr('res');
    var number = Number($('#number-product').val());
    var price = parseFloat($('#allcounttotal-'+number_ID).val().replace(/,/g, ''));
    var pricediscount = price - (price*discountmain /100);
    $('#netdiscount'+number_ID).text(pricediscount.toLocaleString('th-TH', {minimumFractionDigits: 2}));
    var price = parseFloat($('#totalprice-unit-'+number_ID).val().replace(/,/g, ''));
    var pricenew = price*quantitymain
    var pricediscount = pricenew - (pricenew*discountmain /100);
    $('#netdiscount'+number_ID).text(pricediscount.toLocaleString('th-TH', {minimumFractionDigits: 2}));
    totalAmost();
});

    function totalAmost() {
    $(document).ready(function() {
    let allprice = 0;
    let allpricedis = 0;
    let discounttotal =0;
    let vattotal =0;
    let nettotal =0;// เริ่มต้นตัวแปร allprice และ allpricedis ที่นอกลูป
    $('#display-selected-items tr').each(function() {
        let priceCell = $(this).find('td').eq(7);
        let pricetotal = parseInt(priceCell.text().replace(/,/g, '')) || 0; // แปลงข้อความในเซลล์เป็นจำนวนเต็ม และจัดการค่า NaN
        allprice += pricetotal;

        let pricedisCell = $(this).find('td').eq(6);
        let pricedistotal = parseInt(pricedisCell.text().replace(/,/g, '')) || 0; // แปลงข้อความในเซลล์เป็นจำนวนเต็ม และจัดการค่า NaN
        allpricedis += pricedistotal;
        discounttotal = allprice-allpricedis;
        vattotal = allpricedis*7/100;
        nettotal = allpricedis+vattotal;
    });
    $('#total-amount').text(isNaN(allprice) ? '0' : allprice.toLocaleString('th-TH', {minimumFractionDigits: 2}));
    $('#total-Discount').text(isNaN(discounttotal) ? '0' : discounttotal.toLocaleString('th-TH', {minimumFractionDigits: 2}));
    $('#Net-price').text(isNaN(allpricedis) ? '0' : allpricedis.toLocaleString('th-TH', {minimumFractionDigits: 2}));
    $('#total-Vat').text(isNaN(vattotal) ? '0' : vattotal.toLocaleString('th-TH', {minimumFractionDigits: 2}));
    $('#Net-Total').text(isNaN(nettotal) ? '0' : nettotal.toLocaleString('th-TH', {minimumFractionDigits: 2}));


    });

    }
    totalAmost();

</script>

@endsection

