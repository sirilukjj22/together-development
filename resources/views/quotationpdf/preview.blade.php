<html>
    <head>
        <style>
            /** Define the margins of your page **/
            @page {
            margin: 0.5cm 0.5cm 0.5cm 0.5cm;
            }
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
            header {
                /* position: fixed; */
                top: 0cm;
                left: 0cm;
                right: 0cm;
                height: 3cm;
                font-family: "THSarabunNew";
            }
            main{
                font-family: "THSarabunNew";
            }
            footer {
                position: fixed;
                bottom: 0cm;
                left: 0cm;
                right: 0cm;
                height: 3.5cm;
                font-family: "THSarabunNew";
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
            .com {
            display: inline-block;  /* ทำให้ border-bottom มีความยาวเท่ากับข้อความ */
            border-bottom: 2px solid #2D7F7B;  /* กำหนดเส้นใต้ */
            padding-bottom: 2px;
            }
            body {
                margin-top: 0cm;
                margin-left: 0cm;
                margin-right: 0cm;
                margin-bottom: 0cm;
                font-family: "THSarabunNew";
            }
            #logo {

            float: left;

            /* margin-top: 8px; */

            }
            .txt-head {

            float: left;

            /* margin-top: 8px; */

            }


            #logo img {

            height: 70px;

            }
            .wrapper-page {
                page-break-after: always;
            }

            .wrapper-page:last-child {
                page-break-after: avoid;
            }
        </style>
    </head>
    <body>
        <div style="page-break-after: always;">
            <div id="logo">

                <img src="logo_crop.png">

            </div>

            <div class="txt-head">

                <div class="add-text" style="line-height:12px;margin-left:10px;">

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
            <table style="line-height:12px;">
                <tr>
                    <td style="width: 30%"><b style="margin-left: 30px;">Company Name :</b></td>
                    <td>{{$comtypefullname}}</td>
                </tr>
                <tr>
                    <td><b style="margin-left: 30px;">Company Address :</b></td>
                    <td>{{$Company_ID->Address}} {{'ตำบล' . $TambonID->name_th}} {{'อำเภอ' .$amphuresID->name_th}}</td>
                </tr>
                <tr>
                    <td></td>
                <td> {{'จังหวัด' .$provinceNames->name_th}} {{$TambonID->Zip_Code}}</td>
                </tr>
                <tr>
                    <td><b style="margin-left: 30px;">Company Email :</b></td>
                    <td>{{$Company_ID->Company_Email}}</td>
                </tr>
                <tr>
                    <td><b style="margin-left: 30px;">Company Number :</b></td>
                    <td>{{ substr($company_phone->Phone_number, 0, 3) }}-{{ substr($company_phone->Phone_number, 3, 3) }}-{{ substr($company_phone->Phone_number, 6) }}</td>
                </tr>
                <tr>
                    <td><b style="margin-left: 30px;">Company Fax :</b></td>
                    <td>{{$company_fax}}</td>
                </tr>
            </table>
            <span style="position: absolute;top: 220px; right: 30;width: 280px;height: 145px;line-height:14px;">
                <b class="com" style=" font-size:18px">Contact Information</b><br>
                <b style="margin-left: 10px;">Contact Name : </b><span >คุณ{{$Contact_name->First_name}} {{$Contact_name->Last_name}}</span><br>
                <b style="margin-left: 10px;">Contact Email : </b><span >{{$Contact_name->Email}}</span><br>
                <b style="margin-left: 10px;">Contact Number : </b><span>{{ substr($Contact_phone->Phone_number, 0, 3) }}-{{ substr($Contact_phone->Phone_number, 3, 3) }}-{{ substr($Contact_phone->Phone_number, 6) }}</span><br>

            </span>
            <div style="line-height:17px;margin-top: 10px;">
                โรงแรม ทูเก็ตเตอร์ รีสอร์ท แก่งกระจาน ขอแสดงความขอบคุณที่ท่านเลือก โรงแรม ทูเก็ตเตอร์ รีสอร์ท แก่งกระจาน<br>
            ให้ได้รับใช้ท่านในการสำรองห้องพักและการจัดงาน ทางโรงแรมขอเสนอราคาพิเศษ ให้กับหน่วยงานของท่าน ดังนี้<br>
            </div>
            รายละเอียดการจัดงาน
            <table style="line-height:12px;">
                <tr>
                    <td ><span style="margin-left: 30px;">วันที่</span></td>
                    @if ($Checkin == '-')
                    <td>No Check in date</td>
                    @else
                    <td>{{$Checkin}} - {{$Checkout}} ( {{$day}} วัน {{$night}} คืน)</td>
                    @endif
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
                    <td>{{ $totalguest }} ท่าน</td>
                </tr>
                <tr>
                    <td><b style="margin-left: 30px;">Remark :</b></td>
                    <td>เอกสารฉบับนี้ เป็นเพียงการเสนอราคาเท่านั้นยังมิได้ทำการจองแต่อย่างใดทั้งสิ้น</td>
                </tr>
            </table>
            <div style="border: 2px solid #2D7F7B;margin-top: 10px;"></div>
            <b>การจองห้องพัก</b>
            <div style="margin-left: 60px;line-height:5px;">
                {!! $Reservation_show->name_th !!}
            </div>
            <b>เงื่อนไขการจ่ายเงิน</b>
            <div style="margin-left: 60px;line-height:5px;">
                {!! $Paymentterms->name_th !!}
            </div>
            <b>หมายเหตุ</b>
            <div style="margin-left: 60px;line-height:5px;">
                {!! $note->name_th !!}
            </div>
        </div>
        <div style="page-break-after: always;">
            <div id="logo">

                <img src="logo_crop.png">

            </div>

            <div class="txt-head">

                <div class="add-text" style="line-height:12px;margin-left:10px;">

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
            <div style=" margin-top:10px;">
                <b>การยกเลิกและการเปลี่ยนแปลงการจอง</b>
                <div style="margin-left: 60px;line-height:5px;">
                    {!! $Cancellations->name_th !!}
                </div>
                <b>อภินันทนาการทางรีสอร์ท</b>
                <div style="margin-left: 60px;line-height:5px;">
                    {!! $Complimentary->name_th !!}
                </div>
                <b>ทางรีสอร์ทขอสงวนสิทธิ์แก่ผู้ใช้บริการดังนี้</b>
                <div style="margin-left: 60px;line-height:5px;">
                    {!! $All_rights_reserved->name_th !!}
                </div>
            </div>
        </div>
        @php
            $num=0;
            $num1=0;
            $num2 = 0;
        @endphp
        @for ($i=1;$i<=$page_item;$i++)
            @php
                $num += 10;
                $num1 += 11;
            @endphp
            <div class="wrapper-page">
                <header>
                    <div id="logo">

                        <img src="logo_crop.png">

                    </div>

                    <div class="txt-head">

                        <div class="add-text" style="line-height:12px;margin-left:10px;">

                            <b style="font-size:20px;">Together Resort Limited Partnership</b>

                            <br> <b> 168 Moo 2 Kaengkrachan Phetchaburi 76170</b>

                            <br> <b>Tel : 032-708-888, 098-393-944-4 Fax :</b></br>

                            <b> Email : reservation@together-resort.com Website : www.together-resort.com</b>

                        </div>

                    </div>


                    <br><br><br>
                    <div class="PROPOSAL">

                        <div  style="text-align: center">

                            <b style="font-size:18px;color:#ffffff;font-weight: bold;">PROPOSAL</b>

                        </div>

                    </div>


                    <div class="PROPOSALfirst" style="line-height:10px;">

                        <div style="padding: 4%">

                            <b >Proposal ID : </b><span style="margin-left: 10px;">{{ $Quotation }}</span><br>

                            <b >Issue Date : </b><span >{{ $IssueDate }}</span><br>

                            <b>Expiration Date : </b><span>{{ $Expiration }}</span>

                        </div>

                    </div>
                </header>
                <footer>
                    <br><div style="border: 1px solid #2D7F7B;margin-top: 10px;"></div>
                    <table style="width: 100%;line-height:10px;margin-top: 10px;">
                        <tr>
                            <th >สแกนเพื่อเปิดด้วยเว็บไซต์</th>
                            <th >ผู้ออกเอกสาร </th>
                            <th >ผู้อนุมัติเอกสาร  </th>
                            <th >ตราประทับ  </th>
                            <th >ผู้รับเอกสาร (ลูกค้า)</th>
                            <th >ตราประทับ (ลูกค้า)</th>
                        </tr>
                        <tr>
                            <td style="text-align: center;width:10%">
                            <img src="data:image/png;base64, {!! $qrCodeBase64 !!} " alt="QR Code" width="60" height="60"/>
                            <td style="text-align: center;" >
                                <img src="test.png" style="width: 40%;"/>
                                <span style="display: block; text-align: center;">{{$user->name}}</span>
                                <span style="display: block; text-align: center;">{{ $IssueDate }}</span>
                            </td>
                            <td style="text-align: center;">
                                <img src="test.png" style="width: 40%;"/>
                                <span style="display: block; text-align: center;">{{$user->name}}</span>
                                <span style="display: block; text-align: center;">{{ $IssueDate }}</span>
                            </td>
                            <td  style="text-align: center;">

                            </td>
                            <td>
                                <br>
                                <span style="display: block; text-align: center;">______________________</span>
                                <span style="display: block; text-align: center;">_____/__________/_____</span>
                            </td>
                            <td>
                                <div class="">
                                </div>

                            </td>
                        </tr>
                    </table>
                </footer>
                <main>
                    <b class="com" style="font-size:18px">Company Information</b>
                    <div style=" border-right-style: solid  ; border-right-width: 2px;border-right-color:#2D7F7B; width:55%">
                        <table style="line-height:12px;" >
                            <tr>
                                <td ><b style="margin-left: 10px; width:30%">Company Name :</b></td>
                                <td>{{$comtypefullname}}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><b style="margin-left: 10px;">Company Address :</b></td>
                                <td>{{$Company_ID->Address}} {{'ตำบล' . $TambonID->name_th}}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td> {{'อำเภอ' .$amphuresID->name_th}} {{'จังหวัด' .$provinceNames->name_th}} {{$TambonID->Zip_Code}}</td>
                            </tr>
                            <tr>
                                <td><b style="margin-left: 10px;">Company Number :</b></td>
                                <td>{{ substr($company_phone->Phone_number, 0, 3) }}-{{ substr($company_phone->Phone_number, 3, 3) }}-{{ substr($company_phone->Phone_number, 6) }}
                                    <b style="margin-left: 10px;">Company Fax : </b><span>{{$company_fax}}</span>
                                </td>
                                <td>

                                </td>
                            </tr>
                            <tr>
                                <td><b style="margin-left: 10px;">Company Email :</b></td>
                                <td>{{$Company_ID->Company_Email}}</td>
                            </tr>
                            <tr>
                                <td><b style="margin-left: 10px;">Taxpayer Identification : </b></td>
                                <td>{{$Company_ID->Taxpayer_Identification}}</td>
                            </tr>
                        </table>
                        <div style="margin-top: 2px"></div>
                    </div>
                    <span style="position: absolute;top: 120px; right: 30;width: 280px;height: 145px;line-height:14px;">
                        <b class="com" style=" font-size:18px">Personal Information</b><br>
                        <b style="margin-left: 10px;">Contact Name : </b><span >คุณ{{$Contact_name->First_name}} {{$Contact_name->Last_name}}</span><br>
                        <b style="margin-left: 10px;">Contact Number : </b><span>{{ substr($Contact_phone->Phone_number, 0, 3) }}-{{ substr($Contact_phone->Phone_number, 3, 3) }}-{{ substr($Contact_phone->Phone_number, 6) }}</span><br>
                        <b style="margin-left: 10px">Check In : </b><span style="margin-left: 2px;">{{$Checkin}}</span>
                        <b style="margin-left: 10px">Check Out : </b><span style="margin-left: 5px;">{{$Checkout}}</span><br>
                        <b style="margin-left: 10px">Length of Stay :</b>
                        <span style="margin-left: 23px;">
                            @if ($day == null)
                                -
                            @else
                                {{$day}} วัน {{$night}} คืน
                            @endif
                        </span><br>
                        <b style="margin-left: 10px">Number of Guests :</b>
                        <span style="margin-left: 10px;">
                                @if ($adult == null)
                                -
                            @else
                                {{$adult}} Adult , {{$children}} Children
                            @endif
                        </span><br>
                    </span>
                    <div style="border: 1px solid #2D7F7B"></div>
                    <div  style="line-height:15px;">
                        <strong>ขอเสนอราคาและเงื่อนไขสำหรับท่าน ดังนี้ <br> We are pleased to submit you the following desctibed here in as price,items and terms stated :</strong>
                        @if ($page_item > 1)
                        <span style="font-weight: bold; float: right;color:#afafaf">Page {{ $i }}/{{$page_item}}</span>
                        @endif
                    </div>
                    <table id="customers" class="table" style="width: 100%; margin-top:10px;font-size:16px" >
                        <tr style="font-weight: bold;">
                            <th style="font-weight: bold;">NO.</th>
                            <th style="font-weight: bold;">DESCRIPTION</th>
                            <th style="font-weight: bold;">QUANTITY </th>
                            <th style="font-weight: bold;">UNIT </th>
                            <th style="text-align:center;font-weight: bold;">PRICE / UNIT </th>
                            <th style="font-weight: bold;">DISCOUNT</th>
                            <th style="text-align:center;font-weight: bold;">NET PRICE / UNIT</th>
                            <th style="text-align:center;font-weight: bold;">AMOUNT</th>
                        </tr>
                        @foreach($productItems as $key => $item)
                            @if (($key <= $num && $key > $num -10) || $key <= $num && $i == 1)
                                @foreach ($unit as $singleUnit)
                                    @if($singleUnit->id == $item['product']->unit)

                                        <tr>
                                            <td style="text-align:center;">{{$key+1}}</td>
                                            <td>{{ $item['product']->name_en }}</td> <!-- สมมติว่า Product_Name เป็นฟิลด์ในโมเดล -->
                                            <td style="text-align:center;">{{ $item['quantity'] }}</td>
                                            <td  style="text-align:center;">{{ $singleUnit->name_th }}</td>
                                            @php
                                                $normalPrice = preg_replace('/[^0-9.]/', '', $item['product']->normal_price);
                                            @endphp
                                            <td style="text-align:center;">{{ number_format((float)$normalPrice, 0) }}</td>
                                            <td style="text-align:center;">{{ $item['totaldiscount'] }}</td>
                                            <td style="text-align:center;">{{  number_format($item['discountedPrices']) }}</td>
                                            <td style="text-align:center;">{{ number_format($item['discountedPricestotal']) }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                @php
                                    $num2 +=1;
                                @endphp
                            @endif
                        @endforeach
                    </table>
                    @if ($page_item == $i )
                        @if ($Mvat->id == 50)
                            <table  id="customers" class="table" style="width: 28%;float:right;" >
                                <tr>
                                    <td style="text-align:right;font-size: 16px;width: 65%" class="text-right"><strong>Subtotal</strong></td>
                                    <td style="text-align:right;font-size: 16px;"><strong id="total-amount">{{ number_format($totalAmount, 2, '.', ',') }} </strong></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size: 16px;" class="text-right"><strong>Special Discount</strong></td>
                                    <td style="text-align:right;font-size: 16px;"><strong id="total-discount">{{ number_format($SpecialDis, 2, '.', ',') }} </strong></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size: 16px;" class="text-right"><strong>Subtotal less Discount</strong></td>
                                    <td style="text-align:right;font-size: 16px;" ><strong id="total-Price">{{ number_format($subtotal, 2, '.', ',') }} </strong></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size: 16px;" colspan="1" class="text-right"><strong>Price Before Tax</strong></td>
                                    <td style="text-align:right;font-size: 16px;"><strong id="total-Price">{{ number_format($beforeTax, 2, '.', ',') }} </strong></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size: 16px;" colspan="1" class="text-right"><strong>Value Added Tax</strong></td>
                                    <td style="text-align:right;font-size: 16px;"><strong id="total-Price">{{ number_format($AddTax, 2, '.', ',') }} </strong></td>
                                </tr>
                                <tr style="background-color: #ffffff"><td colspan="2"><br></td></tr>

                                <tr style="background-color: #ffffff">
                                    <td colspan="2" style="text-align:center;">
                                        <div style="display: flex; justify-content: center; align-items: center; border: 2px solid #2D7F7B; background-color: #2D7F7B; border-radius: 5px; color: #ffffff;  padding-bottom: 8px;">
                                            <b style="font-size: 16px;">Net Total </b>
                                            <strong id="total-Price" style="font-size: 16px; margin-left: 10px;">{{ number_format($Nettotal, 2, '.', ',') }} </strong>
                                        </div>
                                    </td>
                                </tr>

                                <br>
                                <tr style="border: 1px solid #2D7F7B;background-color: #2D7F7B;">
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr  style="border: 1px solid #ffffff;background-color: #fff;">
                                    <td style="text-align:right;" colspan="1" class="text-right"><strong>Number of Guests : </strong></td>
                                    <td style="text-align:left;"><strong id="total-Price">{{$totalguest}} </strong>Adults</td>
                                </tr>
                                <tr  style="border: 1px solid #ffffff;background-color: #fff;">
                                    <td style="text-align:right;" colspan="1" class="text-right"><strong>Average per person : </strong></td>
                                    <td style="text-align:left;"><strong id="total-Price">{{ number_format($totalaverage, 2, '.', ',') }} </strong>THB</td>
                                </tr>

                            </table>
                        @elseif ($Mvat->id == 51)
                            <table  id="customers" class="table" style="width: 28%;float:right;" >
                                <tr>
                                    <td style="text-align:right;font-size: 16px;width: 65%" class="text-right"><strong>Subtotal</strong></td>
                                    <td style="text-align:right;font-size: 16px;"><strong id="total-amount">{{ number_format($totalAmount, 2, '.', ',') }} </strong></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size: 16px;" class="text-right"><strong>Special Discount</strong></td>
                                    <td style="text-align:right;font-size: 16px;"><strong id="total-discount">{{ number_format($SpecialDis, 2, '.', ',') }} </strong></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size: 16px;" class="text-right"><strong>Subtotal less Discount</strong></td>
                                    <td style="text-align:right;font-size: 16px;" ><strong id="total-Price">{{ number_format($subtotal, 2, '.', ',') }} </strong></td>
                                </tr>
                                <tr style="background-color: #ffffff"><td colspan="2"><br></td></tr>

                                <tr style="background-color: #ffffff">
                                    <td colspan="2" style="text-align:center;">
                                        <div style="display: flex; justify-content: center; align-items: center; border: 2px solid #2D7F7B; background-color: #2D7F7B; border-radius: 5px; color: #ffffff;  padding-bottom: 8px;">
                                            <b style="font-size: 16px;">Net Total </b>
                                            <strong id="total-Price" style="font-size: 16px; margin-left: 10px;">{{ number_format($Nettotal, 2, '.', ',') }} </strong>
                                        </div>
                                    </td>
                                </tr>

                                <br>
                                <tr style="border: 1px solid #2D7F7B;background-color: #2D7F7B;">
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr  style="border: 1px solid #ffffff;background-color: #fff;">
                                    <td style="text-align:right;" colspan="1" class="text-right"><strong>Number of Guests : </strong></td>
                                    <td style="text-align:left;"><strong id="total-Price"> {{$totalguest}} </strong>Adults</td>
                                </tr>
                                <tr  style="border: 1px solid #ffffff;background-color: #fff;">
                                    <td style="text-align:right;" colspan="1" class="text-right"><strong>Average per person : </strong></td>
                                    <td style="text-align:left;"><strong id="total-Price"> {{ number_format($totalaverage, 2, '.', ',') }} </strong>THB</td>
                                </tr>
                            </table>
                        @elseif ($Mvat->id == 52)
                            <table  id="customers" class="table" style="width: 28%;float:right;" >
                                <tr>
                                    <td style="text-align:right;font-size: 16px;width: 65%" class="text-right"><strong>Subtotal</strong></td>
                                    <td style="text-align:right;font-size: 16px;"><strong id="total-amount">{{ number_format($totalAmount, 2, '.', ',') }} </strong></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size: 16px;" class="text-right"><strong>Special Discount</strong></td>
                                    <td style="text-align:right;font-size: 16px;"><strong id="total-discount">{{ number_format($SpecialDis, 2, '.', ',') }} </strong></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size: 16px;" class="text-right"><strong>Subtotal less Discount</strong></td>
                                    <td style="text-align:right;font-size: 16px;" ><strong id="total-Price">{{ number_format($subtotal, 2, '.', ',') }} </strong></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size: 16px;" colspan="1" class="text-right"><strong>Value Added Tax</strong></td>
                                    <td style="text-align:right;font-size: 16px;"><strong id="total-Price">{{ number_format($AddTax, 2, '.', ',') }} </strong></td>
                                </tr>
                                <tr style="background-color: #ffffff"><td colspan="2"><br></td></tr>
                                <tr style="background-color: #ffffff">
                                    <td colspan="2" style="text-align:center;">
                                        <div style="display: flex; justify-content: center; align-items: center; border: 2px solid #2D7F7B; background-color: #2D7F7B; border-radius: 5px; color: #ffffff;  padding-bottom: 8px;">
                                            <b style="font-size: 16px;">Net Total </b>
                                            <strong id="total-Price" style="font-size: 16px; margin-left: 10px;">{{ number_format($Nettotal, 2, '.', ',') }} </strong>
                                        </div>
                                    </td>
                                </tr>
                                <br>
                                <tr style="border: 1px solid #2D7F7B;background-color: #2D7F7B;">
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr  style="border: 1px solid #ffffff;background-color: #fff;">
                                    <td style="text-align:right;" colspan="1" class="text-right"><strong>Number of Guests : </strong></td>
                                    <td style="text-align:left;"><strong id="total-Price"> {{$totalguest}} </strong>Adults</td>
                                </tr>
                                <tr  style="border: 1px solid #ffffff;background-color: #fff;">
                                    <td style="text-align:right;" colspan="1" class="text-right"><strong>Average per person : </strong></td>
                                    <td style="text-align:left;"><strong id="total-Price"> {{ number_format($totalaverage, 2, '.', ',') }} </strong>THB</td>
                                </tr>
                            </table>
                        @else
                            <table  id="customers" class="table" style="width: 28%;float:right;" >
                                <tr>
                                    <td style="text-align:right;font-size: 16px;width: 65%" class="text-right"><strong>Subtotal</strong></td>
                                    <td style="text-align:right;font-size: 16px;"><strong id="total-amount">{{ number_format($totalAmount, 2, '.', ',') }} </strong></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size: 16px;" class="text-right"><strong>Special Discount</strong></td>
                                    <td style="text-align:right;font-size: 16px;"><strong id="total-discount">{{ number_format($SpecialDis, 2, '.', ',') }} </strong></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size: 16px;" class="text-right"><strong>Subtotal less Discount</strong></td>
                                    <td style="text-align:right;font-size: 16px;" ><strong id="total-Price">{{ number_format($subtotal, 2, '.', ',') }} </strong></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size: 16px;" colspan="1" class="text-right"><strong>Price Before Tax</strong></td>
                                    <td style="text-align:right;font-size: 16px;"><strong id="total-Price">{{ number_format($beforeTax, 2, '.', ',') }} </strong></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size: 16px;" colspan="1" class="text-right"><strong>Value Added Tax</strong></td>
                                    <td style="text-align:right;font-size: 16px;"><strong id="total-Price">{{ number_format($AddTax, 2, '.', ',') }} </strong></td>
                                </tr>
                                <tr style="background-color: #ffffff"><td colspan="2"><br></td></tr>

                                <tr style="background-color: #ffffff">
                                    <td colspan="2" style="text-align:center;">
                                        <div style="display: flex; justify-content: center; align-items: center; border: 2px solid #2D7F7B; background-color: #2D7F7B; border-radius: 5px; color: #ffffff;  padding-bottom: 8px;">
                                            <b style="font-size: 16px;">Net Total </b>
                                            <strong id="total-Price" style="font-size: 16px; margin-left: 10px;">{{ number_format($Nettotal, 2, '.', ',') }} </strong>
                                        </div>
                                    </td>
                                </tr>

                                <br>
                                <tr style="border: 1px solid #2D7F7B;background-color: #2D7F7B;">
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr  style="border: 1px solid #ffffff;background-color: #fff;">
                                    <td style="text-align:right;" colspan="1" class="text-right"><strong>Number of Guests : </strong></td>
                                    <td style="text-align:left;"><strong id="total-Price"> {{$totalguest}} </strong>Adults</td>
                                </tr>
                                <tr  style="border: 1px solid #ffffff;background-color: #fff;">
                                    <td style="text-align:right;" colspan="1" class="text-right"><strong>Average per person : </strong></td>
                                    <td style="text-align:left;"><strong id="total-Price"> {{ number_format($totalaverage, 2, '.', ',') }} </strong>THB</td>
                                </tr>
                            </table>
                        @endif
                    @else
                        @if ($Mvat->id == 50)
                            <table  id="customers" class="table" style="width: 25%;float:right;" >
                                <tr>
                                    <td style="text-align:right;font-size: 16px;width: 65%" class="text-right"><strong>Subtotal</strong></td>
                                    <td style="text-align:right;font-size: 16px;"><strong id="total-amount"></strong></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size: 16px;" class="text-right"><strong>Special Discount</strong></td>
                                    <td style="text-align:right;font-size: 16px;"><strong id="total-discount"></strong></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size: 16px;" class="text-right"><strong>Subtotal less Discount</strong></td>
                                    <td style="text-align:right;font-size: 16px;" ><strong id="total-Price"></strong></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size: 16px;" colspan="1" class="text-right"><strong>Price Before Tax</strong></td>
                                    <td style="text-align:right;font-size: 16px;"><strong id="total-Price"></strong></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size: 16px;" colspan="1" class="text-right"><strong>Value Added Tax</strong></td>
                                    <td style="text-align:right;font-size: 16px;"><strong id="total-Price"></strong></td>
                                </tr>
                                <tr style="background-color: #ffffff"><td colspan="2"><br></td></tr>

                                <tr style="background-color: #ffffff">
                                    <td colspan="2" style="text-align:center;">
                                        <div style="display: flex; justify-content: center; align-items: center; border: 2px solid #2D7F7B; background-color: #2D7F7B; border-radius: 5px; color: #ffffff;  padding-bottom: 8px;">
                                            <b style="font-size: 16px;">Net Total </b>
                                            <strong id="total-Price" style="font-size: 16px; margin-left: 10px;"></strong>
                                        </div>
                                    </td>
                                </tr>

                                <br>
                                <tr style="border: 1px solid #2D7F7B;background-color: #2D7F7B;">
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr  style="border: 1px solid #ffffff;background-color: #fff;">
                                    <td style="text-align:right;" colspan="1" class="text-right"><strong>Number of Guests : </strong></td>
                                    <td style="text-align:left;"><strong id="total-Price"> 0 </strong>Adults</td>
                                </tr>
                                <tr  style="border: 1px solid #ffffff;background-color: #fff;">
                                    <td style="text-align:right;" colspan="1" class="text-right"><strong>Average per person : </strong></td>
                                    <td style="text-align:left;"><strong id="total-Price"> 0 </strong>THB</td>
                                </tr>
                            </table>
                        @elseif ($Mvat->id == 51)
                            <table  id="customers" class="table" style="width: 25%;float:right;" >
                                <tr>
                                    <td style="text-align:right;font-size: 16px;width: 65%" class="text-right"><strong>Subtotal</strong></td>
                                    <td style="text-align:right;font-size: 16px;"><strong id="total-amount"></strong></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size: 16px;" class="text-right"><strong>Special Discount</strong></td>
                                    <td style="text-align:right;font-size: 16px;"><strong id="total-discount"></strong></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size: 16px;" class="text-right"><strong>Subtotal less Discount</strong></td>
                                    <td style="text-align:right;font-size: 16px;" ><strong id="total-Price"></strong></td>
                                </tr>
                                <tr style="background-color: #ffffff"><td colspan="2"><br></td></tr>
                                <tr style="background-color: #ffffff">
                                    <td colspan="2" style="text-align:center;">
                                        <div style="display: flex; justify-content: center; align-items: center; border: 2px solid #2D7F7B; background-color: #2D7F7B; border-radius: 5px; color: #ffffff;  padding-bottom: 8px;">
                                            <b style="font-size: 16px;">Net Total </b>
                                            <strong id="total-Price" style="font-size: 16px; margin-left: 10px;"></strong>
                                        </div>
                                    </td>
                                </tr>

                                <br>
                                <tr style="border: 1px solid #2D7F7B;background-color: #2D7F7B;">
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr  style="border: 1px solid #ffffff;background-color: #fff;">
                                    <td style="text-align:right;" colspan="1" class="text-right"><strong>Number of Guests : </strong></td>
                                    <td style="text-align:left;"><strong id="total-Price"> 0 </strong>Adults</td>
                                </tr>
                                <tr  style="border: 1px solid #ffffff;background-color: #fff;">
                                    <td style="text-align:right;" colspan="1" class="text-right"><strong>Average per person : </strong></td>
                                    <td style="text-align:left;"><strong id="total-Price"> 0 </strong>THB</td>
                                </tr>

                            </table>
                        @elseif ($Mvat->id == 52)
                            <table  id="customers" class="table" style="width: 25%;float:right;" >
                                <tr>
                                    <td style="text-align:right;font-size: 16px;width: 65%" class="text-right"><strong>Subtotal</strong></td>
                                    <td style="text-align:right;font-size: 16px;"><strong id="total-amount"></strong></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size: 16px;" class="text-right"><strong>Special Discount</strong></td>
                                    <td style="text-align:right;font-size: 16px;"><strong id="total-discount"></strong></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size: 16px;" class="text-right"><strong>Subtotal less Discount</strong></td>
                                    <td style="text-align:right;font-size: 16px;" ><strong id="total-Price"></strong></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size: 16px;" colspan="1" class="text-right"><strong>Value Added Tax</strong></td>
                                    <td style="text-align:right;font-size: 16px;"><strong id="total-Price"></strong></td>
                                </tr>
                                <tr style="background-color: #ffffff"><td colspan="2"><br></td></tr>
                                <tr style="background-color: #ffffff">
                                    <td colspan="2" style="text-align:center;">
                                        <div style="display: flex; justify-content: center; align-items: center; border: 2px solid #2D7F7B; background-color: #2D7F7B; border-radius: 5px; color: #ffffff;  padding-bottom: 8px;">
                                            <b style="font-size: 16px;">Net Total </b>
                                            <strong id="total-Price" style="font-size: 16px; margin-left: 10px;"></strong>
                                        </div>
                                    </td>
                                </tr>

                                <br>
                                <tr style="border: 1px solid #2D7F7B;background-color: #2D7F7B;">
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr  style="border: 1px solid #ffffff;background-color: #fff;">
                                    <td style="text-align:right;" colspan="1" class="text-right"><strong>Number of Guests : </strong></td>
                                    <td style="text-align:left;"><strong id="total-Price"> 0 </strong>Adults</td>
                                </tr>
                                <tr  style="border: 1px solid #ffffff;background-color: #fff;">
                                    <td style="text-align:right;" colspan="1" class="text-right"><strong>Average per person : </strong></td>
                                    <td style="text-align:left;"><strong id="total-Price"> 0 </strong>THB</td>
                                </tr>
                            </table>
                        @else
                            <table  id="customers" class="table" style="width: 25%;float:right;" >
                                <tr>
                                    <td style="text-align:right;font-size: 16px;width: 65%" class="text-right"><strong>Subtotal</strong></td>
                                    <td style="text-align:right;font-size: 16px;"><strong id="total-amount"></strong></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size: 16px;" class="text-right"><strong>Special Discount</strong></td>
                                    <td style="text-align:right;font-size: 16px;"><strong id="total-discount"></strong></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size: 16px;" class="text-right"><strong>Subtotal less Discount</strong></td>
                                    <td style="text-align:right;font-size: 16px;" ><strong id="total-Price"></strong></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size: 16px;" colspan="1" class="text-right"><strong>Price Before Tax</strong></td>
                                    <td style="text-align:right;font-size: 16px;"><strong id="total-Price"></strong></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size: 16px;" colspan="1" class="text-right"><strong>Value Added Tax</strong></td>
                                    <td style="text-align:right;font-size: 16px;"><strong id="total-Price"></strong></td>
                                </tr>
                                <tr style="background-color: #ffffff"><td colspan="2"><br></td></tr>

                                <tr style="background-color: #ffffff">
                                    <td colspan="2" style="text-align:center;">
                                        <div style="display: flex; justify-content: center; align-items: center; border: 2px solid #2D7F7B; background-color: #2D7F7B; border-radius: 5px; color: #ffffff;  padding-bottom: 8px;">
                                            <b style="font-size: 16px;">Net Total </b>
                                            <strong id="total-Price" style="font-size: 16px; margin-left: 10px;"></strong>
                                        </div>
                                    </td>
                                </tr>

                                <br>
                                <tr style="border: 1px solid #2D7F7B;background-color: #2D7F7B;">
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr  style="border: 1px solid #ffffff;background-color: #fff;">
                                    <td style="text-align:right;" colspan="1" class="text-right"><strong>Number of Guests : </strong></td>
                                    <td style="text-align:left;"><strong id="total-Price"> 0 </strong>Adults</td>
                                </tr>
                                <tr  style="border: 1px solid #ffffff;background-color: #fff;">
                                    <td style="text-align:right;" colspan="1" class="text-right"><strong>Average per person : </strong></td>
                                    <td style="text-align:left;"><strong id="total-Price"> 0 </strong>THB</td>
                                </tr>
                            </table>
                        @endif
                    @endif
                    <b>Notes or Special Comment : </b><br>
                    <div style="line-height:15px;width: 65%;border: 1px solid #afafaf; height: 70px;border-radius: 5px;">
                        <span>{{$comment}}</span>
                    </div>
                    <div style="line-height:10px;">
                    </div>
                    <strong class="com" style="font-size: 14px;">Method of Payment</strong><br>
                    @if ($Mevent == '43')
                        <span style="line-height:10px;font-size: 13px;">
                            Please make a 50% deposit within 7 days after confirmed. <br>
                            Transfer to <strong> " Together Resort Limited Partnership "</strong> following banks details.<br>
                            If you use transfer, Please inform Accounting / Finance Department Tel or LINE ID<span> @Together-resort</span><br>
                            pay-in slip to number 032-708-888 every time for the correctness of payment allocation.<br>
                        </span>
                    @else
                        <span style="line-height:10px;font-size: 13px;">
                            Please make a 100% deposit within 3 days after confirmed. <br>
                            Transfer to <strong> " Together Resort Limited Partnership "</strong> following banks details.<br>
                            If you use transfer, Please inform Accounting / Finance Department Tel or LINE ID<span> @Together-resort</span><br>
                            pay-in slip to number 032-708-888 every time for the correctness of payment allocation.<br>
                        </span>
                    @endif

                    <div style="margin-top: 15px">
                        <img src="SCB.jpg" style="width: 4%; border-radius: 50%;padding:4px"/>
                        <div style="float: right;margin-right:490px;line-height:10px;font-size: 13px;">
                            <strong  style="display: block; text-align: left;">The Siam Commercial Bank Public Company Limited</strong>
                            <strong  style="display: block; text-align: left;">Bank Account No. 708-226791-3</strong>
                            <strong  style="display: block; text-align: left;">Tha Yang - Phetchaburi Branch (Savings Account)</strong>
                        </div>
                    </div>
                </main>
            </div>
        @endfor
    </body>
</html>
