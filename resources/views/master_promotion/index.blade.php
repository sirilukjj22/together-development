@extends('layouts.masterLayout')

@section('content')
    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <small class="text-muted">Welcome to Promotion.</small>
                    <div class=""><span class="span1">Promotion (เอกสารโปรโมชัน)</span></div>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-color-green lift btn_modal" data-bs-toggle="modal" data-bs-target="#PromotionCreate">
                        <i class="fa fa-plus"></i> เพิ่มเอกสารโปรโมชัน</button>
                </div>
                <!-- Prename Modal Center-->
                <div class="modal fade" id="PromotionCreate" tabindex="-1" aria-labelledby="PrenameModalCenterTitle"
                style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="PrenameModalCenterTitle">เพิ่มเอกสารโปรโมชัน</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="col-12">
                                        <div class="card-body">
                                            <form action="{{ route('Mpromotion.save') }}" method="POST" enctype="multipart/form-data" class="row g-3 basic-form" id="form-id">
                                                @csrf
                                                <div class="col-sm-12 col-12">
                                                    <input type="file" class="form-control" name="file[]" id="file" required multiple accept=".png,.jpg,.pdf" onchange="validateFiles()">
                                                <span style="color:red">ขนาดไฟล์ไม่เกิน 10 MB ชนิดไฟล์ที่รองรับ PNG JPG PDF</span>
                                                </div>
                                                <script>
                                                    function validateFiles() {
                                                        var files = document.getElementById('file').files;
                                                        var maxSize = 10 * 1024 * 1024; // 10 MB
                                                        var valid = true;

                                                        for (var i = 0; i < files.length; i++) {
                                                            if (files[i].size > maxSize) {
                                                                alert('File size must not exceed 10 MB');
                                                                valid = false;
                                                                break;
                                                            }
                                                        }

                                                        if (!valid) {
                                                            document.getElementById('file').value = ""; // Clear the file input
                                                        }
                                                    }
                                                </script>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary lift" data-bs-dismiss="modal">ยกเลิก</button>
                                                    <button type="submit" class="btn btn-color-green lift" >สร้าง</button>
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
                                                @if ($menu == 'promotion.all')
                                                    All
                                                @elseif ($menu == 'promotion.ac')
                                                    Active
                                                @elseif ($menu == 'promotion.no')
                                                    Disabled
                                                @else
                                                    Status
                                                @endif
                                        <i class="fas fa-angle-down arrow-dropdown"></i>
                                            </button>
                                            <ul class="dropdown-menu border-0 shadow p-3">
                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('Mpromotion', 'promotion.all') }}">All</a></li>
                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('Mpromotion', 'promotion.ac') }}">Active</a></li>
                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('Mpromotion', 'promotion.no') }}">Disabled</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <label class="entriespage-label">entries per page :</label>
                                    <select class="entriespage-button" id="search-per-page-promotion" onchange="getPage(1, this.value, 'promotion')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                        <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "promotion" ? 'selected' : '' }}>10</option>
                                        <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "promotion" ? 'selected' : '' }}>25</option>
                                        <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "promotion" ? 'selected' : '' }}>50</option>
                                        <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "promotion" ? 'selected' : '' }}>100</option>
                                    </select>
                                    <input class="search-button search-data" id="promotion" style="text-align:left;" placeholder="Search" />

                                </div>
                            </caption>
                            <div style="min-height: 70vh;" class="mt-2">
                                <table id="promotionTable" class="example ui striped table nowrap unstackable hover">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center" data-priority="1">No</th>
                                            <th data-priority="1">Name</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($promotion))
                                            @foreach ($promotion as $key => $item)
                                            <tr>
                                                <td style="text-align: center">{{ $key + 1 }}</td>
                                                <td>{{ $item->name }}</td>
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
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">ทำรายการ &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            <li><a href="{{ asset($path.$item->name) }}" class="dropdown-item py-2 rounded" target="_blank" data-toggle="tooltip" data-placement="top" title="พิมพ์เอกสาร">View</a></li>
                                                            <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Delete({{ $item->id }})">Delete</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <input type="hidden" id="get-total-promotion" value="{{ $promotion->total() }}">
                            <input type="hidden" id="currentPage-promotion" value="1">
                            <caption class="caption-bottom">
                                <div class="md-flex-bt-i-c">
                                    <p class="py2" id="promotion-showingEntries">{{ showingEntriesTable($promotion, 'promotion') }}</p>
                                        <div id="promotion-paginate">
                                            {!! paginateTable($promotion, 'promotion') !!} <!-- ข้อมูล, ชื่อตาราง -->
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
    <script type="text/javascript" src="{{ asset('assets/helper/searchTablePromotion.js')}}"></script>

    <script>
        // Search
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
                    url: '/Mpromotion-search-table',
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
                                { targets: [0, 2 , 3], className: 'dt-center td-content-center' },
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
                        { data: 'name' },
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
        function fetchStatus(status) {
            if (status == 'all' ) {
                $('#StatusName').text('All');
            }else if (status == 'Active') {
                $('#StatusName').text('Active');
            }
            else if (status == 'Disabled') {
                $('#StatusName').text('Disabled');
            }
            else if (status == ' ') {
                $('#StatusName').text('สถานะการใช้งาน');
            }
        }
        function btnstatus(id) {
            jQuery.ajax({
                type: "GET",
                url: "{!! url('/Mpromotion/change-status/" + id + "') !!}",
                datatype: "JSON",
                async: false,
                success: function(result) {
                    Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                    location.reload();
                },
            });
        }
        function Delete(id){
            event.preventDefault();
            Swal.fire({
                title: "คุณต้องการลบใช่หรือไม่?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "ตกลง",
                cancelButtonText: "ยกเลิก",
                confirmButtonColor: "#28a745",
                dangerMode: true
            }).then((result) => {
                jQuery.ajax({
                    type: "GET",
                    url: "/Mpromotion/delete/" + id,
                    datatype: "JSON",
                    async: false,
                    success: function(response) {
                        console.log("AJAX request successful: ", response);
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX request failed: ", status, error);
                    }
                });
            });
        }
    </script>
@endsection
