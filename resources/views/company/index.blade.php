@extends('layouts.masterLayout')

@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to Company / Agent.</small>
                <h1 class="h4 mt-1">Company / Agent (บริษัทและตัวแทน)</h1>
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ route('Company.create') }}'">
                    <i class="fa fa-plus"></i> เพิ่มบริษัทและตัวแทน</button>
            </div>
        </div>
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
                    <li><a class="dropdown-item py-2 rounded" href="{{ route('Company.index') }}">ทั้งหมด</a></li>
                    <li><a class="dropdown-item py-2 rounded" href="{{ route('Company.ac', ['value' => 1]) }}">ใช้งาน</a></li>
                    <li><a class="dropdown-item py-2 rounded" href="{{ route('Company.no', ['value' => 0]) }}">ปิดใช้งาน</a></li>
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
                            <th>เรียงลำดับ</th>
                            <th>รหัสโปรไฟล์</th>
                            <th>ชื่อองค์กร</th>
                            <th>สาขา</th>
                            <th>Booking Channal</th>
                            <th class="text-center">สถานะการใช้งาน</th>
                            <th class="text-center">คำสั่ง</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($Company))
                            @foreach ($Company as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->Profile_ID }}</td>
                                <td>{{ $item->Company_Name }}</td>
                                <td>{{ $item->Branch }}</td>
                                <td>
                                    @php
                                        $Mbooking = explode(',', $item->Booking_Channel);

                                        foreach ($Mbooking as $key => $value) {
                                            $bc = App\Models\master_document::find($value);
                                            echo $bc->name_en . '<br>';
                                        }

                                    @endphp
                                </td>
                                <td style="text-align: center;">
                                    @if ($item->status == 1)
                                    <button type="button" class="btn btn-light-success btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ใช้งาน</button>
                                    @else
                                        <button type="button" class="btn btn-light-danger btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ปิดใช้งาน</button>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">ทำรายการ &nbsp;</button>
                                        <ul class="dropdown-menu border-0 shadow p-3">
                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Company/edit/contact/detail/'.$item->id) }}">ดูรายละเอียด</a></li>
                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Company/edit/'.$item->id) }}">แก้ไขรายการ</a></li>
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
