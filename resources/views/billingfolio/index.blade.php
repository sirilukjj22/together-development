@extends('layouts.masterLayout')
@php
    $excludeDatatable = false;
@endphp
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

                                        <table id="billingTable" class="table-together table-style">
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

                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-Approved" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" class="mt-2">

                                        <table id="proposalApprovedTable" class="table-together table-style">
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

                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-Cancel" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" class="mt-2">

                                        <table id="billingCancelTable" class="table-together table-style">
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

                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-Reject" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" class="mt-2">

                                        <table id="billingRejectTable" class="table-together table-style">
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
    <script>
        function nav(id) {
            $.fn.dataTable
            .tables({ visible: true, api: true })
            .columns.adjust()
            .responsive.recalc();
        }
    </script>
@endsection
