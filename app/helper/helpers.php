<?php
    function showingEntriesTable($data, $table)
    {
        $total = $data->total();
        $currentPage = $data->currentPage();
        $perPage = !empty($_GET['table']) && @$_GET['table'] == $table ? $_GET['perPage'] : 10;

        $from = ($currentPage - 1) * $perPage + 1;
        $to = min($currentPage * $perPage, $total);

        $html = '';

        $html .= 'Showing '.$from .' to '. $to .' of '. $total .' entries';

        return $html;
    }

    function paginateTable($data, $table)
    {
        $currentPage = 1;
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $num = 0;
        $html = '';

        $html .= '<div class="pagination" style="white-space: nowrap;">';
        if ($currentPage <= 1) {
            $html .= '<a href="#" class="r-l-md">&laquo;</a>';
        } else {
            $html .= '<a href="#" onclick="getPage('.($currentPage == 1 ? 1 : $currentPage - 1).', '.$perPage.', '."'$table'".')" class="r-l-md">&laquo;</a>';
        }
                    if ($data->total() > 0) {
                        if ($currentPage > 3)
                        {
                            $html .= '<a class="" href="#" onclick="getPage(1, '.$perPage.', '."'$table'".')">1</a>';

                            if ($currentPage > 4)
                            {
                                $html .= '<a class="" href="#">...</a>';
                            }
                        }

                        for ($i = max(1, $currentPage - 2); $i <= min($data->lastPage(), $currentPage + 2); $i++)
                        {
                            if ($currentPage == $i) {
                                $html .= '<a class="active" href="#" onclick="getPage('.$i.', '.$perPage.', '."'$table'".')">'.$i.'</a>';
                            } else {
                                $html .= '<a class="" href="#" onclick="getPage('.$i.', '.$perPage.', '."'$table'".')">'.$i.'</a>';
                            }
                        }

                        if ($currentPage < $data->lastPage() - 2)
                        {
                            if ($currentPage < $data->lastPage() - 3)
                            {
                                $html .= '<a class="" href="#">...</a>';
                            }
                            $html .= '<a href="#" onclick="getPage(' .$data->lastPage(). ', ' . $perPage . ', '."'$table'".')">'.$data->lastPage().'</a>';
                        }
                    }
        if ($currentPage >= $data->lastPage()) {
            $html .= '<a href="#" class="r-r-md">&raquo;</a>';
        } else {
            $html .= '<a href="#" onclick="getPage('.($data->total() > 10 ? $currentPage + 1 : 1).', '.$perPage.', '."'$table'".')" class="r-r-md">&raquo;</a>';
        }
            $html .= '</div>';

        return $html;
    }

    ## หาวันสุดท้ายของเดือน
    function dayLast($month, $year){

        if($month=='01' || $month=='03' || $month=='05' || $month=='07' || $month=='08' || $month=='10' || $month=='12')
        {
            $EOM='31';
        } elseif($month=='02') {
            if($year%4==0) {
                $EOM='29';
            } else {
                $EOM='28';
            }
        } else {
            $EOM='30';
        }

        return $EOM;
    }

    function formatMonthName($month)
    {
        $name = 'none';

        if ($month == '1' || $month == '01') {
            $name = "January";
        } elseif ($month == '2' || $month == '02') {
            $name = "February";
        } elseif ($month == '3' || $month == '03') {
            $name = "March";
        } elseif ($month == '4' || $month == '04') {
            $name = "April";
        } elseif ($month == '5' || $month == '05') {
            $name = "May";
        } elseif ($month == '6' || $month == '06') {
            $name = "June";
        } elseif ($month == '7' || $month == '07') {
            $name = "July";
        } elseif ($month == '8' || $month == '08') {
            $name = "August";
        } elseif ($month == '9' || $month == '09') {
            $name = "September";
        } elseif ($month == '10' || $month == '10') {
            $name = "October";
        } elseif ($month == '11' || $month == '11') {
            $name = "November";
        } elseif ($month == '12' || $month == '12') {
            $name = "December";
        }

        return $name;
    }


    function formatPhoneNumber($number) {
        $number = preg_replace('/\D/', '', $number); // ลบตัวอักษรที่ไม่ใช่ตัวเลขออก
        $formatted = '';

        if (strlen($number) > 0) {
            $formatted .= substr($number, 0, 3); // xxx
        }
        if (strlen($number) > 3) {
            $formatted .= '-' . substr($number, 3, 3); // xxx-xxx
        }
        if (strlen($number) > 6) {
            $formatted .= '-' . substr($number, 6); // xxx-xxx-xxxx
        }

        return $formatted;
    }

    function formatIdCard($value) {

        $value = preg_replace('/\D/', '', $value); // Remove non-numeric characters
        $formattedValue = '';

        if (strlen($value) > 0) {
            $formattedValue .= substr($value, 0, 1); // 1
        }
        if (strlen($value) > 1) {
            $formattedValue .= '-' . substr($value, 1, 4); // 1-2345
        }
        if (strlen($value) > 5) {
            $formattedValue .= '-' . substr($value, 5, 5); // 1-2345-67890
        }
        if (strlen($value) > 10) {
            $formattedValue .= '-' . substr($value, 10, 2); // 1-2345-67890-34
        }
        if (strlen($value) > 12) {
            $formattedValue .= '-' . substr($value, 12, 1); // 1-2345-67890-34-0
        }

        return $formattedValue;
    }
    //------------------------------------------guest Tax----------------------------------
    function showingEntriesTableTax($data, $table)
    {
        $total = $data->total();
        $currentPage = $data->currentPage();
        $perPage = !empty($_GET['table']) && @$_GET['table'] == $table ? $_GET['perPage'] : 10;

        $from = ($currentPage - 1) * $perPage + 1;
        $to = min($currentPage * $perPage, $total);

        $html = '';

        $html .= 'Showing '.$from .' to '. $to .' of '. $total .' entries';

        return $html;
    }

    function paginateTableTax($data, $table)
    {
        $currentPage = 1;
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $num = 0;
        $html = '';

        $html .= '<div class="pagination" style="white-space: nowrap;">';
        $html .= '<a href="#" onclick="getPageTax('.($currentPage == 1 ? 1 : $currentPage - 1).', '.$perPage.', '."'$table'".')" class="r-l-md">&laquo;</a>';
                    if ($data->total() > 0) {
                        if ($currentPage > 3)
                        {
                            $html .= '<a class="" href="#" onclick="getPageTax(1, '.$perPage.', '."'$table'".')">1</a>';

                            if ($currentPage > 4)
                            {
                                $html .= '<a class="" href="#">...</a>';
                            }
                        }

                        for ($i = max(1, $currentPage - 2); $i <= min($data->lastPage(), $currentPage + 2); $i++)
                        {
                            if ($currentPage == $i) {
                                $html .= '<a class="active" href="#" onclick="getPageTax('.$i.', '.$perPage.', '."'$table'".')">'.$i.'</a>';
                            } else {
                                $html .= '<a class="" href="#" onclick="getPageTax('.$i.', '.$perPage.', '."'$table'".')">'.$i.'</a>';
                            }
                        }

                        if ($currentPage < $data->lastPage() - 2)
                        {
                            if ($currentPage < $data->lastPage() - 3)
                            {
                                $html .= '<a class="" href="#">...</a>';
                            }
                            $html .= '<a href="#" onclick="getPageTax(' .$data->lastPage(). ', ' . $perPage . ', '."'$table'".')">'.$data->lastPage().'</a>';
                        }
                    }
            $html .= '<a href="#" onclick="getPageTax('.($data->total() > 10 ? $currentPage + 1 : 1).', '.$perPage.', '."'$table'".')" class="r-r-md">&raquo;</a>';
          $html .= '</div>';

        return $html;
    }
    //visit
    function showingEntriesTableVisit($data, $table)
    {
        $total = $data->total();
        $currentPage = $data->currentPage();
        $perPage = !empty($_GET['table']) && @$_GET['table'] == $table ? $_GET['perPage'] : 10;

        $from = ($currentPage - 1) * $perPage + 1;
        $to = min($currentPage * $perPage, $total);

        $html = '';

        $html .= 'Showing '.$from .' to '. $to .' of '. $total .' entries';

        return $html;
    }

    function paginateTableVisit($data, $table)
    {
        $currentPage = 1;
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $num = 0;
        $html = '';

        $html .= '<div class="pagination" style="white-space: nowrap;">
                    <a href="#" onclick="getPageVisit('.($currentPage == 1 ? 1 : $currentPage - 1).', '.$perPage.', '."'$table'".')" class="r-l-md">&laquo;</a>';
                    if ($data->total() > 0) {
                        if ($currentPage > 3)
                        {
                            $html .= '<a class="" href="#" onclick="getPageVisit(1, '.$perPage.', '."'$table'".')">1</a>';

                            if ($currentPage > 4)
                            {
                                $html .= '<a class="" href="#">...</a>';
                            }
                        }

                        for ($i = max(1, $currentPage - 2); $i <= min($data->lastPage(), $currentPage + 2); $i++)
                        {
                            if ($currentPage == $i) {
                                $html .= '<a class="active" href="#" onclick="getPageVisit('.$i.', '.$perPage.', '."'$table'".')">'.$i.'</a>';
                            } else {
                                $html .= '<a class="" href="#" onclick="getPageVisit('.$i.', '.$perPage.', '."'$table'".')">'.$i.'</a>';
                            }
                        }

                        if ($currentPage < $data->lastPage() - 2)
                        {
                            if ($currentPage < $data->lastPage() - 3)
                            {
                                $html .= '<a class="" href="#">...</a>';
                            }
                            $html .= '<a href="#" onclick="getPageVisit(' .$data->lastPage(). ', ' . $perPage . ', '."'$table'".')">'.$data->lastPage().'</a>';
                        }
                    }
          $html .= '<a href="#" onclick="getPageVisit('.($data->total() > 10 ? $currentPage + 1 : 1).', '.$perPage.', '."'$table'".')" class="r-r-md">&raquo;</a>
                  </div>';

        return $html;
    }
    //Contact
    function showingEntriesTableContact($data, $table)
    {
        $total = $data->total();
        $currentPage = $data->currentPage();
        $perPage = !empty($_GET['table']) && @$_GET['table'] == $table ? $_GET['perPage'] : 10;

        $from = ($currentPage - 1) * $perPage + 1;
        $to = min($currentPage * $perPage, $total);

        $html = '';

        $html .= 'Showing '.$from .' to '. $to .' of '. $total .' entries';

        return $html;
    }

    function paginateTableContact($data, $table)
    {
        $currentPage = 1;
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $num = 0;
        $html = '';

        $html .= '<div class="pagination" style="white-space: nowrap;">
                    <a href="#" onclick="getPageContact('.($currentPage == 1 ? 1 : $currentPage - 1).', '.$perPage.', '."'$table'".')" class="r-l-md">&laquo;</a>';
                    if ($data->total() > 0) {
                        if ($currentPage > 3)
                        {
                            $html .= '<a class="" href="#" onclick="getPageContact(1, '.$perPage.', '."'$table'".')">1</a>';

                            if ($currentPage > 4)
                            {
                                $html .= '<a class="" href="#">...</a>';
                            }
                        }

                        for ($i = max(1, $currentPage - 2); $i <= min($data->lastPage(), $currentPage + 2); $i++)
                        {
                            if ($currentPage == $i) {
                                $html .= '<a class="active" href="#" onclick="getPageContact('.$i.', '.$perPage.', '."'$table'".')">'.$i.'</a>';
                            } else {
                                $html .= '<a class="" href="#" onclick="getPageContact('.$i.', '.$perPage.', '."'$table'".')">'.$i.'</a>';
                            }
                        }

                        if ($currentPage < $data->lastPage() - 2)
                        {
                            if ($currentPage < $data->lastPage() - 3)
                            {
                                $html .= '<a class="" href="#">...</a>';
                            }
                            $html .= '<a href="#" onclick="getPageContact(' .$data->lastPage(). ', ' . $perPage . ', '."'$table'".')">'.$data->lastPage().'</a>';
                        }
                    }
          $html .= '<a href="#" onclick="getPageContact('.($data->total() > 10 ? $currentPage + 1 : 1).', '.$perPage.', '."'$table'".')" class="r-r-md">&raquo;</a>
                  </div>';

        return $html;
    }

    //Log
    function showingEntriesTableLog($data, $table)
    {
        $total = $data->total();
        $currentPage = $data->currentPage();
        $perPage = !empty($_GET['table']) && @$_GET['table'] == $table ? $_GET['perPage'] : 10;

        $from = ($currentPage - 1) * $perPage + 1;
        $to = min($currentPage * $perPage, $total);

        $html = '';

        $html .= 'Showing '.$from .' to '. $to .' of '. $total .' entries';

        return $html;
    }

    function paginateTableLog($data, $table)
    {
        $currentPage = 1;
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $num = 0;
        $html = '';

        $html .= '<div class="pagination" style="white-space: nowrap;">
                    <a href="#" onclick="getPageLog('.($currentPage == 1 ? 1 : $currentPage - 1).', '.$perPage.', '."'$table'".')" class="r-l-md">&laquo;</a>';
                    if ($data->total() > 0) {
                        if ($currentPage > 3)
                        {
                            $html .= '<a class="" href="#" onclick="getPageLog(1, '.$perPage.', '."'$table'".')">1</a>';

                            if ($currentPage > 4)
                            {
                                $html .= '<a class="" href="#">...</a>';
                            }
                        }

                        for ($i = max(1, $currentPage - 2); $i <= min($data->lastPage(), $currentPage + 2); $i++)
                        {
                            if ($currentPage == $i) {
                                $html .= '<a class="active" href="#" onclick="getPageLog('.$i.', '.$perPage.', '."'$table'".')">'.$i.'</a>';
                            } else {
                                $html .= '<a class="" href="#" onclick="getPageLog('.$i.', '.$perPage.', '."'$table'".')">'.$i.'</a>';
                            }
                        }

                        if ($currentPage < $data->lastPage() - 2)
                        {
                            if ($currentPage < $data->lastPage() - 3)
                            {
                                $html .= '<a class="" href="#">...</a>';
                            }
                            $html .= '<a href="#" onclick="getPageLog(' .$data->lastPage(). ', ' . $perPage . ', '."'$table'".')">'.$data->lastPage().'</a>';
                        }
                    }
          $html .= '<a href="#" onclick="getPageLog('.($data->total() > 10 ? $currentPage + 1 : 1).', '.$perPage.', '."'$table'".')" class="r-r-md">&raquo;</a>
                  </div>';

        return $html;
    }

    //Proposal
    //-----Pending
    function showingEntriesTablePending($data, $table)
    {
        $total = $data->total();
        $currentPage = $data->currentPage();
        $perPage = !empty($_GET['table']) && @$_GET['table'] == $table ? $_GET['perPage'] : 10;

        $from = ($currentPage - 1) * $perPage + 1;
        $to = min($currentPage * $perPage, $total);

        $html = '';

        $html .= 'Showing '.$from .' to '. $to .' of '. $total .' entries';

        return $html;
    }

    function paginateTablePending($data, $table)
    {
        $currentPage = 1;
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $num = 0;
        $html = '';

        $html .= '<div class="pagination" style="white-space: nowrap;">
                    <a href="#" onclick="getPagePending('.($currentPage == 1 ? 1 : $currentPage - 1).', '.$perPage.', '."'$table'".')" class="r-l-md">&laquo;</a>';
                    if ($data->total() > 0) {
                        if ($currentPage > 3)
                        {
                            $html .= '<a class="" href="#" onclick="getPagePending(1, '.$perPage.', '."'$table'".')">1</a>';

                            if ($currentPage > 4)
                            {
                                $html .= '<a class="" href="#">...</a>';
                            }
                        }

                        for ($i = max(1, $currentPage - 2); $i <= min($data->lastPage(), $currentPage + 2); $i++)
                        {
                            if ($currentPage == $i) {
                                $html .= '<a class="active" href="#" onclick="getPagePending('.$i.', '.$perPage.', '."'$table'".')">'.$i.'</a>';
                            } else {
                                $html .= '<a class="" href="#" onclick="getPagePending('.$i.', '.$perPage.', '."'$table'".')">'.$i.'</a>';
                            }
                        }

                        if ($currentPage < $data->lastPage() - 2)
                        {
                            if ($currentPage < $data->lastPage() - 3)
                            {
                                $html .= '<a class="" href="#">...</a>';
                            }
                            $html .= '<a href="#" onclick="getPagePending(' .$data->lastPage(). ', ' . $perPage . ', '."'$table'".')">'.$data->lastPage().'</a>';
                        }
                    }
          $html .= '<a href="#" onclick="getPagePending('.($data->total() > 10 ? $currentPage + 1 : 1).', '.$perPage.', '."'$table'".')" class="r-r-md">&raquo;</a>
                  </div>';

        return $html;
    }
    //--------Awaiting
    function showingEntriesTableAwaiting($data, $table)
    {
        $total = $data->total();
        $currentPage = $data->currentPage();
        $perPage = !empty($_GET['table']) && @$_GET['table'] == $table ? $_GET['perPage'] : 10;

        $from = ($currentPage - 1) * $perPage + 1;
        $to = min($currentPage * $perPage, $total);

        $html = '';

        $html .= 'Showing '.$from .' to '. $to .' of '. $total .' entries';

        return $html;
    }

    function paginateTableAwaiting($data, $table)
    {
        $currentPage = 1;
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $num = 0;
        $html = '';

        $html .= '<div class="pagination" style="white-space: nowrap;">
                    <a href="#" onclick="getPageAwaiting('.($currentPage == 1 ? 1 : $currentPage - 1).', '.$perPage.', '."'$table'".')" class="r-l-md">&laquo;</a>';
                    if ($data->total() > 0) {
                        if ($currentPage > 3)
                        {
                            $html .= '<a class="" href="#" onclick="getPageAwaiting(1, '.$perPage.', '."'$table'".')">1</a>';

                            if ($currentPage > 4)
                            {
                                $html .= '<a class="" href="#">...</a>';
                            }
                        }

                        for ($i = max(1, $currentPage - 2); $i <= min($data->lastPage(), $currentPage + 2); $i++)
                        {
                            if ($currentPage == $i) {
                                $html .= '<a class="active" href="#" onclick="getPageAwaiting('.$i.', '.$perPage.', '."'$table'".')">'.$i.'</a>';
                            } else {
                                $html .= '<a class="" href="#" onclick="getPageAwaiting('.$i.', '.$perPage.', '."'$table'".')">'.$i.'</a>';
                            }
                        }

                        if ($currentPage < $data->lastPage() - 2)
                        {
                            if ($currentPage < $data->lastPage() - 3)
                            {
                                $html .= '<a class="" href="#">...</a>';
                            }
                            $html .= '<a href="#" onclick="getPageAwaiting(' .$data->lastPage(). ', ' . $perPage . ', '."'$table'".')">'.$data->lastPage().'</a>';
                        }
                    }
          $html .= '<a href="#" onclick="getPageAwaiting('.($data->total() > 10 ? $currentPage + 1 : 1).', '.$perPage.', '."'$table'".')" class="r-r-md">&raquo;</a>
                  </div>';

        return $html;
    }
    //------------getPageApproved
    function showingEntriesTableApproved($data, $table)
    {
        $total = $data->total();
        $currentPage = $data->currentPage();
        $perPage = !empty($_GET['table']) && @$_GET['table'] == $table ? $_GET['perPage'] : 10;

        $from = ($currentPage - 1) * $perPage + 1;
        $to = min($currentPage * $perPage, $total);

        $html = '';

        $html .= 'Showing '.$from .' to '. $to .' of '. $total .' entries';

        return $html;
    }

    function paginateTableApproved($data, $table)
    {
        $currentPage = 1;
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $num = 0;
        $html = '';

        $html .= '<div class="pagination" style="white-space: nowrap;">
                    <a href="#" onclick="getPageApproved('.($currentPage == 1 ? 1 : $currentPage - 1).', '.$perPage.', '."'$table'".')" class="r-l-md">&laquo;</a>';
                    if ($data->total() > 0) {
                        if ($currentPage > 3)
                        {
                            $html .= '<a class="" href="#" onclick="getPageApproved(1, '.$perPage.', '."'$table'".')">1</a>';

                            if ($currentPage > 4)
                            {
                                $html .= '<a class="" href="#">...</a>';
                            }
                        }

                        for ($i = max(1, $currentPage - 2); $i <= min($data->lastPage(), $currentPage + 2); $i++)
                        {
                            if ($currentPage == $i) {
                                $html .= '<a class="active" href="#" onclick="getPageApproved('.$i.', '.$perPage.', '."'$table'".')">'.$i.'</a>';
                            } else {
                                $html .= '<a class="" href="#" onclick="getPageApproved('.$i.', '.$perPage.', '."'$table'".')">'.$i.'</a>';
                            }
                        }

                        if ($currentPage < $data->lastPage() - 2)
                        {
                            if ($currentPage < $data->lastPage() - 3)
                            {
                                $html .= '<a class="" href="#">...</a>';
                            }
                            $html .= '<a href="#" onclick="getPageApproved(' .$data->lastPage(). ', ' . $perPage . ', '."'$table'".')">'.$data->lastPage().'</a>';
                        }
                    }
          $html .= '<a href="#" onclick="getPageApproved('.($data->total() > 10 ? $currentPage + 1 : 1).', '.$perPage.', '."'$table'".')" class="r-r-md">&raquo;</a>
                  </div>';

        return $html;
    }
    //------------getPageApproved
    function showingEntriesTableGenerate($data, $table)
    {
        $total = $data->total();
        $currentPage = $data->currentPage();
        $perPage = !empty($_GET['table']) && @$_GET['table'] == $table ? $_GET['perPage'] : 10;

        $from = ($currentPage - 1) * $perPage + 1;
        $to = min($currentPage * $perPage, $total);

        $html = '';

        $html .= 'Showing '.$from .' to '. $to .' of '. $total .' entries';

        return $html;
    }
    function paginateTableGenerate($data, $table)
    {
        $currentPage = 1;
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $num = 0;
        $html = '';

        $html .= '<div class="pagination" style="white-space: nowrap;">
                    <a href="#" onclick="getPageGenerate('.($currentPage == 1 ? 1 : $currentPage - 1).', '.$perPage.', '."'$table'".')" class="r-l-md">&laquo;</a>';
                    if ($data->total() > 0) {
                        if ($currentPage > 3)
                        {
                            $html .= '<a class="" href="#" onclick="getPageGenerate(1, '.$perPage.', '."'$table'".')">1</a>';

                            if ($currentPage > 4)
                            {
                                $html .= '<a class="" href="#">...</a>';
                            }
                        }

                        for ($i = max(1, $currentPage - 2); $i <= min($data->lastPage(), $currentPage + 2); $i++)
                        {
                            if ($currentPage == $i) {
                                $html .= '<a class="active" href="#" onclick="getPageGenerate('.$i.', '.$perPage.', '."'$table'".')">'.$i.'</a>';
                            } else {
                                $html .= '<a class="" href="#" onclick="getPageGenerate('.$i.', '.$perPage.', '."'$table'".')">'.$i.'</a>';
                            }
                        }

                        if ($currentPage < $data->lastPage() - 2)
                        {
                            if ($currentPage < $data->lastPage() - 3)
                            {
                                $html .= '<a class="" href="#">...</a>';
                            }
                            $html .= '<a href="#" onclick="getPageGenerate(' .$data->lastPage(). ', ' . $perPage . ', '."'$table'".')">'.$data->lastPage().'</a>';
                        }
                    }
          $html .= '<a href="#" onclick="getPageGenerate('.($data->total() > 10 ? $currentPage + 1 : 1).', '.$perPage.', '."'$table'".')" class="r-r-md">&raquo;</a>
                  </div>';

        return $html;
    }
    //----------------Reject-----------------
    function showingEntriesTableReject($data, $table)
    {
        $total = $data->total();
        $currentPage = $data->currentPage();
        $perPage = !empty($_GET['table']) && @$_GET['table'] == $table ? $_GET['perPage'] : 10;

        $from = ($currentPage - 1) * $perPage + 1;
        $to = min($currentPage * $perPage, $total);

        $html = '';

        $html .= 'Showing '.$from .' to '. $to .' of '. $total .' entries';

        return $html;
    }

    function paginateTableReject($data, $table)
    {
        $currentPage = 1;
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $num = 0;
        $html = '';

        $html .= '<div class="pagination" style="white-space: nowrap;">
                    <a href="#" onclick="getPageReject('.($currentPage == 1 ? 1 : $currentPage - 1).', '.$perPage.', '."'$table'".')" class="r-l-md">&laquo;</a>';
                    if ($data->total() > 0) {
                        if ($currentPage > 3)
                        {
                            $html .= '<a class="" href="#" onclick="getPageReject(1, '.$perPage.', '."'$table'".')">1</a>';

                            if ($currentPage > 4)
                            {
                                $html .= '<a class="" href="#">...</a>';
                            }
                        }

                        for ($i = max(1, $currentPage - 2); $i <= min($data->lastPage(), $currentPage + 2); $i++)
                        {
                            if ($currentPage == $i) {
                                $html .= '<a class="active" href="#" onclick="getPageReject('.$i.', '.$perPage.', '."'$table'".')">'.$i.'</a>';
                            } else {
                                $html .= '<a class="" href="#" onclick="getPageReject('.$i.', '.$perPage.', '."'$table'".')">'.$i.'</a>';
                            }
                        }

                        if ($currentPage < $data->lastPage() - 2)
                        {
                            if ($currentPage < $data->lastPage() - 3)
                            {
                                $html .= '<a class="" href="#">...</a>';
                            }
                            $html .= '<a href="#" onclick="getPageReject(' .$data->lastPage(). ', ' . $perPage . ', '."'$table'".')">'.$data->lastPage().'</a>';
                        }
                    }
          $html .= '<a href="#" onclick="getPageReject('.($data->total() > 10 ? $currentPage + 1 : 1).', '.$perPage.', '."'$table'".')" class="r-r-md">&raquo;</a>
                  </div>';

        return $html;
    }
    //-----------------Cancel-------------
    function showingEntriesTableCancel($data, $table)
    {
        $total = $data->total();
        $currentPage = $data->currentPage();
        $perPage = !empty($_GET['table']) && @$_GET['table'] == $table ? $_GET['perPage'] : 10;

        $from = ($currentPage - 1) * $perPage + 1;
        $to = min($currentPage * $perPage, $total);

        $html = '';

        $html .= 'Showing '.$from .' to '. $to .' of '. $total .' entries';

        return $html;
    }

    function paginateTableCancel($data, $table)
    {
        $currentPage = 1;
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $num = 0;
        $html = '';

        $html .= '<div class="pagination" style="white-space: nowrap;">
                    <a href="#" onclick="getPageCancel('.($currentPage == 1 ? 1 : $currentPage - 1).', '.$perPage.', '."'$table'".')" class="r-l-md">&laquo;</a>';
                    if ($data->total() > 0) {
                        if ($currentPage > 3)
                        {
                            $html .= '<a class="" href="#" onclick="getPageCancel(1, '.$perPage.', '."'$table'".')">1</a>';

                            if ($currentPage > 4)
                            {
                                $html .= '<a class="" href="#">...</a>';
                            }
                        }

                        for ($i = max(1, $currentPage - 2); $i <= min($data->lastPage(), $currentPage + 2); $i++)
                        {
                            if ($currentPage == $i) {
                                $html .= '<a class="active" href="#" onclick="getPageCancel('.$i.', '.$perPage.', '."'$table'".')">'.$i.'</a>';
                            } else {
                                $html .= '<a class="" href="#" onclick="getPageCancel('.$i.', '.$perPage.', '."'$table'".')">'.$i.'</a>';
                            }
                        }

                        if ($currentPage < $data->lastPage() - 2)
                        {
                            if ($currentPage < $data->lastPage() - 3)
                            {
                                $html .= '<a class="" href="#">...</a>';
                            }
                            $html .= '<a href="#" onclick="getPageCancel(' .$data->lastPage(). ', ' . $perPage . ', '."'$table'".')">'.$data->lastPage().'</a>';
                        }
                    }
          $html .= '<a href="#" onclick="getPageCancel('.($data->total() > 10 ? $currentPage + 1 : 1).', '.$perPage.', '."'$table'".')" class="r-r-md">&raquo;</a>
                  </div>';

        return $html;
    }
    //Log
    function showingEntriesTableLogDoc($data, $table)
    {
        $total = $data->total();
        $currentPage = $data->currentPage();
        $perPage = !empty($_GET['table']) && @$_GET['table'] == $table ? $_GET['perPage'] : 10;

        $from = ($currentPage - 1) * $perPage + 1;
        $to = min($currentPage * $perPage, $total);

        $html = '';

        $html .= 'Showing '.$from .' to '. $to .' of '. $total .' entries';

        return $html;
    }

    function paginateTableLogDoc($data, $table)
    {
        $currentPage = 1;
        $perPage = !empty($_GET['perPage']) ? $_GET['perPage'] : 10;
        $num = 0;
        $html = '';

        $html .= '<div class="pagination" style="white-space: nowrap;">
                    <a href="#" onclick="getPageLogDoc('.($currentPage == 1 ? 1 : $currentPage - 1).', '.$perPage.', '."'$table'".')" class="r-l-md">&laquo;</a>';
                    if ($data->total() > 0) {
                        if ($currentPage > 3)
                        {
                            $html .= '<a class="" href="#" onclick="getPageLogDoc(1, '.$perPage.', '."'$table'".')">1</a>';

                            if ($currentPage > 4)
                            {
                                $html .= '<a class="" href="#">...</a>';
                            }
                        }

                        for ($i = max(1, $currentPage - 2); $i <= min($data->lastPage(), $currentPage + 2); $i++)
                        {
                            if ($currentPage == $i) {
                                $html .= '<a class="active" href="#" onclick="getPageLogDoc('.$i.', '.$perPage.', '."'$table'".')">'.$i.'</a>';
                            } else {
                                $html .= '<a class="" href="#" onclick="getPageLogDoc('.$i.', '.$perPage.', '."'$table'".')">'.$i.'</a>';
                            }
                        }

                        if ($currentPage < $data->lastPage() - 2)
                        {
                            if ($currentPage < $data->lastPage() - 3)
                            {
                                $html .= '<a class="" href="#">...</a>';
                            }
                            $html .= '<a href="#" onclick="getPageLogDoc(' .$data->lastPage(). ', ' . $perPage . ', '."'$table'".')">'.$data->lastPage().'</a>';
                        }
                    }
          $html .= '<a href="#" onclick="getPageLogDoc('.($data->total() > 10 ? $currentPage + 1 : 1).', '.$perPage.', '."'$table'".')" class="r-r-md">&raquo;</a>
                  </div>';

        return $html;
    }
?>

