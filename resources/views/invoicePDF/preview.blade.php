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
                height: 40px;
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
            .tlaoi{
                display: flex;
                justify-content: center;
            }
        </style>
    </head>
    <body>
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

                        <b style="font-size:18px;color:#ffffff;font-weight: bold;">PROFORMA INVOICE</b>

                    </div>

                </div>


                <div class="PROPOSALfirst" style="line-height:10px;">

                    <div style="padding: 4%">

                        <b >Invoice ID : </b><span style="margin-left: 10px;">{{ $Invoice_ID }}</span><br>

                        <b >Proposal ID : </b><span style="margin-left: 10px;">{{ $Quotation->Quotation_ID }}</span><br>

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
                        <td style="text-align: center;">
                            <img src="test.png" style="width: 40%;"/>
                            <span style="display: block; text-align: center;">{{@$Quotation->user->name}}</span>
                            <span style="display: block; text-align: center;">{{ $date }}</span>
                        </td>
                        <td style="text-align: center;">
                            <img src="test.png" style="width: 40%;"/>
                            <span style="display: block; text-align: center;">{{@$Quotation->user->name}}</span>
                            <span style="display: block; text-align: center;">{{ $date }}</span>
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
                <br>
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
                            <td><b style="margin-left: 10px;">Taxpayer Identification : </b></td>
                            <td>{{$Company_ID->Taxpayer_Identification}}</td>
                        </tr>

                    </table>

                    <div style="margin-top: 2px"></div>
                </div>
                <span style="position: absolute;top: 150px; right: 30;width: 280px;height: 145px;line-height:14px;">
                    <b class="com" style=" font-size:18px">Personal Information</b><br>
                    <b style="margin-left: 10px;">Contact Name : </b><span >คุณ{{$Contact_name->First_name}} {{$Contact_name->Last_name}}</span><br>
                    <b style="margin-left: 10px;">Contact Number : </b><span>{{ substr($Contact_phone->Phone_number, 0, 3) }}-{{ substr($Contact_phone->Phone_number, 3, 3) }}-{{ substr($Contact_phone->Phone_number, 6) }}</span><br>
                    <b style="margin-left: 10px">Check In : </b><span style="margin-left: 2px;">{{$checkin}}</span>
                    <b style="margin-left: 10px">Check Out : </b><span style="margin-left: 5px;">{{$checkout}}</span>
                    <b style="margin-left: 10px">Length of Stay :</b><span style="margin-left: 23px;">{{$Quotation->day}} วัน {{$Quotation->night}} คืน</span><br>
                    <b style="margin-left: 10px">Number of Guests :</b><span style="margin-left: 10px;">{{$Quotation->adult}} Adult , {{$Quotation->adult}} Children</span><br>
                    <b style="margin-left: 10px;">Valid : </b><span style="margin-left: 10px;">{{$valid}}</span><br>
                </span>
                <div style="border: 1px solid #2D7F7B"></div>
                <table id="customers" class="table" style="width: 100%; margin-top:10px;font-size:16px" >
                    <thead style="background-color: rgba(45, 127, 123, 1);  color:#fff;">
                        <tr style="">
                            <th style="width:1px;color:rgb(61, 150, 145);">.</th>
                            <th style="line-height: 10px; width:10%; font-weight: bold; text-align:center; ">No.</th>
                            <th style="line-height: 10px;font-weight: bold; text-align:center;">Description</th>
                            <th style=" line-height: 10px;width:10%; font-weight: bold; text-align:center;">Amount</th>
                        </tr>
                    </thead>
                    <tbody id="display-selected-items">
                        <tr>
                            <td style="text-align:center"></td>
                            <td style="text-align:center">1</td>
                            <td style="text-align:left">{{$Invoice_ID}} อ้างอิง Proposal เลขที่ {{$Quotation->Quotation_ID}}  {{$payment}}  of {{ number_format($Nettotal) }} บาท ( {{$vatname}} )<br>กรุณาชำระมัดจำ งวดที่ {{$Deposit}}</td>
                            <td style="text-align:right"><span id="Subtotal">   {{ number_format($Subtotal) }}</span>฿ <input type="hidden" name="Nettotal" id="Nettotal" value="{{$balance}}"></td>
                        </tr>
                        <tr>
                            <td style="text-align:center"></td>
                            <td><br></td>
                            <td style="text-align:right">Subtotal :</td>
                            <td style="text-align:right"><span id="SubtotalAll">{{ number_format($Subtotal, 2) }}</span>฿</td>
                        </tr>
                        <tr>
                            <td style="text-align:center"></td>
                            <td><br></td>
                            <td style="text-align:right">Price Before Tax :</td>
                            <td style="text-align:right"><span id="Before">{{ number_format($before, 2) }}</span>฿</td>
                        </tr>
                        <tr>
                            <td style="text-align:center"></td>
                            <td><br></td>
                            <td style="text-align:right">Value Added Tax :</td>
                            <td style="text-align:right"><span id="Added">{{ number_format($addtax, 2) }}</span>฿</td>
                        </tr>
                        <tr>
                            <td style="text-align:center"></td>
                            <td><br></td>
                            <td style="text-align:right">Net Total :</td>
                            <td style="text-align:right"><span id="Total">{{ number_format($balance, 2) }}</span>฿</td>
                        </tr>
                    </tbody>
                </table>
                <b style="float: right">*** NINETY FIVE THOUSAND EIGHT HUNDRED NINETY BAHT ONLY ***</b>
                <div style="line-height:10px;">
                </div>
                <div>
                    <br><br><br><br><br><br><br><br>
                    <strong class="com" style="font-size: 14px;">Method of Payment</strong><br>
                    <span style="line-height:10px;font-size: 13px;">
                        FULL PAYMENT AFTER RESERVATION DATE 3 DAYS<br>
                        Transfer to <strong> " Together Resort Limited Partnership "</strong> following banks details.<br>
                        If you use transfer, Please inform Accounting / Finance Department Tel or LINE ID<span> @Together-resort</span><br>
                        pay-in slip to number 032-708-888 every time for the correctness of payment allocation.<br>
                    </span>
                    <div style="margin-top: 15px">
                        <img src="SCB.jpg" style="width: 4%; border-radius: 50%;padding:4px"/>
                        <div style="float: right;margin-right:490px;line-height:10px;font-size: 13px;">
                            <strong  style="display: block; text-align: left;">The Siam Commercial Bank Public Company Limited</strong>
                            <strong  style="display: block; text-align: left;">Bank Account No. 708-226791-3</strong>
                            <strong  style="display: block; text-align: left;">Tha Yang - Phetchaburi Branch (Savings Account)</strong>
                        </div>
                    </div><br>
                    <div style="font-size: 13px;line-height:10px;">
                        <b>PLEASE FAX YOUR REMITTANCE ADVICE TO US AT YOUR EARLIES CONVENIENCE</b><br>
                        <span>THANK YOU MUCH FOR YOUR VALUE SUPPORT AND LOOKING FORWARD TO HEAR FROM YOU SOON</span>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
