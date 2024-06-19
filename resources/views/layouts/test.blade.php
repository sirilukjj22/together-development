<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
    <META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
    <META http-equiv="expires" content="0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    {{-- header ("Last-Modified: " . gmdate ("D, d M Y H:i:s") . " GMT"); --}}

    <title>TOGETHER DEVELOPMENT</title>

    <link rel="icon" href="{{ asset('image/Logo1-01.png') }}" type="image/x-icon"> <!-- Favicon -->
    <link rel="stylesheet" href="{{ asset('assets2/css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai+Looped:wght@100;200;300;400;500;600;700;800;900&family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" /> --}}
    <link rel="stylesheet" href="{{ asset('assets2/css/dataTables.dataTables.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="{{ asset('assets2/css/dataTables.dataTables.css')}}">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap5.css">
</head>

<body style="background: #e4e4e4;">
    @php
        $role_permisstion = App\Models\Role_permission_menu::where('user_id', Auth::user()->id)->first();
    @endphp

    <div id="mobileshow">
        <style>
          .dropdown-submenu {
            position: relative;
          }

          .dropdown-submenu .dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: 0;
            margin-left: 0;
          }

          #mobileshow {
            display: none;
          }

          @media screen and (max-width: 500px) {
            #mobileshow {
              display: block;
            }
          }
        </style>

        <nav class="navbar fixed-top navbar-expand-lg navbar-light" style="background-color: #109699;">
          <div class="container-fluid">
            <a class="navbar-brand h-1 text-white" href="{{ route('sms-alert') }}"><img class="mr-2" src="{{ asset('assets2/images/Logo.png') }}"
                style="width: 50px; height: 50px; float: left;" alt="">
              <h6 class="mt-2">Together Resort <br>Development</h6>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa-solid fa-bars text-white"></i>
              </button>
            <div class="collapse navbar-collapse rounded p-3 bg-white" id="navbarNavDropdown">
              <ul class="navbar-nav font-weight-bold">
                @if ($role_permisstion->profile == 1)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-2" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-expanded="false">
                            Profile
                        </a>
                        <ul class="dropdown-menu"  aria-labelledby="navbarDropdownMenuLink">
                            @if ($role_permisstion->company == 1)
                                <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="{{ route('Company.index') }}">Company / Agent</a></li>
                            @endif
                            @if ($role_permisstion->guest == 1)
                                <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="{{ route('guest.index') }}">Guest</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if ($role_permisstion->freelancer == 1)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-2" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-expanded="false">
                            Freelancer
                        </a>
                        <ul class="dropdown-menu"  aria-labelledby="navbarDropdownMenuLink">
                            @if ($role_permisstion->membership == 1)
                                <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="{{ route('freelancer_member.index') }}">Membership</a></li>
                            @endif
                            @if ($role_permisstion->message_inbox == 1)
                                <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="#">Message Inbox</a></li>
                            @endif
                            @if ($role_permisstion->registration_request == 1)
                                <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="#">Registration Request</a></li>
                            @endif
                            @if ($role_permisstion->message_request == 1)
                                <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="#">Message Request</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if ($role_permisstion->document == 1)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-2" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-expanded="false">
                            Document
                        </a>
                        <ul class="dropdown-menu"  aria-labelledby="navbarDropdownMenuLink">
                            @if ($role_permisstion->proposal == 1)
                                <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="{{ route('Quotation.index') }}">Proposal</a></li>
                            @endif
                            @if ($role_permisstion->banquet_event_order == 1)
                                <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="#">Banquet Event Order</a></li>
                            @endif
                            @if ($role_permisstion->hotel_contact_rate == 1)
                                <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="#">Hotel Contract Rate Agreement</a></li>
                            @endif
                            @if ($role_permisstion->proforma_invoice == 1)
                                <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="#">Proforma Invoice</a></li>
                            @endif
                            @if ($role_permisstion->billing_folio == 1)
                                <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="#">Billing Folio</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if ($role_permisstion->debtor == 1)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-2" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-expanded="false">
                            Debtor
                        </a>
                        <ul class="dropdown-menu"  aria-labelledby="navbarDropdownMenuLink">
                            @if ($role_permisstion->agoda == 1)
                                <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="{{ route('debit-agoda-revenue') }}">Agoda</a></li>
                            @endif
                            @if ($role_permisstion->elexa == 1)
                                <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="#">Elexa</a></li>
                            @endif
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-2" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-expanded="false">
                            Maintenance
                        </a>
                        <ul class="dropdown-menu"  aria-labelledby="navbarDropdownMenuLink">
                            @if ($role_permisstion->request_repair == 1)
                                <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="#">Request Repair</a></li>
                            @endif
                            @if ($role_permisstion->repair_job == 1)
                                <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="#">Repair Job</a></li>
                            @endif
                            @if ($role_permisstion->preventive_maintenance == 1)
                                <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="#">Preventive Maintenance</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if ($role_permisstion->general_ledger == 1)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-2" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-expanded="false">
                            General Ledger
                        </a>
                        <ul class="dropdown-menu"  aria-labelledby="navbarDropdownMenuLink">
                            @if ($role_permisstion->sms_alert == 1)
                                <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="{{ route('sms-alert') }}">Daily Bank Transaction Revenue</a></li>
                            @endif
                            @if ($role_permisstion->revenue == 1)
                                <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="{{ route('revenue') }}">Hotel & Water Park Revenue</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if ($role_permisstion->setting == 1)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-2" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-expanded="false">
                            Setting
                        </a>
                        <ul class="dropdown-menu"  aria-labelledby="navbarDropdownMenuLink">
                            @if ($role_permisstion->user == 1)
                                <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="{{ route('users', 'index') }}">User</a></li>
                            @endif
                            @if ($role_permisstion->bank == 1)
                                <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="{{ route('master', 'bank') }}">Bank</a></li>
                            @endif
                            @if ($role_permisstion->document_template_pdf == 1)
                                <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="{{ route('Template.TemplateA1') }}">Template</a></li>
                            @endif
                            <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="{{ route('Mproduct.index') }}">Product Item</a></li>
                            <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="{{ route('Mproduct.index.quantity') }}">Quantity</a></li>
                            <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="{{ route('Mproduct.index.unit') }}">Unit</a></li>
                            <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="{{ route('Mprefix.index') }}">Prefix</a></li>
                            <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="{{ route('Mbank.index') }}">Bank Company</a></li>
                            <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="{{ route('Mcomt.index') }}">Company Type</a></li>
                            <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="{{ route('Mmarket.index') }}">Company Market</a></li>
                            <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="{{ route('MEvent.index') }}">Company Event</a></li>
                            <li class="p-0 remove-hover"><a class="nav-link px-3 font-weight-bold dropdown-item" href="{{ route('Mbooking.index') }}">Booking</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <li>
                            <a class="nav-link px-2" href="#">Debtor Maintenance</a>
                        </li>
                    </li>
                @endif

                <li class="nav-item">
                    <li>
                        <a class="nav-link px-2" href="#" data-toggle="modal" data-target="#exampleModalCenter3">Logout</a>
                    </li>
                </li>
              </ul>
            </div>
          </div>
        </nav>
      </div>

    {{-- <div class="for100vh"> --}}
    <div class="wrapper">
        <div class="menu">
            <div class="logo">
                <a href="{{ route('sms-alert') }}"><img src="{{ asset('assets2/images/Logo.png') }}"
                        id="logo_togeter"></a>
            </div>
            <h1>Menu</h1>

            @if ($role_permisstion->profile == 1)
                <div class="dropdown">
                    <button onclick="myFunction()" class="dropbtn">Profile &nbsp; <i
                            class="fa-solid fa-caret-down"></i></button>
                    <div id="myDropdown" class="dropdown-content">
                        @if ($role_permisstion->company == 1)
                            <a class="menu2" href="{{ route('Company.index') }}">Company / Agent</a>
                        @endif
                        @if ($role_permisstion->guest == 1)
                            <a class="menu2" href="{{ route('guest.index') }}">Guest</a>
                        @endif
                    </div>
                </div>
            @endif

            @if ($role_permisstion->freelancer == 1)
                <div class="dropdown">
                    <button onclick="myFunctionFreelancer()" class="dropbtn">Freelancer &nbsp;
                        <i class="fa-solid fa-caret-down"></i></button>
                    <div id="myDropdownFreelancer" class="dropdown-content">
                        @if ($role_permisstion->membership == 1)
                            <a class="menu2" href="{{ route('freelancer_member.index') }}">Membership</a>
                        @endif
                        @if ($role_permisstion->message_inbox == 1)
                            <a class="menu2" href="#">Message Inbox</a>
                        @endif
                        @if ($role_permisstion->registration_request == 1)
                            <a class="menu2" href="{{ route('freelancer.index') }}">Registration Request</a>
                        @endif
                        @if ($role_permisstion->message_request == 1)
                            <a class="menu2" href="#">Message Request</a>
                        @endif
                    </div>
                </div>
            @endif

            @if ($role_permisstion->document == 1)
                <div class="dropdown">
                    <button onclick="myFunctionDocument()" class="dropbtn">Document &nbsp;
                        <i class="fa-solid fa-caret-down"></i></button>
                    <div id="myDropdownDocument" class="dropdown-content">
                        @if ($role_permisstion->proposal == 1)
                            <a class="menu2" href="{{ route('Quotation.index') }}">Proposal</a>
                        @endif
                        @if ($role_permisstion->banquet_event_order == 1)
                            <a class="menu2" href="#">Banquet Event Order</a>
                        @endif
                        @if ($role_permisstion->hotel_contact_rate == 1)
                            <a class="menu2" href="#">Hotel Contract Rate Agreement</a>
                        @endif
                        @if ($role_permisstion->proforma_invoice == 1)
                            <a class="menu2" href="#">Proforma Invoice</a>
                        @endif
                        @if ($role_permisstion->billing_folio == 1)
                            <a class="menu2" href="#">Billing Folio</a>
                        @endif
                    </div>
                </div>
            @endif

            @if ($role_permisstion->debtor == 1)
            <div class="dropdown">
                <button onclick="myFunctionDebtor()" class="dropbtn">Debtor &nbsp;
                    <i class="fa-solid fa-caret-down"></i></button>
                <div id="myDropdownDebtor" class="dropdown-content">
                    @if ($role_permisstion->agoda == 1)
                        <a class="menu2" href="{{ route('debit-agoda-revenue') }}">Agoda</a>
                    @endif
                    @if ($role_permisstion->elexa == 1)
                        <a class="menu2" href="#">Elexa</a>
                    @endif
                </div>
            </div>

            <div class="dropdown">
                <button onclick="myFunctionMaintenance()" class="dropbtn">Maintenance &nbsp;
                    <i class="fa-solid fa-caret-down"></i></button>
                <div id="myDropdownMaintenance" class="dropdown-content">
                    @if ($role_permisstion->request_repair == 1)
                        <a class="menu2" href="#">Request Repair</a>
                    @endif
                    @if ($role_permisstion->repair_job == 1)
                        <a class="menu2" href="#">Repair Job</a>
                    @endif
                    @if ($role_permisstion->preventive_maintenance == 1)
                        <a class="menu2" href="#">Preventive Maintenance</a>
                    @endif
                </div>
            </div>
            @endif

            @if ($role_permisstion->general_ledger == 1)
                <div class="dropdown">
                    <button onclick="myFunctionGeneralLedger()" class="dropbtn">General Ledger &nbsp;
                        <i class="fa-solid fa-caret-down"></i></button>
                    <div id="myDropdownGeneralLedger" class="dropdown-content">
                            @if ($role_permisstion->sms_alert == 1)
                                <a class="menu2" href="{{ route('sms-alert') }}">Daily Bank Transaction Revenue</a>
                            @endif
                            @if ($role_permisstion->revenue == 1)
                                <a class="menu2" href="{{ route('revenue') }}">Hotel & Water Park Revenue</a>
                            @endif
                    </div>
                </div>
            @endif

            @if ($role_permisstion->setting == 1)
                <div class="dropdown">
                    <button onclick="myFunctionSetting()" class="dropbtn">Setting &nbsp;
                        <i class="fa-solid fa-caret-down"></i></button>
                    <div id="myDropdownSetting" class="dropdown-content">
                        @if ($role_permisstion->user == 1)
                            <a class="menu2" href="{{ route('users', 'index') }}">User</a>
                        @endif
                        @if ($role_permisstion->bank == 1)
                            <a class="menu2" href="{{ route('master', 'bank') }}">Bank</a>
                        @endif
                        @if ($role_permisstion->document_template_pdf == 1)
                            <a class="menu2" href="{{ route('Template.TemplateA1') }}">Template</a>
                        @endif
                        <a class="menu2" href="{{ route('Mproduct.index') }}">Product Item</a>
                        <a class="menu2" href="{{ route('Mproduct.index.quantity') }}">Quantity</a>
                        <a class="menu2" href="{{ route('Mproduct.index.unit') }}">Unit</a>
                        <a class="menu2" href="{{ route('Mprefix.index') }}">Prefix</a>
                        <a class="menu2" href="{{ route('Mbank.index') }}">Bank Company</a>
                        <a class="menu2" href="{{ route('Mprefix.index') }}">Prefix</a>
                        <a class="menu2" href="{{ route('Mcomt.index') }}">Company Type</a>
                        <a class="menu2" href="{{ route('Mmarket.index') }}">Company Market</a>
                        <a class="menu2" href="{{ route('MEvent.index') }}">Company Event</a>
                        <a class="menu2" href="{{ route('Mbooking.index') }}">Booking</a>
                    </div>
                </div>
            @endif

            <div>
                <!-- Button trigger modal -->
                <button type="button" class="menu2 pb-2" style="width:100%;"
                data-toggle="modal" data-target="#exampleModalCenter3">
                Logout
              </button>
            </div>
        </div>

        @yield('content')

    </div>
    {{-- </div> --}}

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Log out</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="text-align: center; font-size: 24px; font-weight: 600; padding: 84px;">
                    <img src="{{ asset('assets2/images/logout.png') }}" alt=""><br>
                    Are You Sure to Logout
                </div>
                <div class="modal-footer">
                    <button type="button" class="button-17 button-18"
                        style="background-color: #f44336; color: black;" data-dismiss="modal">Close</button>
                    <button type="button" class="button-17 button-18"
                        onclick="location.href='{{ route('logout') }}'">Logout</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalCenter3" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Logout</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body-logout">
                    <img src="{{ asset('assets2/images/logout.png') }}" alt=""><br>
                    <h2>Are You Sure to Logout</h2>
                </div>
                <div class="modal-footer mb-2" style="all: unset; ">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" class="btn btn-primary border-0" style="background-color: #f44336; width: 100%; color: white;"
                            data-dismiss="modal">Cancel</button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-primary border-0" onclick="location.href='{{ route('logout') }}'" style="background-color: #109699; width: 100%;">Logout</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- <div class="modal fade" id="exampleModalCenter3" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Logout</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="text-align: center; font-size: 28px; font-weight: 600; padding: 84px;">
                    <img src="{{ asset('assets2/images/logout.png') }}" alt=""><br>
                    Are You Sure to Logout
                </div>
                <div class="modal-footer">
                    <button type="button" class="button-17 button-18"
                        style="background-color: #f44336; color: white;" data-dismiss="modal">Close</button>
                    <button type="button" class="button-17 button-18"
                        onclick="location.href='{{ route('logout') }}'">Logout</button>
                </div>
            </div>
        </div>
    </div> --}}
</body>

<script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.bootstrap5.js"></script>
<script>
    /* When the user clicks on the button, toggle between hiding and showing the dropdown content */

    function myFunctionDebtor() {
        var dropdownDebtor = document.getElementById("myDropdownDebtor");

        dropdownDebtor.classList.toggle("show");

        if (dropdownDebtor.classList.contains("show")) {
            dropdownDebtor.style.maxHeight = dropdownDebtor.scrollHeight + "px";
        } else {
            dropdownDebtor.style.maxHeight = "0";
        }
    }

    function myFunctionMaintenance() {
        var dropdownMaintenance = document.getElementById("myDropdownMaintenance");

        dropdownMaintenance.classList.toggle("show");

        if (dropdownMaintenance.classList.contains("show")) {
            dropdownMaintenance.style.maxHeight = dropdownMaintenance.scrollHeight + "px";
        } else {
            dropdownMaintenance.style.maxHeight = "0";
        }
    }

    function myFunctionSetting() {
        var dropdownSetting = document.getElementById("myDropdownSetting");

        dropdownSetting.classList.toggle("show");

        if (dropdownSetting.classList.contains("show")) {
            dropdownSetting.style.maxHeight = dropdownSetting.scrollHeight + "px";
        } else {
            dropdownSetting.style.maxHeight = "0";
        }
    }

    function myFunctionGeneralLedger() {
        var dropdownGeneralLedger = document.getElementById("myDropdownGeneralLedger");

        dropdownGeneralLedger.classList.toggle("show");

        if (dropdownGeneralLedger.classList.contains("show")) {
            dropdownGeneralLedger.style.maxHeight = dropdownGeneralLedger.scrollHeight + "px";
        } else {
            dropdownGeneralLedger.style.maxHeight = "0";
        }
    }

    function myFunction() {
        var dropdown = document.getElementById("myDropdown");

        dropdown.classList.toggle("show");

        if (dropdown.classList.contains("show")) {
            dropdown.style.maxHeight = dropdown.scrollHeight + "px";
        } else {
            dropdown.style.maxHeight = "0";
        }
    }

    function myFunctionFreelancer() {
        var dropdown = document.getElementById("myDropdownFreelancer");

        dropdown.classList.toggle("show");

        if (dropdown.classList.contains("show")) {
            dropdown.style.maxHeight = dropdown.scrollHeight + "px";
        } else {
            dropdown.style.maxHeight = "0";
        }
    }

    function myFunctionFreelancerAlert() {
        var dropdown = document.getElementById("myDropdownFreelancerAlert");

        dropdown.classList.toggle("show");

        if (dropdown.classList.contains("show")) {
            dropdown.style.maxHeight = dropdown.scrollHeight + "px";
        } else {
            dropdown.style.maxHeight = "0";
        }
    }

    function myFunctionDocument() {
        var dropdown = document.getElementById("myDropdownDocument");

        dropdown.classList.toggle("show");

        if (dropdown.classList.contains("show")) {
            dropdown.style.maxHeight = dropdown.scrollHeight + "px";
        } else {
            dropdown.style.maxHeight = "0";
        }
    }

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
        if (!event.target.matches(".dropbtn")) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                // if (openDropdown.classList.contains("show")) {
                //   openDropdown.classList.remove("show");
                //   openDropdown.style.maxHeight = "0";
                // }
            }
        }
    }

    // Ensure the dropdown menus work correctly
    // document.addEventListener('DOMContentLoaded', function () {
    //     var dropdownSubmenu = document.querySelectorAll('.dropdown-submenu');

    //     dropdownSubmenu.forEach(function (submenu) {
    //     submenu.addEventListener('click', function (e) {
    //         e.stopPropagation();
    //         var dropdownMenu = this.querySelector('.dropdown-menu');
    //         dropdownMenu.classList.toggle('show');
    //     });
    //     });
    // });

    // document.addEventListener('DOMContentLoaded', function () {
    //     var dropdownSubmenu = document.querySelectorAll('.dropdown-submenu');

    //     dropdownSubmenu.forEach(function (submenu) {
    //     submenu.addEventListener('click', function (e) {
    //         e.stopPropagation();
    //         var dropdownMenu = this.querySelector('.dropdown-menu');
    //         dropdownMenu.classList.toggle('show');
    //     });
    //     });
    // });

    function toggleDropdown() {
        var dropdown = document.getElementById("myDropdown");
        dropdown.classList.toggle("show");
        if (dropdown.classList.contains("show")) {
        dropdown.style.maxHeight = dropdown.scrollHeight + "px";
        } else {
        dropdown.style.maxHeight = "0";
        }
    }

    window.onclick = function (event) {
        if (!event.target.matches(".dropbtn")) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains("show")) {
            openDropdown.classList.remove("show");
            openDropdown.style.maxHeight = "0";
            }
        }
        }
    }
</script>

</html>
