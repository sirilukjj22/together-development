
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
                font-size: 18px;
            }
            main{
                font-family: "THSarabunNew";
                font-size: 18px;
                color: #000;
            }
            footer {
                position: fixed;
                bottom: 0cm;
                left: 0cm;
                right: 0cm;
                height: 4cm;
                font-family: "THSarabunNew";
            }
            div.PROPOSAL {
                position: absolute;
                top: 0px;
                right: 6;
                width:580px;
                height: 60px;
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

            height: 120px;

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
            .font-upper {
            text-transform: uppercase;
            }

            .center {
                text-align: center;
            }
            /* Table หน้าออกบิล*/

            .table-revenueEditBill-container {
            margin-bottom: 1rem;
            overflow: auto;
            }

            table#table-revenueEditBill {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            color: rgb(1, 45, 48);
            border-radius: 5px;
            overflow: hidden;
            }

            #table-revenueEditBill td:nth-child(1),
            #table-revenueEditBill th:nth-child(1) {
            text-align: center;
            width: 10%;
            }

            #table-revenueEditBill td:nth-child(2),
            #table-revenueEditBill th:nth-child(2) {
            text-align: start;
            width: 60%;
            }

            #table-revenueEditBill td:nth-child(3),
            #table-revenueEditBill th:nth-child(3) {
            text-align: end;
            width: 15%;
            }

            #table-revenueEditBill td:nth-child(4),
            #table-revenueEditBill th:nth-child(4) {
            text-align: end;
            width: 15%;
            }

            #table-revenueEditBill th {
            border-top: 1px solid #a7adad;
            border-bottom: 1px solid #a7adad;
            text-transform: uppercase;
            }
            #table-revenueEditBill td {
            border-top: 1px solid #a7adad;
            text-transform: capitalize;

            }



            #table-revenueEditBill tr:nth-child(2) td {
            padding: 0 !important;
            }



            #table-revenueEditBill th,
            #table-revenueEditBill td {
            padding: 0.5rem;
            }
            table.receipt-subtotal {
                width: 100%;
                text-align: end;

            }

            table.receipt-subtotal  td:nth-child(1) {
                width: 60%;

            }


            table.receipt-subtotal  td:nth-child(2) {
                    width: 20%;
            }

            table.receipt-subtotal  td:nth-child(3) {
                    width: 20%;
                    border-top: 1px solid rgb(52, 51, 51);
                    border-bottom: 1px double rgb(52, 51, 51);
                text-align: right;
            }

            table.receipt-subtotal  td:nth-child(3) li:nth-last-child(1) {
                border-top: 1px solid rgb(52, 51, 51);
            border-bottom: 1px double rgb(52, 51, 51);
            }
            .receipt-bottom {
                padding: 1rem 2%;
                border-top: 0.5px solid;
            }
            li {
            list-style: none;
            }
        </style>
    </head>
    <body>
        <header>
            <div class="txt-head">

                <div class="add-text" style="line-height:12px;margin-left:10px;">

                    <strong style="font-size:1.2rem;font-weight:100">{{$settingCompany->name_th}}</strong>

                    <br> <strong style="font-size:12px;font-weight:100"> {{$settingCompany->name}}</strong>

                    <br> <strong style="font-size:12px;font-weight:100;padding:12px"> ***Head Office / Headquarters***</strong>

                    <br> <b> {{$settingCompany->address}}</b>

                    <br> <b>Tel : {{$settingCompany->tel}} | Fax : {{$settingCompany->fax}}</b>

                    <br> <b>HOTEL TAX ID :{{$settingCompany->Hotal_ID}}</b>

                    </br> <b> Website : {{$settingCompany->web}} | Email : {{$settingCompany->email}} </b>

                </div>

            </div>
            <br><br><br>
            <div class="PROPOSAL">

                <div id="logo">

                    <img src="{{$settingCompany->image}}">

                </div>

            </div>
        </header>
        <br>
        <footer>
            <div style="border: 1px solid #000;"></div>
            <span>I agree that my liability for this invoice is not waived and agree to be held personally liable in the event that the indicated person, company, or association fails to pay for any part or the full amount of these charges.</span>
              <table class="signature">
                  <tr>
                    <td style="width: 10%"></td>
                    <td style="text-align: left" >
                        ------------------------------------------------- <br>
                        <span>Guest's Signature</span>
                    </td>
                    <td style="text-align: right">
                        ------------------------------------------------- <br>
                        <span>Cashier's Signature</span><br>
                        <span>Nopparat</span>
                    </td>
                    <td style="width: 10%"></td>
                </tr>
              </table>

        </footer>
        <main>
            <h3 class="center font-upper">Receipt / tax invoice</h3>
            <b  style="font-size:18px">Tax invoice no.587 (original)</b>
            <div style="width:70%">
                <table style="line-height:12px;">
                    <tr>
                        <td ><span >Guest Name <span style="color: #fff">asdsd</span></span></td>
                        <td >:</td>
                    </tr>
                    <tr>
                        <td><span >Room Number</span></td>
                        <td>:
                        </td>
                    </tr>
                    <tr>
                        <td><span >Company</span> </td>
                        <td>:

                        </td>
                    </tr>
                    <tr>
                        <td><span >Tax ID/Gst Pass</span></td>
                        <td>:
                        </td>

                    </tr>
                    <tr>
                        <td style="color: #fff">:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><span>Adress</span></td>
                        <td>:</td>
                    </tr>
                    <tr>
                        <td style="color: #fff">:</td>
                        <td></td>
                    </tr>
                </table>
                <div style="margin-top: 2px"></div>
            </div>
            <span style="position: absolute;top: 230px; right: 10px;width: 280px;height: 145px;line-height:14px;">
                <table style="line-height:12px;">
                    <tr>
                        <td ><span >Page#<span style="color: #fff">asdsssssssssd</span></span></td>
                        <td >:</td>
                    </tr>
                    <tr>
                        <td><span >Folio No.</span></td>
                        <td>:
                        </td>
                    </tr>
                    <tr>
                        <td><span >Arrival</span> </td>
                        <td>:

                        </td>
                    </tr>
                    <tr>
                        <td><span >Departure</span></td>
                        <td>:
                        </td>

                    </tr>
                    <tr>
                        <td><span>No. of Guest</span></td>
                        <td>:</td>
                    </tr>
                    <tr>
                        <td><span>Printed Date</span></td>
                        <td>:</td>
                    </tr>
                    <tr>
                        <td><span>Print time</span></td>
                        <td>:</td>
                    </tr>
                    <tr>
                        <td><span>Tax Invoice Date</span></td>
                        <td>:</td>
                    </tr>
                </table>
            </span><br>
            <div class="table-revenueEditBill-container">
                <table id="table-revenueEditBill" class="table-revenueEditBill" style="line-height:12px;">
                    <thead >
                        <tr >
                            <th>Date</th>
                            <th >Description </th>
                            <th>Reference</th>
                            <th style="text-align: right">amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td id="displayPaymentDateEditBill">10/04/2024</td>
                            <td >
                                SCB Bank Transfer - Together Resort Ltd - Reservation Deposit
                                </td>
                            <td ></td>
                            <td style="text-align: right">16,400.00</td>
                        </tr>
                        <tr >
                            <td style="border: none"></td>
                            <td style="border: none">***รายละเอียดโปรดระบุ</td>
                            <td style="border: none"></td>
                            <td style="border: none"></td>
                        </tr>
                    </tbody>
                </table>
                <table class="receipt-subtotal" style="line-height:14px;">
                    <td></td>
                    <td>
                        <li>
                        Total Balance(Baht)
                        </li>
                        <li>
                        Vatable
                        </li>
                        <li>
                        VAT 7 %
                        </li>
                        <li>
                        Non - Vatable
                        </li>
                        <li>
                        Total Amount (Baht)
                        </li>
                        <li class="font-w-600">
                        Net Total
                        </li>
                    </td>
                    <td>
                        <li>
                            400
                        </li>
                        <li>
                            400
                        </li>
                        <li>
                            0
                        </li>
                        <li>
                        0
                        </li>
                        <li>
                            0
                        </li>
                        <li class="font-w-600">
                            0
                        </li>
                    </td>
                </table>
            </div>
        </main>
    </body>

</html>
