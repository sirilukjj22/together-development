@extends('layouts.test')

@section('content')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>
        input[type=text], select {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        }
        .button-guest{
        background-color: #2D7F7B;
        color: whitesmoke;
        border-color: #9a9a9a;
        border-style: solid;
        width: 30%;
        border-width: 1px;
        border-radius: 8px;
        float: right;
        margin-Top: 10px;
        margin-Left: 100px;
        text-align: center;

        }
        .button-guest-end{
            background-color:#ff0000;
            color: whitesmoke;
            border-color: #9a9a9a;
            border-style: solid;
            width: 30%;
            float: left;
            border-width: 1px;
            border-radius: 8px;
            margin-Top: 10px;
            text-align: center;

        }
    </style>
    <div class="Usertable">
        <div class="usertopic">
            <h1>Master Booking Channal </h1>
        </div>
        <br>
        <form action="{{url('/Mbooking/master_booking/Mbooking_update/'.$Mbooking_channal->id)}}" method="POST">
        {!! csrf_field() !!}
            <div>
                <div class="col-12 row" >

                    <div class="col-2">
                        <label for="Sort">Sort</label>
                        <input type="text" id="sort" name="sort"value="{{$Mbooking_channal->sort}}">
                    </div>
                    <div class="col-2">
                    <label for="code">Code</label>
                    <input type="text" id="code" name="code" value="{{$Mbooking_channal->code}}"></div>
                    <div class="col-4" ><label for="Name_th">Name_th</label><br>
                    <input type="text" id="name_th" name="name_th"maxlength="70"  value="{{$Mbooking_channal->name_th}}">
                </div>
                    <div class="col-4" ><label for="name_en" >Name_en</label><br>
                    <input type="text" id="name_en" name="name_en"maxlength="70"  value="{{$Mbooking_channal->name_en}}">
                </div>
                </div>
                <div class="col-12 row mt-2">
                    <div class="col-2"></div>
                    <div class="col-4">
                        <div class="button-guest">
                            <button type="submit" class="btn">ตกลง</button>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="button-guest-end">
                            <button type="button" class="btn" onclick="window.location.href='{{ route('Mbooking.index') }}'" >{{ __('ย้อนกลับ') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
