<!doctype html>

<html class="no-js " lang="en">



<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=Edge">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description" content="Responsive Bootstrap 5 admin template and web Application ui kit.">

    <meta name="keyword"
        content="ALUI, Bootstrap 5, ReactJs, Angular, Laravel, VueJs, ASP .Net, Admin Dashboard, Admin Theme">

    <meta name="csrf-token" content="{{ csrf_token() }}" />



    <title>TOGETHER DEVELOPMENT</title>

    <link rel="icon" href="../../../image/Logo1-01.png" type="image/x-icon"> <!-- Favicon -->



    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.min.css') }}">



    <!-- Plugin Css -->

    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">



    <!-- project css file  -->

    <link rel="stylesheet" href="{{ asset('assets/css/al.style.min.css') }}">



    <!-- project layout css file -->

    <link rel="stylesheet" href="{{ asset('assets/css/layout.a.min.css') }}">



</head>



<body>



    <div id="layout-a" class="theme-blue">



        <!-- Navigation -->

        <div class="navigation navbar navbar-light justify-content-center px-3 px-lg-2 py-2 py-md-3 border-right">



            <!-- Brand -->

            <a href="{{ route('sms-alert') }}" class="mb-0 mb-lg-3 brand-icon">

                <img src="../../../image/Logo1-01.png" alt="" width="40">

            </a>



            <!-- Menu: icon -->

            <ul class="nav navbar-nav flex-row flex-sm-column flex-grow-1 justify-content-start py-2 py-lg-0">



                <!-- Create group -->

                <li class="nav-item"><a class="nav-link p-2 p-lg-3 d-block d-xl-none menu-toggle me-2 me-lg-0"
                        href="#"><i class="fa fa-bars"></i></a></li>

                {{-- <li class="nav-item"><a class="nav-link p-2 p-lg-3" href="#" title="Search" data-bs-toggle="modal" data-bs-target="#SearchModal"><i class="fa fa-search"></i></a></li> --}}



                <!-- Menu collapse -->

                <li class="nav-item"><a class="nav-link p-2 p-lg-3" href="#" title="Settings"
                        data-bs-toggle="modal" data-bs-target="#SettingsModal"><i class="fa fa-gear"></i></a></li>



            </ul>



        </div>



        <!-- sidebar -->

        <div class="sidebar px-3 py-2 py-md-3">

            <div class="d-flex flex-column h-100">

                <center>

                    <img src="../../../image/Logo-02-01.png" alt="" width="120">

                </center>



                <!-- Menu: main ul -->

                <ul class="menu-list flex-grow-1 pb-4">
                    @php
                        $role_permisstion = App\Models\Role_permission_menu::where('user_id', Auth::user()->id)->first();
                    @endphp

                    <li class="divider mt-2 py-2 border-top text-uppercase"></li>
                    @if ($role_permisstion->sms_alert == 1)
                        <li>
                            <a class="m-link" href="{{ route('sms-alert') }}"><i class="fa fa-comment"></i>
                                <span>SMS Alert</span>
                            </a>
                        </li>
                    @endif
                    @if ($role_permisstion->revenue == 1)
                    <li>
                        <a class="m-link" href="{{ route('revenue') }}"><i class="fa fa-bar-chart-o"></i>
                            <span>Revenue</span>
                        </a>
                    </li>
                    @endif

                    @if ($role_permisstion->debtor == 1)
                        <li class="collapsed">
                            <a class="m-link" data-bs-toggle="collapse" data-bs-target="#menu-Components" href="#"><i class="fa fa-clipboard"></i>
                                <span>Debtor</span> <span class="arrow fa fa-plus ms-auto text-end"></span>
                            </a>
                            <!-- Menu: Sub menu ul -->
                            <ul class="sub-menu collapse" id="menu-Components">
                                <?php
                                    $agoda_count = App\Models\SMS_alerts::where('status', 5)->where('status_receive_agoda', 0)->count();
                                ?>
                                @if ($role_permisstion->agoda == 1)
                                <li>
                                    <a class="ms-link" href="{{ route('debit-agoda') }}">
                                        Agoda <span class="badge bg-danger ms-auto text-end">{{ $agoda_count }}</span>
                                    </a>
                                </li>
                                @endif
                                @if ($role_permisstion->elexa == 1)
                                    <li><a class="ms-link" href="#">Elexa</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if ($role_permisstion->user == 1)
                        <li>
                            <a class="m-link" href="{{ url('users', 'index') }}"><i class="fa fa-user-circle"></i><span>Users</span></a>
                        </li>
                    @endif

                        <li class="divider mt-4 py-2 border-top"><small>MASTER DATA</small></li>
                        @if ($role_permisstion->bank == 1)
                            <li>
                                <a class="m-link" href="{{ route('master', 'bank') }}"><i
                                        class="fa fa-folder-open"></i><span>Bank</span></a>
                            </li>
                        @endif
                        <li>
                            <a class="m-link" href="{{ route('Mproduct.index.quantity') }}"><i
                                    class="fa fa-folder-open"></i><span>Quantity</span></a>
                        </li>
                        <li>
                            <a class="m-link" href="{{ route('Mproduct.index.unit') }}"><i
                                    class="fa fa-folder-open"></i><span>Unit</span></a>
                        </li>
                        <li>
                            <a class="m-link" href="{{ route('Mprefix.index') }}"><i
                                    class="fa fa-folder-open"></i><span>Prename</span></a>
                        </li>
                        <li>
                            <a class="m-link" href="{{ route('Mbank.index') }}"><i
                                    class="fa fa-folder-open"></i><span>Bank Company</span></a>
                        </li>
                        <li>
                            <a class="m-link" href="{{ route('Mcomt.index') }}"><i
                                    class="fa fa-folder-open"></i><span>Company Type</span></a>
                        </li>
                        <li>
                            <a class="m-link" href="{{ route('Mmarket.index') }}"><i
                                    class="fa fa-folder-open"></i><span>Company Market</span></a>
                        </li>
                        <li>
                            <a class="m-link" href="{{ route('MEvent.index') }}"><i
                                    class="fa fa-folder-open"></i><span>Company Event</span></a>
                        </li>
                        <li>
                            <a class="m-link" href="{{ route('Mbooking.index') }}"><i
                                    class="fa fa-folder-open"></i><span>Booking</span></a>
                        </li>
                        <li>
                            <a class="m-link" href="{{ route('Template.TemplateA1') }}"><i
                                    class="fa fa-folder-open"></i><span>Template</span></a>
                        </li>

                </ul>
            </div>
        </div>

        <!-- main body area -->
        <div class="main px-md-3">
            <!-- Body: Header -->
            <div class="body-header border-bottom d-flex py-3">

                @yield('pretitle')

            </div>

            <!-- Body: Body -->
            <div class="body d-flex py-lg-4 py-3">

                @yield('content')

            </div>



            <!-- Body: Footer -->

            <div class="body-footer">

                <div class="container">

                    <div class="row">

                        <div class="col-12">

                            <div class="card p-3 mb-3">

                                <div class="row justify-content-between align-items-center">

                                    <div class="col">

                                        <p class="mb-0">Copyright <span class="d-none d-sm-inline-block">
                                                <script>
                                                    document.write(/\d{4}/.exec(Date())[0])
                                                </script> © Together Development.
                                            </span></p>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



        </div>



        <!-- Modal: Search -->

        <div class="modal fade" id="SearchModal" tabindex="-1">

            <div class="modal-dialog modal-dialog-vertical modal-dialog-scrollable">

                <div class="modal-content">

                    <div class="modal-header bg-secondary border-bottom-0 px-3 px-md-5">

                        <h5 class="modal-title">Search</h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    </div>

                    <div class="modal-body custom_scroll">

                        <div class="card-body-height py-4 px-2 px-md-4">

                            <form class="mb-3">

                                <div class="input-group mb-3">

                                    <input type="text" class="form-control" placeholder="Search...">

                                    <button class="btn btn-outline-secondary" type="button"><span
                                            class="fa fa-search"></span> Search</button>

                                </div>

                            </form>



                            <small class="dropdown-header">Recent searches</small>

                            <div class="dropdown-item bg-transparent text-wrap my-2">

                                <span class="h4 me-1">

                                    <a class="btn btn-sm btn-dark" href="#">Github <i
                                            class="fa fa-search ms-1"></i></a>

                                </span>

                                <span class="h4">

                                    <a class="btn btn-sm btn-dark" href="#">Notification panel <i
                                            class="fa fa-search ms-1"></i></a>

                                </span>

                                <span class="h4">

                                    <a class="btn btn-sm btn-dark" href="#">New project <i
                                            class="fa fa-search ms-1"></i></a>

                                </span>

                            </div>



                            <div class="dropdown-divider my-3"></div>



                            <small class="dropdown-header">Tutorials</small>

                            <a class="dropdown-item py-2" href="#">

                                <div class="d-flex align-items-center">

                                    <span class="avatar sm no-thumbnail me-2"><i class="fa fa-github"></i></span>

                                    <div class="text-truncate">

                                        <span>How to set up Github?</span>

                                    </div>

                                </div>

                            </a>

                            <a class="dropdown-item py-2" href="#">

                                <div class="d-flex align-items-center">

                                    <span class="avatar sm no-thumbnail me-2"><i class="fa fa-paint-brush"></i></span>

                                    <div class="text-truncate">

                                        <span>How to change theme color?</span>

                                    </div>

                                </div>

                            </a>



                            <div class="dropdown-divider my-3"></div>



                            <small class="dropdown-header">Members</small>

                            <a class="dropdown-item py-2" href="#">

                                <div class="d-flex align-items-center">

                                    <img class="avatar sm rounded-circle" src="../assets/images/xs/avatar1.jpg"
                                        alt="">

                                    <div class="text-truncate ms-2">

                                        <span>Robert Hammer <i class="fa fa-check-circle text-primary"
                                                data-bs-toggle="tooltip" data-placement="top" title=""
                                                data-original-title="Top endorsed"></i></span>

                                    </div>

                                </div>

                            </a>

                            <a class="dropdown-item py-2" href="#">

                                <div class="d-flex align-items-center">

                                    <img class="avatar sm rounded-circle" src="../assets/images/xs/avatar2.jpg"
                                        alt="">

                                    <div class="text-truncate ms-2">

                                        <span>Orlando Lentz</span>

                                    </div>

                                </div>

                            </a>

                            <a class="dropdown-item py-2" href="#">

                                <div class="d-flex align-items-center">

                                    <div class="avatar sm rounded-circle no-thumbnail">RH</div>

                                    <div class="text-truncate ms-2">

                                        <span>Brian Swader</span>

                                    </div>

                                </div>

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        <!-- Modal: Setting -->

        <div class="modal fade" id="SettingsModal" tabindex="-1">

            <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title">ออกจากระบบ</h5>

                    </div>

                    <div class="modal-body custom_scroll">

                        <label class="form-check-label" for="CheckImage">ต้องการออกจากระบบใช่หรือไม่ ?</label>

                    </div>

                    <div class="modal-footer d-flex justify-content-start text-center">

                        <button type="button" class="btn flex-fill btn-white border lift"
                            data-bs-dismiss="modal">ยกเลิก</button>

                        <a href="{{ route('logout') }}" class="btn flex-fill btn-primary lift">ออกจากระบบ</a>

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
                paging: true,
                ordering: true,
                info: true,
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

            $('.myDataTable').addClass( 'nowrap' ).dataTable( {
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

        });



        $(document).ready(function() {

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

            $('.myDataTableProductItem').addClass( 'nowrap' ).dataTable( {
                responsive: false,
                searching: true,
                paging: true,
                ordering: true,
                info: false,
                scrollX: true,
                columnDefs: [
                    { targets: [0, -2, -1], className: 'dt-body-center' }
                ]
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
