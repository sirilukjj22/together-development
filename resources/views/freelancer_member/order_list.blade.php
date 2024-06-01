@extends('layouts.test')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<style>
    .Usertablecontainer {
        width: 90%;
        display: block;
        margin: auto;
        margin-top: 40px;
        background-color: white;
        padding: 5% 10%;
    }
    .titleh1 {
        font-size: 32px;
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
        input[type=email], select {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        }
        input[type=tel], select {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        }
        input[type=number], select {
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
        background-color: #f8f8f8; /* เพิ่มสีพื้นหลัง */
    }
    .button-guest{
        background-color: #2D7F7B;
        color: whitesmoke;
        border-color: #9a9a9a;
        border-style: solid;
        width: 30%;
        border-width: 1px;
        border-radius: 8px;
        float: right;
        margin-Top: 10px;
        margin-Left: 100px;
        text-align: center;

    }
    .button-guest-end{
        background-color:#ff0000;
        color: whitesmoke;
        border-color: #9a9a9a;
        border-style: solid;
        width: 30%;
        float: left;
        border-width: 1px;
        border-radius: 8px;
        margin-Top: 10px;
        text-align: center;

    }
    .textarea{
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .table-wrapper {
            max-height: 400px; /* กำหนดความสูงที่ต้องการ */
            overflow-y: auto; /* เพิ่ม Scrollbar แนวตั้ง */
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .modal-body-split {
            max-height: auto; /* Adjust the height as needed */
            overflow-y: auto; /* Enable vertical scrollbar */
        }
        textarea {
            resize: none;
        }
        .add-input {
            /* เพิ่มสไตล์ที่คุณต้องการในส่วนนี้ */
            color: #fff;
            background-color: #28a745;
            border-color: #28a745;
            border-radius: 0.25rem;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            cursor: pointer;
        }

        .add-input:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .add-input:disabled {
            background-color: #6c757d;
            border-color: #6c757d;
            cursor: not-allowed;
        }

        /* สไตล์สำหรับปุ่ม "Add Fax" */
        .add-fax {
            /* เพิ่มสไตล์ที่คุณต้องการในส่วนนี้ */
            color: #fff;
            background-color: #28a745;
            border-color: #28a745;
            border-radius: 0.25rem;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            cursor: pointer;
        }

        .add-fax:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .add-fax:disabled {
            background-color: #6c757d;
            border-color: #6c757d;
            cursor: not-allowed;
        }
        .remove-input,
        .remove-fax
        {
            /* เพิ่มสไตล์ที่คุณต้องการในส่วนนี้ */
            color: #fff;
            background-color: #dc3545;
            /* สีแดง */
            border-color: #dc3545;
            /* สีเหลือง */
            border-radius: 0.25rem;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            cursor: not-allowed;
        }
        .Usertable{
            display: block;
            margin: auto;
            width: 100%;
            border-style: solid;
            border-radius: 8px;
            border-width: 1px;
            border-color: #9a9a9a;
            background-color: white;
            padding: 10px;
            margin-top: 40px;

        }

        .modal-body {
            display: auto;
            flex-direction: column;
            align-items: center;
        }
        .carousel-item img {
            margin-bottom: 90px; /* Adjust as needed */
        }
        .carousel-item {
            text-align: center;
        }
        .carousel-caption {
            background-color: rgba(0, 0, 0, 0.5);
            color: #fff;
            padding: 20px;
            border-radius: 10px;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
        }

        .carousel-content {
            margin-top: 20px;
        }
        .carousel-caption {
            margin-bottom: 20px; /* Adjust as needed */
            text-align: left; /* Ensure text alignment is left */
        }



</style>
<div class="Usertablecontainer">
    <div class="row">
        <div class="titleh1 col-lg-6 col-md-6 col-sm-6">
            <h1>Company & Agent Form</h1>
        </div>
    </div>
    <form id="selected-items-form"action="{{url('/Freelancer/member/quotation/save/'.$Freelancer_member->id)}}" method="POST"enctype="multipart/form-data">
        @csrf
        <div class="Usertable">
            <div class="col-12 row" >
                <div class="col-1"></div>
                <div class="col-10 " >
                    <div class="row">
                        <div class="col-4" >
                            <label >Company Name</label><br>
                            <input type="text" id="Company_Name" name="Company_Name"maxlength="70" required>
                        </div>
                        <div class="col-4" >
                            <label for="Branch">Company Branch</label><br>
                            <input type="text" id="Branch" name="Branch"maxlength="70" required>
                        </div>
                        <div class="col-4" >
                            <label >Contact Name</label><br>
                            <input type="text" id="Contact_Name" name="Contact_Name"maxlength="70" required>
                        </div>
                </div>
                </div>
            </div>
            <div class="col-12 row" >
                <div class="col-1" ></div>
                <div class="col-10 " >
                    <div class="row">
                        <div class="col-4">
                            <label for="Market" >Market</label><br>
                            <select name="Mmarket" id="Mmarket" class="form-select" required>
                                <option value=""></option>
                                    @foreach($Mmarket as $item)
                                        <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="col-4" >
                            <label for="booking_channel">Booking Channel:</label><br>
                            <select name="booking_channel" id = "booking_channel" class="select2" required>
                                @foreach($booking_channel as $item)
                                    <option value="{{ $item->id }}">{{ $item->name_en }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4" >
                            <label for="country">Country</label><br>
                            <select name="countrydata" id="countrySelect" class="form-select" onchange="showcityInput()"required>
                                <option value="Thailand">ประเทศไทย</option>
                                <option value="Other_countries">ประเทศอื่นๆ</option>
                            </select>
                        </div>
                </div>
                </div>
            </div>
            <div class="col-12 row" >
                <div class="col-1" ></div>
                <div class="col-10 " >
                    <div class="row">
                        <div class="col-12">
                            <label for="address">Address:</label><br>
                            <textarea type="text" id="address" name="address" rows="3" cols="35" class="textarea" aria-label="With textarea" required></textarea>
                        </div>
                </div>
                </div>
            </div>
            <div class="col-12 row" >
                <div class="col-1" ></div>
                <div class="col-10 " >
                    <div class="row">
                        <div class="col-4"  id="cityInput" style="display:none;border: 2px solid  #000">
                            <label for="city">City</label><br>
                            <input type="text" id="city" name="city">
                        </div>
                        <div class="col-4" >
                            <label for="city">City</label><br>
                            <select name="province" id = "province" class="select2" onchange="select_province()"required>
                                <option value=""></option>
                                @foreach($provinceNames as $item)
                                    <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4" >
                            <label for="Amphures">Amphures</label><br>
                            <select name="amphures" id = "amphures" class="select2"  onchange="select_amphures()"required>
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-4" >
                            <label for="Tambon">Tambon  </label><br>
                            <select name="Tambon" id ="Tambon" class="select2" onchange="select_Tambon()"required>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 row">
                <div class="col-1" ></div>
                <div class="col-10 " >
                    <div class="row">
                        <div class="col-4">
                            <label for="zip_code">Zip Code</label><br>
                            <select name="zip_code" id ="zip_code" class="select2" required>
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-4" >
                            <label >Check In date</label><br>
                            <input type="date" id="Check_In_date" name="Check_In_date" onchange="Onclickreadonly()">
                        </div>
                        <div class="col-4" >
                            <label >Check Out date</label><br>
                            <input type="date" id="Check_Out_date" name="Check_Out_date" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 row" >
                <div class="col-1" ></div>
                <div class="col-10">
                    <div class="row">
                        <div class="col-4" >
                            <label >Pax</label><br>
                            <input type="text" id="Pax" name="Pax" required>
                        </div>
                        <div class="col-4">
                            <label >Company Email</label><br>
                            <input type="email" id="Email" name="Email"maxlength="70" required>
                        </div>
                        <div class="col-4" >
                            <label for="Company_Website" >Company Website</label><br>
                            <input type="text" id="Company_Website" name="Company_Website"maxlength="70" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 row" >
                <div class="col-1" ></div>
                <div class="col-10 " >
                    <div class="row">
                        <div class="col-4" >
                            <label for="Taxpayer_Identification" >Taxpayer Identification Number</label><br>
                            <input type="text" id="Taxpayer_Identification" name="Taxpayer_Identification"maxlength="13" placeholder="เลขประจำตัวผู้เสียภาษี"required>
                        </div>
                        <div class="col-4" >
                            <label for="Company_Phone">Company Phone number</label><br>
                            <div id="inputs-container" class="flex-container">
                                <!-- ตำแหน่งนี้จะใส่ input เดียวในตอนเริ่มต้น -->
                                <div class="phone-group">
                                    <input type="text" name="phone_company[]" class="form-control" maxlength="10"  oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
                                </div>
                            </div>
                            <button type="button" class="add-input" id="add-input">เพิ่ม</button>
                            <button type="button"class=" remove-input" >ลบ</button>
                        </div>
                        <div class="col-4" >
                            <label for="Company_Fax">Company Fax number</label><br>
                            <div id="fax-container" class="flex-container">
                                <!-- ตำแหน่งนี้จะใส่ input เดียวในตอนเริ่มต้น -->
                                <div class="fax-group">
                                    <input type="text" name="fax[]" class="form-control"  oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);"  maxlength="11"required>
                                </div>
                            </div>
                            <button type="button" class="add-fax" id="add-fax">เพิ่ม</button>
                            <button type="button"class="remove-fax" >ลบ</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="Usertable">
            <div class="col-12 row">
                <div class="col-7">
                    <label>
                        ขอรายละเอียดดังนี้
                    </label>
                </div>
                <div class="col-3"></div>
            </div>
            <div class="col-12 row mt-3">{{-- +ห้องพัก modal --}}
                <div class="col-2 ">
                    <button type="button" class="btn button-17 button-18" data-bs-toggle="modal" data-bs-target="#exampleModalRoom">
                        + ห้องพัก
                    </button><br>
                </div>
                <div class="modal fade" id="exampleModalRoom" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">ห้องพัก</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="carouselExample" class="carousel slide">
                                <div class="carousel-inner">
                                    @if (!empty($room_type))
                                        @foreach ($unit as $singleUnit)
                                            @foreach ($room_type as $key => $item)
                                                @if($singleUnit->id == $item->unit)
                                                    <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                                        <div class="image-container">
                                                            <img src="{{ asset($item->image_product) }}" class="image_preview image-with-bottom-border" alt="{{ $item->name_en }}">
                                                        </div>
                                                        <div class="carousel-caption">
                                                            <label>รหัสห้อง : {{ $item->Product_ID }}</label><br>
                                                            <label>รายการ : {{ $item->name_en }}</label><br>
                                                            <label>รายละเอียด : {{ $item->detail_en }}</label><br>
                                                            <label>ความจุ : {{ $item->pax }}</label><br>
                                                            <label>หน่วย : {{ $singleUnit->name_th }}</label><br>
                                                            <button type="button" style="background-color: #109699; display: block; margin: 0 auto;" class="button-10 select-button-room" data-id="{{ $item->Product_ID }}" data-name="{{ $item->name_en }}" data-description="{{ $item->detail_en }}" data-unit="{{ $singleUnit->name_th }}" data-pax="{{ $item->pax }}">Select</button>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @endif
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                            <div class="col-12 row">
                                <h3>รายการที่เลือก</h3>
                                <table id="selected-items-table-room" class="table table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%;">#</th>
                                            <th style="width: 10%;">รหัส</th>
                                            <th>รายการ</th>
                                            <th>รายละเอียด</th>
                                            <th style="width: 10%;">ความจุ</th>
                                            <th scope="col" style="width: 10%;">หน่วย</th>
                                            <th style="width: 15%;">คำสั่ง</th>
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
            <div class="col-12 row mt-3">
                <table id="display-selected-items" class="table table-bordered">
                    <thead>
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
            <div class="col-12 row ">{{-- +ห้องประชุม modal --}}
                <div class="col-2 ">
                    <button type="button" class="btn button-17 button-18" data-bs-toggle="modal" data-bs-target="#exampleModalBanquet">
                        + ห้องประชุม
                    </button><br>
                </div>
                <div class="modal fade" id="exampleModalBanquet" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">ห้องประชุม</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="carouselExampleBanquet" class="carousel slide">
                                <div class="carousel-inner">
                                    @if (!empty($Banquet))
                                        @foreach ($unit as $singleUnit)
                                            @foreach ($Banquet as $key => $item)
                                                @if($singleUnit->id == $item->unit)
                                                    <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                                            <img src="{{ asset($item->image_product) }}" class="d-block w-100 " alt="{{ $item->name_en }}">
                                                        <div class="carousel-caption">
                                                            <label>รหัสห้อง : {{ $item->Product_ID }}</label><br>
                                                            <label>รายการ : {{ $item->name_en }}</label><br>
                                                            <label>รายละเอียด :{{ $item->detail_en }}</label><br>
                                                            <label>ความจุ : {{ $item->pax}}</label><br>
                                                            <label>หน่วย : {{ $singleUnit->name_th }}</label><br>
                                                            <button type="button" style="background-color: #109699;display: block; margin: 0 auto;"class="button-10 select-button-Banquet" data-id="{{ $item->Product_ID }}" data-name="{{ $item->name_en }}" data-description="{{ $item->detail_en }}"data-unit="{{ $singleUnit->name_th }}">Select</button>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @endif
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleBanquet" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleBanquet" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                            <div class="col-12 row">
                                <h3>รายการที่เลือก</h3>
                                <table id="selected-items-table-Banquet" class="table table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%;">#</th>
                                            <th style="width: 10%;">รหัส</th>
                                            <th>รายการ</th>
                                            <th>รายละเอียด</th>
                                            <th style="width: 10%;">หน่วย</th>
                                            <th style="width: 15%;">คำสั่ง</th>
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
                            <button type="button" class="button-10" id="save-button-Banquet" style="background-color: #109699;">Select</button>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-12 row mt-3">
                <table id="display-selected-items-Banquet" class="table table-bordered">
                    <thead>
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
            <div class="col-12 row ">{{-- +ห้องอาหาร modal --}}
                <div class="col-2 ">
                    <button type="button" class="btn button-17 button-18" data-bs-toggle="modal" data-bs-target="#exampleModalMeals">
                        + มื้ออาหาร
                    </button><br>
                </div>
                <div class="modal fade" id="exampleModalMeals" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">มื้ออาหาร</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="carouselExampleMeals" class="carousel slide">
                                <div class="carousel-inner">
                                    @if (!empty($Meals))
                                        @foreach ($unit as $singleUnit)
                                            @foreach ($Meals as $key => $item)
                                                @if($singleUnit->id == $item->unit)
                                                    <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                                        <img src="{{ asset($item->image_product) }}" class="d-block w-100" alt="{{ $item->name_en }}">
                                                        <div class="carousel-caption">
                                                            <label>รหัสห้อง : {{ $item->Product_ID }}</label><br>
                                                            <label>รายการ : {{ $item->name_en }}</label><br>
                                                            <label>รายละเอียด :{{ $item->detail_en }}</label><br>
                                                            <label>ความจุ : {{ $item->pax }}</label><br>
                                                            <label>หน่วย :{{ $singleUnit->name_th }}</label><br>
                                                            <button type="button" style="background-color: #109699;display: block; margin: 0 auto;"class="button-10 select-button-Meals" data-id="{{ $item->Product_ID }}" data-name="{{ $item->name_en }}" data-description="{{ $item->detail_en }}"data-unit="{{ $singleUnit->name_th }}">Select</button>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @endif
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleMeals" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleMeals" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                            <div class="col-12 row">
                                <h3>รายการที่เลือก</h3>
                                <table id="selected-items-table-Meals" class="table table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%;">#</th>
                                            <th style="width: 10%;">รหัส</th>
                                            <th>รายการ</th>
                                            <th>รายละเอียด</th>
                                            <th style="width: 10%;">หน่วย</th>
                                            <th style="width: 15%;">คำสั่ง</th>
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
                            <button type="button" class="button-10"  id="save-button-Meals"  style="background-color: #109699;">Select</button>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-12 row mt-3">
                <table id="display-selected-items-Meals" class="table table-bordered">
                    <thead>
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
            <div class="col-12 row ">
                <div class="col-2">
                    <button type="button" class="btn button-17 button-18" data-bs-toggle="modal" data-bs-target="#exampleModalEntertainment">
                        + ห้องนันทนาการ
                    </button><br>
                </div>
                <div class="modal fade" id="exampleModalEntertainment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">มื้ออาหาร</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="carouselExampleEntertainment" class="carousel slide">
                                <div class="carousel-inner">
                                    @if (!empty($Entertainment))
                                        @foreach ($unit as $singleUnit)
                                            @foreach ($Entertainment as $key => $item)
                                                @if($singleUnit->id == $item->unit)
                                                    <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                                        <img src="{{ asset($item->image_product) }}" class="d-block w-100" alt="{{ $item->name_en }}">
                                                        <div class="carousel-caption">
                                                            <label>รหัสห้อง : {{ $item->Product_ID }}</label><br>
                                                            <label>รายการ : {{ $item->name_en }}</label><br>
                                                            <label>รายละเอียด :{{ $item->detail_en }}</label><br>
                                                            <label>ความจุ : {{ $item->pax }}</label><br>
                                                            <label>หน่วย :
                                                                {{ $singleUnit->name_th }}
                                                            </label><br>
                                                            <button type="button" style="background-color: #109699;display: block; margin: 0 auto; "class="button-10 select-button-Entertainment" data-id="{{ $item->Product_ID }}" data-name="{{ $item->name_en }}" data-description="{{ $item->detail_en }}"data-unit="{{ $singleUnit->name_th }}">Select</button>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @endif
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleEntertainment" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleEntertainment" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                            <div class="col-12 row">
                                <h3>รายการที่เลือก</h3>
                                <table id="selected-items-table-Entertainment" class="table table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%;">#</th>
                                            <th style="width: 10%;">รหัส</th>
                                            <th>รายการ</th>
                                            <th>รายละเอียด</th>
                                            <th style="width: 10%;">หน่วย</th>
                                            <th style="width: 15%;">คำสั่ง</th>
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
            <div class="col-12 row mt-3">
                <table id="display-selected-items-Entertainment" class="table table-bordered">
                    <thead>
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
        </div>
        <div class="col-12 row mt-3">
            <div class="col-2"></div>
            <div class="col-4 d-flex justify-content-end align-items-center" >
                <button type="button" class="button-10" style="background-color: #ff0000;"onclick="window.location.href='{{ route('freelancer_member.view', ['id' => $Freelancer_member->id]) }}'">{{ __('ย้อนกลับ') }}</button>
            </div>

            <div class="col-4 d-flex justify-content-start align-items-center" >
                <button type="submit" class="button-10" style="background-color: #109699;">ส่งรายงาน</button>
            </div>
            <div class="col-2"></div>
        </div>
    </form>
</div>
<script> // ส่วนบริษัท
    function Onclickreadonly(){
        var startDate = document.getElementById('Check_In_date').value;
        if (startDate !== '') {
            console.log(startDate);
            // หากมีค่า กำหนด input field ที่มี id เป็น contract_rate_end_date เป็น readonly
            document.getElementById('Check_Out_date').readOnly = false;
        } else {
            // หากไม่มีค่า กำหนด input field ที่มี id เป็น contract_rate_end_date เป็น readonly
            document.getElementById('Check_Out_date').readOnly = true;
        }
    }
    function toggle(source) {
        checkboxes = document.getElementsByName('dummy');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = source.checked;
        }
    }


    document.addEventListener("DOMContentLoaded", function() {

        const addButton = document.getElementById('add-input');
        const removeButton = document.querySelector('.remove-input');
        const inputsContainer = document.getElementById('inputs-container');

        let inputCount = 1;
        addButton.addEventListener('click', function() {
            const inputGroup = document.createElement('div');
            inputGroup.classList.add('input-group');
            inputGroup.innerHTML = `
            <input type="text" name="phone_company[]" class="form-control" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" >
            `;

            inputsContainer.appendChild(inputGroup);
            inputCount++;
        });

        removeButton.addEventListener('click', function() {
            if (inputCount > 1) {
                const inputGroups = inputsContainer.querySelectorAll('.input-group');
                const lastInputGroup = inputGroups[inputGroups.length - 1];
                inputsContainer.removeChild(lastInputGroup);
                inputCount--;
            }
        });
    });
            //fax
    document.addEventListener("DOMContentLoaded", function() {
        const addButton = document.getElementById('add-fax');
        const removeButton = document.querySelector('.remove-fax');
        const faxContainer = document.getElementById('fax-container');

        let faxCount = 1;

        addButton.addEventListener('click', function() {
            const faxGroup = document.createElement('div');
            faxGroup.classList.add('fax-group');
            faxGroup.innerHTML = `
                <input type="text" name="fax[]" class="form-control"maxlength="11"  oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);" >
            `;
            faxContainer.appendChild(faxGroup);
            faxCount++;
        });

        removeButton.addEventListener('click', function() {
            if (faxCount > 1) {
                const faxGroups = faxContainer.querySelectorAll('.fax-group');
                const lastFaxGroup = faxGroups[faxGroups.length - 1];
                faxContainer.removeChild(lastFaxGroup);
                faxCount--;

            }
        });
    });
    function showcityInput() {
        var countrySelect = document.getElementById("countrySelect");
        var cityInput = document.getElementById("cityInput");
        var citythai = document.getElementById("citythai");
        var amphuresSelect = document.getElementById("amphures");
        var tambonSelect = document.getElementById("Tambon");
        var zipCodeSelect = document.getElementById("zip_code");

        // เช็คค่าที่ถูกเลือกใน dropdown list เมือง
        if (countrySelect.value === "Other_countries") {
            // ถ้าเลือก "Other_countries" แสดง input field สำหรับเมืองอื่นๆ และซ่อน input field สำหรับเมืองไทย
            cityInput.style.display = "block";
            citythai.style.display = "none";
            // ปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
            amphuresSelect.disabled = true;
            tambonSelect.disabled = true;
            zipCodeSelect.disabled = true;
        } else {
            // ถ้าไม่เลือก "Other_countries" ซ่อน input field สำหรับเมืองอื่นๆ และแสดง input field สำหรับเมืองไทย
            cityInput.style.display = "none";
            citythai.style.display = "block";
            // เรียกใช้ฟังก์ชัน select_amphures() เพื่อเปิดการใช้งาน select box ที่มี id เป็น amphures, Tambon, และ zip_code
            select_amphures();
        }
    }


    function select_province(){
        var provinceID = $('#province').val();
        jQuery.ajax({
            type:   "GET",
            url:    "{!! url('/guest/amphures/"+provinceID+"') !!}",
            datatype:   "JSON",
            async:  false,
            success: function(result) {
                jQuery('#amphures').children().remove().end();
                //ตัวแปร
                $('#amphures').append(new Option('', ''));
                jQuery.each(result.data, function(key, value) {
                    var amphures = new Option(value.name_th,value.id);
                    $('#amphures').append(amphures);
                });
            },
        })
    }

    function select_amphures(){
        var amphuresID  = $('#amphures').val();
        $.ajax({
            type:   "GET",
            url:    "{!! url('/guest/Tambon/"+amphuresID+"') !!}",
            datatype:   "JSON",
            async:  false,
            success: function(result) {
                jQuery('#Tambon').children().remove().end();
                $('#Tambon').append(new Option('', ''));
                jQuery.each(result.data, function(key, value) {
                    var Tambon  = new Option(value.name_th,value.id);
                    $('#Tambon ').append(Tambon );
                });
            },
        })
    }
    function select_Tambon(){
        var Tambon  = $('#Tambon').val();
        $.ajax({
            type:   "GET",
            url:    "{!! url('/guest/districts/"+Tambon+"') !!}",
            datatype:   "JSON",
            async:  false,
            success: function(result) {
                jQuery('#zip_code').children().remove().end();
                $('#zip_code').append(new Option('', ''));
                jQuery.each(result.data, function(key, value) {
                    var zip_code  = new Option(value.zip_code,value.zip_code);
                    $('#zip_code ').append(zip_code );
                });
            },
        })
    }
</script>
<script> // ส่วนรายละเอียดลูกค้า
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
        document.body.appendChild(button);
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
                <td>${cells[4].innerText}</td>
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
            let newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>${selectedIndex++}</td>
                <td>${productId}</td>
                <td style="text-align: left;">${productName}</td>
                <td style="text-align: left;">${productDescription}</td>
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
                <td>${cells[4].innerText}</td>
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
                <td>${cells[4].innerText}</td>
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
