@extends('layouts.masterLayout')
@section('content')
    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <small class="text-muted">Welcome to Additional ( Over Bill ).</small>
                    <div class=""><span class="span1">Additional ( Over Bill )</span></div>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ route('BillingFolioOver.select') }}'">
                        <i class="fa fa-plus"></i> Issue Bill
                    </button>
                    {{-- <button type="button" class="btn btn-color-green lift btn_modal" data-bs-toggle="modal" data-bs-target="#allSearch">
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
                                                            <option value="4">Reject</option>
                                                            <option value="0">Cancel</option>
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
                    </div> --}}
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
                                            <form action="{{ url('/Document/BillingFolio/Proposal/Over/Cancel/') }}" method="GET" enctype="multipart/form-data" class="row g-3 basic-form">
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
                        <li class="nav-item" id="nav2"><a class="nav-link " data-bs-toggle="tab" href="#nav-Pending" onclick="nav($id='nav2')" role="tab"><span class="badge" style="background-color:#0ea5e9">{{$Pendingcount}}</span> Receive (RE)</a></li>{{--QUOTAION--}}
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
                                        <table id="AdditionalTable" class="example1 ui striped table nowrap unstackable hover">
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
                                                @if(!empty($Proposal))
                                                    @foreach ($Proposal as $key => $item)
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
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/view/'.$item->id) }}">View</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Document/BillingFolio/Proposal/Over/document/PDF/'.$item->id) }}">Export</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/log/'.$item->id) }}">LOG</a></li>
                                                                    @endif
                                                                    @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                        @if ($canEditProposal == 1)
                                                                            @if ($item->status_document !== 2)
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/edit/'.$item->id) }}">Edit</a></li>
                                                                                @if ($item->status_document == 3)
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/Generate/'.$item->id) }}">Generate</a></li>
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                @endif
                                                                                @if ($item->status_document == 4)
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @elseif ($rolePermission == 2)
                                                                        @if ($item->Operated_by == $CreateBy)
                                                                            @if ($canEditProposal == 1)
                                                                                @if ($item->status_document !== 2)
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/edit/'.$item->id) }}">Edit</a></li>
                                                                                    @if ($item->status_document == 3)
                                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/Generate/'.$item->id) }}">Generate</a></li>
                                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                    @endif
                                                                                    @if ($item->status_document == 4)
                                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                    @endif
                                                                                    @if ($item->status_document == 0)
                                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Delete({{ $item->id }})">Delete</a></li>
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @elseif ($rolePermission == 3)
                                                                        @if ($canEditProposal == 1)
                                                                            @if ($item->status_document !== 2)
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/edit/'.$item->id) }}">Edit</a></li>
                                                                                @if ($item->status_document == 3)
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/Generate/'.$item->id) }}">Generate</a></li>
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                @endif
                                                                                @if ($item->status_document == 4)
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                @endif
                                                                                @if ($item->status_document == 0)
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Delete({{ $item->id }})">Delete</a></li>
                                                                                @endif
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
                                <div class="tab-pane fade" id="nav-Pending" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" class="mt-2">
                                        <caption class="caption-top">
                                            <div class="flex-end-g2">
                                                <label class="entriespage-label">entries per page :</label>
                                                <select class="entriespage-button" id="search-per-page-proposalPending" onchange="getPagePending(1, this.value, 'proposalPending')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                    <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "proposalPending" ? 'selected' : '' }}>10</option>
                                                    <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "proposalPending" ? 'selected' : '' }}>25</option>
                                                    <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "proposalPending" ? 'selected' : '' }}>50</option>
                                                    <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "proposalPending" ? 'selected' : '' }}>100</option>
                                                </select>
                                                <input class="search-button search-data-Pending" id="proposalPending" style="text-align:left;" placeholder="Search" />
                                            </div>
                                        </caption>
                                        <table id="proposalPendingTable" class="example2 ui striped table nowrap unstackable hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"data-priority="1">No</th>
                                                    <th class="text-center" data-priority="1">Receipt ID</th>
                                                    <th class="text-center" data-priority="1">Proposal ID</th>
                                                    <th data-priority="1">Company / Individual</th>
                                                    <th class="text-center">Room No</th>
                                                    <th class="text-center">Payment Date</th>
                                                    <th class="text-center">Amount</th>
                                                    <th class="text-center">Category</th>
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
                                                        <td style="text-align: center;">{{ $item->Receipt_ID }}</td>
                                                        <td style="text-align: center;">{{ $item->Quotation_ID }}</td>
                                                        @if ($item->type_Proposal == 'Company')
                                                            <td>{{ isset($item->company00->Company_Name) ? $item->company00->Company_Name : '' }}</td>
                                                        @elseif ($item->type_Proposal == 'Guest')
                                                            <td>{{ isset($item->guest->First_name) && isset($item->guest->Last_name) ? $item->guest->First_name.' '.$item->guest->Last_name : '' }}</td>
                                                        @elseif ($item->type_Proposal == 'company_tax')
                                                            <td>{{ isset($item->company_tax->Companny_name) ? $item->company_tax->Companny_name : (isset($item->company_tax->first_name) && isset($item->company_tax->last_name) ? $item->company_tax->first_name.' '.$item->company_tax->last_name : '') }}</td>
                                                        @elseif ($item->type_Proposal == 'guest_tax')
                                                            <td>{{ isset($item->guest_tax->Company_name) ? $item->guest_tax->Company_name : (isset($item->guest_tax->first_name) && isset($item->guest_tax->last_name) ? $item->guest_tax->first_name.' '.$item->guest_tax->last_name : '') }}</td>
                                                        @endif
                                                        <td>{{ $item->roomNo }}</td>
                                                        <td>{{ $item->paymentDate }}</td>
                                                        <td style="text-align: center;">
                                                            {{ number_format($item->Amount) }}
                                                        </td>
                                                        <td style="text-align: center;">{{ $item->category }}</td>
                                                        <td style="text-align: center;">
                                                            {{ @$item->userOperated->name }}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <span class="badge rounded-pill"style="background-color:#0ea5e9">Receive (RE)</span>
                                                        </td>
                                                        @php
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
                                                                            <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Document/BillingFolioOverbill/Proposal/invoice/export/'.$item->id) }}">Export</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded"  href="{{ url('/Document/BillingFolio/Over/log/re/'.$item->id) }}">LOG</a></li>
                                                                        @endif
                                                                        @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                            @if ($canEditProposal == 1)
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Additional/receipt/Edit/'.$item->id) }}">Edit</a></li>
                                                                            @endif
                                                                        @elseif ($rolePermission == 2)
                                                                            @if ($item->Operated_by == $CreateBy)
                                                                                @if ($canEditProposal == 1)
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Additional/receipt/Edit/'.$item->id) }}">Edit</a></li>
                                                                                @endif
                                                                            @endif
                                                                        @elseif ($rolePermission == 3)
                                                                            @if ($canEditProposal == 1)
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Additional/receipt/Edit/'.$item->id) }}">Edit</a></li>
                                                                            @endif
                                                                        @endif
                                                                    @else
                                                                        @if ($canViewProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Document/BillingFolioOverbill/Proposal/invoice/export/'.$item->id) }}">Export</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded"  href="{{ url('/Document/BillingFolio/Over/log/re/'.$item->id) }}">LOG</a></li>
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
                                        <input type="hidden" id="get-total-proposalPending" value="{{ $Pending->total() }}">
                                        <input type="hidden" id="currentPage-proposalPending" value="1">
                                        <caption class="caption-bottom">
                                            <div class="md-flex-bt-i-c">
                                                <p class="py2" id="proposalPending-showingEntries">{{ showingEntriesTablePending($Pending, 'proposalPending') }}</p>
                                                <div id="proposalPending-paginate">
                                                    {!! paginateTablePending($Pending, 'proposalPending') !!} <!-- ข้อมูล, ชื่อตาราง -->
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
                                        <table id="proposalAwaitingTable" class="example2 ui striped table nowrap unstackable hover">
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
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/view/'.$item->id) }}">View</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Document/BillingFolio/Proposal/Over/document/PDF/'.$item->id) }}">Export</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/log/'.$item->id) }}">LOG</a></li>
                                                                    @endif
                                                                    @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                        @if ($canEditProposal == 1)
                                                                            @if ($item->status_document !== 2)
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/edit/'.$item->id) }}">Edit</a></li>
                                                                                @if ($item->status_document == 3)
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/Generate/'.$item->id) }}">Generate</a></li>
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                @endif
                                                                                @if ($item->status_document == 4)
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @elseif ($rolePermission == 2)
                                                                        @if ($item->Operated_by == $CreateBy)
                                                                            @if ($canEditProposal == 1)
                                                                                @if ($item->status_document !== 2)
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/edit/'.$item->id) }}">Edit</a></li>
                                                                                    @if ($item->status_document == 3)
                                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/Generate/'.$item->id) }}">Generate</a></li>
                                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                    @endif
                                                                                    @if ($item->status_document == 4)
                                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                    @endif
                                                                                    @if ($item->status_document == 0)
                                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Delete({{ $item->id }})">Delete</a></li>
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @elseif ($rolePermission == 3)
                                                                        @if ($canEditProposal == 1)
                                                                            @if ($item->status_document !== 2)
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/edit/'.$item->id) }}">Edit</a></li>
                                                                                @if ($item->status_document == 3)
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/Generate/'.$item->id) }}">Generate</a></li>
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                @endif
                                                                                @if ($item->status_document == 4)
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                @endif
                                                                                @if ($item->status_document == 0)
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Delete({{ $item->id }})">Delete</a></li>
                                                                                @endif
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
                                        <table id="proposalApprovedTable" class="example2 ui striped table nowrap unstackable hover">
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
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/view/'.$item->id) }}">View</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Document/BillingFolio/Proposal/Over/document/PDF/'.$item->id) }}">Export</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/log/'.$item->id) }}">LOG</a></li>
                                                                    @endif
                                                                    @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                        @if ($canEditProposal == 1)
                                                                            @if ($item->status_document !== 2)
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/edit/'.$item->id) }}">Edit</a></li>
                                                                                @if ($item->status_document == 3)
                                                                                    @php
                                                                                        $requestCount =  DB::table('document_receive')->where('Quotation_ID',$item->Additional_ID)->where('type','Additional')
                                                                                        ->count();
                                                                                    @endphp
                                                                                    @if ($requestCount < 1)
                                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/Generate/'.$item->id) }}">Generate</a></li>
                                                                                    @endif
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                @endif
                                                                                @if ($item->status_document == 4)
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @elseif ($rolePermission == 2)
                                                                        @if ($item->Operated_by == $CreateBy)
                                                                            @if ($canEditProposal == 1)
                                                                                @if ($item->status_document !== 2)
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/edit/'.$item->id) }}">Edit</a></li>
                                                                                    @if ($item->status_document == 3)
                                                                                    @php
                                                                                        $requestCount =  DB::table('document_receive')->where('Quotation_ID',$item->Additional_ID)->where('type','Additional')
                                                                                        ->count();
                                                                                    @endphp
                                                                                    @if ($requestCount < 1)
                                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/Generate/'.$item->id) }}">Generate</a></li>
                                                                                    @endif
                                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                    @endif
                                                                                    @if ($item->status_document == 4)
                                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                    @endif
                                                                                    @if ($item->status_document == 0)
                                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Delete({{ $item->id }})">Delete</a></li>
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @elseif ($rolePermission == 3)
                                                                        @if ($canEditProposal == 1)
                                                                            @if ($item->status_document !== 2)
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/edit/'.$item->id) }}">Edit</a></li>
                                                                                @if ($item->status_document == 3)
                                                                                    @php
                                                                                        $requestCount =  DB::table('document_receive')->where('Quotation_ID',$item->Additional_ID)->where('type','Additional')
                                                                                        ->count();
                                                                                    @endphp
                                                                                    @if ($requestCount < 1)
                                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/Generate/'.$item->id) }}">Generate</a></li>
                                                                                    @endif
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                @endif
                                                                                @if ($item->status_document == 4)
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                @endif
                                                                                @if ($item->status_document == 0)
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Delete({{ $item->id }})">Delete</a></li>
                                                                                @endif
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
                                        <table id="proposalRejectTable" class="example2 ui striped table nowrap unstackable hover">
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
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/view/'.$item->id) }}">View</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Document/BillingFolio/Proposal/Over/document/PDF/'.$item->id) }}">Export</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/log/'.$item->id) }}">LOG</a></li>
                                                                    @endif
                                                                    @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                        @if ($canEditProposal == 1)
                                                                            @if ($item->status_document !== 2)
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/edit/'.$item->id) }}">Edit</a></li>
                                                                                @if ($item->status_document == 3)
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/Generate/'.$item->id) }}">Generate</a></li>
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                @endif
                                                                                @if ($item->status_document == 4)
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @elseif ($rolePermission == 2)
                                                                        @if ($item->Operated_by == $CreateBy)
                                                                            @if ($canEditProposal == 1)
                                                                                @if ($item->status_document !== 2)
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/edit/'.$item->id) }}">Edit</a></li>
                                                                                    @if ($item->status_document == 3)
                                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/Generate/'.$item->id) }}">Generate</a></li>
                                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                    @endif
                                                                                    @if ($item->status_document == 4)
                                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                    @endif
                                                                                    @if ($item->status_document == 0)
                                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Delete({{ $item->id }})">Delete</a></li>
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @elseif ($rolePermission == 3)
                                                                        @if ($canEditProposal == 1)
                                                                            @if ($item->status_document !== 2)
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/edit/'.$item->id) }}">Edit</a></li>
                                                                                @if ($item->status_document == 3)
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/Generate/'.$item->id) }}">Generate</a></li>
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                @endif
                                                                                @if ($item->status_document == 4)
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                @endif
                                                                                @if ($item->status_document == 0)
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Delete({{ $item->id }})">Delete</a></li>
                                                                                @endif
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
                                        <table id="proposalCancelTable" class="example2 ui striped table nowrap unstackable hover">
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
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/view/'.$item->id) }}">View</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Document/BillingFolio/Proposal/Over/document/PDF/'.$item->id) }}">Export</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/log/'.$item->id) }}">LOG</a></li>
                                                                        @endif
                                                                        @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                            @if ($canEditProposal == 1)
                                                                                @if ($item->status_document !== 2)
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/edit/'.$item->id) }}">Edit</a></li>
                                                                                    @if ($item->status_document == 3)
                                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/Generate/'.$item->id) }}">Generate</a></li>
                                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                    @endif
                                                                                    @if ($item->status_document == 4)
                                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                        @elseif ($rolePermission == 2)
                                                                            @if ($item->Operated_by == $CreateBy)
                                                                                @if ($canEditProposal == 1)
                                                                                    @if ($item->status_document !== 2)
                                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/edit/'.$item->id) }}">Edit</a></li>
                                                                                        @if ($item->status_document == 3)
                                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/Generate/'.$item->id) }}">Generate</a></li>
                                                                                            <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                        @endif
                                                                                        @if ($item->status_document == 4)
                                                                                            <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                        @endif
                                                                                        @if ($item->status_document == 0)
                                                                                            <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Delete({{ $item->id }})">Delete</a></li>
                                                                                        @endif
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                        @elseif ($rolePermission == 3)
                                                                            @if ($canEditProposal == 1)
                                                                                @if ($item->status_document !== 2)
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/edit/'.$item->id) }}">Edit</a></li>
                                                                                    @if ($item->status_document == 3)
                                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Document/BillingFolio/Proposal/Over/Generate/'.$item->id) }}">Generate</a></li>
                                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                    @endif
                                                                                    @if ($item->status_document == 4)
                                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                    @endif
                                                                                    @if ($item->status_document == 0)
                                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Delete({{ $item->id }})">Delete</a></li>
                                                                                    @endif
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
    <script type="text/javascript" src="{{ asset('assets/helper/searchTableBillingOver.js')}}"></script>
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
        $(document).on('keyup', '.search-data-Pending', function () {
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
                    url: 'billingover-pending-search-table',
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
                    $('#'+id+'-showingEntries').text(showingEntriesSearchPending(1,count_total, id));
                    $('#'+id+'-paginate').append(paginateSearchPending(count_total, id, getUrl));
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
                        { data: 'number'},
                        { data: 'Receipt' },
                        { data: 'Proposal' },
                        { data: 'Company_Name' },
                        { data: 'Room' },
                        { data: 'Payment' },
                        { data: 'Amount' },
                        { data: 'Category' },
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
                    form.action = `{{ url('/Document/BillingFolio/Proposal/Over/Cancel/') }}/${id}`;
                    $('#myModal').modal('show'); // เปิดโมดอล
                }
            });
        }
        function Delete(id){
            Swal.fire({
            title: "คุณต้องลบใบข้อเสนอนี้ใช่หรือไม่?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "ตกลง",
            cancelButtonText: "ยกเลิก",
            confirmButtonColor: "#28a745",
            dangerMode: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ url('/Document/BillingFolio/Proposal/Over/Delete/') }}/" + id;
                }
            });
        }
    </script>

@endsection
