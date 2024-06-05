@extends('layouts.test')

@section('content')
<style>
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
    .remove-phone {
        /* เพิ่มสไตล์ที่คุณต้องการในส่วนนี้ */
        color: #fff;
        background-color: #dc3545; /* สีแดง */
        border-color: #dc3545; /* สีเหลือง */
        border-radius: 0.25rem;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        cursor: not-allowed;
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

    .image-container {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f0f0f0;
        background: url('{{ asset('assets2/images/no-image.jpg') }}') no-repeat center center;
        background-size: cover;
        position: relative;
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

</style>


<div class="container">
    <div class="row">
        <div class="usertopic">
            <h1>Freelancer</h1>
        </div>
    </div>
    <br>
    <form action="{{url('/Freelancer/check/save')}}" method="POST" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <div class="col-12 row">
            <div class="col-lg-4 col-md-6 col-sm-12"></div>
            <div class="col-lg-4 col-md-6 col-sm-12 d-flex justify-content-center">
                <div class="card">
                    <div class="image-container">
                        <button type="button" class="image-upload-button"></button>
                        <input type="file" name="imageFile" id="imageFile" accept="image/jpeg, image/png, image/svg" required style="display: none;">
                        <img src=""class="image_preview" style="display: none; width: 100%; height: 100%; object-fit:cover;">
                        <button class="deleteImage" type="button" id="deleteImage" style="display: none;"></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 row">
            <div class="col-lg-2 col-md-6 col-sm-12"></div>
            <div class="col-2"><label for="Preface">Title</label><br>
                <select name="Preface" id="PrefaceSelect" class="select" >
                    <option value=""></option>
                        @foreach($prefix as $item)
                            <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                        @endforeach
                </select></div>
            <div class="col-lg-3 col-md-6 col-sm-12" ><label for="first_name">First Name</label><br>
            <input type="text" id="first_name" name="first_name"maxlength="70" required></div>
            <div class="col-lg-3 col-md-6 col-sm-12" ><label for="last_name" >Last Name</label><br>
            <input type="text" id="last_name" name="last_name"maxlength="70" required></div>
        </div>
        <div class="col-12 row mt-2">
            <div class="col-lg-2 col-md-6 col-sm-12"></div>
            <div class="col-lg-4 col-md-6 col-sm-12"><label for="country">Country</label><br>
                <select name="countrydata" id="countrySelect" class="select" onchange="showcityInput()">
                    <option value="Thailand">ประเทศไทย</option>
                    <option value="Other_countries">ประเทศอื่นๆ</option>
                </select>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12" id="cityInput" style="display:none;">
                <label for="city">City</label><br>
                <input type="text" id="city" name="city">
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12" id="citythai" style="display:block;">
                <label for="city">City</label><br>
                <select name="province" id = "province" class="select2" onchange="select_province()"style="border: 1px solid #000">
                    <option value=""></option>
                    @foreach($provinceNames as $item)
                        <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-12 row mt-2">
            <div class="col-lg-2 col-md-2 col-sm-12"></div>
            <div class="col-lg-3 col-md-3 col-sm-12">
                <label for="Amphures">Amphures</label><br>
                <select name="amphures" id = "amphures" class="select2"  onchange="select_amphures()">
                    <option value=""></option>

                </select>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12">
                <label for="Tambon">Tambon  </label><br>
                <select name="Tambon" id ="Tambon" class="select2" onchange="select_Tambon()">
                    <option value=""></option>
                </select>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-12">
                <label for="zip_code">Zip Code</label><br>
                <select name="zip_code" id ="zip_code" class="select2" >
                    <option value=""></option>
                </select>
            </div>
        </div>
        <div class="col-12 row mt-2">
            <div class="col-lg-2 col-md-6 col-sm-12"></div>
            <div class="col-lg-8 col-md-8 col-sm-12" >
                <label for="address">Address:</label><br>
                <textarea type="text" id="address" name="address" rows="5" cols="35" class="textarea" aria-label="With textarea" required></textarea>
            </div>

        </div>
        <div class="col-12 row mt-2">
            <div class="col-lg-2 col-md-6 col-sm-12"></div>
            <div class="col-lg-8 col-md-8 col-sm-12" >
                <label for="email">Email</label><br>
                <input type="text" id="email" name="email"maxlength="70" required>
            </div>
        </div>
        <div class="col-12 row mt-2">
            <div class="col-lg-2 col-md-6 col-sm-12"></div>
            <div class="col-lg-4 col-md-6 col-sm-12" >
                <label for="Birthday">Birthday</label><br>
                <input type="date" id="Birthday" name="Birthday" required>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12" >
                <label for="First_day_work">First day of work</label><br>
                <input type="date" id="First_day_work" name="First_day_work" required>
            </div>
        </div>
        <div class="col-12 row mt-2">
            <div class="col-lg-2 col-md-6 col-sm-12"></div>
            <div class="col-lg-4 col-md-6 col-sm-12" >
                <label for="booking_channel">Booking Channel:</label><br>
                <select name="booking_channel[]" id="booking_channel" class="select2" multiple>
                    <option value=""></option>
                    @foreach($booking_channel as $item)
                    <option value="{{ $item->id }}">{{ $item->name_en }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <label for="Phone_number">Phone number</label><br>
                <div id="phone-container" class="flex-container">
                    <!-- ตำแหน่งนี้จะใส่ input เดียวในตอนเริ่มต้น -->
                    <div class="phone-group" >
                        <input type="text" name="phone[]" id="phone-main"class="form-control" maxlength="10" required oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);">
                        <div id="add-phone-orther"></div>
                    </div>
                </div>

                <button type="button" class="add-phone"id="add-phone" data-target="phone-container">Add Phone</button>
                <button type="button" class="remove-phone" >Remove</button>
            </div>
        </div>
        <div class="col-12 row mt-2">
            <div class="col-lg-2 col-md-6 col-sm-12"></div>
            <div class="col-lg-4 col-md-6 col-sm-12" >
                <label for="identification_number">Identification Number</label><br>
                <input type="text" id="identification_number" name="identification_number"maxlength="13" required>

            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 mt-2">
                <label for="Identification_Number_file">Identification Number File</label><br>
                <input type="file" id="Identification_Number_file" name="Identification_Number_file"
                 required style="width: 100%;border: 2px solid  #b4b4b4"accept="image/jpeg, image/png, image/svg">
            </div>
        </div>
        <div class="col-12 row mt-2">
            <div class="col-lg-2 col-md-6 col-sm-12"></div>
            <div class="col-lg-4 col-md-6 col-sm-12 mt-2">
                <label for="Bank_number">Bank Account Number</label><br>
                <input type="text" id="Bank_number" name="Bank_number"maxlength="10" required>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 mt-2">
                <label for="Account_Name">Bank Account Name</label><br>
                <input type="text" id="Account_Name" name="Account_Name"maxlength="70" required>
            </div>
        </div>
        <div class="col-12 row mt-2">
            <div class="col-lg-2 col-md-6 col-sm-12"></div>
            <div class="col-lg-4 col-md-6 col-sm-12 mt-2">
                <label for="Bank">Bank</label><br>
                    <select name="Mbank" id = "Mbank" class="select2">
                        <option value=""></option>
                        @foreach($Mbank as $item)
                            <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                        @endforeach
                    </select>
            </div>
            <div class="col-4 mt-2" >
                <label for="Bank_file">Bank File</label><br>
                <input type="file" id="Bank_file" name="Bank_file"   required
                style="width: 100%;  border: 2px solid  #b4b4b4"
                accept="image/jpeg, image/png, image/svg">

            </div>
        </div>

            <div class="col-12 mt-2">
                <div class="row">
                    <div class="col-lg-4 col-md-2 col-sm-12 "></div>
                    <div class="col-lg-4 col-md-2 col-sm-12 "style="display:flex; justify-content:center;">
                        <div >
                            <button class="btn btn-animate-1" type="submit" style="background: #2D7F7B;" >ตกลง</button>
                            <button class="btn btn-animate-1" type="button" style="background: #ff0000;" onclick="window.location.href='{{ route('freelancer.index') }}'" >{{ __('ย้อนกลับ') }}</button>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-2 col-sm-12 "></div>
                </div>
            </div>

    </form>
</div>
<script>
$(document).ready(function(){
    $('.select2').select2();
});
$(document).ready(function(){
    $('#identification_number').mask('0-0000-00000-00-0');
});
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
//     document.getElementById("lastestOpen").click();
// }
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
//----------------------------------------------------------------------------
function select_province(){
    var provinceID = $('#province').val();
    jQuery.ajax({
        type:   "GET",
        url:    "{!! url('/Freelancer/checked/create/amphures/"+provinceID+"') !!}",
        datatype:   "JSON",
        async:  false,
        success: function(result) {
            jQuery('#amphures').children().remove().end();
            //ตัวแปร
            $('#amphures').append(new Option('', ''));
            jQuery.each(result.data, function(key, value) {
                var amphures = new Option(value.name_th,value.id);
                $('#amphures').append(amphures);
                console.log(amphures);
            });
        },
    })
}

function select_amphures(){
    var amphuresID  = $('#amphures').val();
    $.ajax({
        type:   "GET",
        url:    "{!! url('/Freelancer/checked/create/Tambon/"+amphuresID+"') !!}",
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
        url:    "{!! url('/Freelancer/checked/create/districts/"+Tambon+"') !!}",
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

document.addEventListener("DOMContentLoaded", function() {
    const addButton = document.getElementById('add-phone');
    const removeButton = document.querySelector('.remove-phone');
    const phoneContainer = document.getElementById('phone-container');

    let phoneCount = 1;

    addButton.addEventListener('click', function() {
            const phoneGroup = document.createElement('div');
            phoneGroup.classList.add('phone-group');
            phoneGroup.innerHTML = `
                <input type="text" name="phone[]" class="form-control" maxlength="10" >
            `;
            phoneContainer.appendChild(phoneGroup);
            phoneCount++;
    });

    removeButton.addEventListener('click', function() {
        if (phoneCount > 1) {
            const phoneGroups = phoneContainer.querySelectorAll('.phone-group');
            const lastPhoneGroup = phoneGroups[phoneGroups.length - 1];
            phoneContainer.removeChild(lastPhoneGroup);
            phoneCount--;
        }
    });
});
</script>
<script>
    $(document).ready(function() {
        // Open file input when upload button is clicked
        $(document).on('click', '.image-upload-button', function() {
            $('#imageFile').click();
        });

        // Display image preview when file is selected
        $(document).on('change', '#imageFile', function() {
            if (this.files && this.files[0]) {
                let img = document.querySelector('.image_preview');
                let imageUploadButton = document.querySelector('.image-upload-button');
                let deleteImage = document.querySelector('.deleteImage');

                img.onload = () => {
                    URL.revokeObjectURL(img.src);
                };
                img.src = URL.createObjectURL(this.files[0]);
                img.style.display = 'block';
                imageUploadButton.style.display = 'none';
                deleteImage.style.display = 'block';
            }
        });

        // Remove image preview and reset the input
        $(document).on('click', '#deleteImage', function(event) {
            event.preventDefault();

            let img = document.querySelector('.image_preview');
            img.src = "";
            img.style.display = 'none';

            let imageUploadButton = document.querySelector('.image-upload-button');
            imageUploadButton.style.display = 'block';

            let deleteImage = document.querySelector('.deleteImage');
            deleteImage.style.display = 'none';

            let inputImageFile = document.getElementById('imageFile');
            inputImageFile.value = "";
        });
    });
</script>
@endsection
