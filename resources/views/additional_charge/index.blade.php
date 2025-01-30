@extends('layouts.masterLayout')
@php
    $excludeDatatable = false;
@endphp
@section('content')
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Additional Charge</div>
                </div>
                <div class="col-auto">
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="PrenameModalCenterTitle">หมายเหตุ (Remark)</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="col-12">
                                        <div class="card-body">
                                            <form action="{{ url('/Document/Additional/Charge/Cancel/') }}" method="POST" enctype="multipart/form-data" class="row g-3 basic-form">
                                                @csrf
                                                <textarea name="note" id="not" class="form-control mt-2" cols="30" rows="5" style="resize: none; overflow: hidden;" oninput="autoResize(this)"></textarea>
                                                <script>
                                                    function autoResize(textarea) {
                                                        textarea.style.height = 'auto'; // รีเซ็ตความสูง
                                                        textarea.style.height = textarea.scrollHeight + 'px'; // กำหนดความสูงตามเนื้อหา
                                                    }
                                                </script>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary lift" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-color-green lift" id="btn-save">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div><!-- Form Validation -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- .row end -->
        </div>
    </div>
    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
            <div class="row align-items-center mb-2" >
                @if (session("success"))
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">Save successful.</h4>
                    <hr>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
                @endif
                @if (session("error"))
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Save failed!</h4>
                        <hr>
                        <p class="mb-0">{{ session('error') }}</p>
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

                </div>
            </div> <!-- Row end  -->
        </div> <!-- Row end  -->
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-sm-12 col-12">
                    <ul class="nav nav-tabs px-3 border-bottom-0" role="tablist">
                        <li class="nav-item" id="nav1"><a class="nav-link active" data-bs-toggle="tab" href="#nav-Dummy" role="tab" onclick="nav($id='nav1')"><span class="badge" style="background-color:#64748b">{{$Proposalcount}}</span> Proposal</a></li>{{--ประวัติการแก้ไข--}}
                        <li class="nav-item" id="nav3"><a class="nav-link" data-bs-toggle="tab" href="#nav-Awaiting" onclick="nav($id='nav3')" role="tab"><span class="badge bg-warning" >{{$Awaitingcount}}</span> Awaiting Approval</a></li>{{--เอกสารออกบิล--}}
                        <li class="nav-item" id="nav4"><a class="nav-link " data-bs-toggle="tab" href="#nav-Approved" onclick="nav($id='nav4')" role="tab"><span class="badge bg-success" >{{$Approvedcount}}</span> Approved</a></li>{{--Doc. number--}}
                        <li class="nav-item" id="nav5"><a class="nav-link " data-bs-toggle="tab" href="#nav-Reject" onclick="nav($id='nav5')" role="tab"><span class="badge "style="background-color:#1d4ed8" >{{$Rejectcount}}</span> Reject</a></li>{{--ชื่อ คนแนะนำ ครั้งต่อครั้ง ต่อ เอกสาร--}}
                        <li class="nav-item" id="nav6"><a class="nav-link" data-bs-toggle="tab" href="#nav-Cancel" onclick="nav($id='nav6')" role="tab"><span class="badge bg-danger" >{{$Cancelcount}}</span> Cancel</a></li>{{--% (Percentage) ครั้งต่อครั้ง ต่อ เอกสาร--}}
                        <li class="nav-item" id="nav7"><a class="nav-link" data-bs-toggle="tab" href="#nav-Complete" onclick="nav($id='nav7')" role="tab"><span class="badge "style="background-color:#2C7F7A" >{{$Completecount}}</span> Complete</a></li>
                    </ul>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade  show active" id="nav-Dummy" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" class="mt-2">

                                        <table id="AdditionalTable" class="table-together table-style">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"data-priority="1">No</th>
                                                    <th class="text-center" data-priority="1">Proposal ID</th>
                                                    <th data-priority="1">Company / Individual</th>
                                                    <th class="text-center">AD Doc.</th>
                                                    <th class="text-center">PD Amount.</th>
                                                    <th class="text-center">AD Amount.</th>
                                                    <th class="text-center">Total Amount</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($Proposal))
                                                    @foreach ($Proposal as $key => $item)
                                                    <tr>
                                                        <td style="text-align: center;">
                                                            {{$key +1}}
                                                        </td>
                                                        <td>{{ $item->Quotation_ID }}</td>
                                                        @if ($item->type_Proposal == 'Company')
                                                            <td>{{ @$item->company->Company_Name}}</td>
                                                        @else
                                                            <td>{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                        @endif
                                                        <td>{{ $item->ADD_count }}</td>
                                                        <td style="text-align: center;">{{ number_format($item->Nettotal) }}</td>

                                                        <td style="text-align: center;">{{ number_format($item->ADD_amount) }}</td>
                                                        <td >{{ number_format($item->Nettotal + $item->ADD_amount) }}</td>

                                                        @php
                                                            $CreateBy = Auth::user()->id;
                                                            $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);

                                                        @endphp
                                                        <td style="text-align: center;">
                                                            @if ($rolePermission == 1 ||$rolePermission == 2)
                                                                @if ($item->Operated_by == $CreateBy)
                                                                    <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ url('/Document/Additional/Charge/create/'.$item->id) }}'">
                                                                        Select
                                                                    </button>
                                                                @else
                                                                    <button type="button" class="btn btn-color-green lift btn_modal" disabled>
                                                                        Select
                                                                    </button>
                                                                @endif
                                                            @elseif ($rolePermission == 3)
                                                                <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ url('/Document/Additional/Charge/create/'.$item->id) }}'">
                                                                    Select
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                                <div class="tab-pane fade "id="nav-Awaiting" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" class="mt-2">

                                        <table id="proposalAwaitingTable" class="table-together table-style">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"data-priority="1">No</th>
                                                    <th class="text-center" data-priority="1">Additional ID</th>
                                                    <th class="text-center" data-priority="1">Proposal ID</th>
                                                    <th data-priority="1">Company / Individual</th>
                                                    <th class="text-center">Issue Date</th>
                                                    <th class="text-center">Expiration Date</th>
                                                    <th class="text-center">Amount</th>
                                                    <th class="text-center">Operated By</th>
                                                    <th class="text-center">Document Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($Awaiting))
                                                    @foreach ($Awaiting as $key => $item)
                                                    <tr>
                                                        <td style="text-align: center;">
                                                            {{$key +1}}
                                                        </td>
                                                        <td style="text-align: center;">{{ $item->Additional_ID }}</td>
                                                        <td style="text-align: center;">{{ $item->Quotation_ID }}</td>
                                                        @if ($item->type_Proposal == 'Company')
                                                            <td style="text-align: left;">{{ @$item->company->Company_Name}}</td>
                                                        @else
                                                            <td style="text-align: left;">{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                        @endif
                                                        <td>{{ $item->issue_date }}</td>
                                                        <td style="text-align: center;">{{ $item->Expirationdate }}</td>
                                                        <td style="text-align: center;">   {{ number_format($item->Nettotal, 2) }}</td>
                                                        <td >{{ @$item->userOperated->name }}</td>
                                                        <td style="text-align: center;">
                                                            <span class="badge rounded-pill bg-warning">Awaiting Approval</span>
                                                        </td>
                                                        @php
                                                            $CreateBy = Auth::user()->id;
                                                            $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                            $canViewProposal = @Auth::user()->roleMenuView('Additional', Auth::user()->id);
                                                            $canEditProposal = @Auth::user()->roleMenuEdit('Additional', Auth::user()->id);
                                                        @endphp
                                                        <td style="text-align: center;">
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                                <ul class="dropdown-menu border-0 shadow p-3">
                                                                    @if ($canViewProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/Additional/Charge/view/'.$item->id) }}">View</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/Additional/Charge/log/'.$item->id) }}">LOG</a></li>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-Approved" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" class="mt-2">

                                        <table id="ApprovedTable" class="table-together table-style">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"data-priority="1">No</th>
                                                    <th class="text-center" data-priority="1">Additional ID</th>
                                                    <th class="text-center" data-priority="1">Proposal ID</th>
                                                    <th data-priority="1">Company / Individual</th>
                                                    <th class="text-center">Issue Date</th>
                                                    <th class="text-center">Expiration Date</th>
                                                    <th class="text-center">Amount</th>
                                                    <th class="text-center">Operated By</th>
                                                    <th class="text-center">Document Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($Approved))
                                                    @foreach ($Approved as $key => $item)
                                                    <tr>
                                                        <td style="text-align: center;">
                                                            {{$key +1}}
                                                        </td>
                                                        <td style="text-align: center;">{{ $item->Additional_ID }}</td>
                                                        <td style="text-align: center;">{{ $item->Quotation_ID }}</td>
                                                        @if ($item->type_Proposal == 'Company')
                                                            <td style="text-align: left;">{{ @$item->company->Company_Name}}</td>
                                                        @else
                                                            <td style="text-align: left;">{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                        @endif
                                                        <td>{{ $item->issue_date }}</td>
                                                        <td style="text-align: center;">{{ $item->Expirationdate }}</td>
                                                        <td style="text-align: center;">   {{ number_format($item->Nettotal, 2) }}</td>
                                                        <td >{{ @$item->userOperated->name }}</td>
                                                        <td style="text-align: center;">
                                                            <span class="badge rounded-pill bg-success">Approved</span>
                                                        </td>
                                                        @php
                                                            $CreateBy = Auth::user()->id;
                                                            $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                            $canViewProposal = @Auth::user()->roleMenuView('Additional', Auth::user()->id);
                                                            $canEditProposal = @Auth::user()->roleMenuEdit('Additional', Auth::user()->id);
                                                        @endphp
                                                        <td style="text-align: center;">
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                                <ul class="dropdown-menu border-0 shadow p-3">
                                                                    @if ($canViewProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/Additional/Charge/view/'.$item->id) }}">View</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Document/Additional/Charge/document/PDF/'.$item->id) }}">Export</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/Additional/Charge/log/'.$item->id) }}">LOG</a></li>
                                                                    @endif
                                                                    @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                        @if ($canEditProposal == 1)
                                                                            @if ($item->status_document !== 2)
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/Additional/Charge/edit/'.$item->id) }}">Edit</a></li>
                                                                                <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                            @endif
                                                                        @endif
                                                                    @elseif ($rolePermission == 2)
                                                                        @if ($item->Operated_by == $CreateBy)
                                                                            @if ($canEditProposal == 1)
                                                                                @if ($item->status_document !== 2)
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/Additional/Charge/edit/'.$item->id) }}">Edit</a></li>
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @elseif ($rolePermission == 3)
                                                                        @if ($canEditProposal == 1)
                                                                            @if ($item->status_document !== 2)
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/Additional/Charge/edit/'.$item->id) }}">Edit</a></li>
                                                                                <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                            @endif
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

                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-Reject" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" class="mt-2">

                                        <table id="proposalRejectTable" class="table-together table-style">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"data-priority="1">No</th>
                                                    <th class="text-center" data-priority="1">Additional ID</th>
                                                    <th class="text-center" data-priority="1">Proposal ID</th>
                                                    <th data-priority="1">Company / Individual</th>
                                                    <th class="text-center">Issue Date</th>
                                                    <th class="text-center">Expiration Date</th>
                                                    <th class="text-center">Amount</th>
                                                    <th class="text-center">Operated By</th>
                                                    <th class="text-center">Document Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($Reject))
                                                    @foreach ($Reject as $key => $item)
                                                    <tr>
                                                        <td style="text-align: center;">
                                                            {{$key +1}}
                                                        </td>
                                                        <td style="text-align: center;">{{ $item->Additional_ID }}</td>
                                                        <td style="text-align: center;">{{ $item->Quotation_ID }}</td>
                                                        @if ($item->type_Proposal == 'Company')
                                                            <td style="text-align: left;">{{ @$item->company->Company_Name}}</td>
                                                        @else
                                                            <td style="text-align: left;">{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                        @endif
                                                        <td>{{ $item->issue_date }}</td>
                                                        <td style="text-align: center;">{{ $item->Expirationdate }}</td>
                                                        <td style="text-align: center;">   {{ number_format($item->Nettotal, 2) }}</td>
                                                        <td >{{ @$item->userOperated->name }}</td>
                                                        <td style="text-align: center;">
                                                            <span class="badge rounded-pill "style="background-color:#1d4ed8">Reject</span>
                                                        </td>
                                                        @php
                                                            $CreateBy = Auth::user()->id;
                                                            $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                            $canViewProposal = @Auth::user()->roleMenuView('Additional', Auth::user()->id);
                                                            $canEditProposal = @Auth::user()->roleMenuEdit('Additional', Auth::user()->id);
                                                        @endphp
                                                        <td style="text-align: center;">
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                                <ul class="dropdown-menu border-0 shadow p-3">
                                                                    @if ($canViewProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/Additional/Charge/view/'.$item->id) }}">View</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/Additional/Charge/log/'.$item->id) }}">LOG</a></li>
                                                                    @endif
                                                                    @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                        @if ($canEditProposal == 1)
                                                                            @if ($item->status_document !== 2)
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/Additional/Charge/edit/'.$item->id) }}">Edit</a></li>
                                                                                <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                            @endif
                                                                        @endif
                                                                    @elseif ($rolePermission == 2)
                                                                        @if ($item->Operated_by == $CreateBy)
                                                                            @if ($canEditProposal == 1)
                                                                                @if ($item->status_document !== 2)
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/Additional/Charge/edit/'.$item->id) }}">Edit</a></li>
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @elseif ($rolePermission == 3)
                                                                        @if ($canEditProposal == 1)
                                                                            @if ($item->status_document !== 2)
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/Additional/Charge/edit/'.$item->id) }}">Edit</a></li>
                                                                                <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                            @endif
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

                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-Cancel" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" class="mt-2">

                                        <table id="proposalCancelTable" class="table-together table-style">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"data-priority="1">No</th>
                                                    <th class="text-center" data-priority="1">Additional ID</th>
                                                    <th class="text-center" data-priority="1">Proposal ID</th>
                                                    <th data-priority="1">Company / Individual</th>
                                                    <th class="text-center">Issue Date</th>
                                                    <th class="text-center">Expiration Date</th>
                                                    <th class="text-center">Amount</th>
                                                    <th class="text-center">Operated By</th>
                                                    <th class="text-center">Document Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($Cancel))
                                                    @foreach ($Cancel as $key => $item)
                                                        <tr>
                                                            <td style="text-align: center;">
                                                                {{$key +1}}
                                                            </td>
                                                            <td style="text-align: center;">{{ $item->Additional_ID }}</td>
                                                            <td style="text-align: center;">{{ $item->Quotation_ID }}</td>
                                                            @if ($item->type_Proposal == 'Company')
                                                                <td style="text-align: left;">{{ @$item->company->Company_Name}}</td>
                                                            @else
                                                                <td style="text-align: left;">{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                            @endif
                                                            <td>{{ $item->issue_date }}</td>
                                                            <td style="text-align: center;">{{ $item->Expirationdate }}</td>
                                                            <td style="text-align: center;">   {{ number_format($item->Nettotal, 2) }}</td>
                                                            <td >{{ @$item->userOperated->name }}</td>
                                                            <td style="text-align: center;">
                                                                <span class="badge rounded-pill bg-danger">Cancel</span>
                                                            </td>
                                                            @php
                                                                $CreateBy = Auth::user()->id;
                                                                $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                                $canViewProposal = @Auth::user()->roleMenuView('Additional', Auth::user()->id);
                                                                $canEditProposal = @Auth::user()->roleMenuEdit('Additional', Auth::user()->id);
                                                            @endphp
                                                            <td style="text-align: center;">
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                                    <ul class="dropdown-menu border-0 shadow p-3">
                                                                        @if ($canViewProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/Additional/Charge/view/'.$item->id) }}">View</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/Additional/Charge/log/'.$item->id) }}">LOG</a></li>
                                                                        @endif
                                                                        @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                            @if ($canEditProposal == 1)
                                                                                @if ($item->status_document !== 2)
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/Additional/Charge/edit/'.$item->id) }}">Edit</a></li>
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Revice({{ $item->id }})">Revice</a></li>
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Delete({{ $item->id }})">Delete</a></li>
                                                                                @endif
                                                                            @endif
                                                                        @elseif ($rolePermission == 2)
                                                                            @if ($item->Operated_by == $CreateBy)
                                                                                @if ($canEditProposal == 1)
                                                                                    @if ($item->status_document !== 2)
                                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/Additional/Charge/edit/'.$item->id) }}">Edit</a></li>
                                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Revice({{ $item->id }})">Revice</a></li>
                                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Delete({{ $item->id }})">Delete</a></li>
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                        @elseif ($rolePermission == 3)
                                                                            @if ($canEditProposal == 1)
                                                                                @if ($item->status_document !== 2)
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/Additional/Charge/edit/'.$item->id) }}">Edit</a></li>
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Revice({{ $item->id }})">Revice</a></li>
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Delete({{ $item->id }})">Delete</a></li>
                                                                                @endif
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

                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-Complete" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" class="mt-2">

                                        <table id="proposalApprovedTable" class="table-together table-style">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"data-priority="1">No</th>
                                                    <th class="text-center" data-priority="1">Additional ID</th>
                                                    <th class="text-center" data-priority="1">Proposal ID</th>
                                                    <th data-priority="1">Company / Individual</th>
                                                    <th class="text-center">Issue Date</th>
                                                    <th class="text-center">Expiration Date</th>
                                                    <th class="text-center">Amount</th>
                                                    <th class="text-center">Operated By</th>
                                                    <th class="text-center">Document Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($Complete))
                                                    @foreach ($Complete as $key => $item)
                                                    <tr>
                                                        <td style="text-align: center;">
                                                            {{$key +1}}
                                                        </td>
                                                        <td style="text-align: center;">{{ $item->Additional_ID }}</td>
                                                        <td style="text-align: center;">{{ $item->Quotation_ID }}</td>
                                                        @if ($item->type_Proposal == 'Company')
                                                            <td style="text-align: left;">{{ @$item->company->Company_Name}}</td>
                                                        @else
                                                            <td style="text-align: left;">{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                        @endif
                                                        <td>{{ $item->issue_date }}</td>
                                                        <td style="text-align: center;">{{ $item->Expirationdate }}</td>
                                                        <td style="text-align: center;">   {{ number_format($item->Nettotal, 2) }}</td>
                                                        <td >{{ @$item->userOperated->name }}</td>
                                                        <td style="text-align: center;">
                                                            <span class="badge rounded-pill " style="background-color: #2C7F7A">Complete</span>
                                                        </td>
                                                        @php
                                                            $CreateBy = Auth::user()->id;
                                                            $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                            $canViewProposal = @Auth::user()->roleMenuView('Additional', Auth::user()->id);
                                                            $canEditProposal = @Auth::user()->roleMenuEdit('Additional', Auth::user()->id);
                                                        @endphp
                                                        <td style="text-align: center;">
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                                <ul class="dropdown-menu border-0 shadow p-3">
                                                                    @if ($canViewProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/Additional/Charge/view/'.$item->id) }}">View</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Document/Additional/Charge/document/PDF/'.$item->id) }}">Export</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/Additional/Charge/log/'.$item->id) }}">LOG</a></li>
                                                                    @endif

                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- dataTable -->
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.semanticui.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.semanticui.js"></script>
    <script src="{{ asset('assets/js/table-together.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/daterangepicker.min.js')}}" defer></script>
    <script type="text/javascript" src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css')}}" />
    <script>
        function nav(id) {
            $.fn.dataTable
            .tables({ visible: true, api: true })
            .columns.adjust()
            .responsive.recalc();
        }
        function Cancel(id){
            Swal.fire({
            title: "คุณต้องการปิดการใช้งานใบข้อเสนอนี้ใช่หรือไม่?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "ตกลง",
            cancelButtonText: "ยกเลิก",
            confirmButtonColor: "#28a745",
            dangerMode: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // อัปเดต URL ของฟอร์มในโมดอล
                    const form = document.querySelector('#myModal form');
                    form.action = `{{ url('/Document/Additional/Charge/Cancel/') }}/${id}`;
                    $('#myModal').modal('show'); // เปิดโมดอล
                }
            });
        }
        function Delete(id){
            Swal.fire({
            title: "คุณต้องลบใบข้อเสนอ(เพิ่มเติม)นี้ใช่หรือไม่?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "ตกลง",
            cancelButtonText: "ยกเลิก",
            confirmButtonColor: "#28a745",
            dangerMode: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ url('/Document/Additional/Charge/Delete/') }}/" + id;
                }
            });
        }
        function Revice(id){
            Swal.fire({
            title: "คุณต้องการเปิดการใช้งานใบข้อเสนอ(เพิ่มเติม)นี้ใช่หรือไม่?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "ตกลง",
            cancelButtonText: "ยกเลิก",
            confirmButtonColor: "#28a745",
            dangerMode: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ url('/Document/Additional/Charge/Revice/') }}/" + id;
                }
            });
        }
    </script>
@endsection
