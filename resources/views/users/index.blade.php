@extends('layouts.masterLayout')

    @section('pretitle')
        <div class="container">
            <div class="row align-items-center">
                <div class="col">
                    <small class="text-muted ">Welcome to {{ $title ?? '' }}.</small>
                    <h1 class="h4 mt-1">{{ $title ?? '' }}</h1>
                </div>
        
                <div class="col-auto">
                    @if (@Auth::user()->roleMenuAdd('User (Setting)') == 1)
                        <a href="{{ route('user-create') }}" type="button" class="btn btn-color-green text-white lift"><i class="fa fa-plus"></i> เพิ่มผู้ใช้งาน</a>
                    @endif
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
                        <button class="btn btn-outline-dark dropdown-toggle lift statusbtn" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            สถานะการใช้งาน
                        </button>
                        <ul class="dropdown-menu border-0 shadow p-3">
                            <li><a class="dropdown-item py-2 rounded" href="{{ url('users', 'users_all') }}">ทั้งหมด</a></li>
                            <li><a class="dropdown-item py-2 rounded" href="{{ url('users', 'users_ac') }}">เปิดใช้งาน</a></li>
                            <li><a class="dropdown-item py-2 rounded" href="{{ url('users', 'users_no') }}">ปิดใช้งาน</a></li>
                        </ul>
                    </div>
                </div>
            </div> <!-- .row end -->
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    @if (session("success"))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>บันทึกข้อมูลเรียบร้อย!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="card p-4 mb-4">
                        <table id="myTable" class=" table display dataTable table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ชื่อผู้ใช้งาน</th>
                                    <th>สิทธิ์ใช้งาน</th>
                                    <th>สถานะการใช้งาน</th>
                                    <th>คำสั่ง</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($users))
                                    @foreach ($users as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                @switch($item->permission)
                                                    @case(0)
                                                        ผู้ใช้งานทั่วไป
                                                    @break

                                                    @case(1)
                                                        แอดมิน
                                                    @break

                                                    @case(2)
                                                        ผู้พัฒนาระบบ
                                                    @break
                                                @endswitch
                                            </td>
                                            <td>
                                                @if ($item->status == 1)
                                                    <button type="button" class="btn btn-light-success btn-sm btn-status" value="{{ $item->id }}">ใช้งาน</button>
                                                @else
                                                    <button type="button" class="btn btn-light-success btn-sm btn-status" value="{{ $item->id }}">ปิดใช้งาน</button>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-color-green rounded-pill text-white dropdown-toggle"
                                                        type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        ทำรายการ
                                                    </button>
                                                    @if (@Auth::user()->roleMenuEdit('User (Setting)') == 1)
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            <li>
                                                                <a href="{{ route('user-edit', $item->id) }}" type="button" class="dropdown-item py-2 rounded">
                                                                    แก้ไขข้อมูล
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    @endif
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
    
    @if (isset($_SERVER['HTTPS']) ? 'https' : 'http' == 'https')
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        {{-- <script src="../assets/bundles/jquerycounterup.bundle.js"></script> --}}
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @else
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @endif

<script type="text/javascript">
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
    