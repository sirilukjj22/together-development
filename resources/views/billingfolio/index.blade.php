@extends('layouts.masterLayout')

@section('content')
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Billing Folio</div>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ route('BillingFolio.issuebill') }}'">
                        <i class="fa fa-plus"></i> Issue Bill
                    </button>
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
            </div> <!-- Row end  -->
        </div> <!-- Row end  -->
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-sm-12 col-12">
                    <ul class="nav nav-tabs px-3 border-bottom-0" role="tablist">
                        <li class="nav-item" id="nav1"><a class="nav-link active" data-bs-toggle="tab" href="#nav-Receipt" role="tab" onclick="nav($id='nav1')"><span class="badge" style="background-color:#64748b">{{$ApprovedCount}}</span> Receipt</a></li>{{--ประวัติการแก้ไข--}}
                        <li class="nav-item" id="nav2"><a class="nav-link " data-bs-toggle="tab" href="#nav-Approved" onclick="nav($id='nav2')" role="tab"><span class="badge bg-success">{{$ComplateCount}}</span> Complete</a></li>
                        <li class="nav-item" id="nav6"><a class="nav-link " data-bs-toggle="tab" href="#nav-Reject" onclick="nav($id='nav6')" role="tab"><span class="badge "style="background-color:#1d4ed8" >{{$Rejectcount}}</span> Reject</a></li>
                        <li class="nav-item" id="nav4"><a class="nav-link " data-bs-toggle="tab" href="#nav-Cancel" onclick="nav($id='nav5')" role="tab"><span class="badge  bg-danger"  >{{$NoshowCount}}</span> No Show</a></li>
                    </ul>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade  show active" id="nav-Receipt" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" class="mt-2">
                                        <caption class="caption-top">
                                            <div class="flex-end-g2">
                                                <label class="entriespage-label">entries per page :</label>
                                                <select class="entriespage-button" id="search-per-page-billing" onchange="getPage(1, this.value, 'billing')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                    <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "billing" ? 'selected' : '' }}>10</option>
                                                    <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "billing" ? 'selected' : '' }}>25</option>
                                                    <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "billing" ? 'selected' : '' }}>50</option>
                                                    <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "billing" ? 'selected' : '' }}>100</option>
                                                </select>
                                                <input class="search-button search-data" id="billing" style="text-align:left;" placeholder="Search" />
                                            </div>
                                        </caption>
                                        <table id="billingTable" class="example ui striped table nowrap unstackable hover">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center;"data-priority="1">No</th>
                                                    <th data-priority="1">Receipt ID</th>
                                                    <th>Proposal ID</th>
                                                    <th data-priority="1">Company / Individual</th>
                                                    <th>Payment Date</th>
                                                    <th class="text-center">Amount</th>
                                                    <th class="text-center">Operated By</th>
                                                    <th class="text-center">Document status</th>
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
                                                        <td>{{ $item->Receipt_ID}}</td>
                                                        <td>{{ $item->Quotation_ID}}</td>
                                                        <td>{{$item->fullname}}</td>
                                                        <td>{{ $item->paymentDate }}</td>
                                                        <td style="text-align: center;">
                                                            {{ number_format($item->document_amount) }}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{ @$item->userOperated->name }}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <span class="badge rounded-pill bg-success">Confirm</span>
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
                                                                    @if ($rolePermission > 0)
                                                                        @if ($canViewProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Document/BillingFolio/Proposal/invoice/view/'.$item->id) }}">Export</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/invoice/log/'.$item->id) }}">LOG</a></li>
                                                                        @endif
                                                                        @if ($item->created_at->toDateString() < now()->toDateString())
                                                                        @else
                                                                            @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                                @if ($canEditProposal == 1)
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/invoice/Generate/Paid/Edit/'.$item->id) }}">Edit</a></li>
                                                                                @endif
                                                                            @elseif ($rolePermission == 2)
                                                                                @if ($item->Operated_by == $CreateBy)
                                                                                    @if ($canEditProposal == 1)
                                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/invoice/Generate/Paid/Edit/'.$item->id) }}">Edit</a></li>
                                                                                    @endif
                                                                                @endif
                                                                            @elseif ($rolePermission == 3)
                                                                                @if ($canEditProposal == 1)
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/invoice/Generate/Paid/Edit/'.$item->id) }}">Edit</a></li>
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @else
                                                                        @if ($canViewProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Document/BillingFolio/Proposal/invoice/view/'.$item->id) }}">Export</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/invoice/log/'.$item->id) }}">LOG</a></li>
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
                                        <input type="hidden" id="get-total-billing" value="{{ $Approved->total() }}">
                                        <input type="hidden" id="currentPage-billing" value="1">
                                        <caption class="caption-bottom">
                                            <div class="md-flex-bt-i-c">
                                                <p class="py2" id="billing-showingEntries">{{ showingEntriesTable($Approved, 'billing') }}</p>
                                                <div id="billing-paginate">
                                                    {!! paginateTable($Approved, 'billing') !!} <!-- ข้อมูล, ชื่อตาราง -->
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
                                                    <th style="text-align: center;"data-priority="1">No</th>
                                                    <th data-priority="1">Proposal ID</th>
                                                    <th data-priority="1">Company / Individual</th>
                                                    <th>Issue Date</th>
                                                    <th>Expiration Date</th>
                                                    <th class="text-center">Amount</th>
                                                    <th class="text-center">Document Receipt</th>
                                                    <th class="text-center">Approve By</th>
                                                    <th class="text-center">Document status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($Complate))
                                                    @foreach ($Complate as $key => $item)
                                                    <tr>
                                                        <td style="text-align: center;">
                                                            {{$key +1}}
                                                        </td>
                                                        <td>{{ $item->Quotation_ID}}</td>
                                                        <td>{{$item->fullname}}</td>
                                                        <td>{{ $item->issue_date }}</td>
                                                        <td>{{ $item->Expirationdate }}</td>
                                                        <td style="text-align: center;">
                                                            {{ number_format($item->document_amount) }}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{$item->receive_count}}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            @if (@$item->userOperated->name == null)
                                                                Auto
                                                            @else
                                                                {{ @$item->userOperated->name }}
                                                            @endif
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <span class="badge rounded-pill bg-success">Proposal</span>
                                                        </td>

                                                        <td style="text-align: center;">
                                                            <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ url('/Document/BillingFolio/Proposal/invoice/CheckPI/'.$item->id) }}'">
                                                                Select
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                        <input type="hidden" id="get-total-proposalApproved" value="{{ $Complate->total() }}">
                                        <input type="hidden" id="currentPage-proposalApproved" value="1">
                                        <caption class="caption-bottom">
                                            <div class="md-flex-bt-i-c">
                                                <p class="py2" id="proposalApproved-showingEntries">{{ showingEntriesTableApproved($Complate, 'proposalApproved') }}</p>
                                                    <div id="proposalApproved-paginate">
                                                        {!! paginateTableApproved($Complate, 'proposalApproved') !!} <!-- ข้อมูล, ชื่อตาราง -->
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
                                                <select class="entriespage-button" id="search-per-page-billingCancel" onchange="getPageCancel(1, this.value, 'billingCancel')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                    <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "billingCancel" ? 'selected' : '' }}>10</option>
                                                    <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "billingCancel" ? 'selected' : '' }}>25</option>
                                                    <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "billingCancel" ? 'selected' : '' }}>50</option>
                                                    <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "billingCancel" ? 'selected' : '' }}>100</option>
                                                </select>
                                                <input class="search-button search-data-billingCancel" id="billingCancel" style="text-align:left;" placeholder="Search" />
                                            </div>
                                        </caption>
                                        <table id="billingCancelTable" class="example ui striped table nowrap unstackable hover">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center;"data-priority="1">No</th>
                                                    <th data-priority="1">Receipt ID</th>
                                                    <th>Proposal ID</th>
                                                    <th data-priority="1">Company / Individual</th>
                                                    <th>Payment Date</th>
                                                    <th class="text-center">Amount</th>
                                                    <th class="text-center">Operated By</th>
                                                    <th class="text-center">Document status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($Noshow))
                                                    @foreach ($Noshow as $key => $item)
                                                    <tr>
                                                        <td style="text-align: center;">
                                                            {{$key +1}}
                                                        </td>
                                                        <td>{{ $item->Receipt_ID}}</td>
                                                        <td>{{ $item->Quotation_ID}}</td>
                                                        <td>{{$item->fullname}}</td>
                                                        <td>{{ $item->paymentDate }}</td>
                                                        <td style="text-align: center;">
                                                            {{ number_format($item->document_amount) }}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{ @$item->userOperated->name }}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <span class="badge rounded-pill bg-success">Confirm</span>
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
                                                                    <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Document/BillingFolio/Proposal/invoice/view/'.$item->id) }}">Export</a></li>
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/invoice/log/'.$item->id) }}">LOG</a></li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                        <input type="hidden" id="get-total-billingCancel" value="{{ $Noshow->total() }}">
                                        <input type="hidden" id="currentPage-billingCancel" value="1">
                                        <caption class="caption-bottom">
                                            <div class="md-flex-bt-i-c">
                                                <p class="py2" id="billingCancel-showingEntries">{{ showingEntriesTableCancel($Noshow, 'billingCancel') }}</p>
                                                <div id="billingCancel-paginate">
                                                    {!! paginateTableCancel($Noshow, 'billingCancel') !!} <!-- ข้อมูล, ชื่อตาราง -->
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
                                                <select class="entriespage-button" id="search-per-page-billingReject" onchange="getPageReject(1, this.value, 'billingReject')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                    <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "billingReject" ? 'selected' : '' }}>10</option>
                                                    <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "billingReject" ? 'selected' : '' }}>25</option>
                                                    <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "billingReject" ? 'selected' : '' }}>50</option>
                                                    <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "billingReject" ? 'selected' : '' }}>100</option>
                                                </select>
                                                <input class="search-button search-data-billingReject" id="billingReject" style="text-align:left;" placeholder="Search" />
                                            </div>
                                        </caption>
                                        <table id="billingRejectTable" class="example ui striped table nowrap unstackable hover">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center;"data-priority="1">No</th>
                                                    <th data-priority="1">Receipt ID</th>
                                                    <th>Proposal ID</th>
                                                    <th data-priority="1">Company / Individual</th>
                                                    <th>Payment Date</th>
                                                    <th class="text-center">Amount</th>
                                                    <th class="text-center">Operated By</th>
                                                    <th class="text-center">Document status</th>
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
                                                        <td>{{ $item->Receipt_ID}}</td>
                                                        <td>{{ $item->Quotation_ID}}</td>
                                                        <td>{{$item->fullname}}</td>
                                                        <td>{{ $item->paymentDate }}</td>
                                                        <td style="text-align: center;">
                                                            {{ number_format($item->document_amount) }}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{ @$item->userOperated->name }}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <span class="badge rounded-pill bg-success">Confirm</span>
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
                                                                    @if ($rolePermission > 0)
                                                                        @if ($canViewProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/invoice/log/'.$item->id) }}">LOG</a></li>
                                                                        @endif
                                                                        @if ($item->created_at->toDateString() < now()->toDateString())
                                                                        @else
                                                                            @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                                @if ($canEditProposal == 1)
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/invoice/Generate/Paid/Edit/'.$item->id) }}">Edit</a></li>
                                                                                @endif
                                                                            @elseif ($rolePermission == 2)
                                                                                @if ($item->Operated_by == $CreateBy)
                                                                                    @if ($canEditProposal == 1)
                                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/invoice/Generate/Paid/Edit/'.$item->id) }}">Edit</a></li>
                                                                                    @endif
                                                                                @endif
                                                                            @elseif ($rolePermission == 3)
                                                                                @if ($canEditProposal == 1)
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/invoice/Generate/Paid/Edit/'.$item->id) }}">Edit</a></li>
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @else
                                                                        @if ($canViewProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/invoice/log/'.$item->id) }}">LOG</a></li>
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
                                        <input type="hidden" id="get-total-billingReject" value="{{ $Reject->total() }}">
                                        <input type="hidden" id="currentPage-billingReject" value="1">
                                        <caption class="caption-bottom">
                                            <div class="md-flex-bt-i-c">
                                                <p class="py2" id="billingReject-showingEntries">{{ showingEntriesTableReject($Reject, 'billingReject') }}</p>
                                                <div id="billingReject-paginate">
                                                    {!! paginateTableReject($Reject, 'billingReject') !!} <!-- ข้อมูล, ชื่อตาราง -->
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
    <script type="text/javascript" src="{{ asset('assets/helper/searchTableBilling.js')}}"></script>
    <script>
        const table_name = ['billingTable','proposalApprovedTable','billingCancelTable','billingRejectTable'];
        $(document).ready(function() {
            for (let index = 0; index < table_name.length; index++) {
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
            console.log(table_name);
                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                    url: '/billing-search-table',
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
                                { targets: [0,4,5,6,7,8], className: 'dt-center td-content-center' },
                    ],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    },
                    columns: [
                        { data: 'number'},
                        { data: 'Receipt' },
                        { data: 'Proposal' },
                        { data: 'Company_Name' },
                        { data: 'Payment_date' },
                        { data: 'Amount' },
                        { data: 'Approve' },
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
            console.log(table_name);
                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                    url: '/billing-Approved-search-table',
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
                                { targets: [0,3,4,5,6,7,8,9], className: 'dt-center td-content-center' },
                    ],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    },
                    columns: [
                        { data: 'number'},
                        { data: 'Proposal' },
                        { data: 'Company_Name' },
                        { data: 'IssueDate' },
                        { data: 'ExpirationDate' },
                        { data: 'Amount' },
                        { data: 'Deposit' },
                        { data: 'Approve' },
                        { data: 'DocumentStatus' },
                        { data: 'btn_action' }
                    ],

                });


            document.getElementById(id).focus();
        });
        $(document).on('keyup', '.search-data-billingReject', function () {
            var id = $(this).attr('id');
            var search_value = $(this).val();
            var table_name = id+'Table';
            var filter_by = $('#filter-by').val();
            var type_status = $('#status').val();
            var total = parseInt($('#get-total-'+id).val());
            var getUrl = window.location.pathname;
            console.log(search_value);
            console.log(table_name);
                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                    url: '/billing-reject-search-table',
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
                                { targets: [0,4,5,6,7,8], className: 'dt-center td-content-center' },
                    ],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    },
                    columns: [
                        { data: 'number'},
                        { data: 'Receipt' },
                        { data: 'Proposal' },
                        { data: 'Company_Name' },
                        { data: 'Payment_date' },
                        { data: 'Amount' },
                        { data: 'Approve' },
                        { data: 'DocumentStatus' },
                        { data: 'btn_action' }
                    ],

                });


            document.getElementById(id).focus();
        });
        $(document).on('keyup', '.search-data-billingCancel', function () {
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
                    url: '/billing-Cancel-search-table',
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
                                { targets: [0,4,5,6,7,8], className: 'dt-center td-content-center' },
                    ],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    },
                    columns: [
                        { data: 'number'},
                        { data: 'Receipt' },
                        { data: 'Proposal' },
                        { data: 'Company_Name' },
                        { data: 'Payment_date' },
                        { data: 'Amount' },
                        { data: 'Approve' },
                        { data: 'DocumentStatus' },
                        { data: 'btn_action' }
                    ],

                });


            document.getElementById(id).focus();
        });
    </script>
@endsection
