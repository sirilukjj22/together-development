@extends('layouts.masterLayout')
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <style>
        .btn-space {
        margin-right: 10px; /* ปรับขนาดช่องว่างตามต้องการ */
    }
    </style>
@section('content')

    <div id="content-index" class="body-header border-bottom d-flex py-3">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col sms-header">
                    <div class="span3">Create & Send Email</div>
                </div>
                <div class="col-auto">

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
                            <form action="{{url('/Document/deposit/send/detail/email/'.$deposit->id)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row mt-2">
                                    <div class="col-lg-1 col-md-1 col-sm-12 flex justify-end" >
                                        <label for="ถึง">ถึง : </label>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-12">
                                        <input type="text" class="form-control" value="{{$emailCom}}"disabled>
                                        <input type="hidden" name="emailCom" class="form-control" value="{{$emailCon}}">
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-1 col-md-1 col-sm-12 flex justify-end">
                                        <label for="ถึง">เรื่อง : </label>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-12">
                                        <input type="text" class="form-control" value="Invoice / Deposit เลขที่: {{$Deposit_ID}} -(คุณ{{$name}})" disabled>
                                        <input type="hidden" name="tital" value="Invoice / Deposit เลขที่: {{$Deposit_ID}} -(คุณ{{$name}})">
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-1 col-md-1 col-sm-12 flex justify-end">
                                        <label for="ถึง">เนื้อหา : </label>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-12">
                                        <input type="hidden" id="DATATYPE" value="{{$type_Proposal}}">
                                        <textarea id="summernote" name="detail">
                                            Dear คุณ {{$nameuser}} <br>
                                            {{$comtypefullname}} <br>
                                            Warmest Greeting from Together Resort Kaengkrachan. <br><br>
                                            Please kindly see attached file Proposal letter at Together Resort Kaengkrachan  on {{$checkin}}  {{$checkout}}  {{$day}} {{$night}} .<br><br>
                                            Should there be any further or assisance you may need please don't be hesitate to contact me any time. <br><br>
                                            Best regards,<br>
                                            {{$nameuser}} (Tel : {{$teluser}}) <br>
                                            Sale Executive<br><br>
                                            < Together Resort Kaengkrachan ><br>
                                            168 Moo 2 Kaengkrachan Phetchaburi 76170<br>
                                            Tel : 032-708-888, 098-393-9444<br>
                                            Email : reservation@together-resort.com <br>
                                            Website : www.together-resort.com<br>
                                            @foreach ($promotions as $promotion)
                                                Video Presentation / Promotion <br>
                                                {!! $promotion !!} <br>
                                            @endforeach
                                        </textarea>
                                        <img src="{{ asset('assets/images/tgt-01.jpg') }}" style="width: 400px"/>
                                        <script>
                                        $.getScript('https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js', function ()
                                            {
                                                $('#summernote').summernote({
                                                    height: 400
                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-1 col-md-1 col-sm-12">
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-12">
                                        <label for="ถึง">Notes or Special Comment : </label>
                                        <textarea type="text" cols="30" rows="5" name="Comment" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-1 col-md-1 col-sm-12 flex justify-end">
                                        <label for="ถึง">สิ่งที่แนบมาด้วย: </label>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-12">
                                        Invoice / Deposit เลขที่ {{$Deposit_ID}} <a href="{{ url('/Document/deposit/cover/document/PDF/'.$deposit->id) }}" target="_blank" ><span style="color: black">[เอกสาร]</span></a>
                                        <input type="file" name="files[]" class="form-control" multiple>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-1 col-md-1 col-sm-12 flex justify-end" >
                                        <label for="ถึง">จาก : </label>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-12">
                                        <input type="text" class="form-control" name="email" value="reservation@together-resort.com" disabled>
                                        <input type="hidden" name="email" value="reservation@together-resort.com">
                                    </div>
                                </div>
                                <div class=" row mt-5">
                                    <div class="col-4"></div>
                                    <div class="col-4 "style="display:flex; justify-content:center; align-items:center;">
                                        <button type="button" class="btn btn-secondary lift btn_modal btn-space" onclick="window.location.href='{{ route('Deposit.index') }}'">
                                            Cancel
                                        </button>
                                        <button type="submit" class="btn btn-color-green lift btn_modal" >Send</button>
                                    </div>
                                    <div class="col-4"></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
