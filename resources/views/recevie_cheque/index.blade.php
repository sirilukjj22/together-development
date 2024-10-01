@extends('layouts.masterLayout')

@section('content')
    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <small class="text-muted">Welcome to Recevie Cheque.</small>
                    <div class=""><span class="span1">Recevie Cheque</span></div>
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
                <div class="col-auto">
                    <button type="button" class="btn btn-color-green lift btn_modal" data-bs-toggle="modal" data-bs-target="#allSearch">
                        <i class="fa fa-plus"></i> Create Recevie Cheque
                    </button>
                    <div class="col-md-12 my-2">
                        <div class="modal fade" id="allSearch" tabindex="-1" aria-labelledby="PrenameModalCenterTitle"
                        style="display: none;" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color:#2C7F7A ">
                                        <h5 class="modal-title " style="color: #fff" id="PrenameModalCenterTitle"><i class="fa fa-plus"></i> Recevie Cheque</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-12">
                                            <div class="card-body">
                                                <form action="{{route('ReceiveCheque.save')}}" method="GET" enctype="multipart/form-data" class="row g-3 basic-form">
                                                    @csrf
                                                    <div class="col-sm-12 col-12">
                                                        <label for="Status">Refer Invoice</label>
                                                        <select name="Refer" id="Refer" class="select2" >
                                                            @foreach($invoice as $item)
                                                                <option value="{{ $item->Invoice_ID }}">
                                                                    {{ $item->Invoice_ID }} Refer Proposal : {{$item->Quotation_ID}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Status">Bank Cheque</label>
                                                        <select name="bank" id="bank" class="select2" >
                                                            @foreach($data_bank as $item)
                                                                <option value="{{ $item->id }}">
                                                                    {{ $item->name_th }} ({{$item->name_en}})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Status">Bank received </label>
                                                        <input type="text" class="form-control" id="received" name="received" disabled>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Status">Cheque Number</label>
                                                        <input type="text" class="form-control" id="chequeNumber" name="chequeNumber" maxlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Status">Amount</label>
                                                        <input type="text" class="form-control" id="Amount" name="Amount" required>

                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="receive">Receive Date</label>
                                                        <div class="input-group">
                                                            <input type="text" name="receive_date" id="receive_date" placeholder="DD/MM/YYYY" class="form-control" required>
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" style="border-radius:  0  5px 5px  0 ">
                                                                    <i class="fas fa-calendar-alt"></i> <!-- ไอคอนปฏิทิน -->
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Issue_Date">Issue Date</label>
                                                        <div class="input-group">
                                                            <input type="text" name="Issue_Date" id="Issue_Date" placeholder="DD/MM/YYYY" class="form-control" required>
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" style="border-radius:  0  5px 5px  0 ">
                                                                    <i class="fas fa-calendar-alt"></i> <!-- ไอคอนปฏิทิน -->
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
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
                    <div class="col-md-12 my-2">
                        <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="PrenameModalCenterTitle"
                        style="display: none;" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color:#2C7F7A ">
                                        <h5 class="modal-title " style="color: #fff" id="PrenameModalCenterTitle"><i class="fa fa-plus"></i> Recevie Cheque</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-12">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-12 col-12">
                                                        <label for="Status">Refer Invoice</label>
                                                        <input type="text" class="form-control" id="Referview" name="received" disabled>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Status">Bank Cheque</label>
                                                        <input type="text" class="form-control" id="BankChequeview" name="received" disabled>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Status">Bank received </label>
                                                        <input type="text" class="form-control" id="Bankreceivedview" name="received" disabled>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Status">Cheque Number</label>
                                                        <input type="text" class="form-control" id="chequeNumberview" name="chequeNumber" disabled>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Status">Amount</label>
                                                        <input type="text" class="form-control" id="Amountview" name="Amount" disabled>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="receive">Receive Date</label>
                                                        <div class="input-group">
                                                            <input type="text" name="receive_date" id="receive_dateview" placeholder="DD/MM/YYYY" class="form-control" disabled>
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" style="border-radius:  0  5px 5px  0 ">
                                                                    <i class="fas fa-calendar-alt"></i> <!-- ไอคอนปฏิทิน -->
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Issue_Date">Issue Date</label>
                                                        <div class="input-group">
                                                            <input type="text" name="Issue_Date" id="Issue_Dateview" placeholder="DD/MM/YYYY" class="form-control" disabled>
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" style="border-radius:  0  5px 5px  0 ">
                                                                    <i class="fas fa-calendar-alt"></i> <!-- ไอคอนปฏิทิน -->
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary lift" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div><!-- Form Validation -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 my-2">
                        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="PrenameModalCenterTitle"
                        style="display: none;" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color:#2C7F7A ">
                                        <h5 class="modal-title " style="color: #fff" id="PrenameModalCenterTitle"><i class="fa fa-plus"></i> Recevie Cheque</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="col-12">
                                            <div class="card-body">
                                                <form action="{{route('ReceiveCheque.update')}}" method="GET" enctype="multipart/form-data" class="row g-3 basic-form">
                                                    @csrf
                                                    <div class="col-sm-12 col-12">
                                                        <label for="Status">Refer Invoice</label>
                                                        <select name="Refer" id="Referedit" class="select2" >
                                                            @foreach($invoice as $item)
                                                                <option value="{{ $item->Invoice_ID }}">
                                                                    {{ $item->Invoice_ID }} Refer Proposal : {{$item->Quotation_ID}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Status">Bank Cheque</label>
                                                        <select name="bank" id="bankedit" class="select2" >
                                                            @foreach($data_bank as $item)
                                                                <option value="{{ $item->id }}">
                                                                    {{ $item->name_th }} ({{$item->name_en}})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Status">Bank received </label>
                                                        <input type="text" class="form-control" id="receivededit" name="received" disabled>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Status">Cheque Number</label>
                                                        <input type="text" class="form-control" id="chequeNumberedit" name="chequeNumber" maxlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Status">Amount</label>
                                                        <input type="text" class="form-control" id="Amountedit" name="Amount" required>
                                                        <input type="hidden" class="form-control" id="ids" name="ids" required>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="receive">Receive Date</label>
                                                        <div class="input-group">
                                                            <input type="text" name="receive_date" id="receive_dateedit" placeholder="DD/MM/YYYY" class="form-control" required>
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" style="border-radius:  0  5px 5px  0 ">
                                                                    <i class="fas fa-calendar-alt"></i> <!-- ไอคอนปฏิทิน -->
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                                        <label for="Issue_Date">Issue Date</label>
                                                        <div class="input-group">
                                                            <input type="text" name="Issue_Date" id="Issue_Dateedit" placeholder="DD/MM/YYYY" class="form-control" required>
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" style="border-radius:  0  5px 5px  0 ">
                                                                    <i class="fas fa-calendar-alt"></i> <!-- ไอคอนปฏิทิน -->
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
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
                </div>
            </div> <!-- Row end  -->
        </div> <!-- Row end  -->
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <ul class="nav nav-tabs px-3 border-bottom-0" role="tablist">
                        <li class="nav-item" id="nav1"><a class="nav-link active" data-bs-toggle="tab" href="#nav-proposal" role="tab" onclick="nav($id='nav1')"><span class="badge" style="background-color:#64748b;color:#64748b" >o</span> Recevie Cheque</a></li>{{--ประวัติการแก้ไข--}}
                    </ul>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade  show active" id="nav-proposal" role="tabpanel" rel="0">
                                    <div style="min-height: 70vh;" class="mt-2">
                                        <caption class="caption-top">
                                            <div class="flex-end-g2">
                                                <label class="entriespage-label">entries per page :</label>
                                                <select class="entriespage-button" id="search-per-page-cheque" onchange="getPage(1, this.value, 'cheque')"> <!-- ชือนำหน้าตาราง, ชื่อ Route -->
                                                    <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "cheque" ? 'selected' : '' }}>10</option>
                                                    <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "cheque" ? 'selected' : '' }}>25</option>
                                                    <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "cheque" ? 'selected' : '' }}>50</option>
                                                    <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "cheque" ? 'selected' : '' }}>100</option>
                                                </select>
                                                <input class="search-button search-data" id="cheque" style="text-align:left;" placeholder="Search" />
                                            </div>
                                        </caption>
                                        <table id="chequeTable" class="example1 ui striped table nowrap unstackable hover">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"data-priority="1">No</th>
                                                    <th>Refer Proforma Invoice</th>
                                                    <th data-priority="1">Bank Cheque</th>
                                                    <th class="text-center" data-priority="1">Cheque Number</th>
                                                    <th class="text-center">Amount</th>
                                                    <th class="text-center">Receive Date</th>
                                                    <th class="text-center">Issue Date</th>
                                                    <th class="text-center">Document status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($cheque))
                                                    @foreach ($cheque as $key => $item)
                                                    <tr>
                                                        <td style="text-align: center;">
                                                            {{$key +1}}
                                                        </td>
                                                        <td style="text-align: left;">
                                                            {{$item->refer_invoice}}
                                                        </td>
                                                        <td style="text-align: left;">
                                                            {{@$item->bank->name_th}} ({{@$item->bank->name_en}})
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{$item->cheque_number}}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{number_format($item->amount)}}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{$item->receive_date}}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            {{$item->issue_date}}
                                                        </td>
                                                        <td style="text-align: center;">
                                                            @if ($item->status == 0)
                                                                <span class="badge rounded-pill "style="background-color: #FF6633">Pending</span>
                                                            @else
                                                                <span class="badge rounded-pill bg-success">Approved</span>
                                                            @endif
                                                        </td>
                                                        @php
                                                            $rolePermission = @Auth::user()->rolePermissionData(Auth::user()->id);
                                                            $canViewProposal = @Auth::user()->roleMenuView('Proposal', Auth::user()->id);
                                                            $canEditProposal = @Auth::user()->roleMenuEdit('Proposal', Auth::user()->id);
                                                            $CreateBy = Auth::user()->id;
                                                        @endphp
                                                        {{-- Receive Cheque --}}
                                                        <td style="text-align: center;">
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                                <ul class="dropdown-menu border-0 shadow p-3">
                                                                    @if ($rolePermission > 0)
                                                                        @if ($canViewProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" onclick="view({{$item->id}})">View</a></li>
                                                                        @endif
                                                                        @if ($item->status == 0)
                                                                            @if ($rolePermission == 1 && $item->Operated_by == $CreateBy)
                                                                                @if ($canEditProposal == 1)
                                                                                    <li><a class="dropdown-item py-2 rounded" onclick="edit({{$item->id}})">Edit</a></li>
                                                                                    <li><a class="dropdown-item py-2 rounded" onclick="Approved({{$item->id}})">Approved</a></li>
                                                                                @endif
                                                                            @elseif ($rolePermission == 2)
                                                                                @if ($item->Operated_by == $CreateBy)
                                                                                    @if ($canEditProposal == 1)
                                                                                        <li><a class="dropdown-item py-2 rounded" onclick="edit({{$item->id}})">Edit</a></li>
                                                                                        <li><a class="dropdown-item py-2 rounded" onclick="Approved({{$item->id}})">Approved</a></li>
                                                                                    @endif
                                                                                @endif
                                                                            @elseif ($rolePermission == 3)
                                                                                @if ($canEditProposal == 1)
                                                                                    <li><a class="dropdown-item py-2 rounded" onclick="edit({{$item->id}})">Edit</a></li>
                                                                                    <li><a class="dropdown-item py-2 rounded" onclick="Approved({{$item->id}})">Approved</a></li>
                                                                                @endif
                                                                            @endif
                                                                        @else
                                                                        @endif
                                                                    @else
                                                                        @if ($canViewProposal == 1)
                                                                            <li><a class="dropdown-item py-2 rounded" onclick="view({{$item->id}})">View</a></li>
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
                                        <input type="hidden" id="get-total-proposal" value="{{ $cheque->total() }}">
                                        <input type="hidden" id="currentPage-proposal" value="1">
                                        <caption class="caption-bottom">
                                            <div class="md-flex-bt-i-c">
                                                <p class="py2" id="proposal-showingEntries">{{ showingEntriesTable($cheque, 'cheque') }}</p>
                                                    <div id="proposal-paginate">
                                                        {!! paginateTable($cheque, 'cheque') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                                    </div>
                                            </div>
                                        </caption>
                                    </div>
                                </div>
                                <!-- Modal -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="{{ asset('assets/js/daterangepicker.min.js')}}" defer></script>
    <script type="text/javascript" src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css')}}" />
    <script type="text/javascript" src="{{ asset('assets/helper/searchTableReceiveCheque.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Please select an option"
            });
        });
        $(function() {
            // ฟอร์แมตวันที่ให้อยู่ในรูปแบบ dd/mm/yyyy
            $('#receive_date').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                autoApply: true,
                locale: {
                    format: 'DD/MM/YYYY' // ฟอร์แมตเป็น dd/mm/yyyy
                }
            });
            $('#receive_date').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY'));

            });
        });
        $(function() {
            // ฟอร์แมตวันที่ให้อยู่ในรูปแบบ dd/mm/yyyy
            $('#Issue_Date').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                autoApply: true,
                locale: {
                    format: 'DD/MM/YYYY' // ฟอร์แมตเป็น dd/mm/yyyy
                }
            });
            $('#Issue_Date').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY'));

            });
        });
        function view(id) {
            var id = id;
            jQuery.ajax({
                type: "GET",
                url: `/Document/ReceiveCheque/view/${id}`,  // ใช้ template literal สร้าง URL
                datatype: "JSON",
                async: false,
                success: function(response) {
                    var invoice = response.invoice;
                    var proposal = response.proposal;
                    var bank_cheque = response.bank_cheque;
                    var bank_received = response.bank_received;
                    var cheque_number = response.cheque_number;
                    var amount = response.amount;
                    var receive_date = response.receive_date;
                    var issue_date = response.issue_date;

                    var refer = 'อ้างอิงจาก Proforma Invoice ' + invoice + ' Proposol ' + proposal;
                    $('#Referview').val(refer);
                    $('#BankChequeview').val(bank_cheque);
                    $('#Bankreceivedview').val(bank_received);
                    $('#chequeNumberview').val(cheque_number);
                    $('#Amountview').val(amount);
                    $('#receive_dateview').val(receive_date);
                    $('#Issue_Dateview').val(issue_date);
                    $('#viewModal').modal('show');
                },
                error: function(xhr, status, error) {

                    $('#viewModal').modal('show');
                    console.error("AJAX request failed: ", status, error);
                }
            });
        }
        function edit(id) {
            var id = id;
            jQuery.ajax({
                type: "GET",
                url: `/Document/ReceiveCheque/edit/${id}`,  // ใช้ template literal สร้าง URL
                datatype: "JSON",
                async: false,
                success: function(response) {
                    var invoice = response.invoice;
                    var proposal = response.proposal;
                    var bank_cheque = response.bank_cheque;
                    var bank_received = response.bank_received;
                    var cheque_number = response.cheque_number;
                    var amount = response.amount;
                    var receive_date = response.receive_date;
                    var issue_date = response.issue_date;

                    console.log(bank_cheque);

                    $('#ids').val(id);
                    $('#Referedit').val(invoice).trigger('change');
                    $('#bankedit').val(bank_cheque).trigger('change');
                    // $('#Bankreceivedview').val(bank_received);
                    $('#chequeNumberedit').val(cheque_number);
                    $('#Amountedit').val(amount);
                    $('#receive_dateedit').val(receive_date);
                    $('#Issue_Dateedit').val(issue_date);
                    $('#editModal').modal('show');
                },
                error: function(xhr, status, error) {

                    $('#editModal').modal('show');
                    console.error("AJAX request failed: ", status, error);
                }
            });
        }
        function Approved(id) {
            jQuery.ajax({
                type: "GET",
                url: "/Document/ReceiveCheque/Approved/" + id,
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
        const table_name = ['chequeTable'];
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
                    url: '/Proposal-request-search-table',
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
                                { targets: [0,2,3,4,5,6], className: 'dt-center td-content-center' },
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
                        { data: 'Company_Name' },
                        { data: 'QuotationType' },
                        { data: 'Operated_by' },
                        { data: 'Count' },
                        { data: 'status' },
                        { data: 'btn_action' },
                    ],
                });
            document.getElementById(id).focus();
        });
    </script>
    @include('script.script')


@endsection
