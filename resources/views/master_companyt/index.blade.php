@extends('layouts.masterLayout')

@section('content')

    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Company Type</div>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-color-green lift btn_modal" data-bs-toggle="modal" data-bs-target="#McomtCreate">
                        <i class="fa fa-plus"></i> เพิ่มประเภทบริษัท
                    </button>
                    <div class="modal fade" id="McomtCreate" tabindex="-1" aria-labelledby="PrenameModalCenterTitle"
                        style="display: none;" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="PrenameModalCenterTitle">เพิ่มประเภทบริษัท</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="col-12">
                                            <div class="card-body">
                                                <form action="{{route('Mcomt.save')}}" method="POST" enctype="multipart/form-data" class="row g-3 basic-form" id="form-id">
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
                                                        <input type="text" class="form-control" id="name_en"  name="name_en" maxlength="50">
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
                </div>
            </div> <!-- .row end -->
        </div>
    </div>
    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
            <div class="row align-items-center mb-2" >
                @if (session("success"))
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">บันทึกสำเร็จ!</h4>
                    <hr>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
                @endif
                @if (session("error"))
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">บันทึกไม่สำเร็จ!</h4>
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
                </div>
            </div> <!-- Row end  -->
        </div> <!-- Row end  -->
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <caption class="caption-top">
                                <div class="top-table-3c">
                                    <div class="top-table-3c_1">
                                        <div class="dropdown">
                                            <button class="bd-button statusbtn enteriespage-button" style="min-width: 100px; text-align: left;" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="text-align: left;">
                                                @if ($menu == 'Mcomt.all')
                                                    All
                                                @elseif ($menu == 'Mcomt.ac')
                                                    Active
                                                @elseif ($menu == 'Mcomt.no')
                                                    Disabled
                                                @else
                                                    Status
                                                @endif
                                        <i class="fas fa-angle-down arrow-dropdown"></i>
                                            </button>
                                            <ul class="dropdown-menu border-0 shadow p-3">
                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('Mcomt', 'Mcomt.all') }}">All</a></li>
                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('Mcomt', 'Mcomt.ac') }}">Active</a></li>
                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('Mcomt', 'Mcomt.no') }}">Disabled</a></li>
                                            </ul>
                                        </div>
                                        <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ route('Mcomt.Log') }}'">
                                            LOG
                                        </button>
                                    </div>

                                    <label class="entriespage-label">entries per page :</label>
                                    <select class="entriespage-button" id="search-per-page-Mcomt" onchange="getPage(1, this.value, 'Mcomt')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                        <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "Mcomt" ? 'selected' : '' }}>10</option>
                                        <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "Mcomt" ? 'selected' : '' }}>25</option>
                                        <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "Mcomt" ? 'selected' : '' }}>50</option>
                                        <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "Mcomt" ? 'selected' : '' }}>100</option>
                                    </select>
                                    <input class="search-button search-data" id="Mcomt" style="text-align:left;" placeholder="Search" />

                                </div>
                            </caption>
                            <div style="min-height: 70vh;" class="mt-2">
                                <table id="McomtTable" class="example ui striped table nowrap unstackable hover">
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
                                        @if(!empty($M_Company_type))
                                            @foreach ($M_Company_type as $key => $item)
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
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            <li><a class="dropdown-item py-2 rounded" href="#" onclick="view_detail({{$item->id}})" href="#" data-bs-toggle="modal" data-bs-target="#McomtCreate">View</a></li>
                                                            <li><a class="dropdown-item py-2 rounded" id="btn-edit" onclick="edit({{$item->id}})" href="#" data-bs-toggle="modal" data-bs-target="#McomtCreate">Edit</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <input type="hidden" id="get-total-Mcomt" value="{{ $M_Company_type->total() }}">
                            <input type="hidden" id="currentPage-Mcomt" value="1">
                            <caption class="caption-bottom">
                                <div class="md-flex-bt-i-c">
                                    <p class="py2" id="Mcomt-showingEntries">{{ showingEntriesTable($M_Company_type, 'Mcomt') }}</p>
                                        <div id="Mcomt-paginate">
                                            {!! paginateTable($M_Company_type, 'Mcomt') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                        </div>
                                </div>
                            </caption>
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
    <script type="text/javascript" src="{{ asset('assets/helper/searchTableMasterCompanyType.js')}}"></script>
    <script>


        $(document).on('keyup', '.search-data', function () {
            var id = $(this).attr('id');
            var search_value = $(this).val();
            var table_name = id+'Table';
            var filter_by = $('#filter-by').val();
            var type_status = $('#status').val();
            var total = parseInt($('#get-total-'+id).val());
            var getUrl = window.location.pathname;
            console.log(search_value);

                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                    url: '/Mcomt-search-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        search_value: search_value,
                        table_name: table_name,
                        filter_by: filter_by,
                        status: type_status,
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                },
                "initComplete": function (settings,json){

                    if ($('#'+id+'Table .dataTable_empty').length == 0) {
                        var count = $('#'+id+'Table tr').length - 1;
                    }else{
                        var count = 0;
                    }
                    if (search_value == '') {
                        count_total = total;
                    }else{
                        count_total = count;
                    }
                    $('#'+id+'-paginate').children().remove().end();
                    $('#'+id+'-showingEntries').text(showingEntriesSearch(1,count_total, id));
                    $('#'+id+'-paginate').append(paginateSearch(count_total, id, getUrl));
                },
                    columnDefs: [
                                { targets: [0,3,4], className: 'dt-center td-content-center' },
                    ],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    },
                    columns: [
                        { data: 'id', "render": function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                        { data: 'nameth' },
                        { data: 'nameen' },
                        { data: 'status' },
                        { data: 'btn_action' },
                    ],

                });


            document.getElementById(id).focus();
        });
        $(document).ready(function() {
            new DataTable('.example', {
                searching: false,
                paging: false,
                info: false,
                columnDefs: [{
                    className: 'dtr-control',
                    orderable: true,
                    target: null,
                }],
                order: [0, 'asc'],
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr'
                    }
                }
            });
        });

    </script>
    @include('script.script')


    <script>

        $('.btn_modal').on('click', function() {
            var module_name = $('#module_name').val();
            $('#form-id')[0].reset();
            document.getElementById('btn-save').disabled = false;
            $('#comment').text('');
            $('#search_list').text('');
            $('#PrenameModalCenterTitle').text("เพิ่มประเภทบริษัท");
            field_disabled_false();
        });

        $('#name_th').on('keyup', function () {
            var datakey = $(this).val();

            $('#comment').text('');
            document.getElementById('btn-save').disabled = false;

            $.ajax({
                type:   "GET",
                url:    "{!! url('/Mproduct/quantity/search-list2/"+datakey+"') !!}",
                datatype:   "JSON",
                success: function(data) {
                    if (data.name_th || data.name_en) {
                        console.log(data.name_th);
                        $('#comment').text("** '" + data.name_th + "' มีอยูในระบบแล้ว !");
                        $('#search_list').text('มีประเภทบริษัทซ้ำกันแล้ว');
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
            $('#PrenameModalCenterTitle').text("แก้ไขประเภทบริษัท");

            jQuery.ajax({
                type:   "GET",
                url:    "{!! url('/Mcomt/edit/"+id+"') !!}",
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

            $('#PrenameModalCenterTitle').text("รายละเอียดประเภทบริษัท");

            jQuery.ajax({
                type:   "GET",
                url:    "{!! url('/Mcomt/edit/"+id+"') !!}",
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
                    url:    "{!! url('/Mcomt/search-list2/"+datakey+"') !!}",
                    datatype:   "JSON",
                    async:  false,
                    success: function(data) {
                            if (data.name_th) {
                                console.log(data.name_th);
                                $('#comment').text("** '" + data.name_th + "' มีอยูในระบบแล้ว !");
                                $('#search_list').text('มีปริมาณซ้ำกันแล้ว');
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
                    url:    "{!! url('/Mcomt/check-edit-name/"+id+"/"+datakey+"') !!}",
                    datatype:   "JSON",
                    async:  false,
                    success: function(result) {
                            if (result.data.name_th == datakey && result.data.name_en == dataEN) {
                                $('#comment').text("** '" + result.data.name_th + "', '" + result.data.name_en + "' มีอยูในระบบแล้ว !");
                                document.getElementById('btn-save').disabled = true;
                            }else{
                                jQuery.ajax({
                                type:   "GET",
                                url:    "{!! url('/Mcomt/update/"+id+"/"+datakey+"/"+dataEN+"') !!}",
                                datatype:   "JSON",
                                async:  false,
                                success: function(response) {
                                    location.reload();
                                    }
                                });
                            }
                            // }
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
                url: "{!! url('/Mcomt/change-Status/" + id + "') !!}",
                datatype: "JSON",
                async: false,
                success: function(result) {
                    Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                    location.reload();
                },
            });
        }
    </script>
@endsection
