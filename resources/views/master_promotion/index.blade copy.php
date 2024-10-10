@extends('layouts.masterLayout')

@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to Promotion.</small>
                <h1 class="h4 mt-1">Promotion (เอกสารโปรโมชัน)</h1>
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
@endsection

@section('content')
<div class="container">
    <div class="row align-items-center mb-2">
        @if (session("success"))
        <div class="alert alert-success" role="alert">
            <h4 class="alert-heading">บันทึกสำเร็จ!</h4>
            <hr>
            <p class="mb-0">{{ session('success') }}</p>
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
            <div class="dropdown">
                <button class="btn btn-outline-dark lift dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    สถานะการใช้งาน
                </button>
                {{-- <button type="button" class="btn btn-danger lift sa-buttons"><i class="fa fa-trash-o"></i> ลบหลายรายการ</button> --}}

                <ul class="dropdown-menu border-0 shadow p-3">
                    <li><a class="dropdown-item py-2 rounded" href="{{ route('Mpromotion.index') }}">ทั้งหมด</a></li>
                    <li><a class="dropdown-item py-2 rounded" href="{{ route('Mpromotion.ac', ['value' => 1]) }}">ใช้งาน</a></li>
                    <li><a class="dropdown-item py-2 rounded" href="{{ route('Mpromotion.no', ['value' => 0]) }}">ปิดใช้งาน</a></li>
                </ul>
            </div>
        </div>
    </div> <!-- Row end  -->

    <div class="row clearfix">
        <div class="col-sm-12 col-12">
            <div class="card p-4 mb-4">
                <form enctype="multipart/form-data" class="row g-3 basic-form" id="form-id2">
                    @csrf
                    <input type="hidden" name="category" value="prename">
                <table class="myDataTableProductItem table table-hover align-middle mb-0" style="width:100%">
                    <thead>
                        <tr>
                            <th style="text-align: center">เรียงลำดับ</th>
                            <th>ชื่อ</th>
                            <th class="text-center">สถานะการใช้งาน</th>
                            <th class="text-center">คำสั่ง</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($promotion))
                            @foreach ($promotion as $key => $item)
                            <tr>
                                <td style="text-align: center">{{ $key + 1 }}</td>
                                <td>{{ $item->name }}</td>
                                <td style="text-align: center;">
                                    @if ($item->status == 1)
                                        <button type="button" class="btn btn-light-success btn-sm btn-status" value="{{ $item->id }}">ใช้งาน</button>
                                    @else
                                        <button type="button" class="btn btn-light-danger btn-sm btn-status" value="{{ $item->id }}">ปิดใช้งาน</button>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">ทำรายการ &nbsp;</button>
                                        <ul class="dropdown-menu border-0 shadow p-3">
                                            <li><a href="{{ asset($path.$item->name) }}" class="dropdown-item py-2 rounded" target="_blank" data-toggle="tooltip" data-placement="top" title="พิมพ์เอกสาร">View</a></li>
                                            <li><li><a class="dropdown-item py-2 rounded" href="{{ url('/Mpromotion/delete/'.$item->id) }}">ลบรายการ</a></li></li>
                                        </ul>
                                    </div>

                                </td>
                            </tr>
                            @endforeach
                            @endif
                    </tbody>
                </table>
                </form>
            </div> <!-- .card end -->
        </div>
    </div> <!-- .row end -->
</div>

<form id="form-id3">
    @csrf
    <input type="hidden" id="deleteID" name="deleteID" value="">
</form>

@include('script.script')

@endsection
