@extends('layouts.test')

@section('content')


    <!-- เพิ่มลิงก์ CSS ของ Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
<!-- ลิงก์ JavaScript ของ jQuery -->

<!-- ลิงก์ JavaScript ของ Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<style>
    .image-container {
        width: 100%;
        height: 100%;
        background-size: cover;
        position: relative;
    }
    .container {
        margin-top: 40px;
        background-color: white;
        padding: 5% 5%;
        overflow-x: hidden;
    }
    input[type=text], select {
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
    input[type=tel1], select {
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
    input[type="number"] {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    .button-guest{
        background-color: #2D7F7B;
        color: whitesmoke;
        border-color: #9a9a9a;
        border-style: solid;
        border-width: 1px;
        border-radius: 8px;
        width: 30%;
        padding: 12px 20px;
        margin: 8px 0;
        box-sizing: border-box;

    }
    .button-guest-end{
        background-color: #ff0000;
        color: whitesmoke;
        border-color: #9a9a9a;
        border-style: solid;
        border-width: 1px;
        border-radius: 8px;
        width: 30%;
        padding: 12px 20px;
        margin: 8px 0;
        box-sizing: border-box;
    }
    .textarea{
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    .add-phone {
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

    .add-phone:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    .add-phone:disabled {
        background-color: #6c757d;
        border-color: #6c757d;
        cursor: not-allowed;
    }
    .remove-phoneed{
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
    .remove-phoneed{
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        margin: auto;
        margin-right: 4%;
        margin-top: 10px;
        height: 55%;
        font-size: 14px !important;
        border: none;
        background: #dc3545;
        /* Adjust the button style as needed */
        padding: 0px 20px;
        cursor: pointer;
    }
    .remove-phone {
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
    .remove-phone {
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        margin: auto;
        margin-right: 6%;
        margin-top: 10px;
        height: 50%;
        font-size: 14px !important;
        border: none;
        background: #dc3545;
        /* Adjust the button style as needed */
        padding: 0px 20px;
        cursor: pointer;
    }

    .phone-group {
        position: relative;
    }

    .phone-group input {
        width: 100%;
        padding-right: 40px;
        /* space for the remove button */
    }

    .card {
        width: 200px; /* กำหนดความกว้างตามต้องการ */
        height: 200px; /* กำหนดความสูงตามต้องการ */
        background-color: #fff;
        border: 1px solid #ccc; /* เพิ่มเส้นขอบ */
        border-radius: 10px; /* เพิ่มมุมโค้งมน */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* เพิ่มเงา */
        overflow: hidden; /* ซ่อนส่วนเกิน */
        position: relative; /* สำหรับการวางปุ่ม */
    }



    .image-upload-button {
        position: absolute;
        bottom: 10px; /* ตำแหน่งจากด้านล่าง */
        right: 10px; /* ตำแหน่งจากด้านขวา */
        width: 32px; /* ขนาดของปุ่ม */
        height: 32px; /* ขนาดของปุ่ม */
        background: url('{{ asset('assets2/images/photo-camera.png') }}') no-repeat center center;
        background-size: cover;
        border: none; /* ไม่มีเส้นขอบ */
        border-radius: 50%; /* ทำให้ปุ่มเป็นวงกลม */
        cursor: pointer;* เปลี่ยนรูปแบบของ cursor เมื่อวางเหนือปุ่ม */
        box-shadow: 0 0 5px 2px rgba(255, 255, 255, 0.8);
    }
    .deleteImage{
        position: absolute;
        bottom: 10px; /* ตำแหน่งจากด้านล่าง */
        right: 10px; /* ตำแหน่งจากด้านขวา */
        width: 32px; /* ขนาดของปุ่ม */
        height: 32px; /* ขนาดของปุ่ม */
        background: url('{{ asset('assets2/images/delete.png') }}') no-repeat center center;
        background-size: cover;
        border: none; /* ไม่มีเส้นขอบ */
        border-radius: 50%; /* ทำให้ปุ่มเป็นวงกลม */
        cursor: pointer;* เปลี่ยนรูปแบบของ cursor เมื่อวางเหนือปุ่ม */
        box-shadow: 0 0 5px 2px rgba(255, 255, 255, 0.8);
    }
    .select2-container {
        width: 100% !important;
    }
    .flex-container {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
    }
    .row label {
        margin: 0;
        margin-bottom: 5px;
    }
    .row input {
        margin: 0;
        margin-bottom: 2%;
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
<div class="container-fluid border rounded-3 p-5 mt-3 bg-white" style="width: 98%;">
    <div class="row">
        <div class="usertopic">
            <h1>Registration Request</h1>
        </div>
    </div>
    <br>
    <form id="imageForm" action="{{url('/Freelancer/check/save/update/'.$Freelancer_checked->id)}}" method="POST"enctype="multipart/form-data">
    @csrf
    <div class="col-12 row">
        <div class="col-lg-4 col-md-6 col-sm-12"></div>
        <div class="col-lg-4 col-md-6 col-sm-12 d-flex justify-content-center">
            <div class="card">
                <div class="image-container">
                    <button type="button" class="image-upload-button"></button>
                    <input type="file" name="imageFile" id="imageFile" accept="image/jpeg, image/png, image/svg" required style="display: none;" >
                    <img src=""class="image_preview" style="display: none; width: 100%; height: 100%; object-fit:cover;">
                    <img src="{{ asset($Freelancer_checked->Imagefreelan) }}"class="image_old" style="display: block; width: 100%; height: 100%; object-fit:cover;">
                    <input type="hidden" name="image" id="hiddenImageInput" value="">
                    <button class="deleteImage" type="button" id="deleteImage" style="display: none;"></button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 row">
        <div class="col-lg-2 col-md-6 col-sm-12"></div>
        <div class="col-lg-2 col-md-6 col-sm-12"><label for="Preface">Title</label><br>
            <select name="Preface" id="PrefaceSelect" class="select" >
                <option value=""></option>
                @foreach($prefix as $item)
                    <option value="{{ $item->id }}"{{$Freelancer_checked->prefix == $item->id ? 'selected' : ''}}>{{ $item->name_th }}</option>
                @endforeach
            </select></div>
        <div class="col-lg-3 col-md-6 col-sm-12" ><label for="first_name">First Name</label><br>
        <input type="text" id="first_name" name="first_name"maxlength="70" value="{{$Freelancer_checked->First_name}}" required></div>
        <div class="col-lg-3 col-md-6 col-sm-12" ><label for="last_name" >Last Name</label><br>
        <input type="text" id="last_name" name="last_name"maxlength="70" value="{{$Freelancer_checked->Last_name}}" required></div>
    </div>
    <div class="col-12 row mt-2">
        <div class="col-lg-2 col-md-6 col-sm-12"></div>
        <div class="col-lg-4 col-md-6 col-sm-12"><label for="country">Country</label><br>
            <select name="countrydata" id="countrySelect" class="form-select" onchange="showcityInput()">
                <option value="Thailand"{{$Freelancer_checked->Country == "Thailand" ? 'selected' : ''}}>ประเทศไทย</option>
                <option value="Other_countries"{{$Freelancer_checked->Country == "Other_countries" ? 'selected' : ''}}>ประเทศอื่นๆ</option>
            </select>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12" id="cityInput" style="display:none;">
            <label for="city">City</label><br>
            <input type="text" id="city" name="city"  value="{{$Freelancer_checked->Other_City}}">
        </div>
        @if (($Freelancer_checked->Country === 'Thailand'))
            <div class="col-lg-4 col-md-6 col-sm-12" id="citythai" style="display:block;">
                <label for="city">City</label><br>
                <select name="province" id = "province" class="select2" onchange="select_province()">
                    @foreach($provinceNames as $item)
                        <option value="{{ $item->id }}" {{$Freelancer_checked->City == $item->id ? 'selected' : ''}}  >{{ $item->name_th }}</option>
                    @endforeach
                </select>
            </div>
        @else
            <div class="col-lg-4 col-md-6 col-sm-12" id="citythai" style="display:none;">
                <label for="city">City</label><br>
                <select name="province" id = "province" class="select2" onchange="select_province()">
                    <option value=""></option>
                    @foreach($provinceNames as $item)
                        <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>
    <div class="col-12 row mt-2">
        <div class="col-lg-2 col-md-3 col-sm-12"></div>
        @if (($Freelancer_checked->Country === 'Thailand'))
        <div class="col-lg-3 col-md-3 col-sm-12">
            <label for="Amphures">Amphures</label><br>
            <select name="amphures" id = "amphures" class="select2"  onchange="select_amphures()">
                @foreach($amphures as $item)
                <option value="{{ $item->id }}" {{ $Freelancer_checked->Amphures == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                @endforeach
            </select>
        </div>
        @else
        <div class="col-lg-3 col-md-3 col-sm-12">
            <label for="Amphures">Amphures</label><br>
            <select name="amphures" id = "amphures" class="select2"  onchange="select_amphures()">
                <option value=""></option>
            </select>
        </div>
        @endif
        @if (($Freelancer_checked->Country === 'Thailand'))
        <div class="col-lg-3 col-md-3 col-sm-12">
            <label for="Tambon">Tambon  </label><br>
            <select name="Tambon" id ="Tambon" class="select2" onchange="select_Tambon()">
                @foreach($amphures as $item)
                    <option value="{{ $item->id }}" {{ $Freelancer_checked->Tambon == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                @endforeach
            </select>
        </div>
        @else
        <div class="col-lg-3 col-md-3 col-sm-12">
            <label for="Tambon">Tambon  </label><br>
            <select name="Tambon" id ="Tambon" class="select2" onchange="select_Tambon()">
                <option value=""></option>
            </select>
        </div>
        @endif
        @if (($Freelancer_checked->Country === 'Thailand'))
        <div class="col-lg-2 col-md-3 col-sm-12">
            <label for="zip_code">Zip Code</label><br>
            <select name="zip_code" id ="zip_code" class="select2" >
                @foreach($Zip_code as $item)
                    <option value="{{ $item->id }}" {{ $Freelancer_checked->Zip_Code == $item->zip_code ? 'selected' : '' }}>{{ $item->zip_code }}</option>
                @endforeach
            </select>
        </div>
        @else
        <div class="col-lg-2 col-md-3 col-sm-12">
            <label for="zip_code">Zip Code</label><br>
            <select name="zip_code" id ="zip_code" class="select2" >
                <option value=""></option>
            </select>
        </div>
        @endif
    </div>
    <div class="col-12 row mt-2">
        <div class="col-lg-2 col-md-3 col-sm-12"></div>
        <div class="col-lg-8 col-md-8 col-sm-12" >
            <label for="address">Address:</label><br>
            <textarea type="text" id="address" name="address" rows="5" cols="35" class="textarea" aria-label="With textarea" required>{{$Freelancer_checked->Address}}</textarea>
        </div>
    </div>
    <div class="col-12 row mt-2">
        <div class="col-lg-2 col-md-2 col-sm-12"></div>
        <div class="col-lg-8 col-md-8 col-sm-12" >
            <label for="email">Email</label><br>
            <input type="text" id="email" name="email"maxlength="70" required value="{{$Freelancer_checked->Email}}">
        </div>
    </div>
    <div class="col-12 row mt-2">
        <div class="col-lg-2 col-md-2 col-sm-12"></div>
        <div class="col-lg-4 col-md-4 col-sm-12" >
            <label for="Birthday">Birthday</label><br>
            <input type="date" id="Birthday" name="Birthday" required  value="{{$Freelancer_checked->Birthday}}">
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12" >
            <label for="First_day_work">First day of work</label><br>
            <input type="date" id="First_day_work" name="First_day_work" required  value="{{$Freelancer_checked->First_day_work}}">
        </div>
    </div>
    <div class="col-12 row mt-2">
        <div class="col-lg-2 col-md-2 col-sm-12"></div>
        <div class="col-lg-4 col-md-4 col-sm-12" >
            <label for="booking_channel">Booking Channel:</label><br>
            <select name="booking_channel[]" id = "booking_channel" class="select2" multiple>
                @php
                $booking = explode(',',$Freelancer_checked->Booking_Channel);
                @endphp
                @foreach($booking_channel as $item)
                    <option value="{{ $item->id }}" {{ in_array($item->id, $booking) ? 'selected' : '' }}>
                        {{ $item->name_en }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="row">
                <div class="col-lg-7 col-md-6 col-sm-12">
                    <label for="Phone_number">หมายเลขโทรศัพท์ / Phone number</label>
                </div>
                <div class="col-lg-5 col-md-6 col-sm-12 my-2">
                    <button type="button" class="add-phone" id="add-phone" data-target="phone-container">เพิ่มเบอร์โทรศัพท์</button>
                </div>
            </div>
            <div id="phone-container" class="flex-container row">
                @foreach($phoneArray as $phone)
                <div class="col-lg-12 col-md-6 col-sm-12 phone-group show">
                    <input type="text" name="phone[]" class="form-control" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);"
                    required value="{{ $phone['Phone_number'] }}">
                    <button type="button" class="remove-phone">ลบ</button>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-12 row mt-2">
        <div class="col-lg-2 col-md-2 col-sm-12"></div>
        <div class="col-lg-4 col-md-4 col-sm-12" >
            <label for="identification_number">Identification Number</label><br>
            <input type="text" id="identification_number" name="identification_number"maxlength="13" required value="{{$Freelancer_checked->Identification_number}}">
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 mt-2">
            <label for="Identification_Number_file">Identification Number File</label><br>
            <input type="file" id="Identification_Number_file" name="Identification_Number_file"
                style="width: 360px;border: 2px solid  #b4b4b4"accept="image/jpeg, image/png, image/svg" >
            <a href="{{ asset($Freelancer_checked->Identification_file) }}" target="_blank" class="file-link">
                <img src="{{ asset('assets2/images/open.png') }}" alt="Card image" style="width: 15%;">
            </a>
        </div>
    </div>
    <div class="col-12 row mt-2">
        <div class="col-lg-2 col-md-2 col-sm-12"></div>
        <div class="col-lg-4 col-md-4 col-sm-12 mt-2">
            <label for="Bank_number">Bank Account Number</label><br>
            <input type="text" id="Bank_number" name="Bank_number"maxlength="10" required value="{{$Freelancer_checked->Bank_number}}">
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 mt-2" >
            <label for="Account_Name">Bank Account Name</label><br>
            <input type="text" id="Account_Name" name="Account_Name"maxlength="70" required value="{{$Freelancer_checked->Bank_account_Name}}">
        </div>
    </div>
    <div class="col-12 row mt-2">
        <div class="col-lg-2 col-md-2 col-sm-12"></div>
        <div class="col-lg-4 col-md-4 col-sm-12 mt-2">
            <label for="Bank">Bank</label><br>
                <select name="Mbank" id = "Mbank" class="select2">
                    <option value=""></option>
                    @foreach($Mbank as $item)
                        <option value="{{ $item->id }}"{{$Freelancer_checked->Mbank == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                    @endforeach
                </select>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12 mt-2" >
            <label for="Bank_file">Bank File</label><br>
            <input type="file" id="Bank_file" name="Bank_file"
            style="width: 360px;  border: 2px solid  #b4b4b4"
            accept="image/jpeg, image/png, image/svg">
            <a href="{{ asset($Freelancer_checked->Bank_file) }}" target="_blank" class="file-link">
                <img src="{{ asset('assets2/images/open.png') }}" alt="Card image" style="width: 15%;">
            </a>
        </div>
    </div>
    <div class="col-12 row mt-2">
        <div class="col-6 col-md-4 col-sm-12"></div>
        <div class="col-4 col-md-4 col-sm-12" style="display:flex; justify-content:center; align-items:center;">
            <button type="button" class="button-return" onclick="window.location.href='{{ route('freelancer.index') }}'" >{{ __('ย้อนกลับ') }}</button>
            <button type="submit" class="button-10"  id="imageSubmit" style="background-color: #109699;">บันทึกข้อมูล</button>
        </div>
        <div class="col-4 col-md-4 col-sm-12"></div>
    </div>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>

$(document).ready(function(){
    $('#identification_number').mask('0-0000-00000-00-0');
});
</script>
<script>
$('#first_name').on('keyup',function(){
    var first_name = $(this).val();
    var last_name =$('#last_name').val();
    $('#Account_Name').val(first_name+" "+last_name);
    console.log(last_name);
});
$('#last_name').on('keyup',function(){
    var first_name = $('#first_name').val();
    var last_name = $(this).val();
    $('#Account_Name').val(first_name+" "+last_name);
    console.log(first_name);
});
// window.onload = function() {
//     document.getElementById("defaultOpen").click(); // เปิดแท็บ Summary Visit info เมื่อหน้าโหลด
//     document.getElementById("lastestOpen").click();}
function openHistory(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("nav-link");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
    }
    $(document).ready(function(){
        $('.select2').select2();
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

    function Onclickreadonly(){
        var startDate = document.getElementById('contract_rate_start_date').value;
        if (startDate !== '') {
            // หากมีค่า กำหนด input field ที่มี id เป็น contract_rate_end_date เป็น readonly
            document.getElementById('contract_rate_end_date').readOnly = false;
        } else {
            // หากไม่มีค่า กำหนด input field ที่มี id เป็น contract_rate_end_date เป็น readonly
            document.getElementById('contract_rate_end_date').readOnly = true;
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
    var alertMessage = "{{ session('alert_') }}";
    var alerterror = "{{ session('error_') }}";
    if(alertMessage) {
        // แสดง SweetAlert ทันทีเมื่อโหลดหน้าเว็บ
        Swal.fire({
            icon: 'success',
            title: alertMessage,
            showConfirmButton: false,
            timer: 1500
        });
    }if(alerterror) {
        Swal.fire({
            icon: 'error',
            title: alerterror,
            showConfirmButton: false,
            timer: 1500
        });
    }
    $(document).ready(function() {
        // เปิด file input เมื่อคลิกที่ปุ่มอัปโหลด
        $(document).on('click', '.image-upload-button', function() {
            $('#imageFile').click();
        });

        // แสดงตัวอย่างรูปภาพเมื่อมีการเลือกไฟล์
        $(document).on('change', '#imageFile', function() {
            if (this.files && this.files[0]) {
                let img = document.querySelector('.image_preview');
                let buttonIcon = document.querySelector('.buttonIcon');
                let imageUploadButton = document.querySelector('.image-upload-button');
                let deleteImage = document.querySelector('.deleteImage');

                // รีเซ็ตรูปภาพเก่า

                // ตั้งค่าใหม่สำหรับรูปภาพที่ถูกเลือก
                img.onload = () => {
                    URL.revokeObjectURL(img.src);
                };
                img.src = URL.createObjectURL(this.files[0]);
                img.style.display = 'block'; // แสดงรูปภาพที่อัปโหลด
               // buttonIcon.style.display = 'block'; // แสดงปุ่มไอคอน
                imageUploadButton.style.display = 'none'; // ซ่อนปุ่มอัปโหลด
                deleteImage.style.display = 'block';
                // แสดงปุ่มลบ
            }
        });
        $(document).on('click', '#deleteImage', function(event) {
            event.preventDefault(); // ป้องกันการโหลดหน้าใหม่

            let img = document.querySelector('.image_preview');
            img.src = ""; // ตั้งค่า src ของรูปเป็นค่าว่าง

            img.style.display = 'none'; // ซ่อนรูปภาพ
            let imageUploadButton = document.querySelector('.image-upload-button');
            imageUploadButton.style.display = 'block'; // แสดงปุ่มอัปโหลด
            let deleteImage = document.querySelector('.deleteImage');
            deleteImage.style.display = 'none'; // ซ่อนปุ่มลบ

            let inputImageFile = document.getElementById('imageFile');
            inputImageFile.value = ""; // ตั้งค่าค่า value เป็นค่าว่าง
        });
    });
    document.getElementById('imageSubmit').addEventListener('click', function(e) {
        if (!document.getElementById('imageFile').files.length) {
            document.getElementById('hiddenImageInput').value = 'empty'; // Set a placeholder value
        }
        document.getElementById('imageForm').submit();
    });



</script>
<script>
   document.getElementById('add-phone').addEventListener('click', function() {
    var phoneContainer = document.getElementById('phone-container');
    var newCol = document.createElement('div');
    newCol.classList.add('col-lg-12', 'col-md-6', 'col-sm-12');
    newCol.innerHTML = `
        <div class="phone-group show">
            <input type="text" name="phone[]" class="form-control" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
            <button type="button" class="remove-phoneed">ลบ</button>
        </div>
    `;
    phoneContainer.appendChild(newCol);

    // Add the show class after a slight delay to trigger the transition
    setTimeout(function() {
        newCol.querySelector('.phone-group').classList.add('show');
    }, 10);

    attachRemoveEvent(newCol.querySelector('.remove-phoneed'));
});

function attachRemoveEvent(button) {
    button.addEventListener('click', function() {
        var phoneContainer = document.getElementById('phone-container');
        if (phoneContainer.childElementCount > 1) {
            phoneContainer.removeChild(button.closest('.col-lg-12'));
        }
    });
}

// Attach the remove event to both initial remove buttons
document.querySelectorAll('.remove-phone').forEach(function(button) {
    attachRemoveEvent(button);
});
document.querySelectorAll('.remove-phoneed').forEach(function(button) {
    attachRemoveEvent(button);
});

</script>






















@if(Session::has('error'))
<script>
    swal({
        title: "{{ Session::get('error') }}",
        icon: "error",
        customClass: {
            title: "my-swal-title" // กำหนดคลาสใหม่สำหรับข้อความหัวเรื่อง
        }
    });

        // แสดงการแจ้งเตือน (error) ด้วย JavaScript โดยใช้ค่าจาก Controller
    var msg = "{{ $msg ?? '' }}"; // กำหนดค่า msg จาก Controller
    if (msg) {
        alert(msg);
    }
</script>
@endif
@if(Session::has('alert'))
<script>
    swal({
        title: "{{ Session::get('alert') }}",
        icon: "success",
        customClass: {
            title: "my-swal-title" // กำหนดคลาสใหม่สำหรับข้อความหัวเรื่อง
        }
    });

        // แสดงการแจ้งเตือน (alert) ด้วย JavaScript โดยใช้ค่าจาก Controller
        var msg = "{{ $msg ?? '' }}"; // กำหนดค่า msg จาก Controller
    if (msg) {
        alert(msg);
    }
</script>
@endif

@endsection
