<html>
    <head>
        <meta charset="utf-8">
        <title>Revenue</title>
        <style>
            /** Define the margins of your page **/
            @page {
                margin: 100px 25px;
            }

            header {
                position: fixed;
                top: -80px;
                left: 0px;
                right: 0px;
                height: 50px;
            }

            body {
                position: relative;
                margin: 0 auto;
                color: #000;
                background: #FFFFFF;
                font-size: 14px;
                font-family: "THSarabunNew";
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

            #logo {
                float: left;
                /* margin-top: 8px; */
            }

            #logo img {
                height: 80px;
            }

            .txt-head {
                float: left;
                /* margin-top: 8px; */
            }

            .add-text {
                font-size: 16px;
                line-height: 12px;
                margin-left: 5px;
                margin-top: 20px;
            }
            #doc_name {
                text-align: center;
                line-height: 18px;
                /* border: 10px solid #000; */
                font-size: 24px;
                padding-left: 100px;
            }

            table {
                width: 100%;
                padding: 5px;
                border-collapse: collapse;
                border-spacing: 0;
                margin-bottom: 20px;
                font-size: 18px;
                color: #000;
                /* border: 1px solid #000; */
            }

            table tbody td {
                /* padding-left: 20px; */
                /* background-color: #EEEEEE; */
                border: 1px solid #000;
            }

            table th {
                /* color: #ffffff; */
                font-weight: bold;
                border: 1px solid #000;
            }

            table tbody tr:first-child td {
                border: 1px solid #000;
            }

            table tbody tr:last-child td {
                border: 1px solid #000;
            }

            table tfoot td {
                border: 1px solid #000;
                font-weight: bold;
            }

            table tfoot tr:first-child td {
                border-top: none;
                /* border: 1px solid #000; */
            }

            footer {
                position: fixed; 
                bottom: -60px; 
                left: 0px; 
                right: 0px;
                height: 10px; 

                /** Extra personal styles **/
                background-color: #029179;
                color: white;
                text-align: center;
                /* float: left; */
                line-height: 25px;
            }

            /* .page-break {
                page-break-after: always;
            } */
        </style>
    </head>
    <body>
        <!-- Define header and footer blocks before your content -->
        <header>
            <div id="logo">
                <img src="image/Logo1-01.png">
            </div>
            <div class="txt-head">
                <div class="add-text" style="line-height:14px;">
                    <b style="font-size:30px;">Together Resort</b>
                    <br> <b>168 หมู่ 2 ตำบลแก่งกระจาน อำเภอแก่งกระจาน เพชรบุรี 76710</b>
                    <br> <b>Tel : 032 708 888
                        Fax : - Ext : -</b>
                        Website : www.together-resort.com</b>
                </div>
            </div>
        </header>

        <footer class="add-name">

        </footer>

        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
            <p class="">
                <table id="detail" cellpadding="5" style="line-height: 13px;">
                    <thead>
                        <tr>
                            <th style="text-align: center;" rowspan="2"><b>วันที่</b></th>
                            <th style="text-align: center;" colspan="3"><b>รายได้ห้องพัก</b></th>
                            <th style="text-align: center;" colspan="3"><b>รายได้ห้องอาหาร</b></th>
                            <th style="text-align: center;" colspan="3"><b>รายได้สวนน้ำ</b></th>
                        </tr>
        
                        <tr>
                            <th>เงินสด</th>
                            <th>เงินโอน</th>
                            <th>เครดิต</th>
        
                            <th>เงินสด</th>
                            <th>เงินโอน</th>
                            <th>เครดิต</th>
        
                            <th>เงินสด</th>
                            <th>เงินโอน</th>
                            <th>เครดิต</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $item)
                            @if ($key <= 24)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ number_format($item->room_cash, 2) }}</td>
                                <td>{{ number_format($item->room_transfer, 2) }}</td>
                                <td>{{ number_format($item->room_credit, 2) }}</td>
            
                                <td>{{ number_format($item->fb_cash, 2) }}</td>
                                <td>{{ number_format($item->fb_transfer, 2) }}</td>
                                <td>{{ number_format($item->fb_credit, 2) }}</td>
            
                                <td>{{ number_format($item->wp_cash, 2) }}</td>
                                <td>{{ number_format($item->wp_transfer, 2) }}</td>
                                <td>{{ number_format($item->wp_credit, 2) }}</td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </p>

            <p class="">
                <table id="" cellpadding="5" style="line-height: 13px;">
                    <thead>
                        <tr>
                            <th style="text-align: center;" rowspan="2"><b>วันที่</b></th>
                            <th style="text-align: center;" colspan="3"><b>รายได้ห้องพัก</b></th>
                            <th style="text-align: center;" colspan="3"><b>รายได้ห้องอาหาร</b></th>
                            <th style="text-align: center;" colspan="3"><b>รายได้สวนน้ำ</b></th>
                        </tr>
        
                        <tr>
                            <th>เงินสด</th>
                            <th>เงินโอน</th>
                            <th>เครดิต</th>
        
                            <th>เงินสด</th>
                            <th>เงินโอน</th>
                            <th>เครดิต</th>
        
                            <th>เงินสด</th>
                            <th>เงินโอน</th>
                            <th>เครดิต</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $total_cash = 0;
                            $total_transfer = 0;
                            $total_credit = 0;

                            $total_fb_cash = 0;
                            $total_fb_transfer = 0;
                            $total_fb_credit = 0;
                            
                            $total_wp_cash = 0;
                            $total_wp_transfer = 0;
                            $total_wp_credit = 0;
                        ?>
                        @foreach ($data as $key => $item)
                            @if ($key >= 24)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ number_format($item->room_cash, 2) }}</td>
                                <td>{{ number_format($item->room_transfer, 2) }}</td>
                                <td>{{ number_format($item->room_credit, 2) }}</td>
            
                                <td>{{ number_format($item->fb_cash, 2) }}</td>
                                <td>{{ number_format($item->fb_transfer, 2) }}</td>
                                <td>{{ number_format($item->fb_credit, 2) }}</td>
            
                                <td>{{ number_format($item->wp_cash, 2) }}</td>
                                <td>{{ number_format($item->wp_transfer, 2) }}</td>
                                <td>{{ number_format($item->wp_credit, 2) }}</td>
                            </tr>
                            @endif

                            <?php 
                                $total_cash += $item->room_cash;
                                $total_transfer += $item->room_transfer;
                                $total_credit += $item->room_credit;

                                $total_fb_cash += $item->fb_cash;
                                $total_fb_transfer += $item->fb_transfer;
                                $total_fb_credit += $item->fb_credit;
                                
                                $total_wp_cash += $item->wp_cash;
                                $total_wp_transfer += $item->wp_transfer;
                                $total_wp_credit += $item->wp_credit;
                            ?>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <td>รวมทั้งหมด</td>
                        <td>{{ number_format($total_cash, 2) }}</td>
                        <td>{{ number_format($total_transfer, 2) }}</td>
                        <td>{{ number_format($total_credit, 2) }}</td>

                        <td>{{ number_format($total_fb_cash, 2) }}</td>
                        <td>{{ number_format($total_fb_transfer, 2) }}</td>
                        <td>{{ number_format($total_fb_credit, 2) }}</td>

                        <td>{{ number_format($total_wp_cash, 2) }}</td>
                        <td>{{ number_format($total_wp_transfer, 2) }}</td>
                        <td>{{ number_format($total_wp_credit, 2) }}</td>
                    </tfoot>
                </table>
            </p>
        </main>
    </body>
</html>


