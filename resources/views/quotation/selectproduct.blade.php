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
        width: 80%;
        padding: 12px 20px;
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

</style>
<form action="{{url('/Quotation/company/create/quotation/'.$Quotation->Quotation_ID)}}" method="POST"enctype="multipart/form-data">
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
                    <button type="button" id="addproduct" class="btn button-17 button-18" data-bs-toggle="modal" data-bs-target="#exampleModalproduct">
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
                                            <a class="dropdown-item" style="color: #000;" data-value="all">All Product</a>
                                            <a class="dropdown-item" style="color: #000;" data-value="Room_Type">Room</a>
                                            <a class="dropdown-item" style="color: #000;" data-value="Banquet">Banquet</a>
                                            <a class="dropdown-item" style="color: #000;" data-value="Meals">Meal</a>
                                            <a class="dropdown-item" style="color: #000;" data-value="Entertainment">Entertainment</a>
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
                                    <tbody id="product-container">

                                    </tbody>

                                </table>

                                <div class="col-4 mt-3" >
                                        <ul class="pagination" id="pagination"></ul>
                                </div>
                                <div class="col-12 mt-3">
                                    <h3>รายการที่เลือก</h3>
                                    <table id="selected-items-table-room" class="table table-bordered" style="width:100%">
                                        <thead class="table-dark">
                                            <tr>
                                                <th style="width: 10%;">#</th>
                                                <th style="width: 10%;">รหัส</th>
                                                <th>รายการ</th>
                                                <th scope="col" style="width: 10%;">หน่วย</th>
                                                <th scope="col"style="width: 10%">ราคา</th>
                                                <th style="width: 5%;">คำสั่ง</th>
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
                                <button type="button" class="button-10" style="background-color: #109699;" id="save-button"data-bs-dismiss="modal">Save</button>
                            </div>
                        </div>
                        </div>
                        <div id="modalOverlay" class="modal-overlay"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 row mt-3">
                <table id="display-selected-items" class="table table-bordered">
                    <thead  class="table-dark">
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 5%;">รหัส</th>
                            <th style="width: 30%;">รายการ</th>
                            <th style="width: 10%;">จำนวน</th>
                            <th scope="col"style="width: 10%">ราคา</th>
                            <th scope="col"style="width: 10%">ส่วนลด</th>
                            <th style="width: 5%;">หน่วย</th>
                            <th style="width: 8%;">ราคาสุทธิต่อหน่วย</th>
                            <th style="width: 8%;">จำนวนเงิน</th>
                            <th style="width: 5%;">คำสั่ง</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>

                        <tr>
                            <td colspan="7" style="text-align:right;">Total Amount</td>
                            <td colspan="2" id="total-amount"></td>
                            <td rowspan="9"></td> <!-- Adjust column span and add an ID for total amount -->
                        </tr>
                        <tr>
                            <td colspan="7" style="text-align:right;">Discount (%)</td>
                            <td colspan="2" id="total-Discount"></td> <!-- Adjust column span and add an ID for total amount -->
                        </tr>
                        <tr>
                            <td colspan="7" style="text-align:right;">Net price </td>
                            <td colspan="2" id="Net-price"></td> <!-- Adjust column span and add an ID for total amount -->
                        </tr>
                        <tr>
                            <td colspan="7" style="text-align:right;">VAT (%)</td>
                            <td colspan="2" id="total-Vat"></td> <!-- Adjust column span and add an ID for total amount -->
                        </tr>
                        <tr>
                            <td colspan="7" style="text-align:right;">Net Total</td>
                            <td colspan="2" id="Net-Total"></td>
                            <!-- Adjust column span and add an ID for total amount -->
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-12 mt-3">
                <div class="col-4">
                    <strong class="titleh1">Payment</strong>
                </div>
                <hr class="mt-2 my-3" style="border: 1px solid #000">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="col-12  mt-2">
                            <div class="row">
                                <div class="col-3 mt-3">
                                    <img src="{{ asset('/image/bank/BAY.jpg') }}" style="width: 100%;border-radius: 50%;"/>
                                </div>
                                <div class="col-7">
                                    <p style="margin: 15px;">ธนาคาร กรุงศรี <br><strong>ออมทรัพย์<br>8009946600</strong><br>กฤตพล มโนโชคกวินสกุล</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="col-12  mt-2">
                            <div class="row">
                                <div class="col-3 mt-3" >
                                    <img src="{{ asset('/image/bank/SCB.jpg') }}" style="width: 100%;border-radius: 50%;"/>
                                </div>
                                <div class="col-7" >
                                    <p style="margin: 15px;">ธนาคาร ไทยพาณิชย์ <br><strong>ออมทรัพย์<br>1472520569</strong><br>กฤตพล มโนโชคกวินสกุล</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="col-12  mt-2">
                            <div class="row">
                                <div class="col-3 mt-3" >
                                    <img src="{{ asset('/image/bank/KBNK.jpg') }}" style="width: 100%;border-radius: 50%;"/>
                                </div>
                                <div class="col-7" >
                                    <p style="margin: 15px;">ธนาคาร กสิกรไทย <br><strong>ออมทรัพย์<br>1612839286</strong><br>กฤตพล มโนโชคกวินสกุล</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="mt-2 my-3" style="border: 1px solid #000">
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    let selectedItemsTableBody = document.querySelector('#selected-items-table-room tbody');
    let displaySelectedItemsTableBody = document.querySelector('#display-selected-items tbody');
    let saveButton = document.querySelector('#save-button');
    let totalAmountCell = document.getElementById('total-amount');
    let TotleDiscountCell = document.getElementById('total-Discount');
    let NetTotalCell = document.getElementById('Net-Total');
    let totalVatCell = document.getElementById('total-Vat');
    let NetpriceCell = document.getElementById('Net-price');
    let selectedIndex = 1;

    // Function to update index
    function updateIndex() {
        let rows = selectedItemsTableBody.querySelectorAll('tr');
        rows.forEach((row, index) => {
            row.cells[0].innerText = index + 1;
        });
    }

    // Function to update total amount
    function updateTotal() {
    let itemTotal = 0;
    let totalAmount =0;
    let itemDiscount = 0;
    let netprice=0;
    let TotleDiscount = 0;
    let TotalVat =0;
    let totalVat =0;
    let Netprice =0;
    let nettotal=0;
    let Nettotal =0;
        document.querySelectorAll('#display-selected-items tbody tr').forEach(row => {
            const productPaxCell = row.cells[4];
            productPax = parseFloat(productPaxCell.innerText.trim().replace(/,/g, ''));
            const quantity = parseInt(row.querySelector('.countroom').value) || 0;
            const discount = parseInt(row.querySelector('.discount').value) || 0;
            console.log(discount);
            const itemTotal = productPax * quantity;
            const itemDiscount = discount* itemTotal /100;
            const netprice = itemTotal-itemDiscount;
            const TotalVat = netprice * 7 / 100;
            const nettotal = netprice +TotalVat;
            row.querySelector('.net-price').innerText = Math.round(netprice).toLocaleString();
            row.querySelector('.item-total').innerText = Math.round(itemTotal).toLocaleString();
            const netPriceInput = row.querySelector('input.net-price-product');
            const totalPriceInput = row.querySelector('input.total-price-product');

            if (netPriceInput == null) {
                const newInput = document.createElement('input');
                newInput.type = 'hidden';
                newInput.classList.add('net-price-product');
                newInput.name = 'net-price-product[]';
                newInput.value = netprice;
                row.querySelector('.net-price').appendChild(newInput);
                console.log(newInput.value);
            } else {
                netPriceInput.value = netprice;
                console.log(netPriceInput.value);
            }
            if (totalPriceInput == null) {
                const totalInput = document.createElement('input');
                totalInput.type = 'hidden';
                totalInput.classList.add('total-price-product');
                totalInput.name = 'total-price-product[]';
                totalInput.value = itemTotal;
                row.querySelector('.item-total').appendChild(totalInput);
                console.log(totalInput.value);
            } else {
                totalPriceInput.value = itemTotal;
                console.log(netPriceInput.value);
            }

            totalAmount += itemTotal;
            TotleDiscount += itemDiscount;
            totalVat +=TotalVat;
            Netprice +=netprice;
            Nettotal +=nettotal;
        });
        totalAmountCell.innerText = totalAmount.toLocaleString();
        NetpriceCell.innerText = Netprice.toLocaleString();
        TotleDiscountCell.innerText = Math.round(TotleDiscount);
        NetTotalCell.innerText = Nettotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });// Update total amount in footer without decimals
        totalVatCell.innerText = totalVat.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });// Update total amount in footer without decimals
    }
    // Add event listener to save button
    saveButton.addEventListener('click', function () {
        displaySelectedItemsTableBody.innerHTML = '';
        let rows = selectedItemsTableBody.querySelectorAll('tr');
        rows.forEach((row, index) => {
            let cells = row.querySelectorAll('td');
            let newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${index + 1}</td>
                <td><input type="hidden" id="ProductID" name="ProductID[]" value="${cells[1].innerText}">${cells[1].innerText}</td>
                <td style="text-align:left;">${cells[2].innerText}</td>
                <td><input type="number" class="countroom" name="countroom[]" value="1" min="1"></td>
                <td>${cells[4].innerText}<input type="hidden" class="price-product" name="price-product[]" value="${cells[4].innerText}"</td>
                <td><input type="number" class="discount" name="discount[]" value="1" min="1"></td>
                <td>${cells[3].innerText}</td>
               <td class="net-price">
                        <input type="hidden" class="net-price-product" name="net-price-product[]" value="">
                        0
                    </td>
                <td class="item-total">
                    <input type="hidden" class="total-price-product" name="total-price-product[]" value="">
                    0</td>
                <td><button type="button" class="Btn remove-button"><svg viewBox="0 0 15 17.5" height="17.5" width="15" xmlns="http://www.w3.org/2000/svg" class="icon">
                    <path transform="translate(-2.5 -1.25)" d="M15,18.75H5A1.251,1.251,0,0,1,3.75,17.5V5H2.5V3.75h15V5H16.25V17.5A1.251,1.251,0,0,1,15,18.75ZM5,5V17.5H15V5Zm7.5,10H11.25V7.5H12.5V15ZM8.75,15H7.5V7.5H8.75V15ZM12.5,2.5h-5V1.25h5V2.5Z" id="Fill"></path>
                    </svg></button>
                </td>
            `;
            displaySelectedItemsTableBody.appendChild(newRow);

            newRow.querySelector('.countroom').addEventListener('input', updateTotal);
            newRow.querySelector('.discount').addEventListener('input', updateTotal);
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

        updateTotal();
    });
    document.addEventListener('click', function (e) {
    if (e.target && e.target.classList.contains('select-button-room')) {
        let button = e.target;
        let productId = button.getAttribute('data-id');
        let productName = button.getAttribute('data-name');
        let productDescription = button.getAttribute('data-description');
        let productUnit = button.getAttribute('data-unit');
        let productPax = button.getAttribute('data-pax');
        let productPrice = button.getAttribute('data-price');
        let isDuplicate = false;

        // Check if the product already exists in the table
        document.querySelectorAll('#selected-items-table-room tbody tr').forEach(row => {
            let existingProductId = row.cells[1].innerText;
            if (existingProductId === productId) {
                isDuplicate = true;
            }
        });

        if (!isDuplicate) {
            let newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${selectedIndex}</td>
                <td>${productId}</td>
                <td style="text-align: left;">${productName}</td>
                <td>${productUnit}</td>
                <td>${productPrice}</td>
                <td><button type="button" class="Btn  remove-button"><svg viewBox="0 0 15 17.5" height="17.5" width="15" xmlns="http://www.w3.org/2000/svg" class="icon">
                    <path transform="translate(-2.5 -1.25)" d="M15,18.75H5A1.251,1.251,0,0,1,3.75,17.5V5H2.5V3.75h15V5H16.25V17.5A1.251,1.251,0,0,1,15,18.75ZM5,5V17.5H15V5Zm7.5,10H11.25V7.5H12.5V15ZM8.75,15H7.5V7.5H8.75V15ZM12.5,2.5h-5V1.25h5V2.5Z" id="Fill"></path>
                    </svg></button>
                </td>
            `;

            selectedItemsTableBody.appendChild(newRow);

            let removeButton = newRow.querySelector('.remove-button');
            removeButton.addEventListener('click', function () {
                selectedItemsTableBody.removeChild(newRow);
                selectedIndex--; // Adjust index if needed
                updateIndex(); // Function to update index after removal
            });

            selectedIndex++;
        } else {
            alert('This product is already added to the list.');
        }
    }
});
});
$(document).ready(function() {
    let products = []; // Array to hold all product data
    let units = [];
    let quantities = [];
    let currentPage = 1;
    const itemsPerPage = 10;

    function fetchProducts(selectedValue) {
        var Quotation_ID = '{{ $Quotation->Quotation_ID }}'; // Replace this with the actual ID you want to send

        $.ajax({
            url: '{{ route("Quotation.addProduct", ["Quotation_ID" => ":id"]) }}'.replace(':id', Quotation_ID),
            method: 'GET',
            data: {
                value: selectedValue
            },
            success: function(response) {
                console.log(response);
                products = response.products; // Store products data
                units = response.units;
                quantities = response.quantitys;
                displayProducts(currentPage); // Display the first page of products
                setupPagination(products.length);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    function displayProducts(page) {
        $('#product-container').empty();
        currentPage = page;

        const startIndex = (page - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const paginatedItems = products.slice(startIndex, endIndex);

        paginatedItems.forEach(function(product, index) {
            var unitMatch = units.find(unit => String(unit.id) === String(product.unit));
            var quantityMatch = quantities.find(quantity => String(quantity.id) === String(product.quantity));
            var quantityName = quantityMatch?.name_th || '';
            var unitName = unitMatch?.name_th || '';
            var paxContent = product.pax !== null ? product.pax : '';
            var row = `
                <tr>
                    <th scope="row">${startIndex + index + 1}</th>
                    <td>${product.Product_ID}</td>
                    <td style="text-align:left;">${product.name_en}<br><p style=" font-size:14px;color:#BEBEBE">${product.detail_en}</p></td>
                    <td>${unitName}</td>
                    <td>${product.normal_price}</td>
                    <td>
                       <button type="button" style="background-color: #109699; display: block; margin: 0 auto;" class="button-11 select-button-room"
                        data-id="${product.Product_ID}" data-name="${product.name_en}" data-description="${product.detail_en}" data-unit="${unitName}"
                        data-pax="${product.pax}" data-price="${product.normal_price}" data-discount="${product.maximum_discount}">+</button>

                    </td>
                </tr>
            `;
            $('#product-container').append(row);
        });
    }

    function setupPagination(totalItems) {
        $('#pagination').empty();
        const totalPages = Math.ceil(totalItems / itemsPerPage);

        for (let i = 1; i <= totalPages; i++) {
            let li = $('<li class="page-item"><a class="page-link" href="#">' + i + '</a></li>');
            if (i === currentPage) li.addClass('active');

            li.click(function(e) {
                e.preventDefault();
                displayProducts(i);
                $('#pagination .page-item').removeClass('active');
                li.addClass('active');
            });

            $('#pagination').append(li);
        }
    }

    $('#addproduct').click(function(e) {
        e.preventDefault();
        fetchProducts('all');
    });

    $('.dropdown-item').click(function(e) {
        e.preventDefault();
        var selectedValue = $(this).data('value');
        fetchProducts(selectedValue);
    });
});


</script>

@endsection
