@extends('layouts.masterLayout')
@section('content')

<style>
    /* Adjust container layout for responsiveness */


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
            flex-direction: column; /* Stack buttons vertically on small screens */
        }
        .btn-group .btn {
            width: 100%; /* Full width for each button */
        }
    }
</style>

    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Audit Hotel & Water Park Revenue by date</div>
                </div>
                <div class="col-auto">
                        <a href="#" type="button" class="btn btn-color-green text-white lift btn_modal">Print Report</a>
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
                        <form action="{{ route('report-audit-revenue-date-search') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                            @csrf
                            <div class="col-md-12">
                                <h3>Search</h3>
                            </div>
                            <div class="col-12 d-flex flex-row gap-3">
                                <label class="form-label">Filter by</label>
                                <div class="btn-group col-lg-6 col-md-6 col-sm-6 w-100">
                                    <button id="filter-date" type="button" class="btn {{ isset($filter_by) && $filter_by == 'date' ? 'selected' : '' }} w-100">Date</button>
                                    <button id="filter-month" type="button" class="btn {{ isset($filter_by) && $filter_by == 'month' ? 'selected' : '' }} w-100">Month</button>
                                    <button id="filter-year" type="button" class="btn {{ isset($filter_by) && $filter_by == 'year' ? 'selected' : '' }} w-100">Year</button>
                                    <button id="filter-custom" type="button" class="btn {{ isset($filter_by) && $filter_by == 'custom' ? 'selected' : '' }} w-100">Custom Range</button>
                                </div>
                            </div>
                            <div id="box-start-date" class="col-md-6">
                                <label for="startDate" class="form-label label-startDate">Start Date</label>
                                <input type="month" class="form-control" id="startDate" name="startDate" value="{{ isset($startDate) ? $startDate : date('Y-m') }}" required>
                            </div>
                            <div id="box-end-date" class="col-md-6">
                                <label for="endDate" class="form-label label-endDate">End Date</label>
                                <input type="month" class="form-control" id="endDate" name="endDate" value="{{ isset($endDate) ? $endDate : date('Y-m') }}" required>
                            </div>
                            <div id="box-start-year" class="col-md-6">
                                <label for="startDate" class="form-label label-startDate">Year</label>
                                <input type="month" class="form-control" id="startDate" name="startDate" value="{{ isset($startDate) ? $startDate : date('Y-m') }}" required>
                                s
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
                        <caption class="caption-top">
                            <div>
                                <div class="flex-end-g2">
                                    <label class="entriespage-label sm-500px-hidden">entries per page :</label>
                                    <select class="entriespage-button" id="search-per-page-verified" onchange="getPage(1, this.value, 'verified')"> <!-- เลขที่หน้า, perpage, ชื่อนำหน้าตาราง -->
                                        <option value="10" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 10 && @$_GET['table'] == "verified" ? 'selected' : '' }}>10</option>
                                        <option value="25" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 25 && @$_GET['table'] == "verified" ? 'selected' : '' }}>25</option>
                                        <option value="50" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 50 && @$_GET['table'] == "verified" ? 'selected' : '' }}>50</option>
                                        <option value="100" class="bg-[#f7fffc] text-[#2C7F7A]" {{ !empty(@$_GET['perPage']) && @$_GET['perPage'] == 100 && @$_GET['table'] == "verified" ? 'selected' : '' }}>100</option>
                                    </select>
                                    <input class="search-button search-data" id="verified" style="text-align:left;" placeholder="Search" />
                                </div>
                        </caption>
                        <div style="min-height: 70vh;">
                            <table id="verifiedTable" class="example ui striped table nowrap unstackable hover">
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
                            </table>
                        </div>
                        <caption class="caption-bottom">
                            <div class="md-flex-bt-i-c">
                                <p class="py2" id="verified-showingEntries">{{ showingEntriesTable($data_query, 'verified') }}</p>
                                <div class="font-bold "></div>
                                    <div id="verified-paginate">
                                        {!! paginateTable($data_query, 'verified') !!} <!-- ข้อมูล, ชื่อตาราง -->
                                    </div>
                            </div>
                        </caption>
                    </div> <!-- .card end -->
                </div>
            </div> <!-- .row end -->
        </div>
    </div>

    @if (isset($_SERVER['HTTPS']) ? 'https' : 'http' == 'https')
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @else
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="{{ asset('assets/bundles/sweetalert2.bundle.js') }}"></script>
    @endif

    <!-- table design css -->
    <link rel="stylesheet" href="{{ asset('assets/css/semantic.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.semanticui.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.semanticui.css') }}">

    <!-- table design js -->
    <script src="{{ asset('assets/js/semantic.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.semanticui.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.responsive.js') }}"></script>
    <script src="{{ asset('assets/js/responsive.semanticui.js') }}"></script>

    <!-- สำหรับค้นหาในส่วนของตาราง -->
    <script type="text/javascript" src="{{ asset('assets/helper/searchTableReportAudit.js')}}"></script>

<script>
    $(document).ready(function() {
        new DataTable('.example', {
            responsive: true,
            searching: false,
            paging: false,
            info: false,
            columnDefs: [{
                    className: 'dtr-control',
                    orderable: true,
                    target: null,
                },
            ],
            order: [0, 'asc'],
            responsive: {
                details: {
                    type: 'column',
                    target: 'tr'
                }
            }
        });

        var filterBy = $('#filter-by').val();
        var startDate = document.getElementById("startDate");
        var endDate = document.getElementById("endDate");

        if (filterBy == "date") {
            startDate.type = "date";
            $('#box-end-date').prop('hidden', true);
        } 

        if (filterBy == "month") {
            startDate.type = "month";
            endDate.type = "month";
            $('#box-end-date').prop('hidden', false);
        } 

        if (filterBy == "year") {
            startDate.type = "number";
            startDate.min = 1900;
            startDate.max = new Date().getFullYear();
            $('#box-end-date').prop('hidden', true);
        } 

        if (filterBy == "custom") {
            startDate.type = "date";
            endDate.type = "date";
            $('#box-end-date').prop('hidden', false);
        } 
    });

    document.addEventListener("DOMContentLoaded", function() {
        const filterButtons = document.querySelectorAll(".btn-group button");
        const startDate = document.getElementById("startDate");
        const endDate = document.getElementById("endDate");

        function resetFilters() {
            startDate.type = "date";
            endDate.type = "date";
            startDate.disabled = false;
            endDate.disabled = false;
        }

        filterButtons.forEach(button => {
            button.addEventListener("click", function() {
                // Remove 'selected' class from all buttons
                filterButtons.forEach(btn => btn.classList.remove("selected"));
                // Add 'selected' class to the clicked button
                this.classList.add("selected");

                // Adjust the input types based on selected filter
                if (this.id === "filter-date") {
                    resetFilters();
                    startDate.type = "date";
                    // endDate.type = "date";
                    $('#box-end-date').prop('hidden', true);
                    $('#filter-by').val("date");
                } else if (this.id === "filter-month") {
                    resetFilters();
                    startDate.type = "month";
                    endDate.type = "month";
                    $('#box-end-date').prop('hidden', false);
                    $('#filter-by').val("month");
                } else if (this.id === "filter-year") {
                    resetFilters();
                    startDate.type = "number";
                    endDate.type = "number";
                    startDate.min = 1900;
                    startDate.max = new Date().getFullYear();
                    endDate.min = 1900;
                    endDate.max = new Date().getFullYear();
                    $('#box-end-date').prop('hidden', true);
                    $('#filter-by').val("year");
                } else if (this.id === "filter-custom") {
                    resetFilters();
                    startDate.type = "date";
                    endDate.type = "date";
                    $('#box-end-date').prop('hidden', false);
                    $('#filter-by').val("custom");
                }
            });
        });
    });
</script>

        
@endsection
