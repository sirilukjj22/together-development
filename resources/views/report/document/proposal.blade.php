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
        max-width: 100%;
        /* Form container takes up more space on larger screens */
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
            max-width: 100%;
            /* Full width on small screens */
        }
    }

    /* Ensure filter buttons take full width on small screens */
    .btn-group .btn {
        width: 100%;
        /* Full width for buttons on small screens */
    }

    /* Revert to original button width on medium and larger screens */
    @media (min-width: 768px) {
        .btn-group .btn {
            width: auto;
            /* Revert button to auto width for medium screens */
        }
    }

    /* Adjust filter button layout for small screens */
    @media (max-width: 767px) {
        .btn-group {
            display: flex;
        }

        .btn-group .btn {
            width: 100%;
            /* Full width for each button */
        }
    }

    .wrap-btn-group {
        display: flex;
        gap: 1em;
        width: 100%;
        margin: 0 1em;
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
            gap: 0;
        }

        .wrap-btn-group .btn-group {
            padding-left: 0;
            width: 100%;
        }

    }

    @media (max-width: 400px) {
        .wrap-btn-group .btn-group>button {
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
                            <form action="{{ route('report-hotel-water-park-revenue-search') }}" method="POST" enctype="multipart/form-data" id="form-search" class="row g-3">
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
                                    <label for="startDate" class="form-label label-startDate">Start Date</label>
                                    <input type="text" class="form-control" id="startDate" name="startDate"
                                        value="{{ isset($search_date) ? $search_date : date('Y-m-d Y-m-d') }}" required>
                                </div>
                                <div id="box-month" class="col-md-6">
                                    <label for="month" class="form-label label-month">Month</label>
                                    <input type="month" class="form-control" id="month" name="month"
                                        value="{{ isset($startDate) ? $startDate : date('Y-m') }}" required>
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
                                        <input class="form-check-input" type="radio" name="status" id="statusSummary" value="summary" {{ isset($status) && $status == 'summary' ? 'checked' : 'checked' }}>
                                        <label class="form-check-label" for="statusSummary">Summary</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="statusDetail" value="detail" {{ isset($status) && $status == 'detail' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="statusDetail">Detail</label>
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

        // const table_name = ['chequeTable'];
        // $(document).ready(function() {
        //     for (let index = 0; index < table_name.length; index++) {
        //         console.log();

        //         new DataTable('#'+table_name[index], {
        //             searching: false,
        //             paging: false,
        //             info: false,
        //             columnDefs: [{
        //                 className: 'dtr-control',
        //                 orderable: true,
        //                 target: null,
        //             }],
        //             order: [0, 'asc'],
        //             responsive: {
        //                 details: {
        //                     type: 'column',
        //                     target: 'tr'
        //                 }
        //             }
        //         });
        //     }
        // });
        // function nav(id) {
        //     for (let index = 0; index < table_name.length; index++) {
        //         $('#'+table_name[index]).DataTable().destroy();
        //         new DataTable('#'+table_name[index], {
        //             searching: false,
        //             paging: false,
        //             info: false,
        //             columnDefs: [{
        //                 className: 'dtr-control',
        //                 orderable: true,
        //                 target: null,
        //             }],
        //             order: [0, 'asc'],
        //             responsive: {
        //                 details: {
        //                     type: 'column',
        //                     target: 'tr'
        //                 }
        //             }
        //         });
        //     }
        // }

        // $(document).on('keyup', '.search-data', function () {
        //     var id = $(this).attr('id');
        //     var search_value = $(this).val();
        //     var table_name = id+'Table';
        //     var filter_by = $('#filter-by').val();
        //     var type_status = $('#status').val();
        //     var total = parseInt($('#get-total-'+id).val());
        //     var getUrl = window.location.pathname;
        //     console.log(search_value);

        //         $('#'+table_name).DataTable().destroy();
        //         var table = $('#'+table_name).dataTable({
        //             searching: false,
        //             paging: false,
        //             info: false,
        //             ajax: {
        //             url: '/cheque-search-table',
        //             type: 'POST',
        //             dataType: "json",
        //             cache: false,
        //             data: {
        //                 search_value: search_value,
        //                 table_name: table_name,
        //                 filter_by: filter_by,
        //                 status: type_status,
        //             },
        //             headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        //         },
        //         "initComplete": function (settings,json){

        //             if ($('#'+id+'Table .dataTable_empty').length == 0) {
        //                 var count = $('#'+id+'Table tr').length - 1;
        //             }else{
        //                 var count = 0;
        //             }
        //             if (search_value == '') {
        //                 count_total = total;
        //             }else{
        //                 count_total = count;
        //             }
        //             $('#'+id+'-paginate').children().remove().end();
        //             $('#'+id+'-showingEntries').text(showingEntriesSearch(1,count_total, id));
        //             $('#'+id+'-paginate').append(paginateSearch(count_total, id, getUrl));
        //         },
        //             columnDefs: [
        //                         { targets: [0,3,5,6,7,8,9], className: 'dt-center td-content-center' },
        //             ],
        //             order: [0, 'asc'],
        //             responsive: {
        //                 details: {
        //                     type: 'column',
        //                     target: 'tr'
        //                 }
        //             },
        //             columns: [
        //                 { data: 'id', "render": function (data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; } },
        //                 { data: 'proposal' },
        //                 { data: 'Bank' },
        //                 { data: 'Cheque_Number' },
        //                 { data: 'Amount' },
        //                 { data: 'Receive_Date' },
        //                 { data: 'Issue_Date' },
        //                 { data: 'Operated' },
        //                 { data: 'status' },
        //                 { data: 'btn_action' },
        //             ],
        //         });
        //     document.getElementById(id).focus();
        // });
        $(document).ready(function() {
            var filterBy = $('#filter-by').val();
            var startDate = document.getElementById("startDate");
            var MonthStart = document.getElementById("month");
            var statusSummary = document.getElementById("statusSummary");
            var statusDetail = document.getElementById("statusDetail");
            startYear.disabled = true;

            $('.to-day').prop('hidden', false);
            $('.m-t-d').prop('hidden', false);
            $('.y-t-d').prop('hidden', false);

            if (filterBy == "date") {
                startDate.type = "text";
                startDate.disabled = false;
                MonthStart.disabled = true;
                statusDetail.disabled = false;
                $('#box-month').prop('hidden', true);
                $('#box-start-date').prop('hidden', false);
                $('#filter-by').val("date");

                var dateRang = document.getElementById("startDate").value;
                var dateSplit = dateRang.split(" - "); // แยกค่าด้วย "-"

                if (dateSplit[0] != dateSplit[1]) {
                    $('.m-t-d').prop('hidden', true);
                    $('.y-t-d').prop('hidden', true);
                }
            }

            if (filterBy == "month") {
                MonthStart.type = "month";
                MonthStart.disabled = false;
                startDate.disabled = true;
                statusDetail.disabled = true;
                $('#box-start-date').prop('hidden', true);
                $('#box-month').prop('hidden', false);
                $('#filter-by').val("month");

                $('.to-day').prop('hidden', true);
            }

            if (filterBy == "year") {
                startYear.disabled = false;
                startDate.disabled = true;
                MonthStart.disabled = true;
                statusDetail.disabled = false;
                $('#box-start-date').prop('hidden', true);
                $('#box-month').prop('hidden', true);
                $('#box-start-year').prop('hidden', false);
                $('#filter-by').val("year");

                $('.to-day').prop('hidden', true);
                $('.m-t-d').prop('hidden', true);
            }

            $('input[name="startDate"]').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY' // กำหนดรูปแบบวันที่เป็น 'ปี-เดือน-วัน'
                },
                // maxSpan: {
                //     days: 10 // กำหนดช่วงเวลาไม่เกิน 10 วัน
                // }
            });
        });


        document.addEventListener("DOMContentLoaded", function() {
            const filterButtons = document.querySelectorAll(".btn-group button");
            const startDate = document.getElementById("startDate");
            const MonthStart = document.getElementById("month");
            const startYear = document.getElementById("startYear");
            var statusSummary = document.getElementById("statusSummary");
            var statusDetail = document.getElementById("statusDetail");

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
                        statusDetail.disabled = false;
                        $('#box-month').prop('hidden', true);
                        $('#filter-by').val("date");
                    } else if (this.id === "filter-month") {
                        MonthStart.type = "month";
                        MonthStart.disabled = false;
                        startDate.disabled = true;
                        statusDetail.disabled = true;
                        statusSummary.checked = true;
                        $('#box-start-date').prop('hidden', true);
                        $('#box-month').prop('hidden', false);
                        $('#filter-by').val("month");
                    } else if (this.id === "filter-year") {
                        startYear.disabled = false;
                        startDate.disabled = true;
                        MonthStart.disabled = true;
                        statusDetail.disabled = false;
                        $('#box-start-date').prop('hidden', true);
                        $('#box-month').prop('hidden', true);
                        $('#box-start-year').prop('hidden', false);
                        $('#filter-by').val("year");
                    }
                });
            });
        });
    </script>
    @include('script.script')


@endsection
