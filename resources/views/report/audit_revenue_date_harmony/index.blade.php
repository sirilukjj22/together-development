@extends('layouts.masterLayoutHarmony')
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
                    <div class="span3">Audit Hotel & Water Park Revenue</div>
                </div>
                <div class="col-auto">
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
                        <form action="{{ route('harmony-report-audit-revenue-date-search') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                            @csrf
                            <div class="col-md-12">
                                <h3>Search</h3>
                            </div>
                            <div class="wrap-btn-group" >
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
            
                            <!-- Radio buttons for status filter -->
                            <div class="col-md-12 d-flex flex-row gap-3 mt-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="statusAll" value="all" {{ isset($status) && $status == 'all' ? 'checked' : 'checked' }}>
                                    <label class="form-check-label" for="statusAll">All</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="statusVerified" value="1" {{ isset($status) && $status == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="statusVerified">Verified</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="statusUnverified" value="0" {{ isset($status) && $status == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="statusUnverified">Unverified</label>
                                </div>
                            </div>

                            <input type="hidden" id="filter-by" name="filter_by" value="{{ isset($filter_by) ? $filter_by : 'month' }}">
            
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-color-green">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> <!-- .row end -->        
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <div class="card p-4 mb-4">
                        <div style="min-height: 70vh;">
                            <table id="verifiedTable" class="table-together table-style">
                                <thead>
                                    <tr>
                                        <th data-priority="1">#</th>
                                        <th data-priority="2">Date</th>
                                        <th data-priority="3">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_query as $key => $value)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ Carbon\Carbon::parse($value->date)->format('d/m/Y') }}</td>
                                            <td>
                                                @if ($value->status == 0)
                                                    <span class="badge bg-danger">Unverified</span>
                                                @else 
                                                    <span class="badge bg-success">Verified</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="font-bold">Total : {{ number_format($total_all) }} &nbsp; Verified : {{ number_format($verified) }} &nbsp; Unverified : {{ number_format($unverified) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div> <!-- .card end -->
                </div>
            </div> <!-- .row end -->
        </div>
    </div>

    <script type="text/javascript" src="{{ asset('assets/js/daterangepicker.min.js')}}" defer></script>
    <script type="text/javascript" src="{{ asset('assets/js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css')}}" />

    <!-- สำหรับค้นหาในส่วนของตาราง -->
    <script src="{{ asset('assets/js/table-together.js') }}"></script>

<script>
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
