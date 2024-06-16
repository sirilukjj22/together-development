<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <title>PDF</title>

</head>
<style>
     @font-face {

font-family: 'THSarabunNew';

font-style: normal;

font-weight: normal;

src: url("{{ public_path('fonts/THSarabunNew.ttf') }}") format('truetype');

}



@font-face {

font-family: 'THSarabunNew';

font-style: normal;

font-weight: bold;

src: url("{{ public_path('fonts/THSarabunNew Bold.ttf') }}") format('truetype');

}



@font-face {

font-family: 'THSarabunNew';

font-style: italic;

font-weight: normal;

src: url("{{ public_path('fonts/THSarabunNew Italic.ttf') }}") format('truetype');

}



@font-face {

font-family: 'THSarabunNew';

font-style: italic;

font-weight: bold;

src: url("{{ public_path('fonts/THSarabunNew BoldItalic.ttf') }}") format('truetype');

}

body {

position: relative;

margin: 0 auto;

color: #000;

background: #FFFFFF;

font-size: 16px;

font-family: "THSarabunNew";

}
#logo {

float: left;

/* margin-top: 8px; */

}



#logo img {

height: 60px;

}
.txt-head {

float: left;

/* margin-top: 8px; */

}
.com {
    display: inline-block;  /* ทำให้ border-bottom มีความยาวเท่ากับข้อความ */
    border-bottom: 2px solid #2D7F7B;  /* กำหนดเส้นใต้ */
    padding-bottom: 5px;
    }
.wrapper-page {
    page-break-after: always;
    }
    @page {
            header: page-header;
        }
</style>
<body>
    {{-- <span  style="float: right">1/3 page</span> --}}
    <div id="logo">
        <img src="logo_crop.png">
    </div>
    <div class="txt-head">
        <div class="add-text" style="line-height:10px;">
           <b style="font-size:20px;">Together Resort Limited Partnership</b>

            <br> <b> 168 Moo 2 Kaengkrachan Phetchaburi 76170</b>

            <br> <b>Tel : 032-708-888, 098-393-944-4 Fax :</b></br>

            <b> Email : reservation@together-resort.com Website : www.together-resort.com</b>
        </div>
    </div>
    <main>
        <br><br><br><br>
        <b>Subject : </b>ขอเสนอราคาค่าที่พัก อาหาร สัมมนา และ กิจกรรม
        <span  style="float: right"> {{ $date->format('d/m/Y H:i:s') }}</span><b style="float: right">Date :</b>
    </main>
    <br>
    <div style="border: 2px solid #2D7F7B"></div>
    <br>
    <b class="com" style="margin-left: 20px; font-size:18px">Company Information</b>
    <table>
        <tr>
            <td ><b style="margin-left: 30px;">Company Name :</b></td>
            <td>{{$comtypefullname}}</td>
            <td> <b class="com" style=" font-size:18px">Contact Information</b></td>
        </tr>
        <tr>
            <td><b style="margin-left: 30px;">Company Address :</b></td>
            <td>{{$Company_ID->Address}} {{'ตำบล' . $TambonID->name_th}} {{'อำเภอ' .$amphuresID->name_th}} {{'จังหวัด' .$provinceNames->name_th}} {{$TambonID->Zip_Code}}</td>
            <td><b style="margin-left: 30px;">Contact Name :</b></td>
            <td>คุณ{{$Contact_name->First_name}} {{$Contact_name->Last_name}}</td>
        </tr>
        <tr>
            <td><b style="margin-left: 30px;">Company Email :</b></td>
            <td>{{$Company_ID->Company_Email}}</td>
            <td><b style="margin-left: 30px;">Contact Email :</b></td>
            <td>{{$Contact_name->Email}}</td>
        </tr>
        <tr>
            <td><b style="margin-left: 30px;">Company Number :</b></td>
            <td>{{ substr($company_phone->Phone_number, 0, 3) }}-{{ substr($company_phone->Phone_number, 3, 3) }}-{{ substr($company_phone->Phone_number, 6) }}</td>
            <td><b style="margin-left: 30px;">Contact Number :</b></td>
            <td>{{ substr($Contact_phone->Phone_number, 0, 3) }}-{{ substr($Contact_phone->Phone_number, 3, 3) }}-{{ substr($Contact_phone->Phone_number, 6) }}</td>
        </tr>
        <tr>
            <td><b style="margin-left: 30px;">Company Fax :</b></td>
            <td>{{$company_fax->Fax_number}}</td>
        </tr>
    </table>

    <div style="line-height:17px;margin-top: 10px;">
        โรงแรม ทูเก็ตเตอร์ รีสอร์ท แก่งกระจาน ขอแสดงความขอบคุณที่ท่านเลือก โรงแรม ทูเก็ตเตอร์ รีสอร์ท แก่งกระจาน<br>
    ให้ได้รับใช้ท่านในการสำรองห้องพักและการจัดงาน ทางโรงแรมขอเสนอราคาพิเศษ ให้กับหน่วยงานของท่าน ดังนี้<br>
    </div>
    รายละเอียดการจัดงาน
    <table>
        <tr>
            <td ><span style="margin-left: 30px;">วันที่</span></td>
            <td>{{$Quotation->checkin}} - {{$Quotation->checkout}} ( {{$Quotation->day}} วัน {{$Quotation->night}} คืน)</td>
        </tr>
        <tr>
            <td><span style="margin-left: 30px;">สถานที่</span></td>
            <td>โรงแรม ทูเก็ตเตอร์ รีสอร์ท แก่งกระจาน</td>
        </tr>
        <tr>
            <td><span style="margin-left: 30px;">รูปแบบการจัดงาน</span></td>
            <td>{{$eventformat->name_th}}</td>
        </tr>
        <tr>
            <td><span style="margin-left: 30px;">จำนวน</span></td>
            <td>{{ $Quotation->adult + $Quotation->children }} ท่าน</td>
        </tr>
        <tr>
            <td><b style="margin-left: 30px;">Remark :</b></td>
            <td>เอกสารฉบับนี้ เป็นเพียงการเสนอราคาเท่านั้นยังมิได้ทำการจองแต่อย่างใดทั้งสิ้น</td>
        </tr>
    </table>
    <div style="border: 2px solid #2D7F7B;margin-top: 10px;"></div>
    การจองห้องพัก
    <div style="margin-left: 60px;line-height:5px;">
        {!! $Reservation_show->name_th !!}
    </div>
    เงื่อนไขการจ่ายเงิน
    <div style="margin-left: 60px;line-height:5px;">
        {!! $Paymentterms->name_th !!}
    </div>
    หมายเหตุ
    <div style="margin-left: 60px;line-height:5px;">
        {!! $note->name_th !!}
    </div>
    {{-- * --}}
    {{-- <span style="float: right">2/3 page</span> --}}
    <div id="logo">

        <img src="logo_crop.png">

    </div>

    <div class="txt-head">
        <div class="add-text" style="line-height:10px;">
            <b style="font-size:20px;">Together Resort Limited Partnership</b>

            <br> <b> 168 Moo 2 Kaengkrachan Phetchaburi 76170</b>

            <br> <b>Tel : 032-708-888, 098-393-944-4 Fax :</b></br>

            <b> Email : reservation@together-resort.com Website : www.together-resort.com</b>
        </div>
    </div>
    <main>
        <br><br><br><br>
        <b>Subject : </b>ขอเสนอราคาค่าที่พัก อาหาร สัมมนา และ กิจกรรม
        <span  style="float: right"> {{ $date->format('d/m/Y H:i:s') }}</span><b style="float: right">Date :</b>
    </main>
    <br>
    <div style="border: 2px solid #2D7F7B"></div>
    <br>
    <div>
        การยกเลิกและการเปลี่ยนแปลงการจอง
        <div style="margin-left: 60px;line-height:5px;">
            {!! $Cancellations->name_th !!}
        </div>
        อภินันทนาการทางรีสอร์ท
        <div style="margin-left: 60px;line-height:5px;">
            {!! $Complimentary->name_th !!}
        </div>
        ทางรีสอร์ทขอสงวนสิทธิ์แก่ผู้ใช้บริการดังนี้
        <div style="margin-left: 60px;line-height:5px;">
            {!! $All_rights_reserved->name_th !!}
        </div>
    </div>

</body>
</html>
