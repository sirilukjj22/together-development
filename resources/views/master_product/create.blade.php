@extends('layouts.masterLayout')
<style>

    .card1 {
        width: 460px; /* กำหนดความกว้างตามต้องการ */
        height: 300px; /* กำหนดความสูงตามต้องการ */
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
        background: url('{{ asset('assets/images/no-image.jpg') }}') no-repeat center center;
        position: relative;
    }

    .image-upload-button {
        position: absolute;
        bottom: 10px; /* ตำแหน่งจากด้านล่าง */
        right: 10px; /* ตำแหน่งจากด้านขวา */
        width: 32px; /* ขนาดของปุ่ม */
        height: 32px; /* ขนาดของปุ่ม */
        background: url('{{ asset('assets/images/photo-camera.png') }}') no-repeat center center;
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
        background: url('{{ asset('assets/images/delete.png') }}') no-repeat center center;
        background-size: cover;
        border: none; /* ไม่มีเส้นขอบ */
        border-radius: 50%; /* ทำให้ปุ่มเป็นวงกลม */
        cursor: pointer;* เปลี่ยนรูปแบบของ cursor เมื่อวางเหนือปุ่ม */
        box-shadow: 0 0 5px 2px rgba(255, 255, 255, 0.8);
    }

    .input-group{
        float: left;
        width:70%;
    }
    .image_preview {
        display: none; /* Hide the image by default */
        max-width: 100%; /* Ensure the image width does not exceed the container */
        max-height: 100%; /* Ensure the image height does not exceed the container */
        object-fit: contain;
        background-color: #ccc; /* Scale the image to maintain aspect ratio */
    }
    .select2{
        width: 100%;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    .input-group {
        margin-top: 20px;
        width: 90%;
    }
    .image-preview-container {
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap; /* Prevent wrapping */
        gap: 10px;
        margin-top: 20px;
        overflow-x: auto;
        margin-top: 10px;/* Enable horizontal scrolling if needed */
    }
    .image-preview {
        width: 200px;
        height: 200px;
        object-fit: cover;
        border: 1px solid #ccc;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .lightbox {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1000; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto; /* Enable scroll if needed */
        background-color: rgba(0, 0, 0, 0.9); /* Black with opacity */
    }
    .lightbox-content {
        margin: auto;
        display: block;
        max-width: 80%;
        max-height: 80%;
    }
    .close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #fff;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
        cursor: pointer;
    }
    .close:hover,
    .close:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }
    .btn-space {
        margin-right: 10px; /* ปรับขนาดช่องว่างตามต้องการ */
    }
    @media (max-width: 768px) {
        .image-container {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f0f0f0;
            background: url('{{ asset('assets/images/no-image.jpg') }}') no-repeat center center;
            background-size: cover;
            position: relative;
        }
        .card1 {
            width: 260px; /* กำหนดความกว้างตามต้องการ */
            height: 200px; /* กำหนดความสูงตามต้องการ */
            background-color: #fff;
            border: 1px solid #ccc; /* เพิ่มเส้นขอบ */
            border-radius: 10px; /* เพิ่มมุมโค้งมน */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* เพิ่มเงา */
            overflow: hidden; /* ซ่อนส่วนเกิน */
            position: relative; /* สำหรับการวางปุ่ม */
        }
        .input-group{
            float: left;
            width:100%;
        }
        .col-4{
            width: 100%;
        }
        .col-7{
            width: 100%;
        }
        .col-6{
            width: 100%;
        }
        .col-3{
            width: 100%;
        }
        h1{
            margin-top:32px;
        }
    }
</style>
@section('content')

    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Create Product Item</div>
                </div>
                <div class="col-auto">

                </div>
            </div> <!-- .row end -->
        </div>
    </div>
    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
            <div class="row align-items-center mb-2" >
                @if (session("success"))
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">บันทึกสำเร็จ!</h4>
                    <hr>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
                @endif
                @if (session("error"))
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">บันทึกไม่สำเร็จ!</h4>
                        <hr>
                        <p class="mb-0">{{ session('error') }}</p>
                    </div>
                @endif
                <div class="col">
                    <ol class="breadcrumb d-inline-flex bg-transparent p-0 m-0">
                        <li></li>
                        <li></li>
                        <li></li>
                    </ol>
                </div>
                <div class="col-auto">
                </div>
            </div> <!-- Row end  -->
        </div> <!-- Row end  -->
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <form action="{{route('Mproduct.save')}}" method="POST"enctype="multipart/form-data">
                                {!! csrf_field() !!}
                                <div class="col-12 col-sm-12 row">
                                    <form id="image_upload_form" enctype="multipart/form-data">
                                        <div class="col-12 "  style="display: flex; justify-content: center;">
                                            <div class="card1">
                                                <div class="image-container">
                                                    <button type="button" class="image-upload-button"></button>
                                                    <input type="file" name="imageFile" id="imageFile" accept="image/jpeg, image/png, image/svg" value="{{ asset('assets/images/no-image.jpg') }}" style="display: none;">
                                                    <img src=""  class="image_preview" style="display: none;">
                                                    <button class="buttonIcon" type="button" id="imageSubmit" style="display: none;"></button>
                                                    <button class="deleteImage" id="deleteImage" type="button" style="display: none;"></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="col-12" style="display: flex; justify-content: center;">
                                        <div class="input-group mt-5" style="width:40%; position: relative;">
                                            <label for="image_other" class="form-control" style="position: absolute; left: 0; top: 0; height: 100%; width: 100%; pointer-events: none; display: flex; align-items: center; padding-left: 1rem; color: #000000;   border-radius: 5px;">รูปเพิ่มเติม</label>
                                            <input type="file" class="form-control" name="image_other[]" id="image_other" aria-describedby="image_other" aria-label="Upload" multiple style="opacity: 0;">
                                        </div>
                                    </div>
                                    <div class="col-12 "  style="display: flex; justify-content: center;">
                                        <div class="image-preview-container mt-3 " id="imagePreviewContainer" ></div>
                                        <div id="lightbox" class="lightbox">
                                            <span class="close" onclick="closeLightbox()">&times;</span>
                                            <img class="lightbox-content" id="lightboxImage">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-12 mt-2">
                                                <label for="category"  style="padding: 5px">Category</label>
                                                <select name="category" id="category" class="select2" style="width:100%" >
                                                    <option value="" ></option>
                                                    <option value="Room_Type">Room Type</option>
                                                    <option value="Banquet">Banquet</option>
                                                    <option value="Meals">Meals</option>
                                                    <option value="Entertainment">Entertainment</option>
                                                </select>
                                            </div>
                                            <div class="col-6"></div>
                                            <div class="col-lg-2 col-md-2 col-sm-12" >
                                                <label for="Profile_ID">Profile ID</label><br>
                                                <input type="text" id="Profile_ID" class="form-control" name="Profile_ID"maxlength="70" disabled>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-sm-12">
                                                <label for="Name_th">Name_th</label><br>
                                                <input type="text" id="name_th" class="form-control" name="name_th"maxlength="70" >
                                            </div>
                                            <div class="col-lg-6 col-sm-12">
                                                <label for="name_en" >Name_en</label><br>
                                                <input type="text" id="name_en" class="form-control" name="name_en"maxlength="70" >
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-sm-12">
                                                <label for="detail_th">Detail_th</label><br>
                                                <input type="text" id="detail_th" class="form-control" name="detail_th"maxlength="70" >
                                            </div>
                                            <div class="col-lg-6 col-sm-12">
                                                <label for="detail_en">Detail_en</label><br>
                                                <input type="text" id="detail_en" class="form-control" name="detail_en"maxlength="70" >
                                            </div>
                                        </div>
                                        <div class=" row">
                                            <div class="col-lg-6 col-sm-12" ><label for="pax">Pax</label><br>
                                                <input type="text" id="pax" class="form-control" name="pax"maxlength="70" >
                                            </div>
                                            <div class="col-lg-6 col-sm-12" ><label for="room_size">Room size (Sqm.)</label><br>
                                                <input type="text" id="room_size"  class="price-input form-control" name="room_size"maxlength="70">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 col-sm-12" >
                                                <label for="normal_price">Normal Price (Include VAT)</label><br>
                                                <input type="text" class="price-input form-control" id="normal_price" name="normal_price" maxlength="70">
                                            </div>
                                            <div class="col-lg-4 col-sm-12" ><label for="Maximum_Discount">Maximum Discount</label><br>
                                                <input type="text" id="Maximum_Discount" class="form-control" name="Maximum_Discount"maxlength="70" required>
                                            </div>
                                            <div class="col-lg-4 col-sm-12" ><label for="Maximum_Discount">Number of rooms</label><br>
                                                <input type="text" id="room" class="form-control" name="room"maxlength="70" required>
                                            </div>
                                        </div>
                                        <div class=" row">
                                            <div class="col-lg-6 col-sm-12" >
                                                <label for="Quantity"  style="padding: 5px">Quantity</label>
                                                <select name="quantity"  id = "quantity" class="select2">
                                                    <option value=""></option>
                                                    @foreach($quantity as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name_th }} ({{ $item->name_en }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-6 col-sm-12" >
                                                <label for="Unit" style="padding: 5px">Unit</label>
                                                <select name="unit" id = "unit" class="select2">
                                                    <option value=""></option>
                                                    @foreach($unit as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name_th }} ({{ $item->name_en }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-lg-3 col-sm-12"></div>
                                            <div class="col-lg-6 col-sm-12 d-flex justify-content-center align-items-center">
                                                <button type="button" class="btn btn-secondary lift  btn-space"  onclick="window.location.href='{{ route('Mproduct.index') }}'">{{ __('ย้อนกลับ') }}</button>
                                                <button type="submit" class="btn btn-color-green lift ">บันทึกข้อมูล</button>
                                            </div>
                                            <div class="col-lg-3 col-sm-12"></div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    @include('script.script')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Please select an option"
            });
        });
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // เปิด file input เมื่อคลิกที่ปุ่มอัปโหลด
            $(document).on('click', '.image-upload-button', function() {
                console.log(1);
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
                let buttonIcon = document.querySelector('.buttonIcon');
                buttonIcon.style.display = 'none'; // ซ่อนปุ่มไอคอน
                let imageUploadButton = document.querySelector('.image-upload-button');
                imageUploadButton.style.display = 'block'; // แสดงปุ่มอัปโหลด
                let deleteImage = document.querySelector('.deleteImage');
                deleteImage.style.display = 'none'; // ซ่อนปุ่มลบ

                let inputImageFile = document.getElementById('imageFile');
                inputImageFile.value = ""; // ตั้งค่าค่า value เป็นค่าว่าง
            });
            // ส่งรูปภาพไปยังเซิร์ฟเวอร์เมื่อฟอร์มถูกส่ง
            $(document).on("click", '#imageSubmit', function(e) {
                const fileInput = document.getElementById('imageFile');
                const file = fileInput.files[0];
                var formData = new FormData();

                formData.append('image', file);

                $.ajax({
                    type: 'POST',
                    url: "{{route('Mproduct.save')}}", // ตรวจสอบ URL
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: (response) => {
                        console.log(response.success);
                        // รีเซ็ตฟอร์มหรือแสดงข้อความสำเร็จ
                    },
                    error: function(data) {

                        console.log('Error:', data);
                    }
                });
            });
        });
        document.getElementById('image_other').addEventListener('change', function(event) {
            const files = event.target.files;
            const previewContainer = document.getElementById('imagePreviewContainer');
            previewContainer.innerHTML = ''; // Clear existing images

            if (files.length > 5) {
                alert('You can upload a maximum of 5 images.');
                event.target.value = ''; // Clear the input field
                return;
            }

            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'image-preview';
                    img.addEventListener('click', () => openLightbox(e.target.result));
                    previewContainer.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        });

        function openLightbox(src) {
            const lightbox = document.getElementById('lightbox');
            const lightboxImage = document.getElementById('lightboxImage');
            lightbox.style.display = 'block';
            lightboxImage.src = src;
        }

        function closeLightbox() {
            const lightbox = document.getElementById('lightbox');
            lightbox.style.display = 'none';
        }
        $(document).ready(function() {
            $('#category').change(function() {
                var selectedCategory = $(this).val();
                $.ajax({
                    url: '/Mproduct/check/Category', // Your Laravel route
                    type: 'POST',
                    data: {
                        category: selectedCategory,
                        _token: '{{ csrf_token() }}' // Ensure CSRF token is included
                    },
                    success: function(response) {
                        $('#Profile_ID').val(response.data);
                        console.log(response.data);
                    },
                    error: function(xhr) {
                        // Handle error response
                        console.error(xhr.responseText);
                    }
                });
                switch(selectedCategory) {
                    case 'Room_Type':
                        document.getElementById('normal_price').disabled = false;
                        document.getElementById('pax').disabled = false;
                        break;
                    case 'Banquet':
                        document.getElementById('normal_price').disabled = false;
                        document.getElementById('pax').disabled = true;
                        break;
                    case 'Meals':
                        document.getElementById('normal_price').disabled = false;
                        document.getElementById('pax').disabled = true;
                        break;
                    case 'Entertainment':
                        document.getElementById('normal_price').disabled = false;
                        document.getElementById('pax').disabled = true;
                        break;
                    default:
                        // ถ้าไม่มีค่าที่เลือกหรือค่าที่เลือกไม่ตรงกับที่กำหนดไว้ ให้ซ่อนทุก container
                        break;
                }
            });
        });
    </script>
@endsection
