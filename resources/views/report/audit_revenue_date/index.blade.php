@extends('layouts.masterLayout')
@section('content')
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
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <div class="card p-4 mb-4">
                        <div style="min-height: 10vh;">
                            <form class="row g-3">
                                <div class="col-md-12">
                                    <h5>Search</h5>
                                </div>
                                <div class="col-12 d-flex flex-row gap-3">
                                    <label for="TextInput1" class="form-label">Filter by</label>

                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <button class="btn btn-secondary">Date</button>
                                        <button class="btn btn-outline-secondary">Month</button>
                                        <button class="btn btn-outline-secondary">Year</button>
                                        <button class="btn btn-outline-secondary">Custom Rang</button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="TextInput1" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="TextInput1">
                                </div>
                                <div class="col-md-4">
                                    <label for="TextInput2" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="TextInput2">
                                </div>
                                <div class="col-md-8 d-flex flex-row gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" checked>
                                        <label class="form-check-label" for="flexRadioDefault1">All</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2">
                                        <label class="form-check-label" for="flexRadioDefault2">Verified</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDisabled">
                                        <label class="form-check-label" for="flexRadioDisabled">Unverified</label>
                                    </div>
                                </div>
                                <div class="col-md-12 text-end">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </form>                                                     
                        </div>
                    </div> <!-- .card end -->
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
        });
    </script>
        
@endsection
