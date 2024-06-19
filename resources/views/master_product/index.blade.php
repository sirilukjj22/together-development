@extends('layouts.test')

@section('content')
<style> .usertopic{
    position: absolute;
    top: 0;
    left: 0;
    margin: 40px;
  }

  /* อันนี้ style ของ table นะ */
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
  }</style>
    <div  class="container-fluid border rounded-3 p-5 mt-3 bg-white" style="width: 98%;">

        <div class="col-12">
            <button type="button" class="submit-button" onclick="window.location.href='{{ route('Mproduct.create') }}'" style="float: right;" >เพิ่มผู้ใช้งาน</button>
        </div>
        <br><br><br>
        <div class="usertopic">
            <h1>Master Product Item</h1>
        </div>

        <div class="selectall" style="float: left; margin-bottom: 10px;">
            <th><label class="custom-checkbox">
                    <input type="checkbox" onClick="toggle(this)" />
                    <span class="checkmark"></span>
                </label>ทั้งหมด</th>
        </div>

        {{-- <button type="button" class="button-4 sa-buttons" style="float: right;" onclick="showSelectedRecords()">ลบหลายรายการ</button> --}}

        <div>
            <button class="statusbtn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                สถานะการใช้งาน &#11206;
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="{{ route('Mproduct.index') }}">ทั้งหมด</a>
                <a class="dropdown-item" style="color: green;" href="{{ route('Mproduct.ac', ['value' => 1]) }}">เปิดใช้งาน</a>
                <a class="dropdown-item" style="color: #f44336;" href="{{ route('Mproduct.no', ['value' => 0]) }}">ปิดใช้งาน</a>
              </div>
        </div>

        <div>
            <button class="statusbtn" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Product type &#11206;
              </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
            <a class="dropdown-item" href="{{ route('Mproduct.index') }}">ทั้งหมด</a>
            <a class="dropdown-item" href="{{ route('Mproduct.Room_Type', ['value' => 'Room_Type']) }}">Room Type</a>
            <a class="dropdown-item" href="{{ route('Mproduct.Banquet', ['value' => 'Banquet']) }}">Banquet</a>
            <a class="dropdown-item" href="{{ route('Mproduct.Meals', ['value' => 'Meals']) }}">Meals</a>
            <a class="dropdown-item" href="{{ route('Mproduct.Entertainment', ['value' => 'Entertainment']) }}">Entertainment</a>
          </div>
        </div>
        <form enctype="multipart/form-data">
            @csrf
            <table id="example" class="table-hover nowarp" style="width:100%">
                <thead>
                    <tr>
                        <th>
                            <label class="custom-checkbox">
                                <input type="checkbox" onClick="toggle(this)"/>
                                <span class="checkmark"></span>
                            </label>ทั้งหมด
                        </th>
                        <th style="text-align: center;">ลำดับ</th>
                        <th>Product item</th>
                        <th>Name</th>
                        <th>type</th>
                        <th>สถานะการใช้งาน</th>
                        <th style="text-align: center;">คำสั่ง</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($product))
                        @foreach ($product as $key => $item)
                            <tr>
                                <td data-label="เลือก">
                                    <label class="custom-checkbox">
                                    <input name="dummy" type="checkbox" data-record-id="{{ $item->id }}">
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                                <td data-label="#">{{ $key + 1 }}</td>
                                <td data-label="Product item">{{ $item->Category }}</td>
                                <td data-label="Name" style="text-align: left">{{ $item->name_en }}</td>
                                <td data-label="type">{{ $item->type }}</td>
                                <td data-label="สถานะการใช้งาน">
                                    @if ($item->status == 1)
                                        <button type="button" class="button-1 status-toggle" data-id="{{ $item->id }}"data-status="{{ $item->status }}">ใช้งาน</button>
                                    @else
                                        <button type="button" class="button-3 status-toggle " data-id="{{ $item->id }}" data-status="{{ $item->status }}">ปิดใช้งาน</button>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="button-18 button-17" type="button" data-toggle="dropdown">ทำรายการ
                                            <span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <li class="licolor"><a href="{{ url('/Mproduct/edit/'.$item->id) }}">แก้ไขข้อมูล</a></li>
                                        </ul>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </form>
    </div>

    <form id="form-id3">
        @csrf
        <input type="hidden" id="deleteID" name="deleteID" value="">
    </form>
    <script>

</script>

    <script>
         $(document).ready(function() {
            new DataTable('#example', {
                columnDefs: [
                    {
                        className: 'dtr-control',
                        orderable: true,
                        target: null
                    },
                    { width: '10%', targets: 0 },
                    { width: '10%', targets: 1 },
                    { width: '10%', targets: 2 },
                    { width: '25%', targets: 3 },
                    { width: '13%', targets: 4 },
                    { width: '13%', targets: 5 },
                    { width: '13%', targets: 6 },

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
            url: "{{ url('/Mproduct/change-Status/') }}" + '/' + id + '/' + status,
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



// function confirmDelete(id) {
//     Swal.fire({
//         title: "คุณต้องการลบใช่หรือไม่?",
//         text: "หากลบข้อมูลแล้ว ไม่สามารถกู้ข้อมูลคืนได้ !",
//         icon: "question",
//         showCancelButton: true,
//         confirmButtonText: "ลบข้อมูล",
//         cancelButtonText: "ยกเลิก",
//         confirmButtonColor: "#B22222",
//         dangerMode: true,
//     }).then((willDelete) => {
//         if (willDelete.isConfirmed) {
//             // ถ้าผู้ใช้คลิก "ตกลง"
//             var token = "{{ csrf_token() }}";
//             $.ajaxSetup({
//                 headers: {
//                     'X-CSRF-TOKEN': token
//                 }
//             });
//             $.ajax({
//                 type: "POST",
//                 url: "{{ url('/Mbooking/delete/') }}" + '/' + id,
//                 dataType: "JSON",
//                 success: function(result) {
//                     Swal('ลบข้อมูลเรียบร้อย!', '', 'success');
//                 },
//                 error: function() {
//                     Swal.fire('Changes are not saved', '', 'error');
//                 }
//             });
//         } else {
//             // ถ้าผู้ใช้คลิก "ยกเลิก"
//             Swal.fire('Changes are not saved');
//         }
//     });
//     return false; // เพื่อป้องกันการนำลิงก์ไปยัง URL หลังจากแสดง SweetAlert2
// }


        // function confirmDelete(id) {
        //     Swal.fire({
        //         title: "คุณต้องการลบใช่หรือไม่?",
        //         text: "หากลบข้อมูลแล้ว ไม่สามารถกู้ข้อมูลคืนได้ !",
        //         icon: "question",
        //         showCancelButton: true,
        //         confirmButtonText: "ลบข้อมูล",
        //         cancelButtonText: "ยกเลิก",
        //         confirmButtonColor: "#B22222",
        //         dangerMode: true,
        //     }).then((willDelete) => {
        //         if (willDelete.isConfirmed) {
        //             // ถ้าผู้ใช้คลิก "ตกลง"
        //             var token = "{{ csrf_token() }}";
        //             $.ajax({
        //                 type: "POST",
        //                 url: "{{ url('/Mbooking/delete/') }}" + '/' + id,
        //                 dataType: "JSON",
        //                 _token: token,
        //                 success: function(result) {
        //                     Swal.fire('ลบข้อมูลเรียบร้อย!', '', 'success');

        //                 },
        //                 error: function() {
        //                     Swal.fire('Changes are not saved', '', 'info');

        //                 }
        //             });
        //         } else {
        //             // ถ้าผู้ใช้คลิก "ยกเลิก"
        //             Swal.fire('Changes are not saved');
        //         }
        //     });
        //     return false; // เพื่อป้องกันการนำลิงก์ไปยัง URL หลังจากแสดง SweetAlert2
        // }


        // function confirmDelete($id) {
        // Swal.fire({
        // icon: "question",
        // title: 'คุณต้องการลบใช่หรือไม่?',
        // text: 'หากลบข้อมูลแล้ว ไม่สามารถกู้ข้อมูลคืนได้ !',
        // showCancelButton: true,
        // confirmButtonText: 'ลบข้อมูล',
        // cancelButtonText: 'ยกเลิก',
        // confirmButtonColor: "#B22222",
        // // cancelButtonColor: "#d33",
        // }).then((result) => {
        //     /* Read more about isConfirmed, isDenied below */
        //     if (result.isConfirmed) {
        //         $('#deleteID').val($id);
        //         var myform = $('#form-id3').serialize();

        //         jQuery.ajax({
        //         type:   "POST",
        //         url:    "{!! url('/Mbooking/delete/') !!}",
        //         datatype:   "JSON",
        //         data: myform,
        //         async:  false,
                // success: function(result) {
                //     Swal.fire('ลบข้อมูลเรียบร้อย!', '', 'success');
                //     location.reload();

                //     },
                // });

                // } else if (result.isDenied) {
                //     Swal.fire('Changes are not saved', '', 'info');
                //     location.reload();
                // }
        //     })
        // }

        // Sweetalert2





    </script>
@endsection
