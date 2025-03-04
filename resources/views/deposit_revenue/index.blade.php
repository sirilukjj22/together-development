@extends('layouts.masterLayout')
@php
    $excludeDatatable = false;
@endphp
@section('content')
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Deposit Revenue</div>
                </div>
                <div class="col-auto">
                </div>
            </div> <!-- .row end -->
        </div>
    </div>
    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
            <div class="row align-items-center mb-2" >
                @if (session("susscess"))
                <div class="alert alert-susscess" role="alert">
                    <h4 class="alert-heading">Save Receipt Paymentful.</h4>
                    <hr>
                    <p class="mb-0">{{ session('susscess') }}</p>
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
                                        <form action="{{ url('/Document/Deposit/cancel/') }}" method="POST" enctype="multipart/form-data" class="row g-3 basic-form">
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
            </div> <!-- Row end  -->
        </div> <!-- Row end  -->
        <div class="row clearfix mb-3">
            <div class="col-sm-12 col-12">
                <ul class="nav nav-tabs px-3 border-bottom-0" role="tablist">
                    <li class="nav-item" id="nav1"><a class="nav-link active" data-bs-toggle="tab" href="#nav-all" onclick="nav($id='nav2')" role="tab"><i class="fa fa-circle fa-xs"style="color: green;" ></i> Receipt / Deposit Revenue</a></li>
                    <li class="nav-item" id="nav3"><a class="nav-link " data-bs-toggle="tab" href="#nav-Pending"  onclick="nav($id='nav3')"role="tab"><i class="fa fa-circle fa-xs"style="color: #FF6633;"></i> Pending</a></li>
                    <li class="nav-item" id="nav4"><a class="nav-link " data-bs-toggle="tab" href="#nav-Approved" onclick="nav($id='nav4')" role="tab"><i class="fa fa-circle fa-xs"style="color: #0ea5e9;"></i> Await Deduct</a></li>
                    <li class="nav-item" id="nav4"><a class="nav-link " data-bs-toggle="tab" href="#nav-com" onclick="nav($id='nav6')" role="tab"><i class="fa fa-circle fa-xs"style="color: #2C7F7A;"></i> Deducted</a></li>
                    <li class="nav-item" id="nav5"><a class="nav-link" data-bs-toggle="tab" href="#nav-Cancel" onclick="nav($id='nav5')" role="tab"><i class="fa fa-circle fa-xs"style="color: red;"></i> Cancel</a></li>
                </ul>
                <div class="card p-4 mb-4">
                    <div class="tab-content">
                        <div class="tab-pane fade  show active" id="nav-all" role="tabpanel" rel="0">
                            <div style="min-height: 70vh;" class="mt-2">
                                <table id="invoiceTable" class="table-together table-style">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;"data-priority="1">No</th>
                                            <th data-priority="1">Deposit Revenue ID</th>
                                            <th data-priority="1">Proposal ID</th>
                                            <th data-priority="1">Company / Individual</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Issue Date</th>
                                            <th class="text-center">Expiration Date</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($diposit))
                                            @foreach ($diposit as $key => $item)
                                            <tr>
                                                <td style="text-align: center;">
                                                    {{$key +1}}
                                                </td>
                                                <td>{{ $item->Deposit_ID}}</td>
                                                <td>{{ $item->Quotation_ID}}</td>
                                                <td style="text-align: left;">{{ $item->fullname}}</td>
                                                <td style="text-align: center;">
                                                    {{ number_format($item->amount, 2) }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ $item->Issue_date}}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ $item->ExpirationDate}}
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($item->document_status == 1 && $item->receipt == 0)
                                                        <span class="badge rounded-pill "style="background-color: #FF6633">Pending</span>
                                                    @elseif ($item->document_status == 2 && $item->receipt == 0)
                                                        <span class="badge rounded-pill "style="background-color: #0ea5e9"> Await Deduct</span>
                                                    @elseif ($item->document_status == 0  && $item->receipt == 0)
                                                        <span class="badge rounded-pill "style="background-color: red"> Cancel</span>
                                                    @endif
                                                    @if ($item->receipt == 1)
                                                        <span class="badge rounded-pill "style="background-color: #2C7F7A"> Deducted</span>
                                                    @endif
                                                </td>
                                                @php
                                                    $CreateBy = Auth::user()->id;
                                                    $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                    $canViewProposal = @Auth::user()->roleMenuView('Deposit Revenue', Auth::user()->id);
                                                    $canEditProposal = @Auth::user()->roleMenuEdit('Deposit Revenue', Auth::user()->id);
                                                @endphp

                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            @if ($rolePermission > 0)
                                                                @if ($canViewProposal == 1)
                                                                    @if ($item->document_status == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/view/invoice/deposit/'.$item->id) }}">View</a></li>
                                                                    @endif
                                                                    @if ($item->document_status == 2)
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/view/revenue/deposit/'.$item->id) }}">View</a></li>
                                                                    @endif
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/LOG/'.$item->id) }}">LOG</a></li>
                                                                @endif
                                                                @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                    @if ($canEditProposal == 1)
                                                                        @if ($item->document_status == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/generate/Revenue/'.$item->id) }}">Await Deduct</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/edit/'.$item->id) }}">Edit</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                        @endif
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/Send/Email/'.$item->id) }}">Send Email</a></li>
                                                                    @endif
                                                                @elseif ($rolePermission == 2)
                                                                    @if ($canEditProposal == 1)
                                                                        @if ($item->document_status == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/generate/Revenue/'.$item->id) }}">Await Deduct</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/edit/'.$item->id) }}">Edit</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                        @endif
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/Send/Email/'.$item->id) }}">Send Email</a></li>
                                                                    @endif
                                                                @elseif ($rolePermission == 3)
                                                                    @if ($canEditProposal == 1)
                                                                        @if ($item->document_status == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/generate/Revenue/'.$item->id) }}">Await Deduct</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/edit/'.$item->id) }}">Edit</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                        @endif
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/Send/Email/'.$item->id) }}">Send Email</a></li>
                                                                    @endif
                                                                @endif
                                                            @else
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/view/invoice/deposit/'.$item->id) }}">View</a></li>
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/LOG/'.$item->id) }}">LOG</a></li>
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
                        <div class="tab-pane fade" id="nav-Pending" role="tabpanel" rel="0">
                            <div style="min-height: 70vh;" class="mt-2">
                                <table id="invoiceTable" class="table-together table-style">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;"data-priority="1">No</th>
                                            <th data-priority="1">Deposit Revenue ID</th>
                                            <th data-priority="1">Proposal ID</th>
                                            <th data-priority="1">Company / Individual</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Issue Date</th>
                                            <th class="text-center">Expiration Date</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($pening))
                                            @foreach ($pening as $key => $item)
                                            <tr>
                                                <td style="text-align: center;">
                                                    {{$key +1}}
                                                </td>
                                                <td>{{ $item->Deposit_ID}}</td>
                                                <td>{{ $item->Quotation_ID}}</td>
                                                <td style="text-align: left;">{{ $item->fullname}}</td>
                                                <td style="text-align: center;">
                                                    {{ number_format($item->amount, 2) }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ $item->Issue_date}}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ $item->ExpirationDate}}
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($item->document_status == 1)
                                                        <span class="badge rounded-pill "style="background-color: #FF6633">Pending</span>
                                                    @elseif ($item->document_status == 2)
                                                        <span class="badge rounded-pill "style="background-color: #0ea5e9"> Await Deduct</span>
                                                    @endif
                                                </td>
                                                @php
                                                    $CreateBy = Auth::user()->id;
                                                    $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                    $canViewProposal = @Auth::user()->roleMenuView('Deposit Revenue', Auth::user()->id);
                                                    $canEditProposal = @Auth::user()->roleMenuEdit('Deposit Revenue', Auth::user()->id);
                                                @endphp

                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            @if ($rolePermission > 0)
                                                                @if ($canViewProposal == 1)
                                                                    @if ($item->document_status == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/view/invoice/deposit/'.$item->id) }}">View</a></li>
                                                                    @endif
                                                                    @if ($item->document_status == 2)
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/view/revenue/deposit/'.$item->id) }}">View</a></li>
                                                                    @endif
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/LOG/'.$item->id) }}">LOG</a></li>
                                                                @endif
                                                                @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                    @if ($canEditProposal == 1)
                                                                        @if ($item->document_status == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/generate/Revenue/'.$item->id) }}">Await Deduct</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/edit/'.$item->id) }}">Edit</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                        @endif
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/Send/Email/'.$item->id) }}">Send Email</a></li>
                                                                    @endif
                                                                @elseif ($rolePermission == 2)
                                                                    @if ($canEditProposal == 1)
                                                                        @if ($item->document_status == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/generate/Revenue/'.$item->id) }}">Await Deduct</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/edit/'.$item->id) }}">Edit</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                        @endif
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/Send/Email/'.$item->id) }}">Send Email</a></li>
                                                                    @endif
                                                                @elseif ($rolePermission == 3)
                                                                    @if ($canEditProposal == 1)
                                                                        @if ($item->document_status == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/generate/Revenue/'.$item->id) }}">Await Deduct</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/edit/'.$item->id) }}">Edit</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                        @endif
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/Send/Email/'.$item->id) }}">Send Email</a></li>
                                                                    @endif
                                                                @endif
                                                            @else
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/view/invoice/deposit/'.$item->id) }}">View</a></li>
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/LOG/'.$item->id) }}">LOG</a></li>
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
                        <div class="tab-pane fade "id="nav-Approved" role="tabpanel" rel="0">
                            <div style="min-height: 70vh;" class="mt-2">
                                <table id="invoiceTable" class="table-together table-style">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;"data-priority="1">No</th>
                                            <th data-priority="1">Deposit Revenue ID</th>
                                            <th data-priority="1">Proposal ID</th>
                                            <th data-priority="1">Company / Individual</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Issue Date</th>
                                            <th class="text-center">Expiration Date</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($success))
                                            @foreach ($success as $key => $item)
                                            <tr>
                                                <td style="text-align: center;">
                                                    {{$key +1}}
                                                </td>
                                                <td>{{ $item->Deposit_ID}}</td>
                                                <td>{{ $item->Quotation_ID}}</td>
                                                <td style="text-align: left;">{{ $item->fullname}}</td>
                                                <td style="text-align: center;">
                                                    {{ number_format($item->amount, 2) }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ $item->Issue_date}}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ $item->ExpirationDate}}
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($item->document_status == 1)
                                                        <span class="badge rounded-pill "style="background-color: #FF6633">Pending</span>
                                                    @elseif ($item->document_status == 2)
                                                        <span class="badge rounded-pill "style="background-color: #0ea5e9"> Await Deduct</span>
                                                    @endif
                                                </td>
                                                @php
                                                    $CreateBy = Auth::user()->id;
                                                    $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                    $canViewProposal = @Auth::user()->roleMenuView('Deposit Revenue', Auth::user()->id);
                                                    $canEditProposal = @Auth::user()->roleMenuEdit('Deposit Revenue', Auth::user()->id);
                                                @endphp

                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            @if ($rolePermission > 0)
                                                                @if ($canViewProposal == 1)
                                                                    @if ($item->document_status == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/view/invoice/deposit/'.$item->id) }}">View</a></li>
                                                                    @endif
                                                                    @if ($item->document_status == 2)
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/view/revenue/deposit/'.$item->id) }}">View</a></li>
                                                                    @endif
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/LOG/'.$item->id) }}">LOG</a></li>
                                                                @endif
                                                                @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                    @if ($canEditProposal == 1)
                                                                        @if ($item->document_status == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/generate/Revenue/'.$item->id) }}">Await Deduct</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/edit/'.$item->id) }}">Edit</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                        @endif
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/Send/Email/'.$item->id) }}">Send Email</a></li>
                                                                    @endif
                                                                @elseif ($rolePermission == 2)
                                                                    @if ($canEditProposal == 1)
                                                                        @if ($item->document_status == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/generate/Revenue/'.$item->id) }}">Await Deduct</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/edit/'.$item->id) }}">Edit</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                        @endif
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/Send/Email/'.$item->id) }}">Send Email</a></li>
                                                                    @endif
                                                                @elseif ($rolePermission == 3)
                                                                    @if ($canEditProposal == 1)
                                                                        @if ($item->document_status == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/generate/Revenue/'.$item->id) }}">Await Deduct</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/edit/'.$item->id) }}">Edit</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                        @endif
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/Send/Email/'.$item->id) }}">Send Email</a></li>
                                                                    @endif
                                                                @endif
                                                            @else
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/view/invoice/deposit/'.$item->id) }}">View</a></li>
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/LOG/'.$item->id) }}">LOG</a></li>
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
                        <div class="tab-pane fade "id="nav-com" role="tabpanel" rel="0">
                            <div style="min-height: 70vh;" class="mt-2">
                                <table id="invoiceTable" class="table-together table-style">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;"data-priority="1">No</th>
                                            <th data-priority="1">Deposit Revenue ID</th>
                                            <th data-priority="1">Proposal ID</th>
                                            <th data-priority="1">Company / Individual</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Issue Date</th>
                                            <th class="text-center">Expiration Date</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($invoice))
                                            @foreach ($invoice as $key => $item)
                                            <tr>
                                                <td style="text-align: center;">
                                                    {{$key +1}}
                                                </td>
                                                <td>{{ $item->Deposit_ID}}</td>
                                                <td>{{ $item->Quotation_ID}}</td>
                                                <td style="text-align: left;">{{ $item->fullname}}</td>
                                                <td style="text-align: center;">
                                                    {{ number_format($item->amount, 2) }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ $item->Issue_date}}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ $item->ExpirationDate}}
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($item->receipt == 1)
                                                        <span class="badge rounded-pill "style="background-color: #2C7F7A">Deducted</span>
                                                    @endif
                                                </td>
                                                @php
                                                    $CreateBy = Auth::user()->id;
                                                    $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                    $canViewProposal = @Auth::user()->roleMenuView('Deposit Revenue', Auth::user()->id);
                                                    $canEditProposal = @Auth::user()->roleMenuEdit('Deposit Revenue', Auth::user()->id);
                                                @endphp

                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            @if ($rolePermission > 0)
                                                                @if ($canViewProposal == 1)
                                                                    @if ($item->document_status == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/view/invoice/deposit/'.$item->id) }}">View</a></li>
                                                                    @endif
                                                                    @if ($item->document_status == 2)
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/view/revenue/deposit/'.$item->id) }}">View</a></li>
                                                                    @endif
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/LOG/'.$item->id) }}">LOG</a></li>
                                                                @endif
                                                                {{-- @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                    @if ($canEditProposal == 1)
                                                                        @if ($item->document_status == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/generate/Revenue/'.$item->id) }}">Await Deduct</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/edit/'.$item->id) }}">Edit</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                        @endif
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/Send/Email/'.$item->id) }}">Send Email</a></li>
                                                                    @endif
                                                                @elseif ($rolePermission == 2)
                                                                    @if ($canEditProposal == 1)
                                                                        @if ($item->document_status == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/generate/Revenue/'.$item->id) }}">Await Deduct</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/edit/'.$item->id) }}">Edit</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                        @endif
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/Send/Email/'.$item->id) }}">Send Email</a></li>
                                                                    @endif
                                                                @elseif ($rolePermission == 3)
                                                                    @if ($canEditProposal == 1)
                                                                        @if ($item->document_status == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/generate/Revenue/'.$item->id) }}">Await Deduct</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/edit/'.$item->id) }}">Edit</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                        @endif
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/Send/Email/'.$item->id) }}">Send Email</a></li>
                                                                    @endif
                                                                @endif --}}
                                                            @else
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/view/invoice/deposit/'.$item->id) }}">View</a></li>
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/LOG/'.$item->id) }}">LOG</a></li>
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
                        <div class="tab-pane fade "id="nav-Cancel" role="tabpanel" rel="0">
                            <div style="min-height: 70vh;" class="mt-2">
                                <table id="invoiceTable" class="table-together table-style">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;"data-priority="1">No</th>
                                            <th data-priority="1">Deposit Revenue ID</th>
                                            <th data-priority="1">Proposal ID</th>
                                            <th data-priority="1">Company / Individual</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Issue Date</th>
                                            <th class="text-center">Expiration Date</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($cancel))
                                            @foreach ($cancel as $key => $item)
                                            <tr>
                                                <td style="text-align: center;">
                                                    {{$key +1}}
                                                </td>
                                                <td>{{ $item->Deposit_ID}}</td>
                                                <td>{{ $item->Quotation_ID}}</td>
                                                <td style="text-align: left;">{{ $item->fullname}}</td>
                                                <td style="text-align: center;">
                                                    {{ number_format($item->amount, 2) }}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ $item->Issue_date}}
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ $item->ExpirationDate}}
                                                </td>
                                                <td style="text-align: center;">
                                                    <span class="badge rounded-pill "style="background-color: red"> Cancel</span>
                                                </td>
                                                @php
                                                    $CreateBy = Auth::user()->id;
                                                    $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                    $canViewProposal = @Auth::user()->roleMenuView('Deposit Revenue', Auth::user()->id);
                                                    $canEditProposal = @Auth::user()->roleMenuEdit('Deposit Revenue', Auth::user()->id);
                                                @endphp

                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            @if ($rolePermission > 0)
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/view/invoice/deposit/'.$item->id) }}">View</a></li>
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/LOG/'.$item->id) }}">LOG</a></li>
                                                                @endif
                                                                @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                    @if ($canEditProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Revise({{ $item->id }})">Revise</a></li>
                                                                    @endif
                                                                @elseif ($rolePermission == 2)
                                                                    @if ($canEditProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Revise({{ $item->id }})">Revise</a></li>
                                                                    @endif
                                                                @elseif ($rolePermission == 3)
                                                                    @if ($canEditProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Revise({{ $item->id }})">Revise</a></li>
                                                                    @endif
                                                                @endif
                                                            @else
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/view/invoice/deposit/'.$item->id) }}">View</a></li>
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/LOG/'.$item->id) }}">LOG</a></li>
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

        function Revise(id){
            Swal.fire({
            title: "Do you want to enable this item?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "Cancel",
            confirmButtonColor: "#2C7F7A",
            dangerMode: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ url('/Document/Deposit/Revise/') }}/" + id;
                }
            });
        }
        function Cancel(id){
            Swal.fire({
            title: "Do you want to cancel this offer?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "Cancel",
            confirmButtonColor: "#2C7F7A",
            dangerMode: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // อัปเดต URL ของฟอร์มในโมดอล
                    const form = document.querySelector('#myModal form');
                    form.action = `{{ url('/Document/Deposit/cancel/') }}/${id}`;
                    $('#myModal').modal('show'); // เปิดโมดอล
                }
            });
        }



    </script>
@endsection
