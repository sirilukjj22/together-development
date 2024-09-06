@extends('layouts.masterLayout')

@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted ">Welcome to Bank (ธนาคาร).</small>
                <h1 class="h4 mt-1">Bank (ธนาคาร)</h1>
            </div>

            <div class="col-auto">
                <button type="button" class="btn btn-color-green text-white lift btn_modal"><i class="fa fa-plus"></i> เพิ่มธนาคาร</button>
            </div>
        </div> <!-- .row end -->
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row align-items-center mb-3">
            <div class="col">

            </div>

            <div class="col-auto">
                <div class="dropdown">
                    <button class="btn btn-outline-dark dropdown-toggle lift statusbtn" type="button"
                        id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        สถานะการใช้งาน
                    </button>
                    <ul class="dropdown-menu border-0 shadow p-3">
                        <li><a class="dropdown-item py-2 rounded" href="{{ url('master', 'bank_all') }}">ทั้งหมด</a></li>
                        <li><a class="dropdown-item py-2 rounded" href="{{ url('master', 'bank_ac') }}">เปิดใช้งาน</a></li>
                        <li><a class="dropdown-item py-2 rounded" href="{{ url('master', 'bank_no') }}">ปิดใช้งาน</a></li>
                    </ul>
                </div>
            </div>
        </div> <!-- .row end -->
        <div class="row clearfix">
            <div class="col-md-12 col-12">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>บันทึกข้อมูลเรียบร้อย!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card p-4 mb-4">
                    <table id="myTable" class=" table display dataTable table-hover">
                        <thead>
                            <tr>
                                <th data-priority="1">#</th>
                                <th data-priority="1">รูปภาพ</th>
                                <th data-priority="1">ชื่อภาษาไทย</th>
                                <th>ชื่อภาษาอังกฤษ</th>
                                <th>สถานะการใช้งาน</th>
                                <th>คำสั่ง</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($masters))
                                @foreach ($masters as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <img src="../upload/images/{{ $item->picture }}" alt="" class="rounded object-fit-cover mx-1" style="width: 30px; height: 30px;" >
                                        </td>
                                        <td style="text-align: left;">{{ $item->name_th }}</td>
                                        <td style="text-align: left;">{{ $item->name_en }}</td>
                                        <td>
                                            @if ($item->status == 1)
                                                    <button type="button" class="btn btn-light-success btn-sm btn-status" value="{{ $item->id }}">ใช้งาน</button>
                                            @else
                                                <button type="button" class="btn btn-light-success btn-sm btn-status" value="{{ $item->id }}">ปิดใช้งาน</button>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-color-green rounded-pill text-white dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                                    ทำรายการ
                                                </button>
                                                <ul class="dropdown-menu border-0 shadow p-3">
                                                    <li>
                                                        <a href="#" type="button" class="dropdown-item py-2 rounded" onclick="view_detail({{ $item->id }})" data-bs-toggle="modal" data-bs-target="#exampleModalLongAddBank">
                                                            แก้ไขข้อมูล
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div> <!-- .card end -->
            </div>
        </div> <!-- .row end -->
    </div>

    <div class="modal fade" id="exampleModalLongAddBank" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-color-green">
                    <h5 class="modal-title text-white" id="exampleModalLongTitle">เพิ่มธนาคาร</h5>
                    <button type="button" class="btn-close lift" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('master-store') }}" method="POST" enctype="multipart/form-data" id="form-id">
                    @csrf
                    <div class="modal-body">
                        <div class="col-12">
                            <label for="form-label">เรียงลำดับ</label><br>
                            <input type="number" class="form-control" id="sort" name="sort" maxlength="10">
                        </div>
                        <div class="col-12 mt-3">
                            <label for="form-label">ชื่อภาษาไทย <sup class="text-danger">*</sup></label><br>
                            <input type="text" class="form-control check_name_th mb-2" id="name_th" name="name_th" maxlength="100">
                            <p class="text-danger" id="comment"></p>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-danger">ชื่อที่คล้ายกัน:</label>
                            <span id="search_list"></span>
                        </div>
                        <div class="col-12 mt-3">
                            <label for="form-label">ชื่อภาษาอังกฤษ</label><br>
                            <input type="text" class="form-control" id="name_en" name="name_en" maxlength="100">
                        </div>
                        <div class="col-12 mt-3">
                            <label for="form-label">รูปภาพ</label><br>
                            <div class="card-body text-light text-center mb-2" id="ex-image" hidden>
                                <div class="me-2 align-items-center" id="show-img">
                                </div>
                            </div>
                            <label class="text-danger mt-2">* รูปแบบไฟล์นำเข้า (.jpg, jpeg, png)</label> <br>
                            <input type="file" class="form-control" id="formFile" name="image" accept="image/*">
                        </div>
                        <input type="hidden" id="edit_id" name="edit_id" value="">
                        <input type="hidden" name="created_by" value="1">
                        <input type="hidden" name="category" value="bank">
                        <input type="hidden" id="module_name" name="module_name" value="create">
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary submit-button" id="btn-save">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    @if (isset($_SERVER['HTTPS']) ? 'https' : 'http' == 'https')
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        {{-- <script src="../assets/bundles/jquerycounterup.bundle.js"></script> --}}
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @else
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @endif

    <script type="text/javascript">
        $('.btn_modal').on('click', function() {
            var module_name = $('#module_name').val();
            $('#form-id')[0].reset();
            document.getElementById('btn-save').disabled = false;
            $('#comment').text('');
            $('#search_list').text('');
            $('#exampleModalLongTitle').text("เพิ่มธนาคาร");
            $('#ex-image').prop('hidden', true);
            field_disabled_false();
            $('#exampleModalLongAddBank').modal('show');
        });

        function edit(id) {
            $('#form-id')[0].reset();
            document.getElementById('btn-save').disabled = false;
            $('#comment').text('');
            $('#exampleModalLongTitle').text("แก้ไขธนาคาร");
            jQuery.ajax({
                type: "GET",
                url: "{!! url('master/edit/"+id+"') !!}",
                datatype: "JSON",
                async: false,
                success: function(result) {
                    $('#exampleModalLongAddBank').modal('show');
                    field_disabled_false();
                    $('#ex-image').prop('hidden', false);
                    $('#module_name').val("edit");
                    $('#edit_id').val(result.data.id);
                    $('#sort').val(result.data.sort);
                    $('#name_th').val(result.data.name_th);
                    $('#name_en').val(result.data.name_en);
                    $('#show-img').empty();
                    $('#show-img').append('<img class="avatar" src="../upload/images/' + result.data.picture +
                        '" alt="avatar" title="">');
                },
            });
        }

        function view_detail(id) {
            $('#form-id')[0].reset();
            document.getElementById('btn-save').disabled = false;
            $('#comment').text('');
            $('#exampleModalLongTitle').text("รายละเอียดธนาคาร");

            jQuery.ajax({
                type: "GET",
                url: "{!! url('master/edit/"+id+"') !!}",
                datatype: "JSON",
                async: false,
                success: function(result) {
                    $('#exampleModalLongAddBank').modal('show');
                    $('#ex-image').prop('hidden', false);
                    $('#module_name').val("view");
                    $('#sort').val(result.data.sort);
                    $('#name_th').val(result.data.name_th);
                    $('#name_en').val(result.data.name_en);
                    $('#show-img').empty();
                    $('#show-img').append('<img class="avatar" src="../upload/images/' + result.data.picture +
                        '" alt="avatar" title="">');
                    field_disabled();
                },
            });
        }

        $('#btn-save').on('click', function() {
            var name_th = $('#name_th').val();

            if (name_th != '') {
                var datakey = $('#name_th').val();
                var field = "name_th";
                var category = "bank";
                var module_name = $('#module_name').val();
                var type_name = 0;

                document.getElementById('btn-save').disabled = false;
                $('#comment').text('');

                if (module_name == "create") {
                    jQuery.ajax({
                        type: "GET",
                        url: "{!! url('master/check/"+category+"/"+field+"/"+datakey+"') !!}",
                        datatype: "JSON",
                        async: false,
                        success: function(result) {
                            if (result.data) {
                                $('#comment').text("** '" + result.data.name_th +
                                    "' มีอยูในระบบแล้ว !");
                                document.getElementById('btn-save').disabled = true;
                            } else {
                                jQuery.ajax({
                                    type: "GET",
                                    url: "{!! url('master/check-dupicate-name/"+category+"/"+datakey+"/"+type_name+"') !!}",
                                    datatype: "JSON",
                                    async: false,
                                    success: function(response) {
                                        if (response.data.length > 0) {
                                            Swal.fire({
                                                icon: "info",
                                                title: 'ระบบมีชื่อที่คล้ายกันอยู่แล้ว ต้องการบันทึกชื่อนี้ใช่หรือไม่?',
                                                text: "ชื่อที่คล้ายกัน: " + response
                                                    .data.join(", "),
                                                showCancelButton: true,
                                                confirmButtonText: 'บันทึก',
                                                cancelButtonText: 'ยกเลิก',
                                                // confirmButtonColor: "#3085d6",
                                                // cancelButtonColor: "#d33",
                                            }).then((result) => {
                                                /* Read more about isConfirmed, isDenied below */
                                                if (result.isConfirmed) {
                                                    $('#form-id').submit();
                                                } else if (result.isDenied) {
                                                    Swal.fire(
                                                        'Changes are not saved',
                                                        '', 'info');
                                                    location.reload();
                                                }
                                            });
                                        } else {
                                            $('#form-id').submit();
                                        }
                                    },
                                });
                            }
                        },
                    });

                } else {
                    var id = $('#edit_id').val();
                    jQuery.ajax({
                        type: "GET",
                        url: "{!! url('master/check-edit/"+id+"/"+category+"/"+field+"/"+datakey+"') !!}",
                        datatype: "JSON",
                        async: false,
                        success: function(result) {
                            if (result.data) {
                                $('#comment').text("** '" + result.data.name_th +
                                    "' มีอยูในระบบแล้ว !");
                                document.getElementById('btn-save').disabled = true;
                            } else {
                                jQuery.ajax({
                                    type: "GET",
                                    url: "{!! url('master/check-dupicate-name-edit/"+id+"/"+category+"/"+datakey+"/"+type_name+"') !!}",
                                    datatype: "JSON",
                                    async: false,
                                    success: function(response) {
                                        if (response.data.length > 0) {
                                            Swal.fire({
                                                icon: "info",
                                                title: 'ระบบมีชื่อที่คล้ายกันอยู่แล้ว ต้องการบันทึกชื่อนี้ใช่หรือไม่?',
                                                text: "ชื่อที่คล้ายกัน: " + response.data.join(", "),
                                                showCancelButton: true,
                                                confirmButtonText: 'บันทึก',
                                                cancelButtonText: 'ยกเลิก',
                                            }).then((result) => {
                                                /* Read more about isConfirmed, isDenied below */
                                                if (result.isConfirmed) {
                                                    $('#form-id').submit();
                                                } else if (result.isDenied) {
                                                    Swal.fire(
                                                        'Changes are not saved', '', 'info');
                                                    location.reload();
                                                }
                                            });
                                        } else {
                                            $('#form-id').submit();
                                        }
                                    },
                                });
                            }
                        },
                    });
                }
            } else {
                $('#comment').text("** กรุณาระบุชื่อภาษาไทย !");
            }
        });

        $('#radio_master').on('click', function() {
            if ($('#radio_master').is(':checked')) {
                $('.radio_master_sub').prop('checked', true);
            } else {
                $('.radio_master_sub').prop('checked', false);
            }
        });

        function field_disabled() {
            $('#name_th').prop('readonly', true);
            $('#name_en').prop('readonly', true);
            document.getElementById('btn-save').disabled = true;
        }

        function field_disabled_false() {
            $('#name_th').prop('readonly', false);
            $('#name_en').prop('readonly', false);
            document.getElementById('btn-save').disabled = false;
        }

        $('.btn-status').on('click', function() {
            var id = $(this).val();
            jQuery.ajax({
                type: "GET",
                url: "{!! url('master/change-status/"+id+"') !!}",
                datatype: "JSON",
                async: false,
                success: function(result) {
                    Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                    location.reload();
                },
            });
        });
    </script>
@endsection
