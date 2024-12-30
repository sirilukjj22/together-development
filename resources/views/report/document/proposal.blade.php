@extends('layouts.masterLayout')
@php
    $excludeDatatable = false;
@endphp
@section('content')

<style>
    /* Form container styling */
    .form-container {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        width: 100%;
        max-width: 100%; /* Form container takes up more space on larger screens */
    }

    .show-number {
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        margin-top: 2px;
        height: 20%;
    }

    /* Heading styling */
    h3 {
        color: #495057;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    /* Styling for buttons */
    .btn {
        border-radius: 8px;
        border-color: #ced4da;
        box-shadow: none;
    }

    /* Styling for filter buttons */
    .btn-group button {
        border-radius: 20px;
        padding: 8px 20px;
        transition: background-color 0.3s, color 0.3s;
    }

    /* Selected button style */
    .btn-group .btn-secondary,
    .btn-group .selected {
        background-color: rgba(45, 127, 123, 1);
        color: white;
    }

    /* Hover effect for buttons */
    .btn-group button:hover {
        background-color: rgba(45, 127, 123, 1);
        color: white;
    }

    /* Form input control styling */
    .form-control {
        border-radius: 8px;
        border-color: #ced4da;
        box-shadow: none;
    }

    /* Adjust container layout for smaller screens */
    @media (max-width: 767px) {
        .form-container {
            max-width: 100%; /* Full width on small screens */
        }
    }

    /* Ensure filter buttons take full width on small screens */
    .btn-group .btn {
        width: 100%; /* Full width for buttons on small screens */
    }

    /* Revert to original button width on medium and larger screens */
    @media (min-width: 768px) {
        .btn-group .btn {
            width: auto; /* Revert button to auto width for medium screens */
        }
    }

    /* Adjust filter button layout for small screens */
    @media (max-width: 767px) {
        .btn-group {
            display: flex;
        }
        .btn-group .btn {
            width: 100%; /* Full width for each button */
        }
    }

    .wrap-btn-group {
        display: flex;
        gap:1em;
        width: 100%;
        margin:0 1em;
    }

    .wrap-btn-group label {
        min-width: max-content;
    }

     .btn-group {
        width: 50%;
    }

     @media (max-width: 767px) {
        .wrap-btn-group {
        display: flex;
        flex-direction: column;
        width: 100%;
        justify-content: center;
        gap:0;
    }

    .wrap-btn-group  .btn-group {
        padding-left: 0;
        width: 100%;
    }

     }

     @media (max-width: 400px) {
        .wrap-btn-group  .btn-group > button {
            padding: 8px 8px;
        }
    }
</style>
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Document Proposal Report</div>
                </div>
                <div class="col-auto">
                    <button type="button" class="bt-tg-normal export-pdf" id="download-pdf"> Print <img src="/image/front/pdf.png" width="30px" alt=""></button>
                    <button type="button" class="bt-tg-normal export-excel" id="export-excel"> Export <img src="/image/front/xls.png" width="30px" alt=""></button>
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

                </div>
            </div> <!-- Row end  -->
        </div> <!-- Row end  -->
        <div id="content-index" class="body d-flex py-lg-4 py-3">
            <div class="container-xl">
                <div class="row clearfix mb-3">
                    <div class="col-12 d-flex flex-column flex-md-row justify-content-between">
                        <!-- Form Container -->
                        <div class="form-container mb-3 mb-md-0">
                            <form action="{{ route('report-proposal-search') }}" method="POST" enctype="multipart/form-data" id="form-search" class="row g-3">
                                @csrf
                                <div class="col-md-12">
                                    <h3>Search</h3>
                                </div>
                                <div class="wrap-btn-group mt-3">
                                    <label>Filter by</label>
                                    <div class="btn-group">
                                        <button id="filter-date" type="button"
                                            class="btn {{ isset($filter_by) && $filter_by == 'date' ? 'selected' : '' }} w-100">Date</button>
                                        <button id="filter-month" type="button"
                                            class="btn {{ isset($filter_by) && $filter_by == 'month' ? 'selected' : '' }} w-100">Month</button>
                                        <button id="filter-year" type="button"
                                            class="btn {{ isset($filter_by) && $filter_by == 'year' ? 'selected' : '' }} w-100">Year</button>
                                    </div>
                                </div>
                                <div id="box-start-date" class="col-md-6" hidden>
                                    <label for="startDate" class="form-label label-startDate">Check In / Out Date</label>
                                    <input type="text" class="form-control" id="startDate" name="startDate"
                                        value="{{ isset($search_date) ? $search_date : date('d/m/Y d/m/Y') }}" required>
                                </div>
                                <div id="box-month" class="col-md-6">
                                    <label for="month" class="form-label label-month">Check In / Out Month</label>
                                    <input type="month" class="form-control" id="month" name="month"
                                        value="{{ isset($startDate) ? $startDate : date('m/Y') }}" required>
                                </div>
                                <div id="box-start-year" class="col-md-6" hidden>
                                    <label for="startYear" class="form-label label-startYear">Check In / Out Year</label>
                                    <select class="form-select" name="startDate" id="startYear">
                                        @for ($i = 2024; $i <= date('Y', strtotime('+1 year')); $i++)
                                            <option value="{{ $i }}" {{ isset($filter_by) && $filter_by == 'year' && $i == $search_date ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <style>
                                .d-al-center div {
                                    display: flex;
                                    align-items: center;

                                }
                                .d-al-center label {
                                    margin: 0px;
                                }
                                </style>
                                <div class="col-md-6" ></div>
                                <div class="col-md-6" >
                                    <label for="statusinput" class="form-label">Select Status</label>
                                    <select name="statusinput" id="statusinput" class="select2">
                                        <option></option>
                                        <option value="Pending" {{ isset($status) && $status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="2" {{ isset($status) && $status == '2' ? 'selected' : '' }}>Awaiting Approval</option>
                                        <option value="11" {{ isset($status) && $status == '11' ? 'selected' : '' }}>Approved</option>
                                        <option value="4" {{ isset($status) && $status == '4' ? 'selected' : '' }}>Reject</option>
                                        <option value="9" {{ isset($status) && $status == '9' ? 'selected' : '' }}>Complete</option>
                                        <option value="55" {{ isset($status) && $status == '55' ? 'selected' : '' }}>Cancel</option>
                                    </select>
                                </div>

                                <input type="hidden" id="filter-by" name="filter_by" value="{{ isset($filter_by) ? $filter_by : 'month' }}">
                                <input type="hidden" value="search" id="method-name" name="method_name">

                                <div class="col-md-12 text-end">
                                    <button type="button" class="btn btn-color-green btn-search">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> <!-- .row end -->
                <div id="content-to-export" class="table-2" style="overflow-x:auto;padding: 1em;">
                    <div class="d-flex gap-3 m-3">
                        <div class="center">
                            <img src="image/Logo-tg2.png" alt="logo of Together Resort" width="80" class="mb-1" />
                        </div>
                        <div class="text-capitalize d-grid gap-0" style="height: max-content;">
                            <span class="f-semi">Together Resort Kaengkrachan</span>
                            <span>Document Proposal</span>
                            <span>Date On : {{ $search_date }}</span>
                        </div>
                    </div>
                    <div class="p-4 mb-4">
                        <style>
                            .example td:nth-child(4) {
                                text-align: left !important;
                                vertical-align: center !important;
                            }
                            </style>
                        <div style="min-height: 70vh;">
                            <table id="ProposalTable" class="table-together table-style" >
                                <thead>
                                    <tr>
                                        <th style="text-align: center;" data-priority="1">#</th>
                                        <th style="text-align: center;" data-priority="1">Proposal ID</th>
                                        <th style="text-align: center;" data-priority="1">Company / Individual</th>
                                        <th style="text-align: center;">Check IN</th>
                                        <th style="text-align: center;">Check OUT</th>
                                        <th style="text-align: center;">Issue Date</th>
                                        <th style="text-align: center;">Creatd By</th>
                                        <th style="text-align: center;">Amount</th>
                                        <th style="text-align: center;">Status</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_query as $key => $item)
                                    <tr>
                                        <td class="td-content-center">{{ $key + 1 }}</td>
                                        <td class="td-content-center">{{ $item->Quotation_ID}}</td>
                                        @if ($item->type_Proposal == 'Company')
                                            <td class="td-content-center target-class text-start">{{ @$item->company->Company_Name}}</td>
                                        @else
                                            <td class="td-content-center target-class text-start">{{ @$item->guest->First_name.' '.@$item->guest->Last_name}}</td>
                                        @endif
                                        <td class="td-content-center">
                                            {{ $item->checkin}}
                                        </td>
                                        <td class="td-content-center">
                                            {{ $item->checkout}}
                                        </td>
                                        <td class="td-content-center">{{ $item->issue_date }}</td>
                                        <td class="td-content-center">{{ @$item->userOperated->name }}</td>
                                        <td class="td-content-center  target-class text-end">{{ number_format($item->Nettotal, 2) }}</td>
                                        <td class="td-content-center">
                                            @if($item->status_guest == 1 && $item->status_document !== 0 && $item->status_document !== 9)
                                                <span class="badge rounded-pill bg-success" >Approved</span>
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
                                                @elseif ($item->status_document == 9)
                                                    <span class="badge rounded-pill "style="background-color: #2C7F7A">Complete</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="td-content-center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-color-green text-white rounded-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">List &nbsp;</button>
                                                <ul class="dropdown-menu border-0 shadow p-3">
                                                    <li><a class="dropdown-item py-2 rounded" target="_blank" href="{{ url('/Proposal/view/'.$item->id) }}">View</a></li>
                                                    <li><a class="dropdown-item py-2 rounded" target="_blank" href="{{ url('/Proposal/Quotation/cover/document/PDF/'.$item->id) }}">Export</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
    <script src="{{ asset('assets/js/table-together.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Please select an option"
            });
        });
        $(document).ready(function() {
            var filterBy = $('#filter-by').val();
            var startDate = document.getElementById("startDate");
            var MonthStart = document.getElementById("month");
            startYear.disabled = true;

            if (filterBy == "date") {
                startDate.type = "text";
                startDate.disabled = false;
                MonthStart.disabled = true;
                $('#box-month').prop('hidden', true);
                $('#box-start-date').prop('hidden', false);
                $('#filter-by').val("date");

            }

            if (filterBy == "month") {
                MonthStart.type = "month";
                MonthStart.disabled = false;
                startDate.disabled = true;
                $('#box-start-date').prop('hidden', true);
                $('#box-month').prop('hidden', false);
                $('#filter-by').val("month");
            }

            if (filterBy == "year") {
                startYear.disabled = false;
                startDate.disabled = true;
                MonthStart.disabled = true;
                $('#box-start-date').prop('hidden', true);
                $('#box-month').prop('hidden', true);
                $('#box-start-year').prop('hidden', false);
                $('#filter-by').val("year");
            }

            $('input[name="startDate"]').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY'  // กำหนดรูปแบบวันที่เป็น 'ปี-เดือน-วัน'
                }
            });
            $('#startDate').on('apply.daterangepicker', function(ev, picker) {
                document.getElementById('statusinput').disabled = true;
            });
        });


        document.addEventListener("DOMContentLoaded", function() {
            const filterButtons = document.querySelectorAll(".btn-group button");
            const startDate = document.getElementById("startDate");
            const MonthStart = document.getElementById("month");
            const startYear = document.getElementById("startYear");
            var statusinput = document.getElementById("statusinput");

            const date = new Date();
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0'); // เพิ่ม 0 ถ้าเป็นเลขหลักเดียว
            const day = String(date.getDate()).padStart(2, '0');

            const formattedDate = `${year}-${month}-${day}`;
            const formattedMonth = `${year}-${month}`;
            const formattedYear = 2025;


            // เมื่อเลือกค่าใน month (กรณีเดือน)
            MonthStart.addEventListener('input', function () {
                statusinput.disabled = true;
            });

            // เมื่อเลือกค่าใน startYear (กรณีปี)
            startYear.addEventListener('change', function () {
                statusinput.disabled = true;
            });
            filterButtons.forEach(button => {
                button.addEventListener("click", function() {
                    // Remove 'selected' class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove("selected"));
                    // Add 'selected' class to the clicked button
                    this.classList.add("selected");

                    $('#box-start-year').prop('hidden', true);
                    $('#box-start-date').prop('hidden', false);
                    $('#box-month').prop('hidden', false);

                    startDate.disabled = true;
                    MonthStart.disabled = true;
                    startYear.disabled = true;

                    // Adjust the input types based on selected filter
                    if (this.id === "filter-date") {
                        startDate.type = "text";
                        startDate.disabled = false;
                        MonthStart.disabled = true;
                        $('#box-month').prop('hidden', true);
                        $('#filter-by').val("date");

                    } else if (this.id === "filter-month") {
                        MonthStart.type = "month";
                        MonthStart.disabled = false;
                        startDate.disabled = true;
                        $('#box-start-date').prop('hidden', true);
                        $('#box-month').prop('hidden', false);
                        $('#filter-by').val("month");

                    } else if (this.id === "filter-year") {
                        startYear.disabled = false;
                        startDate.disabled = true;
                        MonthStart.disabled = true;
                        $('#box-start-date').prop('hidden', true);
                        $('#box-month').prop('hidden', true);
                        $('#box-start-year').prop('hidden', false);
                        $('#filter-by').val("year");

                    }
                });
            });
        });
        $(document).ready(function() {
            const startDate = document.getElementById("startDate");
            const MonthStart = document.getElementById("month");
            const startYear = document.getElementById("startYear");
            $('#statusinput').on('change', function() {
                // เช็คว่า radio ถูกเลือกหรือไม่

                    // ทำสิ่งที่ต้องการเมื่อ radio ถูกเลือก
                    startDate.disabled = true;
                    MonthStart.disabled = true;
                    startYear.disabled = true;

            });

        });
        // Export
        $(document).on('click', '.export-pdf', function () {
            $('#method-name').val("pdf");
            document.getElementById("form-search").setAttribute("target", "_blank");
            $('#form-search').submit();
        });

        $(document).on('click', '.export-excel', function () {
            $('#method-name').val("excel");
            document.getElementById("form-search").setAttribute("target", "_blank");
            $('#form-search').submit();
        });

        $(document).on('click', '.btn-search', function () {
            document.getElementById("form-search").removeAttribute('target');
            $('#method-name').val("search");
            $('#form-search').submit();
        });
    </script>


@endsection
