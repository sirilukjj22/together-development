@extends('layouts.masterLayout')
@section('content')
    <div id="content-index" class="border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">{{ $title }}</div>
                </div>
                <div class="col-auto">
                    @if (@Auth::user()->roleMenuAdd('Users', Auth::user()->id) == 1)
                        <a href="{{ route('user-create') }}" type="button" class="btn btn-color-green text-white lift">Add
                            User</a>
                    @endif
                </div>
            </div> <!-- .row end -->
        </div>
    </div>
    @php
        $role_revenue = App\Models\Role_permission_revenue::where('user_id', Auth::user()->id)->first();
    @endphp
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
                                        @if ($menu == 'users_all')
                                            All
                                        @elseif ($menu == 'users_ac')
                                            Active
                                        @elseif ($menu == 'users_no')
                                            Disabled
                                        @else
                                            Status
                                        @endif
                                        <i class="fas fa-angle-down arrow-dropdown"></i>
                                    </button>
                                    <ul class="dropdown-menu border-0 shadow p-3">
                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('users', 'users_all') }}">All</a></li>
                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('users', 'users_ac') }}">Active</a></li>
                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('users', 'users_no') }}">Disabled</a></li>
                                    </ul>
                                </div>
                              </div>
      
                            <!-- Entries per Page Dropdown -->
                            <div class="top-table-3c_2">
                                <label class="entriespage-label">entries per page :</label>
                                <select class="entriespage-button bd-button" id="search-per-page-user" style="text-align: left;" onchange="getPage(1, this.value, 'user')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                    <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]">10</option>
                                    <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]">25</option>
                                    <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]">50</option>
                                    <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]">100</option>
                                </select>
                            </div>
      
                            <!-- Search Input -->
                            <div class="top-table-3c_3">
                                <label class="entriespage-label">Search :</label>
                                <input class="search-button bd-button search-data" id="user" style="text-align: left;" placeholder="Search" />
                            </div>
                            </div>
                        </caption>
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                <strong>Successfully!</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                <strong>Error!</strong> {{ session('error') }}
                                <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <div style="min-height: 70vh;">
                            <table id="userTable" class="example ui striped table nowrap unstackable hover">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;" data-priority="1">#</th>
                                        <th style="text-align: center;" data-priority="1">Name</th>
                                        <th style="text-align: center;">Permission</th>
                                        <th style="text-align: center;">Status</th>
                                        <th style="text-align: center;" data-priority="1">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $key => $item)
                                        <tr style="text-align: center;">
                                            <td class="td-content-center">{{ $key + 1 }}</td>
                                            <td class="td-content-center">{{ $item->name }}</td>
                                            <td class="td-content-center">{{ @$item->permissionName->department }}</td>
                                            <td class="td-content-center">
                                                @if ($item->status == 1)
                                                    <button type="button" class="btn btn-light-success btn-sm btn-status"
                                                        value="{{ $item->id }}">Active</button>
                                                @else
                                                    <button type="button" class="btn btn-light-success btn-sm btn-status"
                                                        value="{{ $item->id }}">Disabled</button>
                                                @endif
                                            </td>
                                            <td class="td-content-center">
                                                <div class="dropdown">
                                                    <button type="button" class="btn" style="background-color: #2C7F7A; color:white;" data-bs-toggle="dropdown" data-toggle="dropdown">
                                                        Select <span class="caret"></span>
                                                    </button>
                                                    @if (@Auth::user()->roleMenuEdit('Users', Auth::user()->id) == 1)
                                                        <ul class="dropdown-menu">
                                                            <li class="button-li" onclick="window.location.href='{{ route('user-edit', $item->id) }}'">Edit</li>
                                                        </ul>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <caption class="caption-bottom">
                            <div class="md-flex-bt-i-c">
                                <p class="py2" id="user-showingEntries">{{ showingEntriesTable($users, 'user') }}</p>
                                <div id="user-paginate">
                                    {!! paginateTable($users, 'user') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                </div>
                            </div>
                        </caption>
                    </div> <!-- .card end -->
                </div>
            </div> <!-- .row end -->
        </div>
    </div>

    <input type="hidden" id="get-total-user" value="{{ $users->total() }}">
    <input type="hidden" id="currentPage-user" value="1">

    @if (isset($_SERVER['HTTPS']) ? 'https' : 'http' == 'https')
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @else
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @endif

    <!-- table design css -->
    <link rel="stylesheet" href="{{ asset('assets/css/semantic.min.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.semanticui.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.semanticui.css') }}?v={{ time() }}">

    <!-- table design js -->
    <script src="{{ asset('assets/js/semantic.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.semanticui.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.responsive.js') }}"></script>
    <script src="{{ asset('assets/js/responsive.semanticui.js') }}"></script>

    <!-- สำหรับค้นหาในส่วนของตาราง -->
    <script type="text/javascript" src="{{ asset('assets/helper/searchTableUser.js') }}"></script>

    <script>
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
                    },
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
            var getUrl = window.location.pathname;

            $('#' + table_name).DataTable().destroy();
            var table = $('#' + table_name).dataTable({
                searching: false,
                paging: false,
                info: false,
                ajax: {
                    url: '/user-search-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        search_value: search_value,
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
                    targets: [0, 1, 2, 3, 4],
                    className: 'dt-center td-content-center'
                }, ],
                order: [0, 'asc'],
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr'
                    }
                },
                columns: [{
                        data: 'id',
                        "render": function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'username'
                    },
                    {
                        data: 'permission_name'
                    },
                    {
                        data: 'status_name'
                    },
                    {
                        data: 'btn_action'
                    },
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
                    Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                    location.reload();
                },
            });
        });
    </script>
@endsection
