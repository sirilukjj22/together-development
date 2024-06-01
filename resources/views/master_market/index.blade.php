@extends('layouts.test')

@section('content')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div class="Usertable">
        <a href="{{ route('Mmarket.create') }}">
            <button type="button" class="submit-button" style="float: right;" >เพิ่มผู้ใช้งาน</button></a>
        <div class="usertopic">
            <h1>Master Market</h1>
        </div>

        <div class="selectall" style="float: left; margin-bottom: 10px;">
            <th><label class="custom-checkbox">
                    <input type="checkbox" onClick="toggle(this)" />
                    <span class="checkmark"></span>
                </label>ทั้งหมด</th>
        </div>

        {{-- <button type="button" class="button-4 sa-buttons" style="float: right;" onclick="showSelectedRecords()">ลบหลายรายการ</button> --}}


        <button class="statusbtn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            สถานะการใช้งาน &#11206;
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="{{ route('Mmarket.index') }}">ทั้งหมด</a>
            <a class="dropdown-item" style="color: green;" href="{{ route('Mmarket.ac', ['value' => 1]) }}">เปิดใช้งาน</a>
            <a class="dropdown-item" style="color: #f44336;" href="{{ route('Mmarket.no', ['value' => 0]) }}">ปิดใช้งาน</a>
          </div>

        <form enctype="multipart/form-data" id="form-id2">
            @csrf
            <table id="example" class="display3 display2">
                <thead>
                    <tr>
                        <th>
                            <label class="custom-checkbox">
                                <input type="checkbox" onClick="toggle(this)"/>
                                <span class="checkmark"></span>
                            </label>ทั้งหมด
                        </th>
                        <th style="text-align: center;">ลำดับ</th>
                        <th>ประเภทบริษัทไทย</th>
                        <th>ประเภทบริษัทอังกฤษ</th>
                        <th>สถานะการใช้งาน</th>
                        <th style="text-align: center;">คำสั่ง</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($Mmarket))
                        @foreach ($Mmarket as $key => $item)
                            <tr>
                                <td data-label="เลือก">
                                    <label class="custom-checkbox">
                                    <input name="dummy" type="checkbox" data-record-id="{{ $item->id }}">
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                                <td data-label="#">{{ $key + 1 }}</td>
                                <td data-label="คำนำหน้าไทย">{{ $item->name_th }}</td>
                                <td data-label="คำนำหน้าอังกฤษ">{{ $item->name_en }}</td>

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
                                            <li class="licolor"><a href="{{ url('/Mmarket/edit/'.$item->id) }}">แก้ไขข้อมูล</a></li>
                                            {{-- <li class="licolor"><a href="#" class="delete" title="Delete" data-toggle="tooltip" onclick="confirmDelete({{ $item->id }})">ลบข้อมูล</li> --}}
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

    @if (isset($_SERVER['HTTPS']) ? 'https' : 'http' == 'https')
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>


    @else
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>

    @endif
    <script>

</script>

    <script>
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
            url: "{{ url('/Mmarket/change-Status/') }}" + '/' + id + '/' + status,
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
