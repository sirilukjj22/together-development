@extends('layouts.masterLayout')

<style>
    .btn-space {
        margin-right: 10px; /* ปรับขนาดช่องว่างตามต้องการ */
    }
</style>
@section('content')
    <div id="content-index" class="body-header d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <small class="text-muted">Welcome to System Address.</small>
                    <div class=""><span class="span1">System Address (ที่อยู่ระบบ)</span></div>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ route('System.Log') }}'">
                        LOG
                    </button>
                </div>
            </div> <!-- .row end -->
        </div>
    </div>

    <div id="content-index" class="body d-flex py-lg-4 py-3">
        <div class="container-xl">
            <div class="row clearfix">
                <div class="col-md-12 col-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <form action="{{url('/Master/System/edit/'.$address->id)}}" method="POST" enctype="multipart/form-data" >
                                @csrf
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <label for="">Name Company Main</label>
                                            <input type="text" class="form-control" name="name" id="name" value="{{$address->name}}">
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <label for="">Name Company</label>
                                            <input type="text" class="form-control" name="name_th" id="name_th" value="{{$address->name_th}}">
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 mt-2">
                                            <label for="">Address</label>
                                            <input type="text" class="form-control" name="address" id="address" value="{{$address->address}}">
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 mt-2">
                                            <label for="">Telephone</label>
                                            <input type="text" class="form-control" name="tel" id="tel" value="{{$address->tel}}">
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 mt-2">
                                            <label for="">Fax</label>
                                            <input type="text" class="form-control" name="fax" id="fax" value="{{$address->fax}}">
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 mt-2">
                                            <label for="">Hotal ID</label>
                                            <input type="text" class="form-control" name="Hotal_ID" id="Hotal_ID" value="{{$address->Hotal_ID}}">
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 mt-2">
                                            <label for="">Email</label>
                                            <input type="text" class="form-control" name="email" id="email" value="{{$address->email}}">
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 mt-2">
                                            <label for="">Website</label>
                                            <input type="text" class="form-control" name="web" id="web" value="{{$address->web}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12  col-sm-12 mt-5">
                                    <div class="row">
                                        <div class="col-lg-4"></div>
                                        <div class="col-lg-4 "  style="display:flex; justify-content:center; align-items:center;">
                                            <button type="button" class="btn btn-secondary lift btn_modal btn-space" onclick="window.location.href='{{ route('System.index') }}'">
                                                Back
                                            </button>
                                            <button type="submit" class="btn btn-color-green lift btn_modal btn-space">
                                                Save
                                            </button>
                                        </div>
                                        <div class="col-lg-4"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    @include('script.script')

@endsection
