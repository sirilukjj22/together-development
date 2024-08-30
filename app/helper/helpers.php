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

        $html .= '<div class="pagination" style="white-space: nowrap;">
                    <a href="#" onclick="getPage('.($currentPage == 1 ? 1 : $currentPage - 1).', '.$perPage.', '."'$table'".')" class="r-l-md">&laquo;</a>';
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
          $html .= '<a href="#" onclick="getPage('.($data->total() > 10 ? $currentPage + 1 : 1).', '.$perPage.', '."'$table'".')" class="r-r-md">&raquo;</a>
                  </div>';

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

        $html .= '<div class="pagination" style="white-space: nowrap;">
                    <a href="#" onclick="getPageTax('.($currentPage == 1 ? 1 : $currentPage - 1).', '.$perPage.', '."'$table'".')" class="r-l-md">&laquo;</a>';
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
          $html .= '<a href="#" onclick="getPageTax('.($data->total() > 10 ? $currentPage + 1 : 1).', '.$perPage.', '."'$table'".')" class="r-r-md">&raquo;</a>
                  </div>';

        return $html;
    }
?>
