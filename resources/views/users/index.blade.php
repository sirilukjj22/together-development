@extends('layouts.test')

@section('content')

<style>
    .container{
      position: relative;
      padding: 5% 10%;
      margin-top: 40px;
      border-radius: 8px;
      border: 1px solid #aaa;
    }
    .usertopic{
      position: absolute;
      top: 0;
      left: 0;
      margin: 40px;
    }
  </style>
    <div class="container-fluid border rounded-3 p-5 mt-3 bg-white" style="width: 98%;">
        <a href="{{ route('user-create') }}">
            <button type="button" class="submit-button" style="float: right;">เพิ่มผู้ใช้งาน</button>
        </a>

        <div class="usertopic">
            <h1>User (ผู้ใช้งาน)</h1>
        </div>

        <div class="selectall" style="float: left; margin-bottom: 10px;">
            <th><label class="custom-checkbox">
                    <input type="checkbox" onClick="toggle(this)" />
                    <span class="checkmark"></span>
                </label>ทั้งหมด
            </th>
        </div>

        {{-- <button type="button" class="button-4 sa-buttons" style="float: right;">ลบหลายรายการ</button> --}}

        <button class="statusbtn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            สถานะการใช้งาน &#11206;
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="{{ url('users', 'users_all') }}">ทั้งหมด</a>
            <a class="dropdown-item" style="color: green;" href="{{ url('users', 'users_ac') }}">เปิดใช้งาน</a>
            <a class="dropdown-item" style="color: #f44336;" href="{{ url('users', 'users_no') }}">ปิดใช้งาน</a>
        </div>

        <form enctype="multipart/form-data" id="form-id2">
            @csrf
            <table id="example" class="display2">
                <thead>
                    <tr>
                        {{-- <th>
                            <label class="custom-checkbox">
                                <input type="checkbox" onClick="toggle(this)" />
                                <span class="checkmark"></span>
                            </label>ทั้งหมด
                        </th> --}}
                        <th style="text-align: center;">ลำดับ</th>
                        <th>ชื่อผู้ใช้งาน</th>
                        <th>สิทธ์ของผู้ใช้งาน</th>
                        <th>สถานะการใช้งาน</th>
                        <th style="text-align: center;">คำสั่ง</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($users))
                        @foreach ($users as $key => $item)
                            <tr>
                                {{-- <td data-label="เลือก">
                                    <label class="custom-checkbox">
                                        <input name="radio_master_sub[]" type="checkbox" value="{{ $item->id }}">
                                        <span class="checkmark"></span>
                                    </label>
                                </td> --}}
                                <td data-label="#">{{ $key + 1 }}</td>
                                <td data-label="ชื่อผู้ใช้งาน">{{ $item->name }}</td>
                                <td data-label="สิทธิผู้ใช้งาน">
                                    @switch($item->permission)
                                        @case(0)
                                            ผู้ใช้งานทั่วไป
                                        @break

                                        @case(1)
                                            แอดมิน
                                        @break

                                        @case(2)
                                            ผู้พัฒนาระบบ
                                        @break
                                    @endswitch
                                </td>
                                <td data-label="สถานะการใช้งาน">
                                    @if ($item->status == 1)
                                        <button type="button" class="button-1 btn-status"
                                            value="{{ $item->id }}">ใช้งาน
                                        </button>
                                    @else
                                        <button type="button" class="button-3 btn-status"
                                            value="{{ $item->id }}">ปิดใช้งาน
                                        </button>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="button-18 button-17" type="button" data-toggle="dropdown">
                                            ทำรายการ<span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li class="licolor">
                                                <a href="{{ route('user-edit', $item->id) }}">แก้ไขข้อมูล</a>
                                            </li>
                                            {{-- <li class="licolor"><a href="#"
                                                    onclick="deleted({{ $item->id }})">ลบข้อมูล</a>
                                            </li> --}}
                                        </ul>
                                    </div>
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



    @if (isset($_SERVER['HTTPS']) ? 'https' : 'http' == 'https')
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="../assets/bundles/sweetalert2.bundle.js"></script>
    @else
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="../assets/bundles/sweetalert2.bundle.js"></script>
    @endif

    <script type="text/javascript">
        $(document).ready(function() {
            new DataTable('#example', {
                //ajax: 'arrays.txt'
                // scrollX: true,
            });
        });

        function toggle(source) {
            checkboxes = document.getElementsByName('dummy');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }

        $('.btn-status').on('click', function() {
            var id = $(this).val();

            jQuery.ajax({
                type: "GET",
                url: "{!! url('user/change-status/"+id+"') !!}",
                datatype: "JSON",
                async: false,
                success: function(result) {
                    Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                    location.reload();
                },
            });
        });

        function deleted($id) {
            Swal.fire({
                icon: "question",
                title: 'คุณต้องการลบใช่หรือไม่?',
                text: 'หากลบข้อมูลแล้ว ไม่สามารถกู้ข้อมูลคืนได้ !',
                showCancelButton: true,
                confirmButtonText: 'ลบข้อมูล',
                cancelButtonText: 'ยกเลิก',
                confirmButtonColor: "#B22222",
                // cancelButtonColor: "#d33",
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $('#deleteID').val($id);
                    var myform = $('#form-id3').serialize();

                    jQuery.ajax({
                        type: "POST",
                        url: "{!! url('user-delete') !!}",
                        datatype: "JSON",
                        data: myform,
                        async: false,
                        success: function(result) {
                            Swal.fire('ลบข้อมูลเรียบร้อย!', '', 'success');
                            location.reload();
                        },
                    });

                } else if (result.isDenied) {

                    Swal.fire('ลบข้อมูลไม่สำเร็จ!', '', 'info');

                    location.reload();
                }
            })
        }

        // Sweetalert2
        document.querySelector(".sa-buttons").addEventListener('click', function() {
            Swal.fire({
                icon: "question",
                title: 'คุณต้องการลบใช่หรือไม่?',
                text: 'หากลบข้อมูลแล้ว ไม่สามารถกู้ข้อมูลคืนได้ !',
                showCancelButton: true,
                confirmButtonText: 'ลบข้อมูล',
                cancelButtonText: 'ยกเลิก',
                confirmButtonColor: "#B22222",
                // cancelButtonColor: "#d33",
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    var myform = $('#form-id2').serialize();

                    jQuery.ajax({
                        type: "POST",
                        url: "{!! url('user-delete') !!}",
                        datatype: "JSON",
                        data: myform,
                        async: false,
                        success: function(result) {
                            Swal.fire('ลบข้อมูลเรียบร้อย!', '', 'success');
                            location.reload();
                        },
                    });

                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info');
                    location.reload();
                }
            })
        });
    </script>
@endsection
