@extends('layouts.masterLayout')

@section('content')
    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <small class="text-muted">Welcome to Proposal.</small>
                    <div class=""><span class="span1">Proposal (ข้อเสนอ)</span></div>
                </div>
                <div class="col-auto">
                    @if (@Auth::user()->roleMenuAdd('Proposal',Auth::user()->id) == 1)
                    <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ route('Quotation.create') }}'">
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
                                                <form action="{{route('Quotation.Search')}}" method="GET" enctype="multipart/form-data" class="row g-3 basic-form">
                                                    @csrf
                                                    <div class="col-sm-12 col-12">
                                                        <label for="Status">ตัวเลือก</label>
                                                        <select name="Filter" id="Filter" class="form-select" >
                                                            <option value=" "selected disabled>ตัวเลือก</option>
                                                            <option value="All">ทั้งหมด</option>
                                                            <option value="Nocheckin">No Check in date</option>
                                                            <option value="Checkin">Check in & Check out</option>
                                                        </select>
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
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <ul class="nav nav-tabs px-3 border-bottom-0" role="tablist">
                        <li class="nav-item" id="nav1"><a class="nav-link active" data-bs-toggle="tab" href="#nav-Dummy" role="tab" onclick="nav($id='nav1')"> <span class="badge" style="background-color:#64748b">{{$Proposalcount}}</span> Proposal</a></li>
                        <li class="nav-item" id="nav2"><a class="nav-link" data-bs-toggle="tab" href="#nav-Pending" role="tab" onclick="nav($id='nav2')"><span class="badge" style="background-color:#FF6633">{{$Pendingcount}}</span> Pending</a></li>
                        <li class="nav-item" id="nav3"><a class="nav-link" data-bs-toggle="tab" href="#nav-Awaiting" role="tab" onclick="nav($id='nav3')"><span class="badge bg-warning" >{{$Awaitingcount}}</span> Awaiting Approval</a></li>
                        <li class="nav-item" id="nav4"><a class="nav-link" data-bs-toggle="tab" href="#nav-Approved" role="tab" onclick="nav($id='nav4')"><span class="badge bg-success" >{{$Approvedcount}}</span> Approved</a></li>
                        <li class="nav-item" id="nav5"><a class="nav-link" data-bs-toggle="tab" href="#nav-Reject" role="tab" onclick="nav($id='nav5')"><span class="badge "style="background-color:#1d4ed8" >{{$Rejectcount}}</span> Reject</a></li>
                        <li class="nav-item" id="nav6"><a class="nav-link" data-bs-toggle="tab" href="#nav-Cancel" role="tab" onclick="nav($id='nav6')"><span class="badge bg-danger" >{{$Cancelcount}}</span> Cancel</a></li>
                    </ul>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="tab-pane fade show active" id="nav-Dummy" role="tabpanel" rel="0">
                                <div style="min-height: 70vh;" class="mt-2">
                                    <table id="proposal-indexTable" class="example ui striped table nowrap unstackable hover">
                                        <caption class="caption-top">
                                            <div>
                                                <div class="flex-end-g2">
                                                    <label class="entriespage-label">entries per page :</label>
                                                    <select class="entriespage-button" id="search-per-page-proposal-index" onchange="getPage(1, this.value, 'proposal-index')">
                                                        <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "proposal-index" ? 'selected' : '' }}>10</option>
                                                        <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "proposal-index" ? 'selected' : '' }}>25</option>
                                                        <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "proposal-index" ? 'selected' : '' }}>50</option>
                                                        <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "proposal-index" ? 'selected' : '' }}>100</option>
                                                    </select>
                                                    <input class="search-button search-data-proposal-index" id="proposal-index" style="text-align:left;" placeholder="Search" />
                                                </div>
                                        </caption>
                                        <thead>
                                            <tr>
                                                <th class="text-center" data-priority="1">No</th>
                                                <th class="text-center">Dummy</th>
                                                <th class="text-center" data-priority="1">ID</th>
                                                <th class="text-center" data-priority="1">Company</th>
                                                <th class="text-center">Issue Date</th>
                                                <th class="text-center">Expiration Date</th>
                                                <th class="text-center">Check In</th>
                                                <th class="text-center">Check Out</th>
                                                <th class="text-center">Discount (%)</th>
                                                <th class="text-center">Discount (Bath)</th>
                                                <th class="text-center">Approve  By</th>
                                                <th class="text-center">Operated By</th>
                                                <th class="text-center">Document status</th>
                                                <th class="text-center">Order</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($Proposal))
                                                @foreach ($Proposal as $key => $item)
                                                <tr>
                                                    <td style="text-align: center;">
                                                        {{$key +1}}
                                                    </td>
                                                    <td  style="text-align: center;">
                                                        @if ($item->DummyNo == $item->Quotation_ID )
                                                        -
                                                        @else
                                                        {{ $item->DummyNo }}
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->Quotation_ID }}</td>
                                                    <td>{{ @$item->company->Company_Name}}</td>
                                                    <td>{{ $item->issue_date }}</td>
                                                    <td>{{ $item->Expirationdate }}</td>
                                                    @if ($item->checkin)
                                                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($item->checkin)->format('d/m/Y') }}</td>
                                                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($item->checkout)->format('d/m/Y') }}</td>
                                                    @else
                                                    <td style="text-align: center;">-</td>
                                                    <td style="text-align: center;">-</td>
                                                    @endif

                                                    <td style="text-align: center;">
                                                        @if ($item->SpecialDiscount == 0)
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
                                                    <td >
                                                        @if ($item->Confirm_by == 'Auto' || $item->Confirm_by == '-')
                                                            {{ @$item->Confirm_by}}
                                                        @else
                                                            {{ @$item->userConfirm->name }}
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
                                                                <span class="badge rounded-pill bg-warning">Awaiting Approva</span>
                                                            @elseif ($item->status_document == 3)
                                                                <span class="badge rounded-pill "style="background-color: #FF6633	">Pending</span>
                                                            @elseif ($item->status_document == 4)
                                                                <span class="badge rounded-pill "style="background-color:#1d4ed8">Reject</span>
                                                            @elseif ($item->status_document == 6)
                                                                <span class="badge rounded-pill "style="background-color: #FF6633">Pending</span>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td style="text-align: center;">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                            <ul class="dropdown-menu border-0 shadow p-3">
                                                                @if (@Auth::user()->rolePermissionData(Auth::user()->id) == 0)
                                                                @elseif (@Auth::user()->rolePermissionData(Auth::user()->id) == 1)
                                                                    @if (@Auth::user()->roleMenuView('Proposal',Auth::user()->id) == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Quotation/view/'.$item->id) }}">View</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Quotation/Quotation/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Quotation/send/email/'.$item->id) }}">Send Email</a></li>
                                                                    @endif
                                                                    @if (@Auth::user()->roleMenuEdit('Proposal',Auth::user()->id) == 1)
                                                                        @if (in_array($item->status_document, [1, 6,3]))
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Quotation/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                        @endif
                                                                        @if($item->status_document == 1)
                                                                            @if ($item->SpecialDiscountBath == 0 && $item->SpecialDiscount == 0)
                                                                            <li><a class="dropdown-item py-2 rounded" onclick="Approved({{ $item->id }})">Approved</a></li>
                                                                            @endif
                                                                        @endif
                                                                        @if($item->status_document == 3)
                                                                            @if ($item->Confirm_by !== 0 )
                                                                                <li><a class="dropdown-item py-2 rounded" onclick="Approved({{ $item->id }})">Approved</a></li>
                                                                            @endif
                                                                        @endif
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Quotation/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" onclick="Cancel()"><input type="hidden" name="id" id="id" value="{{$item->id}}">Cancel</a></li>
                                                                    @endif
                                                                @elseif (@Auth::user()->rolePermissionData(Auth::user()->id) == 2)
                                                                    @if (@Auth::user()->roleMenuView('Proposal',Auth::user()->id) == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Quotation/view/'.$item->id) }}">View</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Quotation/Quotation/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Quotation/send/email/'.$item->id) }}">Send Email</a></li>
                                                                    @endif
                                                                    @if (Auth::user()->id == $item->Operated_by)
                                                                        @if (@Auth::user()->roleMenuEdit('Proposal',Auth::user()->id) == 1)
                                                                            @if (in_array($item->status_document, [1, 6,3]))
                                                                                <li><a class="dropdown-item py-2 rounded" href="{{ url('/Quotation/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                            @endif
                                                                            @if($item->status_document == 1)
                                                                                @if ($item->SpecialDiscountBath == 0 && $item->SpecialDiscount == 0)
                                                                                <li><a class="dropdown-item py-2 rounded" onclick="Approved({{ $item->id }})">Approved</a></li>
                                                                                @endif
                                                                            @endif
                                                                            @if($item->status_document == 3)
                                                                                @if ($item->Confirm_by !== 0 )
                                                                                    <li><a class="dropdown-item py-2 rounded" onclick="Approved({{ $item->id }})">Approved</a></li>
                                                                                @endif
                                                                            @endif
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Quotation/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
                                                                            <li><a class="dropdown-item py-2 rounded" onclick="Cancel()"><input type="hidden" name="id" id="id" value="{{$item->id}}">Cancel</a></li>
                                                                        @endif
                                                                    @endif
                                                                @elseif (@Auth::user()->rolePermissionData(Auth::user()->id) == 3)
                                                                    @if (@Auth::user()->roleMenuView('Proposal',Auth::user()->id) == 1)
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Quotation/view/'.$item->id) }}">View</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" target="_bank" href="{{ url('/Quotation/Quotation/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Quotation/send/email/'.$item->id) }}">Send Email</a></li>
                                                                    @endif
                                                                    @if (@Auth::user()->roleMenuEdit('Proposal',Auth::user()->id) == 1)
                                                                        @if (in_array($item->status_document, [1, 6,3]))
                                                                            <li><a class="dropdown-item py-2 rounded" href="{{ url('/Quotation/edit/quotation/'.$item->id) }}">Edit</a></li>
                                                                        @endif
                                                                        @if($item->status_document == 1)
                                                                            @if ($item->SpecialDiscountBath == 0 && $item->SpecialDiscount == 0)
                                                                            <li><a class="dropdown-item py-2 rounded" onclick="Approved({{ $item->id }})">Approved</a></li>
                                                                            @endif
                                                                        @endif
                                                                        @if($item->status_document == 3)
                                                                            @if ($item->Confirm_by !== 0 )
                                                                                <li><a class="dropdown-item py-2 rounded" onclick="Approved({{ $item->id }})">Approved</a></li>
                                                                            @endif
                                                                        @endif
                                                                        <li><a class="dropdown-item py-2 rounded" href="{{ url('/Quotation/view/quotation/LOG/'.$item->id) }}">LOG</a></li>
                                                                        <li><a class="dropdown-item py-2 rounded" onclick="Cancel()"><input type="hidden" name="id" id="id" value="{{$item->id}}">Cancel</a></li>
                                                                    @endif
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                        {{-- <input type="hidden" id="profile-Proposal-index" name="profile-company" value="{{$Proposal->Quotation_ID}}"> --}}
                                        <input type="hidden" id="get-total-proposal-index" value="{{ $Proposal->total() }}">
                                        <input type="hidden" id="currentPage-proposal-index" value="1">
                                        <caption class="caption-bottom">
                                            <div class="md-flex-bt-i-c">
                                                <p class="py2" id="proposal-index-showingEntries">{{ showingEntriesTable($Proposal, 'proposal-index') }}</p>
                                                    <div id="proposal-index-paginate">
                                                        {!! paginateTable($Proposal, 'proposal-index') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                                    </div>
                                            </div>
                                        </caption>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-Pending" role="tabpanel" rel="0">

                            </div>
                            <div class="tab-pane fade" id="nav-Awaiting" role="tabpanel" rel="0">

                            </div>
                            <div class="tab-pane fade" id="nav-Approved" role="tabpanel" rel="0">

                            </div>
                            <div class="tab-pane fade" id="nav-Reject" role="tabpanel" rel="0">

                            </div>
                            <div class="tab-pane fade" id="nav-Cancel" role="tabpanel" rel="0">

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
    <script type="text/javascript" src="{{ asset('assets/helper/searchTableProposal.js')}}"></script>
    <script>
        const table_name = ['proposal-indexTable'];
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
        $(document).on('keyup', '.search-data-proposal-index', function () {
            var id = $(this).attr('id');
            var search_value = $(this).val();
            var table_name = id+'Table';
            var filter_by = $('#filter-by').val();
            var type_status = $('#status').val();
            var total = parseInt($('#get-total-'+id).val());
            var getUrl = window.location.pathname;
            console.log(table_name);

                $('#'+table_name).DataTable().destroy();
                var table = $('#'+table_name).dataTable({
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                    url: '/Index-Proposal-search-table',
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
                        { targets: [0, 1, 2, 3,4, 5, 6, 7, 8, 9, 10, 11, 12, 13 ,14], className: 'dt-center td-content-center' },
                    ],
                    order: [0, 'asc'],
                    responsive: {
                        details: {
                            type: 'column',
                            target: 'tr'
                        }
                    },
                    columns: [
                        {
                            data: 'id',
                            "render": function (data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        { data: 'DummyNo' },
                        { data: 'Quotation_ID' },
                        { data: 'Company_Name' },
                        { data: 'IssueDate' },
                        { data: 'ExpirationDate' },
                        { data: 'CheckIn' },
                        { data: 'CheckOut' },
                        { data: 'DiscountP' },
                        { data: 'DiscountB' },
                        { data: 'Approve' },
                        { data: 'Operated' },
                        { data: 'DocumentStatus' },
                        { data: 'btn_action' }
                    ],
                });
            document.getElementById(id).focus();
        });
    </script>



@endsection
