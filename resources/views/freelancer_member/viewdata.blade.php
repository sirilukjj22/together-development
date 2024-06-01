@extends('layouts.test')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<style>
    .viewcompany{
        display: block;
        margin: auto;
        width: 70%;
        border-style: solid;
        border-radius: 8px;
        border-width: 1px;
        border-color: #9a9a9a;
        background-color: white;
        padding: 30px;
        margin-top: 40px;
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
        <div class="row">
            <div class="col-4">
                <h1>Data Company & Agent</h1>
            </div>
            <div class="col-6"></div>
            <div class="col-2" >
                @if ($viewComMassage->status == 2)
                    {{$viewComMassage->Operated_by}}
                @else
                    <label style="color: #8B8989;text-align: right; display: block;">สถานะการตรวจสอบ</label>
                @endif
            </div>
        </div>
    </div>
    <div class="viewcompany">
        <div class="col-12 mt-2">
            <div class="row">
            <div class="col-6">
                <label>ผู้ติดต่อ : {{ $viewComMassage->Contact_Name }}</label>
            </div>
            <div class="col-6">
                <label>เลขประจำตัวผู้เสียภาษี : {{ $viewComMassage->Taxpayer_Identification }}</label>
            </div>
        </div>
        <div class=" row ">
            <div class="col-6">
                <label>ชื่อบริษัท : {{ $viewComMassage->Company_Name }}</label>
            </div>
            <div class="col-6">
                <label>ชื่อสาขาบริษัท : {{ $viewComMassage->Branch }}</label>
            </div>
        </div>
        <div class=" row ">
            <div class="col-12">
                <label>ที่อยู่ : {{ $viewComMassage->Address }}</label>
            </div>
        </div>
        <div class=" row ">
            <div class="col-6">
                <label>จังหวัด : </label> <label id="provinceLabel"></label> <label>อำเภอ : </label><label id="amphuresLabel"></label>
                <label>ตำบล : </label> <label id="TambonLabel" ></label> <label>รหัสไปรษณีย์ : </label> <label id="zip_codeLabel" ></label>
            </div>
        </div>
        <div class=" row ">
                <select name="province" id = "province" class="select2" onchange="select_province()"style="display: none;">
                    @foreach($provinceNames as $item)
                        <option value="{{ $item->id }}" {{$viewComMassage->City == $item->id ? 'selected' : ''}}  >{{ $item->name_th }}</option>
                    @endforeach
                </select>
                <div class="col-3">
                    <select name="amphures" id = "amphures" class="select2"  onchange="select_amphures()"style="display: none;">
                        @foreach($amphures as $item)
                        <option value="{{ $item->id }}" {{ $viewComMassage->Amphures == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                        @endforeach
                    </select>
                </div>
                <select name="Tambon" id ="Tambon" class="select2" onchange="select_Tambon()" style="display: none;">
                    @foreach($Tambon as $item)
                        <option value="{{ $item->id }}" {{ $viewComMassage->Tambon == $item->id ? 'selected' : '' }}>{{ $item->name_th }}</option>
                    @endforeach
                </select>
                <select name="zip_code" id ="zip_code" class="select2" style="display: none;">
                    @foreach($Zip_code as $item)
                        <option value="{{ $item->id }}" {{ $viewComMassage->Zip_Code == $item->zip_code ? 'selected' : '' }}>{{ $item->zip_code }}</option>
                    @endforeach
                </select>
        </div>
        <div class=" row ">
            <div class="col-6">
                <label>โทร : {{ $phoneM['Phone_number'] }}</label>
            </div>
            <div class="col-6">
                <label>โทรสาร : {{ $faxM['Fax_number'] }}</label>
            </div>
        </div>
        <div class=" row ">
            <div class="col-6">
                <label>อีเมล์ : {{ $viewComMassage->Company_Email }}</label>
            </div>
            <div class="col-6">
                <label>เว็บไซต์ : {{ $viewComMassage->Company_Website }}</label>
            </div>
        </div>
        <div class=" row ">
            <div class="col-6">
                <label>จำนวนลูกค้า (คน) : {{ $viewComMassage->Pax }}</label>
            </div>
            <div class="col-6">
                <label>วันที่เข้าพัก : {{ $Check_In_Date }}</label><br><label>วันที่ออกที่พัก : {{ $Check_Out_Date}}</label>
            </div>

        </div>
        <div class=" row ">
            <div class="col-6">
                <label>ผู้ดำเนินการ: {{$member_name}}</label>
            </div>
            <div class="col-6">
                <label>วันที่ดำเนินการ : {{ $viewComMassage->created_at->format('d/m/Y') }}</label>
            </div>
        </div>
    </div>
</div>
<div class="viewcompany">
    <table class="table">
        <thead>
          <tr>
            <th style="width: 10%">ลำดับ</th>
            <th style="width: 10%">รหัส</th>
            <th style="width: 50%">รายการ</th>
            <th style="width: 10%">จำนวนผู้เข้าพัก</th>
            <th style="width: 10%">จำนวน</th>
            <th style="width: 10%">หน่วย</th>
          </tr>
        </thead>
        <tbody>
            @php
                $Roomtotal = 0;
                $guesttatal = 0;
                $Mealtatal = 0;
                $Quantityroom =0;
                $paxroom =0;
                $Meetingroom = 0;
                $Entertainmentroom = 0;
            @endphp
            @foreach ($viewComcontent as $key => $item)
            <tr>
                <td style="width: 10%">{{$key +1}}</td>
                <td style="width: 10%">{{ $item->Product_ID}}</td>
                <td style="width: 50%">{{ @$item->product->name_th}}</td>
                <td style="width: 10%">{{ @$item->product->pax}}</td>
                <td style="width: 10%">{{ $item->Quantity}}</td>
                <td style="width: 10%">{{ @$item->product->productunit->name_th}}</td>


                @if (@$item->product->Category =="Room_Type")
                   @php
                       $Roomtotal += 1;
                       $Quantityroom += $item->Quantity;
                       $paxroom += @$item->product->pax * $item->Quantity;

                   @endphp

                @elseif (@$item->product->Category =="Meals")
                    @php
                       $Mealtatal += $item->Quantity;
                   @endphp
                @elseif(@$item->product->Category =="Entertainment")
                    @php
                        $Entertainmentroom += $item->Quantity;
                    @endphp
                @elseif(@$item->product->Category =="Banquet")
                    @php
                        $Meetingroom += $item->Quantity;
                    @endphp
                @endif
            </tr>

            @endforeach
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align:right;">Total Guest</td>
                    <td colspan="6" id="total-guest">{{$paxroom}}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align:right;">Total Room</td>
                    <td colspan="6" id="total-room">{{$Quantityroom}}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align:right;">Total Meal</td>
                    <td colspan="6" id="total-meal">{{$Mealtatal}}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align:right;">Total Meeting</td>
                    <td colspan="6" id="total-guest">{{$Meetingroom}}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align:right;">Total Entertainment</td>
                    <td colspan="6" id="total-guest">{{$Entertainmentroom}}</td>
                </tr>
            </tfoot>
        </tbody>
    </table>
    <div class="col-12 d-flex justify-content-center">
        <div class="button-guest-end">
            <button type="button" class="btn" onclick="window.location.href='{{ route('freelancer_member.view', ['id' => $Freelancer_Main->id]) }}'">{{ __('ย้อนกลับ') }}</button>
        </div>
    </div>

</div>
<script>
function select_province(){
    var provinceID = $('#province').val();
    jQuery.ajax({
        type:   "GET",
        url:    "{!! url('/guest/amphures/"+provinceID+"') !!}",
        datatype:   "JSON",
        async:  false,
        success: function(result) {
            jQuery('#amphures').children().remove().end();
            //ตัวแปร
            $('#amphures').append(new Option('', ''));
            jQuery.each(result.data, function(key, value) {
                var amphures = new Option(value.name_th,value.id);
                $('#amphures').append(amphures);
            });
        },
    })
}

function select_amphures(){
    var amphuresID  = $('#amphures').val();
    $.ajax({
        type:   "GET",
        url:    "{!! url('/guest/Tambon/"+amphuresID+"') !!}",
        datatype:   "JSON",
        async:  false,
        success: function(result) {
            jQuery('#Tambon').children().remove().end();
            $('#Tambon').append(new Option('', ''));
            jQuery.each(result.data, function(key, value) {
                var Tambon  = new Option(value.name_th,value.id);
                $('#Tambon ').append(Tambon );
            });
        },
    })
}
function select_Tambon(){
    var Tambon  = $('#Tambon').val();
    $.ajax({
        type:   "GET",
        url:    "{!! url('/guest/districts/"+Tambon+"') !!}",
        datatype:   "JSON",
        async:  false,
        success: function(result) {
            jQuery('#zip_code').children().remove().end();
            $('#zip_code').append(new Option('', ''));
            jQuery.each(result.data, function(key, value) {
                var zip_code  = new Option(value.zip_code,value.zip_code);
                $('#zip_code ').append(zip_code );
            });
        },
    })
}
document.addEventListener('DOMContentLoaded', function () {
    const selectElement = document.getElementById('province');
    const labelElement = document.getElementById('provinceLabel');

    function updateLabel() {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        labelElement.textContent = selectedOption.text;
    }

    // เรียกใช้ฟังก์ชันนี้เมื่อมีการเปลี่ยนแปลงการเลือก
    selectElement.addEventListener('change', updateLabel);

    // เรียกใช้ฟังก์ชันนี้เมื่อเริ่มต้นเพื่อแสดงค่าที่เลือกไว้ก่อนหน้า
    updateLabel();
});
document.addEventListener('DOMContentLoaded', function () {
    const selectElement = document.getElementById('amphures');
    const labelElement = document.getElementById('amphuresLabel');

    function updateLabel() {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        labelElement.textContent = selectedOption.text;
    }

    // เรียกใช้ฟังก์ชันนี้เมื่อมีการเปลี่ยนแปลงการเลือก
    selectElement.addEventListener('change', updateLabel);

    // เรียกใช้ฟังก์ชันนี้เมื่อเริ่มต้นเพื่อแสดงค่าที่เลือกไว้ก่อนหน้า
    updateLabel();
});
document.addEventListener('DOMContentLoaded', function () {
    const selectElement = document.getElementById('Tambon');
    const labelElement = document.getElementById('TambonLabel');

    function updateLabel() {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        labelElement.textContent = selectedOption.text;
    }

    // เรียกใช้ฟังก์ชันนี้เมื่อมีการเปลี่ยนแปลงการเลือก
    selectElement.addEventListener('change', updateLabel);

    // เรียกใช้ฟังก์ชันนี้เมื่อเริ่มต้นเพื่อแสดงค่าที่เลือกไว้ก่อนหน้า
    updateLabel();
});
document.addEventListener('DOMContentLoaded', function () {
    const selectElement = document.getElementById('zip_code');
    const labelElement = document.getElementById('zip_codeLabel');

    function updateLabel() {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        labelElement.textContent = selectedOption.text;
    }

    // เรียกใช้ฟังก์ชันนี้เมื่อมีการเปลี่ยนแปลงการเลือก
    selectElement.addEventListener('change', updateLabel);

    // เรียกใช้ฟังก์ชันนี้เมื่อเริ่มต้นเพื่อแสดงค่าที่เลือกไว้ก่อนหน้า
    updateLabel();
});

</script>
@endsection
