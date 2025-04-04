<!doctype html>

<html class="no-js " lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Responsive Bootstrap 5 admin template and web Application ui kit." />
    <meta name="keyword" content="ALUI, Bootstrap 5, ReactJs, Angular, Laravel, VueJs, ASP .Net, Admin Dashboard, Admin Theme" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>HARMONY DEVELOPMENT</title>
    <link rel="icon" href="{{ asset('assets/images/harmony/logo_2.png') }}" type="image/x-icon" />

    <!-- Plugin Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}?v={{ time() }}">
    <!-- project css file  -->
    <link rel="stylesheet" href="{{ asset('assets/css/al.style.min.css') }}?v={{ time() }}">
    <!-- project layout css file -->
    <link rel="stylesheet" href="{{ asset('assets/css/layout.c.min.css') }}?v={{ time() }}">

    <!-- table design css -->
    @if(!isset($excludeDatatable))
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.9.2/semantic.min.css">
        <link rel="stylesheet" href="{{ asset('assets/css/dataTables.min.css')}}?v={{ time() }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/semantic.min.css')}}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('assets/css/dataTables.semanticui.css')}}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('assets/css/responsive.semanticui.css')}}?v={{ time() }}">
        <script src="https://nightly.datatables.net/js/jquery.dataTables.js"></script>
    @endif

    <!-- ลิงค์ใส่ใหม่ -->
    <!-- Bootstrap link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <!-- สำหรับ style css -->
    <link rel="stylesheet" href="{{ asset('assets/src/smsPage.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/src/table.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/src/index.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/src/revenue.css') }}?v={{ time() }}" />
    <link rel="stylesheet" href="{{ asset('assets/src/userProfile.css') }}?v={{ time() }}" />
    <link rel="stylesheet" href="{{ asset('assets/src/global.css') }}?v={{ time() }}" />
    <link rel="stylesheet" href="{{ asset('assets/src/tableAllDesign.css') }}?v={{ time() }}">

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

        body {
            font-size: 14px; /* กำหนดขนาดตามที่ต้องการ */
        }
    </style>

    <div id="layout-c" class="theme-blue">
        <!-- Navigation -->
        <div class="navigation navbar navbar-light justify-content-center px-2 py-2 py-md-3 d-xl-none">
            <!-- Brand -->
            <div class="d-flex align-items-center">
                <a href="{{ route('dashboard') }}" class="">
                    <img class="" src="{{ asset('assets/images/harmony/logo.png') }}" alt="logo of Harmony Resort" width="50" />
                    <label class="text-white me-3 mobileLabelShow">Harmony Development</label>
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
                    <a href="{{ route('dashboard') }}" class="">
                        <img src="{{ asset('assets/images/harmony/logo.png') }}" alt="logo of Harmony Resort" width="150" class="text-center mobileHidden" />
                    </a>
                </div>

                <!-- Menu: tab content -->
                <div class="tab-content flex-grow-1 mt-1">
                    <div class="tab-pane fade show active" id="nav-menu">
                        <!-- Menu: main ul -->
                        <ul class="menu-list">
                            <li>
                                <a class="m-link" href="{{ route('dashboard') }}">
                                    <i class="fa fa-lg fa-home" style="font-weight: bold; color: white;"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            @if (Auth::user()->roleMenu->debtor == 1)
                                <li class="collapsed">
                                    <a class="m-link" data-bs-toggle="collapse" data-bs-target="#menu-Debtor" href="#">
                                        <i class="fa fa-lg fa-file-text"></i> <span>Debtor</span> 
                                        <span class="arrow fa fa-angle-down ms-auto text-end"></span>
                                    </a>

                                    <!-- Menu: Sub menu ul -->
                                    <ul class="sub-menu collapse" id="menu-Debtor">
                                        @if (Auth::user()->roleMenu->agoda == 1)
                                            <li><a class="ms-link" href="{{ route('harmony-debit-agoda') }}">Agoda</a></li>
                                        @endif
                                        @if (Auth::user()->roleMenu->elexa == 1)
                                            <li><a class="ms-link" href="{{ route('harmony-debit-elexa') }}">Elexa EGAT</a></li>
                                        @endif
                                    </ul>
                                </li>
                            @endif

                            <li class="collapsed">
                                <a class="m-link" data-bs-toggle="collapse" data-bs-target="#menu-General-ledger" href="#">
                                    <i class="fa fa-lg fa-bar-chart-o"></i> <span>General Ledger</span> 
                                    <span class="arrow fa fa-angle-down ms-auto text-end"></span>
                                </a>

                                <!-- Menu: Sub menu ul -->
                                <ul class="sub-menu collapse" id="menu-General-ledger">
                                    @if (Auth::user()->roleMenu->sms_alert == 1)
                                        <li><a class="ms-link" href="{{ route('harmony-sms-alert') }}">Bank Transaction Revenue</a></li>
                                    @endif
                                    @if (Auth::user()->roleMenu->revenue == 1)
                                        <li><a class="ms-link" href="{{ route('harmony-revenue') }}">Hotel & Water Park Revenue</a></li>
                                    @endif
                                    @if (Auth::user()->roleMenu->audit_hotel_water_park_revenue == 1)
                                        <li><a class="ms-link" href="{{ route('harmony-report-audit-revenue-date') }}">Audit Hotel & Water Park Revenue</a></li>
                                    @endif
                                </ul>
                            </li>

                            @if (Auth::user()->roleMenu->setting == 1)
                                <li class="collapsed">
                                    <a class="m-link" data-bs-toggle="collapse" data-bs-target="#menu-Setting" href="#"><i class="fa fa-lg fa-cogs"></i>
                                        <span>Setting</span> <span class="arrow fa fa-angle-down ms-auto text-end"></span>
                                    </a>

                                    <!-- Menu: Sub menu ul -->
                                    <ul class="sub-menu collapse" id="menu-Setting">
                                        @if (Auth::user()->roleMenu->user == 1)
                                            <li class="collapsed">
                                                <a class="m-link" data-bs-toggle="collapse" data-bs-target="#menu-user-level-2" href="#"><span>User</span> <span class="arrow fa fa-plus ms-auto text-end"></span></a>

                                                <!-- Menu: Sub menu level 3 -->
                                                <ul class="sub-menu collapse" id="menu-user-level-2">
                                                    <li><a class="ms-link" href="{{ route('users', ['index', '0']) }}">User</a></li>
                                                    @if (Auth::user()->roleMenu->department == 1)
                                                        <li><a class="ms-link" href="{{ route('user-department') }}">Department</a></li>
                                                    @endif
                                                </ul>
                                            </li>
                                        @endif
                                        
                                        @if (Auth::user()->roleMenu->bank == 1)
                                            <li><a class="ms-link" href="{{ route('master', 'bank') }}">Bank</a></li>
                                        @endif
                                    </ul>
                                </li>
                            @endif

                            @if (Auth::user()->permission_branch == 3)
                                <li>
                                    <a class="m-link" href="{{ route('select-branch') }}">
                                        <i class="fa fa-lg fa-refresh" style="font-weight: bold; color: white;"></i>
                                        <span>Switch branch</span>
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a class="m-link" href="#" data-bs-toggle="modal" data-bs-target="#exampleModalLogout">
                                    <i class="fa fa-lg fa-power-off" style="font-weight: bold; color: white;"></i>
                                    <span>Logout</span>
                                </a>
                            </li>
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
            <div class="body-footer">
                <div class="container-xl p-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="card p-3 mb-3">
                                <div class="row justify-content-between align-items-center">
                                    <div class="col">
                                        <p class="mb-0"> Copyright <span class="d-none d-sm-inline-block">
                                                <script>
                                                    document.write(/\d{4}/.exec(Date())[0])
                                                </script> © Harmony Development.
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

    <!-- Modal: Logout -->
    <div class="modal fade" id="exampleModalLogout" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-color-green">
                    <h5 class="modal-title text-white" id="exampleModalLogoutLabel">Logout</h5>
                    <button type="button" class="btn-close lift" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are You Sure to Logout!</p>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary lift" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-color-green lift" onclick="location.href='{{ route('logout') }}'">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Jquery Core Js -->
    <script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>

    <!-- Plugin Js -->
    @if(!isset($excludeDatatable))
        <script src="{{ asset('assets/bundles/dataTables.bundle.js') }}"></script>
    @endif
    <script src="{{ asset('assets/bundles/select2.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugin/select2-searchInputPlaceholder.js') }}"></script>
    <script src="{{ asset('assets/bundles/bootstraptagsinput.bundle.js') }}"></script>

    @if(isset($excludeDatatable) && !$excludeDatatable) <!-- ถ้า $excludeDatatable และเท่ากับค่า false -->
        <script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/2.1.2/js/dataTables.semanticui.js"></script>
        <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
        <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.semanticui.js"></script>
    @endif

    <!-- Jquery Page Js -->
    <script src="{{ asset('assets/js/template.js') }}"></script>

</body>

</html>
