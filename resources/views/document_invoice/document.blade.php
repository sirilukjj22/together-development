@extends('layouts.masterLayout')

@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to Log Invoice.</small>
                <h1 class="h4 mt-1">Log Invoice (ประวัติใบแจ้งหนี้)</h1>
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
                            <th>No</th>
                            <th>Quotation ID</th>
                            <th>Quotation Type</th>
                            <th>Correct No</th>
                            <th class="text-center">Export</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($log))
                            @foreach ($log as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->Quotation_ID }}</td>
                                <td>{{ $item->QuotationType }}</td>
                                <td>{{ $item->correct }}</td>
                                <td class="text-center">
                                    @if ($item->correct == $correct)
                                        @if ($correct == 0)
                                            <a href="{{ asset($path.$item->Quotation_ID.".pdf") }}" type="button" class="btn btn-outline-dark rounded-pill lift" target="_blank" data-toggle="tooltip" data-placement="top" title="พิมพ์เอกสาร">
                                                <i class="fa fa-print"></i>
                                            </a>
                                        @else
                                            <a href="{{ asset($path.$item->Quotation_ID.'-'.$correct.".pdf") }}" type="button" class="btn btn-outline-dark rounded-pill lift" target="_blank" data-toggle="tooltip" data-placement="top" title="พิมพ์เอกสาร">
                                                <i class="fa fa-print"></i>
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ asset($path.$item->Quotation_ID.".pdf") }}" type="button" class="btn btn-outline-dark rounded-pill lift" target="_blank" data-toggle="tooltip" data-placement="top" title="พิมพ์เอกสาร">
                                            <i class="fa fa-print"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                </form>
            </div> <!-- .card end -->
        </div>
        <div class="col-12 row">
            <div class="col-4"></div>
            <div class="col-4 "  style="display:flex; justify-content:center; align-items:center;">
                <button type="button" class="btn btn-secondary lift btn_modal btn-space"  onclick="window.location.href='{{ route('invoice.index') }}'">
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
