// Per Page
function search_per_page($page, $table_name, $link) {
    var perPage = $('#search-per-page').val();

    $.ajax({
        type:   "GET",
        url:    ""+$link+"?page="+$page+"&perPage="+perPage+"#"+$table_name+"",
        datatype:   "JSON",
        async:  false,
        success: function(response) {
            window.location.href = ""+$link+"?page="+$page+"&perPage="+perPage+"#sms";
        }
    });
}

// Showing Entries
function showingEntriesSearch($total) {
    var total = $total;
    var currentPage = $('#get-page').val();
    var perPage = $('#search-per-page').val();

    var from = (currentPage - 1) * perPage + 1;
    var to = Math.min(currentPage * perPage, total);

    var html = '';

    html += 'Showing '+ from +' to '+ to +' of '+ total +' entries';

    return html;
}

// Paginate 
function paginateSearch($total, $link) 
{
    var total = $total;
    var currentPage = $('#get-page').val();
    var perPage = $('#search-per-page').val();
    var previousPageUrl = currentPage;
    var nextPageUrl = currentPage;
    var html = '';
    console.log(total);
    

    if (currentPage > 1) {
        previousPageUrl = currentPage - 1;
    }

    if (currentPage < total) {
        nextPageUrl = currentPage + 1;
    }

    html += '<div class="pagination" style="white-space: nowrap;">'+
                '<a href="'+$link+'?page='+previousPageUrl+'&perPage='+perPage+'#sms" class="r-l-md">&laquo;</a>';
                    for($i=1;$i<=Math.ceil(total / 10);$i++)
                    {
                        if (currentPage == $i) {
                            html += '<a class="active" href="'+$link+'?page='+currentPage+'&perPage='+perPage+'#sms">'+$i+'</a>';
                        } else {
                            html += '<a class="" href="'+$link+'?page='+currentPage+'&perPage='+perPage+'#sms">'+$i+'</a>';
                        }
                    }
        html += '<a href="'+$link+'?page='+nextPageUrl+'&perPage='+perPage+'#sms" class="r-r-md">&raquo;</a>'+
                '</div>';
                
    return html;
}