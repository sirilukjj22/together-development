@extends('layouts.test')

@section('content')
    <div class="Usertable">
        <div class="col-12">
            <button type="button" class="submit-button" onclick="window.location.href='{{ route('Quotation.create') }}'" style="float: right;" >เพิ่มผู้ใช้งาน</button>
        </div>
        <div class="usertopic">
            <h1>Quotation</h1>
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
                <a class="dropdown-item" href="{{ route('Quotation.index') }}">ทั้งหมด</a>
                <a class="dropdown-item" style="color: green;" href="{{ route('Quotation.ac', ['value' => 1]) }}">เปิดใช้งาน</a>
                <a class="dropdown-item" style="color: #f44336;" href="{{ route('Quotation.no', ['value' => 0]) }}">ปิดใช้งาน</a>
            </div>
        </div>
        <form enctype="multipart/form-data">
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
                        <th>Product item</th>
                        <th>Name</th>
                        <th>type</th>
                        <th>สถานะการใช้งาน</th>
                        <th style="text-align: center;">คำสั่ง</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($Quotation))
                        @foreach ($Quotation as $key => $item)
                            <tr>
                                <td data-label="เลือก">
                                    <label class="custom-checkbox">
                                    <input name="dummy" type="checkbox" data-record-id="{{ $item->id }}">
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                                <td data-label="#">{{ $key + 1 }}</td>
                                <td data-label="Product item">{{ $item->Category }}</td>
                                <td data-label="Name">{{ $item->name_en }}</td>
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


    // หากมีการส่งค่า alert มาจากหน้าอื่น
    var alertMessage = "{{ session('success') }}";
    var alerterror = "{{ session('error') }}";
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
</script>
@endsection
