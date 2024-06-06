@extends('layouts.test')

@section('content')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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
        display: flex;
        flex-direction: row;
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

</style>
<form action="{{url('/Quotation/Event_Formate/create/quotation/'.$Quotation->Quotation_ID)}}" method="POST"enctype="multipart/form-data">
    @csrf
    <div class="container">
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
                <div class="col-lg-1 col-md-12 col-sm-12"></div>
                <div class="col-lg-3 col-md-12 col-sm-12 quotation-container">
                    <div class="row">
                        <p class="quotation-number">Quotation </p>
                        <p class="quotation-id " name="Quotation_ID" value="{{ $Quotation->Quotation_ID }}"><p>Quotation ID: {{ $Quotation->Quotation_ID }}</p></p>
                        <p class="quotation-id " name="IssueDate" value="{{ $Quotation->issue_date }}"><p>Issue Date: {{ $Quotation->issue_date }}</p></p>
                        <p class="quotation-id "  name="ExpirationDate" value="{{ $Quotation->ExpirationDate }}"><p>Expiration Date: {{ $Quotation->ExpirationDate }}</p></p>
                        <input type="hidden" id="Quotation_ID" name="Quotation_ID" value="{{$Quotation->Quotation_ID}}">
                        <input type="hidden" id="Quotation_ID" name="IssueDate" value="{{ $Quotation->issue_date }}">
                        <input type="hidden" id="Quotation_ID" name="ExpirationDate" value="{{ $Quotation->ExpirationDate }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 Customer-Information-container mt-5" >
                    <div class="info">
                        <p class="titleh1">Customer Information</p>
                        <div class="row">
                            <div>
                                <p style="display: inline-block;font-weight: bold;">Company Type :</p>
                                <p id="Company_type" name="Company_type" style="display: inline-block;">{{$Company_type->name_th}}</p>
                            </div>
                            <div>
                                <p style="display: inline-block;font-weight: bold;">Company Name :</p>
                                <p id="Company_name" name="Company_name" style="display: inline-block;">{{$Company_ID->Company_Name}}</p>
                            </div>
                            <div>
                                <p style="display: inline-block;font-weight: bold;">Company Address :</p>
                                <p id="Company_Address" name="Company_Address" style="display: inline-block;">{{$Company_ID->Address}}</p>
                                <p id="Tambon" name="Tambon" style="display: inline-block;" >{{'ตำบล' . $TambonID->name_th}}</p>
                                <p id="Amphures" name="Amphures" style="display: inline-block;">{{'อำเภอ' .$amphuresID->name_th}}</p>
                                <p id="City" name="City" style="display: inline-block;">{{'จังหวัด' .$provinceNames->name_th}}</p>
                                <p id="Zip_Code" name="Zip_Code" style="display: inline-block;">{{$TambonID->Zip_Code}}</p>
                            </div>
                            <div>
                                <p style="display: inline-block;font-weight: bold;">Taxpayer Identification Number :</p>
                                <p id="Taxpayer_Identification" name="Taxpayer_Identification" style="display: inline-block;">{{$Company_ID->Taxpayer_Identification}}</p>
                            </div>
                            <div>
                                <p style="display: inline-block;font-weight: bold;">Company Email :</p>
                                <p id="Company_Email" name="Company_Email" style="display: inline-block;">{{$Company_ID->Company_Email}}</p>
                            </div>
                            <div>
                                <p style="display: inline-block;font-weight: bold;">Company Website :</p>
                                <p id="Company_Website" name="Company_Website" style="display: inline-block;">{{$Company_ID->Company_Website}}</p>
                            </div>
                            <div>
                                <p style="display: inline-block;font-weight: bold;">Company Number :</p>
                                <p id="Company_Number" name="Company_Number" style="display: inline-block;">{{$company_phone->Phone_number}}</p>
                            </div>
                            <div>
                                <p style="display: inline-block;font-weight: bold;">Company Fax :</p>
                                <p id="Company_Fax" name="Company_Fax" style="display: inline-block;">{{$company_fax->Fax_number}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 Contact-Information-container mt-5" >
                    <div class="info">
                        <p class="titleh1">Contact Information</p>
                        <div class="row">
                            <div>
                                <p style="display: inline-block;font-weight: bold;">Contact Name :</p>
                                <p id="Company_contact" name="Company_contact" style="display: inline-block;">{{$Contact_name->First_name}} {{$Contact_name->Last_name}}</p>
                            </div>
                            <div>
                                <p style="display: inline-block;font-weight: bold;">Contact Address :</p>
                                <p id="Contact_Address" name="Contact_Address" style="display: inline-block;">{{$Contact_name->Address}}</p>
                                <p id="Contact_Tambon" name="Contact_Tambon" style="display: inline-block;">{{'ตำบล'.$ContactTambonID->name_th}}</p>
                                <p id="Contact_Amphures" name="Contact_Amphures" style="display: inline-block;">{{'อำเภอ'.$ContactamphuresID->name_th}}</p>
                                <p id="Contact_City" name="Contact_City" style="display: inline-block;">{{'จังหวัด'.$ContactCity->name_th}}</p>
                                <p id="Contact_Zip_Code" name="Contact_Zip_Code" style="display: inline-block;">{{$ContactTambonID->Zip_Code}}</p>
                            </div>
                            <div>
                                <p style="display: inline-block;font-weight: bold;">Contact Email :</p>
                                <p id="Contact_Email" name="Contact_Email" style="display: inline-block;">{{$Contact_name->Email}}</p>
                            </div>
                            <div>
                                <p style="display: inline-block;font-weight: bold;">Contact Phone :</p>
                                <p id="Contact_Phone" name="Contact_Phone" style="display: inline-block;">{{$Contact_phone->Phone_number}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="mt-3 my-3" style="border: 1px solid #000">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-12">
                    <button type="button" class="btn button-17 button-18" data-bs-toggle="modal" data-bs-target="#exampleModalRoom">
                        + ห้องพัก
                    </button><br>
                    <div class="modal fade" id="exampleModalRoom" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">ห้องพัก</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-bordered ">
                                    <thead  class="table-dark">
                                        <tr>
                                            <th scope="col" style="width: 10%">#</th>
                                            <th scope="col"style="width: 10%">รหัส</th>
                                            <th scope="col">รายการ</th>
                                            <th scope="col">รายละเอียด</th>
                                            <th scope="col"style="width: 10%">ความจุ</th>
                                            <th scope="col"style="width: 10%">หน่วย</th>
                                            <th scope="col"style="width: 10%">คำสั่ง</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($room_type))
                                            @foreach ($unit as $singleUnit)
                                                @foreach ($room_type as $key => $item)
                                                    @if($singleUnit->id == $item->unit)
                                                    <tr>
                                                        <th scope="row">{{$key + 1}}</th>
                                                        <td>{{ $item->Product_ID }}</td>
                                                        <td>{{ $item->name_en }}</td>
                                                        <td>{{ $item->detail_en }}</td>
                                                        <td>{{ $item->pax }}</td>
                                                        <td>{{ $singleUnit->name_th }}</td>
                                                        <td><button type="button" style="background-color: #109699; display: block; margin: 0 auto;" class="button-10 select-button-room" data-id="{{ $item->Product_ID }}" data-name="{{ $item->name_en }}" data-description="{{ $item->detail_en }}" data-unit="{{ $singleUnit->name_th }}" data-pax="{{ $item->pax }}">Select</button></td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                <div class="col-12 mt-3">
                                    <h3>รายการที่เลือก</h3>
                                    <table id="selected-items-table-room" class="table table-bordered" style="width:100%">
                                        <thead class="table-dark">
                                            <tr>
                                                <th style="width: 10%;">#</th>
                                                <th style="width: 10%;">รหัส</th>
                                                <th>รายการ</th>
                                                <th>รายละเอียด</th>
                                                <th style="width: 10%;">ความจุ</th>
                                                <th scope="col" style="width: 10%;">หน่วย</th>
                                                <th style="width: 10%;">คำสั่ง</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Rows will be added here by JavaScript -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="button-10" style="background-color: #109699;" id="save-button">Select</button>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 row mt-3">
                <table id="display-selected-items" class="table table-bordered">
                    <thead  class="table-dark">
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 10%;">รหัส</th>
                            <th style="width: 20%;">รายการ</th>
                            <th style="width: 25%;">รายละเอียด</th>
                            <th style="width: 10%;">ผู้อาศัย</th>
                            <th style="width: 10%;">จำนวน</th>
                            <th style="width: 10%;">หน่วย</th>
                            <th style="width: 10%;">รวม</th>
                            <th style="width: 15%;">คำสั่ง</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows will be added here by JavaScript -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7" style="text-align:right;">Total</td>
                            <td colspan="1" id="total-amount"></td> <!-- Adjust column span and add an ID for total amount -->
                        </tr>
                    </tfoot>
                </table>
            </div>
            <hr class="mt-3 my-3" style="border: 1px solid #000">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-12">
                    <button type="button" class="btn button-17 button-18" data-bs-toggle="modal" data-bs-target="#exampleModalBanquet">
                        + ห้องประชุม
                    </button><br>
                    <div class="modal fade" id="exampleModalBanquet" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">ห้องประชุม</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-bordered ">
                                    <thead  class="table-dark">
                                        <tr>
                                            <th scope="col" style="width: 10%">#</th>
                                            <th scope="col"style="width: 10%">รหัส</th>
                                            <th scope="col">รายการ</th>
                                            <th scope="col">รายละเอียด</th>
                                            <th scope="col"style="width: 10%">ความจุ</th>
                                            <th scope="col"style="width: 10%">หน่วย</th>
                                            <th scope="col"style="width: 10%">คำสั่ง</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($Banquet))
                                            @foreach ($unit as $singleUnit)
                                                @foreach ($Banquet as $key => $item)
                                                    @if($singleUnit->id == $item->unit)
                                                    <tr>
                                                        <th scope="row">{{$key + 1}}</th>
                                                        <td>{{ $item->Product_ID }}</td>
                                                        <td>{{ $item->name_en }}</td>
                                                        <td>{{ $item->detail_en }}</td>
                                                        <td>{{ $item->pax }}</td>
                                                        <td>{{ $singleUnit->name_th }}</td>
                                                        <td><button type="button" style="background-color: #109699;display: block; margin: 0 auto;"class="button-10 select-button-Banquet" data-id="{{ $item->Product_ID }}" data-name="{{ $item->name_en }}" data-description="{{ $item->detail_en }}"data-unit="{{ $singleUnit->name_th }}">Select</button></td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                <div class="col-12 mt-3">
                                    <h3>รายการที่เลือก</h3>
                                    <table id="selected-items-table-Banquet" class="table table-bordered" style="width:100%">
                                        <thead class="table-dark">
                                            <tr>
                                                <th style="width: 10%;">#</th>
                                                <th style="width: 10%;">รหัส</th>
                                                <th>รายการ</th>
                                                <th>รายละเอียด</th>
                                                <th style="width: 10%;">ความจุ</th>
                                                <th scope="col" style="width: 10%;">หน่วย</th>
                                                <th style="width: 10%;">คำสั่ง</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Rows will be added here by JavaScript -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button"  id="save-button-Banquet" class="button-10" style="background-color: #109699;" id="save-button">Select</button>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 row mt-3">
                <table id="display-selected-items-Banquet" class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 10%;">#</th>
                            <th style="width: 10%;">รหัส</th>
                            <th style="width: 25%;">รายการ</th>
                            <th style="width: 35%;">รายละเอียด</th>
                            <th style="width: 10%;">จำนวน</th>
                            <th style="width: 10%;">หน่วย</th>
                            <th style="width: 10%;">คำสั่ง</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows will be added here by JavaScript -->
                    </tbody>
                </table>
            </div>
            <hr class="mt-3 my-3" style="border: 1px solid #000">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-12">
                    <button type="button" class="btn button-17 button-18" data-bs-toggle="modal" data-bs-target="#exampleModalMeals">
                        + มื้ออาหาร
                    </button><br>
                    <div class="modal fade" id="exampleModalMeals" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">มื้ออาหาร</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-bordered ">
                                    <thead  class="table-dark">
                                        <tr>
                                            <th scope="col" style="width: 10%">#</th>
                                            <th scope="col"style="width: 10%">รหัส</th>
                                            <th scope="col">รายการ</th>
                                            <th scope="col">รายละเอียด</th>
                                            <th scope="col"style="width: 10%">ความจุ</th>
                                            <th scope="col"style="width: 10%">หน่วย</th>
                                            <th scope="col"style="width: 10%">คำสั่ง</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($Meals))
                                            @foreach ($unit as $singleUnit)
                                                @foreach ($Meals as $key => $item)
                                                    @if($singleUnit->id == $item->unit)
                                                    <tr>
                                                        <th scope="row">{{$key + 1}}</th>
                                                        <td>{{ $item->Product_ID }}</td>
                                                        <td>{{ $item->name_en }}</td>
                                                        <td>{{ $item->detail_en }}</td>
                                                        <td>{{ $item->pax }}</td>
                                                        <td>{{ $singleUnit->name_th }}</td>
                                                        <td><button type="button" style="background-color: #109699;display: block; margin: 0 auto;"class="button-10 select-button-Meals" data-id="{{ $item->Product_ID }}" data-name="{{ $item->name_en }}" data-description="{{ $item->detail_en }}"data-unit="{{ $singleUnit->name_th }}">Select</button></td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                <div class="col-12 mt-3">
                                    <h3>รายการที่เลือก</h3>
                                    <table id="selected-items-table-Meals" class="table table-bordered" style="width:100%">
                                        <thead class="table-dark">
                                            <tr>
                                                <th style="width: 10%;">#</th>
                                                <th style="width: 10%;">รหัส</th>
                                                <th>รายการ</th>
                                                <th>รายละเอียด</th>
                                                <th style="width: 10%;">ความจุ</th>
                                                <th scope="col" style="width: 10%;">หน่วย</th>
                                                <th style="width: 10%;">คำสั่ง</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Rows will be added here by JavaScript -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button"  id="save-button-Meals" class="button-10" style="background-color: #109699;" id="save-button">Select</button>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 row mt-3">
                <table id="display-selected-items-Meals" class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 10%;">#</th>
                            <th style="width: 10%;">รหัส</th>
                            <th style="width: 25%;">รายการ</th>
                            <th style="width: 35%;">รายละเอียด</th>
                            <th style="width: 10%;">จำนวน</th>
                            <th style="width: 10%;">หน่วย</th>
                            <th style="width: 10%;">คำสั่ง</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows will be added here by JavaScript -->
                    </tbody>
                </table>
            </div>
            <hr class="mt-3 my-3" style="border: 1px solid #000">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-12">
                    <button type="button" class="btn button-17 button-18" data-bs-toggle="modal" data-bs-target="#exampleModalEntertainment">
                        + ห้องนันทนาการ
                    </button><br>
                    <div class="modal fade" id="exampleModalEntertainment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">ห้องนันทนาการ</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-bordered ">
                                    <thead  class="table-dark">
                                        <tr>
                                            <th scope="col" style="width: 10%">#</th>
                                            <th scope="col"style="width: 10%">รหัส</th>
                                            <th scope="col">รายการ</th>
                                            <th scope="col">รายละเอียด</th>
                                            <th scope="col"style="width: 10%">ความจุ</th>
                                            <th scope="col"style="width: 10%">หน่วย</th>
                                            <th scope="col"style="width: 10%">คำสั่ง</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($Entertainment))
                                            @foreach ($unit as $singleUnit)
                                                @foreach ($Entertainment as $key => $item)
                                                    @if($singleUnit->id == $item->unit)
                                                    <tr>
                                                        <th scope="row">{{$key + 1}}</th>
                                                        <td>{{ $item->Product_ID }}</td>
                                                        <td>{{ $item->name_en }}</td>
                                                        <td>{{ $item->detail_en }}</td>
                                                        <td>{{ $item->pax }}</td>
                                                        <td>{{ $singleUnit->name_th }}</td>
                                                        <td> <button type="button" style="background-color: #109699;display: block; margin: 0 auto; "class="button-10 select-button-Entertainment" data-id="{{ $item->Product_ID }}" data-name="{{ $item->name_en }}" data-description="{{ $item->detail_en }}"data-unit="{{ $singleUnit->name_th }}">Select</button></td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                <div class="col-12 mt-3">
                                    <h3>รายการที่เลือก</h3>
                                    <table  id="selected-items-table-Entertainment" class="table table-bordered" style="width:100%">
                                        <thead class="table-dark">
                                            <tr>
                                                <th style="width: 10%;">#</th>
                                                <th style="width: 10%;">รหัส</th>
                                                <th>รายการ</th>
                                                <th>รายละเอียด</th>
                                                <th style="width: 10%;">ความจุ</th>
                                                <th scope="col" style="width: 10%;">หน่วย</th>
                                                <th style="width: 10%;">คำสั่ง</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Rows will be added here by JavaScript -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="button-10" id="save-button-Entertainment" style="background-color: #109699;">Select</button>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 row mt-3">
                <table id="display-selected-items-Entertainment" class="table table-bordered">
                    <thead  class="table-dark">
                        <tr>
                            <th style="width: 10%;">#</th>
                            <th style="width: 10%;">รหัส</th>
                            <th style="width: 25%;">รายการ</th>
                            <th style="width: 35%;">รายละเอียด</th>
                            <th style="width: 10%;">จำนวน</th>
                            <th style="width: 10%;">หน่วย</th>
                            <th style="width: 10%;">คำสั่ง</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows will be added here by JavaScript -->
                    </tbody>
                </table>
            </div>
            <div class="col-12 row mt-3">
                <div class="col-4"></div>
                <div class="col-4 "  style="display:flex; justify-content:center; align-items:center;">
                    <button type="submit" class="button-10" style="background-color: #109699;">บันทึกใบเสนอราคา</button>
                </div>
                <div class="col-4"></div>
            </div>
        </div>
    </div>
</form>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let selectedItemsTableBody = document.querySelector('#selected-items-table-room tbody');
    let displaySelectedItemsTableBody = document.querySelector('#display-selected-items tbody');
    let selectButtons = document.querySelectorAll('.select-button-room');
    let saveButton = document.querySelector('#save-button');
    let totalAmountCell = document.getElementById('total-amount');
    let selectedIndex = 1;

    // Example select button data (Replace this with your actual data)
    let exampleData = [
        { id: 1, name: 'Product 1', description: 'Description 1', unit: 'Unit 1', pax: 10 },
        { id: 2, name: 'Product 2', description: 'Description 2', unit: 'Unit 2', pax: 20 }
    ];

    // Simulate select buttons
    exampleData.forEach(data => {
        let button = document.createElement('button');
        button.innerText = `Select ${data.name}`;
        button.classList.add('btn', 'btn-primary', 'select-button-room');
        button.setAttribute('data-id', data.id);
        button.setAttribute('data-name', data.name);
        button.setAttribute('data-description', data.description);
        button.setAttribute('data-unit', data.unit);
        button.setAttribute('data-pax', data.pax);
        // document.body.appendChild(button);
    });

    selectButtons = document.querySelectorAll('.select-button-room');
    selectButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            let productId = button.getAttribute('data-id');
            let productName = button.getAttribute('data-name');
            let productDescription = button.getAttribute('data-description');
            let productUnit = button.getAttribute('data-unit');
            let productPax = button.getAttribute('data-pax');
            let newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${selectedIndex}</td>
                <td>${productId}</td>
                <td style="text-align: left;">${productName}</td>
                <td style="text-align: left;">${productDescription}</td>
                <td>${productPax}</td>
                <td>${productUnit}</td>
                <td><button type="button" class="btn btn-danger remove-button">Remove</button></td>
            `;

            selectedItemsTableBody.appendChild(newRow);

            let removeButton = newRow.querySelector('.remove-button');
            removeButton.addEventListener('click', function () {
                selectedItemsTableBody.removeChild(newRow);
                selectedIndex--; // Adjust index if needed
                updateIndex(); // Function to update index after removal
            });

            selectedIndex++;
        });
    });

    function updateIndex() {
        let rows = selectedItemsTableBody.querySelectorAll('tr');
        rows.forEach((row, index) => {
            row.cells[0].innerText = index + 1;
        });
    }

    function updateTotal() {
        let totalAmount = 0;
        document.querySelectorAll('#display-selected-items tbody tr').forEach(row => {
            const productPax = parseFloat(row.cells[4].innerText);
            const quantity = parseInt(row.querySelector('.countroom').value) || 0;
            const itemTotal = productPax * quantity;
            row.querySelector('.item-total').innerText = Math.round(itemTotal); // Update item total in row without decimals
            totalAmount += itemTotal;
        });
        totalAmountCell.innerText = Math.round(totalAmount); // Update total amount in footer without decimals
    }

    saveButton.addEventListener('click', function () {
        displaySelectedItemsTableBody.innerHTML = '';
        let rows = selectedItemsTableBody.querySelectorAll('tr');
        rows.forEach((row, index) => {
            let cells = row.querySelectorAll('td');
            let newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${index + 1}</td>
                <td><input type="hidden" id="RoomID" name="RoomID[]" value="${cells[1].innerText}">${cells[1].innerText}</td>
                <td>${cells[2].innerText}</td>
                <td>${cells[3].innerText}</td>
                <td>${cells[4].innerText}</td>
                <td><input type="number" class="countroom" name="countroom[]" value="1" min="1"></td> <!-- Default quantity can be adjusted -->
                <td>${cells[5].innerText}</td>
                <td class="item-total">0</td> <!-- Add a cell for item total -->
                <td><button type="button" class="btn btn-danger remove-button">Remove</button></td>
            `;
            displaySelectedItemsTableBody.appendChild(newRow);

            newRow.querySelector('.countroom').addEventListener('input', updateTotal);
            newRow.querySelector('.remove-button').addEventListener('click', function () {
                // Remove corresponding row from selectedItemsTableBody
                let productId = newRow.cells[1].innerText;
                let correspondingRow = Array.from(selectedItemsTableBody.rows).find(row => row.cells[1].innerText === productId);
                if (correspondingRow) {
                    selectedItemsTableBody.removeChild(correspondingRow);
                    selectedIndex--;
                    updateIndex();
                }

                // Remove the row from displaySelectedItemsTableBody
                displaySelectedItemsTableBody.removeChild(newRow);
                updateTotal();
            });
        });

        updateTotal(); // Update total after all rows are added
    });
});
document.addEventListener('DOMContentLoaded', function () {
    let selectedItemsTableBodyBanquet = document.querySelector('#selected-items-table-Banquet tbody');
    let displaySelectedItemsTableBodyBanquet = document.querySelector('#display-selected-items-Banquet tbody');
    let selectButtons = document.querySelectorAll('.select-button-Banquet');
    let saveButtonBanquet = document.querySelector('#save-button-Banquet');
    let selectedIndex = 1;

    selectButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            let productId = button.getAttribute('data-id');
            let productName = button.getAttribute('data-name');
            let productDescription = button.getAttribute('data-description');
            let productUnit = button.getAttribute('data-unit');
            let newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${selectedIndex++}</td>
                <td>${productId}</td>
                <td style="text-align: left;">${productName}</td>
                <td style="text-align: left;">${productDescription}</td>
                <td></td>
                <td>${productUnit}</td>
                <td><button type="button" class="btn btn-danger remove-button">Remove</button></td>
            `;

            selectedItemsTableBodyBanquet.appendChild(newRow);

            newRow.querySelector('.remove-button').addEventListener('click', function () {
                selectedItemsTableBodyBanquet.removeChild(newRow);
                selectedIndex--; // Adjust index if needed
                updateIndex(); // Function to update index after removal
            });
        });
    });

    function updateIndex() {
        let rows = selectedItemsTableBodyBanquet.querySelectorAll('tr');
        rows.forEach((row, index) => {
            row.cells[0].innerText = index + 1;
        });
    }

    saveButtonBanquet.addEventListener('click', function () {
        displaySelectedItemsTableBodyBanquet.innerHTML = '';
        let rows = selectedItemsTableBodyBanquet.querySelectorAll('tr');
        rows.forEach((row, index) => {
            let cells = row.querySelectorAll('td');
            let newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${index + 1}</td>
                <td><input type="hidden" id="BanquetID" name="BanquetID[]" value="${cells[1].innerText}">${cells[1].innerText}</td>
                <td>${cells[2].innerText}</td>
                <td>${cells[3].innerText}</td>
                <td><input type="number" class="countBanquet" name="countBanquet[]" value="1" min="1"></td>
                <td>${cells[5].innerText}</td>
                <td><button type="button" class="btn btn-danger remove-button">Remove</button></td>
            `;
            displaySelectedItemsTableBodyBanquet.appendChild(newRow);

            newRow.querySelector('.remove-button').addEventListener('click', function () {
                // Remove corresponding row from selectedItemsTableBodyBanquet
                selectedItemsTableBodyBanquet.removeChild(rows[index]);

                // Remove the row from displaySelectedItemsTableBodyBanquet
                displaySelectedItemsTableBodyBanquet.removeChild(newRow);

                // Update indexes
                selectedIndex--;
                updateIndex();
            });
        });
    });
});
document.addEventListener('DOMContentLoaded', function () {
    let selectedItemsTableBodyMeals = document.querySelector('#selected-items-table-Meals tbody');
    let displaySelectedItemsTableBodyMeals = document.querySelector('#display-selected-items-Meals tbody');
    let selectButtons = document.querySelectorAll('.select-button-Meals');
    let saveButtonMeals = document.querySelector('#save-button-Meals');

    let selectedIndex = 1;

    selectButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            let productId = button.getAttribute('data-id');
            let productName = button.getAttribute('data-name');
            let productDescription = button.getAttribute('data-description');
            let productUnit = button.getAttribute('data-unit');
            let productPax = button.getAttribute('data-pax');
            let newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${selectedIndex++}</td>
                <td>${productId}</td>
                <td style="text-align: left;">${productName}</td>
                <td style="text-align: left;">${productDescription}</td>
                <td style="text-align: left;"></td>
                <td>${productUnit}</td>
                <td><button type="button" class="btn btn-danger remove-button-meals">Remove</button></td>
            `;

            selectedItemsTableBodyMeals.appendChild(newRow);

            newRow.querySelector('.remove-button-meals').addEventListener('click', function () {
                selectedItemsTableBodyMeals.removeChild(newRow);
                selectedIndex--; // Adjust index if needed
                updateIndex(); // Function to update index after removal
            });
        });
    });

    function updateIndex() {
        let rows = selectedItemsTableBodyMeals.querySelectorAll('tr');
        rows.forEach((row, index) => {
            row.cells[0].innerText = index + 1;
        });
    }

    saveButtonMeals.addEventListener('click', function () {
        displaySelectedItemsTableBodyMeals.innerHTML = '';
        let rows = selectedItemsTableBodyMeals.querySelectorAll('tr');
        rows.forEach((row, index) => {
            let cells = row.querySelectorAll('td');
            let newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${index + 1}</td>
                <td><input type="hidden" id="MealsID" name="MealsID[]" value="${cells[1].innerText}">${cells[1].innerText}</td>
                <td>${cells[2].innerText}</td>
                <td>${cells[3].innerText}</td>
                <td><input type="number" class="countMeals" name="countMeals[]" value="1" min="1"></td>
                <td>${cells[5].innerText}</td>
                <td><button type="button" class="btn btn-danger remove-button-meals">Remove</button></td>
            `;
            displaySelectedItemsTableBodyMeals.appendChild(newRow);

            newRow.querySelector('.remove-button-meals').addEventListener('click', function () {
                // Remove corresponding row from selectedItemsTableBodyMeals
                selectedItemsTableBodyMeals.removeChild(rows[index]);

                // Remove the row from displaySelectedItemsTableBodyMeals
                displaySelectedItemsTableBodyMeals.removeChild(newRow);

                // Update indexes
                selectedIndex--;
                updateIndex();
            });
        });
    });
});
document.addEventListener('DOMContentLoaded', function () {
    let selectedItemsTableBodyEntertainment = document.querySelector('#selected-items-table-Entertainment tbody');
    let selectButtons = document.querySelectorAll('.select-button-Entertainment');
    let displaySelectedItemsTableBodyEntertainment = document.querySelector('#display-selected-items-Entertainment tbody');
    let saveButtonEntertainment = document.querySelector('#save-button-Entertainment');
    let selectedIndex = 1;

    selectButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            let productId = button.getAttribute('data-id');
            let productName = button.getAttribute('data-name');
            let productDescription = button.getAttribute('data-description');
            let productUnit = button.getAttribute('data-unit');
            let newRow = document.createElement('tr');
            let displayUnit = productUnit === 'Music_band' ? 'Music band' : productUnit;
            newRow.innerHTML = `
                <td>${selectedIndex++}</td>
                <td>${productId}</td>
                <td style="text-align: left;">${productName}</td>
                <td style="text-align: left;">${productDescription}</td>
                 <td style="text-align: left;"></td>
                <td>${displayUnit}</td>
                <td><button type="button" class="btn btn-danger remove-button">Remove</button></td>
            `;

            selectedItemsTableBodyEntertainment.appendChild(newRow);

            newRow.querySelector('.remove-button').addEventListener('click', function () {
                selectedItemsTableBodyEntertainment.removeChild(newRow);
                selectedIndex--; // Adjust index if needed
                updateIndex(); // Function to update index after removal
            });
        });
    });

    function updateIndex() {
        let rows = selectedItemsTableBodyEntertainment.querySelectorAll('tr');
        rows.forEach((row, index) => {
            row.cells[0].innerText = index + 1;
        });
    }

    saveButtonEntertainment.addEventListener('click', function () {
        displaySelectedItemsTableBodyEntertainment.innerHTML = '';
        let rows = selectedItemsTableBodyEntertainment.querySelectorAll('tr');
        rows.forEach((row, index) => {
            let cells = row.querySelectorAll('td');
            let productId = cells[1].innerText;
            let newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${index + 1}</td>
                <td><input type="hidden" id="EntertainmentID" name="EntertainmentID[]" value="${cells[1].innerText}">${cells[1].innerText}</td>
                <td>${cells[2].innerText}</td>
                <td>${cells[3].innerText}</td>
                <td><input type="number" id="countEntertainment" name="countEntertainment[]"value="1" min="1"></td> <!-- Default quantity can be adjusted -->
                <td>${cells[5].innerText}</td>
                <td><button type="button" class="btn btn-danger remove-button">Remove</button></td>
            `;
            displaySelectedItemsTableBodyEntertainment.appendChild(newRow);

            newRow.querySelector('.remove-button').addEventListener('click', function () {
                // Remove corresponding row from selectedItemsTableBodyEntertainment
                selectedItemsTableBodyEntertainment.removeChild(rows[index]);

                // Remove the row from displaySelectedItemsTableBodyEntertainment
                displaySelectedItemsTableBodyEntertainment.removeChild(newRow);

                // Update indexes
                selectedIndex--;
                updateIndex();
            });
        });
    });
});
</script>
@endsection
