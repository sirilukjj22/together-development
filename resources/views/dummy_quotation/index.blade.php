@extends('layouts.masterLayout')

</style>
@section('content')
    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <small class="text-muted">Welcome to Dummy Proposal.</small>
                    <div class=""><span class="span1">Dummy Proposal (ต้นแบบเอกสารใบข้อเสนอ)</span></div>
                </div>
                <div class="col-auto">
                    @if (@Auth::user()->roleMenuAdd('proposal',Auth::user()->id) == 1)
                    <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ route('DummyQuotation.create') }}'">
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
                                                <form action="{{route('DummyProposal.Search')}}" method="GET" enctype="multipart/form-data" class="row g-3 basic-form">
                                                    @csrf
                                                    <div class="col-sm-12 col-12">
                                                        <label for="Status">ตัวเลือก</label>
                                                        <select name="Filter" id="Filter" class="form-select" >
                                                            <option value=" "selected disabled>ตัวเลือก</option>
                                                            <option value="All">ทั้งหมด</option>
                                                            <option value="Nocheckin">No Check in date</option>
                                                            <option value="Checkin">Check in & Check out</option>
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
                                                    <div id="User"  class="col-sm-6 col-12" style="display: block">
                                                        <label for="User">User</label>
                                                        <select name="User" class="form-select">
                                                            @if ( Auth::user()->permission == 0)
                                                                @foreach($User as $item)
                                                                    <option value="{{ $item->id }}">{{ @$item->name}}</option>
                                                                @endforeach
                                                            @else
                                                                <option value="" selected disabled>ชื่อผู้ใช้งาน</option>
                                                                @foreach($User as $item)
                                                                    <option value="{{ $item->id }}">{{ @$item->name}}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div id="status" class="col-sm-6 col-12"style="display: block">
                                                        <label for="Status">Status</label>
                                                        <select name="status"  class="form-select">
                                                            <option value=" "selected disabled>สถานะเอกสาร</option>
                                                            <option value="1">Pending</option>
                                                            <option value="2">Awaiting Approval</option>
                                                            <option value="3">Approved</option>
                                                            <option value="5">Generate</option>
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
                                                    document.getElementById('Filter').addEventListener('change', function() {
                                                        const selectedValue = this.value;
                                                        // ทำสิ่งที่คุณต้องการเมื่อมีการเปลี่ยนแปลง
                                                        console.log('Selected filter:', selectedValue);
                                                        const checkinDiv = document.getElementById('checkin');
                                                        const checkoutDiv = document.getElementById('checkout');
                                                        const status = document.getElementById('status');
                                                        const User = document.getElementById('User');
                                                        const checkinput = document.getElementById('checkinput');
                                                        const checkinout = document.getElementById('checkinout');
                                                        const inputcompany = document.getElementById('inputcompany');
                                                        if (selectedValue === 'All') {
                                                            checkinDiv.style.display = 'none';
                                                            checkoutDiv.style.display = 'none';
                                                            User.style.display = 'none';
                                                            status.style.display = 'none';
                                                            checkinput.disabled = true;
                                                            checkinout.disabled = true;
                                                        } else if (selectedValue === 'Nocheckin') {
                                                            checkinDiv.style.display = 'none';
                                                            checkoutDiv.style.display = 'none';
                                                            User.style.display = 'block';
                                                            status.style.display = 'block';
                                                            checkinput.disabled = true;
                                                            checkinout.disabled = true;
                                                        } else if (selectedValue === 'Checkin') {
                                                            checkinDiv.style.display = 'block';
                                                            checkoutDiv.style.display = 'block';
                                                            User.style.display = 'block';
                                                            status.style.display = 'block';
                                                            checkinput.disabled = false;
                                                            checkinout.disabled = false;
                                                        }else if (selectedValue === 'Company') {
                                                            checkinDiv.style.display = 'none';
                                                            checkoutDiv.style.display = 'none';
                                                            User.style.display = 'none';
                                                            status.style.display = 'none';
                                                            checkinput.disabled = true;
                                                            checkinout.disabled = true;
                                                            inputcompany.style.display = 'block';
                                                        }
                                                    });
                                                </script>
                                            </div>
                                        </div><!-- Form Validation -->
                                    </div>
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
                <div class="col">
                    <ol class="breadcrumb d-inline-flex bg-transparent p-0 m-0">
                        <li></li>
                        <li></li>
                        <li></li>
                    </ol>
                </div>
            </div> <!-- Row end  -->
        </div> <!-- Row end  -->
        <div class="row clearfix">
            <div class="col-sm-12 col-12">
                <ul class="nav nav-tabs px-3 border-bottom-0" role="tablist">
                    <li class="nav-item" id="nav1"><a class="nav-link active" data-bs-toggle="tab" href="#nav-Dummy" role="tab" onclick="nav($id='nav1')"><span class="badge" style="background-color:#64748b">{{$Proposalcount}}</span> Dummy Proposal</a></li>{{--ประวัติการแก้ไข--}}
                    <li class="nav-item" id="nav2"><a class="nav-link " data-bs-toggle="tab" href="#nav-Pending" onclick="nav($id='nav2')" role="tab"><span class="badge" style="background-color:#FF6633">{{$Pendingcount}}</span> Pending</a></li>{{--QUOTAION--}}
                    <li class="nav-item" id="nav3"><a class="nav-link" data-bs-toggle="tab" href="#nav-Awaiting" onclick="nav($id='nav3')" role="tab"><span class="badge bg-warning" >{{$Awaitingcount}}</span> Awaiting Approval</a></li>{{--เอกสารออกบิล--}}
                    <li class="nav-item" id="nav4"><a class="nav-link " data-bs-toggle="tab" href="#nav-Approved" onclick="nav($id='nav4')" role="tab"><span class="badge bg-success" >{{$Approvedcount}}</span> Approved</a></li>{{--Doc. number--}}
                    <li class="nav-item" id="nav5"><a class="nav-link" data-bs-toggle="tab" href="#nav-Generate" onclick="nav($id='nav5')"role="tab"><span class="badge " style="background-color: #0ea5e9">{{$Generatecount}}</span> Generate</a></li>
                    <li class="nav-item" id="nav5"><a class="nav-link " data-bs-toggle="tab" href="#nav-Reject" onclick="nav($id='nav6')" role="tab"><span class="badge "style="background-color:#1d4ed8" >{{$Rejectcount}}</span> Reject</a></li>{{--ชื่อ คนแนะนำ ครั้งต่อครั้ง ต่อ เอกสาร--}}
                    <li class="nav-item" id="nav6"><a class="nav-link" data-bs-toggle="tab" href="#nav-Cancel" onclick="nav($id='nav7')" role="tab"><span class="badge bg-danger" >{{$Cancelcount}}</span> Cancel</a></li>{{--% (Percentage) ครั้งต่อครั้ง ต่อ เอกสาร--}}
                </ul>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane fade  show active" id="nav-Dummy" role="tabpanel" rel="0">
                                <div style="min-height: 70vh;" class="mt-2">
                                    <caption class="caption-top">
                                        <div class="top-table-3c">
                                            <div class="top-table-3c_1">
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-color-green lift btn_modal" id="Submit_Documents"><i class="fa fa-paper-plane-o"></i> ส่งเอกสาร</button>
                                                </div>
                                            </div>
                                            <label class="entriespage-label">entries per page :</label>
                                            <select class="entriespage-button" id="search-per-page-dummyproposal" onchange="getPage(1, this.value, 'dummyproposal')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "dummyproposal" ? 'selected' : '' }}>10</option>
                                                <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "dummyproposal" ? 'selected' : '' }}>25</option>
                                                <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "dummyproposal" ? 'selected' : '' }}>50</option>
                                                <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "dummyproposal" ? 'selected' : '' }}>100</option>
                                            </select>
                                            <input class="search-button search-data" id="dummyproposal" style="text-align:left;" placeholder="Search" />
                                        </div>
                                    </caption>
                                    <table id="dummyproposalTable" class="example1 ui striped table nowrap unstackable hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>ID</th>
                                                <th>Company / Individual</th>
                                                <th>Issue Date</th>
                                                <th class="text-center">Date type</th>
                                                <th class="text-center">Check In</th>
                                                <th class="text-center">Check Out</th>
                                                <th>Expiration Date</th>
                                                <th class="text-center">Add.Dis</th>
                                                <th class="text-center">Operated By</th>
                                                <th class="text-center">Approve By</th>
                                                <th class="text-center">Document status</th>
                                                <th class="text-center">Order</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($Proposal))
                                                @foreach ($Proposal as $key => $item)
                                                <tr>
                                                    <td style="text-align: center;">
                                                        @if ($item->status_document == 1)
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input checkbox-select checkbox-{{$key + 1}}" type="checkbox" name="checkbox[]" value="{{ $item->id }}" id="checkbox-{{$key + 1}}" rel="{{ $item->vat }}">
                                                                <label class="form-check-label" for="checkbox-{{$key + 1}}"></label>
                                                            </div>
                                                        @else
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input checkbox-select checkbox-{{$key + 1}}" checked type="checkbox" name="checkbox[]" value="{{ $item->id }}" id="checkbox-{{$key + 1}}" rel="{{ $item->vat }}" disabled>
                                                                <label class="form-check-label" for="checkbox-{{$key + 1}}"></label>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->DummyNo}}</td>
                                                    @if ($item->type_Proposal == 'Company')
                                                        <td>{{ @$item->company->Company_Name}}</td>
                                                    @else
                                                        <td>{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                    @endif
                                                    <td>{{ $item->issue_date }}</td>
                                                    <td style="text-align: center;">{{$item->Date_type ?? '-'}}</td>
                                                    @if ($item->checkin)
                                                    <td style="text-align: center;">{{ $item->checkin}}</td>
                                                    <td style="text-align: center;">{{ $item->checkout }}</td>
                                                    @else
                                                    <td style="text-align: center;">-</td>
                                                    <td style="text-align: center;">-</td>
                                                    @endif
                                                    <td>{{ $item->Expirationdate }}</td>
                                                    <td style="text-align: center;">
                                                        @if ($item->additional_discount == 0)
                                                            -
                                                        @else
                                                            <i class="bi bi-check-lg text-green" ></i>
                                                        @endif
                                                    </td>
                                                    <td style="text-align: center;">
                                                        @if (@$item->userOperated->name == null)
                                                            -
                                                        @else
                                                            {{ @$item->userOperated->name }}
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
                                                    @php
                                                        $CreateBy = Auth::user()->id;
                                                        $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                        $canViewProposal = @Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                                                        $canEditProposal = @Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);
                                                    @endphp
                                                    <td style="text-align: center;">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                            <ul class="dropdown-menu border-0 shadow p-3">
                                                                @if ($rolePermission > 0)
                                                                    @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                        @if ($canViewProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/'.$item->id) }}">View</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" target="_blank" href="{{ url('/Dummy/Proposal/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
                                                                        @endif

                                                                        @if ($canEditProposal == 1)
                                                                            @if ($item->status_document !== 2 && $item->status_document !== 5)
                                                                                @if ($item->status_document == 3 )
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Generate({{ $item->id }})">Generate</a></li>
                                                                                @else
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                                @endif
                                                                                @if ($item->status_document == 0)
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Revice</a></li>
                                                                                @else
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @elseif ($rolePermission == 2)
                                                                        @if ($canViewProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/'.$item->id) }}">View</a></li>
                                                                        @endif
                                                                        @if ($item->Operated_by == $CreateBy)
                                                                            @if ($canViewProposal == 1)
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
                                                                                <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Dummy/Proposal/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                                            @endif
                                                                            @if ($canEditProposal == 1)
                                                                                @if ($item->status_document !== 2 && $item->status_document !== 5)
                                                                                    @if ($item->status_document == 3)
                                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Generate({{ $item->id }})">Generate</a></li>
                                                                                    @else
                                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                                    @endif
                                                                                    @if ($item->status_document == 0)
                                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Revice</a></li>
                                                                                    @else
                                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @elseif ($rolePermission == 3)
                                                                        @if ($canViewProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/'.$item->id) }}">View</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" target="_blank" href="{{ url('/Dummy/Proposal/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                                        @endif
                                                                        @if ($canEditProposal == 1)
                                                                            @if ($item->status_document !== 2 && $item->status_document !== 5)
                                                                                @if ($item->status_document == 3)
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Generate({{ $item->id }})">Generate</a></li>
                                                                                @else
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                                @endif
                                                                                @if ($item->status_document == 0)
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Revice</a></li>
                                                                                @else
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    @if ($canViewProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/'.$item->id) }}">View</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Dummy/Proposal/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
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
                                    <input type="hidden" id="get-total-dummyproposal" value="{{ $Proposal->total() }}">
                                    <input type="hidden" id="currentPage-dummyproposal" value="1">
                                    <caption class="caption-bottom">
                                        <div class="md-flex-bt-i-c">
                                            <p class="py2" id="dummyproposal-showingEntries">{{ showingEntriesTable($Proposal, 'dummyproposal') }}</p>
                                            <div id="dummyproposal-paginate">
                                                {!! paginateTable($Proposal, 'dummyproposal') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                            </div>
                                        </div>
                                    </caption>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-Pending" role="tabpanel" rel="0">
                                <div style="min-height: 70vh;" class="mt-2">
                                    <caption class="caption-top">
                                        <div class="top-table-3c">
                                            <div class="top-table-3c_1">
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-color-green lift btn_modal" id="Submit_Documents"><i class="fa fa-paper-plane-o"></i> ส่งเอกสาร</button>
                                                </div>
                                            </div>
                                            <label class="entriespage-label">entries per page :</label>
                                            <select class="entriespage-button" id="search-per-page-dummyproposalPending" onchange="getPagePending(1, this.value, 'dummyproposalPending')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "dummyproposalPending" ? 'selected' : '' }}>10</option>
                                                <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "dummyproposalPending" ? 'selected' : '' }}>25</option>
                                                <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "dummyproposalPending" ? 'selected' : '' }}>50</option>
                                                <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "dummyproposalPending" ? 'selected' : '' }}>100</option>
                                            </select>
                                            <input class="search-button search-data-Pending" id="dummyproposalPending" style="text-align:left;" placeholder="Search" />
                                        </div>
                                    </caption>
                                    <table id="dummyproposalPendingTable" class="example2 ui striped table nowrap unstackable hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>ID</th>
                                                <th>Company / Individual</th>
                                                <th>Issue Date</th>
                                                <th class="text-center">Date type</th>
                                                <th class="text-center">Check In</th>
                                                <th class="text-center">Check Out</th>
                                                <th>Expiration Date</th>
                                                <th class="text-center">Add.Dis</th>
                                                <th class="text-center">Operated By</th>
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
                                                                <input class="form-check-input checkbox-select checkbox-{{$key + 1}}"checked type="checkbox" name="checkbox[]" value="{{ $item->id }}" id="checkbox-{{$key + 1}}" rel="{{ $item->vat }}" disabled>
                                                                <label class="form-check-label" for="checkbox-{{$key + 1}}"></label>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->DummyNo}}</td>
                                                    @if ($item->type_Proposal == 'Company')
                                                        <td>{{ @$item->company->Company_Name}}</td>
                                                    @else
                                                        <td>{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                    @endif
                                                    <td>{{ $item->issue_date }}</td>
                                                    <td style="text-align: center;">{{$item->Date_type ?? '-'}}</td>
                                                    @if ($item->checkin)
                                                    <td style="text-align: center;">{{ $item->checkin}}</td>
                                                    <td style="text-align: center;">{{ $item->checkout }}</td>
                                                    @else
                                                    <td style="text-align: center;">-</td>
                                                    <td style="text-align: center;">-</td>
                                                    @endif
                                                    <td>{{ $item->Expirationdate }}</td>
                                                    <td style="text-align: center;">
                                                        @if ($item->additional_discount == 0)
                                                            -
                                                        @else
                                                            <i class="bi bi-check-lg text-green" ></i>
                                                        @endif
                                                    </td>
                                                    <td style="text-align: center;">
                                                        @if (@$item->userOperated->name == null)
                                                            -
                                                        @else
                                                            {{ @$item->userOperated->name }}
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
                                                    @php
                                                        $CreateBy = Auth::user()->id;
                                                        $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                        $canViewProposal = @Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                                                        $canEditProposal = @Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);
                                                    @endphp
                                                    <td style="text-align: center;">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                            <ul class="dropdown-menu border-0 shadow p-3">
                                                                @if ($rolePermission > 0)
                                                                    @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                        @if ($canViewProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/'.$item->id) }}">View</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" target="_blank" href="{{ url('/Dummy/Proposal/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
                                                                        @endif

                                                                        @if ($canEditProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                            @if ($item->status_document == 0)
                                                                                <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Revice</a></li>
                                                                            @else
                                                                                <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                            @endif
                                                                        @endif
                                                                    @elseif ($rolePermission == 2)
                                                                        @if ($canViewProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/'.$item->id) }}">View</a></li>
                                                                        @endif
                                                                        @if ($item->Operated_by == $CreateBy)
                                                                            @if ($canViewProposal == 1)
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
                                                                            @endif
                                                                            @if ($canEditProposal == 1)
                                                                                <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Dummy/Proposal/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                                @if ($item->status_document == 0)
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Revice</a></li>
                                                                                @else
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @elseif ($rolePermission == 3)
                                                                        @if ($canViewProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/'.$item->id) }}">View</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" target="_blank" href="{{ url('/Dummy/Proposal/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                                        @endif
                                                                        @if ($canEditProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                            @if ($item->status_document == 0)
                                                                                <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Revice</a></li>
                                                                            @else
                                                                                <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    @if ($canViewProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/'.$item->id) }}">View</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Dummy/Proposal/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
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
                                    <input type="hidden" id="get-total-dummyproposalPending" value="{{ $Pending->total() }}">
                                    <input type="hidden" id="currentPage-dummyproposalPending" value="1">
                                    <caption class="caption-bottom">
                                        <div class="md-flex-bt-i-c">
                                            <p class="py2" id="dummyproposalPending-showingEntries">{{ showingEntriesTablePending($Pending, 'dummyproposalPending') }}</p>
                                                <div id="dummyproposalPending-paginate">
                                                    {!! paginateTablePending($Pending, 'dummyproposalPending') !!} <!-- ข้อมูล, ชื่อตาราง -->
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
                                            <select class="entriespage-button" id="search-per-page-dummyproposalAwaiting" onchange="getPageAwaiting(1, this.value, 'dummyproposalAwaiting')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "dummyproposalAwaiting" ? 'selected' : '' }}>10</option>
                                                <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "dummyproposalAwaiting" ? 'selected' : '' }}>25</option>
                                                <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "dummyproposalAwaiting" ? 'selected' : '' }}>50</option>
                                                <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "dummyproposalAwaiting" ? 'selected' : '' }}>100</option>
                                            </select>
                                            <input class="search-button search-data-Awaiting" id="dummyproposalAwaiting" style="text-align:left;" placeholder="Search" />
                                        </div>
                                    </caption>
                                    <table id="dummyproposalAwaitingTable" class="example2 ui striped table nowrap unstackable hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>ID</th>
                                                <th>Company / Individual</th>
                                                <th>Issue Date</th>
                                                <th class="text-center">Date type</th>
                                                <th class="text-center">Check In</th>
                                                <th class="text-center">Check Out</th>
                                                <th>Expiration Date</th>
                                                <th class="text-center">Add.Dis</th>
                                                <th class="text-center">Operated By</th>
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
                                                                <input class="form-check-input checkbox-select checkbox-{{$key + 1}}"checked type="checkbox" name="checkbox[]" value="{{ $item->id }}" id="checkbox-{{$key + 1}}" rel="{{ $item->vat }}" disabled>
                                                                <label class="form-check-label" for="checkbox-{{$key + 1}}"></label>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->DummyNo}}</td>
                                                    @if ($item->type_Proposal == 'Company')
                                                        <td>{{ @$item->company->Company_Name}}</td>
                                                    @else
                                                        <td>{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                    @endif
                                                    <td>{{ $item->issue_date }}</td>
                                                    <td style="text-align: center;">{{$item->Date_type ?? '-'}}</td>
                                                    @if ($item->checkin)
                                                    <td style="text-align: center;">{{ $item->checkin}}</td>
                                                    <td style="text-align: center;">{{ $item->checkout }}</td>
                                                    @else
                                                    <td style="text-align: center;">-</td>
                                                    <td style="text-align: center;">-</td>
                                                    @endif
                                                    <td>{{ $item->Expirationdate }}</td>
                                                    <td style="text-align: center;">
                                                        @if ($item->additional_discount == 0)
                                                            -
                                                        @else
                                                            <i class="bi bi-check-lg text-green" ></i>
                                                        @endif
                                                    </td>
                                                    <td style="text-align: center;">
                                                        @if (@$item->userOperated->name == null)
                                                            -
                                                        @else
                                                            {{ @$item->userOperated->name }}
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
                                                    @php
                                                        $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                        $canViewProposal = @Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                                                    @endphp
                                                    <td style="text-align: center;">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                            <ul class="dropdown-menu border-0 shadow p-3">
                                                                @if ($rolePermission == 1 || $rolePermission == 2 || $rolePermission == 3)
                                                                    @if ($canViewProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/'.$item->id) }}">View</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Dummy/Proposal/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
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
                                    <input type="hidden" id="get-total-dummyproposalAwaiting" value="{{ $Awaiting->total() }}">
                                    <input type="hidden" id="currentPage-dummyproposalAwaiting" value="1">
                                    <caption class="caption-bottom">
                                        <div class="md-flex-bt-i-c">
                                            <p class="py2" id="dummyproposalAwaiting-showingEntries">{{ showingEntriesTableAwaiting($Awaiting, 'dummyproposalAwaiting') }}</p>
                                                <div id="dummyproposalAwaiting-paginate">
                                                    {!! paginateTableAwaiting($Awaiting, 'dummyproposalAwaiting') !!} <!-- ข้อมูล, ชื่อตาราง -->
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
                                            <select class="entriespage-button" id="search-per-page-dummyproposalApproved" onchange="getPageApproved(1, this.value, 'dummyproposalApproved')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "dummyproposalApproved" ? 'selected' : '' }}>10</option>
                                                <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "dummyproposalApproved" ? 'selected' : '' }}>25</option>
                                                <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "dummyproposalApproved" ? 'selected' : '' }}>50</option>
                                                <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "dummyproposalApproved" ? 'selected' : '' }}>100</option>
                                            </select>
                                            <input class="search-button search-data-Approved" id="dummyproposalApproved" style="text-align:left;" placeholder="Search" />
                                        </div>
                                    </caption>
                                    <table id="dummyproposalApprovedTable" class="example2 ui striped table nowrap unstackable hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>ID</th>
                                                <th>Company / Individual</th>
                                                <th>Issue Date</th>
                                                <th class="text-center">Date type</th>
                                                <th class="text-center">Check In</th>
                                                <th class="text-center">Check Out</th>
                                                <th>Expiration Date</th>
                                                <th class="text-center">Add.Dis</th>
                                                <th class="text-center">Operated By</th>
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
                                                                <input class="form-check-input checkbox-select checkbox-{{$key + 1}}" checked type="checkbox" name="checkbox[]" value="{{ $item->id }}" id="checkbox-{{$key + 1}}" rel="{{ $item->vat }}" disabled>
                                                                <label class="form-check-label" for="checkbox-{{$key + 1}}"></label>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->DummyNo}}</td>
                                                    @if ($item->type_Proposal == 'Company')
                                                        <td>{{ @$item->company->Company_Name}}</td>
                                                    @else
                                                        <td>{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                    @endif
                                                    <td>{{ $item->issue_date }}</td>
                                                    <td style="text-align: center;">{{$item->Date_type ?? '-'}}</td>
                                                    @if ($item->checkin)
                                                    <td style="text-align: center;">{{ $item->checkin}}</td>
                                                    <td style="text-align: center;">{{ $item->checkout }}</td>
                                                    @else
                                                    <td style="text-align: center;">-</td>
                                                    <td style="text-align: center;">-</td>
                                                    @endif
                                                    <td>{{ $item->Expirationdate }}</td>
                                                    <td style="text-align: center;">
                                                        @if ($item->additional_discount == 0)
                                                            -
                                                        @else
                                                            <i class="bi bi-check-lg text-green" ></i>
                                                        @endif
                                                    </td>
                                                    <td style="text-align: center;">
                                                        @if (@$item->userOperated->name == null)
                                                            -
                                                        @else
                                                            {{ @$item->userOperated->name }}
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
                                                    @php
                                                        $CreateBy = Auth::user()->id;
                                                        $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                        $canViewProposal = @Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                                                        $canEditProposal = @Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);
                                                    @endphp
                                                    <td style="text-align: center;">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                            <ul class="dropdown-menu border-0 shadow p-3">
                                                                @if ($rolePermission > 0)
                                                                    @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                        @if ($canViewProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/'.$item->id) }}">View</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" target="_blank" href="{{ url('/Dummy/Proposal/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
                                                                        @endif

                                                                        @if ($canEditProposal == 1)
                                                                            @if ($item->status_document !== 2)
                                                                                @if ($item->status_document == 3 ||$item->status_document == 1 && $item->SpecialDiscountBath == 0 && $item->SpecialDiscount == 0)
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Generate({{ $item->id }})">Generate</a></li>
                                                                                @else
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                                @endif
                                                                                @if ($item->status_document == 0)
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Revice({{ $item->id }})">Revice</a></li>
                                                                                @else
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @elseif ($rolePermission == 2)
                                                                        @if ($canViewProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/'.$item->id) }}">View</a></li>
                                                                        @endif
                                                                        @if ($item->Operated_by == $CreateBy)
                                                                            @if ($canViewProposal == 1)
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
                                                                                <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Dummy/Proposal/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                                            @endif
                                                                            @if ($canEditProposal == 1)
                                                                                @if ($item->status_document !== 2)
                                                                                    @if ($item->status_document == 3)
                                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Generate({{ $item->id }})">Generate</a></li>
                                                                                    @else
                                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                                    @endif
                                                                                    @if ($item->status_document == 0)
                                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Revice({{ $item->id }})">Revice</a></li>
                                                                                    @else
                                                                                        <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @elseif ($rolePermission == 3)
                                                                        @if ($canViewProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/'.$item->id) }}">View</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" target="_blank" href="{{ url('/Dummy/Proposal/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                                        @endif
                                                                        @if ($canEditProposal == 1)
                                                                            @if ($item->status_document !== 2)
                                                                                @if ($item->status_document == 3)
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Generate({{ $item->id }})">Generate</a></li>
                                                                                @else
                                                                                    <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                                @endif
                                                                                @if ($item->status_document == 0)
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Revice({{ $item->id }})">Revice</a></li>
                                                                                @else
                                                                                    <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    @if ($canViewProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/'.$item->id) }}">View</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Dummy/Proposal/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
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
                                    <input type="hidden" id="get-total-dummyproposalApproved" value="{{ $Approved->total() }}">
                                    <input type="hidden" id="currentPage-dummyproposalApproved" value="1">
                                    <caption class="caption-bottom">
                                        <div class="md-flex-bt-i-c">
                                            <p class="py2" id="dummyproposalApproved-showingEntries">{{ showingEntriesTableApproved($Approved, 'dummyproposalApproved') }}</p>
                                                <div id="dummyproposalApproved-paginate">
                                                    {!! paginateTableApproved($Approved, 'dummyproposalApproved') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                                </div>
                                        </div>
                                    </caption>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-Generate" role="tabpanel" rel="0">
                                <div style="min-height: 70vh;" class="mt-2">
                                    <caption class="caption-top">
                                        <div class="flex-end-g2">
                                            <label class="entriespage-label">entries per page :</label>
                                            <select class="entriespage-button" id="search-per-page-dummyproposalGenerate" onchange="getPageGenerate(1, this.value, 'dummyproposalGenerate')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "dummyproposalGenerate" ? 'selected' : '' }}>10</option>
                                                <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "dummyproposalGenerate" ? 'selected' : '' }}>25</option>
                                                <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "dummyproposalGenerate" ? 'selected' : '' }}>50</option>
                                                <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "dummyproposalGenerate" ? 'selected' : '' }}>100</option>
                                            </select>
                                            <input class="search-button search-data-Generate" id="dummyproposalGenerate" style="text-align:left;" placeholder="Search" />
                                        </div>
                                    </caption>
                                    <table id="dummyproposalGenerateTable" class="example2 ui striped table nowrap unstackable hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>ID</th>
                                                <th>Company / Individual</th>
                                                <th>Issue Date</th>
                                                <th class="text-center">Date type</th>
                                                <th class="text-center">Check In</th>
                                                <th class="text-center">Check Out</th>
                                                <th>Expiration Date</th>
                                                <th class="text-center">Add.Dis</th>
                                                <th class="text-center">Operated By</th>
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
                                                                <input class="form-check-input checkbox-select checkbox-{{$key + 1}}" checked type="checkbox" name="checkbox[]" value="{{ $item->id }}" id="checkbox-{{$key + 1}}" rel="{{ $item->vat }}" disabled>
                                                                <label class="form-check-label" for="checkbox-{{$key + 1}}"></label>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->DummyNo}}</td>
                                                    @if ($item->type_Proposal == 'Company')
                                                        <td>{{ @$item->company->Company_Name}}</td>
                                                    @else
                                                        <td>{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                    @endif
                                                    <td>{{ $item->issue_date }}</td>
                                                    <td style="text-align: center;">{{$item->Date_type ?? '-'}}</td>
                                                    @if ($item->checkin)
                                                    <td style="text-align: center;">{{ $item->checkin}}</td>
                                                    <td style="text-align: center;">{{ $item->checkout }}</td>
                                                    @else
                                                    <td style="text-align: center;">-</td>
                                                    <td style="text-align: center;">-</td>
                                                    @endif
                                                    <td>{{ $item->Expirationdate }}</td>
                                                    <td style="text-align: center;">
                                                        @if ($item->additional_discount == 0)
                                                            -
                                                        @else
                                                            <i class="bi bi-check-lg text-green" ></i>
                                                        @endif
                                                    </td>
                                                    <td style="text-align: center;">
                                                        @if (@$item->userOperated->name == null)
                                                            -
                                                        @else
                                                            {{ @$item->userOperated->name }}
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
                                                    @php
                                                        $CreateBy = Auth::user()->id;
                                                        $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                        $canViewProposal = @Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                                                        $canEditProposal = @Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);
                                                    @endphp
                                                    <td style="text-align: center;">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                            <ul class="dropdown-menu border-0 shadow p-3">
                                                                @if ($rolePermission == 1 || $rolePermission == 2 || $rolePermission == 3)
                                                                    @if ($canViewProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/'.$item->id) }}">View</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Dummy/Proposal/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
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
                                    <input type="hidden" id="get-total-dummyproposalGenerate" value="{{ $Generate->total() }}">
                                    <input type="hidden" id="currentPage-dummyproposalGenerate" value="1">
                                    <caption class="caption-bottom">
                                        <div class="md-flex-bt-i-c">
                                            <p class="py2" id="dummyproposalGenerate-showingEntries">{{ showingEntriesTableGenerate($Generate, 'dummyproposalGenerate') }}</p>
                                            <div id="dummyproposalGenerate-paginate">
                                                {!! paginateTableGenerate($Generate, 'dummyproposalGenerate') !!} <!-- ข้อมูล, ชื่อตาราง -->
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
                                            <select class="entriespage-button" id="search-per-page-dummyproposalReject" onchange="getPageReject(1, this.value, 'dummyproposalReject')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "dummyproposalReject" ? 'selected' : '' }}>10</option>
                                                <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "dummyproposalReject" ? 'selected' : '' }}>25</option>
                                                <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "dummyproposalReject" ? 'selected' : '' }}>50</option>
                                                <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "dummyproposalReject" ? 'selected' : '' }}>100</option>
                                            </select>
                                            <input class="search-button search-data-Reject" id="dummyproposalReject" style="text-align:left;" placeholder="Search" />
                                        </div>
                                    </caption>
                                    <table id="dummyproposalRejectTable" class="example2 ui striped table nowrap unstackable hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>ID</th>
                                                <th>Company / Individual</th>
                                                <th>Issue Date</th>
                                                <th class="text-center">Date type</th>
                                                <th class="text-center">Check In</th>
                                                <th class="text-center">Check Out</th>
                                                <th>Expiration Date</th>
                                                <th class="text-center">Add.Dis</th>
                                                <th class="text-center">Operated By</th>
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
                                                                <input class="form-check-input checkbox-select checkbox-{{$key + 1}}" checked type="checkbox" name="checkbox[]" value="{{ $item->id }}" id="checkbox-{{$key + 1}}" rel="{{ $item->vat }}" disabled>
                                                                <label class="form-check-label" for="checkbox-{{$key + 1}}"></label>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->DummyNo}}</td>
                                                    @if ($item->type_Proposal == 'Company')
                                                        <td>{{ @$item->company->Company_Name}}</td>
                                                    @else
                                                        <td>{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                    @endif
                                                    <td>{{ $item->issue_date }}</td>
                                                    <td style="text-align: center;">{{$item->Date_type ?? '-'}}</td>
                                                    @if ($item->checkin)
                                                    <td style="text-align: center;">{{ $item->checkin}}</td>
                                                    <td style="text-align: center;">{{ $item->checkout }}</td>
                                                    @else
                                                    <td style="text-align: center;">-</td>
                                                    <td style="text-align: center;">-</td>
                                                    @endif
                                                    <td>{{ $item->Expirationdate }}</td>
                                                    <td style="text-align: center;">
                                                        @if ($item->additional_discount == 0)
                                                            -
                                                        @else
                                                            <i class="bi bi-check-lg text-green" ></i>
                                                        @endif
                                                    </td>
                                                    <td style="text-align: center;">
                                                        @if (@$item->userOperated->name == null)
                                                            -
                                                        @else
                                                            {{ @$item->userOperated->name }}
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
                                                    @php
                                                        $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                        $canViewProposal = @Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                                                        $canEditProposal = @Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);
                                                        $CreateBy = Auth::user()->id;
                                                    @endphp
                                                    <td style="text-align: center;">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                            <ul class="dropdown-menu border-0 shadow p-3">
                                                                @if ($rolePermission > 0)
                                                                    @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                        @if ($canViewProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/'.$item->id) }}">View</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" target="_blank" href="{{ url('/Dummy/Proposal/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
                                                                        @endif
                                                                        @if ($canEditProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                        @endif
                                                                    @elseif ($rolePermission == 2)
                                                                        @if ($canViewProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/'.$item->id) }}">View</a></li>
                                                                        @endif
                                                                        @if ($item->Operated_by == $CreateBy)
                                                                            @if ($canViewProposal == 1)
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
                                                                            @endif
                                                                            @if ($canEditProposal == 1)
                                                                                <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Dummy/Proposal/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                                <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                            @endif
                                                                        @endif
                                                                    @elseif ($rolePermission == 3)
                                                                        @if ($canViewProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/'.$item->id) }}">View</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" target="_blank" href="{{ url('/Dummy/Proposal/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                                        @endif
                                                                        @if ($canEditProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Cancel({{ $item->id }})">Cancel</a></li>
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    @if ($canViewProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/'.$item->id) }}">View</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Dummy/Proposal/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
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
                                    <input type="hidden" id="get-total-dummyproposalReject" value="{{ $Reject->total() }}">
                                    <input type="hidden" id="currentPage-dummyproposalReject" value="1">
                                    <caption class="caption-bottom">
                                        <div class="md-flex-bt-i-c">
                                            <p class="py2" id="dummyproposalReject-showingEntries">{{ showingEntriesTableReject($Reject, 'dummyproposalReject') }}</p>
                                                <div id="dummyproposalReject-paginate">
                                                    {!! paginateTableReject($Reject, 'dummyproposalReject') !!} <!-- ข้อมูล, ชื่อตาราง -->
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
                                            <select class="entriespage-button" id="search-per-page-dummyproposalCancel" onchange="getPageCancel(1, this.value, 'dummyproposalCancel')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "dummyproposalCancel" ? 'selected' : '' }}>10</option>
                                                <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "dummyproposalCancel" ? 'selected' : '' }}>25</option>
                                                <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "dummyproposalCancel" ? 'selected' : '' }}>50</option>
                                                <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "dummyproposalCancel" ? 'selected' : '' }}>100</option>
                                            </select>
                                            <input class="search-button search-data-Cancel" id="dummyproposalCancel" style="text-align:left;" placeholder="Search" />
                                        </div>
                                    </caption>
                                    <table id="dummyproposalCancelTable" class="example2 ui striped table nowrap unstackable hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>ID</th>
                                                <th>Company / Individual</th>
                                                <th>Issue Date</th>
                                                <th class="text-center">Date type</th>
                                                <th class="text-center">Check In</th>
                                                <th class="text-center">Check Out</th>
                                                <th>Expiration Date</th>
                                                <th class="text-center">Add.Dis</th>
                                                <th class="text-center">Operated By</th>
                                                <th class="text-center">Approve By</th>
                                                <th class="text-center">Document status</th>
                                                <th class="text-center">Order</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($Cancel))
                                                @foreach ($Cancel as $key => $item)
                                                <tr>
                                                    <td style="text-align: center;">
                                                        @if ($item->status_document == 1)
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input checkbox-select checkbox-{{$key + 1}}" type="checkbox" name="checkbox[]" value="{{ $item->id }}" id="checkbox-{{$key + 1}}" rel="{{ $item->vat }}">
                                                                <label class="form-check-label" for="checkbox-{{$key + 1}}"></label>
                                                            </div>
                                                        @else
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input checkbox-select checkbox-{{$key + 1}}" checked type="checkbox" name="checkbox[]" value="{{ $item->id }}" id="checkbox-{{$key + 1}}" rel="{{ $item->vat }}" disabled>
                                                                <label class="form-check-label" for="checkbox-{{$key + 1}}"></label>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->DummyNo}}</td>
                                                    @if ($item->type_Proposal == 'Company')
                                                        <td>{{ @$item->company->Company_Name}}</td>
                                                    @else
                                                        <td>{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                                    @endif
                                                    <td>{{ $item->issue_date }}</td>
                                                    <td style="text-align: center;">{{$item->Date_type ?? '-'}}</td>
                                                    @if ($item->checkin)
                                                    <td style="text-align: center;">{{ $item->checkin}}</td>
                                                    <td style="text-align: center;">{{ $item->checkout }}</td>
                                                    @else
                                                    <td style="text-align: center;">-</td>
                                                    <td style="text-align: center;">-</td>
                                                    @endif
                                                    <td>{{ $item->Expirationdate }}</td>
                                                    <td style="text-align: center;">
                                                        @if ($item->additional_discount == 0)
                                                            -
                                                        @else
                                                            <i class="bi bi-check-lg text-green" ></i>
                                                        @endif
                                                    </td>
                                                    <td style="text-align: center;">
                                                        @if (@$item->userOperated->name == null)
                                                            -
                                                        @else
                                                            {{ @$item->userOperated->name }}
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
                                                    @php
                                                        $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                        $canViewProposal = @Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                                                        $canEditProposal = @Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);
                                                        $CreateBy = Auth::user()->id;
                                                    @endphp
                                                    <td style="text-align: center;">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                            <ul class="dropdown-menu border-0 shadow p-3">
                                                                @if ($rolePermission > 0)
                                                                    @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                        @if ($canViewProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/'.$item->id) }}">View</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" target="_blank" href="{{ url('/Dummy/Proposal/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
                                                                        @endif
                                                                        @if ($canEditProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Revice({{ $item->id }})">Revice</a></li>
                                                                        @endif
                                                                    @elseif ($rolePermission == 2)
                                                                        @if ($canViewProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/'.$item->id) }}">View</a></li>
                                                                        @endif
                                                                        @if ($item->Operated_by == $CreateBy)
                                                                            @if ($canViewProposal == 1)
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
                                                                            @endif
                                                                            @if ($canEditProposal == 1)
                                                                                <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Dummy/Proposal/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                                <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Revice({{ $item->id }})">Revice</a></li>
                                                                            @endif
                                                                        @endif
                                                                    @elseif ($rolePermission == 3)
                                                                        @if ($canViewProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/'.$item->id) }}">View</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" target="_blank" href="{{ url('/Dummy/Proposal/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                                        @endif
                                                                        @if ($canEditProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded"href="javascript:void(0);" onclick="Revice({{ $item->id }})">Revice</a></li>
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    @if ($canViewProposal == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/'.$item->id) }}">View</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Dummy/Proposal/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Dummy/Proposal/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
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
                                    <input type="hidden" id="get-total-dummyproposalCancel" value="{{ $Cancel->total() }}">
                                    <input type="hidden" id="currentPage-dummyproposalCancel" value="1">
                                    <caption class="caption-bottom">
                                        <div class="md-flex-bt-i-c">
                                            <p class="py2" id="dummyproposalCancel-showingEntries">{{ showingEntriesTableCancel($Cancel, 'dummyproposalCancel') }}</p>
                                                <div id="dummyproposalCancel-paginate">
                                                    {!! paginateTableCancel($Cancel, 'dummyproposalCancel') !!} <!-- ข้อมูล, ชื่อตาราง -->
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
    <script type="text/javascript" src="{{ asset('assets/helper/searchTabledummyproposal.js')}}"></script>
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
        const table_name = ['dummyproposalTable','dummyproposalPendingTable','dummyproposalAwaitingTable','dummyproposalApprovedTable','dummyproposalRejectTable','dummyproposalCancelTable'];
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
            console.log(table_name);
                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                    url: '/DummyProposal-search-table',
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
                                { targets: [0,4,5,6,7,8,9,10,11,12], className: 'dt-center td-content-center' },
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
                        { data: 'DummyNo' },
                        { data: 'Company_Name' },
                        { data: 'IssueDate' },
                        { data: 'Type' },
                        { data: 'CheckIn' },
                        { data: 'CheckOut' },
                        { data: 'ExpirationDate' },
                        { data: 'DiscountP' },
                        { data: 'Operated' },
                        { data: 'Approve' },
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
                    url: '/DummyProposal-Pending-search-table',
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
                                { targets: [0,4,5,6,7,8,9,10,11,12], className: 'dt-center td-content-center' },
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
                        { data: 'DummyNo' },
                        { data: 'Company_Name' },
                         { data: 'IssueDate' },
                        { data: 'Type' },
                        { data: 'CheckIn' },
                        { data: 'CheckOut' },
                        { data: 'ExpirationDate' },
                        { data: 'DiscountP' },
                        { data: 'Operated' },
                        { data: 'Approve' },
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
                    url: '/DummyProposal-Awaiting-search-table',
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
                                { targets: [0,4,5,6,7,8,9,10,11,12], className: 'dt-center td-content-center' },
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
                        { data: 'DummyNo' },
                        { data: 'Company_Name' },
                        { data: 'IssueDate' },
                        { data: 'Type' },
                        { data: 'CheckIn' },
                        { data: 'CheckOut' },
                        { data: 'ExpirationDate' },
                        { data: 'DiscountP' },
                        { data: 'Operated' },
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

                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                    url: '/DummyProposal-Approved-search-table',
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
                                { targets: [0,4,5,6,7,8,9,10,11,12], className: 'dt-center td-content-center' },
                    ],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    },
                    columns: [
                        { data: 'number' },
                        { data: 'DummyNo' },
                        { data: 'Company_Name' },
                         { data: 'IssueDate' },
                        { data: 'Type' },
                        { data: 'CheckIn' },
                        { data: 'CheckOut' },
                        { data: 'ExpirationDate' },
                        { data: 'DiscountP' },
                        { data: 'Operated' },
                        { data: 'Approve' },
                        { data: 'DocumentStatus' },
                        { data: 'btn_action' }
                    ],

                });


            document.getElementById(id).focus();
        });
        $(document).on('keyup', '.search-data-Generate', function () {
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
                    url: '/DummyProposal-Generate-search-table',
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
                    $('#'+id+'-showingEntries').text(showingEntriesSearchGenerate(1,count_total, id));
                    $('#'+id+'-paginate').append(paginateSearchGenerate(count_total, id, getUrl));
                },
                    columnDefs: [
                                { targets: [0,4,5,6,7,8,9,10,11,12], className: 'dt-center td-content-center' },
                    ],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    },
                    columns: [
                        { data: 'number' },
                        { data: 'DummyNo' },
                        { data: 'Company_Name' },
                         { data: 'IssueDate' },
                        { data: 'Type' },
                        { data: 'CheckIn' },
                        { data: 'CheckOut' },
                        { data: 'ExpirationDate' },
                        { data: 'DiscountP' },
                        { data: 'Operated' },
                        { data: 'Approve' },
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
                    url: '/DummyProposal-Reject-search-table',
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
                                { targets: [0,4,5,6,7,8,9,10,11,12], className: 'dt-center td-content-center' },
                    ],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    },
                    columns: [
                        { data: 'number' },
                        { data: 'DummyNo' },
                        { data: 'Company_Name' },
                         { data: 'IssueDate' },
                        { data: 'Type' },
                        { data: 'CheckIn' },
                        { data: 'CheckOut' },
                        { data: 'ExpirationDate' },
                        { data: 'DiscountP' },
                        { data: 'Operated' },
                        { data: 'Approve' },
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
                    url: '/DummyProposal-Cancel-search-table',
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
                                { targets: [0,4,5,6,7,8,9,10,11,12], className: 'dt-center td-content-center' },
                    ],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    },
                    columns: [
                        { data: 'number' },
                        { data: 'DummyNo' },
                        { data: 'Company_Name' },
                         { data: 'IssueDate' },
                        { data: 'Type' },
                        { data: 'CheckIn' },
                        { data: 'CheckOut' },
                        { data: 'ExpirationDate' },
                        { data: 'DiscountP' },
                        { data: 'Operated' },
                        { data: 'Approve' },
                        { data: 'DocumentStatus' },
                        { data: 'btn_action' }
                    ],

                });


            document.getElementById(id).focus();
        });
    </script>
    <script>
        function Generate(id){
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
                    window.location.href = "{{ url('/Dummy/Proposal/Generate/') }}/" + id;
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
                    window.location.href = "{{ url('/Dummy/Proposal/cancel/') }}/" + id;
                }
            });
        }
        function Revice(id){
            Swal.fire({
            title: "คุณต้องการเปิดการใช้งานรายการนี้ใช่หรือไม่?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "ตกลง",
            cancelButtonText: "ยกเลิก",
            confirmButtonColor: "#28a745",
            dangerMode: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ url('/Dummy/Proposal/cancel/') }}/" + id;
                }
            });
        }
    </script>


@endsection
