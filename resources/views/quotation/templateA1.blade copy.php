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

height: 90px;

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
div.PROPOSAL {
    position: absolute;
    top: 0px;
    right: 6;
    width: 180px;
    height: 40px;
    border: 3px solid #2D7F7B;
    border-radius: 10px;
    background-color: #109699;
}
div.PROPOSALfirst {
    position: absolute;
    top: 50px;
    right: 6;
    width: 180px;
    height: 60px;
    border: 2px solid #2D7F7B;
    border-radius: 10px;
}
div.frame{
    position: absolute;
    top: 20px;
    right: -4;
    width: 200px;
    height: 145px;
    border: 2px solid #2D7F7B;
    border-radius: 10px;
}
#customers {
  border-collapse: collapse;
  width: 100%;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  background-color: #109699;
  color: white;
}
.centered-content4 {

    border: 2px solid #000000; /* กำหนดกรอบสี่เหลี่ยม */
    padding: 2px; /* เพิ่ม padding ภายในกรอบ */
    border-radius: 5px; /* เพิ่มมุมโค้ง (ถ้าต้องการ) */
    height: 60px;
    /* width: 150px; */
}
</style>
<body>
    {{-- <span  style="float: right">1/3 page</span> --}}
    <div id="logo">

        <img src="logo_crop.png">

    </div>

    <div class="txt-head">

        <div class="add-text" style="line-height:14px;">

           <b style="font-size:20px;">Together Resort Limited Partnership</b>

            <br> <b> 168 Moo 2 Kaengkrachan Phetchaburi 76170</b>

            <br> <b>Tel : 032-708-888, 098-393-944-4 Fax :</b></br>

            <b> Email : reservation@together-resort.com Website : www.together-resort.com</b>
        </div>
    </div>
    <main>
        <br><br><br><br>
        <b>Subject : </b>ขอเสนอราคาค่าที่พัก อาหาร สัมมนา และ กิจกรรม
        <span  style="float: right" > {{ $date->format('d/m/Y H:i:s') }}</span><b style="float: right">Date :</b>
    </main>
    <br>
    <div style="border: 2px solid #2D7F7B"></div>
    <b class="com" style="margin-left: 20px; margin-top:10px; font-size:18px">Company Information</b>
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
    <div class="wrapper-page">
    {{-- * --}}
        {{-- <span style="float: right">2/3 page</span> --}}
        <div id="logo">

            <img src="logo_crop.png">

        </div>

        <div class="txt-head">
            <div class="add-text" style="line-height:14px;">
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
        <div style=" margin-top:10px;">
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
    </div>

    <div id="logo">

        <img src="logo_crop.png">

    </div>

    <div class="txt-head">

        <div class="add-text" style="line-height:14px;">

            <b style="font-size:20px;">Together Resort Limited Partnership</b>

            <br> <b> 168 Moo 2 Kaengkrachan Phetchaburi 76170</b>

            <br> <b>Tel : 032-708-888, 098-393-944-4 Fax :</b></br>

            <b> Email : reservation@together-resort.com Website : www.together-resort.com</b>

        </div>

    </div>
    <br><br><br><br>


    <div class="PROPOSAL">

        <div  style="text-align: center">

            <b style="font-size:18px;color:#000000;font-weight: bold;">PROPOSAL</b>

        </div>

    </div>


    <div class="PROPOSALfirst" style="line-height:10px;">

        <div style="padding: 4%">

            <b >Proposal ID : </b><span style="margin-left: 20px;">{{ $Quotation->Quotation_ID }}</span><br>

            <b >Issue Date : </b><span style="margin-left: 25px">{{ $Quotation->issue_date }}</span><br>

            <b>Expiration Date : </b><span>{{ $Quotation->Expirationdate }}</span>

        </div>

    </div>
    <b class="com" style="font-size:18px">Company Information</b>
<div style=" border-right-style: solid  ; border-right-width: 2px;border-right-color:#2D7F7B; width:55%">
    <table style="line-height:12px;" >
        <tr>
            <td ><b style="margin-left: 10px; width:30%">Company Name :</b></td>
            <td>{{$comtypefullname}}</td>
        </tr>
        <tr>
            <td><b style="margin-left: 10px;">Company Address :</b></td>
            <td>{{$Company_ID->Address}} {{'ตำบล' . $TambonID->name_th}} {{'อำเภอ' .$amphuresID->name_th}} </td>
        </tr>
        <tr>
            <td></td>
            <td>{{'จังหวัด' .$provinceNames->name_th}} {{$TambonID->Zip_Code}}</td>
        </tr>
        <tr>
            <td><b style="margin-left: 10px;">Company Number :</b></td>
            <td>{{ substr($company_phone->Phone_number, 0, 3) }}-{{ substr($company_phone->Phone_number, 3, 3) }}-{{ substr($company_phone->Phone_number, 6) }}
                <b style="margin-left: 10px;">Company Fax : </b><span>{{$company_fax->Fax_number}}</span>
            </td>
            <td>

            </td>
        </tr>
        <tr>
            <td><b style="margin-left: 10px;">Company Email :</b></td>
            <td>{{$Company_ID->Company_Email}}</td>
        </tr>
        <tr>
            <td><b style="margin-left: 10px;">Taxpayer Identification :</b></td>
            <td>{{$Company_ID->Taxpayer_Identification}}</td>
        </tr>

    </table>
        <b class="com" style=" font-size:18px">Personal Information</b><br>
        <b style="margin-left: 10px;">Contact Name :</b><span >คุณ{{$Contact_name->First_name}} {{$Contact_name->Last_name}}</span><b style="margin-left: 10px;">Contact Number :</b><span>{{ substr($Contact_phone->Phone_number, 0, 3) }}-{{ substr($Contact_phone->Phone_number, 3, 3) }}-{{ substr($Contact_phone->Phone_number, 6) }}</span>
        <br><b style="margin-left: 10px;">Contact Email :</b><span>{{$Contact_name->Email}}</span>
    <div style="margin-top: 5px"></div>
</div>
    <span style="position: absolute;top: 190px; right: 30;width: 250px;height: 145px;line-height:18px;">
        <b style="margin-left: 10px">Check In : </b><span style="margin-left: 55px;">{{$Quotation->checkin}}</span><br>
        <b style="margin-left: 10px">Check Out : </b><span style="margin-left: 45px;">{{$Quotation->checkout}}</span><br>
        <b style="margin-left: 10px">Length of Stay :</b><span style="margin-left: 28px;">{{$Quotation->day}} วัน {{$Quotation->night}} คืน</span><br>
        <b style="margin-left: 10px">Number of Guests :</b><span style="margin-left: 10px;">{{$Quotation->adult}} Adult , {{$Quotation->adult}} Children</span><br>
    </span>
    <div style="border: 1px solid #2D7F7B"></div>
    <div  style="line-height:15px;">
        <strong>ขอเสนอราคาและเงื่อนไขสำหรับท่าน ดังนี้ <br> We are pleased to submit you the following desctibed here in as price,items and terms stated :</strong>
    </div>
    <table id="customers" class="table" style="width: 100%; margin-top:10px;" >
        <tr>
            <th >No.</th>
            <th >Description</th>
            <th >Quantity </th>
            <th >Unit </th>
            <th style="text-align:right;">Price / Unit </th>
            <th >Discount  (%)</th>
            <th style="text-align:right;">Net price/Unit</th>
            <th style="text-align:right;">Amount</th>
        </tr>
        @if (!empty($selectproduct))
            @foreach ($selectproduct as $key => $item)
                @foreach ($unit as $singleUnit)
                    @if($singleUnit->id == @$item->product->unit)
                        <tr >
                            <td style="text-align:center;">{{$key+1}}</td>
                            <td >{{@$item->product->name_th}}</td>
                            <td  style="text-align:center;">{{$item->Quantity}}</td>
                            <td  style="text-align:center;">{{ $singleUnit->name_th }}</td>
                            <td  style="text-align:right;" >{{ number_format($item->priceproduct, 2, '.', ',') }} ฿</td>
                            <td  style="text-align:center;">{{$item->discount}}</td>
                            <td style="text-align:right;">{{ number_format($item->netpriceproduct, 2, '.', ',') }} ฿</td>
                            <td style="text-align:right;" class="amount">{{ number_format($item->totalpriceproduct, 2, '.', ',') }} ฿</td>
                        </tr>
                    @endif
                @endforeach
            @endforeach
        @endif
    </table>
    <table  id="customers" class="table" style="width: 30%;float:right;" >
        <tr>
            <td style="text-align:right;width: 65%" class="text-right"><strong>Total</strong></td>
            <td style="text-align:right;"><strong id="total-amount">{{ number_format($totalAmount, 2, '.', ',') }} ฿</strong></td>
        </tr>
        <tr>
            <td style="text-align:right;" class="text-right"><strong>Discount</strong></td>
            <td style="text-align:right;"><strong id="total-discount">{{ number_format($totaldiscount, 2, '.', ',') }} ฿</strong></td>
        </tr>
        <tr>
            <td style="text-align:right;" class="text-right"><strong>Total Amount</strong></td>
            <td style="text-align:right;" ><strong id="total-Price">{{ number_format($totalPrice, 2, '.', ',') }} ฿</strong></td>
        </tr>
        <tr>
            <td style="text-align:right;" colspan="1" class="text-right"><strong>Vat 7%</strong></td>
            <td style="text-align:right;"><strong id="total-Price">{{ number_format($vat, 2, '.', ',') }} ฿</strong></td>
        </tr>
        <tr>
            <td style="text-align:right;" colspan="1" class="text-right"><strong>Grand Total</strong></td>
            <td style="text-align:right;"><strong id="total-Price">{{ number_format($total, 2, '.', ',') }} ฿</strong></td>
        </tr>
    </table>
    <div style="line-height:15px;">
        <b>Notes or Special Comment : </b><br>
        <span>{{$Quotation->comment}}</span>
    </div>
    <br><br><br>


    <div style="line-height:10px;">
    <b>จำนวนลูกค้า (Number of Guests) : </b><span style="margin-left: 15px">{{$totalguest}} คน</span><br>
    <b>ค่าเฉลี่ยต่อท่าน (Average per person) : </b><span >{{ number_format($totalaverage, 2, '.', ',') }} ฿</span>
    </div>

    <div style="border: 1px solid #2D7F7B;margin-top:30px;"></div>
    <table style="width: 100%">
        <tr>

            <th >สแกนเพื่อเปิดด้วยเว็บไซต์</th>
            <th >ผู้ออกเอกสาร (ผู้ขาย)</th>
            <th >ผู้อนุมัติเอกสาร (ผู้ขาย) </th>
            <th >ตราประทับ (ผู้ขาย) </th>
            <th >ผู้รับเอกสาร (ลูกค้า)</th>
            <th >ตราประทับ (ลูกค้า)</th>
        </tr>
        <tr>
            <td style="text-align: center"> <img src="data:image/png;base64,{{ base64_encode($qrCodePng) }}" width="60" height="60"/></td>
            <td style="text-align: center">
                <br>
                <span style="display: block; text-align: center;">{{@$Quotation->user->name}}</span>
                <span style="display: block; text-align: center;">{{ $Quotation->issue_date }}</span>
            </td>
            <td style="text-align: center">
                <br>
                <span style="display: block; text-align: center;">{{@$Quotation->user->name}}</span>
                <span style="display: block; text-align: center;">{{ $Quotation->issue_date }}</span>
            </td>
            <td>

            </td>
            <td>
                <br>
                <span style="display: block; text-align: center;">---------------------</span>
                <span style="display: block; text-align: center;">{{ $Company_ID->Company_Name }}</span>
            </td>
            <td>
                <div class="centered-content4 ">
                </div>

            </td>
        </tr>
    </table>
    <div class="wrapper-page">

    <div id="logo">

        <img src="logo_crop.png">

    </div>

    <div class="txt-head">

        <div class="add-text" style="line-height:14px;">

            <b style="font-size:20px;">Together Resort Limited Partnership</b>

            <br> <b> 168 Moo 2 Kaengkrachan Phetchaburi 76170</b>

            <br> <b>Tel : 032-708-888, 098-393-944-4 Fax :</b></br>

            <b> Email : reservation@together-resort.com Website : www.together-resort.com</b>

        </div>

    </div>
    </div>
</body>
</html>

