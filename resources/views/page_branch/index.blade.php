@extends('layouts.masterLayoutSelectBranch')
@section('content')
    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class=""><span class="span1">Select Branch</span></div>
                    <div class="span3">Branch</div>
                </div>
            </div> <!-- .row end -->
        </div>
    </div>
    @php
        $role_revenue = App\Models\Role_permission_revenue::where('user_id', Auth::user()->id)->first();
    @endphp
    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-2">
                    <a href="#" onclick="select_branch('Together')">
                        <div class="card text-center shadow-sm lift d-flex flex-column h-100">
                            <div class="card-body py-4">
                                <img src="{{ asset('logo_crop.png') }}" alt="Avatar" class="" width="100">
                            </div>
                            <div class="card-footer border-0">
                                <h6>Together Resort</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-2">
                    <a href="#" onclick="select_branch('Harmony')">
                        <div class="card text-center shadow-sm lift d-flex flex-column h-100">
                            <div class="card-body py-4">
                                <img src="{{ asset('assets/images/harmony/logo_2.png') }}" alt="Avatar" class="mt-4" width="130">
                            </div>
                            <div class="card-footer border-0">
                                <h6>Harmony Resort</h6>
                            </div>
                        </div>
                    </a>
                </div>
            </div> <!-- .row end -->
        </div>
    </div>

    <style>
        .swal-title-custom {
            font-size: 19px;
            font-weight: bold;
            color: #202020; 
        }
    </style>

    <!-- Sweet Alert 2 -->
    <script src="{{ asset('assets/bundles/sweetalert2.bundle.js')}}"></script>

    <script>
        function select_branch($branch) {
            Swal.fire({
                icon: "info",
                title: 'Are you sure you want to switch to '+ $branch +' ?',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Cancel',
                customClass: {
                    title: 'swal-title-custom'
                },
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    window.location.href = "{!! url('confirm-branch/"+ $branch +"') !!}";
                }
            });
        }
    </script>
@endsection
