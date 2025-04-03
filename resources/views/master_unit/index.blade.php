@extends('layouts.masterLayout')
@php
    $excludeDatatable = false;
@endphp
@section('content')
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Unit</div>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-color-green lift btn_modal" data-bs-toggle="modal" data-bs-target="#UnitCreate">
                        <i class="fa fa-plus"></i> เพิ่มหน่วย</button>
                </div>
                <!-- Prename Modal Center-->
                <div class="modal fade" id="UnitCreate" tabindex="-1" aria-labelledby="PrenameModalCenterTitle"
                style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="PrenameModalCenterTitle">เพิ่มหน่วย</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="col-12">
                                        <div class="card-body">
                                            <form action="{{ route('Mproduct.save.unit') }}" method="POST" enctype="multipart/form-data" class="row g-3 basic-form" id="form-id">
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
    </div>


    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
            <div class="row align-items-center mb-2" >
                @if (session("success"))
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">Save successful.</h4>
                    <hr>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
                @endif
                @if (session("error"))
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Save failed!</h4>
                        <hr>
                        <p class="mb-0">{{ session('error') }}</p>
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
                    <div class="d-flex align-items-center">
                        <div class="dropdown">
                            <button class="bd-button statusbtn enteriespage-button" style="min-width: 100px; text-align: left;" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="text-align: left;">
                                @if ($menu == 'Unit.all')
                                    All
                                @elseif ($menu == 'Unit.ac')
                                    Active
                                @elseif ($menu == 'Unit.no')
                                    Disabled
                                @else
                                    Status
                                @endif
                        <i class="fas fa-angle-down arrow-dropdown"></i>
                            </button>
                            <ul class="dropdown-menu border-0 shadow p-3">
                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/Unit', 'Unit.all') }}">All</a></li>
                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/Unit', 'Unit.ac') }}">Active</a></li>
                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Mproduct/Unit', 'Unit.no') }}">Disabled</a></li>
                            </ul>
                        </div>
                        <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ route('Unit.Log') }}'">
                            LOG
                        </button>
                    </div>
                </div>
            </div> <!-- Row end  -->
        </div> <!-- Row end  -->
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div style="min-height: 70vh;" class="mt-2">
                                <table id="unitTable" class="table-together table-style">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center" data-priority="1">No</th>
                                            <th  class="text-center" data-priority="1">Name Thai</th>
                                            <th data-priority="1">Name Eng</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($unit))
                                            @foreach ($unit as $key => $item)
                                            <tr>
                                                <td style="text-align: center">{{ $key + 1 }}</td>
                                                <td >
                                                    {{ $item->name_th }}
                                                </td>
                                                <td>{{ $item->name_en }}</td>
                                                <td style="text-align: center;">
                                                    <input type="hidden" id="status" value="{{ $item->status }}">

                                                    @if ($item->status == 1)
                                                        <button type="button" class="btn btn-light-success btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ใช้งาน</button>
                                                    @else
                                                        <button type="button" class="btn btn-light-danger btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ปิดใช้งาน</button>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Select &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            <li><a class="dropdown-item py-2 rounded" href="#" onclick="view_detail({{$item->id}})" href="#" data-bs-toggle="modal" data-bs-target="#UnitCreate">View</a></li>
                                                            <li><a class="dropdown-item py-2 rounded" id="btn-edit" onclick="edit({{$item->id}})" href="#" data-bs-toggle="modal" data-bs-target="#UnitCreate">Edit</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <!-- dataTable -->
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.semanticui.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.semanticui.js"></script>
    <script src="{{ asset('assets/js/table-together.js') }}"></script>
    @include('script.script')

    <script>

        $('.btn_modal').on('click', function() {
            var module_name = $('#module_name').val();
            $('#form-id')[0].reset();
            document.getElementById('btn-save').disabled = false;
            $('#comment').text('');
            $('#search_list').text('');
            $('#PrenameModalCenterTitle').text("เพิ่มหน่วย");
            field_disabled_false();
        });

        $('#name_th').on('keyup', function () {
            var datakey = $(this).val();

            $('#comment').text('');
            document.getElementById('btn-save').disabled = false;

            $.ajax({
                type:   "GET",
                url:    "{!! url('/Mproduct/Unit/search-list2/"+datakey+"') !!}",
                datatype:   "JSON",
                success: function(data) {
                    if (data.name_th) {
                        console.log(data.name_th);
                        $('#comment').text("** '" + data.name_th + "' มีอยูในระบบแล้ว !");
                        $('#search_list').text('มีหน่วยซ้ำกันแล้ว');
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
            $('#PrenameModalCenterTitle').text("แก้ไขหน่วย");

            jQuery.ajax({
                type:   "GET",
                url:    "{!! url('/Mproduct/Unit/edit/"+id+"') !!}",
                datatype:   "JSON",
                async:  false,
                success: function(result) {
                    field_disabled_false();
                    $('#module_name').val("edit");
                    $('#edit_id').val(result.data.id);
                    $('#name_th').val(result.data.name_th);
                    $('#name_en').val(result.data.name_en);
                },
            });
        }

        function view_detail(id) {
            $('#form-id')[0].reset();
            document.getElementById('btn-save').disabled = false;
            $('#comment').text('');

            $('#PrenameModalCenterTitle').text("รายละเอียดหน่วยนับ");

            jQuery.ajax({
                type:   "GET",
                url:    "{!! url('/Mproduct/Unit/edit/"+id+"') !!}",
                datatype:   "JSON",
                async:  false,
                success: function(result) {
                    $('#module_name').val("view");
                    $('#name_th').val(result.data.name_th);
                    $('#name_en').val(result.data.name_en);

                    field_disabled();
                },
            });
        }

        $('#btn-save').on('click', function() {
            var name_th = $('#name_th').val();

            if (name_th != '') {
                var datakey = $('#name_th').val();
                var dataEN = $('#name_en').val();
                var field = "name_th";
                var module_name = $('#module_name').val();

                document.getElementById('btn-save').disabled = false;
                $('#comment').text('');

                if (module_name == "create") {
                    jQuery.ajax({
                    type:   "GET",
                    url:    "{!! url('/Mproduct/Unit/search-list2/"+datakey+"') !!}",
                    datatype:   "JSON",
                    async:  false,
                    success: function(data) {
                        console.log(data.name_th);
                            if (data.name_th) {
                                $('#comment').text("** '" + data.name_th + "' มีอยูในระบบแล้ว !");
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
                    url:    "{!! url('/Mproduct/Unit/check-edit-name/"+id+"/"+datakey+"') !!}",
                    datatype:   "JSON",
                    async:  false,
                    success: function(result) {
                            if (result.data.name_th == datakey && result.data.name_en == dataEN) {
                                $('#comment').text("** '" + result.data.name_th + "', '" + result.data.name_en + "' มีอยูในระบบแล้ว !");
                                document.getElementById('btn-save').disabled = true;
                            }else{
                                if (module_name == "edit") {
                                    jQuery.ajax({
                                    type:   "GET",
                                    url:    "{!! url('/Mproduct/Unit/update/"+id+"/"+datakey+"/"+dataEN+"') !!}",
                                    datatype:   "JSON",
                                    async:  false,
                                    success: function(response) {
                                        location.reload();
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
            document.getElementById('btn-save').disabled = true;
        }

        function field_disabled_false() {

            $('#name_th').prop('readonly', false);
            $('#name_en').prop('readonly', false);
            document.getElementById('btn-save').disabled = false;
        }

        function btnstatus(id) {
            jQuery.ajax({
                type: "GET",
                url: "{!! url('/Mproduct/changeStatus_unit/" + id + "') !!}",
                datatype: "JSON",
                async: false,
                success: function(result) {
                    Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                    location.reload();
                },
            });
        }



        // Sweetalert2

    </script>
@endsection
