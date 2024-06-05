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
</style>
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
                    <p class="quotation-id "><p>Quotation ID: {{ $Quotation->Quotation_ID }}</p></p>
                    <p class="quotation-id "><p>Issue Date: {{ $Quotation->issue_date }}</p></p>
                    <p class="quotation-id "><p>Expiration Date: {{ $Quotation->ExpirationDate }}</p></p>
                    <input type="hidden" id="Quotation_ID" name="Quotation_ID" value="{{$Quotation->Quotation_ID}}">
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
            <div class="col-lg-2 col-md-12 col-sm-12">
                <img src="{{ asset('assets2/images/icon01-01.png') }}" id="room_revenue_image" class="room_revenue" data-bs-toggle="modal" data-bs-target="#room_revenue_modal">
            </div>
            <div class="col-lg-2 col-md-12 col-sm-12">
                <img src="{{ asset('assets2/images/icon01-02.png') }}" id="Foodrevenue" class="Foodrevenue">
            </div>
            {{-- Modal --}}
            <div class="modal fade" id="room_revenue_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Room</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color: #fff"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered" style="width: 100%; table-layout:fixed;">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th>Code</th>
                                        <th>Product</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                        <th>Discount</th>
                                        <th>Add only item</th>
                                    </tr>
                                </thead>
                                @if (!empty($room_type))
                                    @foreach ($room_type as $key => $item)
                                    <tbody>
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{$item->Product_ID}}</td>
                                            <td>{{$item->name_en}}</td>
                                            <td>{{$item->detail_en}}</td>
                                            <td>{{$item->normal_price}}</td>
                                            <td>{{$item->maximum_discount}}%</td>
                                            <td><button type="button" style="background-color: #109699;display: block; margin: 0 auto;"class="button-10 select-button-Meals" >Select</button></td>
                                        </tr>
                                    </tbody>
                                    @endforeach
                                @endif
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                            <button type="button" class="btn btn-primary">ยืนยัน</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="mt-3 my-3" style="border: 1px solid #000">

    </div>

</div>
<script>
</script>
@endsection
