// Showing Entries
function showingEntriesSearch($page, $total, $table_name) {
    var total = $total;
    var currentPage = parseInt($page);
    var perPage = parseInt($('#search-per-page-' + $table_name).val());
    var html = '';

    if (total > 0) {
        var from = (currentPage - 1) * perPage + 1;
    } else {
        var from = 0;
    }
    var to = Math.min(currentPage * perPage, total);

    html += 'Showing ' + from + ' to ' + to + ' of ' + total + ' entries';

    return html;
}

// Paginate 
function paginateSearch($total, $table, $link) {
    var total = $total;
    var currentPage = parseInt($('#currentPage-' + $table).val());
    var perPage = parseInt($('#search-per-page-' + $table).val());
    var previousPageUrl = currentPage;
    var nextPageUrl = currentPage;
    var table = $table;
    var html = '';    

    if (currentPage > 1) {
        previousPageUrl = currentPage - 1;
    }

    if (currentPage < total) {
        nextPageUrl = currentPage + 1;
    }

    html += '<div class="pagination" style="white-space: nowrap;">';
    if (currentPage <= 1) {
        html += '<a href="#" class="r-l-md">&laquo;</a>';
    } else {
        html += '<a href="#" class="r-l-md" onclick="getPage(' + previousPageUrl + ', ' + perPage + ', ' + "'" + table + "'" + ')">&laquo;</a>';
    }

    if (total > 0) {
        if (currentPage > 3)
        {
            html += '<a class="" href="#" onclick="getPage(1, ' + perPage + ', ' + "'" + table + "'" + ')">1</a>';

            // if (currentPage > 3)
            // {
                html += '<a class="" href="#">...</a>';
            // }
        }        

        for ($i = Math.max(1, currentPage - 2); $i <= Math.min(Math.ceil(total / perPage), currentPage + 2); $i++)
        {
            if ($i == currentPage)
            {
                html += '<a class="active" href="#" onclick="getPage(' + $i + ', ' + perPage + ', ' + "'" + table + "'" + ')">' + $i + '</a>';
            } else {
                html += '<a class="" href="#" onclick="getPage(' + $i + ', ' + perPage + ', ' + "'" + table + "'" + ')">'+ $i +'</a>';
            }
        }

        if (currentPage < Math.ceil(total / perPage) - 2) 
        {
            if (currentPage < Math.ceil(total / perPage) - 3)
            {
                html += '<a class="" href="#">...</a>';
            }
            html += '<a href="#" onclick="getPage(' + Math.ceil(total / perPage) + ', ' + perPage + ', ' + "'" + table + "'" + ')">'+ Math.ceil(total / perPage) +'</a>';
        }
    }

    if (currentPage >= Math.ceil(total / perPage)) {
        html += '<a href="#" class="r-r-md">&raquo;</a>';
    } else {
        html += '<a href="#" class="r-r-md" onclick="getPage(' + nextPageUrl + ', ' + perPage + ', ' + "'" + table + "'" + ')">&raquo;</a>';
    }
    html += '</div>';

    return html;
}

function getPage(page, perPage, table_n) {
    var table_name = table_n + 'Table';
    var filter_by = $('#filter-by').val();
    var dateString = $('#combined-selected-box').val();
    var type = $('#search-status').val();
    var account = $('#into_account').val();
    var total = parseInt($('#get-total-' + table_n).val());
    var getUrl = window.location.pathname;

    $('#currentPage-' + table_n).val(page);

    $('#' + table_name).DataTable().destroy();
        var table = $('#' + table_name).dataTable({
            searching: false,
            paging: false,
            info: false,
            ajax: {
                url: '/sms-paginate-table',
                type: 'POST',
                dataType: "json",
                cache: false,
                data: {
                    page: page,
                    perPage: perPage,
                    table_name: table_name,
                    filter_by: filter_by,
                    date: dateString,
                    status: type,
                    into_account: account
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            },
            columnDefs: [
                { targets: [0, 1, 2, 4, 5, 6, 7, 8, 9], className: 'dt-center td-content-center' },
                { targets: [3], className: 'text-start' },
            ],
            order: [0, 'asc'],
            responsive: {
                details: {
                    type: 'column',
                    target: 'tr'
                }
            },
            columns: [
                { data: 'number' },
                { data: 'date' },
                { data: 'time' },
                { data: 'transfer_bank' },
                { data: 'into_account' },
                { data: 'amount' },
                { data: 'remark' },
                { data: 'revenue_name' },
                { data: 'date_into' },
                { data: 'btn_action' },
            ],
    
        });

    $('#' + table_n + '-paginate').children().remove().end();
    $('#' + table_n + '-showingEntries').text(showingEntriesSearch(page, total, table_n));
    $('#' + table_n + '-paginate').append(paginateSearch(total, table_n, getUrl));

}