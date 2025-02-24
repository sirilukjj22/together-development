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

    .wrap-status-paid {
        background-color: #44a768;
        color: white;
        vertical-align: middle;
        padding: 3px 8px;
        border-radius: 7px;
        font-size: 0.8em;
    }

    .wrap-status-pending {
        background-color: rgb(235, 181, 32);
        color: white;
        vertical-align: middle;
        padding: 3px 8px;
        border-radius: 7px;
        font-size: 0.8em;
    }
</style>

    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Agoda Paid Revenue Report</div>
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
                        <form action="{{ route('report-agoda-paid-search') }}" method="POST" enctype="multipart/form-data" id="form-search" class="row g-3">
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
                        <span>Agoda Paid Revenue</span>
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
                        <table id="smsAgodaTable" class="table-together table-style" >
                            <thead>
                                <tr class="text-capitalize">
                                    <th data-priority="1">#</th>
                                    <th data-priority="1">วันที่ทำรายการ</th>
                                    <th data-priority="3">Booking number</th>
                                    <th data-priority="4">Check in date</th>
                                    <th data-priority="5">Check out date</th>
                                    <th data-priority="1">amount</th>
                                    <th data-priority="2">status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_query as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                        <td>{{ $item->batch }}</td>
                                        <td>{{ Carbon\Carbon::parse($item->agoda_check_in)->format('d/m/Y') }}</td>
                                        <td>{{ Carbon\Carbon::parse($item->agoda_check_out)->format('d/m/Y') }}</td>
                                        <td class="text-end target-class">{{ $item->agoda_outstanding }}</td>
                                        <td><span class="wrap-status-paid">paid</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="background-color: #d7ebe1; font-weight: bold">
                                <tr>
                                    <td></td>
                                    <td class="text-center" style="padding: 10px">Total</td>
                                    <td colspan="3"></td>
                                    <td class="text-end format-number-table" id="tfoot-total-outstanding">{{ $total_agoda_amount }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="filter-by-old" value="{{ isset($filter_by) ? $filter_by : 'month' }}">
    <input type="hidden" id="date-old" value="{{ isset($search_date) ? $search_date : date('Y-m') }}">
    <input type="hidden" id="status-revenue" value="5">

    <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>

    <script type="text/javascript" src="{{ asset('assets/js/daterangepicker.min.js')}}" defer></script>
    <script type="text/javascript" src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css')}}" />

    <!-- สำหรับค้นหาในส่วนของตาราง -->
    <script src="{{ asset('assets/js/table-together.js') }}"></script>

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
