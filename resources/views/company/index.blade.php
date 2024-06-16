@extends('layouts.test')

@section('content')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div class="container-fluid border rounded-3 p-5 mt-3 bg-white" style="width: 98%;">
        <a href="{{ route('Company.create') }}">
            <button type="button" class="submit-button" style="float: right;">เพิ่มผู้ใช้งาน</button></a>
        <div class="usertopic">
            <h1>Company / Agent</h1>
        </div>

        <div class="selectall" style="float: left; margin-bottom: 10px;">
            <th><label class="custom-checkbox">
                    <input type="checkbox" onClick="toggle(this)" />
                    <span class="checkmark"></span>
                </label>ทั้งหมด</th>
        </div>
        <button class="statusbtn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            สถานะการใช้งาน &#11206;
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="{{ route('Company.index') }}">ทั้งหมด</a>
            <a class="dropdown-item" style="color: green;" href="{{ route('Company.ac', ['value' => 1]) }}">เปิดใช้งาน</a>
            <a class="dropdown-item" style="color: #f44336;" href="{{ route('Company.no', ['value' => 0]) }}">ปิดใช้งาน</a>
        </div>

        <form enctype="multipart/form-data" id="form-id2">
            @csrf
            <table id="example" class="display3 display2">
                <thead>
                    <tr>
                        <th>
                            <label class="custom-checkbox">
                                <input type="checkbox" onClick="toggle(this)" />
                                <span class="checkmark"></span>
                            </label>ทั้งหมด
                        </th>
                        <th style="text-align: center;">ลำดับ</th>
                        <th style="text-align: center;">รหัสโปรไฟล์</th>
                        <th>ชื่อองค์กร</th>
                        <th>สาขา</th>
                        <th>Booking Channal</th>
                        <th>สถานะการใช้งาน</th>
                        <th style="text-align: center;">คำสั่ง</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($Company))
                        @foreach ($Company as $key => $item)
                            <tr>
                                <td data-label="เลือก">
                                    <label class="custom-checkbox">
                                        <input name="dummy" type="checkbox" data-record-id="{{ $item->id }}">
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                                <td data-label="#">{{ $key + 1 }}</td>
                                <td data-label="รหัสลูกค้า">{{ $item->Profile_ID }}</td>
                                <td data-label="ตัวย่อ">{{ $item->Company_Name }}</td>
                                <td data-label="ชื่อผู้ใช้งาน">{{ $item->Branch }}</td>

                                <td>
                                    @php
                                        $Mbooking = explode(',', $item->Booking_Channel);

                                        foreach ($Mbooking as $key => $value) {
                                            $bc = App\Models\master_document::find($value);
                                            echo $bc->name_en . '<br>';
                                        }

                                    @endphp
                                </td>
                                <td data-label="สถานะการใช้งาน">
                                    @if ($item->status === 1)
                                        <button type="button" class="button-1 status-toggle"
                                            data-id="{{ $item->id }}"data-status="{{ $item->status }}">ใช้งาน</button>
                                    @else
                                        <button type="button" class="button-3 status-toggle "
                                            data-id="{{ $item->id }}"
                                            data-status="{{ $item->status }}">ปิดใช้งาน</button>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="button-18 button-17" type="button" data-toggle="dropdown">ทำรายการ
                                            <span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <li class="licolor"><a
                                                    href="{{ url('/Company/edit/' . $item->id) }}">แก้ไขข้อมูล</a></li>
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
    <script></script>

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
        //เลือกแล้วลบ
        var Guestid = [];

        function showSelectedRecords() {
            var checkboxes = document.querySelectorAll('input[name="dummy"]:checked');

            checkboxes.forEach(function(checkbox) {
                var id = checkbox.dataset.recordId;
                Guestid.push(id);
                confirmDeletecheck(id);
                console.log('Record ID:', id);
            });
        }

        function confirmDeletecheck(id) {
            var token = "{{ csrf_token() }}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': token
                }
            });

            Swal.fire({
                title: "คุณต้องการลบใช่หรือไม่?",
                text: "หากลบข้อมูลแล้ว ไม่สามารถกู้ข้อมูลคืนได้ !",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "ลบข้อมูล",
                cancelButtonText: "ยกเลิก",
                confirmButtonColor: "#B22222",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/Company/Company/delete/') }}",
                        type: 'POST',
                        data: {
                            ids: Guestid
                        },
                        async: false,
                        // ส่งข้อมูลไปยัง server ในรูปแบบที่คุณต้องการ
                        success: function(data) {
                            console.log(data);
                            Swal.fire('ลบข้อมูลเรียบร้อย!', '', 'success');
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                        }
                    });
                }
            });
        }

        // หากมีการส่งค่า alert มาจากหน้าอื่นCompany
        var alertMessage = "{{ session('alert_') }}";
        var alerterror = "{{ session('error_') }}";
        if (alertMessage) {
            // แสดง SweetAlert ทันทีเมื่อโหลดหน้าเว็บ
            Swal.fire({
                icon: 'success',
                title: alertMessage,
                showConfirmButton: false,
                timer: 1500
            });
        }
        if (alerterror) {
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
                    type: 'POST',
                    url: "{{ route('Company.changeStatus') }}",
                    data: {
                        _token: token, // เพิ่ม CSRF token ในข้อมูลของ request
                        id: id,
                        status: status
                    },
                    success: function(response) {
                        // ปรับเปลี่ยนสถานะบนหน้าเว็บ
                        if (status == 1) {
                            // เปลี่ยนสถานะจากเปิดเป็นปิด
                            $(this).data('status', 0);
                            $(this).removeClass('btn-success').addClass('btn-danger').html(
                                'Deactivate');
                            Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                            location.reload();
                        } else {
                            // เปลี่ยนสถานะจากปิดเป็นเปิด
                            $(this).data('status', 1);
                            $(this).removeClass('btn-danger').addClass('btn-success').html(
                                'Activate');
                            Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                            location.reload();
                        }
                    }
                });
            });
        });



        function confirmDelete(id) {
            Swal.fire({
                title: "คุณต้องการลบใช่หรือไม่?",
                text: "หากลบข้อมูลแล้ว ไม่สามารถกู้ข้อมูลคืนได้ !",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "ลบข้อมูล",
                cancelButtonText: "ยกเลิก",
                confirmButtonColor: "#B22222",
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete.isConfirmed) {
                    // ถ้าผู้ใช้คลิก "ตกลง"
                    var token = "{{ csrf_token() }}";
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': token
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: "{{ url('/Company/delete/') }}" + '/' + id,
                        dataType: "JSON",
                        success: function(result) {
                            Swal.fire('ลบข้อมูลเรียบร้อย!', '', 'success');
                            location.reload();
                        },
                        error: function() {
                            Swal.fire('Changes are not saved', '', 'error');
                        }
                    });
                } else {
                    // ถ้าผู้ใช้คลิก "ยกเลิก"
                    Swal.fire('Changes are not saved');
                }
            });
            return false; // เพื่อป้องกันการนำลิงก์ไปยัง URL หลังจากแสดง SweetAlert2
        }
    </script>
@endsection
