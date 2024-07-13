@extends('layouts.masterLayout')

@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to Proposal.</small>
                <h1 class="h4 mt-1">Proposal (ข้อเสนอ)</h1>
            </div>
            <div class="col-auto">
                @if (@Auth::user()->roleMenuAdd('Proposal',Auth::user()->id) == 1)
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
                            <th>Issue Date</th>
                            <th>Expiration Date</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th class="text-center">Special Discount</th>
                            <th class="text-center">Approve  By</th>
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
                                <td>{{ $item->issue_date }}</td>
                                <td>{{ $item->Expirationdate }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->checkin)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->checkout)->format('d/m/Y') }}</td>
                                <td style="text-align: center;">
                                    @if ($item->SpecialDiscount == 0)
                                        -
                                    @else
                                        <i class="bi bi-check-lg text-green" ></i>
                                    @endif
                                </td>
                                <td >{{ @$item->userConfirm->name }}</td>
                                <td >{{ @$item->userOperated->name }}</td>
                                <td style="text-align: center;">
                                    @if ($item->status_document == 1)
                                        <span class="badge rounded-pill bg-warning">Awaiting</span>
                                    @elseif ($item->status_document == 2)
                                        <span class="badge rounded-pill bg-success">Confirmed</span>
                                    @elseif ($item->status_document == 3)
                                        <span class="badge rounded-pill bg-info">Wait Approve</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                        <ul class="dropdown-menu border-0 shadow p-3">
                                            @if (@Auth::user()->roleMenuView('Proposal',Auth::user()->id) == 1)
                                                <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Quotation/Quotation/cover/document/PDF/'.$item->id) }}">View</a></li>
                                            @endif
                                            @if (@Auth::user()->roleMenuEdit('Proposal',Auth::user()->id) == 1)
                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Quotation/edit/quotation/'.$item->id) }}">Edit</a></li>
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
