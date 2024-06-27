@extends('layouts.masterLayout')
<style>
.image-container {
    display: flex;
    flex-direction: row;
    align-items: center;
    text-align: left;
}
.image-container img.logo {
    width: 15%; /* กำหนดขนาดคงที่ */
    height: auto;
    margin-right: 20px;
}

.image-container .info {
    margin-top: 0;
}

.image-container .info p {
    margin: 5px 0;
}

.image-container .titleh1 {
    font-size: 1.2em;
    font-weight: bold;
    margin-bottom: 10px;
}
@media (max-width: 768px) {
    .image-container {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .image-container img.logo {
        margin-bottom: 20px;
        width: 50%;
    }
}
</style>
@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to Create Company / Agent.</small>
                <h1 class="h4 mt-1">Create Company / Agent (เพิ่มบริษัทและตัวแทน)</h1>
            </div>
        </div>
    </div>
@endsection
@section('content')
<div class="container">
    <div class="container mt-3">
        <div class="row clearfix">
            <div class="col-sm-12 col-12">
                <div class="card p-4 mb-4">
                    <div class="row">
                        <div class="col-lg-8 col-md-12 col-sm-12 image-container">
                            <img src="{{ asset('assets2/images/logo_crop.png') }}" alt="Together Resort Logo" class="logo"/>
                            <div class="info">
                                <p class="titleh1">Together Resort Limited Partnership</p>
                                <p>168 Moo 2 Kaengkrachan Phetchaburi 76170</p>
                                <p>Tel : 032-708-888, 098-393-944-4 Fax :</p>
                                <p>Email : reservation@together-resort.com Website : www.together-resort.com</p>
                                <p></p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="row">
                                <p class="quotation-number">Quotation </p>
                                <p class="quotation-id ">{{$Quotation_ID}}</p>
                                <input type="hidden" id="Quotation_ID" name="Quotation_ID" value="{{$Quotation_ID}}">
                                <div id="reportrange1" style="background: #fff; cursor: pointer; padding: 5px 10px; width: 100%;" >
                                    <div class="col-12 ">
                                        <div class="row">
                                            <div class="col-6"style="display:flex; justify-content:right; align-items:center;">
                                                <span>Issue Date:</span>
                                            </div>
                                            <div class="col-6">
                                                <input type="text" id="datestart" name="IssueDate" style="text-align: left;"readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 ">
                                        <div class="row">
                                            <div class="col-6"style="display:flex; justify-content:right; align-items:center;">
                                                <span>Expiration Date:</span>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="text" id="dateex" name="Expiration" style="text-align: left;"readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
