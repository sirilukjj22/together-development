@extends('layouts.masterLayout')
@php
    $excludeDatatable = false;
@endphp
@section('content')
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Proforma Invoice</div>
                </div>
                <div class="col-auto">

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
                                        <form action="{{ url('/Document/invoice/cancel/') }}" method="POST" enctype="multipart/form-data" class="row g-3 basic-form">
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
                    <li class="nav-item" id="nav1"><a class="nav-link active"  data-bs-toggle="tab" href="#nav-Dummy"onclick="nav($id='nav1')" role="tab" ><span class="badge" style="background-color:#64748b">{{0}}</span> Proposal</a></li>{{--ประวัติการแก้ไข--}}
                    <li class="nav-item" id="nav2"><a class="nav-link " data-bs-toggle="tab" href="#nav-all" onclick="nav($id='nav2')" role="tab"><span class="badge bg-success" >{{0}}</span> Invoice</a></li>
                    <li class="nav-item" id="nav2"><a class="nav-link " data-bs-toggle="tab" href="#nav-Pending"  onclick="nav($id='nav3')"role="tab"><span class="badge" style="background-color:#FF6633">{{0}}</span> Pending</a></li>
                    <li class="nav-item" id="nav4"><a class="nav-link " data-bs-toggle="tab" href="#nav-Approved" onclick="nav($id='nav4')" role="tab"><span class="badge" style="background-color: #0ea5e9" >{{0}}</span> Generate</a></li>
                    <li class="nav-item" id="nav5"><a class="nav-link" data-bs-toggle="tab" href="#nav-Cancel" onclick="nav($id='nav5')" role="tab"><span class="badge bg-danger">{{0}}</span> Cancel</a></li>
                    <li class="nav-item" id="nav7"><a class="nav-link" data-bs-toggle="tab" href="#nav-Complete"  onclick="nav($id='nav6')"role="tab"><span class="badge "style="background-color:#2C7F7A" >{{0}}</span> Complete</a></li>
                </ul>
                <div class="card p-4 mb-4">
                    <div class="tab-content">
                        <div class="tab-pane fade  show active" id="nav-Dummy" role="tabpanel" rel="0">
                            <div style="min-height: 70vh;" class="mt-2">
                                <table id="invoiceTable" class="table-together table-style">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;"data-priority="1">No</th>
                                            <th data-priority="1">Proposal ID</th>
                                            <th data-priority="1">Company / Individual</th>
                                            <th>PI Doc.</th>
                                            <th class="text-center">PD Amount</th>
                                            <th class="text-center">PI Amount</th>
                                            <th class="text-center">Balance</th>
                                            <th class="text-center">Status</th>
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
                                                <td>{{ $item->Quotation_ID}}</td>
                                                @if ($item->type_Proposal == 'Company')
                                                    <td style="text-align: left;">{{ @$item->company->Company_Name}}</td>
                                                @else
                                                    <td style="text-align: left;">{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                @endif
                                                <td>{{ $item->invoice_count }}</td>
                                                <td style="text-align: center;">
                                                    {{ number_format($item->Nettotal + $item->Adtotal, 2) }}
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($item->total_payment == 0 )
                                                        0
                                                    @else
                                                        {{ number_format($item->total_payment, 2) }}
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    {{ number_format($item->Nettotal+ $item->Adtotal - $item->total_payment, 2) }}
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($item->invoice_count == 0)
                                                        <span class="badge rounded-pill "style="background-color: #64748b">Create Invoice</span>
                                                    @else
                                                        <span class="badge rounded-pill "style="background-color: #64748b">Pending</span>
                                                    @endif

                                                </td>
                                                @php
                                                    $CreateBy = Auth::user()->id;
                                                    $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                    $canViewProposal = @Auth::user()->roleMenuView('Proforma Invoice', Auth::user()->id);
                                                    $canEditProposal = @Auth::user()->roleMenuEdit('Proforma Invoice', Auth::user()->id);
                                                @endphp

                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            @if ($rolePermission > 0)
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/view/list/'.$item->id) }}">View Invoice</a></li>
                                                                @endif

                                                                @if (($rolePermission == 1 || ($rolePermission == 2 && $item->Operated_by == $CreateBy)) && $canEditProposal == 1)
                                                                    @if(!empty($Pending) && $Pending->count() == 0)
                                                                        @if ($item->Nettotal - $item->total_payment != 0 && $item->Nettotal + $item->Adtotal - $item->total_payment != 0)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/Generate/'.$item->id) }}">Create</a></li>
                                                                        @endif
                                                                        @if ($item->invoice_count == 0)
                                                                            <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                        @endif
                                                                    @else
                                                                        @php
                                                                            $hasStatusReceiveZero = false;
                                                                        @endphp

                                                                        @foreach ($invoicecheck as $key2 => $item2)
                                                                            @if ($item->QID == $item2->Quotation_ID && $item2->status_receive == 0)
                                                                                @php
                                                                                    $hasStatusReceiveZero = true;
                                                                                    break; // หยุดการลูปทันทีเมื่อพบเงื่อนไขที่ต้องการ
                                                                                @endphp
                                                                            @endif
                                                                        @endforeach

                                                                        @if (!$hasStatusReceiveZero && $item->Nettotal - $item->total_payment != 0 )
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/Generate/'.$item->id) }}">Create</a></li>
                                                                        @endif
                                                                    @endif
                                                                @elseif ($rolePermission == 2 || $rolePermission == 3 && $canEditProposal == 1)
                                                                    @if(!empty($Pending) && $Pending->count() == 0)
                                                                        @if ($item->Nettotal - $item->total_payment != 0 && $item->Nettotal + $item->Adtotal - $item->total_payment != 0)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/Generate/'.$item->id) }}">Create</a></li>
                                                                        @endif
                                                                        @if ($item->invoice_count == 0)
                                                                            <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                        @endif
                                                                    @else
                                                                        @php
                                                                            $hasStatusReceiveZero = false;
                                                                        @endphp

                                                                        @foreach ($invoicecheck as $key2 => $item2)
                                                                            @if ($item->QID == $item2->Quotation_ID && $item2->status_receive == 0 )
                                                                                @php
                                                                                    $hasStatusReceiveZero = true;
                                                                                    break; // หยุดการลูปทันทีเมื่อพบเงื่อนไขที่ต้องการ
                                                                                @endphp
                                                                            @endif
                                                                        @endforeach

                                                                        @if (!$hasStatusReceiveZero  && $item->Nettotal - $item->total_payment != 0 )
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/Generate/'.$item->id) }}">Create</a></li>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @else
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/view/list/'.$item->id) }}">View Invoice</a></li>
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
                        <div class="tab-pane fade" id="nav-all" role="tabpanel" rel="0">
                            <div style="min-height: 70vh;" >

                                <table id="allTable" class="table-together table-style">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th data-priority="1">Invoice ID</th>
                                            <th data-priority="1">Proposal ID</th>
                                            <th data-priority="1">Company / Individual</th>
                                            <th class="text-center">Issue Date</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Operated By</th>
                                            <th class="text-center">Document status</th>
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
                                                <td>{{ $item->Invoice_ID}}</td>
                                                <td>{{ $item->Quotation_ID}}</td>
                                                @if ($item->type_Proposal == 'Company')
                                                    <td>{{ @$item->company00->Company_Name}}</td>
                                                @else
                                                    <td>{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                @endif
                                                <td style="text-align: center;">{{ $item->IssueDate }}</td>

                                                <td style="text-align: center;">
                                                    {{ number_format($item->sumpayment, 2) }}
                                                </td>
                                                <td style="text-align: center;">
                                                    @if (@$item->userOperated->name == null)
                                                        Auto
                                                    @else
                                                        {{ @$item->userOperated->name }}
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($item->document_status == 1)
                                                        <span class="badge rounded-pill "style="background-color: #FF6633">Pending</span>
                                                    @elseif ($item->document_status == 2)
                                                        <span class="badge rounded-pill " style="background-color: #0ea5e9">Generate</span>
                                                    @elseif ($item->document_status == 0)
                                                        <span class="badge rounded-pill  bg-danger" >Cancel</span>
                                                    @endif
                                                </td>
                                                @php
                                                    $CreateBy = Auth::user()->id;
                                                    $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                    $canViewProposal = @Auth::user()->roleMenuView('Proforma Invoice', Auth::user()->id);
                                                    $canEditProposal = @Auth::user()->roleMenuEdit('Proforma Invoice', Auth::user()->id);
                                                @endphp
                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            @if ($canViewProposal == 1)
                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/view/'.$item->id) }}">View</a></li>
                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/view/LOG/'.$item->id) }}">LOG</a></li>
                                                            @endif
                                                            @if ($rolePermission > 0)
                                                                @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                    @if ($canEditProposal == 1)
                                                                        @if ($item->document_status == 0)
                                                                            <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Revise({{ $item->id }})">Revise</a></li>
                                                                        @endif
                                                                        @if ($item->document_status == 1)
                                                                            <li><a class="dropdown-item py-2 rounded"  href="{{ url('/Document/invoice/revised/'.$item->id) }}">Edit</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/Generate/to/Re/'.$item->id) }}">Generate</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/viewinvoice/'.$item->id) }}">Send Email</a></li>
                                                                        @endif
                                                                        @if ($item->document_status == 2)
                                                                            <li><a class="dropdown-item py-2 rounded"  href="{{ url('/Document/invoice/revised/'.$item->id) }}">Edit</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/viewinvoice/'.$item->id) }}">Send Email</a></li>
                                                                        @endif
                                                                    @endif
                                                                @elseif ($rolePermission == 2)
                                                                    @if ($item->Operated_by == $CreateBy)
                                                                        @if ($canEditProposal == 1)
                                                                            @if ($item->document_status == 0)
                                                                                <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Revise({{ $item->id }})">Revise</a></li>
                                                                            @endif
                                                                            @if ($item->document_status == 1)
                                                                                <li><a class="dropdown-item py-2 rounded"  href="{{ url('/Document/invoice/revised/'.$item->id) }}">Edit</a></li>
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/Generate/to/Re/'.$item->id) }}">Generate</a></li>
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/viewinvoice/'.$item->id) }}">Send Email</a></li>
                                                                            @endif
                                                                            @if ($item->document_status == 2)
                                                                                <li><a class="dropdown-item py-2 rounded"  href="{{ url('/Document/invoice/revised/'.$item->id) }}">Edit</a></li>
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/viewinvoice/'.$item->id) }}">Send Email</a></li>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                @elseif ($rolePermission == 3)
                                                                    @if ($canEditProposal == 1)
                                                                        @if ($item->document_status == 0)
                                                                            <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Revise({{ $item->id }})">Revise</a></li>
                                                                        @endif
                                                                        @if ($item->document_status == 1)
                                                                            <li><a class="dropdown-item py-2 rounded"  href="{{ url('/Document/invoice/revised/'.$item->id) }}">Edit</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/Generate/to/Re/'.$item->id) }}">Generate</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/viewinvoice/'.$item->id) }}">Send Email</a></li>
                                                                        @endif
                                                                        @if ($item->document_status == 2)
                                                                            <li><a class="dropdown-item py-2 rounded"  href="{{ url('/Document/invoice/revised/'.$item->id) }}">Edit</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/viewinvoice/'.$item->id) }}">Send Email</a></li>
                                                                        @endif
                                                                        <li><a class="dropdown-item py-2 rounded" onclick="Delete({{$item->id}})">Cancel</a></li>
                                                                    @endif
                                                                @endif
                                                            @else
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/view/'.$item->id) }}">View</a></li>
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/view/LOG/'.$item->id) }}">LOG</a></li>
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
                            <div style="min-height: 70vh;" >

                                <table id="PendingTable" class="table-together table-style">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th data-priority="1">Invoice ID</th>
                                            <th data-priority="1">Proposal ID</th>
                                            <th data-priority="1">Company / Individual</th>
                                            <th class="text-center">Issue Date</th>

                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Operated By</th>
                                            <th class="text-center">Document status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($Pending))
                                            @foreach ($Pending as $key => $item)
                                            <tr>
                                                <td style="text-align: center;">
                                                {{$key +1}}
                                                </td>
                                                <td>{{ $item->Invoice_ID}}</td>
                                                <td>{{ $item->Quotation_ID}}</td>
                                                @if ($item->type_Proposal == 'Company')
                                                    <td>{{ @$item->company00->Company_Name}}</td>
                                                @else
                                                    <td>{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                @endif
                                                <td style="text-align: center;">{{ $item->IssueDate }}</td>

                                                <td style="text-align: center;">
                                                    {{ number_format($item->sumpayment, 2) }}
                                                </td>
                                                <td style="text-align: center;">
                                                    @if (@$item->userOperated->name == null)
                                                        Auto
                                                    @else
                                                        {{ @$item->userOperated->name }}
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    <span class="badge rounded-pill "style="background-color: #FF6633">Pending</span>
                                                </td>
                                                @php
                                                    $CreateBy = Auth::user()->id;
                                                    $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                    $canViewProposal = @Auth::user()->roleMenuView('Proforma Invoice', Auth::user()->id);
                                                    $canEditProposal = @Auth::user()->roleMenuEdit('Proforma Invoice', Auth::user()->id);
                                                @endphp
                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            @if ($canViewProposal == 1)
                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/view/'.$item->id) }}">View</a></li>
                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/view/LOG/'.$item->id) }}">LOG</a></li>
                                                            @endif
                                                            @if ($rolePermission > 0)
                                                                @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                    @if ($canEditProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded"  href="{{ url('/Document/invoice/revised/'.$item->id) }}">Edit</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/Generate/to/Re/'.$item->id) }}">Generate</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/viewinvoice/'.$item->id) }}">Send Email</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" onclick="Delete({{$item->id}})">Cancel</a></li>
                                                                    @endif
                                                                @elseif ($rolePermission == 2)
                                                                    @if ($item->Operated_by == $CreateBy)
                                                                        @if ($canEditProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded"  href="{{ url('/Document/invoice/revised/'.$item->id) }}">Edit</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/Generate/to/Re/'.$item->id) }}">Generate</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/viewinvoice/'.$item->id) }}">Send Email</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" onclick="Delete({{$item->id}})">Cancel</a></li>
                                                                        @endif
                                                                    @endif
                                                                @elseif ($rolePermission == 3)
                                                                    @if ($canEditProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded"  href="{{ url('/Document/invoice/revised/'.$item->id) }}">Edit</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/Generate/to/Re/'.$item->id) }}">Generate</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/viewinvoice/'.$item->id) }}">Send Email</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" onclick="Delete({{$item->id}})">Cancel</a></li>
                                                                    @endif
                                                                @endif
                                                            @else
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/view/'.$item->id) }}">View</a></li>
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/view/LOG/'.$item->id) }}">LOG</a></li>
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

                                <table id="ApprovedTable" class="table-together table-style">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th data-priority="1">Invoice ID</th>
                                            <th data-priority="1">Proposal ID</th>
                                            <th data-priority="1">Company / Individual</th>
                                            <th class="text-center">Issue Date</th>

                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Operated By</th>
                                            <th class="text-center">Document status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($Generate))
                                            @foreach ($Generate as $key => $item)
                                            <tr>
                                                <td style="text-align: center;">
                                                {{$key +1}}
                                                </td>
                                                <td>{{ $item->Invoice_ID}}</td>
                                                <td>{{ $item->Quotation_ID}}</td>
                                                @if ($item->type_Proposal == 'Company')
                                                    <td>{{ @$item->company00->Company_Name}}</td>
                                                @else
                                                    <td>{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                @endif
                                                <td style="text-align: center;">{{ $item->IssueDate }}</td>

                                                <td style="text-align: center;"> {{ number_format($item->sumpayment , 2) }}</td>
                                                <td style="text-align: center;">
                                                    @if (@$item->userOperated->name == null)
                                                        Auto
                                                    @else
                                                        {{ @$item->userOperated->name }}
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    <span class="badge rounded-pill " style="background-color: #0ea5e9">Generate</span>
                                                </td>
                                                @php
                                                    $CreateBy = Auth::user()->id;
                                                    $rolePermission = @Auth::user()->rolePermission(Auth::user()->id);
                                                    $canViewProposal = @Auth::user()->roleMenuView('Proforma Invoice', Auth::user()->id);
                                                    $canEditProposal = @Auth::user()->roleMenuEdit('Proforma Invoice', Auth::user()->id);
                                                @endphp
                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/view/'.$item->id) }}">View</a></li>
                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/view/LOG/'.$item->id) }}">LOG</a></li>
                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/viewinvoice/'.$item->id) }}">Send Email</a></li>
                                                            @if ($canEditProposal == 1)
                                                                <li><a class="dropdown-item py-2 rounded" onclick="Delete({{$item->id}})">Cancel</a></li>
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
                        <div class="tab-pane fade "id="nav-Complete" role="tabpanel" rel="0">
                            <div style="min-height: 70vh;" class="mt-2">
                                <table id="CompleteTable" class="table-together table-style">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th data-priority="1">Invoice ID</th>
                                            <th data-priority="1">Proposal ID</th>
                                            <th data-priority="1">Company / Individual</th>
                                            <th class="text-center">Issue Date</th>

                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Operated By</th>
                                            <th class="text-center">Document status</th>
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
                                                <td>{{ $item->Invoice_ID}}</td>
                                                <td>{{ $item->Quotation_ID}}</td>
                                                @if ($item->type_Proposal == 'Company')
                                                    <td>{{ @$item->company00->Company_Name}}</td>
                                                @else
                                                    <td>{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                @endif
                                                <td style="text-align: center;">{{ $item->IssueDate }}</td>

                                                <td style="text-align: center;"> {{ number_format($item->sumpayment , 2) }}</td>
                                                <td style="text-align: center;">
                                                    @if (@$item->userOperated->name == null)
                                                        Auto
                                                    @else
                                                        {{ @$item->userOperated->name }}
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    <span class="badge rounded-pill " style="background-color: #2C7F7A">Complete</span>
                                                </td>
                                                @php
                                                    $CreateBy = Auth::user()->id;
                                                    $rolePermission = @Auth::user()->rolePermission(Auth::user()->id);
                                                    $canViewProposal = @Auth::user()->roleMenuView('Proforma Invoice', Auth::user()->id);
                                                    $canEditProposal = @Auth::user()->roleMenuEdit('Proforma Invoice', Auth::user()->id);
                                                @endphp
                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/view/'.$item->id) }}">View</a></li>
                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/view/LOG/'.$item->id) }}">LOG</a></li>
                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/viewinvoice/'.$item->id) }}">Send Email</a></li>
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
                            <div style="min-height: 70vh;" >

                                <table id="CancelTable" class="table-together table-style">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th data-priority="1">Invoice ID</th>
                                            <th data-priority="1">Proposal ID</th>
                                            <th data-priority="1">Company / Individual</th>
                                            <th class="text-center">Issue Date</th>

                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Operated By</th>
                                            <th class="text-center">Document status</th>
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
                                                <td>{{ $item->Invoice_ID}}</td>
                                                <td>{{ $item->Quotation_ID}}</td>
                                                @if ($item->type_Proposal == 'Company')
                                                    <td>{{ @$item->company00->Company_Name}}</td>
                                                @else
                                                    <td>{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                @endif
                                                <td style="text-align: center;">{{ $item->IssueDate }}</td>

                                                <td style="text-align: center;">
                                                    {{ number_format($item->sumpayment, 2) }}
                                                </td>
                                                <td style="text-align: center;">
                                                    @if (@$item->userOperated->name == null)
                                                        Auto
                                                    @else
                                                        {{ @$item->userOperated->name }}
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    <span class="badge rounded-pill bg-danger">Cancel</span>
                                                </td>
                                                @php
                                                    $CreateBy = Auth::user()->id;
                                                    $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                    $canViewProposal = @Auth::user()->roleMenuView('Proforma Invoice', Auth::user()->id);
                                                    $canEditProposal = @Auth::user()->roleMenuEdit('Proforma Invoice', Auth::user()->id);
                                                @endphp
                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            @if ($canViewProposal == 1)
                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/view/'.$item->id) }}">View</a></li>
                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/view/LOG/'.$item->id) }}">LOG</a></li>
                                                            @endif
                                                            @if ($canEditProposal == 1)
                                                                <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Revise({{ $item->id }})">Revise</a></li>
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
        function Delete(id){
            Swal.fire({
            title: "Do you want to cancel this item?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "Cancel",
            confirmButtonColor: "#2C7F7A",
            dangerMode: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ url('/Document/invoice/delete/') }}/" + id;
                }
            });
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
                    window.location.href = "{{ url('/Document/invoice/Revise/') }}/" + id;
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
                    form.action = `{{ url('/Document/invoice/cancel/') }}/${id}`;
                    $('#myModal').modal('show'); // เปิดโมดอล
                }
            });
        }

        // $(document).ready(function () {
        //     $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        //         var targetTab = $(e.target).attr('href'); // ดึงค่า href ของแท็บที่คลิก
        //         reloadTable(targetTab); // เรียกฟังก์ชันโหลดข้อมูลใหม่
        //         // setTimeout(function () {
        //         //     if ($.fn.DataTable.isDataTable(targetTab + ' table')) {
        //         //         // $(targetTab + ' table').DataTable().columns.adjust().draw();
        //         //     }
        //         // }, 200);

        //     });
        //     // function hideLabel() {
        //     //     // เปลี่ยน placeholder สำหรับฟิลด์ค้นหาทั้งหมดในทุก DataTable
        //     //     $('input[type="search"]').each(function () {
        //     //         $(this).attr("placeholder", "Type to search...");
        //     //         var searchID = $(this).attr('id');
        //     //         var text = searchID.split('-');
        //     //         var number = text[2];

        //     function hideLabel() {
        //         // เปลี่ยน placeholder สำหรับฟิลด์ค้นหาทั้งหมดในทุก DataTable
        //         $('input[type="search"]').each(function () {
        //             $(this).attr("placeholder", "Type to search...");
        //             var searchID = $(this).attr('id');
        //             var text = searchID.split('-');
        //             var number = text[2];

        //             $('label[for="dt-length-'+ number +'"], label[for="'+ searchID +'"]').hide();

        //         });

        //         $(window).on("resize", function () {
        //             $.fn.dataTable
        //             .tables({ visible: true, api: true })
        //             .columns.adjust()
        //             .responsive.recalc();
        //         });
        //     }

        //     function initializeDataTable(tableId, url, columns) {
        //         // if ($.fn.DataTable.isDataTable(tableId)) {
        //         //     // $(tableId).DataTable().clear().destroy(); // ล้าง DataTable เก่าก่อนโหลดใหม่
        //         // }
        //         $(tableId).dataTable({
        //             processing: true,
        //             serverSide: true,
        //             searching: true,
        //             // paging: true,
        //             destroy: true,
        //             info: true,
        //             ajax: {
        //                 url: url,
        //                 method: 'GET',
        //                 dataSrc: function (json) {
        //                     return json.data;
        //                 }
        //             },
        //             columns: columns,
        //             responsive: true, // รองรับการเปลี่ยนขนาดอัตโนมัติ
        //             info: true,
        //             // autoWidth: false  ,  // ป้องกันตารางกว้างผิดปกติ
        //             // dom: '<"top"l>rt<"bottom"ip><"clear">', // กำหนดโครงสร้างของ DOM ของ DataTable
        //             // className: 'table-together table-style'
        //         });

        //         hideLabel();
        //     }

        //     function reloadTable(target) {
        //         console.log(1);

        //         if (target === '#nav-Dummy') {

        //             initializeDataTable('#invoiceTable', '/invoice/get/proposal', [
        //                 { data: 'no', title: 'No' },
        //                 { data: 'quotation_id', title: 'Proposal ID' },
        //                 { data: 'company_name', title: 'Company / Individual' },
        //                 { data: 'pi_doc', title: 'PI Doc.' },
        //                 { data: 'pd_amount', title: 'PD Amount' },
        //                 { data: 'pi_amount', title: 'PI Amount' },
        //                 { data: 'balance', title: 'Balance' },
        //                 { data: 'status', title: 'Status' },
        //                 { data: 'action', title: 'Action' }
        //             ]);

        //         } else if (target === '#nav-all') {
        //             initializeDataTable('#allTable', '/invoice/get/allTable', [
        //                 { data: 'no', title: 'No' },
        //                 { data: 'invoice_id', title: 'Invoice ID' },
        //                 { data: 'quotation_id', title: 'Proposal ID' },
        //                 { data: 'company_name', title: 'Company / Individual' },
        //                 { data: 'pi_doc', title: 'Issue Date' },
        //                 { data: 'pd_amount', title: 'Amount' },
        //                 { data: 'pi_amount', title: 'Operated By' },
        //                 { data: 'status', title: 'Document status' },
        //                 { data: 'action', title: 'Action' }
        //             ]);

        //         } else if (target === '#nav-Pending') {
        //             initializeDataTable('#PendingTable', '/invoice/get/PendingTable', [
        //                 { data: 'no', title: 'No' },
        //                 { data: 'invoice_id', title: 'Invoice ID' },
        //                 { data: 'quotation_id', title: 'Proposal ID' },
        //                 { data: 'company_name', title: 'Company / Individual' },
        //                 { data: 'pi_doc', title: 'Issue Date' },
        //                 { data: 'pd_amount', title: 'Amount' },
        //                 { data: 'pi_amount', title: 'Operated By' },
        //                 { data: 'status', title: 'Document status' },
        //                 { data: 'action', title: 'Action' }
        //             ]);
        //         } else if (target === '#nav-Approved') {
        //             initializeDataTable('#ApprovedTable', '/invoice/get/ApprovedTable', [
        //                 { data: 'no', title: 'No' },
        //                 { data: 'invoice_id', title: 'Invoice ID' },
        //                 { data: 'quotation_id', title: 'Proposal ID' },
        //                 { data: 'company_name', title: 'Company / Individual' },
        //                 { data: 'pi_doc', title: 'Issue Date' },
        //                 { data: 'pd_amount', title: 'Amount' },
        //                 { data: 'pi_amount', title: 'Operated By' },
        //                 { data: 'status', title: 'Document status' },
        //                 { data: 'action', title: 'Action' }
        //             ]);
        //         } else if (target === '#nav-Complete') {
        //             initializeDataTable('#CompleteTable', '/invoice/get/CompleteTable', [
        //                 { data: 'no', title: 'No' },
        //                 { data: 'invoice_id', title: 'Invoice ID' },
        //                 { data: 'quotation_id', title: 'Proposal ID' },
        //                 { data: 'company_name', title: 'Company / Individual' },
        //                 { data: 'pi_doc', title: 'Issue Date' },
        //                 { data: 'pd_amount', title: 'Amount' },
        //                 { data: 'pi_amount', title: 'Operated By' },
        //                 { data: 'status', title: 'Document status' },
        //                 { data: 'action', title: 'Action' }
        //             ]);
        //         }
        //         else if (target === '#nav-Cancel') {
        //             initializeDataTable('#CancelTable', '/invoice/get/CancelTable', [
        //                 { data: 'no', title: 'No' },
        //                 { data: 'invoice_id', title: 'Invoice ID' },
        //                 { data: 'quotation_id', title: 'Proposal ID' },
        //                 { data: 'company_name', title: 'Company / Individual' },
        //                 { data: 'pi_doc', title: 'Issue Date' },
        //                 { data: 'pd_amount', title: 'Amount' },
        //                 { data: 'pi_amount', title: 'Operated By' },
        //                 { data: 'status', title: 'Document status' },
        //                 { data: 'action', title: 'Action' }
        //             ]);
        //         }
        //     }
        // });

    </script>
@endsection
