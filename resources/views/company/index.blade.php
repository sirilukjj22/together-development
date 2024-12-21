@extends('layouts.masterLayout')
@php
    $excludeDatatable = false;
@endphp
@section('content')
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Company / Agent</div>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-color-green lift btn_modal"  onclick="window.location.href='{{ route('Company.create') }}'">
                        <i class="fa fa-plus"></i> Create Company / Agent
                    </button>
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
                    <div class="d-flex align-items-center">
                        <div class="dropdown">
                            <button class="bd-button statusbtn enteriespage-button" style="min-width: 100px; text-align: left;" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="text-align: left;">
                                @if ($menu == 'Company.all')
                                    All
                                @elseif ($menu == 'Company.ac')
                                    Active
                                @elseif ($menu == 'Company.no')
                                    Disabled
                                @else
                                    Status
                                @endif
                        <i class="fas fa-angle-down arrow-dropdown"></i>
                            </button>
                            <ul class="dropdown-menu border-0 shadow p-3">
                                <li><a class="dropdown-item py-2 rounded" href="{{ url('Company', 'Company.all') }}">All</a></li>
                                <li><a class="dropdown-item py-2 rounded" href="{{ url('Company', 'Company.ac') }}">Active</a></li>
                                <li><a class="dropdown-item py-2 rounded" href="{{ url('Company', 'Company.no') }}">Disabled</a></li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div> <!-- Row end  -->
        </div> <!-- Row end  -->
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <div class="card p-4 mb-4">
                        <div style="min-height: 70vh;" class="mt-2">
                            <table id="CompanyTable" class="table-together table-style">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;"data-priority="1">No</th>
                                        <th style="text-align: center;"data-priority="1">Company ID</th>
                                        <th data-priority="1">Company Name</th>
                                        <th class="text-center">Branch </th>
                                        <th class="text-center">Phone Number</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($Company))
                                        @foreach ($Company as $key => $item)
                                        <tr>
                                            <td style="text-align: center;">{{ $key + 1 }}</td>
                                            <td style="text-align: center;">{{ $item->Profile_ID }}</td>
                                            <td>{{ $item->Company_Name }}</td>
                                            <td style="text-align: center;">{{ $item->Branch }}</td>
                                            <td style="text-align: center;">
                                                {{ $item->Phone_numbers }}
                                            </td>
                                            <td style="text-align: center;">
                                                @if ($item->status == 1)
                                                <button type="button" class="btn btn-light-success btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ใช้งาน</button>
                                                @else
                                                    <button type="button" class="btn btn-light-danger btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ปิดใช้งาน</button>
                                                @endif
                                            </td>
                                            @php
                                                $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                $canViewProposal = @Auth::user()->roleMenuView('Company / Agent', Auth::user()->id);
                                                $canEditProposal = @Auth::user()->roleMenuEdit('Company / Agent', Auth::user()->id);
                                            @endphp
                                            <td style="text-align: center;">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                    <ul class="dropdown-menu border-0 shadow p-3">
                                                        @if ($rolePermission > 0)
                                                            @if ($canViewProposal == 1)
                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Company/view/'.$item->id) }}">View</a></li>
                                                            @endif
                                                            @if ($canEditProposal == 1)
                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Company/edit/'.$item->id) }}">Edit</a></li>
                                                            @endif
                                                        @else
                                                            @if ($canViewProposal == 1)
                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Company/view/'.$item->id) }}">View</a></li>
                                                            @endif
                                                        @endif
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
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <!-- dataTable -->
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.semanticui.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.semanticui.js"></script>
    <script src="{{ asset('assets/js/table-together.js') }}"></script>
    @include('script.script')

    <script>

        function btnstatus(id) {
            jQuery.ajax({
                type: "GET",
                url: "{!! url('/Company/change-status/" + id + "') !!}",
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
