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
.com {
    display: inline-block;  /* ทำให้ border-bottom มีความยาวเท่ากับข้อความ */
    border-bottom: 2px solid #2D7F7B;  /* กำหนดเส้นใต้ */
    padding-bottom: 5px;
    font-size: 20px;
}
.Profile{
    width: 15%;
}
.styled-hr {
    border: none; /* เอาขอบออก */
    border: 1px solid #2D7F7B; /* กำหนดระยะห่างด้านล่าง */
}
.table-borderless1{
        border-radius: 6px;
        background-color: #109699;
        color:#fff;
    }
    .centered-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .centered-content4 {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        border: 2px solid #6b6b6b; /* กำหนดกรอบสี่เหลี่ยม */
        padding: 20px; /* เพิ่ม padding ภายในกรอบ */
        border-radius: 5px; /* เพิ่มมุมโค้ง (ถ้าต้องการ) */
        height: 150px;
        width: 150px; /* กำหนดความสูงของกรอบ */
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
    .Profile{
    width: 100%;
    }
}
</style>
@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to add Product to Proposal.</small>
                <h1 class="h4 mt-1">Add Product to Proposal (เพิ่มโปรดักส์ลงข้อเสนอ)</h1>
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
                    <form id="myForm" action="{{url('/Quotation/company/create/quotation/'.$Quotation->Quotation_ID)}}" method="POST"enctype="multipart/form-data">
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
                                    <div class="col-lg-12  d-flex justify-content-center" >
                                        <div style="background-color: rgba(45, 127, 123, 1);border:1px solid rgba(45, 127, 123, 1);  width: 70%; border-radius: 4px;" >
                                            <b class="titleQuotation" style="font-size: 28px;color:#fff;">Proposal</b>
                                        </div>
                                    </div>
                                    <div class="col-lg-12  d-flex justify-content-center mt-2" >
                                        <div style="border:1px solid rgba(45, 127, 123, 1);  width: 70%;border-radius: 4px; ">
                                            <span class="titleQuotation">{{$Quotation_ID}}</span>
                                            <input type="hidden" id="Quotation_ID" name="Quotation_ID" value="{{$Quotation_ID}}">
                                            <div id="reportrange1" style="background: #fff; cursor: pointer; padding: 5px 10px; width: 100%;" >
                                                <div class="col-12 col-md-12 col-sm-12">
                                                    <div class="row">
                                                        <div class="col-9"style="display:flex; justify-content:right; align-items:center;">
                                                            <span>Issue Date: {{ $Quotation->issue_date }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-12 col-sm-12 mt-2">
                                                    <div class="row">
                                                        <div class="col-9"style="display:flex; justify-content:right; align-items:center;">
                                                            <span>Expiration Date: {{ $Quotation->Expirationdate }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-lg-12 col-md-12 col-sm-12 ">
                                <div class="Profile" style="background-color: rgba(45, 127, 123, 1);border:1px solid rgba(45, 127, 123, 1);  border-radius: 4px;">
                                    <b class="titleQuotation" style="font-size: 20px;color:#fff;">Profile ID : {{ $Quotation->Company_ID }} </b>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-6 col-md-12 col-sm-12" style=" border-right-style: solid  ; border-right-width: 2px;border-right-color:#109699">
                                <p class="com mt-2">Company Information</p>
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
                            <div class="col-lg-6 col-md-12 col-sm-12">
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
                            <div class="styled-hr"></div>
                        </div>
                        
                        <div class="mt-2">
                            <strong>ขอเสนอราคาและเงื่อนไขสำหรับท่าน ดังนี้ <br> We are pleased to submit you the following desctibed here in as price,items and terms stated :</strong>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-2 col-md-12 col-sm-12">
                                <button  id="addproduct" type="button" class="btn btn-color-green lift btn_modal my-3" data-bs-toggle="modal" data-bs-target="#exampleModalproduct"onclick="fetchProducts('all')">
                                    <i class="fa fa-plus"></i> Add Product</button>
                            </div>
                            <div class="modal fade" id="exampleModalproduct" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header btn-color-green ">
                                        <h5 class="modal-title text-white" id="exampleModalLabel">Product</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-12 mt-3">
                                            <div class="dropdown">
                                                <button class="btn btn-outline-dark lift dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                    ประเภท Product
                                                </button>
                                                <ul class="dropdown-menu border-0 shadow p-3">
                                                    <li><a class="dropdown-item py-2 rounded" data-value="all" onclick="fetchProducts('all')">All Product</a></li>
                                                    <li><a class="dropdown-item py-2 rounded" data-value="Room_Type"onclick="fetchProducts('Room_Type')">Room</a></li>
                                                    <li><a class="dropdown-item py-2 rounded" data-value="Banquet"onclick="fetchProducts('Banquet')">Banquet</a></li>
                                                    <li><a class="dropdown-item py-2 rounded" data-value="Meals"onclick="fetchProducts('Meals')">Meal</a></li>
                                                    <li><a class="dropdown-item py-2 rounded" data-value="Entertainment"onclick="fetchProducts('Entertainment')">Entertainment</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <hr class="mt-3 my-3" style="border: 1px solid #000">
                                            <table  class="myDataTableQuotationmodal table table-hover align-middle mb-0" style="width:100%">
                                                <thead >
                                                    <tr>
                                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 10%">#</th>
                                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 10%">รหัส</th>
                                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">รายการ</th>
                                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 10%">หน่วย</th>
                                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 10%">ราคา</th>
                                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 5%">คำสั่ง</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="product-list">

                                                </tbody>
                                            </table>
                                        <div class="col-12 mt-3">
                                            <h3>รายการที่เลือก</h3>
                                            <table  class="table table-hover align-middle mb-0">
                                                <thead >
                                                    <tr>
                                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 10%">#</th>
                                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 10%">รหัส</th>
                                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">รายการ</th>
                                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 10%">หน่วย</th>
                                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 10%">ราคา</th>
                                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width: 5%">คำสั่ง</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="product-list-select">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary lift" data-bs-dismiss="modal">ยกเลิก</button>
                                        <button type="button" class="btn btn-color-green lift confirm-button" id="confirm-button">สร้าง</button>
                                    </div>
                                </div>
                                </div>
                                <div id="modalOverlay" class="modal-overlay"></div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <table class=" table table-hover align-middle mb-0" style="width:100%">
                                <thead >
                                    <tr>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">No.</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">Description</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:10%;">Quantity</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">Unit</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">Price / Unit</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;width:10%;">Discount</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">Net price/Unit</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">Amount</th>
                                        <th style="background-color: rgba(45, 127, 123, 1); color:#fff;">Order</th>
                                    </tr>
                                </thead>
                                <tbody id="display-selected-items">

                                </tbody>
                            </table>
                        </div>
                        <input type="hidden" name="adult" id="adult" value="{{$Quotation->adult}}">
                        <input type="hidden" name="children" id="children" value="{{$Quotation->children}}">
                        <div class="col-12 row ">
                            <div class="col-lg-8 col-md-8 col-sm-12 mt-2" >
                                <span >Notes or Special Comment</span>
                                <textarea class="form-control mt-2"cols="30" rows="5"name="comment" id="comment" placeholder="Leave a comment here" id="floatingTextarea"></textarea>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12 " >
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr >
                                            
                                            <td scope="row"style="text-align:right;width: 55%;"><b>Total Amount</b></td>
                                            <td style="text-align:left;width: 45%;"><span id="total-amount">0</span></td>
                                        </tr>
                                        <tr>
                                            
                                            <td scope="row"style="text-align:right;width: 55%;"><b>Discount (%)</b></td>
                                            <td style="text-align:left;width: 45%;"><span id="total-Discount">0</span></td>
                                        </tr>
                                        <tr>
                                            
                                            <td scope="row"style="text-align:right;width: 55%;"><b>Net price</b></td>
                                            <td style="text-align:left;width: 45%;"><span id="Net-price">0</span></td>
                                        </tr>
                                        <tr>
                                            
                                            <td scope="row" style="text-align:right;width: 55%;"><b>Value Added Tax</b></td>
                                            <td style="text-align:left;width: 45%;"><span id="total-Vat">0</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 row">
                            <div class="col-8">
            
                            </div>
                            <div class="col-lg-4 col-md-3 col-sm-12">
                                <table class="table table-borderless1" >
                                    <tbody>
                                        <tr>
                                            <td style=" text-align:right;background-color: #109699;color:#fff;width: 55%;"><b>Net Total (฿)</b></td>
                                            <td style=" text-align:left;background-color: #109699;color:#fff;width: 45%;"><span id="Net-Total">0</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 row">
                            <div class="col-8"></div>
                            <div class="col-4 styled-hr"></div>
                        </div>
                        <div class="col-12 row">
                            <div class="col-8">
                            </div>
                            <div class="col-lg-4 col-md-3 col-sm-12">
                                <table class="table table-borderless" >
                                    <tbody>
                                        <tr>
                                            <td style="text-align:right;width: 55%;"><b>Average per person</b></td>
                                            <td style="text-align:left;width: 45%;"><span id="Average">0</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <strong class="titleh1">Method of Payment</strong>
                            </div>
                            <div class="styled-hr my-3"></div>
                            <span class="col-md-6 col-sm-12">
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
                                            <div class="col-7 mt-4">
                                                <strong>The Siam Commercial Bank Public Company Limited <br>Bank Account No. 708-226791-3<br>Tha Yang - Phetchaburi Branch (Savings Account)</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="styled-hr mt-3"></div>
                        <div class="col-12 mt-2">
                            <div class="col-4">
                                <strong class="titleh1">รับรอง</strong>
                            </div>
                            <div class="col-12 my-2">
                                <div class="row">
                                    <div class="col-lg-2 centered-content">
                                        <span>สแกนเพื่อเปิดด้วยเว็บไซต์</span>
                                        @php
                                            $id = $Quotation->id;
                                            $gethttp =(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http";
                                            $linkQR = $gethttp."://".$_SERVER['HTTP_HOST']."/quotation-preview-export/$id?page_shop=".@$_GET['page_shop'];
                                        @endphp
                                        <img src="data:image/png;base64,{{DNS2D::getBarcodePNG($linkQR,'QRCODE') }}" width="120" height="120"/></td>
                                    </div>
                                    <div class="col-lg-2 centered-content">
                                        <span>ผู้ออกเอกสาร (ผู้ขาย)</span><br>
                                        <br><br><br>
                                        <span>{{@$Quotation->user->name}}</span>
                                        <span>{{ $Quotation->issue_date }}</span>
                                    </div>
                                    <div class="col-lg-2 centered-content">
                                        <span>ผู้อนุมัติเอกสาร (ผู้ขาย)</span><br>
                                        <br><br><br>
                                        <span>{{@$Quotation->user->name}}</span>
                                        <span>{{ $Quotation->issue_date }}</span>
                                    </div>
                                    <div class="col-lg-2 centered-content">
                                        <span>ตราประทับ (ผู้ขาย)</span>
                                    </div>
                                    <div class="col-lg-2 centered-content">
                                        <span>ผู้รับเอกสาร (ลูกค้า)</span>
                                        <br><br><br>
                                            ---------------------
                                        <span>{{ $Company_ID->Company_Name }}</span>
                                    </div>
                                    <div class="col-lg-2 centered-content">
                                        <span>ตราประทับ (ลูกค้า)</span>
                                        <div class="centered-content4 mt-1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="styled-hr mt-3"></div>
                        <div class="col-12 row mt-5">
                            <div class="col-4"></div>
                            <div class="col-4 "  style="display:flex; justify-content:center; align-items:center;">
                                <button type="submit" class="btn btn-color-green lift btn_modal">บันทึกใบเสนอราคา</button>
                            </div>
                            <div class="col-4"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script>
    function fetchProducts(status) {
        var table = $('.myDataTableQuotationmodal').DataTable();
        var Quotation_ID = '{{ $Quotation->Quotation_ID }}'; // Replace this with the actual ID you want to send
        var clickCounter = 1;
        $.ajax({
            url: '{{ route("Quotation.addProduct", ["Quotation_ID" => ":id"]) }}'.replace(':id', Quotation_ID),
            method: 'GET',
            data: {
                value: status
            },
            success: function(response) {
                if (response.products.length > 0) {
                    // Clear the existing rows
                    table.clear();

                    for (let i = 0; i < response.products.length; i++) {
                        const data = response.products[i];
                        const productId = data.id;
                        table.row.add([
                            i + 1,
                            data.Product_ID,
                            data.name_th,
                            data.unit_name,
                            data.normal_price,
                            `<button type="button"  class="btn btn-color-green lift btn_modal select-button-product" id="product-${data.id}" value="${data.id}"><i class="fa fa-plus"></i></button>`
                        ]).node().id = `row-${productId}`;
                    }
                    table.draw(false);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
        $(document).on('click', '.select-button-product', function() {
            var table = $('.product-list-select').DataTable();
            var product = $(this).val();
            if ($('#productselect' + product).length > 0) {
                return;
            }
            $.ajax({
                url: '{{ route("Quotation.addProductselect", ["Quotation_ID" => ":id"]) }}'.replace(':id', Quotation_ID),
                method: 'GET',
                data: {
                    value:product
                },
                success: function(response) {
                    $.each(response.products, function(index, val) {
                        var name = '';
                        var price = 0;
                        var rowNumber = $('#product-list-select tr').length+1;
                        $('#product-list-select').append(
                            '<tr id="tr-select-add' + val.id + '">' +
                            '<td>' + rowNumber + '</td>' +
                            '<td><input type="hidden" class="randomKey" name="randomKey" id="randomKey" value="' + val.Product_ID + '">' + val.Product_ID + '</td>' +
                            '<td style="text-align:left;">' + val.name_en + '</td>' +
                            '<td style="text-align:right;">' + val.unit_name + '</td>' +
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
        function renumberRows() {
            $('#product-list-select tr').each(function(index) {
                $(this).find('td:first-child').text(index+1); // เปลี่ยนเลขลำดับในคอลัมน์แรก
            });
            $('#display-selected-items tr').each(function(index) {
                $(this).find('td:first-child').text(index+1); // เปลี่ยนเลขลำดับในคอลัมน์แรก
            });
        }
        $(document).on('click', '.remove-button', function() {
            console.log(1);
            var product = $(this).val();
            $('#tr-select-add' + product).remove();
            renumberRows(); // ลบแถวที่มี id เป็น 'tr-select-add' + product
        });
        $(document).on('click', '.confirm-button', function() {
            var product = $(this).val();
            var number = $('#randomKey').val();
            console.log(number);
            $.ajax({
                url: '{{ route("Quotation.addProducttablecreatemain", ["Quotation_ID" => ":id"]) }}'.replace(':id', Quotation_ID),
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
                                var rowNumbemain = $('#display-selected-items tr').length+1;
                                $('#display-selected-items').append(
                                    '<tr id="tr-select-addmain' + val.id + '">' +
                                    '<td>' + rowNumbemain + '</td>' +
                                    '<td style="text-align:left;"><input type="hidden" id="Product_ID" name="Product_ID[]" value="' + val.Product_ID + '">' + val.name_en + '</td>' +
                                    '<td><input class="quantitymain form-control" type="text" id="quantitymain" name="quantitymain[]" value="1" min="1" rel="' + number + '" style="text-align:center;"></td>' +
                                    '<td>' + val.unit_name + '</td>' +
                                    '<td><input type="hidden" id="totalprice-unit-' + number + '" name="price-unit[]" value="' + val.normal_price + '">' + val.normal_price + '</td>' +
                                    '<td><input class="discountmain form-control" type="text" id="discountmain" name="discountmain[]" value="1" min="1" res="' + number + '" style="text-align:center;"></td>' +
                                    '<td><input type="hidden" id="net_discount-' + number + '"  value="' + val.normal_price + '"><span id="netdiscount' + number + '">' + netDiscount + '</span></td>' +
                                    '<td><input type="hidden" id="allcounttotal-' + number + '"  value=" '+ val.normal_price +'"><span id="allcount' + number + '">' + val.normal_price + '</span></td>' +
                                    '<td><button type="button" class="Btn remove-buttonmain" value="' + val.id + '"><svg viewBox="0 0 15 17.5" height="17.5" width="15" xmlns="http://www.w3.org/2000/svg" class="icon"><path transform="translate(-2.5 -1.25)" d="M15,18.75H5A1.251,1.251,0,0,1,3.75,17.5V5H2.5V3.75h15V5H16.25V17.5A1.251,1.251,0,0,1,15,18.75ZM5,5V17.5H15V5Zm7.5,10H11.25V7.5H12.5V15ZM8.75,15H7.5V7.5H8.75V15ZM12.5,2.5h-5V1.25h5V2.5Z" id="Fill"></path></svg></button></td>' +
                                    '</tr>'
                                );
                            }
                        }
                    });
                    totalAmost();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });
        $(document).on('click', '.remove-buttonmain', function() {
            var product = $(this).val();
            $('#tr-select-add' + product + ', #tr-select-addmain' + product).remove();

            $('#display-selected-items tbody tr').each(function(index) {
                // เปลี่ยนเลขลำดับใหม่
                $(this).find('td:first').text(index + 1);
            });
            renumberRows();
            totalAmost();// ลบแถวที่มี id เป็น 'tr-select-add' + product
        });
    }
    //----------------------------------------รายการ---------------------------
    $(document).ready(function() {
        $(document).on('keyup', '.quantitymain', function() {
            var quantitymain =  Number($(this).val());
            console.log(quantitymain);
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
    });

    function totalAmost() {
        $(document).ready(function() {
            
            let allprice = 0;
            let allpricedis = 0;
            let discounttotal =0;
            let vattotal =0;
            let nettotal =0;
            let totalperson=0;
            let priceArray = [];
            let pricedistotal = [];// เริ่มต้นตัวแปร allprice และ allpricedis ที่นอกลูป
            $('#display-selected-items tr').each(function() {
                
                var adultValue = parseFloat(document.getElementById('adult').value);
                var childrenValue = parseFloat(document.getElementById('children').value);
                let priceCell = $(this).find('td').eq(7);
                let pricetotal = parseInt(priceCell.text().replace(/,/g, '')) || 0;
                allprice += pricetotal;
                console.log(adultValue);
                console.log(childrenValue);
                let pricedisCell = $(this).find('td').eq(6);
                let pricedistotal = parseInt(pricedisCell.text().replace(/,/g, '')) || 0; // แปลงข้อความในเซลล์เป็นจำนวนเต็ม และจัดการค่า NaN
                allpricedis += pricedistotal;
                discounttotal = allprice-allpricedis;
                vattotal = allpricedis*7/100;
                nettotal = allpricedis+vattotal;
                var person =adultValue+childrenValue;
                totalperson = nettotal/person;
               
            });
            $('#total-amount').text(isNaN(allprice) ? '0' : allprice.toLocaleString('th-TH', {minimumFractionDigits: 2}));
            $('#total-Discount').text(isNaN(discounttotal) ? '0' : discounttotal.toLocaleString('th-TH', {minimumFractionDigits: 2}));
            $('#Net-price').text(isNaN(allpricedis) ? '0' : allpricedis.toLocaleString('th-TH', {minimumFractionDigits: 2}));
            $('#total-Vat').text(isNaN(vattotal) ? '0' : vattotal.toLocaleString('th-TH', {minimumFractionDigits: 2}));
            $('#Net-Total').text(isNaN(nettotal) ? '0' : nettotal.toLocaleString('th-TH', {minimumFractionDigits: 2}));
            $('#Average').text(isNaN(totalperson) ? '0' : totalperson.toLocaleString('th-TH', {minimumFractionDigits: 2}));
        });
    }
    totalAmost();
</script>

@endsection
