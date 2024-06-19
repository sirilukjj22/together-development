@extends('layouts.test')



@section('content')

<style>
    /* .container{
      position: relative;
      padding: 5% 10%;
      margin-top: 40px;
      border-radius: 8px;
      border: 1px solid #aaa;
      background-color: white;
    } */
    .usertopic h1{
      /* position: absolute; */
      top: 0;
      left: 0;
      margin-left: 10px;
      margin-top: 100px;
      font-size: 10px;
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
    }

    @media (max-width: 768px) {
    h1{
       margin-top:32px;
    }
  }
  </style>

    <div class="container-fluid border rounded-3 p-5 mt-3 bg-white" style="width: 98%;">
        {{-- <div class="usertopic"> --}}
            <h1>Bank (ธนาคาร)</h1>
        {{-- </div> --}}

        {{-- <button type="button" class="button-4 sa-buttons" style="float: right;">ลบหลายรายการ</button> --}}
        <button class="statusbtn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            สถานะการใช้งาน &#11206;
        </button>

        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="{{ url('master', 'bank_all') }}">ทั้งหมด</a>
            <a class="dropdown-item" style="color: green;" href="{{ url('master', 'bank_ac') }}">เปิดใช้งาน</a>
            <a class="dropdown-item" style="color: #f44336;" href="{{ url('master', 'bank_no') }}">ปิดใช้งาน</a>
        </div>

        <button type="button" class="submit-button-mobile btn_modal">เพิ่มธนาคาร</button>

        <form enctype="multipart/form-data" id="form-id2">
            @csrf
            <table id="example" class="table-hover nowarp" style="width:100%">
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
                                <td data-label="สถานะการใช้งาน">
                                    @if ($item->status == 1)
                                        <button type="button" class="button-1 btn-status" value="{{ $item->id }}">ใช้งาน</button>
                                    @else
                                        <button type="button" class="button-3 btn-status" value="{{ $item->id }}">ปิดใช้งาน</button>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-custom" type="button" data-toggle="dropdown">
                                            ทำรายการ
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li class="licolor">
                                                <a class="" href="#" onclick="view_detail({{ $item->id }})"
                                                    data-bs-toggle="modal" data-bs-target="#exampleModalLongAddBank">ดูรายละเอียด
                                                </a>
                                            </li>
                                            <li class="licolor">
                                                <a class="" href="#" onclick="edit({{ $item->id }})" id="btn-edit">
                                                    แก้ไขรายการ
                                                </a>
                                            </li>
                                            {{-- <li class="licolor">
                                                <a class="sa-buttons2" href="#" onclick="deleted({{ $item->id }})" >ลบรายการ</a>
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

    <div class="modal fade" id="exampleModalLongAddBank" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">เพิ่มธนาคาร</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('master-store') }}" method="POST" enctype="multipart/form-data" id="form-id">
                    @csrf
                    <div class="modal-body-Add-Bank">
                        <div class="Sort">
                            <label for="">เรียงลำดับ</label><br>
                            <input type="number" class="form-control" id="sort" name="sort" maxlength="10">
                        </div>
                        <div class="thai_name_bank">
                            <label for="">ชื่อภาษาไทย <sup class="text-danger">*</sup></label><br>
                            <input type="text" class="form-control check_name_th mb-2" id="name_th" name="name_th" maxlength="100">
                            <p class="text-danger" id="comment"></p>
                        </div>
                        <div class="eng_name_bank">
                            <label class="form-label text-danger">ชื่อที่คล้ายกัน:</label>
                            <span id="search_list"></span>
                        </div>
                        <div class="eng_name_bank">
                            <label for="">ชื่อภาษาอังกฤษ</label><br>
                            <input type="text" class="form-control" id="name_en" name="name_en" maxlength="100">
                        </div>
                        <div class="logo_bank">
                            <label for="">รูปภาพ</label><br>
                            <label class="text-danger">* รูปแบบไฟล์นำเข้า (.jpg, jpeg, png)</label>
                            <div class="card-body text-light text-center mb-2" id="ex-image" hidden>
                                <div class="me-2 align-items-center" id="show-img">
                                </div>
                            </div>
                            <input type="file" id="formFile" name="image" accept="image/*">
                        </div>
                        <input type="hidden" id="edit_id" name="edit_id" value="">
                        <input type="hidden" name="created_by" value="1">
                        <input type="hidden" name="category" value="bank">
                        <input type="hidden" id="module_name" name="module_name" value="create">
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="close-button" data-dismiss="modal">Close</button>
                    <button type="button" class="submit-button" id="btn-save" style="width: 30%;">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <form id="form-id3">
        @csrf
        <input type="hidden" id="deleteID" name="deleteID" value="">
    </form>

    @if (isset($_SERVER['HTTPS']) ? 'https' : 'http' == 'https')
        <script src="../assets/bundles/sweetalert2.bundle.js"></script>
    @else
        <script src="../assets/bundles/sweetalert2.bundle.js"></script>
    @endif

    <script type="text/javascript">
        // $(".account_number").mask("999-9-99999-9");
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
                    { width: '20%', targets: 2 },
                    { width: '20%', targets: 3 },
                    { width: '11%', targets: 4 },
                    { width: '10%', targets: 5 },

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
            checkboxes = document.getElementsByName('radio_master_sub[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }

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
                                                text: "ชื่อที่คล้ายกัน: " + response.data.join(", "),
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



        function deleted($id) {
            Swal.fire({

                icon: "info",

                title: 'คุณต้องการลบใช่หรือไม่?',

                text: 'หากลบข้อมูลแล้ว ไม่สามารถกู้ข้อมูลคืนได้ !',

                showCancelButton: true,

                confirmButtonText: 'ลบข้อมูล',

                cancelButtonText: 'ยกเลิก',

            }).then((result) => {

                /* Read more about isConfirmed, isDenied below */

                if (result.isConfirmed) {

                    $('#deleteID').val($id);

                    var myform = $('#form-id3').serialize();



                    jQuery.ajax({

                        type: "POST",

                        url: "{!! url('master-delete') !!}",

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

                icon: "info",

                title: 'คุณต้องการลบใช่หรือไม่?',

                text: 'หากลบข้อมูลแล้ว ไม่สามารถกู้ข้อมูลคืนได้ !',

                showCancelButton: true,

                confirmButtonText: 'ลบข้อมูล',

                cancelButtonText: 'ยกเลิก',

            }).then((result) => {

                /* Read more about isConfirmed, isDenied below */

                if (result.isConfirmed) {

                    var myform = $('#form-id2').serialize();



                    jQuery.ajax({

                        type: "POST",

                        url: "{!! url('master-delete') !!}",

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

