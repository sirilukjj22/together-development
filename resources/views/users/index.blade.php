{{-- <META HTTP-EQUIV="Refresh"  CONTENT="300"> --}}

    @extends('layouts.masterLayout')

    @section('pretitle')
        <div class="container">
            <div class="row align-items-center">
                <div class="col">
                    <small class="text-muted ">Welcome to {{ $title ?? '' }}.</small>
                    <h1 class="h4 mt-1">{{ $title ?? '' }}</h1>
                </div>
        
                <div class="col-auto">
                    
                </div>
            </div> <!-- .row end -->
        </div>
    @endsection
    
    @section('content')
        <div class="container">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <div class="card p-4 mb-4">
                        <h6 class="mb-3" style="font-weight: bold;">Agoda Revenue</h6>
                        <table id="myTable" class="table display dataTable table-hover">
                            <thead>
                                <tr>
                                    <th data-priority="1">#</th>
                                    <th data-priority="1">ชื่อผู้ใช้งาน</th>
                                    <th data-priority="1">สิทธิ์ใช้งาน</th>
                                    <th>สถานะการใช้งาน</th>
                                    <th data-priority="1">คำสั่ง</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($users))
                                    @foreach ($users as $key => $item)
                                        <tr>
                                            <td data-label="#">{{ $key + 1 }}</td>
                                            <td data-label="ชื่อผู้ใช้งาน">{{ $item->name }}</td>
                                            <td data-label="สิทธิผู้ใช้งาน">
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
                                            <td data-label="สถานะการใช้งาน">
                                                @if ($item->status == 1)
                                                    <button type="button" class="btn btn-light-success btn-sm btn-status" value="{{ $item->id }}">ใช้งาน</button>
                                                @else
                                                    <button type="button" class="btn btn-light-success btn-sm btn-status" value="{{ $item->id }}">ปิดใช้งาน</button>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-color-green rounded-pill text-white dropdown-toggle"
                                                        type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        ทำรายการ
                                                    </button>
                                                    <ul class="dropdown-menu border-0 shadow p-3">
                                                        <li>
                                                            <a href="{{ route('user-edit', $item->id) }}" type="button" class="dropdown-item py-2 rounded">
                                                                แก้ไข
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
    
    
    
        @if (isset($_SERVER['HTTPS']) ? 'https' : 'http' == 'https')
            <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
            {{-- <script src="../assets/bundles/jquerycounterup.bundle.js"></script> --}}
            <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
        @else
            <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
            <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
        @endif
    @endsection
    