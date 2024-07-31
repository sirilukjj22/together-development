@extends('layouts.masterLayout')
<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to Send Email.</small>
                <h1 class="h4 mt-1">Send Email</h1>
            </div>
        </div>
    </div>
@endsection
<style>
</style>
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
    <form action="{{url('/Quotation/send/detail/email/'.$quotation->id)}}" method="POST">
        @csrf
        <div class="row clearfix">
            <div class="col-sm-12 col-12">
                <div class="card p-4 mb-4">
                    <div class="row mt-2">
                        <div class="col-lg-1 col-md-1 col-sm-12 flex justify-end" >
                            <label for="ถึง">ถึง : </label>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-12">
                            <input type="text" class="form-control" value="{{$emailCom}}"disabled>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-1 col-md-1 col-sm-12 flex justify-end">
                            <label for="ถึง">เรื่อง : </label>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-12">
                            <input type="text" class="form-control" value="เอกสารเสนอราคา เลขที่: {{$Quotation_ID}} -(คุณ{{$name}})" disabled>
                            <input type="hidden" name="tital" value="เอกสารเสนอราคา เลขที่: {{$Quotation_ID}} -(คุณ{{$name}})">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-1 col-md-1 col-sm-12 flex justify-end">
                            <label for="ถึง">เนื้อหา : </label>
                        </div>
                        <div class="col-lg-10 col-md-10 col-sm-12">
                            <textarea id="summernote" name="detail">
                                Dear คุณ{{$name}} <br>
                                Company : {{$comtypefullname}} <br>
                                Warmest Greeting from Together Resort Kaengkrachan. <br><br>
                                Please kindly see attached file Proposal letter at Together Resort Kaengkrachan on on {{$checkin}}  {{$checkout}}  {{$day}} {{$night}} .<br><br>
                                Should there be any further or assisance you may need please don't be hesitate to contact me any time. <br><br>
                                Best regards,<br>
                                อัครพล มโนโชคกวินสกุล (Tel : 081-410-8888) <br>
                                Adminstrator<br><br>
                                < Together Resort Kaengkrachan ><br>
                                168 Moo 2 Kaengkrachan Phetchaburi 76170<br>
                                Tel : 032-708-888, 098-393-944-4 Fax :<br>
                                Email : reservation@together-resort.com <br>
                                Website : www.together-resort.com<br>
                            </textarea>
                            <script>
                            $('#summernote').summernote({
                                height: 400
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
                            ใบเสนอราคา Proposal เลขที่ {{$Quotation_ID}} <a href="{{ url('/Quotation/Quotation/cover/document/PDF/'.$quotation->id) }}" target="_blank" >[เอกสาร]</a>
                            <input type="file" cols="30" rows="5" name="file" class="form-control">
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
                        <div class="col-4 "  style="display:flex; justify-content:center; align-items:center;">
                            <button type="submit" class="btn btn-color-green lift btn_modal" >Send</button>
                        </div>
                        <div class="col-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<form id="form-id3">
    @csrf
    <input type="hidden" id="deleteID" name="deleteID" value="">
</form>

@include('script.script')

@endsection


