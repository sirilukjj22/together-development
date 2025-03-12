@php
    $layout = (Auth::user()->current_branch == 1) ? 'layouts.masterLayout' : 'layouts.masterLayoutHarmony';
@endphp

@extends($layout)

@php
    $excludeDatatable = false;
@endphp
@section('content')

<style>
    .wrap-status-active {
        background-color: rgb(30, 133, 47);
        color: white;
        vertical-align: middle;
        padding: 3px 8px;
        border-radius: 7px;
        font-size: 0.8em;
    }

    .wrap-status-disable {
        background-color: rgb(224, 94, 42);
        color: white;
        vertical-align: middle;
        padding: 3px 8px;
        border-radius: 7px;
        font-size: 0.8em;
    }
</style> 

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
                        <div class="flex-between-2end">
                            <div class="flex-end">
                                <div class="filter-section bd-select-cl d-flex mb-2 mr-2 " style=" gap: 0.3em;">
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
                            </div>
                        </div>
                        <div style="min-height: 70vh;">
                            <table id="userTable" class="table-together table-style">
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
                                            <td class="td-content-center" >{{ $key + 1 }}</td>
                                            <td class="td-content-center text-start">{{ $item->name }}</td>
                                            <td class="td-content-center text-start">{{ @$item->permissionName->department }}</td>
                                            <td class="td-content-center">
                                                @if ($item->status == 1)
                                                    <span class="wrap-status-active">Active</span>
                                                @else
                                                    <span class="wrap-status-disable">No active</span>
                                                @endif
                                            </td>
                                            <td class="td-content-center">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Select &nbsp;</button>
                                                    @if (@Auth::user()->roleMenuEdit('Users', Auth::user()->id) == 1)
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            <li><a class="dropdown-item py-2 rounded" href="{{ route('user-edit', $item->id) }}">Edit</a></li>
                                                            <li><a class="dropdown-item py-2 rounded" href="#" onclick="btnChangeStatus({{ $item->id }})">{{ $item->status == 1 ? "No active" : "Active" }}</a></li>
                                                        </ul>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- .card end -->
                </div>
            </div> <!-- .row end -->
        </div>
    </div>

    <!-- สำหรับค้นหาในส่วนของตาราง -->
    <script src="{{ asset('assets/js/table-together.js') }}"></script>
    <!-- Sweet Alert 2 -->
    <script src="{{ asset('assets/bundles/sweetalert2.bundle.js')}}"></script>

    <script>
        function btnChangeStatus(id) {
            Swal.fire({
                icon: "info",
                title: 'ต้องการเปลี่ยนสถานะใช่หรือไม่?',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {

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

                } else if (result.isDenied) {
                    Swal.fire('บันทึกข้อมูลไม่สำเร็จ!', '', 'info');
                }
            });
        }
    </script>
@endsection
