<!doctype html>

<html class="no-js " lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Responsive Bootstrap 5 admin template and web Application ui kit." />
    <meta name="keyword"
        content="ALUI, Bootstrap 5, ReactJs, Angular, Laravel, VueJs, ASP .Net, Admin Dashboard, Admin Theme" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>TOGETHER DEVELOPMENT</title>
    <link rel="icon" href="../../../image/Logo1-01.png" type="image/x-icon" />
    <!-- Favicon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.9.2/semantic.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.semanticui.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.semanticui.css">

    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.min.css') }}">
    <!-- Plugin Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <!-- project css file  -->
    <link rel="stylesheet" href="{{ asset('assets/css/al.style.min.css') }}">
    <!-- project layout css file -->
    <link rel="stylesheet" href="{{ asset('assets/css/layout.c.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/src/smsPage.css') }}">

    <!-- table design css -->
    <link rel="stylesheet" href="assets/css/dataTables.min.css" />
    <link rel="stylesheet" href="assets/css/semantic.min.css">
    <link rel="stylesheet" href="assets/css/dataTables.semanticui.css">
    <link rel="stylesheet" href="assets/css/responsive.semanticui.css">
    <script src="https://nightly.datatables.net/js/jquery.dataTables.js"></script>

    <!-- ลิงค์ใส่ใหม่ -->
    <!-- Bootstrap link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <!-- สำหรับ style css -->
    <link rel="stylesheet" href="{{ asset('assets/src/table.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/src/index.css') }}">
    <!-- สำหรับ tooltip info -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>
    <!-- icon font -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
</head>

<body>
    <style>
        @media screen and (max-width: 500px) {
            .mobileHidden {
                display: none;
            }

            .mobileLabelShow {
                display: inline;
            }

            #mobileshow {
                margin-top: 60px;
            }
        }
    </style>

    @php
        // $role_permisstion = App\Models\Role_permission_menu::where('user_id', Auth::user()->id)->first();
    @endphp

    <div id="layout-c" class="theme-blue">
        <!-- Navigation -->
        <div class="navigation navbar navbar-light justify-content-center px-2 py-2 py-md-3 d-xl-none">
            <!-- Brand -->
            <div class="d-flex align-items-center">
                <a href="{{ route('sms-alert') }}" class="">
                    <img class="" src="{{ asset('assets2/images/Logo.png') }}" alt="logo of Together Resort"
                        width="50" />
                    <label class="text-white me-3 mobileLabelShow">Together Development</label>
                </a>
            </div>

            <!-- Menu: icon -->
            <ul class="nav navbar-nav flex-row flex-sm-column flex-grow-1 justify-content-start py-2 py-lg-0">
                <!-- Create group -->
                <li class="nav-item"><a class="nav-link p-2 p-lg-3 d-block d-xl-none menu-toggle me-2 me-lg-0"
                        href="#"><i class="fa fa-lg fa-bars text-white"></i></a></li>
            </ul>
        </div>

        <!-- sidebar -->
        <div class="sidebar px-4 py-2">
            <div class="d-flex flex-column h-100">
                <div class="text-center mb-2" id="mobileshow">
                    <a href="{{ route('sms-alert') }}" class="">
                        <img src="{{ asset('assets2/images/Logo.png') }}" alt="logo of Together Resort" width="120"
                            class="text-center mobileHidden" />
                    </a>
                </div>

                <!-- Menu: tab content -->
                <div class="tab-content flex-grow-1 mt-1">
                    <div class="tab-pane fade show active" id="nav-menu">
                        <!-- Menu: main ul -->
                        <ul class="menu-list">

                            @if (Auth::user()->roleMenu->profile == 1)
                                <li class="collapsed">
                                    <a class="m-link" data-bs-toggle="collapse" data-bs-target="#menu-Profile"
                                        href="#"><i class="fa fa-lg fa-user"></i> <span>Profile</span> <span
                                            class="arrow fa fa-angle-down ms-auto text-end"></span></a>

                                    <!-- Menu: Sub menu ul -->
                                    <ul class="sub-menu collapse" id="menu-Profile">
                                        <li><a class="ms-link" href="{{ route('Company.index') }}">Company / Agent</a>
                                        </li>
                                        <li><a class="ms-link" href="{{ route('guest.index') }}">Guest</a></li>
                                    </ul>
                                </li>
                            @endif
                            @if (Auth::user()->roleMenu->freelancer == 1)
                                <li class="collapsed">
                                    <a class="m-link" data-bs-toggle="collapse" data-bs-target="#menu-Freelancer"
                                        href="#"><i class="fa fa-lg fa-user-plus"></i> <span>Freelancer</span>
                                        <span class="arrow fa fa-angle-down ms-auto text-end"></span></a>

                                    <!-- Menu: Sub menu ul -->
                                    <ul class="sub-menu collapse" id="menu-Freelancer">
                                        @if (Auth::user()->roleMenu->membership == 1)
                                            <li><a class="ms-link"
                                                    href="{{ route('freelancer_member.index') }}">Membership</a></li>
                                        @endif
                                        @if (Auth::user()->roleMenu->message_inbox == 1)
                                            <li><a class="ms-link" href="#">Message Inbox</a></li>
                                        @endif
                                        @if (Auth::user()->roleMenu->registration_request == 1)
                                            <li><a class="ms-link"
                                                    href="{{ route('freelancer.index') }}">Registration Request</a>
                                            </li>
                                        @endif
                                        @if (Auth::user()->roleMenu->message_request == 1)
                                            <li><a class="ms-link" href="{{ route('freelancer.index') }}">Message
                                                    Request</a></li>
                                        @endif
                                    </ul>
                                </li>
                            @endif
                            @if (Auth::user()->roleMenu->document == 1)
                                <li class="collapsed">
                                    <a class="m-link" data-bs-toggle="collapse" data-bs-target="#menu-Document"
                                        href="#">
                                        <i class="fa fa-lg fa-folder-open"></i> <span>Document</span> <span
                                            class="arrow fa fa-angle-down ms-auto text-end"></span>
                                    </a>

                                    <!-- Menu: Sub menu ul -->
                                    <ul class="sub-menu collapse" id="menu-Document">
                                        @if (Auth::user()->roleMenu->proposal == 1)
                                            <li><a class="ms-link" href="{{ route('DummyQuotation.index') }}">Dummy
                                                    Proposal</a></li>
                                            <li><a class="ms-link" href="{{ route('ProposalReq.index') }}">Proposal
                                                    Request</a></li>
                                            <li><a class="ms-link" href="{{ route('Quotation.index') }}">Proposal</a>
                                            </li>
                                        @endif
                                        @if (Auth::user()->roleMenu->banquet_event_order == 1)
                                            <li><a class="ms-link" href="#">Banquet Event Order</a></li>
                                        @endif
                                        @if (Auth::user()->roleMenu->hotel_contact_rate == 1)
                                            <li><a class="ms-link" href="#">Hotel Contract Rate Agreement</a>
                                            </li>
                                        @endif
                                        @if (Auth::user()->roleMenu->proforma_invoice == 1)
                                            <li><a class="ms-link" href="{{ route('invoice.index') }}">Proforma
                                                    Invoice</a></li>
                                        @endif
                                        <li><a class="ms-link" href="{{ route('receipt.index') }}">Receipt Payment</a></li>
                                        @if (Auth::user()->roleMenu->billing_folio == 1)
                                            <li><a class="ms-link" href="#">Billing Folio</a></li>
                                        @endif
                                    </ul>
                                </li>
                            @endif
                            @if (Auth::user()->roleMenu->product_item == 1)
                                <li><a class="m-link" href="{{ route('Mproduct.index') }}"><i
                                            class="fa fa-lg fa-cubes" style="font-weight: bold; color: white;"></i>
                                        <span>Product Item</span></a></li>
                            @endif
                            @if (Auth::user()->roleMenu->debtor == 1)
                                <li class="collapsed">
                                    <a class="m-link" data-bs-toggle="collapse" data-bs-target="#menu-Debtor"
                                        href="#"><i class="fa fa-lg fa-file-text"></i> <span>Debtor</span> <span
                                            class="arrow fa fa-angle-down ms-auto text-end"></span></a>

                                    <!-- Menu: Sub menu ul -->
                                    <ul class="sub-menu collapse" id="menu-Debtor">
                                        @if (Auth::user()->roleMenu->agoda == 1)
                                            <li><a class="ms-link" href="{{ route('debit-agoda') }}">Agoda</a></li>
                                        @endif
                                        @if (Auth::user()->roleMenu->elexa == 1)
                                            <li><a class="ms-link" href="#">Elexa</a></li>
                                        @endif
                                    </ul>
                                </li>
                            @endif
                            @if (Auth::user()->roleMenu->maintenance == 1)
                                <li class="collapsed">
                                    <a class="m-link" data-bs-toggle="collapse" data-bs-target="#menu-Maintenance"
                                        href="#"><i class="fa fa-lg fa-gear"></i> <span>Maintenance</span> <span
                                            class="arrow fa fa-angle-down ms-auto text-end"></span></a>

                                    <!-- Menu: Sub menu ul -->
                                    <ul class="sub-menu collapse" id="menu-Maintenance">
                                        @if (Auth::user()->roleMenu->request_repair == 1)
                                            <li><a class="ms-link" href="#">Request Repair</a></li>
                                        @endif
                                        @if (Auth::user()->roleMenu->repair_job == 1)
                                            <li><a class="ms-link" href="#">Repair Job</a></li>
                                        @endif
                                        @if (Auth::user()->roleMenu->preventive_maintenance == 1)
                                            <li><a class="ms-link" href="#">Preventive Maintenance</a></li>
                                        @endif
                                    </ul>
                                </li>
                            @endif
                            @if (Auth::user()->roleMenu->general_ledger == 1)
                                <li class="collapsed">
                                    <a class="m-link" data-bs-toggle="collapse" data-bs-target="#menu-General-ledger"
                                        href="#"><i class="fa fa-lg fa-bar-chart-o"></i> <span>General
                                            Ledger</span> <span
                                            class="arrow fa fa-angle-down ms-auto text-end"></span></a>

                                    <!-- Menu: Sub menu ul -->
                                    <ul class="sub-menu collapse" id="menu-General-ledger">
                                        @if (Auth::user()->roleMenu->sms_alert == 1)
                                            <li><a class="ms-link" href="{{ route('sms-alert') }}">Daily Bank
                                                    Transaction Revenue</a></li>
                                        @endif
                                        @if (Auth::user()->roleMenu->revenue == 1)
                                            <li><a class="ms-link" href="{{ route('revenue') }}">Hotel & Water Park
                                                    Revenue</a></li>
                                        @endif
                                    </ul>
                                </li>
                            @endif
                            @if (Auth::user()->roleMenu->setting == 1)
                                <li><a class="m-link" href="#"><i class="fa fa-lg fa-list"
                                            style="font-weight: bold; color: white;"></i> <span>Report</span></a></li>
                            @endif
                            @if (Auth::user()->roleMenu->setting == 1)
                                <li class="collapsed">
                                    <a class="m-link" data-bs-toggle="collapse" data-bs-target="#menu-Setting"
                                        href="#"><i class="fa fa-lg fa-cogs"></i> <span>Setting</span> <span
                                            class="arrow fa fa-angle-down ms-auto text-end"></span></a>

                                    <!-- Menu: Sub menu ul -->
                                    <ul class="sub-menu collapse" id="menu-Setting">
                                        @if (Auth::user()->roleMenu->user == 1)
                                            <li><a class="ms-link" href="{{ route('users', 'index') }}">User</a></li>
                                        @endif
                                        @if (Auth::user()->roleMenu->bank == 1)
                                            <li><a class="ms-link" href="{{ route('master', 'bank') }}">Bank</a></li>
                                        @endif
                                        @if (Auth::user()->roleMenu->quantity == 1)
                                            <li><a class="ms-link"
                                                    href="{{ route('Mproduct.index.quantity') }}">Quantity</a></li>
                                        @endif
                                        @if (Auth::user()->roleMenu->prefix == 1)
                                            <li><a class="ms-link" href="{{ route('Mprefix.index') }}">Prename</a>
                                            </li>
                                        @endif

                                        @if (Auth::user()->roleMenu->company_type == 1)
                                            <li><a class="ms-link" href="{{ route('Mcomt.index') }}">Company Type</a>
                                            </li>
                                        @endif
                                        @if (Auth::user()->roleMenu->company_market == 1)
                                            <li><a class="ms-link" href="{{ route('Mmarket.index') }}">Company
                                                    Market</a></li>
                                        @endif
                                        @if (Auth::user()->roleMenu->company_event == 1)
                                            <li><a class="ms-link" href="{{ route('MEvent.index') }}">Company
                                                    Event</a></li>
                                        @endif
                                        @if (Auth::user()->roleMenu->booking == 1)
                                            <li><a class="ms-link" href="{{ route('Mbooking.index') }}">Booking</a>
                                            </li>
                                        @endif
                                        @if (Auth::user()->roleMenu->document_template_pdf == 1)
                                            <li><a class="ms-link"
                                                    href="{{ route('Template.TemplateA1') }}">Template</a></li>
                                        @endif

                                    </ul>
                                </li>
                            @endif
                            <li><a class="m-link" href="#" data-bs-toggle="modal"
                                    data-bs-target="#exampleModalLogout"><i class="fa fa-lg fa-power-off"
                                        style="font-weight: bold; color: white;"></i> <span>Logout</span></a></li>
                        </ul>

                    </div>
                </div>

            </div>
        </div>

        <!-- main body area -->
        <div class="main px-xl-5 px-lg-4 px-md-3">

            {{-- <div class="body-header border-bottom d-flex py-3">

                @yield('pretitle')

            </div> --}}

            <!-- main body area -->
                @yield('content')
                
            <!-- Body: Footer -->
            <div class="body-footer" style="margin: 0 8px;">
                <div class="container-xl">
                    <div class="row">
                        <div class="col-12">
                            <div class="card p-3 mb-3">
                                <div class="row justify-content-between align-items-center">
                                    <div class="col">
                                        <p class="mb-0"> Copyright <span class="d-none d-sm-inline-block">
                                                <script>
                                                    document.write(/\d{4}/.exec(Date())[0])
                                                </script> © Together Development.
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Jquery Core Js -->
    <script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>

    <!-- Plugin Js -->
    <script src="{{ asset('assets/bundles/dataTables.bundle.js') }}"></script>
    <script src="{{ asset('assets/bundles/select2.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugin/select2-searchInputPlaceholder.js') }}"></script>
    <script src="{{ asset('assets/bundles/bootstraptagsinput.bundle.js') }}"></script>

    <!-- Jquery Page Js -->
    <script src="{{ asset('assets/js/template.js') }}"></script>

    <script>
        $(document).ready(function() {

            $('#myTable').addClass('nowrap').dataTable({
                responsive: true,
                searching: true,
                paging: false,
                ordering: true,
                info: true,
                columnDefs: [
                    // className: 'bolded'
                    // { targets: [-1, -3], className: 'dt-body-right' }
                ]

            });
            $('#Receive').addClass('nowrap').dataTable({
                responsive: true,
                searching: false,
                paging: false,
                ordering: false,
                info: false,
                columnDefs: [
                    // className: 'bolded'
                    // { targets: [-1, -3], className: 'dt-body-right' }
                ]

            });
            $('#myTable2').addClass('nowrap').dataTable({
                responsive: true,
                searching: true,
                paging: true,
                ordering: true,
                info: true,
                columnDefs: [
                    // className: 'bolded'
                    // { targets: [-1, -3], className: 'dt-body-right' }
                ]

            });

            $('.myDataTable').addClass('nowrap').dataTable({
                responsive: false,
                searching: true,
                paging: true,
                ordering: true,
                info: true,
                scrollX: true,
                columnDefs: [
                    // { targets: [-1, -2], className: 'dt-body-center' }
                ]
            });

            $('#myDataTableAll').addClass('nowrap').dataTable({

                responsive: false,
                searching: true,
                paging: true,
                ordering: true,
                info: true,
                scrollX: true,
                columnDefs: [
                    // className: 'bolded'
                    // { targets: [-1, -3], className: 'dt-body-right' }
                ]

            });

            $('#myDataTableOutstanding').dataTable({

                responsive: false,
                searching: true,
                paging: true,
                ordering: true,
                info: true,
                scrollX: true,
                // "order": [[ 1, "asc" ]],
                // columnDefs: [{"targets":1, "type":"date-eu"}],

            });

            $('.myDataTableProductItem').addClass('nowrap').dataTable({
                responsive: false,
                searching: true,
                paging: true,
                ordering: true,
                info: false,
                scrollX: true,
                columnDefs: [{
                    targets: [0, -2, -1],
                    className: 'dt-body-center'
                }]
            });

            $('.myDataTableQuotation').addClass('nowrap').dataTable({
                responsive: true,
                searching: true,
                paging: true,
                ordering: true,
                info: true,
                scrollX: false,
                columnDefs: [{
                    targets: [0, -2, -1],
                    className: 'dt-body-center'
                }]
            });
            $('.myDataTableQuotationmodal').addClass('nowrap').dataTable({
                responsive: true,
                searching: true,
                paging: true,
                ordering: true,
                info: true,
                scrollX: false,
                columnDefs: [{
                    targets: [0, -2, -1],
                    className: 'dt-body-center'
                }]
            });
            $('.product-list-select').addClass('nowrap').dataTable({
                responsive: true,
                searching: true,
                paging: true,
                ordering: true,
                info: true,
                scrollX: false,
                columnDefs: [{
                    targets: [0, -2, -1],
                    className: 'dt-body-center'
                }]
            });
        });



        $('.select2').select2();

        $('.select2').select2({

            searchInputPlaceholder: 'Search...'

        });
        // Select2

        $(".country, .language").select2({});
    </script>

</body>

</html>
