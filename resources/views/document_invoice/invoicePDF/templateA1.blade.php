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
        @if ($Selectdata == 'Company')
            <header>
                <div id="logo">

                    <img src="{{$settingCompany->image}}">

                </div>

                <div class="txt-head">

                    <div class="add-text" style="line-height:12px;margin-left:10px;">

                        <b style="font-size:20px;">{{$settingCompany->name}}</b>

                        <br> <b> {{$settingCompany->address}}</b>

                        <br> <b>Tel : {{$settingCompany->tel}} Fax : {{$settingCompany->fax}}</b></br>

                        <b> Email : {{$settingCompany->email}} Website : {{$settingCompany->web}}</b>

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

                        <b >Issue Date : </b><span >{{ $IssueDate }}</span><br>

                        <b>Expiration Date : </b><span>{{ $Expiration }}</span>

                    </div>

                </div>
            </header>
            <footer>
                <div style="border: 1px solid #2D7F7B;margin-top: 10px;"></div>
                <table style="width: 35%;line-height:10px;margin-top: 10px;float: right;" >
                    <tr>
                        <th >สแกนเพื่อเปิดด้วยเว็บไซต์</th>
                        <th >ผู้ออกเอกสาร </th>
                    </tr>
                    <tr>
                        <td style="text-align: center;width:10%">
                            <img src="data:image/png;base64, {!! $qrCodeBase64 !!} " alt="QR Code" width="60" height="60"/>
                        <td style="text-align: center;">
                            @if ($user->signature)
                                <img src="upload/signature/{{$user->signature}}" style="width: 50%;"/>
                            @endif
                            @if ($user->firstname)
                                <span style="display: block; text-align: center;">{{$user->firstname}} {{$user->lastname}}</span>
                            @endif
                            <span style="display: block; text-align: center;">{{ $IssueDate }}</span>
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
                            <td>{{$fullName}}</td>
                            <td></td>
                        </tr>
                         <tr>
                            <td><b style="margin-left: 10px;">Company Address :</b></td>
                            <td>{{$Address}}
                                @if ($TambonID)
                                    {{'ตำบล' . $TambonID->name_th}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                @if ($TambonID)
                                    {{'อำเภอ' .$amphuresID->name_th}} {{'จังหวัด' .$provinceNames->name_th}} {{$TambonID->Zip_Code}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><b style="margin-left: 10px;">Company Number :</b></td>
                            <td>{{ $phone->Phone_number }}
                                <b style="margin-left: 10px;">Company Fax : </b><span>{{$Fax_number}}</span>
                            </td>
                            <td>

                            </td>
                        </tr>
                        <tr>
                            <td><b style="margin-left: 10px;">Company Email :</b></td>
                            <td>{{$Email}}</td>
                        </tr>
                        <tr>
                            <td><b style="margin-left: 10px;">Taxpayer Identification : </b></td>
                            <td>{{$Taxpayer_Identification}}</td>
                        </tr>
                        <tr>
                            <td><br></td>
                            <td><br></td>
                        </tr>
                    </table>
                    <div style="margin-top: 2px"></div>
                </div>
                <span style="position: absolute;top: 120px; right: 30;width: 280px;height: 145px;line-height:14px;">
                    <br>
                    <b class="com" style=" font-size:18px">Personal Information</b><br>
                    <b style="margin-left: 10px">Contact Name :  </b><span style="margin-left: 2px;">คุณ {{$Contact_Name}}</span><br>
                    <b style="margin-left: 10px">Contact Number : </b><span style="margin-left: 2px;">{{ substr($Contact_phone->Phone_number, 0, 3) }}-{{ substr($Contact_phone->Phone_number, 3, 3) }}-{{ substr($Contact_phone->Phone_number, 6) }}</span><br>
                    @if ($Checkin == '-')
                        <b style="margin-left: 10px">Check In : </b><span style="margin-left: 2px;">No Check in date</span><br>
                    @else
                        <b style="margin-left: 10px">Check In : </b><span style="margin-left: 2px;">{{$Checkin}}</span>
                        <b style="margin-left: 10px">Check Out : </b><span style="margin-left: 5px;">{{$Checkout}}</span><br>
                    @endif
                    <b style="margin-left: 10px">Length of Stay :</b>
                    <span style="margin-left: 28px;">
                        @if ($Day == null)
                            -
                        @else
                            {{$Day}} วัน {{$Night}} คืน
                        @endif
                    </span><br>
                    <b style="margin-left: 10px">Number of Guests :</b>
                    <span style="margin-left: 10px;">
                            @if ($Adult == null)
                            -
                        @else
                            {{$Adult}} Adult , {{$Children}} Children
                        @endif
                    </span><br>
                    <b style="margin-left: 10px">Valid :</b>
                    <span style="margin-left: 10px;">{{$valid}}</span>
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
                            <td style="text-align:left">Proposal ID : {{$Quotation->Quotation_ID}}  กรุณาชำระมัดจำ งวดที่ {{$Deposit}}</td>
                            <td style="text-align:right"><span id="Subtotal">  {{ number_format($Subtotal, 2) }}</span> THB</td>
                        </tr>
                        <tr>
                            <td style="text-align:center"></td>
                            <td><br></td>
                            <td style="text-align:right">Subtotal :</td>
                            <td style="text-align:right"><span id="SubtotalAll">{{ number_format($Subtotal, 2) }}</span> THB</td>
                        </tr>
                        <tr>
                            <td style="text-align:center"></td>
                            <td><br></td>
                            <td style="text-align:right">Price Before Tax :</td>
                            <td style="text-align:right"><span id="Before">{{ number_format($before, 2) }}</span> THB</td>
                        </tr>
                        <tr>
                            <td style="text-align:center"></td>
                            <td><br></td>
                            <td style="text-align:right">Value Added Tax :</td>
                            <td style="text-align:right"><span id="Added">{{ number_format($addtax, 2) }}</span> THB</td>
                        </tr>
                        <tr>
                            <td style="text-align:center"></td>
                            <td><br></td>
                            <td style="text-align:right">Net Total :</td>
                            <td style="text-align:right"><span id="Total">{{ number_format($Subtotal, 2) }}</span> THB</td>
                        </tr>
                    </tbody>
                </table>
                <div style="line-height:10px;">
                </div>
                <div>
                    <br><br><br><br><br><br><br><br><br><br>
                    <strong class="com" style="font-size: 14px;">FULL PAYMENT AFTER RESERVATION</strong><br>
                    <span style="line-height:10px;font-size: 13px;">
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
                    </div>
                </div>
            </main>
        @else
            <header>
                <div id="logo">

                    <img src="{{$settingCompany->image}}">

                </div>

                <div class="txt-head">

                    <div class="add-text" style="line-height:12px;margin-left:10px;">

                        <b style="font-size:20px;">{{$settingCompany->name}}</b>

                        <br> <b> {{$settingCompany->address}}</b>

                        <br> <b>Tel : {{$settingCompany->tel}} Fax : {{$settingCompany->fax}}</b></br>

                        <b> Email : {{$settingCompany->email}} Website : {{$settingCompany->web}}</b>

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

                        <b >Issue Date : </b><span >{{ $IssueDate }}</span><br>

                        <b>Expiration Date : </b><span>{{ $Expiration }}</span>

                    </div>

                </div>
            </header>
            <footer>
                <div style="border: 1px solid #2D7F7B;margin-top: 10px;"></div>
                <table style="width: 35%;line-height:10px;margin-top: 10px;float: right;" >
                    <tr>
                        <th >สแกนเพื่อเปิดด้วยเว็บไซต์</th>
                        <th >ผู้ออกเอกสาร </th>
                    </tr>
                    <tr>
                        <td style="text-align: center;width:10%">
                            <img src="data:image/png;base64, {!! $qrCodeBase64 !!} " alt="QR Code" width="60" height="60"/>
                        <td style="text-align: center;">
                            @if ($user->signature)
                                <img src="upload/signature/{{$user->signature}}" style="width: 50%;"/>
                            @endif
                            @if ($user->firstname)
                                <span style="display: block; text-align: center;">{{$user->firstname}} {{$user->lastname}}</span>
                            @endif
                            <span style="display: block; text-align: center;">{{ $IssueDate }}</span>
                        </td>
                    </tr>
                </table>
            </footer>
            <main>
                <b class="com" style="font-size:18px">Guest Information</b>
                <div style=" border-right-style: solid  ; border-right-width: 2px;border-right-color:#2D7F7B; width:55%">
                    <table style="line-height:12px;" >
                        <tr>
                            <td ><b style="margin-left: 10px; width:30%">Guest Name :</b></td>
                            <td>{{$fullName}}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><b style="margin-left: 10px;">Guest Address :</b></td>
                            <td>{{$Address}}
                                @if ($TambonID)
                                    {{'ตำบล' . $TambonID->name_th}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                @if ($TambonID)
                                    {{'อำเภอ' .$amphuresID->name_th}} {{'จังหวัด' .$provinceNames->name_th}} {{$TambonID->Zip_Code}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><b style="margin-left: 10px;">Guest Number :</b></td>
                            <td>{{ $phone->Phone_number }}
                                <b style="margin-left: 10px;">Guest Fax : </b><span>{{$Fax_number}}</span>
                            </td>
                            <td>

                            </td>
                        </tr>
                        <tr>
                            <td><b style="margin-left: 10px;">Guest Email :</b></td>
                            <td>{{$Email}}</td>
                        </tr>
                        <tr>
                            <td><b style="margin-left: 10px;">Taxpayer Identification : </b></td>
                            <td>{{$Taxpayer_Identification}}</td>
                        </tr>
                        <tr>
                            <td><br></td>
                            <td><br></td>
                        </tr>
                    </table>
                    <div style="margin-top: 2px"></div>
                </div>
                <span style="position: absolute;top: 120px; right: 30;width: 280px;height: 145px;line-height:14px;">
                    <br>
                    <br>
                    @if ($Checkin == '-')
                        <b style="margin-left: 10px">Check In : </b><span style="margin-left: 2px;">No Check in date</span><br>
                    @else
                        <b style="margin-left: 10px">Check In : </b><span style="margin-left: 2px;">{{$Checkin}}</span>
                        <b style="margin-left: 10px">Check Out : </b><span style="margin-left: 5px;">{{$Checkout}}</span><br>
                    @endif
                    <b style="margin-left: 10px">Length of Stay :</b>
                    <span style="margin-left: 28px;">
                        @if ($Day == null)
                            -
                        @else
                            {{$Day}} วัน {{$Night}} คืน
                        @endif
                    </span><br>
                    <b style="margin-left: 10px">Number of Guests :</b>
                    <span style="margin-left: 10px;">
                            @if ($Adult == null)
                            -
                        @else
                            {{$Adult}} Adult , {{$Children}} Children
                        @endif
                    </span><br>
                    <b style="margin-left: 10px">Valid :</b>
                    <span style="margin-left: 10px;">{{$valid}}</span>
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
                            <td style="text-align:left">Proposal ID : {{$Quotation->Quotation_ID}} กรุณาชำระมัดจำ งวดที่ {{$Deposit}}</td>
                            <td style="text-align:right"><span id="Subtotal">  {{ number_format($Subtotal, 2) }}</span> THB</td>
                        </tr>
                        <tr>
                            <td style="text-align:center"></td>
                            <td><br></td>
                            <td style="text-align:right">Subtotal :</td>
                            <td style="text-align:right"><span id="SubtotalAll">{{ number_format($Subtotal, 2) }}</span> THB</td>
                        </tr>
                        <tr>
                            <td style="text-align:center"></td>
                            <td><br></td>
                            <td style="text-align:right">Price Before Tax :</td>
                            <td style="text-align:right"><span id="Before">{{ number_format($before, 2) }}</span> THB</td>
                        </tr>
                        <tr>
                            <td style="text-align:center"></td>
                            <td><br></td>
                            <td style="text-align:right">Value Added Tax :</td>
                            <td style="text-align:right"><span id="Added">{{ number_format($addtax, 2) }}</span> THB</td>
                        </tr>
                        <tr>
                            <td style="text-align:center"></td>
                            <td><br></td>
                            <td style="text-align:right">Net Total :</td>
                            <td style="text-align:right"><span id="Total">{{ number_format($Subtotal, 2) }}</span> THB</td>
                        </tr>
                    </tbody>
                </table>
                <div style="line-height:10px;">
                </div>
                <div>
                    <br><br><br><br><br><br><br><br><br><br>
                    <strong class="com" style="font-size: 14px;">FULL PAYMENT AFTER RESERVATION</strong><br>
                    <span style="line-height:10px;font-size: 13px;">
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
                    </div>
                </div>
            </main>
        @endif
    </body>
</html>
