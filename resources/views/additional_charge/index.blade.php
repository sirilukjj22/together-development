@extends('layouts.masterLayout')

@section('content')
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Additional</div>
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
                    <h4 class="alert-heading">บันทึกสำเร็จ!</h4>
                    <hr>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
                @endif
                @if (session("error"))
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">บันทึกไม่สำเร็จ!</h4>
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
                        <li class="nav-item" id="nav1"><a class="nav-link active" data-bs-toggle="tab" href="#nav-Dummy" role="tab" onclick="nav($id='nav1')"><span class="badge" style="background-color:#64748b">{{$Proposalcount}}</span> Additional</a></li>{{--ประวัติการแก้ไข--}}
                        <li class="nav-item" id="nav3"><a class="nav-link" data-bs-toggle="tab" href="#nav-Awaiting" onclick="nav($id='nav3')" role="tab"><span class="badge bg-warning" >{{$Awaitingcount}}</span> Awaiting Approval</a></li>{{--เอกสารออกบิล--}}
                        <li class="nav-item" id="nav4"><a class="nav-link " data-bs-toggle="tab" href="#nav-Approved" onclick="nav($id='nav4')" role="tab"><span class="badge bg-success" >{{$Approvedcount}}</span> Approved</a></li>{{--Doc. number--}}
                        <li class="nav-item" id="nav5"><a class="nav-link " data-bs-toggle="tab" href="#nav-Reject" onclick="nav($id='nav5')" role="tab"><span class="badge "style="background-color:#1d4ed8" >{{$Rejectcount}}</span> Reject</a></li>{{--ชื่อ คนแนะนำ ครั้งต่อครั้ง ต่อ เอกสาร--}}
                        <li class="nav-item" id="nav6"><a class="nav-link" data-bs-toggle="tab" href="#nav-Cancel" onclick="nav($id='nav6')" role="tab"><span class="badge bg-danger" >{{$Cancelcount}}</span> Cancel</a></li>{{--% (Percentage) ครั้งต่อครั้ง ต่อ เอกสาร--}}
                    </ul>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade  show active" id="nav-Dummy" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" class="mt-2">
                                        <caption class="caption-top">
                                            <div class="flex-end-g2">
                                                <label class="entriespage-label">entries per page :</label>
                                                <select class="entriespage-button" id="search-per-page-Additional" onchange="getPage(1, this.value, 'Additional')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                    <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "Additional" ? 'selected' : '' }}>10</option>
                                                    <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "Additional" ? 'selected' : '' }}>25</option>
                                                    <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "Additional" ? 'selected' : '' }}>50</option>
                                                    <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "Additional" ? 'selected' : '' }}>100</option>
                                                </select>
                                                <input class="search-button search-data" id="Additional" style="text-align:left;" placeholder="Search" />
                                            </div>
                                        </caption>
                                        <table id="AdditionalTable" class="example ui striped table nowrap unstackable hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"data-priority="1">No</th>
                                                    <th class="text-center" data-priority="1">Proposal ID</th>
                                                    <th data-priority="1">Company / Individual</th>
                                                    <th class="text-center">Issue Date</th>
                                                    <th class="text-center">Day Type</th>
                                                    <th class="text-center">Check In</th>
                                                    <th class="text-center">Check Out</th>
                                                    <th class="text-center">Expiration Date</th>
                                                    <th class="text-center">Operated By</th>
                                                    <th class="text-center">Document Status</th>
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
                                                        <td>{{ $item->issue_date }}</td>
                                                        <td style="text-align: center;">{{$item->Date_type ?? 'No Check in Date'}}</td>
                                                        @if ($item->checkin)
                                                        <td style="text-align: center;">{{ $item->checkin}}</td>
                                                        <td style="text-align: center;">{{ $item->checkout }}</td>
                                                        @else
                                                        <td style="text-align: center;">-</td>
                                                        <td style="text-align: center;">-</td>
                                                        @endif
                                                        <td style="text-align: center;">{{ $item->Expirationdate }}</td>
                                                        <td >{{ @$item->userOperated->name }}</td>
                                                        <td style="text-align: center;">
                                                            <span class="badge rounded-pill bg-success">Approved</span>
                                                        </td>
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
                                        <input type="hidden" id="get-total-Additional" value="{{ $Proposal->total() }}">
                                        <input type="hidden" id="currentPage-Additional" value="1">
                                        <caption class="caption-bottom">
                                            <div class="md-flex-bt-i-c">
                                                <p class="py2" id="Additional-showingEntries">{{ showingEntriesTable($Proposal, 'Additional') }}</p>
                                                    <div id="Additional-paginate">
                                                        {!! paginateTable($Proposal, 'Additional') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                                    </div>
                                            </div>
                                        </caption>
                                    </div>
                                </div>
                                <div class="tab-pane fade "id="nav-Awaiting" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" class="mt-2">
                                        <caption class="caption-top">
                                            <div class="flex-end-g2">
                                                <label class="entriespage-label">entries per page :</label>
                                                <select class="entriespage-button" id="search-per-page-proposalAwaiting" onchange="getPageAwaiting(1, this.value, 'proposalAwaiting')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                    <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "proposalAwaiting" ? 'selected' : '' }}>10</option>
                                                    <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "proposalAwaiting" ? 'selected' : '' }}>25</option>
                                                    <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "proposalAwaiting" ? 'selected' : '' }}>50</option>
                                                    <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "proposalAwaiting" ? 'selected' : '' }}>100</option>
                                                </select>
                                                <input class="search-button search-data-Awaiting" id="proposalAwaiting" style="text-align:left;" placeholder="Search" />
                                            </div>
                                        </caption>
                                        <table id="proposalAwaitingTable" class="example ui striped table nowrap unstackable hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"data-priority="1">No</th>
                                                    <th class="text-center" data-priority="1">Additional ID</th>
                                                    <th class="text-center" data-priority="1">Proposal ID</th>
                                                    <th data-priority="1">Company / Individual</th>
                                                    <th class="text-center">Issue Date</th>
                                                    <th class="text-center">Day Type</th>
                                                    <th class="text-center">Check In</th>
                                                    <th class="text-center">Check Out</th>
                                                    <th class="text-center">Expiration Date</th>
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
                                                            <td>{{ @$item->company->Company_Name}}</td>
                                                        @else
                                                            <td>{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                        @endif
                                                        <td>{{ $item->issue_date }}</td>
                                                        <td style="text-align: center;">{{ $item->Date_type ?? 'No Check In Date' }}</td>
                                                        @if ($item->checkin)
                                                        <td style="text-align: center;">{{ $item->checkin}}</td>
                                                        <td style="text-align: center;">{{ $item->checkout }}</td>
                                                        @else
                                                        <td style="text-align: center;">-</td>
                                                        <td style="text-align: center;">-</td>
                                                        @endif
                                                        <td style="text-align: center;">{{ $item->Expirationdate }}</td>
                                                        <td >{{ @$item->userOperated->name }}</td>
                                                        <td style="text-align: center;">
                                                            <span class="badge rounded-pill bg-warning">Awaiting Approval</span>
                                                        </td>
                                                        @php
                                                            $CreateBy = Auth::user()->id;
                                                            $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                            $canViewProposal = @Auth::user()->roleMenuView('Billing Folio', Auth::user()->id);
                                                            $canEditProposal = @Auth::user()->roleMenuEdit('Billing Folio', Auth::user()->id);
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
                                        <input type="hidden" id="get-total-proposalAwaiting" value="{{ $Awaiting->total() }}">
                                        <input type="hidden" id="currentPage-proposalAwaiting" value="1">
                                        <caption class="caption-bottom">
                                            <div class="md-flex-bt-i-c">
                                                <p class="py2" id="proposalAwaiting-showingEntries">{{ showingEntriesTableAwaiting($Awaiting, 'proposalAwaiting') }}</p>
                                                    <div id="proposalAwaiting-paginate">
                                                        {!! paginateTableAwaiting($Awaiting, 'proposalAwaiting') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                                    </div>
                                            </div>
                                        </caption>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-Approved" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" class="mt-2">
                                        <caption class="caption-top">
                                            <div class="flex-end-g2">
                                                <label class="entriespage-label">entries per page :</label>
                                                <select class="entriespage-button" id="search-per-page-proposalApproved" onchange="getPageApproved(1, this.value, 'proposalApproved')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                    <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "proposalApproved" ? 'selected' : '' }}>10</option>
                                                    <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "proposalApproved" ? 'selected' : '' }}>25</option>
                                                    <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "proposalApproved" ? 'selected' : '' }}>50</option>
                                                    <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "proposalApproved" ? 'selected' : '' }}>100</option>
                                                </select>
                                                <input class="search-button search-data-Approved" id="proposalApproved" style="text-align:left;" placeholder="Search" />
                                            </div>
                                        </caption>
                                        <table id="proposalApprovedTable" class="example ui striped table nowrap unstackable hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"data-priority="1">No</th>
                                                    <th class="text-center" data-priority="1">Additional ID</th>
                                                    <th class="text-center" data-priority="1">Proposal ID</th>
                                                    <th data-priority="1">Company / Individual</th>
                                                    <th class="text-center">Issue Date</th>
                                                    <th class="text-center">Day Type</th>
                                                    <th class="text-center">Check In</th>
                                                    <th class="text-center">Check Out</th>
                                                    <th class="text-center">Expiration Date</th>
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
                                                            <td>{{ @$item->company->Company_Name}}</td>
                                                        @else
                                                            <td>{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                        @endif
                                                        <td>{{ $item->issue_date }}</td>
                                                        <td style="text-align: center;">{{ $item->Date_type ?? 'No Check In Date' }}</td>
                                                        @if ($item->checkin)
                                                        <td style="text-align: center;">{{ $item->checkin}}</td>
                                                        <td style="text-align: center;">{{ $item->checkout }}</td>
                                                        @else
                                                        <td style="text-align: center;">-</td>
                                                        <td style="text-align: center;">-</td>
                                                        @endif
                                                        <td style="text-align: center;">{{ $item->Expirationdate }}</td>
                                                        <td >{{ @$item->userOperated->name }}</td>
                                                        <td style="text-align: center;">
                                                            <span class="badge rounded-pill bg-success">Approved</span>
                                                        </td>
                                                        @php
                                                            $CreateBy = Auth::user()->id;
                                                            $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                            $canViewProposal = @Auth::user()->roleMenuView('Billing Folio', Auth::user()->id);
                                                            $canEditProposal = @Auth::user()->roleMenuEdit('Billing Folio', Auth::user()->id);
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
                                        <input type="hidden" id="get-total-proposalApproved" value="{{ $Approved->total() }}">
                                        <input type="hidden" id="currentPage-proposalApproved" value="1">
                                        <caption class="caption-bottom">
                                            <div class="md-flex-bt-i-c">
                                                <p class="py2" id="proposalApproved-showingEntries">{{ showingEntriesTableApproved($Approved, 'proposalApproved') }}</p>
                                                    <div id="proposalApproved-paginate">
                                                        {!! paginateTableApproved($Approved, 'proposalApproved') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                                    </div>
                                            </div>
                                        </caption>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-Reject" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" class="mt-2">
                                        <caption class="caption-top">
                                            <div class="flex-end-g2">
                                                <label class="entriespage-label">entries per page :</label>
                                                <select class="entriespage-button" id="search-per-page-proposalReject" onchange="getPageReject(1, this.value, 'proposalReject')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                    <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "proposalReject" ? 'selected' : '' }}>10</option>
                                                    <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "proposalReject" ? 'selected' : '' }}>25</option>
                                                    <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "proposalReject" ? 'selected' : '' }}>50</option>
                                                    <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "proposalReject" ? 'selected' : '' }}>100</option>
                                                </select>
                                                <input class="search-button search-data-Reject" id="proposalReject" style="text-align:left;" placeholder="Search" />
                                            </div>
                                        </caption>
                                        <table id="proposalRejectTable" class="example ui striped table nowrap unstackable hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"data-priority="1">No</th>
                                                    <th class="text-center" data-priority="1">Additional ID</th>
                                                    <th class="text-center" data-priority="1">Proposal ID</th>
                                                    <th data-priority="1">Company / Individual</th>
                                                    <th class="text-center">Issue Date</th>
                                                    <th class="text-center">Day Type</th>
                                                    <th class="text-center">Check In</th>
                                                    <th class="text-center">Check Out</th>
                                                    <th class="text-center">Expiration Date</th>
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
                                                            <td>{{ @$item->company->Company_Name}}</td>
                                                        @else
                                                            <td>{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                        @endif
                                                        <td>{{ $item->issue_date }}</td>
                                                        <td style="text-align: center;">{{ $item->Date_type ?? 'No Check In Date' }}</td>
                                                        @if ($item->checkin)
                                                        <td style="text-align: center;">{{ $item->checkin}}</td>
                                                        <td style="text-align: center;">{{ $item->checkout }}</td>
                                                        @else
                                                        <td style="text-align: center;">-</td>
                                                        <td style="text-align: center;">-</td>
                                                        @endif
                                                        <td style="text-align: center;">{{ $item->Expirationdate }}</td>
                                                        <td >{{ @$item->userOperated->name }}</td>
                                                        <td style="text-align: center;">
                                                            <span class="badge rounded-pill "style="background-color:#1d4ed8">Reject</span>
                                                        </td>
                                                        @php
                                                            $CreateBy = Auth::user()->id;
                                                            $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                            $canViewProposal = @Auth::user()->roleMenuView('Billing Folio', Auth::user()->id);
                                                            $canEditProposal = @Auth::user()->roleMenuEdit('Billing Folio', Auth::user()->id);
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
                                        <input type="hidden" id="get-total-proposalReject" value="{{ $Reject->total() }}">
                                        <input type="hidden" id="currentPage-proposalReject" value="1">
                                        <caption class="caption-bottom">
                                            <div class="md-flex-bt-i-c">
                                                <p class="py2" id="proposalReject-showingEntries">{{ showingEntriesTableReject($Reject, 'proposalReject') }}</p>
                                                    <div id="proposalReject-paginate">
                                                        {!! paginateTableReject($Reject, 'proposalReject') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                                    </div>
                                            </div>
                                        </caption>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-Cancel" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" class="mt-2">
                                        <caption class="caption-top">
                                            <div class="flex-end-g2">
                                                <label class="entriespage-label">entries per page :</label>
                                                <select class="entriespage-button" id="search-per-page-proposalCancel" onchange="getPageCancel(1, this.value, 'proposalCancel')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                    <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "proposalCancel" ? 'selected' : '' }}>10</option>
                                                    <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "proposalCancel" ? 'selected' : '' }}>25</option>
                                                    <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "proposalCancel" ? 'selected' : '' }}>50</option>
                                                    <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "proposalCancel" ? 'selected' : '' }}>100</option>
                                                </select>
                                                <input class="search-button search-data-Cancel" id="proposalCancel" style="text-align:left;" placeholder="Search" />
                                            </div>
                                        </caption>
                                        <table id="proposalCancelTable" class="example ui striped table nowrap unstackable hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"data-priority="1">No</th>
                                                    <th class="text-center" data-priority="1">Additional ID</th>
                                                    <th class="text-center" data-priority="1">Proposal ID</th>
                                                    <th data-priority="1">Company / Individual</th>
                                                    <th class="text-center">Issue Date</th>
                                                    <th class="text-center">Day Type</th>
                                                    <th class="text-center">Check In</th>
                                                    <th class="text-center">Check Out</th>
                                                    <th class="text-center">Expiration Date</th>
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
                                                                <td>{{ @$item->company->Company_Name}}</td>
                                                            @else
                                                                <td>{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                            @endif
                                                            <td>{{ $item->issue_date }}</td>
                                                            <td style="text-align: center;">{{ $item->Date_type ?? 'No Check In Date' }}</td>
                                                            @if ($item->checkin)
                                                            <td style="text-align: center;">{{ $item->checkin}}</td>
                                                            <td style="text-align: center;">{{ $item->checkout }}</td>
                                                            @else
                                                            <td style="text-align: center;">-</td>
                                                            <td style="text-align: center;">-</td>
                                                            @endif
                                                            <td style="text-align: center;">{{ $item->Expirationdate }}</td>
                                                            <td >{{ @$item->userOperated->name }}</td>
                                                            <td style="text-align: center;">
                                                                @if($item->status_document == 0)
                                                                    <span class="badge rounded-pill bg-danger">Cancel</span>
                                                                @elseif ($item->status_document == 1)
                                                                    <span class="badge rounded-pill "style="background-color: #FF6633">Pending</span>
                                                                @elseif ($item->status_document == 2)
                                                                    <span class="badge rounded-pill bg-warning">Awaiting Approval</span>
                                                                @elseif ($item->status_document == 3)
                                                                    <span class="badge rounded-pill bg-success">Approved</span>
                                                                @elseif ($item->status_document == 4)
                                                                    <span class="badge rounded-pill "style="background-color:#1d4ed8">Reject</span>
                                                                @elseif ($item->status_document == 5)
                                                                    <span class="badge rounded-pill "style="background-color:#0ea5e9">Receive (RE)</span>
                                                                @endif
                                                            </td>
                                                            @php
                                                                $CreateBy = Auth::user()->id;
                                                                $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                                $canViewProposal = @Auth::user()->roleMenuView('Billing Folio', Auth::user()->id);
                                                                $canEditProposal = @Auth::user()->roleMenuEdit('Billing Folio', Auth::user()->id);
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
                                        <input type="hidden" id="get-total-proposalCancel" value="{{ $Reject->total() }}">
                                        <input type="hidden" id="currentPage-proposalCancel" value="1">
                                        <caption class="caption-bottom">
                                            <div class="md-flex-bt-i-c">
                                                <p class="py2" id="proposalCancel-showingEntries">{{ showingEntriesTableCancel($Reject, 'proposalCancel') }}</p>
                                                    <div id="proposalCancel-paginate">
                                                        {!! paginateTableCancel($Reject, 'proposalCancel') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                                    </div>
                                            </div>
                                        </caption>
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
    <script type="text/javascript" src="{{ asset('assets/helper/searchTableAdditional.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/daterangepicker.min.js')}}" defer></script>
    <script type="text/javascript" src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css')}}" />
    <script>
        const table_name = ['AdditionalTable','proposalPendingTable','proposalAwaitingTable','proposalApprovedTable','proposalRejectTable','proposalCancelTable'];
        $(document).ready(function() {
            for (let index = 0; index < table_name.length; index++) {
                console.log();

                new DataTable('#'+table_name[index], {
                    searching: false,
                    paging: false,
                    info: false,
                    columnDefs: [{
                        className: 'dtr-control',
                        orderable: true,
                        target: null,
                    }],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    }
                });
            }
        });
        function nav(id) {
            for (let index = 0; index < table_name.length; index++) {
                $('#'+table_name[index]).DataTable().destroy();
                new DataTable('#'+table_name[index], {
                    searching: false,
                    paging: false,
                    info: false,
                    columnDefs: [{
                        className: 'dtr-control',
                        orderable: true,
                        target: null,
                    }],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    }
                });
            }
        }
        $(document).on('keyup', '.search-data', function () {
            var id = $(this).attr('id');
            var search_value = $(this).val();
            var table_name = id+'Table';
            var filter_by = $('#filter-by').val();
            var type_status = $('#status').val();
            var total = parseInt($('#get-total-'+id).val());
            var getUrl = window.location.pathname;
            console.log(search_value);

                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                    url: '/billingover-search-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        search_value: search_value,
                        table_name: table_name,
                        filter_by: filter_by,
                        status: type_status,
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                },
                "initComplete": function (settings,json){

                    if ($('#'+id+'Table .dataTable_empty').length == 0) {
                        var count = $('#'+id+'Table tr').length - 1;
                    }else{
                        var count = 0;
                    }
                    if (search_value == '') {
                        count_total = total;
                    }else{
                        count_total = count;
                    }
                    $('#'+id+'-paginate').children().remove().end();
                    $('#'+id+'-showingEntries').text(showingEntriesSearch(1,count_total, id));
                    $('#'+id+'-paginate').append(paginateSearch(count_total, id, getUrl));
                },
                    columnDefs: [
                                { targets: [0,1,3,4,5,6,7,8,9,10], className: 'dt-center td-content-center' },
                    ],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    },
                    columns: [
                        { data: 'id', "render": function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                        { data: 'Proposal_ID' },
                        { data: 'Company_Name' },
                        { data: 'IssueDate' },
                        { data: 'Type' },
                        { data: 'CheckIn' },
                        { data: 'CheckOut' },
                        { data: 'ExpirationDate' },
                        { data: 'Operated' },
                        { data: 'DocumentStatus' },
                        { data: 'btn_action' }
                    ],

                });


            document.getElementById(id).focus();
        });
        $(document).on('keyup', '.search-data-Awaiting', function () {
            var id = $(this).attr('id');
            var search_value = $(this).val();
            var table_name = id+'Table';
            var filter_by = $('#filter-by').val();
            var type_status = $('#status').val();
            var total = parseInt($('#get-total-'+id).val());
            var getUrl = window.location.pathname;
            console.log(search_value);

                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                    url: '/billingover-Awaiting-search-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        search_value: search_value,
                        table_name: table_name,
                        filter_by: filter_by,
                        status: type_status,
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                },
                "initComplete": function (settings,json){

                    if ($('#'+id+'Table .dataTable_empty').length == 0) {
                        var count = $('#'+id+'Table tr').length - 1;
                    }else{
                        var count = 0;
                    }
                    if (search_value == '') {
                        count_total = total;
                    }else{
                        count_total = count;
                    }
                    $('#'+id+'-paginate').children().remove().end();
                    $('#'+id+'-showingEntries').text(showingEntriesSearchAwaiting(1,count_total, id));
                    $('#'+id+'-paginate').append(paginateSearchAwaiting(count_total, id, getUrl));
                },
                    columnDefs: [
                                { targets: [0,1,2,3,4,5,6,7,8,9,10], className: 'dt-center td-content-center' },
                    ],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    },
                    columns: [
                        { data: 'id', "render": function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                        { data: 'Additional_ID' },
                        { data: 'Proposal_ID' },
                        { data: 'Company_Name' },
                        { data: 'IssueDate' },
                        { data: 'Type' },
                        { data: 'CheckIn' },
                        { data: 'CheckOut' },
                        { data: 'ExpirationDate' },
                        { data: 'Operated' },
                        { data: 'DocumentStatus' },
                        { data: 'btn_action' }
                    ],

                });


            document.getElementById(id).focus();
        });
        $(document).on('keyup', '.search-data-Approved', function () {
            var id = $(this).attr('id');
            var search_value = $(this).val();
            var table_name = id+'Table';
            var filter_by = $('#filter-by').val();
            var type_status = $('#status').val();
            var total = parseInt($('#get-total-'+id).val());
            var getUrl = window.location.pathname;
            console.log(search_value);

                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                    url: '/billingover-Approved-search-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        search_value: search_value,
                        table_name: table_name,
                        filter_by: filter_by,
                        status: type_status,
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                },
                "initComplete": function (settings,json){

                    if ($('#'+id+'Table .dataTable_empty').length == 0) {
                        var count = $('#'+id+'Table tr').length - 1;
                    }else{
                        var count = 0;
                    }
                    if (search_value == '') {
                        count_total = total;
                    }else{
                        count_total = count;
                    }
                    $('#'+id+'-paginate').children().remove().end();
                    $('#'+id+'-showingEntries').text(showingEntriesSearchApproved(1,count_total, id));
                    $('#'+id+'-paginate').append(paginateSearchApproved(count_total, id, getUrl));
                },
                    columnDefs: [
                                { targets: [0,1,2,3,4,5,6,7,8,9,10], className: 'dt-center td-content-center' },
                    ],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    },
                    columns: [
                        { data: 'id', "render": function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                        { data: 'Additional_ID' },
                        { data: 'Proposal_ID' },
                        { data: 'Company_Name' },
                        { data: 'IssueDate' },
                        { data: 'Type' },
                        { data: 'CheckIn' },
                        { data: 'CheckOut' },
                        { data: 'ExpirationDate' },
                        { data: 'Operated' },
                        { data: 'DocumentStatus' },
                        { data: 'btn_action' }
                    ],

                });


            document.getElementById(id).focus();
        });
        $(document).on('keyup', '.search-data-Reject', function () {
            var id = $(this).attr('id');
            var search_value = $(this).val();
            var table_name = id+'Table';
            var filter_by = $('#filter-by').val();
            var type_status = $('#status').val();
            var total = parseInt($('#get-total-'+id).val());
            var getUrl = window.location.pathname;

                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                    url: '/billingover-Reject-search-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        search_value: search_value,
                        table_name: table_name,
                        filter_by: filter_by,
                        status: type_status,
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                },
                "initComplete": function (settings,json){

                    if ($('#'+id+'Table .dataTable_empty').length == 0) {
                        var count = $('#'+id+'Table tr').length - 1;
                    }else{
                        var count = 0;
                    }
                    if (search_value == '') {
                        count_total = total;
                    }else{
                        count_total = count;
                    }
                    $('#'+id+'-paginate').children().remove().end();
                    $('#'+id+'-showingEntries').text(showingEntriesSearchReject(1,count_total, id));
                    $('#'+id+'-paginate').append(paginateSearchReject(count_total, id, getUrl));
                },
                    columnDefs: [
                                { targets: [0,1,2,3,4,5,6,7,8,9,10], className: 'dt-center td-content-center' },
                    ],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    },
                    columns: [
                        { data: 'id', "render": function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                        { data: 'Additional_ID' },
                        { data: 'Proposal_ID' },
                        { data: 'Company_Name' },
                        { data: 'IssueDate' },
                        { data: 'Type' },
                        { data: 'CheckIn' },
                        { data: 'CheckOut' },
                        { data: 'ExpirationDate' },
                        { data: 'Operated' },
                        { data: 'DocumentStatus' },
                        { data: 'btn_action' }
                    ],

                });


            document.getElementById(id).focus();
        });
        $(document).on('keyup', '.search-data-Cancel', function () {
            var id = $(this).attr('id');
            var search_value = $(this).val();
            var table_name = id+'Table';
            var filter_by = $('#filter-by').val();
            var type_status = $('#status').val();
            var total = parseInt($('#get-total-'+id).val());
            var getUrl = window.location.pathname;

                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                    url: '/billingover-Cancel-search-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        search_value: search_value,
                        table_name: table_name,
                        filter_by: filter_by,
                        status: type_status,
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                },
                "initComplete": function (settings,json){

                    if ($('#'+id+'Table .dataTable_empty').length == 0) {
                        var count = $('#'+id+'Table tr').length - 1;
                    }else{
                        var count = 0;
                    }
                    if (search_value == '') {
                        count_total = total;
                    }else{
                        count_total = count;
                    }
                    $('#'+id+'-paginate').children().remove().end();
                    $('#'+id+'-showingEntries').text(showingEntriesSearchCancel(1,count_total, id));
                    $('#'+id+'-paginate').append(paginateSearchCancel(count_total, id, getUrl));
                },
                    columnDefs: [
                                { targets: [0,1,2,3,4,5,6,7,8,9,10], className: 'dt-center td-content-center' },
                    ],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    },
                    columns: [
                        { data: 'id', "render": function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
                        { data: 'Additional_ID' },
                        { data: 'Proposal_ID' },
                        { data: 'Company_Name' },
                        { data: 'IssueDate' },
                        { data: 'Type' },
                        { data: 'CheckIn' },
                        { data: 'CheckOut' },
                        { data: 'ExpirationDate' },
                        { data: 'Operated' },
                        { data: 'DocumentStatus' },
                        { data: 'btn_action' }
                    ],

                });


            document.getElementById(id).focus();
        });
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
