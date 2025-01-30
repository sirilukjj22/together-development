@extends('layouts.masterLayout')
@php
    $excludeDatatable = false;
@endphp
@section('content')

    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Proposal Request </div>
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
                <div class="col-md-12 col-12">
                    <ul class="nav nav-tabs px-3 border-bottom-0" role="tablist">
                        <li class="nav-item" id="nav1"><a class="nav-link active" data-bs-toggle="tab" href="#nav-proposal" role="tab" onclick="nav($id='nav1')"><span class="badge" style="background-color:#64748b">{{$proposalcount}}</span> Proposal Request</a></li>{{--ประวัติการแก้ไข--}}
                        {{-- <li class="nav-item" id="nav2"><a class="nav-link " data-bs-toggle="tab" href="#nav-Pending" onclick="nav($id='nav2')" role="tab"><span class="badge" style="background-color:#FF6633">{{$requestcount}}</span> Request OverBill</a></li>QUOTAION --}}
                        <li class="nav-item" id="nav3"><a class="nav-link" data-bs-toggle="tab" href="#nav-Awaiting" onclick="nav($id='nav3')" role="tab"><span class="badge bg-warning" >{{$Additionalcount}}</span> Additional</a></li>{{--เอกสารออกบิล--}}
                    </ul>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade  show active" id="nav-proposal" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" class="mt-2">
                                        <div class="flex-end">
                                            <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ route('ProposalReq.log') }}'">LOG</button>
                                        </div>
                                        <table id="proposalTable" class="table-together table-style">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"data-priority="1">No</th>
                                                    <th>Company / Individual</th>
                                                    <th class="text-center" data-priority="1">Proposal Type</th>
                                                    <th class="text-center" data-priority="1">Operated by</th>
                                                    <th class="text-center">Count</th>
                                                    <th class="text-center">Document status</th>
                                                    <th class="text-center">Order</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($proposal))
                                                    @foreach ($proposal as $key => $item)
                                                    <tr>
                                                        <td style="text-align: center;">
                                                            {{$key +1}}
                                                        </td>
                                                        @if ($item->type_Proposal == 'Company')
                                                            <td>{{ @$item->company->Company_Name}}</td>
                                                        @else
                                                            <td>{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                        @endif
                                                        <td style="text-align: center;">{{$item->QuotationType}}</td>
                                                        <td style="text-align: center;">{{ @$item->userOperated->name }}</td>
                                                        <td style="text-align: center;">{{ $item->COUNTDummyNo }}</td>
                                                        <td style="text-align: center;"><span class="badge rounded-pill bg-warning">Awaiting Approval</span></td>
                                                        <td style="text-align: center;">
                                                            <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ url('/Dummy/Proposal/Request/document/view/'.$item->Company_ID.'/'.$item->QuotationType.'/'.$item->Operated_by) }}'">
                                                                <i class="fa fa-folder-open-o"></i> View
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                                {{-- <div class="tab-pane fade" id="nav-Pending" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" class="mt-2">
                                        <table id="requestTable" class="table-together table-style">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"data-priority="1">No</th>
                                                    <th>Name request</th>
                                                    <th class="text-center" data-priority="1">Expiration Time</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($request))
                                                    @foreach ($request as $key => $item)
                                                    <tr>
                                                        <td style="text-align: center;">
                                                            {{$key +1}}
                                                        </td>
                                                        <td>{{ @$item->requestername->name}}</td>
                                                        <td style="text-align: center;">{{$item->expiration_time	}}</td>
                                                        <td style="text-align: center;"> <span class="badge rounded-pill "style="background-color: #FF6633	">Pending</span></td>
                                                        <td style="text-align: center;">
                                                            <button type="button" class="btn btn-light-success btn-sm" value="{{ $item->id }}" onclick="btnConfirm({{ $item->id }})">Confirm</button>
                                                            <button type="button" class="btn btn-light-danger btn-sm" value="{{ $item->id }}" onclick="btnCancel({{ $item->id }})">Cancel</button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>

                                    </div>
                                </div> --}}
                                <div class="tab-pane fade" id="nav-Awaiting" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" class="mt-2">
                                        <div class="flex-end">
                                            <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ route('ProposalReq.LogAdditional') }}'">LOG</button>
                                        </div>
                                        <table id="proposalAwaitingTable" class="table-together table-style">
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
                                                @if(!empty($Additional))
                                                    @foreach ($Additional as $key => $item)
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
                                                            @endphp
                                                            <td style="text-align: center;">
                                                                <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ url('/Proposal/request/document/Additional/view/'.$item->id) }}'">
                                                                    <i class="fa fa-folder-open-o"></i> View
                                                                </button>
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
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <!-- dataTable -->
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.semanticui.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.semanticui.js"></script>
    <script src="{{ asset('assets/js/table-together.js') }}"></script>

    @include('script.script')
    <script>
        function btnConfirm(id) {
            jQuery.ajax({
                type: "GET",
                url: "{!! url('/Proposal-request/confirm-request/" + id + "') !!}",
                datatype: "JSON",
                async: false,
                success: function(result) {
                    Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                    location.reload();
                },
            });
        }
        function nav(id) {
            $.fn.dataTable
            .tables({ visible: true, api: true })
            .columns.adjust()
            .responsive.recalc();
        }
        function btnCancel(id) {
            jQuery.ajax({
                type: "GET",
                url: "{!! url('/Proposal-request/Cancel-request/" + id + "') !!}",
                datatype: "JSON",
                async: false,
                success: function(result) {
                    Swal.fire('บันทึกข้อมูลเรียบร้อย!', '', 'success');
                    location.reload();
                },
            });
        }
    </script>

@endsection
