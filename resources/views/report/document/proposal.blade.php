@extends('layouts.masterLayout')

@section('content')
    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <small class="text-muted">Welcome to Document Proposal Report.</small>
                    <div class=""><span class="span1">Document Proposal Report</span></div>
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
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-12 col-12">

                    <div class="card mb-3">
                        <div class="card-body">

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
    </script>
    @include('script.script')


@endsection
