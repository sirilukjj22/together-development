@extends('layouts.test')

@section('content')
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
        background: url('{{ asset($product->image_product)}}') no-repeat center center;
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
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    padding-top: 60px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0,0,0);
    background-color: rgba(0,0,0,0.4);
}

.modal-content {
    display: flex;
    flex-direction: column;
    align-items: center; /* จัดตำแหน่งลูกในแนวนอน */
    justify-content: center; /* จัดตำแหน่งลูกในแนวตั้ง */
    background-color: #fefefe;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

</style>
    <div  class="container-fluid border rounded-3 p-5 mt-3 bg-white" style="width: 98%;">
        <div class="usertopic">
            <h1>Master Product Item</h1>
        </div>
        <br>
        <form action="{{url('/Mproduct/master_Mproduct/Mproduct_update/'.$product->id)}}" method="POST"enctype="multipart/form-data">
        {!! csrf_field() !!}
            <div>
                <div class="col-12 row" >
                    <div class="col-5">
                        <form id="image_upload_form" enctype="multipart/form-data">
                            <div class="card">
                                <div class="image-container">
                                    <button type="button" class="image-upload-button"></button>
                                    <input type="file" name="imageFile" id="imageFile" accept="image/jpeg, image/png, image/svg"  style="display: none;">
                                    <img src="" width="460" height="300" class="image_preview" style="display: none;">
                                    <button class="deleteImage"  type="button"id="deleteImage"style="display: none;"></button>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="input-group mt-5 " style="float: left;width:80%; ">
                            <input type="file" class="form-control " name="image_other" id="image_other" aria-describedby="image_other" aria-label="Upload" multiple>
                        </div>
                    </div>
                    <div class="col-7 ">
                        <div class="row">
                            <div class="col-4">
                                <label for="Profile_ID">Profile ID</label><br>
                                <input type="text" id="Profile_ID" name="Profile_ID"maxlength="70" disabled value="{{$product->Product_ID}}">
                            </div>
                            <div class="col-4">
                                <label for="category">Category</label>
                                <select name="category" id="category" class="select2" disabled>
                                    <option value="Room_Type"{{$product->Category == "Room_Type" ? 'selected' : ''}}>Room Type</option>
                                    <option value="Banquet"{{$product->Category == "Banquet" ? 'selected' : ''}}>Banquet</option>
                                    <option value="Meals"{{$product->Category == "Meals" ? 'selected' : ''}}>Meals</option>
                                    <option value="Entertainment"{{$product->Category == "Entertainment" ? 'selected' : ''}}>Entertainment</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12" >
                                <label for="name_th" >Name_th</label><br>
                                <input type="text" id="name_th" name="name_th"maxlength="70" value="{{$product->name_th}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12" >
                                <label for="name_en" >Name_en</label><br>
                                <input type="text" id="name_en" name="name_en"maxlength="70" value="{{$product->name_en}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12" ><label for="detail_th">Detail_th</label><br>
                                <input type="text" id="detail_th" name="detail_th"maxlength="70" value="{{$product->detail_th}}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12" ><label for="detail_en">Detail_en</label><br>
                                <input type="text" id="detail_en" name="detail_en"maxlength="70" value="{{$product->detail_en}}">
                            </div>
                        </div>

                        <div class=" row">
                            <div class="col-6" ><label for="pax">Pax</label><br>
                                <input type="text" id="pax" name="pax"maxlength="70"value="{{$product->pax}}" >
                            </div>
                            <div class="col-6" ><label for="room_size">Room size (Sqm.)</label><br>
                                <input type="text" id="room_size" class="price-input" name="room_size"maxlength="70" value="{{$product->room_size}}">
                            </div>
                        </div>
                        <div class=" row">
                            <div class="col-3" >
                                <label for="normal_price">Normal Price <br>(Include VAT)</label><br>
                                <input type="text"  class="price-input"id="normal_price" name="normal_price"maxlength="70" value="{{$product->normal_price}}">
                            </div>
                            <div class="col-3">
                                <label for="weekend_price">Weekday Price (Include VAT)</label><br>
                                <input type="text"  class="price-input"id="weekend_price" name="weekend_price"maxlength="70"value="{{$product->weekend_price}}">
                            </div>
                            <div class="col-3" >
                                <label for="long_weekend_price">Long Weekend Price (Include VAT)</label><br>
                                <input type="text"  class="price-input"id="long_weekend_price" name="long_weekend_price"maxlength="70"value="{{$product->long_weekend_price}}">
                            </div>
                            <div class="col-3" >
                                <label for="end_weekend_price">End Weekend Price (Include VAT)</label><br>
                                <input type="text"  class="price-input"id="end_weekend_price" name="end_weekend_price"maxlength="70"value="{{$product->end_weekend_price}}">
                            </div>
                        </div>
                        <div class=" row">
                            <div class="col-4" ><label for="Quantity">Quantity</label><br>
                                <select name="quantity" id = "quantity" class="select2">
                                    <option value=""></option>
                                    @foreach($quantity as $item)
                                        <option value="{{ $item->id }}"{{$product->quantity== $item->id ? 'selected' : ''}}>{{ $item->name_th }} ({{ $item->name_en }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4" ><label for="Unit">Unit</label><br>
                                <select name="unit" id = "unit" class="select2">
                                    <option value=""></option>
                                    @foreach($unit as $item)
                                        <option value="{{ $item->id }}"{{$product->unit== $item->id ? 'selected' : ''}}>{{ $item->name_th }} ({{ $item->name_en }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4" ><label for="Maximum_Discount">Maximum Discount</label><br>
                                <input type="text" id="Maximum_Discount" name="Maximum_Discount"maxlength="70" required  value="{{$product->maximum_discount}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 row mt-2">
                        <div class="col-2"></div>
                        <div class="col-4">
                            <div class="button-guest">
                                <button type="submit" class="btn">ตกลง</button>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="button-guest-end">
                                <button type="button" class="btn" onclick="window.location.href='{{ route('Mproduct.index') }}'" >{{ __('ย้อนกลับ') }}</button>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </form>
        <script>
            // เลือกองค์ประกอบทั้งหมดที่มีคลาส "price-input"
            var priceInputs = document.querySelectorAll('.price-input');

            // ฟังก์ชันในการจัดรูปแบบตัวเลข
            function formatNumberWithCommas(value) {
                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }

            // ติดตั้ง event listener สำหรับแต่ละฟิลด์ input
            priceInputs.forEach(function(input) {
                input.addEventListener('blur', function() {
                    var value = parseFloat(this.value).toFixed(2);
                    if (!isNaN(value)) {
                        this.value = formatNumberWithCommas(value);
                    }
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
        // ส่งรูปภาพไปยังเซิร์ฟเวอร์เมื่อฟอร์มถูกส่ง
        $(document).on("click", '#imageSubmit', function(e) {
            const fileInput = document.getElementById('imageFile');
            const file = fileInput.files[0];
            var formData = new FormData();

            formData.append('image', file);

            $.ajax({
                type: 'POST',
                url: "{{url('/Mproduct/master_Mproduct/Mproduct_update/'.$product->id)}}", // ตรวจสอบ URL
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
</script>

@endsection
