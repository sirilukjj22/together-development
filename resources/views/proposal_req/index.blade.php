@extends('layouts.masterLayout')

@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to Proposal Request.</small>
                <h1 class="h4 mt-1">Proposal Request</h1>
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
    </div> <!-- Row end  -->
    <div class="row clearfix">
        <div class="col-sm-12 col-12">
            <ul class="nav nav-tabs px-3 border-bottom-0" role="tablist">
                <li class="nav-item" id="nav1"><a class="nav-link active" data-bs-toggle="tab" href="#nav-Awaiting" role="tab"><span class="badge bg-warning" >{{$proposalcount}}</span> Awaiting Approval</a></li>
                <li class="nav-item" id="nav2"><a class="nav-link" data-bs-toggle="tab" href="#nav-Approved" role="tab"><span class="badge bg-success" >{{$Logproposalcount}}</span> Approved</a></li>
                <li class="nav-item"id="nav3"><a class="nav-link" data-bs-toggle="tab" href="#nav-Cancel" role="tab"><span class="badge bg-danger" >{{$logdummycount}}</span> Cancel</a></li>
            </ul>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="nav-Awaiting" role="tabpanel">
                            <form enctype="multipart/form-data" class="row g-3 basic-form" id="form-id2">
                                @csrf
                                <input type="hidden" name="category" value="prename">
                            <table class="myTableProposalRequest1 table table-hover align-middle mb-0" >
                                <thead>
                                    <tr>
                                        <th  class="text-center"style="width: 5%">No</th>
                                        <th>Company</th>
                                        <th   class="text-center" style="width: 15%">QuotationType</th>
                                        <th  class="text-center" style="width: 15%">Operated_by</th>
                                        <th  class="text-center"style="width: 5%">Count</th>
                                        <th class="text-center"style="width: 10%">Document status</th>
                                        <th class="text-center" style="width: 10%">Order</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($proposal))
                                        @foreach ($proposal as $key => $item)
                                        <tr>
                                            <td style="text-align: center;">{{ $key+1}}</td>
                                            <td>{{ @$item->company2->Company_Name}}</td>
                                            <td>{{$item->QuotationType}}</td>
                                            <td style="text-align: center;">{{ @$item->userOperated->name }}</td>
                                            <td style="text-align: center;">{{ $item->COUNTDummyNo }}</td>
                                            <td><span class="badge rounded-pill bg-warning">Awaiting Approva</span></td>
                                            <td style="text-align: center;">
                                                <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ url('/Dummy/Proposal/Request/document/view/'.$item->Company_ID) }}'">
                                                    <i class="fa fa-folder-open-o"></i> View
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif

                                </tbody>
                            </table>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="nav-Approved" role="tabpanel">
                            <div class="col-md-12">
                                <form action="{{ url('/Proposal/request/search/Approved') }}" method="GET">
                                    <div class="row">
                                        <div class="col-md-3 d-flex align-items-center" >
                                            <input type="date" name="selectday" id="selectday" class="form-control" style="margin-right: 10px;">
                                            <input class="form-check-input" type="checkbox" name="checkbox" value="all" id="checkbox"> All
                                        </div>
                                        <div class="col-md-2 ">
                                            <button type="submit" class="btn btn-color-green lift btn_modal">search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <form enctype="multipart/form-data" class="row g-3 basic-form" id="form-id2">
                                @csrf
                                <input type="hidden" name="category" value="prename">
                            <table  class="myTableProposalRequest2 table table-hover align-middle mb-0" >
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID</th>
                                        <th>Company</th>
                                        <th class="text-center">Type</th>
                                        <th>Issue Date</th>
                                        <th>Expiration Date</th>
                                        <th>Awaiting at</th>
                                        <th>Approve at</th>
                                        <th class="text-center">Discount (%)</th>
                                        <th class="text-center">Discount (Bath)</th>
                                        <th class="text-center">Approve By</th>
                                        <th class="text-center">Document status</th>
                                        <th class="text-center">Order</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($Logproposal))
                                        @foreach ($Logproposal as $key => $item)
                                        <tr>
                                            <td style="text-align: center;">
                                               {{$key+1}}
                                            </td>
                                            <td>{{ $item->DummyNo}}<input type="hidden" name="id" id="id" value="{{$item->id}}"></td>
                                            <td>{{ @$item->company->Company_Name}}</td>
                                            <td>{{$item->QuotationType}}</td>
                                            <td>{{ $item->issue_date }}</td>
                                            <td>{{ $item->Expirationdate }}</td>
                                            <td>{{ $item->issue_date }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->Approve_at)->format('d/m/Y') }}</td>
                                            <td style="text-align: center;">
                                                @if ($item->SpecialDiscount == 0)
                                                    -
                                                @else
                                                    <i class="bi bi-check-lg text-green" ></i>
                                                @endif
                                            </td>
                                            <td>-</td>
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
                                                    <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Action &nbsp;</button>
                                                    <ul class="dropdown-menu border-0 shadow p-3">
                                                        @if (@Auth::user()->roleMenuView('Proposal',Auth::user()->id) == 1)
                                                            <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Dummy/Proposal/Request/document/view/Approve/viewApprove/'.$item->id) }}">View</a></li>
                                                            <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Dummy/Quotation/Quotation/cover/document/PDF/'.$item->id) }}">Export</a></li>
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
                        <div class="tab-pane fade" id="nav-Cancel" role="tabpanel">
                            <div class="col-md-12">
                                <form action="{{ url('/Proposal/request/search/cancel') }}" method="GET">
                                    <div class="row">
                                        <div class="col-md-3 d-flex align-items-center" >
                                            <input type="date" name="selectday" id="selectday" class="form-control" style="margin-right: 10px;">
                                            <input class="form-check-input" type="checkbox" name="checkbox" value="all" id="checkbox"> All
                                        </div>
                                        <div class="col-md-2 ">
                                            <button type="submit" class="btn btn-color-green lift btn_modal">search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <form enctype="multipart/form-data" class="row g-3 basic-form" id="form-id2">
                                @csrf
                            <input type="hidden" name="category" value="prename">
                            <table class="myTableProposalRequest3 table table-hover align-middle mb-0" >
                                <thead>
                                    <tr>
                                        <th style="text-align: center">No</th>
                                        <th>ID</th>
                                        <th style="text-align: center">Date</th>
                                        <th style="text-align: center">Time</th>
                                        <th class="text-center">Export</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($logdummy))
                                        @foreach ($logdummy as $key => $item)
                                            <tr>
                                                <td>{{$key +1}}</td>
                                                <td>{{$item->Quotation_ID}}</td>
                                                <td style="text-align: center">{{$item->Approve_date}}</td>
                                                <td style="text-align: center">{{$item->Approve_time}}</td>
                                                <td>
                                                    <a href="{{ asset($path.$item->Quotation_ID.".pdf") }}" type="button" class="btn btn-outline-dark rounded-pill lift" target="_blank" data-toggle="tooltip" data-placement="top" title="พิมพ์เอกสาร">
                                                        <i class="fa fa-print"></i>
                                                    </a>
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

    $('#nav1').on('click', function () {
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
    })
    $('#nav2').on('click', function () {
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
    })
    $('#nav3').on('click', function () {
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
    })


</script>
@endsection
