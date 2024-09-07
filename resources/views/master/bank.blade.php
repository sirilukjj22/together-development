@extends('layouts.masterLayout')
@section('content')
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Bank</div>
                </div>
                <div class="col-auto">
                    {{-- @if (@Auth::user()->roleMenuAdd('Users', Auth::user()->id) == 1) --}}
                        <a href="#" type="button" class="btn btn-color-green text-white lift btn_modal">Add</a>
                    {{-- @endif --}}
                </div>
            </div> <!-- .row end -->
        </div>
    </div>
    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <div class="card p-4 mb-4">
                        <caption class="caption-top">
                            <div class="top-table-3c">
                              <!-- Status Dropdown -->
                              <div class="top-table-3c_1">
                                <label class="entriespage-label">Status :</label>
                                <div class="dropdown">
                                    <button class="bd-button statusbtn enteriespage-button" style="min-width: 100px; text-align: left;" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="text-align: left;">
                                        @if ($menu == 'bank_all')
                                            All
                                        @elseif ($menu == 'bank_ac')
                                            Active
                                        @elseif ($menu == 'bank_no')
                                            Disabled
                                        @else
                                            Status
                                        @endif
                                        <i class="fas fa-angle-down arrow-dropdown"></i>
                                    </button>
                                    <ul class="dropdown-menu border-0 shadow p-3">
                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('master', 'bank_all') }}">All</a></li>
                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('master', 'bank_ac') }}">Active</a></li>
                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('master', 'bank_no') }}">Disabled</a></li>
                                    </ul>
                                </div>
                              </div>
      
                            <!-- Entries per Page Dropdown -->
                            <div class="top-table-3c_2">
                                <label class="entriespage-label">entries per page :</label>
                                <select class="entriespage-button bd-button" id="search-per-page-master" style="text-align: left;" onchange="getPage(1, this.value, 'master')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                    <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == 'master' ? 'selected' : '' }}>10</option>
                                    <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == 'master' ? 'selected' : '' }}>25</option>
                                    <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == 'master' ? 'selected' : '' }}>50</option>
                                    <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == 'master' ? 'selected' : '' }}>100</option>
                                </select>
                            </div>
      
                            <!-- Search Input -->
                            <div class="top-table-3c_3">
                                <label class="entriespage-label">Search :</label>
                                <input class="search-button bd-button search-data" id="master" style="text-align: left;" placeholder="Search" />
                            </div>
                            </div>
                        </caption>
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                <strong>บันทึกข้อมูลเรียบร้อย!</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <div style="min-height: 70vh;">
                            <table id="masterTable" class="example ui striped table nowrap unstackable hover">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;" data-priority="1">#</th>
                                        <th style="text-align: center;">Picture</th>
                                        <th style="text-align: center;" data-priority="1">Name (Thai)</th>
                                        <th style="text-align: center;">Name (Eng)</th>
                                        <th style="text-align: center;">Status</th>
                                        <th style="text-align: center;" data-priority="1">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($masters as $key => $item)
                                        <tr style="text-align: center;">
                                            <td class="td-content-center">{{ $key + 1 }}</td>
                                            <td class="td-content-center">
                                                <div class="flex-jc p-left-4 center">
                                                    <img class="img-bank" src="../upload/images/{{ $item->picture }}">
                                                </div>
                                            </td>
                                            <td class="td-content-center">{{ $item->name_th }}</td>
                                            <td class="td-content-center">{{ $item->name_en }}</td>
                                            <td class="td-content-center">
                                                @if ($item->status == 1)
                                                    <button type="button" class="btn btn-light-success btn-sm btn-status" value="{{ $item->id }}">Active</button>
                                                @else
                                                    <button type="button" class="btn btn-light-success btn-sm btn-status" value="{{ $item->id }}">Disabled</button>
                                                @endif
                                            </td>
                                            <td class="td-content-center">
                                                <div class="dropdown">
                                                    <button type="button" class="btn" style="background-color: #2C7F7A; color:white;" data-bs-toggle="dropdown" data-toggle="dropdown">
                                                        Select <span class="caret"></span>
                                                    </button>
                                                    {{-- @if (@Auth::user()->roleMenuEdit('Users', Auth::user()->id) == 1) --}}
                                                        <ul class="dropdown-menu">
                                                            <li class="button-li" onclick="view_detail({{ $item->id }})" data-bs-toggle="modal" data-bs-target="#exampleModalLongAddBank">Edit</li>
                                                        </ul>
                                                    {{-- @endif --}}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <caption class="caption-bottom">
                            <div class="md-flex-bt-i-c">
                                <p class="py2" id="master-showingEntries">{{ showingEntriesTable($masters, 'master') }}</p>
                                <div id="master-paginate">
                                    {!! paginateTable($masters, 'master') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                </div>
                            </div>
                        </caption>
                    </div> <!-- .card end -->
                </div>
            </div> <!-- .row end -->
        </div>
    </div>

    <div class="modal fade" id="exampleModalLongAddBank" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-color-green">
                    <h5 class="modal-title text-white" id="exampleModalLongTitle">Add</h5>
                    <button type="button" class="btn-close lift" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('master-store') }}" method="POST" enctype="multipart/form-data" id="form-id">
                    @csrf
                    <div class="modal-body">
                        <div class="col-12">
                            <label for="form-label">Sort</label><br>
                            <input type="number" class="form-control" id="sort" name="sort" maxlength="10">
                        </div>
                        <div class="col-12 mt-3">
                            <label for="form-label">Name (Thai) <sup class="text-danger">*</sup></label><br>
                            <input type="text" class="form-control check_name_th mb-2" id="name_th" name="name_th" maxlength="100">
                            <p class="text-danger" id="comment"></p>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-danger">Similar Names:</label>
                            <span id="search_list"></span>
                        </div>
                        <div class="col-12 mt-3">
                            <label for="form-label">Name (Eng)</label><br>
                            <input type="text" class="form-control" id="name_en" name="name_en" maxlength="100">
                        </div>
                        <div class="col-12 mt-3">
                            <label for="form-label">Picture</label><br>
                            <div class="card-body text-light text-center mb-2" id="ex-image" hidden>
                                <div class="me-2 align-items-center" id="show-img">
                                </div>
                            </div>
                            <label class="text-danger mt-2">* Import File Format (.jpg, jpeg, png)</label> <br>
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

    <input type="hidden" id="status-use" value="{{ $menu }}">
    <input type="hidden" id="get-total-master" value="{{ $masters->total() }}">
    <input type="hidden" id="currentPage-master" value="1">

    @if (isset($_SERVER['HTTPS']) ? 'https' : 'http' == 'https')
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @else
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @endif

    <!-- table design css -->
    <link rel="stylesheet" href="{{ asset('assets/css/semantic.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.semanticui.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.semanticui.css') }}">

    <!-- table design js -->
    <script src="{{ asset('assets/js/semantic.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.semanticui.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.responsive.js') }}"></script>
    <script src="{{ asset('assets/js/responsive.semanticui.js') }}"></script>

    <!-- สำหรับค้นหาในส่วนของตาราง -->
    <script type="text/javascript" src="{{ asset('assets/helper/searchTableMasterBank.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            new DataTable('.example', {
                responsive: true,
                searching: false,
                paging: false,
                info: false,
                columnDefs: [{
                        className: 'dtr-control',
                        orderable: true,
                        target: null,
                    }
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

        // Search
        $(document).on('keyup', '.search-data', function() {
            var id = $(this).attr('id');
            var search_value = $(this).val();
            var total = parseInt($('#get-total-' + id).val());
            var table_name = id + 'Table';
            var status_use = $('#status-use').val();
            var status = [];
            var getUrl = window.location.pathname;

            if (status_use == "bank_all") {
                status = [0, 1];
            } if (status_use == "bank_ac") {
                status = [1];
            } if (status_use == "bank_no") {
                status = [0];
            } else {
                status = [1];
            }

            $('#' + table_name).DataTable().destroy();
            var table = $('#' + table_name).dataTable({
                searching: false,
                paging: false,
                info: false,
                ajax: {
                    url: '/master-search-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        search_value: search_value,
                        menu: "bank",
                        status: status,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                },
                "initComplete": function(settings, json) {

                    if ($('#' + id + 'Table .dataTables_empty').length == 0) {
                        var count = $('#' + id + 'Table tr').length - 1;
                    } else {
                        var count = 0;
                        $('.dataTables_empty').addClass('dt-center');
                    }

                    if (search_value == '') {
                        count_total = total;
                    } else {
                        count_total = count;
                    }

                    $('#' + id + '-paginate').children().remove().end();
                    $('#' + id + '-showingEntries').text(showingEntriesSearch(1, count_total, id));
                    $('#' + id + '-paginate').append(paginateSearch(count_total, id, getUrl));

                },
                columnDefs: [{
                    targets: [0, 1, 2, 3, 4, 5],
                    className: 'dt-center td-content-center'
                }, ],
                order: [0, 'asc'],
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr'
                    }
                },
                columns: [
                    { data: 'id', "render": function(data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                    { data: 'image' },
                    { data: 'name_th' },
                    { data: 'name_en' },
                    { data: 'status_name' },
                    { data: 'btn_action' },
                ],

            });

            document.getElementById(id).focus();
        });

        $('.btn-status').on('click', function() {
            var id = $(this).val();

            jQuery.ajax({
                type: "GET",
                url: "{!! url('user/change-status/"+id+"') !!}",
                datatype: "JSON",
                async: false,
                success: function(result) {
                    Swal.fire('Successfully!', '', 'success');
                    location.reload();
                },
            });
        });

        $('.btn_modal').on('click', function() {
            var module_name = $('#module_name').val();
            $('#form-id')[0].reset();
            document.getElementById('btn-save').disabled = false;
            $('#comment').text('');
            $('#search_list').text('');
            $('#exampleModalLongTitle').text("Add");
            $('#ex-image').prop('hidden', true);
            field_disabled_false();
            $('#exampleModalLongAddBank').modal('show');
        });

        function edit(id) {
            $('#form-id')[0].reset();
            document.getElementById('btn-save').disabled = false;
            $('#comment').text('');
            $('#exampleModalLongTitle').text("Edit");
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
            $('#exampleModalLongTitle').text("Detail");

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
    </script>
@endsection
