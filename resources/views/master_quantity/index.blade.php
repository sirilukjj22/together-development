@extends('layouts.test')

@section('content')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<!-- Bootstrap Bundle with Popper -->
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
    .statusbtn1{
        border-style: solid;
        border-radius: 8px;
        border-width: 1px;
        border-color: #9a9a9a;
        margin-left: 10px;
        width: 45%;
        height: 40px;
        border-radius: 8px;
        float: right;
        color: #000000;
        margin: 0;
        margin-left: 10px;
        margin-bottom: 10px;

      }
      .create{
        background-color: #109699 !important;
        color: white !important;
        text-align: center;
        border-radius: 8px;
        border-color: #9a9a9a;
        border-style: solid;
        border-width: 1px;
        width: 35%;
        height: 40px;
        padding-top: 6px;
        float: right;
    }
    .statusbtndiv{
        float: right;
    }
    .dtr-details {
        width: 100%;
    }

    .dtr-title {
        float: left;
        text-align: left;
        margin-right: 10px;
    }

    .dtr-data {
        display: block;
        text-align: right !important;
    }

    .dt-container .dt-paging .dt-paging-button {
        padding: 0 !important;
    }
    @media (max-width: 768px) {
        h1{
        margin-top:32px;
        }
        .create{
            width: 100%!important;
            font-size: 14px;
            padding: 5px;
        }
        .statusbtndiv{
            width: 100%;
        }
        .statusbtn1{
            border-style: solid;
            border-radius: 8px;
            border-width: 1px;
            border-color: #9a9a9a;
            margin-left: 10px;
            width: 100%;
            height: 40px;
            border-radius: 8px;
            float: right;
            color: #000000;
            margin: 0;
            margin-left: 10px;
            margin-bottom: 10px;
        }

        .dropdown-menu {
            width: 10%;
        }
    }
    @media (max-width: 1368px) {
        .create{
            width: 100%!important;
            font-size: 14px;
            padding: 5px;
        }
        .statusbtndiv{
            width: 100%;
        }
        .statusbtn1{
            border-style: solid;
            border-radius: 8px;
            border-width: 1px;
            border-color: #9a9a9a;
            margin-left: 10px;
            width: 50%;
            height: 40px;
            border-radius: 8px;
            float: right;
            color: #000000;
            margin: 0;
            margin-left: 10px;
            margin-bottom: 10px;
        }
    }
</style>
    <div  class="container-fluid border rounded-3 p-5 mt-3 bg-white" style="width: 98%;">

        <h1>Master Quantity Item</h1>
        <div class="col-lg-12" style="float: right">
            <div  class="col-lg-4" style="float: right">
                <button type="button" class="create"  data-bs-toggle="modal" data-bs-target="#QuantityCreate">
                    + Add Quantity
                </button>
            </div>
        </div>

            <div  class="col-4 mt-3 statusbtndiv">
                <button class="statusbtn1" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    สถานะการใช้งาน &#11206;
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ route('Mproduct.index.quantity') }}">ทั้งหมด</a>
                    <a class="dropdown-item" style="color: green;" href="{{ route('Mproduct.quantity.ac', ['value' => 1]) }}">เปิดใช้งาน</a>
                    <a class="dropdown-item" style="color: #f44336;" href="{{ route('Mproduct.quantity.no', ['value' => 0]) }}">ปิดใช้งาน</a>
                </div>
            </div>

        <form enctype="multipart/form-data">
            @csrf
            <table id="example" class="table-hover nowarp" style="width:100%">
                <thead>
                    <tr>
                        <th style="text-align: center;">ลำดับ</th>
                        <th>Name th</th>
                        <th>Name En</th>
                        <th>สถานะการใช้งาน</th>
                        <th style="text-align: center;">คำสั่ง</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($quantity))
                        @foreach ($quantity as $key => $item)
                            <tr>
                                <td data-label="#">{{ $key + 1 }}</td>
                                <td data-label="Name">{{ $item->name_th }}</td>
                                <td data-label="Name">{{ $item->name_en }}</td>
                                <td data-label="สถานะการใช้งาน">
                                    @if ($item->status == 1)
                                        <button type="button" class="button-1 status-toggle" data-id="{{ $item->id }}"data-status="{{ $item->status }}">ใช้งาน</button>
                                    @else
                                        <button type="button" class="button-3 status-toggle " data-id="{{ $item->id }}" data-status="{{ $item->status }}">ปิดใช้งาน</button>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown-a">
                                    <button class="button-18 button-17" type="button" data-toggle="dropdown">ทำรายการ
                                        <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li class="licolor"><a  data-bs-toggle="modal" data-bs-target="#editquantity{{$item->id}}">แก้ไขข้อมูล</a></li>
                                        {{-- <li class="licolor"><a href="{{ url('/Mproduct/edit/'.$item->id) }}">แก้ไขข้อมูล</a></li> --}}
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </form>
    </div>
    @foreach ($quantity as $item)
    <div class="modal fade" id="editquantity{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header"style=" background-color: #2D7F7B;">
                <h1 class="modal-title fs-5" id="staticBackdropLabel" style=" color: #FFFFFF;">Edit Quantity</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style=" background-color: #FFFFFF;" aria-label="Close"></button>
              </div>
            <div class="modal-body">
                <form action="{{ url('/Mproduct/Quantity/edit/') }}" method="POST"enctype="multipart/form-data">
                    @csrf
                    <div class="col-12 row">
                        <input type="hidden" id="id" name="id" value="{{ $item->id}}">
                        <div class="col-6">
                            <label for="Name_th">Name th</label><br>
                            <input type="text" id="name_th" name="name_th"maxlength="70" value="{{$item->name_th}}" >
                        </div>
                        <div class="col-6">
                            <label for="Name_en">Name en </label><br>
                            <input type="text" id="name_en" name="name_en"maxlength="70" value="{{$item->name_en}}">
                        </div>
                    </div>
                    <div class="modal-footer mt-2">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn " style="background-color: #2D7F7B; color: #FFFFFF;">Save</button>
                      </div>
                </form>
            </div>
          </div>
        </div>
    </div>
    @endforeach
    <form id="form-id3">
        @csrf
        <input type="hidden" id="deleteID" name="deleteID" value="">
    </form>
    {{-- modal create --}}
    <div class="modal fade" id="QuantityCreate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header"style=" background-color: #2D7F7B;">
              <h1 class="modal-title fs-5" id="staticBackdropLabel" style=" color: #FFFFFF;">+ Add Quantity</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" style=" background-color: #FFFFFF;" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('Mproduct.save.quantity')}}" method="POST"enctype="multipart/form-data">
                    @csrf
                    <div class="col-12 row">
                        <div class="col-6">
                            <label for="Name_th">Name th</label><br>
                            <input type="text" id="name_th" name="name_th"maxlength="70" >
                        </div>
                        <div class="col-6">
                            <label for="Name_en">Name en </label><br>
                            <input type="text" id="name_en" name="name_en"maxlength="70" >
                        </div>
                    </div>
                    <div class="modal-footer mt-2">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn " style="background-color: #2D7F7B; color: #FFFFFF;">Save</button>
                      </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        new DataTable('#example', {
            columnDefs: [
                {
                    className: 'dtr-control',
                    orderable: true,
                    target: null
                },
                { width: '5%', targets: 0 },
                { width: '25%', targets: 1 },
                { width: '25%', targets: 2 },
                { width: '10%', targets: 3 },
                { width: '10%', targets: 4 },
            ],
            order: [0, 'asc'],
            responsive: {
                details: {
                    type: 'column',
                    target: 'tr'
                }
            }
        });
    });
    function toggle(source) {
        checkboxes = document.getElementsByName('dummy');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = source.checked;
        }
    }


    // หากมีการส่งค่า alert มาจากหน้าอื่น
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
    <script type="text/javascript">
        $(document).ready(function() {
    $('.status-toggle').click(function() {
        var id = $(this).data('id');
        var status = $(this).data('status');
        var token = "{{ csrf_token() }}"; // รับ CSRF token จาก Laravel
        // ทำ AJAX request
        $.ajax({
            type: 'GET',
            url: "{{ url('/Mproduct/changeStatus_quantity/') }}" + '/' + id + '/' + status,
            success: function(response) {
                // ปรับเปลี่ยนสถานะบนหน้าเว็บ
                console.log(response.success);
                if (status == 1) {
                    // เปลี่ยนสถานะจากเปิดเป็นปิด
                    $(this).data('status', 0);
                    $(this).removeClass('btn-success').addClass('btn-danger').html('Deactivate');
                    Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                     location.reload();
                } else  {
                    // เปลี่ยนสถานะจากปิดเป็นเปิด
                    $(this).data('status', 1);
                    $(this).removeClass('btn-danger').addClass('btn-success').html('Activate');
                    Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                     location.reload();
                }
            }
        });
    });
});


</script>
@endsection
