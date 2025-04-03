@extends('layouts.masterLayout')
@php
    $excludeDatatable = false;
@endphp
@section('content')
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Proposal</div>
                </div>
                <div class="col-auto">
                    @if (@Auth::user()->roleMenuAdd('proposal',Auth::user()->id) == 1)
                    <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ route('Proposal.create') }}'">
                        <i class="fa fa-plus"></i> เพิ่มใบเสนอราคา</button>
                    @endif
                    <button type="button" class="btn btn-color-green lift btn_modal" data-bs-toggle="modal" data-bs-target="#allSearch">
                        <i class="fa fa-reorder"></i> Filter</button>
                    <div class="col-md-12 my-2">
                        <div class="modal fade" id="allSearch" tabindex="-1" aria-labelledby="PrenameModalCenterTitle"
                        style="display: none;" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="PrenameModalCenterTitle"><i class="fa fa-reorder"></i> Filter</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-12">
                                            <div class="card-body">
                                                <form action="{{route('Proposal.Search')}}" method="GET" enctype="multipart/form-data" class="row g-3 basic-form">
                                                    @csrf
                                                    <div class="col-sm-12 col-12">
                                                        <label for="Status">ตัวเลือก</label>
                                                        <select name="Filter" id="Filter" class="form-select" >
                                                            <option value=" "selected disabled>ตัวเลือก</option>
                                                            <option value="All">ทั้งหมด</option>
                                                            <option value="Nocheckin">No Check in date</option>
                                                            <option value="Checkin">Check in & Check out</option>
                                                            <option value="Month">Month / Year</option>
                                                            <option value="Company">Company / Individual</option>
                                                        </select>
                                                    </div>
                                                    <div id="inputcompany" class="col-sm-12 col-12" style="display: none">
                                                        <label for="checkin">Company / Individual</label><br>
                                                        <input type="text" name="inputcompanyindividual" id="inputcompanyindividual" class="form-control" required>
                                                    </div>
                                                    <div id="checkin" class="col-sm-6 col-12" style="display: none">
                                                        <label for="checkin">Check-in Date</label><br>
                                                        <input type="text" name="checkin" id="checkinput" class="form-control" required>
                                                    </div>
                                                    <div  id="checkout" class="col-sm-6 col-12" style="display: none">
                                                        <label for="checkin">Check-out Date</label><br>
                                                        <input type="text" name="checkout" id="checkinout" class="form-control" required>
                                                    </div>
                                                    <div  id="Month" class="col-lg-12 col-sm-12" style="display: none">
                                                        <div class="row">
                                                            <div class="col-lg-6 col-sm-12 ">
                                                                <label for="month">เลือกเดือน:</label>
                                                                <select class="select2" id="month" name="month">
                                                                    <option value="01">มกราคม</option>
                                                                    <option value="02">กุมภาพันธ์</option>
                                                                    <option value="03">มีนาคม</option>
                                                                    <option value="04">เมษายน</option>
                                                                    <option value="05">พฤษภาคม</option>
                                                                    <option value="06">มิถุนายน</option>
                                                                    <option value="07">กรกฎาคม</option>
                                                                    <option value="08">สิงหาคม</option>
                                                                    <option value="09">กันยายน</option>
                                                                    <option value="10">ตุลาคม</option>
                                                                    <option value="11">พฤศจิกายน</option>
                                                                    <option value="12">ธันวาคม</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-6 col-sm-12">
                                                                <label for="year">เลือกปี:</label>
                                                                <select class="select2" id="year" name="year">
                                                                    @for ($i = $oldestYear; $i <= $newestYear ; $i++)
                                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div id="User"  class="col-sm-6 col-12" style="display: block">
                                                        <label for="User">User</label>
                                                        <select name="User" class="form-select">
                                                            <option value="" selected disabled>ชื่อผู้ใช้งาน</option>
                                                            @foreach($User as $item)
                                                                <option value="{{ $item->id }}">{{ @$item->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div id="status" class="col-sm-6 col-12"style="display: block">
                                                        <label for="Status">Status</label>
                                                        <select name="status"  class="form-select" >
                                                            <option value=" "selected disabled>สถานะเอกสาร</option>
                                                            <option value="1">Pending</option>
                                                            <option value="2">Awaiting Approval</option>
                                                            <option value="3">Approved</option>
                                                            <option value="6">Generate</option>
                                                            <option value="4">Reject</option>
                                                            <option value="0">Cancel</option>
                                                            <option value="5">No Show</option>
                                                            <option value="9">Complete</option>
                                                        </select>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary lift" data-bs-dismiss="modal">ยกเลิก</button>
                                                        <button type="submit" class="btn btn-color-green lift" id="btn-save">ค้นหา</button>
                                                    </div>
                                                </form>

                                                <script>
                                                    $(function() {
                                                        // ฟอร์แมตวันที่ให้อยู่ในรูปแบบ dd/mm/yyyy
                                                        $('#checkinput').daterangepicker({
                                                            singleDatePicker: true,
                                                            showDropdowns: true,
                                                            autoUpdateInput: false,
                                                            autoApply: true,

                                                            locale: {
                                                                format: 'DD/MM/YYYY' // ฟอร์แมตเป็น dd/mm/yyyy
                                                            }
                                                        });
                                                        $('#checkinput').on('apply.daterangepicker', function(ev, picker) {
                                                            $(this).val(picker.startDate.format('DD/MM/YYYY'));

                                                        });
                                                    });
                                                    $(function() {
                                                        // ฟอร์แมตวันที่ให้อยู่ในรูปแบบ dd/mm/yyyy
                                                        $('#checkinout').daterangepicker({
                                                            singleDatePicker: true,
                                                            showDropdowns: true,
                                                            autoUpdateInput: false,
                                                            autoApply: true,

                                                            locale: {
                                                                format: 'DD/MM/YYYY' // ฟอร์แมตเป็น dd/mm/yyyy
                                                            }
                                                        });
                                                        $('#checkinout').on('apply.daterangepicker', function(ev, picker) {
                                                            $(this).val(picker.startDate.format('DD/MM/YYYY'));

                                                        });
                                                    });
                                                    document.getElementById('Filter').addEventListener('change', function() {
                                                        const selectedValue = this.value;
                                                        // ทำสิ่งที่คุณต้องการเมื่อมีการเปลี่ยนแปลง
                                                        console.log('Selected filter:', selectedValue);
                                                        const checkinDiv = document.getElementById('checkin');
                                                        const checkoutDiv = document.getElementById('checkout');
                                                        const Month = document.getElementById('Month');
                                                        const status = document.getElementById('status');
                                                        const User = document.getElementById('User');
                                                        const checkinput = document.getElementById('checkinput');
                                                        const checkinout = document.getElementById('checkinout');
                                                        const inputcompany = document.getElementById('inputcompany');
                                                        const inputcompanyindividual = document.getElementById('inputcompanyindividual');
                                                        inputcompanyindividual.disabled = true;
                                                        checkinput.disabled = true;
                                                        checkinout.disabled = true;
                                                        if (selectedValue === 'All') {
                                                            checkinDiv.style.display = 'none';
                                                            checkoutDiv.style.display = 'none';
                                                            User.style.display = 'none';
                                                            status.style.display = 'none';
                                                            Month.style.display = 'none';
                                                            checkinput.disabled = true;
                                                            checkinout.disabled = true;
                                                            inputcompanyindividual.disabled = true;
                                                        } else if (selectedValue === 'Nocheckin') {
                                                            checkinDiv.style.display = 'none';
                                                            checkoutDiv.style.display = 'none';
                                                            User.style.display = 'block';
                                                            status.style.display = 'block';
                                                            Month.style.display = 'none';
                                                            checkinput.disabled = true;
                                                            checkinout.disabled = true;
                                                            inputcompanyindividual.disabled = true;
                                                        } else if (selectedValue === 'Checkin') {
                                                            checkinDiv.style.display = 'block';
                                                            checkoutDiv.style.display = 'block';
                                                            User.style.display = 'block';
                                                            status.style.display = 'block';
                                                            Month.style.display = 'none';
                                                            checkinput.disabled = false;
                                                            checkinout.disabled = false;
                                                            inputcompanyindividual.disabled = true;
                                                        }else if (selectedValue === 'Company') {
                                                            checkinDiv.style.display = 'none';
                                                            checkoutDiv.style.display = 'none';
                                                            User.style.display = 'none';
                                                            status.style.display = 'none';
                                                            Month.style.display = 'none';
                                                            checkinput.disabled = true;
                                                            checkinout.disabled = true;
                                                            inputcompany.style.display = 'block';
                                                        }else if (selectedValue === 'Month') {
                                                            checkinDiv.style.display = 'none';
                                                            checkoutDiv.style.display = 'none';
                                                            User.style.display = 'none';
                                                            status.style.display = 'none';
                                                            Month.style.display = 'none';
                                                            checkinput.disabled = true;
                                                            checkinout.disabled = true;
                                                            inputcompany.style.display = 'none';
                                                            Month.style.display = 'block';
                                                            inputcompanyindividual.disabled = true;
                                                        }else{
                                                            console.log(1);

                                                            checkinDiv.style.display = 'none';
                                                            checkoutDiv.style.display = 'none';
                                                            User.style.display = 'none';
                                                            status.style.display = 'none';
                                                            Month.style.display = 'none';
                                                            checkinput.disabled = true;
                                                            checkinout.disabled = true;
                                                            inputcompany.style.display = 'none';
                                                            inputcompanyindividual.disabled = true;
                                                        }
                                                    });
                                                    $(document).ready(function() {
                                                        const inputcompanyindividual = document.getElementById('inputcompanyindividual');
                                                        const checkinput = document.getElementById('checkinput');
                                                        const checkinout = document.getElementById('checkinout');
                                                        checkinput.disabled = true;
                                                        checkinout.disabled = true;
                                                        inputcompanyindividual.disabled = true;
                                                    });
                                                </script>
                                            </div>
                                        </div><!-- Form Validation -->
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                            <form action="{{ url('/Proposal/cancel/') }}" method="POST" enctype="multipart/form-data" class="row g-3 basic-form">
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
        <div class="row clearfix">
            <div class="col-sm-12 col-12">
                <ul class="nav nav-tabs px-3 border-bottom-0" role="tablist">
                    <li class="nav-item" id="nav1"><a class="nav-link active" data-bs-toggle="tab" href="#nav-Dummy"onclick="nav($id='nav1')"  role="tab"><span class="badge" style="background-color:#64748b">{{$Proposalcount}}</span> Proposal</a></li>{{--ประวัติการแก้ไข--}}
                    <li class="nav-item" id="nav2"><a class="nav-link " data-bs-toggle="tab" href="#nav-Pending" onclick="nav($id='nav2')"  role="tab"><span class="badge" style="background-color:#FF6633">{{$Pendingcount}}</span> Pending</a></li>{{--QUOTAION--}}
                    <li class="nav-item" id="nav3"><a class="nav-link" data-bs-toggle="tab" href="#nav-Awaiting"onclick="nav($id='nav3')"  role="tab"><span class="badge bg-warning" >{{$Awaitingcount}}</span> Awaiting Approval</a></li>{{--เอกสารออกบิล--}}
                    <li class="nav-item" id="nav4"><a class="nav-link " data-bs-toggle="tab" href="#nav-Approved" onclick="nav($id='nav4')" role="tab"><span class="badge bg-success">{{$Approvedcount}}</span> Approved</a></li>{{--Doc. number--}}
                    <li class="nav-item" id="nav9"><a class="nav-link " data-bs-toggle="tab" href="#nav-Generate" onclick="nav($id='nav5')" role="tab"><span class="badge" style="background-color: #0ea5e9" >{{$Generatecount}}</span> Generate</a></li>
                    <li class="nav-item" id="nav5"><a class="nav-link " data-bs-toggle="tab" href="#nav-Reject" onclick="nav($id='nav6')"  role="tab"><span class="badge "style="background-color:#1d4ed8" >{{$Rejectcount}}</span> Reject</a></li>{{--ชื่อ คนแนะนำ ครั้งต่อครั้ง ต่อ เอกสาร--}}
                    <li class="nav-item" id="nav6"><a class="nav-link" data-bs-toggle="tab" href="#nav-Cancel"onclick="nav($id='nav7')"  role="tab"><span class="badge bg-danger" >{{$Cancelcount}}</span> Cancel</a></li>{{--% (Percentage) ครั้งต่อครั้ง ต่อ เอกสาร--}}
                    <li class="nav-item" id="nav7"><a class="nav-link" data-bs-toggle="tab" href="#nav-noshow" onclick="nav($id='nav8')" role="tab"><span class="badge "style="background-color:#FF0066" >{{$noshowcount}}</span> No Show </a></li>{{--% (Percentage) ครั้งต่อครั้ง ต่อ เอกสาร--}}
                    <li class="nav-item" id="nav8"><a class="nav-link" data-bs-toggle="tab" href="#nav-Complete"  onclick="nav($id='nav9')" role="tab"><span class="badge "style="background-color:#2C7F7A" >{{$Completecount}}</span> Complete</a></li>
                </ul>
                <div class="card p-4 mb-4">
                    <div class="tab-content">
                        <div class="tab-pane fade  show active" id="nav-Dummy" role="tabpanel" rel="0">
                            <div style="min-height: 70vh;" class="mt-2">
                                <table id="proposalTable" class="table-together table-style">
                                    <thead>
                                        <tr>
                                            <th class="text-center"data-priority="1">No</th>
                                            <th class="text-center">Dummy</th>
                                            <th class="text-center" data-priority="1">Proposal ID</th>
                                            <th class="text-center" data-priority="1">Company</th>
                                            <th class="text-center">Issue Date</th>
                                            <th class="text-center">Day Type</th>
                                            <th class="text-center">Check In</th>
                                            <th class="text-center">Check Out</th>
                                            <th class="text-center">Add.Dis</th>
                                            <th class="text-center">Spe.Dis</th>
                                            <th class="text-center" style="width: 6%">Deposit</th>
                                            <th class="text-center">Create By</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($Proposal))
                                            @foreach ($Proposal as $key => $item)
                                            <tr>
                                                <td style="text-align: center;">
                                                    {{$key +1}}
                                                    <input type="hidden" id="update_date" value="{{$item->created_at}}">
                                                    <input type="hidden" id="approve_date" value="{{$item->Approve_at}}">
                                                </td>
                                                <td  style="text-align: center;">
                                                    @if ($item->DummyNo == $item->Quotation_ID )
                                                        -
                                                    @else
                                                        {{ $item->DummyNo }}
                                                    @endif
                                                </td>
                                                <td>{{ $item->Quotation_ID }}</td>
                                                @if ($item->type_Proposal == 'Company')
                                                    <td style="text-align: left;">{{ @$item->company->Company_Name}}</td>
                                                @else
                                                    <td style="text-align: left;">{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                @endif
                                                <td>{{ $item->issue_date }}</td>
                                                <td style="text-align: center;">{{$item->Date_type}}</td>
                                                @if ($item->checkin)
                                                <td style="text-align: center;">{{ $item->checkin}}</td>
                                                <td style="text-align: center;">{{ $item->checkout }}</td>
                                                @else
                                                <td style="text-align: center;">-</td>
                                                <td style="text-align: center;">-</td>
                                                @endif

                                                <td style="text-align: center;">
                                                    @if ($item->additional_discount == 0)
                                                        -
                                                    @else
                                                        <i class="bi bi-check-lg text-green" ></i>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($item->SpecialDiscountBath	== 0)
                                                        -
                                                    @else
                                                        <i class="bi bi-check-lg text-green" ></i>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($item->status_receive)
                                                        <img src="{{ asset('assets/images/deposit.png') }}" style="width: 35%;">
                                                    @endif
                                                </td>
                                                <td >{{ @$item->userOperated->name }}</td>
                                                <td style="text-align: center;">
                                                    @if($item->status_document == 0)
                                                        <span class="badge rounded-pill bg-danger">Cancel</span>
                                                    @elseif ($item->status_document == 1 )
                                                        <span class="badge rounded-pill "style="background-color: #FF6633	">Pending</span>
                                                    @elseif ($item->status_document == 2)
                                                        <span class="badge rounded-pill bg-warning">Awaiting Approval</span>
                                                    @elseif ($item->status_document == 3 )
                                                        <span class="badge rounded-pill bg-success">Approved</span>
                                                    @elseif ($item->status_document == 6 )
                                                    <span class="badge rounded-pill" style="background-color: #0ea5e9">Generate</span>
                                                    @elseif ($item->status_document == 4)
                                                        <span class="badge rounded-pill "style="background-color:#1d4ed8">Reject</span>
                                                    @elseif ($item->status_document == 5 )
                                                        <span class="badge rounded-pill "style="background-color: #FF0066">No Show</span>
                                                    @elseif ($item->status_document == 9)
                                                        <span class="badge rounded-pill "style="background-color: #2C7F7A">Complete</span>
                                                    @endif
                                                </td>
                                                @php
                                                    $CreateBy = Auth::user()->id;
                                                    $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                    $canViewProposal = @Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                                                    $canEditProposal = @Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);
                                                    $invoice =  DB::table('document_invoice')->where('Quotation_ID',$item->Quotation_ID)->where('document_status', 1)->count();
                                                @endphp
                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Select &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            @if ($rolePermission > 0)
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/'.$item->id) }}">View</a></li>

                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
                                                                @endif
                                                                @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                    @if ($canEditProposal == 1)
                                                                        @if ($item->status_document !== 2)
                                                                            @if ($item->status_document == 1 || $item->status_document == 3)
                                                                                <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Approved({{ $item->id }})">Generate</a></li>
                                                                            @endif
                                                                            @if ($item->status_document == 0)
                                                                                <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Revice({{ $item->id }})">Revise</a></li>
                                                                            @else
                                                                                @if ($item->status_document == 1 || $item->status_document == 3)
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/viewproposal/'.$item->id) }}">Send Email</a></li>
                                                                                @endif
                                                                                @if ($item->status_document !== 9)
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                                @endif
                                                                                @if ($item->status_document == 1 || $item->status_document == 3 || $item->status_document == 5 || $item->status_document == 4)
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                @endif
                                                                                @if ($item->status_document == 6)
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/create/'.$item->id) }}">Deposit Invoice</a></li>
                                                                                    <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="invoice({{ $item->id }})">Profoma Invoice</a></li>

                                                                                @endif
                                                                                @if ($item->status_document == 6 && $item->status_receive > 0 )
                                                                                    <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="noshow({{ $item->id }})">No Show</a></li>
                                                                                @endif
                                                                            @endif
                                                                        @endif

                                                                    @endif
                                                                @elseif ($rolePermission == 2)
                                                                    @if ($item->Operated_by == $CreateBy)
                                                                        @if ($canEditProposal == 1)
                                                                            @if ($item->status_document !== 2)
                                                                                @if ($item->status_document == 1 || $item->status_document == 3)
                                                                                    <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Approved({{ $item->id }})">Generate</a></li>
                                                                                @endif
                                                                                @if ($item->status_document == 0)
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Revice({{ $item->id }})">Revise</a></li>
                                                                                @else
                                                                                    @if ($item->status_document == 1 || $item->status_document == 3)
                                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/viewproposal/'.$item->id) }}">Send Email</a></li>
                                                                                    @endif
                                                                                    @if ($item->status_document !== 9)
                                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                                    @endif
                                                                                    @if ($item->status_document == 1 || $item->status_document == 3 || $item->status_document == 5 || $item->status_document == 4)
                                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                    @endif
                                                                                    @if ($item->status_document == 6)
                                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/create/'.$item->id) }}">Deposit Invoice</a></li>
                                                                                        <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="invoice({{ $item->id }})">Profoma Invoice</a></li>
                                                                                    @endif
                                                                                    @if ($item->status_document == 6 && $item->status_receive > 0 )
                                                                                        <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="noshow({{ $item->id }})">No Show</a></li>
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                @elseif ($rolePermission == 3)
                                                                    @if ($canEditProposal == 1)
                                                                        @if ($item->status_document !== 2)
                                                                            @if ($item->status_document == 1 || $item->status_document == 3)
                                                                                <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Approved({{ $item->id }})">Generate</a></li>
                                                                            @endif
                                                                            @if ($item->status_document == 0)
                                                                                <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Revice({{ $item->id }})">Revise</a></li>
                                                                            @else
                                                                                @if ($item->status_document == 1 || $item->status_document == 3)
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/viewproposal/'.$item->id) }}">Send Email</a></li>
                                                                                @endif
                                                                                @if ($item->status_document !== 9)
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                                @endif
                                                                                @if ($item->status_document == 1 || $item->status_document == 3 || $item->status_document == 5 || $item->status_document == 4)
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                @endif
                                                                                @if ($item->status_document == 6)
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/create/'.$item->id) }}">Deposit Invoice</a></li>
                                                                                    <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="invoice({{ $item->id }})">Profoma Invoice</a></li>
                                                                                @endif
                                                                                @if ($item->status_document == 6 && $item->status_receive > 0 )
                                                                                    <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="noshow({{ $item->id }})">No Show</a></li>
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @else
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/'.$item->id) }}">View</a></li>
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
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

                                <table id="PendingTable" class="table-together table-style">
                                    <thead>
                                        <tr>
                                            <th class="text-center"data-priority="1">No</th>
                                            <th class="text-center">Dummy</th>
                                            <th class="text-center" data-priority="1">Proposal ID</th>
                                            <th class="text-center" data-priority="1">Company</th>
                                            <th class="text-center">Issue Date</th>
                                            <th class="text-center">Day Type</th>
                                            <th class="text-center">Check In</th>
                                            <th class="text-center">Check Out</th>
                                            <th class="text-center">Add.Dis</th>
                                            <th class="text-center">Spe.Dis</th>
                                            <th class="text-center">Deposit</th>
                                            <th class="text-center">Create By</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($Pending))
                                            @foreach ($Pending as $key => $item)
                                            <tr>
                                                <td style="text-align: center;">
                                                    {{$key +1}}
                                                    <input type="hidden" id="update_date" value="{{$item->created_at}}">
                                                    <input type="hidden" id="approve_date" value="{{$item->Approve_at}}">
                                                </td>
                                                <td  style="text-align: center;">
                                                    @if ($item->DummyNo == $item->Quotation_ID )
                                                        -
                                                    @else
                                                        {{ $item->DummyNo }}
                                                    @endif
                                                </td>
                                                <td>{{ $item->Quotation_ID }}</td>
                                                @if ($item->type_Proposal == 'Company')
                                                    <td style="text-align: left;">{{ @$item->company->Company_Name}}</td>
                                                @else
                                                    <td style="text-align: left;">{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                @endif
                                                <td>{{ $item->issue_date }}</td>
                                                <td style="text-align: center;">{{$item->Date_type}}</td>
                                                @if ($item->checkin)
                                                <td style="text-align: center;">{{ $item->checkin}}</td>
                                                <td style="text-align: center;">{{ $item->checkout }}</td>
                                                @else
                                                <td style="text-align: center;">-</td>
                                                <td style="text-align: center;">-</td>
                                                @endif

                                                <td style="text-align: center;">
                                                    @if ($item->additional_discount == 0)
                                                        -
                                                    @else
                                                        <i class="bi bi-check-lg text-green" ></i>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($item->SpecialDiscountBath	== 0)
                                                        -
                                                    @else
                                                        <i class="bi bi-check-lg text-green" ></i>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($item->status_receive)
                                                        <img src="{{ asset('assets/images/deposit.png') }}" style="width: 25%;">
                                                    @endif
                                                </td>
                                                <td >{{ @$item->userOperated->name }}</td>
                                                <td style="text-align: center;">
                                                    <span class="badge rounded-pill "style="background-color: #FF6633	">Pending</span>
                                                </td>
                                                @php
                                                    $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                    $canViewProposal = @Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                                                    $canEditProposal = @Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);
                                                    $CreateBy = Auth::user()->id;
                                                @endphp
                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Select &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            @if ($rolePermission > 0)
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/'.$item->id) }}">View</a></li>
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
                                                                @endif
                                                                @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                    @if ($canEditProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Approved({{ $item->id }})">Generate</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/viewproposal/'.$item->id) }}">Send Email</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                    @endif
                                                                @elseif ($rolePermission == 2)
                                                                    @if ($canEditProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Approved({{ $item->id }})">Generate</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/viewproposal/'.$item->id) }}">Send Email</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                    @endif
                                                                @elseif ($rolePermission == 3)
                                                                    @if ($canEditProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Approved({{ $item->id }})">Generate</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/viewproposal/'.$item->id) }}">Send Email</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                    @endif
                                                                @endif
                                                            @else
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/'.$item->id) }}">View</a></li>
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
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
                        <div class="tab-pane fade "id="nav-Awaiting" role="tabpanel" rel="0">
                            <div style="min-height: 70vh;" class="mt-2">

                                <table id="proposalAwaitingTable" class="table-together table-style">
                                    <thead>
                                        <tr>
                                            <th class="text-center"data-priority="1">No</th>
                                            <th class="text-center">Dummy</th>
                                            <th class="text-center" data-priority="1">Proposal ID</th>
                                            <th class="text-center" data-priority="1">Company / Individual</th>
                                            <th class="text-center">Issue Date</th>
                                            <th class="text-center">Day Type</th>
                                            <th class="text-center">Check In</th>
                                            <th class="text-center">Check Out</th>

                                            <th class="text-center">Add.Dis</th>
                                            <th class="text-center">Spe.Dis</th>
                                            <th class="text-center">Deposit</th>
                                            <th class="text-center">Create By</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($Awaiting))
                                            @foreach ($Awaiting as $key => $item)
                                            <tr>
                                                <td style="text-align: center;">
                                                    {{$key +1}}
                                                    <input type="hidden" id="update_date" value="{{$item->created_at}}">
                                                    <input type="hidden" id="approve_date" value="{{$item->Approve_at}}">
                                                </td>
                                                <td  style="text-align: center;">
                                                    @if ($item->DummyNo == $item->Quotation_ID )
                                                        -
                                                    @else
                                                        {{ $item->DummyNo }}
                                                    @endif
                                                </td>
                                                <td>{{ $item->Quotation_ID }}</td>
                                                @if ($item->type_Proposal == 'Company')
                                                    <td>{{ @$item->company->Company_Name}}</td>
                                                @else
                                                    <td>{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                @endif

                                                <td>{{ $item->issue_date }}</td>
                                                <td style="text-align: center;">{{$item->Date_type}}</td>
                                                @if ($item->checkin)
                                                <td style="text-align: center;">{{ $item->checkin}}</td>
                                                <td style="text-align: center;">{{ $item->checkout }}</td>
                                                @else
                                                <td style="text-align: center;">-</td>
                                                <td style="text-align: center;">-</td>
                                                @endif

                                                <td style="text-align: center;">
                                                    @if ($item->additional_discount == 0)
                                                        -
                                                    @else
                                                        <i class="bi bi-check-lg text-green" ></i>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($item->SpecialDiscountBath	== 0)
                                                        -
                                                    @else
                                                        <i class="bi bi-check-lg text-green" ></i>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($item->status_receive)
                                                        <img src="{{ asset('assets/images/deposit.png') }}" style="width: 25%;">
                                                    @endif
                                                </td>
                                                <td >{{ @$item->userOperated->name }}</td>
                                                <td style="text-align: center;">
                                                    @if($item->status_guest == 1)
                                                        <span class="badge rounded-pill bg-success">Approved</span>
                                                    @else
                                                        @if($item->status_document == 0)
                                                            <span class="badge rounded-pill bg-danger">Cancel</span>
                                                        @elseif ($item->status_document == 1)
                                                            <span class="badge rounded-pill "style="background-color: #FF6633	">Pending</span>
                                                        @elseif ($item->status_document == 2)
                                                            <span class="badge rounded-pill bg-warning">Awaiting Approval</span>
                                                        @elseif ($item->status_document == 3)
                                                            <span class="badge rounded-pill "style="background-color: #FF6633	">Pending</span>
                                                        @elseif ($item->status_document == 4)
                                                            <span class="badge rounded-pill "style="background-color:#1d4ed8">Reject</span>
                                                        @elseif ($item->status_document == 6)
                                                            <span class="badge rounded-pill "style="background-color: #FF6633">Pending</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                @php
                                                    $rolePermission = @Auth::user()->rolePermission(Auth::user()->id);
                                                    $canViewProposal = @Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                                                @endphp
                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Select &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            @if ($rolePermission == 1 || $rolePermission == 2 || $rolePermission == 3)
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/'.$item->id) }}">View</a></li>
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
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
                                <table id="proposalTable" class="table-together table-style">
                                    <thead>
                                        <tr>
                                            <th class="text-center"data-priority="1">No</th>
                                            <th class="text-center">Dummy</th>
                                            <th class="text-center" data-priority="1">Proposal ID</th>
                                            <th class="text-center" data-priority="1">Company</th>
                                            <th class="text-center">Issue Date</th>
                                            <th class="text-center">Day Type</th>
                                            <th class="text-center">Check In</th>
                                            <th class="text-center">Check Out</th>

                                            <th class="text-center">Add.Dis</th>
                                            <th class="text-center">Spe.Dis</th>
                                            <th class="text-center">Deposit</th>
                                            <th class="text-center">Create By</th>
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
                                                    <input type="hidden" id="update_date" value="{{$item->created_at}}">
                                                    <input type="hidden" id="approve_date" value="{{$item->Approve_at}}">
                                                </td>
                                                <td  style="text-align: center;">
                                                    @if ($item->DummyNo == $item->Quotation_ID )
                                                        -
                                                    @else
                                                        {{ $item->DummyNo }}
                                                    @endif
                                                </td>
                                                <td>{{ $item->Quotation_ID }}</td>
                                                @if ($item->type_Proposal == 'Company')
                                                    <td style="text-align: left;">{{ @$item->company->Company_Name}}</td>
                                                @else
                                                    <td style="text-align: left;">{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                @endif
                                                <td>{{ $item->issue_date }}</td>
                                                <td style="text-align: center;">{{$item->Date_type}}</td>
                                                @if ($item->checkin)
                                                <td style="text-align: center;">{{ $item->checkin}}</td>
                                                <td style="text-align: center;">{{ $item->checkout }}</td>
                                                @else
                                                <td style="text-align: center;">-</td>
                                                <td style="text-align: center;">-</td>
                                                @endif

                                                <td style="text-align: center;">
                                                    @if ($item->additional_discount == 0)
                                                        -
                                                    @else
                                                        <i class="bi bi-check-lg text-green" ></i>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($item->SpecialDiscountBath	== 0)
                                                        -
                                                    @else
                                                        <i class="bi bi-check-lg text-green" ></i>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($item->status_receive)
                                                        <img src="{{ asset('assets/images/deposit.png') }}" style="width: 25%;">
                                                    @endif
                                                </td>
                                                <td >{{ @$item->userOperated->name }}</td>
                                                <td style="text-align: center;">
                                                    <span class="badge rounded-pill bg-success">Approved</span>
                                                </td>
                                                @php
                                                    $CreateBy = Auth::user()->id;
                                                    $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                    $canViewProposal = @Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                                                    $canEditProposal = @Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);
                                                    $invoice_count =  DB::table('document_invoice')->where('Quotation_ID', $item->Quotation_ID)
                                                    ->count();
                                                @endphp
                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Select &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            @if ($rolePermission > 0)
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/'.$item->id) }}">View</a></li>
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
                                                                @endif
                                                                @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                    @if ($canEditProposal == 1)
                                                                        @if ($item->status_document !== 2)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/viewproposal/'.$item->id) }}">Send Email</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                            @if ($item->status_receive == 0 || $invoice_count == 0)
                                                                                <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                @elseif ($rolePermission == 2)
                                                                    @if ($canEditProposal == 1)
                                                                        @if ($item->status_document !== 2)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/viewproposal/'.$item->id) }}">Send Email</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                            @if ($item->status_receive == 0 || $invoice_count == 0)
                                                                                <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                @elseif ($rolePermission == 3)
                                                                    @if ($canEditProposal == 1)
                                                                        @if ($item->status_document !== 2)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/viewproposal/'.$item->id) }}">Send Email</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                            @if ($item->status_receive == 0 || $invoice_count == 0)
                                                                                <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @else
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/'.$item->id) }}">View</a></li>

                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
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
                        <div class="tab-pane fade" id="nav-Generate" role="tabpanel" rel="0">
                            <div style="min-height: 70vh;" class="mt-2">
                                <table id="GenerateTable" class="table-together table-style">
                                    <thead>
                                        <tr>
                                            <th class="text-center"data-priority="1">No</th>
                                            <th class="text-center">Dummy</th>
                                            <th class="text-center" data-priority="1">Proposal ID</th>
                                            <th class="text-center" data-priority="1">Company</th>
                                            <th class="text-center">Issue Date</th>
                                            <th class="text-center">Day Type</th>
                                            <th class="text-center">Check In</th>
                                            <th class="text-center">Check Out</th>

                                            <th class="text-center">Add.Dis</th>
                                            <th class="text-center">Spe.Dis</th>
                                            <th class="text-center" style="width: 6%">Deposit</th>
                                            <th class="text-center">Create By</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($Generate))
                                            @foreach ($Generate as $key => $item)
                                            <tr>
                                                <td style="text-align: center;">
                                                    {{$key +1}}
                                                    <input type="hidden" id="update_date" value="{{$item->created_at}}">
                                                    <input type="hidden" id="approve_date" value="{{$item->Approve_at}}">
                                                </td>
                                                <td  style="text-align: center;">
                                                    @if ($item->DummyNo == $item->Quotation_ID )
                                                        -
                                                    @else
                                                        {{ $item->DummyNo }}
                                                    @endif
                                                </td>
                                                <td>{{ $item->Quotation_ID }}</td>
                                                @if ($item->type_Proposal == 'Company')
                                                    <td style="text-align: left;">{{ @$item->company->Company_Name}}</td>
                                                @else
                                                    <td style="text-align: left;">{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                @endif
                                                <td>{{ $item->issue_date }}</td>
                                                <td style="text-align: center;">{{$item->Date_type}}</td>
                                                @if ($item->checkin)
                                                <td style="text-align: center;">{{ $item->checkin}}</td>
                                                <td style="text-align: center;">{{ $item->checkout }}</td>
                                                @else
                                                <td style="text-align: center;">-</td>
                                                <td style="text-align: center;">-</td>
                                                @endif

                                                <td style="text-align: center;">
                                                    @if ($item->additional_discount == 0)
                                                        -
                                                    @else
                                                        <i class="bi bi-check-lg text-green" ></i>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($item->SpecialDiscountBath	== 0)
                                                        -
                                                    @else
                                                        <i class="bi bi-check-lg text-green" ></i>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($item->status_receive)
                                                        <img src="{{ asset('assets/images/deposit.png') }}" style="width: 35%;">
                                                    @endif
                                                </td>
                                                <td >{{ @$item->userOperated->name }}</td>
                                                <td style="text-align: center;">
                                                    <span class="badge rounded-pill" style="background-color: #0ea5e9">Generate</span>
                                                </td>
                                                @php
                                                    $CreateBy = Auth::user()->id;
                                                    $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                    $canViewProposal = @Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                                                    $canEditProposal = @Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);
                                                    $invoice =  DB::table('document_invoice')->where('Quotation_ID',$item->Quotation_ID)->where('document_status', 1)->count();
                                                @endphp
                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Select &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            @if ($rolePermission > 0)
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/'.$item->id) }}">View</a></li>
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
                                                                @endif
                                                                @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                    @if ($canEditProposal == 1)
                                                                        @if ($item->status_document !== 2)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/create/'.$item->id) }}">Deposit Invoice</a></li>
                                                                            @if ($invoice !== 1)
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/Generate/'.$item->id) }}">Profoma Invoice</a></li>
                                                                            @endif
                                                                            @if ($item->status_receive > 0 )
                                                                                <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="noshow({{ $item->id }})">No Show</a></li>
                                                                            @endif
                                                                            @if ($item->status_document == 6 && $item->invoice_count == 0 && $item->status_receive < 0)
                                                                                <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                            @endif

                                                                        @endif
                                                                    @endif
                                                                @elseif ($rolePermission == 2)
                                                                    @if ($canEditProposal == 1)
                                                                        @if ($item->status_document !== 2)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/create/'.$item->id) }}">Deposit Invoice</a></li>
                                                                            @if ($invoice !== 1)
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/Generate/'.$item->id) }}">Profoma Invoice</a></li>
                                                                            @endif
                                                                            @if ($item->status_receive > 0 )
                                                                                <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="noshow({{ $item->id }})">No Show</a></li>
                                                                            @endif
                                                                            @if ($item->status_document == 6 && $item->invoice_count == 0 && $item->status_receive < 0)
                                                                                <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                @elseif ($rolePermission == 3)
                                                                    @if ($canEditProposal == 1)
                                                                        @if ($item->status_document !== 2)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Deposit/create/'.$item->id) }}">Deposit Invoice</a></li>
                                                                            @if ($invoice !== 1)
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/invoice/Generate/'.$item->id) }}">Profoma Invoice</a></li>
                                                                            @endif
                                                                            @if ($item->status_receive > 0 )
                                                                                <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="noshow({{ $item->id }})">No Show</a></li>
                                                                            @endif
                                                                            @if ($item->status_document == 6 && $item->invoice_count == 0 && $item->status_receive < 0)
                                                                                <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @else
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/'.$item->id) }}">View</a></li>
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
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
                                            <th class="text-center">Dummy</th>
                                            <th class="text-center" data-priority="1">Proposal ID</th>
                                            <th class="text-center" data-priority="1">Company</th>
                                            <th class="text-center">Issue Date</th>
                                            <th class="text-center">Day Type</th>
                                            <th class="text-center">Check In</th>
                                            <th class="text-center">Check Out</th>

                                            <th class="text-center">Add.Dis</th>
                                            <th class="text-center">Spe.Dis</th>
                                            <th class="text-center">Deposit</th>
                                            <th class="text-center">Create By</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($Reject))
                                            @foreach ($Reject as $key => $item)
                                            <tr>
                                                <td style="text-align: center;">
                                                    {{$key +1}}
                                                    <input type="hidden" id="update_date" value="{{$item->created_at}}">
                                                    <input type="hidden" id="approve_date" value="{{$item->Approve_at}}">
                                                </td>
                                                <td  style="text-align: center;">
                                                    @if ($item->DummyNo == $item->Quotation_ID )
                                                        -
                                                    @else
                                                        {{ $item->DummyNo }}
                                                    @endif
                                                </td>
                                                <td>{{ $item->Quotation_ID }}</td>
                                                @if ($item->type_Proposal == 'Company')
                                                    <td style="text-align: left;">{{ @$item->company->Company_Name}}</td>
                                                @else
                                                    <td style="text-align: left;">{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                @endif

                                                <td>{{ $item->issue_date }}</td>
                                                <td style="text-align: center;">{{$item->Date_type}}</td>
                                                @if ($item->checkin)
                                                <td style="text-align: center;">{{ $item->checkin}}</td>
                                                <td style="text-align: center;">{{ $item->checkout }}</td>
                                                @else
                                                <td style="text-align: center;">-</td>
                                                <td style="text-align: center;">-</td>
                                                @endif

                                                <td style="text-align: center;">
                                                    @if ($item->additional_discount == 0)
                                                        -
                                                    @else
                                                        <i class="bi bi-check-lg text-green" ></i>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($item->SpecialDiscountBath	== 0)
                                                        -
                                                    @else
                                                        <i class="bi bi-check-lg text-green" ></i>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($item->status_receive)
                                                        <img src="{{ asset('assets/images/deposit.png') }}" style="width: 25%;">
                                                    @endif
                                                </td>
                                                <td >{{ @$item->userOperated->name }}</td>
                                                <td style="text-align: center;">
                                                    <span class="badge rounded-pill "style="background-color:#1d4ed8">Reject</span>
                                                </td>
                                                @php
                                                    $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                    $canViewProposal = @Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                                                    $canEditProposal = @Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);
                                                    $CreateBy = Auth::user()->id;
                                                @endphp
                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Select &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            @if ($rolePermission > 0)
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/'.$item->id) }}">View</a></li>
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
                                                                @endif
                                                                @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                    @if ($canEditProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                    @endif
                                                                @elseif ($rolePermission == 2)
                                                                    @if ($canEditProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                    @endif
                                                                @elseif ($rolePermission == 3)
                                                                    @if ($canEditProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                    @endif
                                                                @endif
                                                            @else
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/'.$item->id) }}">View</a></li>
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
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
                                            <th class="text-center">Dummy</th>
                                            <th class="text-center" data-priority="1">Proposal ID</th>
                                            <th class="text-center" data-priority="1">Company</th>
                                            <th class="text-center">Issue Date</th>
                                            <th class="text-center">Day Type</th>
                                            <th class="text-center">Check In</th>
                                            <th class="text-center">Check Out</th>

                                            <th class="text-center">Add.Dis</th>
                                            <th class="text-center">Spe.Dis</th>
                                            <th class="text-center">Deposit</th>
                                            <th class="text-center">Create By</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($Cancel))
                                            @foreach ($Cancel as $key => $item)
                                            <tr>
                                                <td style="text-align: center;">
                                                    {{$key +1}}
                                                    <input type="hidden" id="update_date" value="{{$item->created_at}}">
                                                    <input type="hidden" id="approve_date" value="{{$item->Approve_at}}">
                                                </td>
                                                <td  style="text-align: center;">
                                                    @if ($item->DummyNo == $item->Quotation_ID )
                                                        -
                                                    @else
                                                        {{ $item->DummyNo }}
                                                    @endif
                                                </td>
                                                <td>{{ $item->Quotation_ID }}</td>
                                                @if ($item->type_Proposal == 'Company')
                                                    <td style="text-align: left;">{{ @$item->company->Company_Name}}</td>
                                                @else
                                                    <td style="text-align: left;">{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                @endif

                                                <td>{{ $item->issue_date }}</td>
                                                <td style="text-align: center;">{{$item->Date_type}}</td>
                                                @if ($item->checkin)
                                                <td style="text-align: center;">{{ $item->checkin}}</td>
                                                <td style="text-align: center;">{{ $item->checkout}}</td>
                                                @else
                                                <td style="text-align: center;">-</td>
                                                <td style="text-align: center;">-</td>
                                                @endif

                                                <td style="text-align: center;">
                                                    @if ($item->additional_discount == 0)
                                                        -
                                                    @else
                                                        <i class="bi bi-check-lg text-green" ></i>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($item->SpecialDiscountBath	== 0)
                                                        -
                                                    @else
                                                        <i class="bi bi-check-lg text-green" ></i>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($item->status_receive)
                                                        <img src="{{ asset('assets/images/deposit.png') }}" style="width: 25%;">
                                                    @endif
                                                </td>
                                                <td >{{ @$item->userOperated->name }}</td>
                                                <td style="text-align: center;">
                                                    <span class="badge rounded-pill bg-danger">Cancel</span>
                                                </td>
                                                @php
                                                    $rolePermission = @Auth::user()->rolePermission(Auth::user()->id);
                                                    $canViewProposal = @Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                                                    $canEditProposal = @Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);
                                                    $CreateBy = Auth::user()->id;
                                                @endphp
                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Select &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            @if ($rolePermission > 0)
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/'.$item->id) }}">View</a></li>
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
                                                                @endif
                                                                @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                    @if ($canEditProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Revice({{ $item->id }})">Revise</a></li>
                                                                    @endif
                                                                @elseif ($rolePermission == 2)
                                                                    @if ($canEditProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Revice({{ $item->id }})">Revise</a></li>
                                                                    @endif
                                                                @elseif ($rolePermission == 3)
                                                                    @if ($canEditProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Revice({{ $item->id }})">Revise</a></li>
                                                                    @endif
                                                                @endif
                                                            @else
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/'.$item->id) }}">View</a></li>
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
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
                        <div class="tab-pane fade" id="nav-noshow" role="tabpanel" rel="0">
                            <div style="min-height: 70vh;" class="mt-2">

                                <table id="noshowTable" class="table-together table-style">
                                    <thead>
                                        <tr>
                                            <th class="text-center"data-priority="1">No</th>
                                            <th class="text-center">Dummy</th>
                                            <th class="text-center" data-priority="1">Proposal ID</th>
                                            <th class="text-center" data-priority="1">Company</th>
                                            <th class="text-center">Issue Date</th>
                                            <th class="text-center">Day Type</th>
                                            <th class="text-center">Check In</th>
                                            <th class="text-center">Check Out</th>

                                            <th class="text-center">Add.Dis</th>
                                            <th class="text-center">Spe.Dis</th>
                                             <th class="text-center">Deposit</th>
                                            <th class="text-center">Create By</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($noshow))
                                            @foreach ($noshow as $key => $item)
                                            <tr>
                                                <td style="text-align: center;">
                                                    {{$key +1}}
                                                    <input type="hidden" id="update_date" value="{{$item->created_at}}">
                                                    <input type="hidden" id="approve_date" value="{{$item->Approve_at}}">
                                                </td>
                                                <td  style="text-align: center;">
                                                    @if ($item->DummyNo == $item->Quotation_ID )
                                                        -
                                                    @else
                                                        {{ $item->DummyNo }}
                                                    @endif
                                                </td>
                                                <td>{{ $item->Quotation_ID }}</td>
                                                @if ($item->type_Proposal == 'Company')
                                                    <td style="text-align: left;">{{ @$item->company->Company_Name}}</td>
                                                @else
                                                    <td style="text-align: left;">{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                @endif

                                                <td>{{ $item->issue_date }}</td>
                                                <td style="text-align: center;">{{$item->Date_type}}</td>
                                                @if ($item->checkin)
                                                <td style="text-align: center;">{{ $item->checkin}}</td>
                                                <td style="text-align: center;">{{ $item->checkout}}</td>
                                                @else
                                                <td style="text-align: center;">-</td>
                                                <td style="text-align: center;">-</td>
                                                @endif

                                                <td style="text-align: center;">
                                                    @if ($item->additional_discount == 0)
                                                        -
                                                    @else
                                                        <i class="bi bi-check-lg text-green" ></i>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($item->SpecialDiscountBath	== 0)
                                                        -
                                                    @else
                                                        <i class="bi bi-check-lg text-green" ></i>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($item->status_receive)
                                                        <img src="{{ asset('assets/images/deposit.png') }}" style="width: 25%;">
                                                    @endif
                                                </td>
                                                <td >{{ @$item->userOperated->name }}</td>
                                                <td style="text-align: center;">
                                                    <span class="badge rounded-pill " style="background: #FF0066">No Show</span>
                                                </td>
                                                @php
                                                    $rolePermission = @Auth::user()->rolePermission(Auth::user()->id);
                                                    $canViewProposal = @Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                                                    $canEditProposal = @Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);
                                                    $CreateBy = Auth::user()->id;
                                                @endphp
                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Select &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            @if ($rolePermission > 0)
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/'.$item->id) }}">View</a></li>
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
                                                                @endif
                                                                @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                    @if ($canEditProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="javascript:void(0);" onclick="Revice({{ $item->id }})">Revise</a></li>
                                                                    @endif
                                                                @elseif ($rolePermission == 2)
                                                                    @if ($canEditProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Revice({{ $item->id }})">Revise</a></li>
                                                                    @endif
                                                                @elseif ($rolePermission == 3)
                                                                    @if ($canEditProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Revice({{ $item->id }})">Revise</a></li>
                                                                    @endif
                                                                @endif
                                                            @else
                                                                @if ($canViewProposal == 1)
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/'.$item->id) }}">View</a></li>
                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
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

                                <table id="completeTable" class="table-together table-style">
                                    <thead>
                                        <tr>
                                            <th class="text-center"data-priority="1">No</th>
                                            <th class="text-center">Dummy</th>
                                            <th class="text-center" data-priority="1">Proposal ID</th>
                                            <th class="text-center" data-priority="1">Company / Individual</th>
                                            <th class="text-center">Issue Date</th>
                                            <th class="text-center">Day Type</th>
                                            <th class="text-center">Check In</th>
                                            <th class="text-center">Check Out</th>

                                            <th class="text-center">Add.Dis</th>
                                            <th class="text-center">Spe.Dis</th>
                                            <th class="text-center">Deposit</th>
                                            <th class="text-center">Create By</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($Complete))
                                            @foreach ($Complete as $key => $item)
                                            <tr>
                                                <td style="text-align: center;">
                                                    {{$key +1}}
                                                    <input type="hidden" id="update_date" value="{{$item->created_at}}">
                                                    <input type="hidden" id="approve_date" value="{{$item->Approve_at}}">
                                                </td>
                                                <td  style="text-align: center;">
                                                    @if ($item->DummyNo == $item->Quotation_ID )
                                                        -
                                                    @else
                                                        {{ $item->DummyNo }}
                                                    @endif
                                                </td>
                                                <td>{{ $item->Quotation_ID }}</td>
                                                @if ($item->type_Proposal == 'Company')
                                                    <td>{{ @$item->company->Company_Name}}</td>
                                                @else
                                                    <td>{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                @endif

                                                <td>{{ $item->issue_date }}</td>
                                                <td style="text-align: center;">{{$item->Date_type}}</td>
                                                @if ($item->checkin)
                                                <td style="text-align: center;">{{ $item->checkin}}</td>
                                                <td style="text-align: center;">{{ $item->checkout }}</td>
                                                @else
                                                <td style="text-align: center;">-</td>
                                                <td style="text-align: center;">-</td>
                                                @endif

                                                <td style="text-align: center;">
                                                    @if ($item->additional_discount == 0)
                                                        -
                                                    @else
                                                        <i class="bi bi-check-lg text-green" ></i>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($item->SpecialDiscountBath	== 0)
                                                        -
                                                    @else
                                                        <i class="bi bi-check-lg text-green" ></i>
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    @if ($item->status_receive)
                                                        <img src="{{ asset('assets/images/deposit.png') }}" style="width: 25%;">
                                                    @endif
                                                </td>
                                                <td >{{ @$item->userOperated->name }}</td>
                                                <td style="text-align: center;">
                                                    <span class="badge rounded-pill "style="background-color: #2C7F7A">Complete</span>
                                                </td>
                                                <td style="text-align: center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Select &nbsp;</button>
                                                        <ul class="dropdown-menu border-0 shadow p-3">
                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/'.$item->id) }}">View</a></li>

                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
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
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
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
    </script>
    <script>

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
                    form.action = `{{ url('/Proposal/cancel/') }}/${id}`;
                    $('#myModal').modal('show'); // เปิดโมดอล
                }
            });
        }
        function Revice(id){
            Swal.fire({
            title: "Do you want to activate this offer?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "Cancel",
            confirmButtonColor: "#2C7F7A",
            dangerMode: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ url('/Proposal/Revice/') }}/" + id;
                }
            });
        }
        function Approved(id) {
            jQuery.ajax({
                type: "GET",
                url: "/Proposal/Request/document/Approve/guest/" + id,
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
        function noshow(id) {
            jQuery.ajax({
                type: "GET",
                url: "/Proposal/Request/document/noshow/" + id,
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
        function invoice(id) {
            jQuery.ajax({
                type: "GET",
                url: "/Proposal/check/invoice/" + id,
                datatype: "JSON",
                async: false,
                success: function(response) {

                    var invoice = response.data;
                    if (invoice == 1) {
                        Swal.fire({
                            icon: "error",
                            text: "Have an invoice document Already in the system.",
                        });
                    }else{
                        var url = "{{ route('invoice.Generate', ':id') }}".replace(':id', id);
                        window.location.href = url;
                    }
                },
                error: function(xhr, status, error) {
                    alert("An error occurred while processing the request.");
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
        //             initializeDataTable('#proposalTable', '/Proposal/get/proposalTable', [
        //                 { data: 'no', title: 'No' },
        //                 { data: 'dummy_id', title: 'Dummy' },
        //                 { data: 'quotation_id', title: 'Proposal ID' },
        //                 { data: 'company_name', title: 'Company' },
        //                 { data: 'Issue', title: 'Issue Date' },
        //                 { data: 'Day', title: 'Day Type' },
        //                 { data: 'Checkin', title: 'Check In' },
        //                 { data: 'Checkout', title: 'Check Out' },
        //                 { data: 'Add.Dis', title: 'Add.Dis' },
        //                 { data: 'Spe.Dis', title: 'Spe.Dis' },
        //                 { data: 'Deposit', title: 'Deposit' },
        //                 { data: 'Create', title: 'Create By' },
        //                 { data: 'status', title: 'Status' },
        //                 { data: 'action', title: 'Action' }
        //             ]);
        //         }  else if (target === '#nav-Pending') {
        //             initializeDataTable('#PendingTable', '/Proposal/get/PendingTable', [
        //                 { data: 'no', title: 'No' },
        //                 { data: 'dummy_id', title: 'Dummy' },
        //                 { data: 'quotation_id', title: 'Proposal ID' },
        //                 { data: 'company_name', title: 'Company' },
        //                 { data: 'Issue', title: 'Issue Date' },
        //                 { data: 'Day', title: 'Day Type' },
        //                 { data: 'Checkin', title: 'Check In' },
        //                 { data: 'Checkout', title: 'Check Out' },
        //                 { data: 'Add.Dis', title: 'Add.Dis' },
        //                 { data: 'Spe.Dis', title: 'Spe.Dis' },
        //                 { data: 'Deposit', title: 'Deposit' },
        //                 { data: 'Create', title: 'Create By' },
        //                 { data: 'status', title: 'Status' },
        //                 { data: 'action', title: 'Action' }
        //             ]);
        //         } else if (target === '#nav-Approved') {

        //         } else if (target === '#nav-Complete') {

        //         }
        //         else if (target === '#nav-Cancel') {

        //         }
        //     }
        // });
    </script>

@endsection
