@extends('layouts.masterLayout')
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
                    <div class="span3">Hotel Manual Charge</div>
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
            <div class="row clearfix mb-3">
                <div class="col-12 d-flex flex-column flex-md-row justify-content-between">
                    <!-- Form Container -->
                    <div class="form-container mb-3 mb-md-0">
                        <form action="{{ route('report-hotel-manual-charge-search') }}" method="POST" enctype="multipart/form-data" id="form-search" class="row g-3">
                            @csrf
                            <div class="col-md-12">
                                <h3>Search</h3>
                            </div>
                            <div class="wrap-btn-group mt-3">
                                <label >Filter by</label>
                                <div class="btn-group">
                                    <button id="filter-date" type="button" class="btn {{ isset($filter_by) && $filter_by == 'date' ? 'selected' : '' }} w-100">Date</button>
                                    <button id="filter-month" type="button" class="btn {{ isset($filter_by) && $filter_by == 'month' ? 'selected' : '' }} w-100">Month</button>
                                    <button id="filter-year" type="button" class="btn {{ isset($filter_by) && $filter_by == 'year' ? 'selected' : '' }} w-100">Year</button>
                                </div>
                            </div>
                            <div id="box-start-date" class="col-md-6" hidden>
                                <label for="startDate" class="form-label label-startDate">Start Date</label>
                                <input type="text" class="form-control" id="startDate" name="startDate" value="{{ isset($search_date) ? $search_date : date('Y-m-d Y-m-d') }}" required>
                            </div>
                            <div id="box-month" class="col-md-6">
                                <label for="month" class="form-label label-month">Month</label>
                                <input type="month" class="form-control" id="month" name="month" value="{{ isset($startDate) ? $startDate : date('Y-m') }}" required>
                            </div>
                            <div id="box-start-year" class="col-md-6" hidden>
                                <label for="startYear" class="form-label label-startYear">Year</label>
                                <select class="form-select" name="startDate" id="startYear">
                                    @for ($i = 2024; $i <= date('Y', strtotime('+1 year')); $i++)
                                        <option value="{{ $i }}" {{ isset($filter_by) && $filter_by == 'year' && $i == $search_date ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="col-md-12 d-flex flex-row gap-3 mt-4">
                                <div class="form-check">
                                    <input class="form-check-input status" type="checkbox" name="statusHide" id="statusHide" value="1" {{ isset($statusHide) && $statusHide == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="statusHide">Hide No Revenue</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input status" type="checkbox" name="statusNotComplete" id="statusNotComplete" value="1" {{ isset($statusNotComplete) && $statusNotComplete == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="statusNotComplete">Not Complete <span class="fa fa-info-circle" data-bs-toggle="tooltip" data-placement="top" title="Manual Charge, Fee, Hotel Bank Transfer"></span</label>
                                </div>
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
            <!-- Table -->
            <div id="content-to-export" class="table-2" style="overflow-x:auto;padding: 1em;">
                <div class="d-flex gap-3 m-3">
                    <div class="center">
                        <img src="image/Logo-tg2.png" alt="logo of Together Resort" width="80" class="mb-1" />
                    </div>
                    <div class="text-capitalize d-grid gap-0" style="height: max-content;">
                        <span class="f-semi">Together Resort Kaengkrachan</span>
                        <span>Hotel and water park revenue</span>
                        <span>Date On : {{ $search_date }}</span>
                    </div>
                </div>
                <table id="table-data" class="table-report-manual-charge">
                    <thead>
                        <tr class="table-row-bg1 text-capitalize">
                            <th>#</th>
                            <th>Date</th>
                            <th>Manual Charge</th>
                            <th>Fee</th>
                            <th>Hotel Bank Transfer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_manual = 0;
                            $total_fee = 0;
                            $total_sms = 0;
                            $number = 0;
                        @endphp

                        @if (isset($statusNotComplete) && $statusNotComplete == 1)
                            @foreach ($data_query as $key => $item)
                                @if ($item->manual_charge == 0 && $item->total_credit > 0 || $item->manual_charge > 0 && $item->total_credit == 0 || $statusHide == 1 && $item->manual_charge > 0 && $item->total_credit > 0)
                                    <tr>
                                        <td>{{ $number += 1 }}</td>
                                        <td style="text-align: left;">{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                        <td>{{ $item->manual_charge == 0 ? "-" : number_format($item->manual_charge, 2) }}</td>
                                        <td>{{ $item->fee == 0 || $item->manual_charge == 0 ? "-" : number_format($item->fee, 2) }}</td>
                                        <td>{{ $item->total_credit == 0 ? "-" : number_format($item->total_credit, 2) }}</td>
                                    </tr>

                                    @php
                                        $total_manual += $item->manual_charge;
                                        $total_fee += $item->fee == 0 || $item->manual_charge == 0 ? 0 : $item->fee;
                                        $total_sms += $item->total_credit;
                                    @endphp
                                @endif
                            @endforeach
                        @else
                            @foreach ($data_query as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td style="text-align: left;">{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                    <td>{{ $item->manual_charge == 0 ? "-" : number_format($item->manual_charge, 2) }}</td>
                                    <td>{{ $item->fee == 0 || $item->manual_charge == 0 ? "-" : number_format($item->fee, 2) }}</td>
                                    <td>{{ $item->total_credit == 0 ? "-" : number_format($item->total_credit, 2) }}</td>
                                </tr>

                                @php
                                    $total_manual += $item->manual_charge;
                                    $total_fee += $item->fee == 0 || $item->manual_charge == 0 ? 0 : $item->fee;
                                    $total_sms += $item->total_credit;
                                @endphp
                            @endforeach
                        @endif
                        <tr>
                            <td colspan="2" class="fw-bold text-end">Total</td>
                            <td class="fw-bold">{{ number_format($total_manual, 2) }}</td>
                            <td class="fw-bold">{{ number_format($total_fee, 2) }}</td>
                            <td class="fw-bold">{{ number_format($total_sms, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if (isset($_SERVER['HTTPS']) ? 'https' : 'http' == 'https')
        {{-- <script src="https://code.jquery.com/jquery-1.10.2.js"></script> --}}
        {{-- <script src="../assets/bundles/jquerycounterup.bundle.js"></script> --}}
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @else
        {{-- <script src="http://code.jquery.com/jquery-1.10.2.js"></script> --}}
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @endif

    <script type="text/javascript" src="{{ asset('assets/js/daterangepicker.min.js')}}" defer></script>
    <script type="text/javascript" src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css')}}" />

    <script type="text/javascript">
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
        });


        document.addEventListener("DOMContentLoaded", function() {
            const filterButtons = document.querySelectorAll(".btn-group button");
            const startDate = document.getElementById("startDate");
            const MonthStart = document.getElementById("month");
            const startYear = document.getElementById("startYear");

            const date = new Date();
            const year = date.getFullYear(); 
            const month = String(date.getMonth() + 1).padStart(2, '0'); // เพิ่ม 0 ถ้าเป็นเลขหลักเดียว
            const day = String(date.getDate()).padStart(2, '0');

            const formattedDate = `${year}-${month}-${day}`;
            const formattedMonth = `${year}-${month}`;
            const formattedYear = 2025;

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

        $(document).on('click', '.status', function () {
            var status = $(this).attr('id');

            if (status == "statusAll") {
                $('#statusHide').prop('checked', false);
                $('#statusNotComplete').prop('checked', false);
            } else {
                $('#statusAll').prop('checked', false);
            }
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
