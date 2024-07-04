@extends('layouts.masterLayout')

@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to Proposal Request.</small>
                <h1 class="h4 mt-1">Proposal Request (คำขอข้อเสนอ)</h1>
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
    </div> <!-- Row end  -->

    <div class="row clearfix">
        <div class="col-sm-12 col-12">
            <div class="card p-4 mb-4">
                <form enctype="multipart/form-data" class="row g-3 basic-form" id="form-id2">
                    @csrf
                    <input type="hidden" name="category" value="prename">
                    <table class="myDataTableQuotation table table-hover align-middle mb-0" style="width:100%">
                        <thead>
                            <tr>
                                <th>เรียงลำดับ</th>
                                <th>รหัสโปรไฟล์</th>
                                <th>ชื่อองค์กร</th>
                                <th>ตัวแทน</th>
                                <th class="text-center">SpecialDiscount</th>
                                <th class="text-center">สถานะยืนยันใบเสนอ</th>
                                <th class="text-center">Operated By</th>
                                <th class="text-center">คำสั่ง</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($Quotation))
                                @foreach ($Quotation as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->Quotation_ID }}</td>
                                    <td>{{ @$item->company->Company_Name}}</td>
                                    <td>{{@$item->contact->First_name}} {{@$item->contact->Last_name}}</td>
                                    <td style="text-align: center;">{{ $item->SpecialDiscount }}</td>
                                    <td style="text-align: center;">
                                        @if ($item->Confirm == 1)
                                        <button type="button" class="btn btn-light-success btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">ยืนยันแล้ว</button>
                                        @else
                                            <button type="button" class="btn btn-light-danger btn-sm" value="{{ $item->id }}" onclick="btnstatus({{ $item->id }})">รอการยืนยัน</button>
                                        @endif
                                    </td>
                                    <td >{{ @$item->userOperated->name }}</td>
                                    <td style="text-align: center;">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">ทำรายการ &nbsp;</button>
                                            <ul class="dropdown-menu border-0 shadow p-3">
                                                <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Quotation/Quotation/cover/document/PDF/'.$item->id) }}">ดูรายละเอียดใบเสนอ</a></li>
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
        url: "{!! url('/Quotation/change-confirm/" + id + "') !!}",
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
