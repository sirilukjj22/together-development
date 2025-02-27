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
            table.signature {
            width: 100%;
            text-align: center;
            }
        </style>
    </head>
    <body>
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

                    <b style="font-size:18px;color:#ffffff;font-weight: bold;">DEPOSIT REVENUE</b>

                </div>

            </div>


            <div class="PROPOSALfirst" style="line-height:10px;">

                <div style="padding: 4%">

                    <b >DEPOSIT ID : </b><span style="margin-left: 10px;">{{ $DepositID }}</span><br>

                    <b >Issue Date : </b><span >{{ $IssueDate }}</span><br>

                    <b>Expiration Date : </b><span>{{ $Expiration }}</span>

                </div>

            </div>
        </header>
        <footer>
            <div style="border: 1px solid #2D7F7B;"></div>
            <span>I agree that my liability for this invoice is not waived and agree to be held personally liable in the event that the indicated person, company, or association fails to pay for any part or the full amount of these charges.</span>
              <table class="signature">
                  <tr>
                    <td style="width: 10%"></td>
                    <td style="text-align: left" >
                        ------------------------------------------------- <br>
                        <span>Guest's Signature</span>
                        <br><br>
                    </td>
                    <td style="text-align: right">
                        ------------------------------------------------- <br>
                        <span>Cashier's Signature</span><br>
                        <span>{{$user->firstname}}</span>
                    </td>
                    <td style="width: 10%"></td>
                </tr>
              </table>
        </footer>
        <main>
            <b class="com" style="font-size:18px">Deposit Revenue</b>
            <div style=" border-right-style: solid  ; border-right-width: 2px;border-right-color:#2D7F7B; width:55%">
                <table style="line-height:12px;" >
                    <tr>
                        <td ><b style="margin-left: 10px; width:30%">Guest Name :</b></td>
                        <td>{{$fullname}}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><b style="margin-left: 10px;">Address :</b></td>
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
                        <td><b style="margin-left: 10px;">Email :</b></td>
                        <td>{{$email}}</td>
                    </tr>
                    <tr>
                        <td><b style="margin-left: 10px;">Tax ID/Gst Pass : </b></td>
                        <td>{{$Identification}}</td>
                    </tr>
                    <tr>
                        <td><b style="margin-left: 10px;">Phone Number :</b></td>
                        <td>{{ $phone->Phone_number }}</td>
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
                @if ($Checkin == '-')
                    <b style="margin-left: 10px">Check In : </b><span style="margin-left: 2px;">No Check in date</span><br>
                @else
                <br><b style="margin-left: 10px">Check In : </b><span style="margin-left: 62px;">{{$Checkin}}</span><br>
                <b style="margin-left: 10px">Check Out : </b><span style="margin-left: 52px;">{{$Checkout}}</span><br>
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
                        <td style="text-align:left">อ้างอิงเอกสาร : {{$Quotation->Quotation_ID}} เอกสาร Invoice / Deposit : {{$DepositID}}</span> ครั้งที่ {{$Deposit}}</td>
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
                <br><br><br><br><br><br>
                <div style="border: 1px solid #2D7F7B"></div>
                <div style=" width:50%">
                    <strong style="font-size: 14px;">PAYMENT</strong><br>
                    <table style="line-height:12px;" >
                        <tr>
                            <td ><b style="margin-left: 10px; width:30%">Date </b></td>
                            <td>: {{$paymentDate}}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><b style="margin-left: 10px;">Total Amount </b></td>
                            <td>: {{number_format($Amount, 2)}} THB
                            </td>
                        </tr>
                        @if ($count > 2)
                            <tr>
                                <td></td>
                                <td style="color: #fff">.</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="color: #fff">.</td>
                            </tr>
                        @endif
                    </table>

                </div>
                <span style="position: absolute;top: 690px; right: 50;width: 450px;height: 145px;line-height:14px;">
                    <div class="table-revenueEditBill-container">
                        <table id="table-revenueEditBill" class="table-revenueEditBill" style="line-height:12px;width: 100%;">
                            <thead >
                                <tr >
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th style="text-align: right"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productItems as $key => $item)
                                <tr>
                                    <td >{{$key + 1}}</td>
                                    <td >
                                        {{ $item['detail'] }}
                                    </td>
                                    <td ></td>
                                    <td style="text-align: right">{{ number_format($item['amount'], 2) }} THB</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </span>
            </div>
        </main>
    </body>
</html>
