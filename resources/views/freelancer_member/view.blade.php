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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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
        background: url('{{ asset($Freelancer_Main->Imagefreelan) }}') ;
    }

    .image-upload-button {
        position: absolute;
        bottom: 10px; /* ตำแหน่งจากด้านล่าง */
        right: 10px; /* ตำแหน่งจากด้านขวา */
        width: 32px; /* ขนาดของปุ่ม */
        height: 32px; /* ขนาดของปุ่ม */
        background: url('{{ asset('assets2/images/pepicons-pencil--photo-camera-circle-filled.png') }}') no-repeat center center;
        background-size: cover;
        border: none; /* ไม่มีเส้นขอบ */
        border-radius: 50%; /* ทำให้ปุ่มเป็นวงกลม */
        cursor: pointer;* เปลี่ยนรูปแบบของ cursor เมื่อวางเหนือปุ่ม */
        box-shadow: 0 0 5px 2px rgba(255, 255, 255, 0.8);
    }
    .buttonIcon {
        position: absolute;
        bottom: 10px; /* ตำแหน่งจากด้านล่าง */
        right: 50px; /* ตำแหน่งจากด้านขวา */
        width: 32px; /* ขนาดของปุ่ม */
        height: 35px;
        background: url('{{ asset('assets2/images/verified.png') }}') no-repeat center center;
        background-size: cover;
        border: none; /* ไม่มีเส้นขอบ */
        border-radius: 50%; /* ทำให้ปุ่มเป็นวงกลม */
        cursor: pointer; /* เปลี่ยนรูปแบบของ cursor เมื่อวางเหนือปุ่ม */
        box-shadow: 0 0 5px 2px rgba(255, 255, 255, 0.8);/* เพิ่มเส้นขอบสี */
    }
    .deleteImage{
        position: absolute;
        bottom: 10px; /* ตำแหน่งจากด้านล่าง */
        right: 10px; /* ตำแหน่งจากด้านขวา */
        width: 32px; /* ขนาดของปุ่ม */
        height: 32px; /* ขนาดของปุ่ม */
        background: url('{{ asset('assets2/images/multiply.png') }}') no-repeat center center;
        background-size: cover;
        border: none; /* ไม่มีเส้นขอบ */
        border-radius: 50%; /* ทำให้ปุ่มเป็นวงกลม */
        cursor: pointer;* เปลี่ยนรูปแบบของ cursor เมื่อวางเหนือปุ่ม */
        box-shadow: 0 0 5px 2px rgba(255, 255, 255, 0.8);
    }
    .centered-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%; /* ปรับตามความสูงที่ต้องการ */
            text-align: center;
        }
        .input-group .form-control,
        .input-group .input-group-text {
            height: 100%; /* กำหนดความสูงให้เต็มที่ */
        }
        .input-group .form-control {
            height: 38px; /* ความสูงมาตรฐานของอินพุตใน Bootstrap */
        }
        .input-group .input-group-text {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .table th {

            padding: 8px;
            text-align: left; /* จัดข้อความในส่วนหัวให้อยู่ชิดซ้าย */
        }
        .table td {

            padding: 8px;
            text-align: left; /* จัดข้อความในเซลล์ให้อยู่ชิดซ้าย */
        }
        .image-container {
            width: 100%;
            height: 100%;
            background: url('{{ asset($Freelancer_Main->Imagefreelan) }}') no-repeat center center;
            background-size: cover;
            position: relative;
        }

        .image-upload-button {
            position: absolute;
            bottom: 10px; /* ตำแหน่งจากด้านล่าง */
            right: 10px; /* ตำแหน่งจากด้านขวา */
            width: 32px; /* ขนาดของปุ่ม */
            height: 32px; /* ขนาดของปุ่ม */
            background: url('{{ asset('assets2/images/pencil.png') }}') no-repeat center center;
            background-size: cover;
            border: none; /* ไม่มีเส้นขอบ */
            border-radius: 50%; /* ทำให้ปุ่มเป็นวงกลม */
            cursor: pointer;* เปลี่ยนรูปแบบของ cursor เมื่อวางเหนือปุ่ม */
            box-shadow: 0 0 5px 2px rgba(255, 255, 255, 0.8);
        }
        .image-read-button {
            background: url('{{ asset('assets2/images/analytics.png') }}') no-repeat center center;
            background-size: cover;
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 50%;
        }

    </style>
    <div class="Usertable">
        <div class="usertopic">
            <h1>Agent (member)</h1>
        </div>
        <div class="Usertable" >
            <div class="row g-0">
              <div class="col-md-3" >
                <div class="image-container">
                    <button type="button" class="image-upload-button"  onclick="window.location.href = '{{ url('/Freelancer/member/edit/'.$Freelancer_Main->id) }}'"></button>
                    </button>
                </div>
              </div>
              <div class="col-md-9">
                <div class="card-body">
                    <div class="col-12" >
                        <div class="row" >
                            <div class="col-3" >
                                <label style=" font-size: 16px; font-weight: bold;">Date of birth</label>
                                <div class="row" >
                                    <div class="col-2">
                                        <span  style=" font-size: 40px; font-weight: bold;">{{ $day }}</span>
                                    </div>
                                    <div class="col-1"></div>
                                    <div class="col-2">
                                        <span >{{ $month }}</span><br>
                                        <span >{{ $monthYear }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 " >
                                <label style=" font-size: 16px; font-weight: bold;">Email</label>
                                <label style=" font-size: 16px;">{{$Freelancer_Main->Email}}</label>
                            </div>
                            <div class="col-2 " >
                                <label style=" font-size: 16px; font-weight: bold;">Phone</label>
                                <label style=" font-size: 16px;">{{ $phoneM['Phone_number'] }}</label>
                            </div>
                            <div class="col-4 " >
                                <label style=" font-size: 16px; font-weight: bold;">Identification </label>
                                <label style=" font-size: 16px;">{{$Freelancer_Main->Identification_number}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-2" >
                        <div class="row" >
                            <div class="col-6">
                                <label style=" font-size: 20px; font-weight: bold;">ชื่อ {{$Freelancer_Main->First_name}}
                                    {{$Freelancer_Main->Last_name}} <label>(</label> {{$Freelancer_Main->Profile_ID}}<label>)</label></label><br>
                                <label style=" font-size: 16px;">วันที่สมัคร <label>{{$First_day_work}}</label> </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-3 centered-container">
                                <label style=" font-size: 16px; font-weight: bold;">ยอดขายทั้งหมด</label>
                                <label style=" font-size: 16px;">2,000</label>
                            </div>
                            <div class="col-4 centered-container">
                                <label style=" font-size: 16px; font-weight: bold;">รายได้ 20% จากยอดขาย</label>
                                <label style=" font-size: 16px;">400</label>
                            </div>
                            <div class="col-2 centered-container">
                                <label style=" font-size: 16px; font-weight: bold;">ยอดเบิก</label>
                                <label style=" font-size: 16px;">400</label>
                            </div>
                            <div class="col-3 centered-container">
                                <label style=" font-size: 16px; font-weight: bold;">ยอดคงเหลือ </label>
                                <label style=" font-size: 16px;">0</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 ">
                        <div class="row">
                            <div class="col-8 row  centered-container">
                                <div class="input-group ">
                                    <input type="text" aria-label="Last name" class="form-control"  placeholder="พิมพ์ยอดเงินที่ต้องการใช้" >
                                    <button class="input-group-text mt-2">เบิกค่าคอมมิชชั่น</button>
                                </div>
                            </div>
                            <div class="col-4 mt-2">
                                <button class="button-17 button-18" onclick="window.location.href = '{{ url('/Freelancer/member/order_list/'.$Freelancer_Main->id) }}'">ส่งรายละเอียดลูกค้า</button>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
            </div>
        </div>
    </div>
    <div class="Usertable" >
        <label >รายการส่งรายละเอียดลูกค้า</label>
        <table  class="table table-hover">
            <thead class="table-secondary">
              <tr>
                <th scope="col" style="width: 10%">วันที่ส่ง</th>
                <th scope="col">ชื่อบริษัท</th>
                <th scope="col"style="width: 10%">ดูข้อมูล</th>
                {{-- ชื่อ user ที่กดรับทราบ ถ้าไม่ให้รอ --}}
                <th scope="col"style="width: 15%">สถานะ </th>
                <th scope="col"style="width: 20%">Receive By</th>
              </tr>
            </thead>
            <tbody>
                @if (!empty($Company_massage))
                    @foreach ($Company_massage as $key => $item)
                    <tr>
                        <th>{{ $item->created_at->format('d/m/Y') }}</th>
                        <td>{{ $item->Company_Name }}</td>
                        <td>
                            <button type="button" class="image-read-button" onclick="window.location.href = '{{ url('/Freelancer/member/view/data/'.$Freelancer_Main->id.'/'.$item->id ) }}'"></button>
                        </td>
                        @if($item->status == 1)
                            <td>
                                <label style=" font-size: 16px; color:#7c807c">รอตรวจสอบ </label>
                            </td>
                        @elseif ($item->status == 2)
                            <td>
                                <label style=" font-size: 16px; color:#10e426">อนุมัติแล้ว </label>
                            </td>
                        @else
                            <td>
                                <label style=" font-size: 16px; color:#7c807c">รอตรวจสอบ </label>
                            </td>
                        @endif
                        <td>{{ $item->Operated_by }}</td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
          </table>
    </div>
<script>
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
</script>
@endsection
