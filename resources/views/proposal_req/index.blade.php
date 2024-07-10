@extends('layouts.masterLayout')

@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to Proposal Request.</small>
                <h1 class="h4 mt-1">Proposal Request</h1>
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
        <div class="col">
            <ol class="breadcrumb d-inline-flex bg-transparent p-0 m-0">
                <li></li>
                <li></li>
                <li></li>
            </ol>
        </div>
    </div> <!-- Row end  -->

    <div class="row clearfix">
        <div class="col-sm-12 col-12">
            <div class="card p-4 mb-4">
                <form enctype="multipart/form-data" class="row g-3 basic-form" id="form-id2">
                    @csrf
                    <input type="hidden" name="category" value="prename">
                <table class="myDataTableQuotation table table-hover align-middle mb-0" style="width:100%">
                    <h5>Dummy Proposal Request</h5>
                    <thead>
                        <tr>
                            <th style="width: 5%">No</th>
                            <th>Company</th>
                            <th style="width: 5%">Count</th>
                            <th class="text-center" style="width: 10%">Order</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($proposal))
                            @foreach ($proposal as $key => $item)
                            <tr>
                                <td style="text-align: center;">{{ $key+1}}</td>
                                <td>{{ @$item->company2->Company_Name}}</td>
                                <td style="text-align: center;">{{ $item->COUNTDummyNo }}</td>
                                <td style="text-align: center;">
                                    <button type="button" class="btn btn-color-green lift btn_modal" onclick="window.location.href='{{ url('/Dummy/Proposal/Request/document/view/'.$item->Company_ID) }}'">
                                        <i class="fa fa-folder-open-o"></i> View
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                    </tbody>
                </table>
                </form>
            </div> <!-- .card end -->
        </div>
    </div>
</div>
@endsection
