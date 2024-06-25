@extends('layouts.masterLayout')

<style>
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

    .input-group{
        float: left;
        width:70%;
    }
    .image_preview {
        display: none; /* Hide the image by default */
        max-width: 100%; /* Ensure the image width does not exceed the container */
        max-height: 100%; /* Ensure the image height does not exceed the container */
        object-fit: contain;
        background-color: #ccc;
         /* Scale the image to maintain aspect ratio */
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
    @media (max-width: 768px) {
        .image-container {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f0f0f0;
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
@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to Create Product Item.</small>
                <h1 class="h4 mt-1">Create Product Item (เพิ่มโปรดักส์)</h1>
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
                    <div class="col-12 col-sm-12">
                        <form action="{{url('/Mproduct/master_Mproduct/Mproduct_update/'.$product->id)}}" method="POST"enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            <div class="col-12 col-sm-12 row">
                                <form id="image_upload_form" enctype="multipart/form-data">
                                    <div class="col-12 "  style="display: flex; justify-content: center;">
                                        <div class="card1">
                                            <div class="image-container">
                                                <input type="file" name="imageFile" id="imageFile" accept="image/jpeg, image/png, image/svg" style="display: none;">
                                                <img src="{{ asset($product->image_product)}}" class="image_preview" style="display: block;">
                                                <button class="buttonIcon" type="button" id="imageSubmit" style="display: none;"></button>
                                                <button class="deleteImage" id="deleteImage" type="button" style="display: none;"></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="col-12 my-3" style="display: flex; justify-content: center;">
                                    <div class="image-preview-container mt-3" id="imagePreviewContainer">
                                        @foreach ($imagePaths as $path)
                                            <img src="{{ asset($path) }}" class="image_preview" alt="Image Preview" style="margin: 0 10px; max-width: 17%;height: auto;display: block; border-radius: 10px;"disabled>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                        <div class="modal-body">
                                            <img id="modalImage" src="" class="img-fluid" alt="Large Image">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-12 mt-2">
                                            <label for="category"  style="padding: 5px">Category</label>
                                            <select name="category" id="category" class="select2" style="width:100%" disabled>
                                                <option value="" ></option>
                                                <option value="Room_Type"{{$product->Category == "Room_Type" ? 'selected' : ''}}>Room Type</option>
                                                <option value="Banquet"{{$product->Category == "Banquet" ? 'selected' : ''}}>Banquet</option>
                                                <option value="Meals"{{$product->Category == "Meals" ? 'selected' : ''}}>Meals</option>
                                                <option value="Entertainment"{{$product->Category == "Entertainment" ? 'selected' : ''}}>Entertainment</option>
                                            </select>
                                        </div>
                                        <div class="col-6"></div>
                                        <div class="col-lg-2 col-md-2 col-sm-12" >
                                            <label for="Profile_ID">Profile ID</label><br>
                                            <input type="text" id="Profile_ID" name="Profile_ID"maxlength="70" disabled value="{{$product->Product_ID}}" disabled>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12">
                                            <label for="Name_th">Name_th</label><br>
                                            <input type="text" id="name_th" name="name_th"maxlength="70" value="{{$product->name_th}}" disabled>
                                        </div>
                                        <div class="col-lg-6 col-sm-12">
                                            <label for="name_en" >Name_en</label><br>
                                            <input type="text" id="name_en" name="name_en"maxlength="70" value="{{$product->name_en}}" disabled>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12">
                                            <label for="detail_th">Detail_th</label><br>
                                            <input type="text" id="detail_th" name="detail_th"maxlength="70" value="{{$product->detail_th}}" disabled>
                                        </div>
                                        <div class="col-lg-6 col-sm-12">
                                            <label for="detail_en">Detail_en</label><br>
                                            <input type="text" id="detail_en" name="detail_en"maxlength="70" value="{{$product->detail_en}}"disabled>
                                        </div>
                                    </div>
                                    <div class=" row">
                                        <div class="col-lg-6 col-sm-12" ><label for="pax">Pax</label><br>
                                            <input type="text" id="pax" name="pax"maxlength="70"  value="{{$product->pax}}" disabled>
                                        </div>
                                        <div class="col-lg-6 col-sm-12" ><label for="room_size">Room size (Sqm.)</label><br>
                                            <input type="text" id="room_size" class="price-input" name="room_size"maxlength="70"value="{{$product->room_size}}"disabled>
                                        </div>
                                    </div>
                                    <div class=" row">
                                        <div class="col-lg-3 col-sm-12" >
                                            <label for="normal_price">Normal Price <br>(Include VAT)</label><br>
                                            <input type="text"  class="price-input"id="normal_price" name="normal_price"maxlength="70" value="{{$product->normal_price}}" disabled>
                                        </div>
                                        <div class="col-lg-3 col-sm-12">
                                            <label for="weekend_price">Weekday Price <br>(Include VAT)</label><br>
                                            <input type="text"  class="price-input"id="weekend_price" name="weekend_price"maxlength="70"value="{{$product->weekend_price}}" disabled>
                                        </div>
                                        <div class="col-lg-3 col-sm-12" >
                                            <label for="long_weekend_price">Long Weekend Price<br> (Include VAT)</label><br>
                                            <input type="text"  class="price-input"id="long_weekend_price" name="long_weekend_price"maxlength="70"value="{{$product->long_weekend_price}}" disabled>
                                        </div>
                                        <div class="col-lg-3 col-sm-12" >
                                            <label for="long_weekend_price">End Weekend Price<br> (Include VAT)</label><br>
                                            <input type="text"  class="price-input"id="end_weekend_price" name="end_weekend_price"maxlength="70"value="{{$product->end_weekend_price}}" disabled>
                                        </div>
                                    </div>
                                    <div class=" row">
                                        <div class="col-lg-4 col-sm-12" >
                                            <label for="Quantity"  style="padding: 5px">Quantity</label>
                                            <select name="quantity"  id = "quantity" class="select2" disabled>
                                                <option value=""></option>
                                                @foreach($quantity as $item)
                                                    <option value="{{ $item->id }}"{{$product->quantity== $item->id ? 'selected' : ''}}>{{ $item->name_th }} ({{ $item->name_en }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-sm-12" >
                                            <label for="Unit" style="padding: 5px">Unit</label>
                                            <select name="unit" id = "unit" class="select2" disabled>
                                                <option value=""></option>
                                                @foreach($unit as $item)
                                                    <option value="{{ $item->id }}"{{$product->unit== $item->id ? 'selected' : ''}}>{{ $item->name_th }} ({{ $item->name_en }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-sm-12" ><label for="Maximum_Discount">Maximum Discount</label><br>
                                            <input type="text" id="Maximum_Discount" name="Maximum_Discount"maxlength="70" required value="{{$product->maximum_discount}}" disabled>
                                        </div>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const imagePreviews = document.querySelectorAll('.image_preview');
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');

    imagePreviews.forEach(image => {
        image.addEventListener('click', function() {
            modalImage.src = this.src;
            $('#imageModal').modal('show');
        });
    });
});
</script>
@endsection
