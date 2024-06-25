@extends('layouts.masterLayout')
<style>
    input[type=text],
    select {
        width: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
</style>
@section('pretitle')
    <div class="container">
        <div class="row align-items-center">
            <div class="col">
                <small class="text-muted">Welcome to Create Guest.</small>
                <h1 class="h4 mt-1">Create Guest (เพิ่มลูกค้า)</h1>
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
                        <div class="col-lg-11 col-md-11 col-sm-12">
                            <h1 class="h4 mt-1">GUEST</h1>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-12">
                            <input style=" float:right;" type="text" id="Profile_ID" name="Profile_ID" maxlength="70" required value="{{$N_Profile}}" disabled>
                        </div>
                    </div>
                    <form action="{{route('saveguest')}}" method="POST">
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-12">
                                <label for="Preface" style="padding: 5px;">คำนำหน้า / Title</label><br>
                                <select name="Preface" id="PrefaceSelect" class="form-select">
                                    <option value=""></option>
                                    @foreach($prefix as $item)
                                    <option value="{{ $item->id }}">{{ $item->name_th }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-5 col-md-4 col-sm-12">
                                <label for="first_name">ชื่อจริง / First Name</label><br>
                                <input type="text" placeholder="First Name" id="first_name" name="first_name" maxlength="70" required>
                            </div>
                            <div class="col-lg-5 col-md-4 col-sm-12"><label for="last_name">นามสกุล / Last Name</label><br>
                                <input type="text" placeholder="Last Name" id="last_name" name="last_name" maxlength="70" required>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
