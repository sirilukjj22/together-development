@extends('layouts.masterLayout')

@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to Market.</small>
                <h1 class="h4 mt-1">Market (ตลาด)</h1>
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-color-green lift btn_modal" data-bs-toggle="modal" data-bs-target="#MarketCreate">
                    <i class="fa fa-plus"></i> เพิ่มตลาด </button>
            </div>

            <!-- Prename Modal Center-->
            <div class="modal fade" id="MarketCreate" tabindex="-1" aria-labelledby="PrenameModalCenterTitle"
            style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="PrenameModalCenterTitle">เพิ่มตลาด</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="col-12">
                                    <div class="card-body">
                                        <form action="{{route('Mmarket.save')}}" method="POST" enctype="multipart/form-data" class="row g-3 basic-form" id="form-id">
                                            @csrf
                                            <div class="col-sm-12 col-12">
                                                <label class="form-label">ชื่อภาษาไทย <sup class="text-danger">*</sup> </label>
                                                <input type="text" class="form-control check_name_th mb-2" id="name_th" name="name_th" maxlength="50">
                                                <p class="text-danger" id="comment"></p>
                                            </div>
                                            <div class="col-sm-12 col-12">
                                                <label class="form-label text-danger">ชื่อที่คล้ายกัน:</label>
                                                <span id="search_list"></span>
                                            </div>
                                            <div class="col-sm-12 col-12">
                                                <label class="form-label">ชื่อภาษาอังกฤษ</label>
                                                <input type="text" class="form-control" id="name_en" name="name_en" maxlength="50">
                                            </div>
                                            <div class="col-sm-12 col-12">
                                                <label class="form-label">Code</label>
                                                <input type="text" class="form-control" id="code" name="code" maxlength="50">
                                            </div>

                                            <input type="hidden" id="edit_id" name="edit_id" value="">
                                            <input type="hidden" name="created_by" value="1">
                                            <input type="hidden" name="category" value="prename">
                                            <input type="hidden" id="module_name" name="module_name" value="create">

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary lift" data-bs-dismiss="modal">ยกเลิก</button>
                                                <button type="button" class="btn btn-color-green lift" id="btn-save">สร้าง</button>
                                            </div>
                                        </form>
                                    </div>
                            </div><!-- Form Validation -->
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- .row end -->

    </div>
@endsection

@section('content')
<div class="container">
    <div class="row align-items-center mb-2">
        @if (session("success"))
        <div class="alert alert-success" role="alert">
            <h4 class="alert-heading">บันทึกสำเร็จ!</h4>
            <hr>
            <p class="mb-0">{{ session('success') }}</p>
        </div>
        @endif
        <div class="col">
            <ol class="breadcrumb d-inline-flex bg-transparent p-0 m-0">
                <li></li>
                <li></li>
                <li></li>
            </ol>
        </div>
        <div class="col-auto">
            <div class="dropdown">
                <button class="btn btn-outline-dark lift dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    สถานะการใช้งาน
                </button>
                {{-- <button type="button" class="btn btn-danger lift sa-buttons"><i class="fa fa-trash-o"></i> ลบหลายรายการ</button> --}}

                <ul class="dropdown-menu border-0 shadow p-3">
                    <li><a class="dropdown-item py-2 rounded" href="{{ route('Mmarket.index') }}">ทั้งหมด</a></li>
                    <li><a class="dropdown-item py-2 rounded" href="{{ route('Mmarket.ac', ['value' => 1]) }}">ใช้งาน</a></li>
                    <li><a class="dropdown-item py-2 rounded" href="{{ route('Mmarket.no', ['value' => 0]) }}">ปิดใช้งาน</a></li>
                </ul>
            </div>
        </div>
    </div> <!-- Row end  -->

    <div class="row clearfix">
        <div class="col-sm-12 col-12">
            <div class="card p-4 mb-4">
                <form enctype="multipart/form-data" class="row g-3 basic-form" id="form-id2">
                    @csrf
                    <input type="hidden" name="category" value="prename">
                <table class="myDataTableProductItem table table-hover align-middle mb-0" style="width:100%">
                    <thead>
                        <tr>
                            <th>เรียงลำดับ</th>
                            <th>Code</th>
                            <th>ชื่อภาษาไทย</th>
                            <th>ชื่อภาษาอังกฤษ</th>
                            <th class="text-center">สถานะการใช้งาน</th>
                            <th class="text-center">คำสั่ง</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($Mmarket))
                            @foreach ($Mmarket as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->code }}</td>
                                <td>{{ $item->name_th }}</td>
                                <td>{{ $item->name_en }}</td>
                                <td style="text-align: center;">
                                    @if ($item->status == 1)
                                        <button type="button" class="btn btn-light-success btn-sm btn-status" value="{{ $item->id }}">ใช้งาน</button>
                                    @else
                                        <button type="button" class="btn btn-light-danger btn-sm btn-status" value="{{ $item->id }}">ปิดใช้งาน</button>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">ทำรายการ &nbsp;</button>
                                        <ul class="dropdown-menu border-0 shadow p-3">
                                            <li><a class="dropdown-item py-2 rounded" href="#" onclick="view_detail({{$item->id}})" href="#" data-bs-toggle="modal" data-bs-target="#MarketCreate">ดูรายละเอียด</a></li>
                                            <li><a class="dropdown-item py-2 rounded" id="btn-edit" onclick="edit({{$item->id}})" href="#" data-bs-toggle="modal" data-bs-target="#MarketCreate">แก้ไขรายการ</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                    </tbody>
                </table>
                </form>
            </div> <!-- .card end -->
        </div>
    </div> <!-- .row end -->
</div>

<form id="form-id3">
    @csrf
    <input type="hidden" id="deleteID" name="deleteID" value="">
</form>

@include('script.script')

<script>

    $('.btn_modal').on('click', function() {
        var module_name = $('#module_name').val();
        $('#form-id')[0].reset();
        document.getElementById('btn-save').disabled = false;
        $('#comment').text('');
        $('#search_list').text('');
        $('#PrenameModalCenterTitle').text("เพิ่มตลาด");
        field_disabled_false();
    });

    $('#name_th').on('keyup', function () {
        var datakey = $(this).val();

        $('#comment').text('');
        document.getElementById('btn-save').disabled = false;

        $.ajax({
            type:   "GET",
            url:    "{!! url('/Mmarket/search-list2/"+datakey+"') !!}",
            datatype:   "JSON",
            success: function(data) {
                if (data.name_th) {
                    console.log(data.name_th);
                    $('#comment').text("** '" + data.name_th + "' มีอยูในระบบแล้ว !");
                    $('#search_list').text('มีตลาดซ้ำกันแล้ว');
                    document.getElementById('btn-save').disabled = true;
                }else{
                    $('#search_list').text('ไม่มีข้อมูล');
                }
            },
        });
    });

    function edit(id) {
        $('#form-id')[0].reset();
        document.getElementById('btn-save').disabled = false;
        $('#comment').text('');
        $('#PrenameModalCenterTitle').text("แก้ไขตลาด");

        jQuery.ajax({
            type:   "GET",
            url:    "{!! url('/Mmarket/edit/"+id+"') !!}",
            datatype:   "JSON",
            async:  false,
            success: function(result) {
                field_disabled_false();
                $('#module_name').val("edit");
                $('#edit_id').val(result.data.id);
                $('#name_th').val(result.data.name_th);
                $('#name_en').val(result.data.name_en);
                $('#code').val(result.data.code);
            },
        });
    }

    function view_detail(id) {
        $('#form-id')[0].reset();
        document.getElementById('btn-save').disabled = false;
        $('#comment').text('');

        $('#PrenameModalCenterTitle').text("รายละเอียดตลาด");

        jQuery.ajax({
            type:   "GET",
            url:    "{!! url('/Mmarket/edit/"+id+"') !!}",
            datatype:   "JSON",
            async:  false,
            success: function(result) {
                console.log(result.data);
                $('#module_name').val("view");
                $('#name_th').val(result.data.name_th);
                $('#name_en').val(result.data.name_en);
                $('#code').val(result.data.code);
                field_disabled();
            },
        });
    }

    $('#btn-save').on('click', function() {
        var name_th = $('#name_th').val();

        if (name_th != '') {
            var datakey = $('#name_th').val();
            var dataEN = $('#name_en').val();
            var code = $('#code').val();
            var field = "name_th";
            var module_name = $('#module_name').val();

            document.getElementById('btn-save').disabled = false;
            $('#comment').text('');

            if (module_name == "create") {
                jQuery.ajax({
                type:   "GET",
                url:    "{!! url('/Mmarket/search-list2/"+datakey+"') !!}",
                datatype:   "JSON",
                async:  false,
                success: function(data) {
                        if (data.name_th) {
                            console.log(data.name_th);
                            $('#comment').text("** '" + data.name_th + "' มีอยูในระบบแล้ว !");
                            $('#search_list').text('มีตลาดซ้ำกันแล้ว');
                            document.getElementById('btn-save').disabled = true;
                        }else{
                            $('#form-id').submit();
                        }
                    },
                });
            }else{
                var id = $('#edit_id').val();
                jQuery.ajax({
                type:   "GET",
                url:    "{!! url('/Mmarket/check-edit-name/"+id+"/"+datakey+"') !!}",
                datatype:   "JSON",
                async:  false,
                success: function(result) {
                        if (result.data&&
                            result.data.name_th === datakey &&
                            result.data.name_en === dataEN &&
                            result.data.code === code) {
                            $('#comment').text("** '" + result.data.name_th + "', '" + result.data.name_en + "', '" + result.data.code + "' มีอยูในระบบแล้ว !");
                            document.getElementById('btn-save').disabled = true;
                        }else{
                            if (module_name == "edit") {
                                $.ajax({
                                    type: "POST",
                                    url: `{!! url('/Mmarket/update') !!}`,
                                    data: {
                                        id: id,
                                        datakey: datakey,
                                        dataEN: dataEN,
                                        code: code
                                    },
                                    datatype: "JSON",
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function(response) {
                                        location.reload();
                                    },
                                    error: function(xhr, status, error) {
                                        console.error('An error occurred:', status, error);
                                    }
                                });
                            }
                        }
                    },
                });
            }
        }else{
            $('#comment').text("** กรุณาระบุชื่อภาษาไทย !");
        }
	});

    $('#radio_master').on('click', function () {
        if ($('#radio_master').is(':checked')) {
            $('.radio_master_sub').prop('checked', true);
        }else{
            $('.radio_master_sub').prop('checked', false);
        }
    });

    function field_disabled() {

        $('#name_th').prop('readonly', true);
        $('#name_en').prop('readonly', true);
        $('#code').prop('readonly', true);
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
        type:   "GET",
        url:    "{!! url('/Mmarket/change-Status/"+id+"') !!}",
        datatype:   "JSON",
        async:  false,
        success: function(result) {
            Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
            location.reload();

            },
        });
	});

</script>
@endsection
