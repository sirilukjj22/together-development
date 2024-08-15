<?php
    function showingEntriesTable($data_sms)
    {
        $total = $data_sms->total();
        $currentPage = $data_sms->currentPage();
        $perPage = $data_sms->perPage();
        
        $from = ($currentPage - 1) * $perPage + 1;
        $to = min($currentPage * $perPage, $total);

        $html = '';

        $html .= 'Showing '.$from .' to '. $to .' of '. $total .' entries';

        return $html;
    }

    function paginateTable($data_sms) 
    {
        $html = '';

        $html .= '<div class="pagination" style="white-space: nowrap;">
                    <a href="'.$data_sms->previousPageUrl().'#sms" class="r-l-md">&laquo;</a>';
                        for($i=1;$i<=$data_sms->lastPage();$i++)
                        {
                            if ($_GET["page"] == $i || empty($_GET["page"]) && $i == 1) {
                                $html .= '<a class="active" href="'.$data_sms->url($i).'">'.$i.'</a>';
                            } else {
                                $html .= '<a class="" href="'.$data_sms->url($i).'">'.$i.'</a>';
                            }
                        }
          $html .= '<a href="'.$data_sms->nextPageUrl().'#sms" class="r-r-md">&raquo;</a>
                  </div>';
                  
        return $html;
    }
?>

