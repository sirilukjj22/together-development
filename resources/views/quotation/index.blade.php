@extends('layouts.masterLayout')

@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to Dummy Proposal.</small>
                <h1 class="h4 mt-1">Dummy Proposal (แบบร่างข้อเสนอ)</h1>
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
<style>
    .tab1{
    background-color: white;
    color: black; /* เปลี่ยนสีตัวอักษรเป็นสีดำหากต้องการ */
}
</style>
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
        <div class="col-auto ">
            <div class="dropdown">
                <button type="button" class="btn btn-color-green lift btn_modal" id="Submit_Documents"><i class="fa fa-paper-plane-o"></i> ส่งเอกสาร</button>
            </div>
        </div>
    </div> <!-- Row end  -->

    <div class="row clearfix">
        <div class="col-sm-12 col-12">
            <ul class="nav nav-tabs px-3 border-bottom-0" role="tablist">
                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#nav-Dummy" role="tab"> <span class="badge" style="background-color:#64748b">{{$Proposalcount}}</span> Proposal</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#nav-Pending" role="tab"><span class="badge" style="background-color:#FF6633">{{0}}</span> Pending</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#nav-Awaiting" role="tab"><span class="badge bg-warning" >{{0}}</span> Awaiting Approval</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#nav-Approved" role="tab"><span class="badge bg-success" >{{0}}</span> Approved</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#nav-Reject" role="tab"><span class="badge "style="background-color:#1d4ed8" >{{0}}</span> Reject</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#nav-Cancel" role="tab"><span class="badge bg-danger" >{{0}}</span> Cancel</a></li>
            </ul>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="nav-Dummy" role="tabpanel">
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
                                            <th class="text-center">Discount (%)</th>
                                            <th class="text-center">Discount (Bath)</th>
                                            <th class="text-center">Approve  By</th>
                                            <th class="text-center">Operated By</th>
                                            <th class="text-center">Document status</th>
                                            <th class="text-center">Order</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($Proposal))
                                            @foreach ($Proposal as $key => $item)
                                            <tr>
                                                <td style="text-align: center;">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input checkbox-select checkbox-{{$key + 1}}" type="checkbox" name="checkbox[]" value="{{ $item->id }}" id="checkbox-{{$key + 1}}" rel="{{ $item->vat }}">
                                                        <label class="form-check-label" for="checkbox-{{$key + 1}}"></label>
                                                    </div>
                                                </td>
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
                                                <td style="text-align: center;">
                                                    @if ($item->SpecialDiscountBath	== 0)
                                                        -
                                                    @else
                                                        <i class="bi bi-check-lg text-green" ></i>
                                                    @endif
                                                </td>
                                                <td >
                                                    @if ($item->SpecialDiscountBath == 0 && $item->SpecialDiscount == 0)
                                                        Auto
                                                    @else
                                                        @if ($item->Confirm_by == 0)
                                                        -
                                                        @else
                                                        {{ @$item->userConfirm->name }}
                                                        @endif
                                                    @endif
                                                </td>
                                                <td >{{ @$item->userOperated->name }}</td>
                                                <td style="text-align: center;">
                                                    @if($item->status_document == 0)
                                                        <span class="badge rounded-pill bg-danger">Cancel</span>
                                                    @elseif ($item->status_document == 1)
                                                        <span class="badge rounded-pill "style="background-color: #FF6633	">Pending</span>
                                                    @elseif ($item->status_document == 2)
                                                        <span class="badge rounded-pill bg-warning">Awaiting Approva</span>
                                                    @elseif ($item->status_document == 3)
                                                        <span class="badge rounded-pill bg-success">Approved</span>
                                                    @elseif ($item->status_document == 4)
                                                        <span class="badge rounded-pill "style="background-color:#1d4ed8">Reject</span>
                                                    @elseif ($item->status_document == 5)
                                                        <span class="badge rounded-pill "style="background-color:#0ea5e9">Generate</span>
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
                                                                @if (($item->status_document == 2))
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Quotation/edit/quotation/'.$item->id) }}">Edit</a></li>
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
                            </form>

                        </div>
                        <div class="tab-pane fade" id="nav-Pending" role="tabpanel">

                        </div>
                        <div class="tab-pane fade" id="nav-Awaiting" role="tabpanel">

                        </div>
                        <div class="tab-pane fade" id="nav-Approved" role="tabpanel">

                        </div>
                        <div class="tab-pane fade" id="nav-Reject" role="tabpanel">

                        </div>
                        <div class="tab-pane fade" id="nav-Cancel" role="tabpanel">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="form-id3">
    @csrf
    <input type="hidden" id="deleteID" name="deleteID" value="">
</form>

@include('script.script')

<script>
    document.getElementById('Submit_Documents').addEventListener('click', function() {
        // Select all checked checkboxes
        const checkedCheckboxes = document.querySelectorAll('.form-check-input:checked');

        // Get all the IDs of checked checkboxes
        const ids = Array.from(checkedCheckboxes).map(checkbox => checkbox.value);

        if (ids.length > 0) {
            // Create query string from ids array
            const queryString = new URLSearchParams({ ids: ids }).toString();
            const url = `{{ route('Quotation.senddocuments') }}?${queryString}`;

            fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to submit documents.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while submitting documents.');
            });
        } else {
            alert('Please select at least one checkbox.');
        }
    });

    function Generate(){
        var id  = $('#id').val();
        Swal.fire({
        title: "คุณต้องการ Generate รายการนี้ใช่หรือไม่?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "ตกลง",
        cancelButtonText: "ยกเลิก",
        confirmButtonColor: "#28a745",
        dangerMode: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ url('/Dummy/Quotation/Generate/') }}/" + id;
            }
        });
    }
    function Cancel(){
        var id  = $('#id').val();
        Swal.fire({
        title: "คุณต้องการ Generate รายการนี้ใช่หรือไม่?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "ตกลง",
        cancelButtonText: "ยกเลิก",
        confirmButtonColor: "#28a745",
        dangerMode: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ url('/Dummy/Quotation/cancel/') }}/" + id;
            }
        });
    }
</script>
@endsection
