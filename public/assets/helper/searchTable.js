// Per Page
function searchPerPage($table_name, $link) {
    var perPage = $('#search-per-page-'+$table_name).val();

    $.ajax({
        type:   "GET",
        url:    ""+$link+"?perPage="+perPage+"&table="+$table_name+"#"+$table_name+"",
        datatype:   "JSON",
        async:  false,
        success: function(response) {
            window.location.href = ""+$link+"?perPage="+perPage+"&table="+$table_name+"#"+$table_name+"";
        }
    });
}

// Showing Entries
function showingEntriesSearch($total, $table_name) {
    var total = $total;
    var currentPage = 1;
    var perPage = parseInt($('#search-per-page-'+$table_name).val());
    var html = '';

    if (total > 0) {
        var from = (currentPage - 1) * perPage + 1;
    } else {
        var from = 0;
    }
    var to = Math.min(currentPage * perPage, total);

    html += 'Showing '+ from +' to '+ to +' of '+ total +' entries';

    return html;
}

// Paginate 
function paginateSearch($total, $table, $link) 
{
    var total = $total;
    var currentPage = 1;
    var perPage = parseInt($('#search-per-page-'+$table).val());
    var previousPageUrl = currentPage;
    var nextPageUrl = currentPage;
    var html = '';
    

    if (currentPage > 1) {
        previousPageUrl = currentPage - 1;
    }

    if (currentPage < total) {
        nextPageUrl = currentPage + 1;
    }

    html += '<div class="pagination" style="white-space: nowrap;">'+
                '<a href="'+$link+'?page='+previousPageUrl+'&perPage='+perPage+'&table='+$table+'#'+$table+'" class="r-l-md">&laquo;</a>';
                    if (total > 0) {
                        for($i=1;$i<=Math.ceil(total / 10);$i++)
                        {
                            if (currentPage == $i) {
                                html += '<a class="active" href="'+$link+'?page='+currentPage+'&perPage='+perPage+'&table='+$table+'#'+$table+'">'+$i+'</a>';
                            } else {
                                html += '<a class="" href="'+$link+'?page='+currentPage+'&perPage='+perPage+'&table='+$table+'#'+$table+'">'+$i+'</a>';
                            }
                        }
                    }
        html += '<a href="'+$link+'?page='+nextPageUrl+'&perPage='+perPage+'&table='+$table+'#'+$table+'" class="r-r-md">&raquo;</a>'+
                '</div>';
                
    return html;
}