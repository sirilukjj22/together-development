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
                <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ route('DummyQuotation.create') }}'">
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
                <li class="nav-item" id="nav1"><a class="nav-link active" data-bs-toggle="tab" href="#nav-Dummy" role="tab"> <span class="badge" style="background-color:#64748b">{{$Quotationcount}}</span> Proposal</a></li>
                <li class="nav-item" id="nav2"><a class="nav-link" data-bs-toggle="tab" href="#nav-Pending" role="tab"><span class="badge" style="background-color:#FF6633">{{$Pendingcount}}</span> Pending</a></li>
                <li class="nav-item" id="nav3"><a class="nav-link" data-bs-toggle="tab" href="#nav-Awaiting" role="tab"><span class="badge bg-warning" >{{$Awaitingcount}}</span> Awaiting Approval</a></li>
                <li class="nav-item" id="nav4"><a class="nav-link" data-bs-toggle="tab" href="#nav-Approved" role="tab"><span class="badge bg-success" >{{$Approvedcount}}</span> Approved</a></li>
                <li class="nav-item" id="nav5"><a class="nav-link" data-bs-toggle="tab" href="#nav-Generate" role="tab"><span class="badge " style="background-color: #0ea5e9">{{$Generatecount}}</span> Generate</a></li>
                <li class="nav-item" id="nav6"><a class="nav-link" data-bs-toggle="tab" href="#nav-Reject" role="tab"><span class="badge "style="background-color:#1d4ed8" >{{$Rejectcount}}</span> Reject</a></li>
                <li class="nav-item" id="nav7"><a class="nav-link" data-bs-toggle="tab" href="#nav-Cancel" role="tab"><span class="badge bg-danger" >{{$cancelcount}}</span> Cancel</a></li>
            </ul>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="nav-Dummy" role="tabpanel" rel="0">
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
                                            <th class="text-center">Special Discount</th>
                                            <th class="text-center">Approve By</th>
                                            <th class="text-center">Document status</th>
                                            <th class="text-center">Order</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($Quotation))
                                        @foreach ($Quotation as $key => $item)
                                        <tr>
                                            <td style="text-align: center;">
                                                @if ($item->status_document == 1)
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input checkbox-select checkbox-{{$key + 1}}" type="checkbox" name="checkbox[]" value="{{ $item->id }}" id="checkbox-{{$key + 1}}" rel="{{ $item->vat }}">
                                                        <label class="form-check-label" for="checkbox-{{$key + 1}}"></label>
                                                    </div>
                                                @else
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input checkbox-select checkbox-{{$key + 1}}" type="checkbox" name="checkbox[]" value="{{ $item->id }}" id="checkbox-{{$key + 1}}" rel="{{ $item->vat }}" disabled>
                                                        <label class="form-check-label" for="checkbox-{{$key + 1}}"></label>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $item->DummyNo}}</td>
                                            <td>{{ @$item->company->Company_Name}}</td>
                                            <td>{{ $item->issue_date }}</td>
                                            <td>{{ $item->Expirationdate }}</td>
                                            <td style="text-align: center;">
                                                @if ($item->SpecialDiscount == 0)
                                                    -
                                                @else
                                                    <i class="bi bi-check-lg text-green" ></i>
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                @if (@$item->userConfirm->name == null)
                                                    -
                                                @else
                                                    {{ @$item->userConfirm->name }}
                                                @endif
                                            </td>
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
                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Quotation/view/'.$item->id) }}">View</a></li>
                                                        <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Dummy/Quotation/Quotation/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                    @endif
                                                    @if ($item->status_document == 1)
                                                        @if (@Auth::user()->roleMenuEdit('Proposal',Auth::user()->id) == 1)
                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Quotation/edit/'.$item->id) }}">Edit</a></li>
                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Quotation/cancel/'.$item->id) }}">Cancel</a></li>
                                                        @endif
                                                    @elseif ($item->status_document == 2)
                                                    @elseif ($item->status_document == 3)
                                                            <li><a class="dropdown-item py-2 rounded" onclick="Generate('{{$item->id}}')">Generate</a></li>
                                                    @elseif ($item->status_document == 4)
                                                        @if (@Auth::user()->roleMenuEdit('Proposal',Auth::user()->id) == 1)
                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Quotation/edit/'.$item->id) }}">Edit</a></li>
                                                            <li><a class="dropdown-item py-2 rounded" onclick="Cancel('{{$item->id}}')">Cancel</a></li>
                                                        @endif
                                                    @elseif ($item->status_document == 5)
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
                        <div class="tab-pane fade" id="nav-Pending" role="tabpanel" rel="0">
                            <form enctype="multipart/form-data" class="row g-3 basic-form" id="form-id2">
                                @csrf
                                <input type="hidden" name="category" value="prename">
                                <table class="myTableProposalRequest2 table table-hover align-middle mb-0" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>ID</th>
                                            <th>Company</th>
                                            <th>Issue Date</th>
                                            <th>Expiration Date</th>
                                            <th class="text-center">Special Discount</th>
                                            <th class="text-center">Approve By</th>
                                            <th class="text-center">Document status</th>
                                            <th class="text-center">Order</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($Pending))
                                            @foreach ($Pending as $key => $item)
                                            <tr>
                                                <td style="text-align: center;">
                                                    @if ($item->status_document == 1)
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input checkbox-select checkbox-{{$key + 1}}" type="checkbox" name="checkbox[]" value="{{ $item->id }}" id="checkbox-{{$key + 1}}" rel="{{ $item->vat }}">
                                                            <label class="form-check-label" for="checkbox-{{$key + 1}}"></label>
                                                        </div>
                                                    @else
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input checkbox-select checkbox-{{$key + 1}}" type="checkbox" name="checkbox[]" value="{{ $item->id }}" id="checkbox-{{$key + 1}}" rel="{{ $item->vat }}" disabled>
                                                            <label class="form-check-label" for="checkbox-{{$key + 1}}"></label>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>{{ $item->DummyNo}}</td>
                                                <td>{{ @$item->company->Company_Name}}</td>
                                                <td>{{ $item->issue_date }}</td>
                                                <td>{{ $item->Expirationdate }}</td>
                                                <td style="text-align: center;">
                                                    @if ($item->SpecialDiscount == 0)
                                                        -
                                                    @else
                                                        <i class="bi bi-check-lg text-green" ></i>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    @if (@$item->userConfirm->name == null)
                                                        -
                                                    @else
                                                        {{ @$item->userConfirm->name }}
                                                    @endif
                                                </td>
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
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            @if (@Auth::user()->roleMenuView('Proposal',Auth::user()->id) == 1)
                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Quotation/view/'.$item->id) }}">View</a></li>
                                                                <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Dummy/Quotation/Quotation/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                            @endif
                                                            @if (@Auth::user()->roleMenuEdit('Proposal',Auth::user()->id) == 1)
                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Quotation/edit/'.$item->id) }}">Edit</a></li>
                                                                <li><a class="dropdown-item py-2 rounded" onclick="Cancel('{{$item->id}}')">Cancel</a></li>
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
                        <div class="tab-pane fade" id="nav-Awaiting" role="tabpanel" rel="0">
                            <form enctype="multipart/form-data" class="row g-3 basic-form" id="form-id2">
                                @csrf
                                <input type="hidden" name="category" value="prename">
                                <table class="myTableProposalRequest3 table table-hover align-middle mb-0" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>ID</th>
                                            <th>Company</th>
                                            <th>Issue Date</th>
                                            <th>Expiration Date</th>
                                            <th class="text-center">Special Discount</th>
                                            <th class="text-center">Approve By</th>
                                            <th class="text-center">Document status</th>
                                            <th class="text-center">Order</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($Awaiting))
                                            @foreach ($Awaiting as $key => $item)
                                            <tr>
                                                <td style="text-align: center;">
                                                    @if ($item->status_document == 1)
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input checkbox-select checkbox-{{$key + 1}}" type="checkbox" name="checkbox[]" value="{{ $item->id }}" id="checkbox-{{$key + 1}}" rel="{{ $item->vat }}">
                                                            <label class="form-check-label" for="checkbox-{{$key + 1}}"></label>
                                                        </div>
                                                    @else
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input checkbox-select checkbox-{{$key + 1}}" type="checkbox" name="checkbox[]" value="{{ $item->id }}" id="checkbox-{{$key + 1}}" rel="{{ $item->vat }}" disabled>
                                                            <label class="form-check-label" for="checkbox-{{$key + 1}}"></label>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>{{ $item->DummyNo}}</td>
                                                <td>{{ @$item->company->Company_Name}}</td>
                                                <td>{{ $item->issue_date }}</td>
                                                <td>{{ $item->Expirationdate }}</td>
                                                <td style="text-align: center;">
                                                    @if ($item->SpecialDiscount == 0)
                                                        -
                                                    @else
                                                        <i class="bi bi-check-lg text-green" ></i>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    @if (@$item->userConfirm->name == null)
                                                        -
                                                    @else
                                                        {{ @$item->userConfirm->name }}
                                                    @endif
                                                </td>
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
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            @if (@Auth::user()->roleMenuView('Proposal',Auth::user()->id) == 1)
                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Quotation/view/'.$item->id) }}">View</a></li>
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
                        <div class="tab-pane fade" id="nav-Approved" role="tabpanel" rel="0">
                            <form enctype="multipart/form-data" class="row g-3 basic-form" id="form-id2">
                                @csrf
                                <input type="hidden" name="category" value="prename">
                                <table class="myTableProposalRequest4 table table-hover align-middle mb-0" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>ID</th>
                                            <th>Company</th>
                                            <th>Issue Date</th>
                                            <th>Expiration Date</th>
                                            <th class="text-center">Special Discount</th>
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
                                                    @if ($item->status_document == 1)
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input checkbox-select checkbox-{{$key + 1}}" type="checkbox" name="checkbox[]" value="{{ $item->id }}" id="checkbox-{{$key + 1}}" rel="{{ $item->vat }}">
                                                            <label class="form-check-label" for="checkbox-{{$key + 1}}"></label>
                                                        </div>
                                                    @else
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input checkbox-select checkbox-{{$key + 1}}" type="checkbox" name="checkbox[]" value="{{ $item->id }}" id="checkbox-{{$key + 1}}" rel="{{ $item->vat }}" disabled>
                                                            <label class="form-check-label" for="checkbox-{{$key + 1}}"></label>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>{{ $item->DummyNo}}</td>
                                                <td>{{ @$item->company->Company_Name}}</td>
                                                <td>{{ $item->issue_date }}</td>
                                                <td>{{ $item->Expirationdate }}</td>
                                                <td style="text-align: center;">
                                                    @if ($item->SpecialDiscount == 0)
                                                        -
                                                    @else
                                                        <i class="bi bi-check-lg text-green" ></i>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    @if (@$item->userConfirm->name == null)
                                                        -
                                                    @else
                                                        {{ @$item->userConfirm->name }}
                                                    @endif
                                                </td>
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
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            @if (@Auth::user()->roleMenuView('Proposal',Auth::user()->id) == 1)
                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Quotation/view/'.$item->id) }}">View</a></li>
                                                                <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Dummy/Quotation/Quotation/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                            @endif
                                                            @if (@Auth::user()->roleMenuEdit('Proposal',Auth::user()->id) == 1)
                                                                <li><a class="dropdown-item py-2 rounded" onclick="Generate('{{$item->id}}')">Generate</a></li>
                                                                <li><a class="dropdown-item py-2 rounded" onclick="Cancel('{{$item->id}}')">Cancel</a></li>
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
                        <div class="tab-pane fade" id="nav-Generate" role="tabpanel" rel="0">
                            <form enctype="multipart/form-data" class="row g-3 basic-form" id="form-id2">
                                @csrf
                                <input type="hidden" name="category" value="prename">
                            <table class="myTableProposalRequest5 table table-hover align-middle mb-0" >
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ID</th>
                                        <th>Company</th>
                                        <th>Issue Date</th>
                                        <th>Expiration Date</th>
                                        <th class="text-center">Special Discount</th>
                                        <th class="text-center">Approve By</th>
                                        <th class="text-center">Document status</th>
                                        <th class="text-center">Order</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($Generate))
                                        @foreach ($Generate as $key => $item)
                                        <tr>
                                            <td style="text-align: center;">
                                                @if ($item->status_document == 1)
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input checkbox-select checkbox-{{$key + 1}}" type="checkbox" name="checkbox[]" value="{{ $item->id }}" id="checkbox-{{$key + 1}}" rel="{{ $item->vat }}">
                                                        <label class="form-check-label" for="checkbox-{{$key + 1}}"></label>
                                                    </div>
                                                @else
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input checkbox-select checkbox-{{$key + 1}}" type="checkbox" name="checkbox[]" value="{{ $item->id }}" id="checkbox-{{$key + 1}}" rel="{{ $item->vat }}" disabled>
                                                        <label class="form-check-label" for="checkbox-{{$key + 1}}"></label>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $item->DummyNo}}</td>
                                            <td>{{ @$item->company->Company_Name}}</td>
                                            <td>{{ $item->issue_date }}</td>
                                            <td>{{ $item->Expirationdate }}</td>
                                            <td style="text-align: center;">
                                                @if ($item->SpecialDiscount == 0)
                                                    -
                                                @else
                                                    <i class="bi bi-check-lg text-green" ></i>
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                @if (@$item->userConfirm->name == null)
                                                    -
                                                @else
                                                    {{ @$item->userConfirm->name }}
                                                @endif
                                            </td>
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
                                                    <span class="badge rounded-pill "style="background-color: #0ea5e9">Generate</span>
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                    <ul class="dropdown-menu border-0 shadow p-3">
                                                        @if (@Auth::user()->roleMenuView('Proposal',Auth::user()->id) == 1)
                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Quotation/view/'.$item->id) }}">View</a></li>
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
                        <div class="tab-pane fade" id="nav-Reject" role="tabpanel" rel="0">
                            <form enctype="multipart/form-data" class="row g-3 basic-form" id="form-id2">
                                @csrf
                                <input type="hidden" name="category" value="prename">
                                <table class="myTableProposalRequest6 table table-hover align-middle mb-0" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>ID</th>
                                            <th>Company</th>
                                            <th>Issue Date</th>
                                            <th>Expiration Date</th>
                                            <th class="text-center">Special Discount</th>
                                            <th class="text-center">Approve By</th>
                                            <th class="text-center">Document status</th>
                                            <th class="text-center">Order</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($Reject))
                                            @foreach ($Reject as $key => $item)
                                            <tr>
                                                <td style="text-align: center;">
                                                    @if ($item->status_document == 1)
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input checkbox-select checkbox-{{$key + 1}}" type="checkbox" name="checkbox[]" value="{{ $item->id }}" id="checkbox-{{$key + 1}}" rel="{{ $item->vat }}">
                                                            <label class="form-check-label" for="checkbox-{{$key + 1}}"></label>
                                                        </div>
                                                    @else
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input checkbox-select checkbox-{{$key + 1}}" type="checkbox" name="checkbox[]" value="{{ $item->id }}" id="checkbox-{{$key + 1}}" rel="{{ $item->vat }}" disabled>
                                                            <label class="form-check-label" for="checkbox-{{$key + 1}}"></label>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>{{ $item->DummyNo}}</td>
                                                <td>{{ @$item->company->Company_Name}}</td>
                                                <td>{{ $item->issue_date }}</td>
                                                <td>{{ $item->Expirationdate }}</td>
                                                <td style="text-align: center;">
                                                    @if ($item->SpecialDiscount == 0)
                                                        -
                                                    @else
                                                        <i class="bi bi-check-lg text-green" ></i>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    @if (@$item->userConfirm->name == null)
                                                        -
                                                    @else
                                                        {{ @$item->userConfirm->name }}
                                                    @endif
                                                </td>
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
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            @if (@Auth::user()->roleMenuView('Proposal',Auth::user()->id) == 1)
                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Quotation/view/'.$item->id) }}">View</a></li>
                                                                <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Dummy/Quotation/Quotation/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                            @endif
                                                            @if (@Auth::user()->roleMenuEdit('Proposal',Auth::user()->id) == 1)
                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Quotation/edit/'.$item->id) }}">Edit</a></li>
                                                                <li><a class="dropdown-item py-2 rounded" onclick="Cancel('{{$item->id}}')">Cancel</a></li>
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
                        <div class="tab-pane fade" id="nav-Cancel" role="tabpanel" rel="0">
                            <form enctype="multipart/form-data" class="row g-3 basic-form" id="form-id2">
                                @csrf
                                <input type="hidden" name="category" value="prename">
                                <table class="myTableProposalRequest7 table table-hover align-middle mb-0" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>ID</th>
                                            <th>Company</th>
                                            <th>Issue Date</th>
                                            <th>Expiration Date</th>
                                            <th class="text-center">Special Discount</th>
                                            <th class="text-center">Approve By</th>
                                            <th class="text-center">Document status</th>
                                            <th class="text-center">Order</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($cancel))
                                            @foreach ($cancel as $key => $item)
                                            <tr>
                                                <td style="text-align: center;">
                                                    @if ($item->status_document == 1)
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input checkbox-select checkbox-{{$key + 1}}" type="checkbox" name="checkbox[]" value="{{ $item->id }}" id="checkbox-{{$key + 1}}" rel="{{ $item->vat }}">
                                                            <label class="form-check-label" for="checkbox-{{$key + 1}}"></label>
                                                        </div>
                                                    @else
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input checkbox-select checkbox-{{$key + 1}}" type="checkbox" name="checkbox[]" value="{{ $item->id }}" id="checkbox-{{$key + 1}}" rel="{{ $item->vat }}" disabled>
                                                            <label class="form-check-label" for="checkbox-{{$key + 1}}"></label>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>{{ $item->DummyNo}}<input type="hidden" name="id" id="id" value="{{$item->id}}"></td>
                                                <td>{{ @$item->company->Company_Name}}</td>
                                                <td>{{ $item->issue_date }}</td>
                                                <td>{{ $item->Expirationdate }}</td>
                                                <td style="text-align: center;">
                                                    @if ($item->SpecialDiscount == 0)
                                                        -
                                                    @else
                                                        <i class="bi bi-check-lg text-green" ></i>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    @if (@$item->userConfirm->name == null)
                                                        -
                                                    @else
                                                        {{ @$item->userConfirm->name }}
                                                    @endif
                                                </td>
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
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            @if (@Auth::user()->roleMenuView('Proposal',Auth::user()->id) == 1)
                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Quotation/view/'.$item->id) }}">View</a></li>
                                                                <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Dummy/Quotation/Quotation/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                            @endif
                                                            <li><a class="dropdown-item py-2 rounded" onclick="Cancel('{{$item->id}}')">Revice</a></li>
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
    document.getElementById('Submit_Documents').addEventListener('click', function() {
        // Select all checked checkboxes
        const checkedCheckboxes = document.querySelectorAll('.form-check-input:checked');

        // Get all the IDs of checked checkboxes
        const ids = Array.from(checkedCheckboxes).map(checkbox => checkbox.value);

        if (ids.length > 0) {
            // Create query string from ids array
            const queryString = new URLSearchParams({ ids: ids }).toString();
            const url = `{{ route('DummyQuotation.senddocuments') }}?${queryString}`;

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
        var status = $('#nav-Pending').attr('rel');

        if (status == 0) {
            document.getElementById("nav-Pending").setAttribute("rel", "1");
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
        var status = $('#nav-Awaiting').attr('rel');

        if (status == 0) {
            document.getElementById("nav-Awaiting").setAttribute("rel", "1");
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
        var status = $('#nav-Approved').attr('rel');

        if (status == 0) {
            document.getElementById("nav-Approved").setAttribute("rel", "1");
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
    $('#nav5').on('click', function () {
        var status = $('#nav-Generate').attr('rel');

        if (status == 0) {
            document.getElementById("nav-Generate").setAttribute("rel", "1");
            $('.myTableProposalRequest5').addClass('nowrap').dataTable({
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
    $('#nav6').on('click', function () {
        var status = $('#nav-Reject').attr('rel');

        if (status == 0) {
            document.getElementById("nav-Reject").setAttribute("rel", "1");
            $('.myTableProposalRequest6').addClass('nowrap').dataTable({
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
    $('#nav7').on('click', function () {
        var status = $('#nav-Cancel').attr('rel');

        if (status == 0) {
            document.getElementById("nav-Cancel").setAttribute("rel", "1");
            $('.myTableProposalRequest7').addClass('nowrap').dataTable({
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
    function Generate(id){
        console.log(id);
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
    function Cancel(id){
        Swal.fire({
        title: "คุณต้องการปิดการใช้งานรายการนี้ใช่หรือไม่?",
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
