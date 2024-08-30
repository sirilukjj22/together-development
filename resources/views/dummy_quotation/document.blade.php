@extends('layouts.masterLayout')

@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to Log Proposal.</small>
                <h1 class="h4 mt-1">Log Proposal (ประวัติการแก้ไข)</h1>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container">
    <div class="row align-items-center mb-2">
        @if (session("success"))
        <div class="alert alert-success" role="alert">
            <h4 class="alert-heading">บันทึกสำเร็จ!</h4>
            <hr>
            <p class="mb-0">{{ session('success') }}</p>
        </div>
        @endif
    </div> <!-- Row end  -->

    <div class="row clearfix">
        <div class="col-sm-12 col-12">
            <div class="card p-4 mb-4">
                <form enctype="multipart/form-data" class="row g-3 basic-form" id="form-id2">
                    @csrf
                    <input type="hidden" name="category" value="prename">
                <table class="myDataTableQuotation table table-hover align-middle mb-0" style="width:100%">
                    <thead>
                        <tr>
                            <th  class="text-center">No</th>
                            <th  >Category</th>
                            <th  class="text-center">Type</th>
                            <th  class="text-center">Created_by</th>
                            <th  class="text-center">content</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($logproposal))
                            @foreach($logproposal as $key => $item)
                            <tr>
                                <td style="text-align: center;">{{$key +1 }}</td>
                                <td style="text-align: left;">{{$item->Category}}</td>
                                <td style="text-align: center;">{{$item->type}}</td>
                                <td style="text-align: center;">{{@$item->userOperated->name}}</td>
                                @php
                                    // แยกข้อมูล content ออกเป็น array
                                    $contentArray = explode('+', $item->content);
                                @endphp
                                <td style="text-align: left;">

                                    <b style="color:#0000FF ">{{$item->Category}}</b>
                                    @foreach($contentArray as $contentItem)
                                        <div>{{ $contentItem }}</div>
                                    @endforeach
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                </form>
            </div> <!-- .card end -->
        </div>
        <div class="col-12 row mt-3">
            <div class="col-4"></div>
            <div class="col-4 "  style="display:flex; justify-content:center; align-items:center;">
                <button type="button" class="btn btn-secondary lift btn_modal btn-space"  onclick="window.location.href='{{ route('Quotation.index') }}'">
                    Back
                </button>

            </div>
            <div class="col-4"></div>
        </div>
    </div> <!-- .row end -->
</div>

<form id="form-id3">
    @csrf
    <input type="hidden" id="deleteID" name="deleteID" value="">
</form>

@include('script.script')

@endsection