@extends('layouts.masterLayout')
@php
    $excludeDatatable = false;
@endphp
@section('content')
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">{{ $title }}</div>
                </div>
                <div class="col-auto">
                    @if (@Auth::user()->roleMenuAdd('Users', Auth::user()->id) == 1)
                        <a href="{{ route('user-department-create') }}" type="button" class="btn btn-color-green text-white lift">Add Department</a>
                    @endif
                </div>
            </div> <!-- .row end -->
        </div>
    </div>
    <div id="content-index" class="body d-flex py-lg-4 py-3">

        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <div class="card p-4 mb-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                <strong>บันทึกข้อมูลเรียบร้อย!</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                <strong>เกิดข้อผิดพลาด!</strong> {{ session('error') }}
                                <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <div style="min-height: 70vh;">
                            <table id="departmentTable" class="table-together table-style">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;" data-priority="1">#</th>
                                        <th style="text-align: center;" data-priority="1">Department Name</th>
                                        <th style="text-align: center;" data-priority="1">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($departments as $key => $item)
                                        <tr style="text-align: center;">
                                            <td class="td-content-center">{{ $key + 1 }}</td>
                                            <td class="td-content-center text-start">{{ $item->department }}</td>
                                            <td class="td-content-center">
                                                <div class="dropdown">
                                                    <button type="button" class="btn" style="background-color: #2C7F7A; color:white;" data-bs-toggle="dropdown" data-toggle="dropdown">
                                                        Select <span class="caret"></span>
                                                    </button>
                                                    @if (@Auth::user()->roleMenuEdit('Users', Auth::user()->id) == 1)
                                                        <ul class="dropdown-menu">
                                                            <li class="button-li" onclick="window.location.href='{{ route('user-department-edit', $item->id) }}'">Edit</li>
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
    </script>
@endsection
