@extends('layouts.masterLayout')

@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to Proforma Invoice.</small>
                <h1 class="h4 mt-1">Proforma Invoice (ใบแจ้งหนี้)</h1>
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
    </div> <!-- Row end  -->

    <div class="row clearfix">
        <div class="col-sm-12 col-12">
            <ul class="nav nav-tabs px-3 border-bottom-0" role="tablist">
                <li class="nav-item" id="nav4"><a class="nav-link active" data-bs-toggle="tab" href="#nav-Approved" role="tab"><span class="badge "style="background-color:#64748b">{{$Approvedcount}}</span> Approved</a></li>
                <li class="nav-item" id="nav2"><a class="nav-link " data-bs-toggle="tab" href="#nav-invoice" role="tab"> <span class="badge bg-warning" >{{$invoicecount}}</span> Invoice</a></li>
                <li class="nav-item" id="nav3"><a class="nav-link" data-bs-toggle="tab" href="#nav-Complete" role="tab"><span class="badge bg-success" >{{$Completecount}}</span> Complete</a></li>
                <li class="nav-item" id="nav4"><a class="nav-link" data-bs-toggle="tab" href="#nav-Cancel" role="tab"><span class="badge bg-danger" >{{$Cancelcount}}</span> Cancel</a></li>
            </ul>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="nav-Approved" role="tabpanel" rel="0">
                            <form enctype="multipart/form-data" class="row g-3 basic-form" id="form-id2">
                                @csrf
                                <input type="hidden" name="category" value="prename">
                                <table class="myTableProposalRequest1 table table-hover align-middle mb-0" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>ID</th>
                                            <th>Company</th>
                                            <th>Issue Date</th>
                                            <th>Expiration Date</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Approve By</th>
                                            <th class="text-center">Document status</th>
                                            <th class="text-center">Order</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($Approved))
                                        @foreach ($Approved as $key => $item)
                                        <tr>
                                            <td style="text-align: center;">
                                               {{$key +1}}
                                            </td>
                                            <td>{{ $item->DummyNo}}</td>
                                            <td>{{ @$item->company->Company_Name}}</td>
                                            <td>{{ $item->issue_date }}</td>
                                            <td>{{ $item->Expirationdate }}</td>
                                            <td style="text-align: center;">
                                                {{$item->Nettotal}}
                                            </td>
                                            <td style="text-align: center;">
                                                @if (@$item->userConfirm->name == null)
                                                    -
                                                @else
                                                    {{ @$item->userConfirm->name }}
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                <span class="badge rounded-pill bg-success">Approved</span>
                                            </td>
                                            <td style="text-align: center;">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                    <ul class="dropdown-menu border-0 shadow p-3">
                                                        <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Invoice/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/Generate/'.$item->id) }}">Generate</a></li>
                                                        <li><a class="dropdown-item py-2 rounded" onclick="Cancel('{{$item->id}}')">Cancel</a></li>
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
                        <div class="tab-pane fade" id="nav-invoice" role="tabpanel" rel="0">
                            <form enctype="multipart/form-data" class="row g-3 basic-form" id="form-id2">
                                @csrf
                                <input type="hidden" name="category" value="prename">
                                <table class="myTableProposalRequest2 table table-hover align-middle mb-0" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">ID</th>
                                            <th>Company</th>
                                            <th class="text-center">Issue Date</th>
                                            <th class="text-center">Expiration Date</th>
                                            <th class="text-center">Payment</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Approve By</th>
                                            <th class="text-center">Document status</th>
                                            <th class="text-center">Order</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($invoice))
                                        @foreach ($invoice as $key => $item)
                                        <tr>
                                            <td style="text-align: center;">
                                               {{$key +1}}
                                            </td>
                                            <td>{{ $item->Invoice_ID}}</td>
                                            <td>{{ @$item->company00->Company_Name}}</td>
                                            <td style="text-align: center;">{{ $item->IssueDate }}</td>
                                            <td style="text-align: center;">{{ $item->Expiration }}</td>
                                            <td style="text-align: center;">{{$item->payment}}</td>
                                            <td style="text-align: center;">
                                                {{$item->Nettotal}}
                                            </td>
                                            <td style="text-align: center;">
                                                @if (@$item->userConfirm->name == null)
                                                    -
                                                @else
                                                    {{ @$item->userConfirm->name }}
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                <span class="badge rounded-pill bg-warning">Invoice</span>
                                            </td>
                                            <td style="text-align: center;">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                    <ul class="dropdown-menu border-0 shadow p-3">
                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/view/'.$item->id) }}">View</a></li>
                                                        <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Invoice/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                        <li><a class="dropdown-item py-2 rounded" onclick="Approved({{ $item->id }})">Approved</a></li>
                                                        <li><a class="dropdown-item py-2 rounded" onclick="Revice('{{$item->id}}')">Cancel</a></li>
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
                        <div class="tab-pane fade" id="nav-Complete" role="tabpanel" rel="0">
                            <form enctype="multipart/form-data" class="row g-3 basic-form" id="form-id2">
                                @csrf
                                <input type="hidden" name="category" value="prename">
                                <table class="myTableProposalRequest3 table table-hover align-middle mb-0" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">ID</th>
                                            <th>Company</th>
                                            <th class="text-center">Issue Date</th>
                                            <th class="text-center">Expiration Date</th>
                                            <th class="text-center">Payment</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Approve By</th>
                                            <th class="text-center">Document status</th>
                                            <th class="text-center">Order</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($Complete))
                                        @foreach ($Complete as $key => $item)
                                        <tr>
                                            <td style="text-align: center;">
                                               {{$key +1}}
                                            </td>
                                            <td>{{ $item->Invoice_ID}}</td>
                                            <td>{{ @$item->company00->Company_Name}}</td>
                                            <td style="text-align: center;">{{ $item->IssueDate }}</td>
                                            <td style="text-align: center;">{{ $item->Expiration }}</td>
                                            <td style="text-align: center;">{{$item->payment}}</td>
                                            <td style="text-align: center;">
                                                {{$item->Nettotal}}
                                            </td>
                                            <td style="text-align: center;">
                                                @if (@$item->userConfirm->name == null)
                                                    -
                                                @else
                                                    {{ @$item->userConfirm->name }}
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                <span class="badge rounded-pill bg-success">Complete</span>
                                            </td>
                                            <td style="text-align: center;">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                    <ul class="dropdown-menu border-0 shadow p-3">
                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/view/'.$item->id) }}">View</a></li>
                                                        <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Invoice/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                        <li><a class="dropdown-item py-2 rounded" onclick="Revice('{{$item->id}}')">Cancel</a></li>
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
                        <div class="tab-pane fade" id="nav-Cancel" role="tabpanel" rel="0">
                            <form enctype="multipart/form-data" class="row g-3 basic-form" id="form-id2">
                                @csrf
                                <input type="hidden" name="category" value="prename">
                                <table class="myTableProposalRequest4 table table-hover align-middle mb-0" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">ID</th>
                                            <th>Company</th>
                                            <th class="text-center">Issue Date</th>
                                            <th class="text-center">Expiration Date</th>
                                            <th class="text-center">Payment</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Approve By</th>
                                            <th class="text-center">Document status</th>
                                            <th class="text-center">Order</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($Cancel))
                                        @foreach ($Cancel as $key => $item)
                                        <tr>
                                            <td style="text-align: center;">
                                                {{$key +1}}
                                            </td>
                                            <td>{{ $item->Invoice_ID}}</td>
                                            <td>{{ @$item->company00->Company_Name}}</td>
                                            <td style="text-align: center;">{{ $item->IssueDate }}</td>
                                            <td style="text-align: center;">{{ $item->Expiration }}</td>
                                            <td style="text-align: center;">{{$item->payment}}</td>
                                            <td style="text-align: center;">
                                                {{$item->Nettotal}}
                                            </td>
                                            <td style="text-align: center;">
                                                @if (@$item->userConfirm->name == null)
                                                    -
                                                @else
                                                    {{ @$item->userConfirm->name }}
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                <span class="badge rounded-pill bg-danger">Cancel</span>
                                            </td>
                                            <td style="text-align: center;">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                    <ul class="dropdown-menu border-0 shadow p-3">
                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/view/'.$item->id) }}">View</a></li>
                                                        <li><a class="dropdown-item py-2 rounded" onclick="Revice('{{$item->id}}')">Revice</a></li>
                                                        <li><a class="dropdown-item py-2 rounded" onclick="Delete('{{$item->id}}')">Delete</a></li>
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
    $(document).ready(function () {
        $('.myTableProposalRequest1').addClass('nowrap').dataTable({
            responsive: true,
            searching: true,
            paging: true,
            ordering: true,
            info: true,
            columnDefs: [
                // className: 'bolded'
                // { targets: [-1, -3], className: 'dt-body-right' }
            ]
        });
    });
    $('#nav2').on('click', function () {
        var status = $('#nav-invoice').attr('rel');

        if (status == 0) {
            document.getElementById("nav-invoice").setAttribute("rel", "1");
            $('.myTableProposalRequest2').addClass('nowrap').dataTable({
                responsive: true,
                searching: true,
                paging: true,
                ordering: true,
                info: true,
                columnDefs: [
                    // className: 'bolded'
                    // { targets: [-1, -3], className: 'dt-body-right' }
                ]

            });
        }
    })
    $('#nav3').on('click', function () {
        var status = $('#nav-Complete').attr('rel');

        if (status == 0) {
            document.getElementById("nav-Complete").setAttribute("rel", "1");
            $('.myTableProposalRequest3').addClass('nowrap').dataTable({
                responsive: true,
                searching: true,
                paging: true,
                ordering: true,
                info: true,
                columnDefs: [
                    // className: 'bolded'
                    // { targets: [-1, -3], className: 'dt-body-right' }
                ]

            });
        }
    })
    $('#nav4').on('click', function () {
        var status = $('#nav-Cancel').attr('rel');

        if (status == 0) {
            document.getElementById("nav-Cancel").setAttribute("rel", "1");
            $('.myTableProposalRequest4').addClass('nowrap').dataTable({
                responsive: true,
                searching: true,
                paging: true,
                ordering: true,
                info: true,
                columnDefs: [
                    // className: 'bolded'
                    // { targets: [-1, -3], className: 'dt-body-right' }
                ]

            });
        }
    })
    function Approved(id) {
        jQuery.ajax({
            type: "GET",
            url: "/Document/Request/document/Approve/invoice/" + id,
            datatype: "JSON",
            async: false,
            success: function(response) {
                console.log("AJAX request successful: ", response);
                if (response.success) {
                    // เปลี่ยนไปยังหน้าที่ต้องการ
                    location.reload();
                } else {
                    alert("An error occurred while processing the request.");
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed: ", status, error);
            }
        });
    }
    function Revice(id){
        Swal.fire({
        title: "คุณต้องการปิดการใช้งานใบแจ้งหนี้นี้ใช่หรือไม่?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "ตกลง",
        cancelButtonText: "ยกเลิก",
        confirmButtonColor: "#28a745",
        dangerMode: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ url('/Document/invoice/ReviceCancel/') }}/" + id;
            }
        });

    }
    function Delete(id){
        Swal.fire({
        title: "คุณต้องการลบใบแจ้งหนี้นี้ใช่หรือไม่?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "ตกลง",
        cancelButtonText: "ยกเลิก",
        confirmButtonColor: "#28a745",
        dangerMode: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ url('/Document/invoice/Delete/') }}/" + id;
            }
        });

    }

</script>
@endsection
