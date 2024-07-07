@extends('layouts.masterLayout')

@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to Proposal.</small>
                <h1 class="h4 mt-1">Proposal (ข้อเสนอ)</h1>
            </div>
            <div class="col-auto">
                @if (@Auth::user()->roleMenuAdd('Proposal') == 1)
                <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ route('Quotation.create') }}'">
                    <i class="fa fa-plus"></i> เพิ่มใบเสนอราคา</button>
                @endif
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
                <ul class="dropdown-menu border-0 shadow p-3">
                    <li><a class="dropdown-item py-2 rounded" href="{{ route('Quotation.index') }}">ทั้งหมด</a></li>
                    <li><a class="dropdown-item py-2 rounded" href="{{ route('Quotation.ac', ['value' => 1]) }}">ใช้งาน</a></li>
                    <li><a class="dropdown-item py-2 rounded" href="{{ route('Quotation.no', ['value' => 0]) }}">ปิดใช้งาน</a></li>
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
                <table class="myDataTableQuotation table table-hover align-middle mb-0" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th>Company</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th class="text-center">Special Discount Requect</th>
                            <th class="text-center">Approve Special Discount By</th>
                            <th class="text-center">Operated By</th>
                            <th class="text-center">Document status</th>
                            <th class="text-center">Order</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($Quotation))
                            @foreach ($Quotation as $key => $item)
                            <tr>
                                <td style="text-align: center;">{{ $key + 1 }}</td>
                                <td>{{ $item->Quotation_ID }}</td>
                                <td>{{ @$item->company->Company_Name}}</td>
                                <td>{{ $item->checkin }}</td>
                                <td>{{ $item->checkout }}</td>
                                <td style="text-align: center;">
                                    @if ($item->SpecialDiscount == 0)
                                        -
                                    @else
                                        SP-{{$item->SpecialDiscount}}
                                    @endif
                                </td>
                                <td >{{ @$item->userConfirm->name }}</td>
                                <td >{{ @$item->userOperated->name }}</td>
                                <td style="text-align: center;">
                                    @if ($item->status_document == 1)
                                    <button type="button" class="btn btn-light-warning btn-sm" disabled>Awaiting</button>
                                    @elseif ($item->status_document == 2)
                                        <button type="button" class="btn btn-light-success btn-sm" disabled>Confirmed</button>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                        <ul class="dropdown-menu border-0 shadow p-3">
                                            @if (@Auth::user()->roleMenuView('Proposal') == 1)
                                                <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Quotation/Quotation/cover/document/PDF/'.$item->id) }}">View</a></li>
                                            @endif
                                            @if (@Auth::user()->roleMenuEdit('Proposal') == 1)
                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Quotation/edit/quotation/'.$item->id) }}">Edit</a></li>
                                            @endif
                                            @if (@Auth::user()->roleMenuSpecialDiscount('Proposal') == 1)
                                                <li><a class="dropdown-item py-2 rounded" data-bs-toggle="modal" data-bs-target="#Sp">Request Special Discount</a></li>
                                            @endif
                                            <li><a class="dropdown-item py-2 rounded" href="#">Deposit</a></li>
                                            @if ($item->status_document != 2)
                                            <li><a class="dropdown-item py-2 rounded" onclick="btnstatus('{{ $item->id }}', 2)">Confirmed</a></li>
                                            @endif
                                            <li><a class="dropdown-item py-2 rounded" onclick="btnstatus('{{ $item->id }}', 0)">Canceled</a></li>
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
    </div>
    <div class="modal fade" id="Sp" tabindex="-1" aria-labelledby="PrenameModalCenterTitle"
    style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="PrenameModalCenterTitle">ขอส่วนลดท้ายบิล</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                            <div class="card-body">
                                @foreach ($Quotation as $key => $item)
                                    <form action="{{ route('quotation.specialDis', $item->id) }}" method="POST" enctype="multipart/form-data" class="row g-3 basic-form" id="form-id">
                                        @csrf
                                        <div class="col-sm-12 col-12">
                                            <label class="form-label">Special Discount </label>
                                            <input type="text" id="SpecialDis" name="SpecialDis" class="form-control">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary lift" data-bs-dismiss="modal">ยกเลิก</button>
                                            <button type="submit" class="btn btn-color-green lift" id="btn-save">ยืนยัน</button>
                                        </div>
                                    </form>
                                @endforeach
                            </div>
                    </div><!-- Form Validation -->
                </div>
            </div>
        </div>
    </div> <!-- .row end -->
</div>

<form id="form-id3">
    @csrf
    <input type="hidden" id="deleteID" name="deleteID" value="">
</form>

@include('script.script')

<script>
function btnstatus(itemId, status) {
    jQuery.ajax({
        type: "GET",
        url: "{!! url('/Quotation/change-Status/') !!}" + "/" + itemId + "/" + status,
        dataType: "JSON",
        success: function(result) {
            Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
            // Optionally reload the page after successful update
            window.location.reload();
        },
        error: function(xhr, error) {
            Swal.fire('เกิดข้อผิดพลาด!', 'ไม่สามารถบันทึกข้อมูลได้', 'error');
            console.error(xhr.responseText);
        }
    });
}
</script>
@endsection
