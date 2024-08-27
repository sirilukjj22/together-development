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
console.log(perPage);

    if (currentPage > 1) {
        previousPageUrl = currentPage - 1;
    }

    if (currentPage < total) {
        nextPageUrl = currentPage + 1;
    }

    html += '<div class="pagination" style="white-space: nowrap;">';
    html += '<a href="#" class="r-l-md" onclick="getPage(' + previousPageUrl + ', ' + perPage + ', ' + "'" + table + "'" + ')">&laquo;</a>';
    if (total > 0) {
        if (currentPage > 3)
        {
            html += '<a class="" href="#" onclick="getPage(1, ' + perPage + ', ' + "'" + table + "'" + ')">1</a>';

            // if (currentPage > 3)
            // {
                html += '<a class="" href="#">...</a>';
            // }
        }

        for ($i = Math.max(1, currentPage - 1); $i <= Math.min(Math.ceil(total / perPage), currentPage + 2); $i++)
        {
            console.log($i);

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
    html += '<a href="#" class="r-r-md" onclick="getPage(' + nextPageUrl + ', ' + perPage + ', ' + "'" + table + "'" + ')">&raquo;</a>';
    html += '</div>';

    return html;
}

function getPage(page, perPage, table_n) {
    var table_name = table_n + 'Table';
    var getUrl = window.location.pathname;
    var type = $('#status').val();
    var total = parseInt($('#get-total-' + table_n).val());

    $('#currentPage-' + table_n).val(page);

    $('#' + table_name).DataTable().destroy();
    if (table_n == "guest") {
        var table = $('#' + table_name).dataTable({
            searching: false,
            paging: false,
            info: false,
            ajax: {
                url: '/guest-paginate-table',
                type: 'POST',
                dataType: "json",
                cache: false,
                data: {
                    page: page,
                    perPage: perPage,
                    table_name: table_name,
                    status: type,
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            },
            columnDefs: [
                { targets: [0, 1, 2, 3, 4, 5], className: 'dt-center td-content-center' },
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
                { data: 'Profile_ID' },
                { data: 'name' },
                { data: 'Booking_Channel' },
                { data: 'status' },
                { data: 'btn_action' },
            ],

        });
    }

    $('#' + table_n + '-paginate').children().remove().end();
    $('#' + table_n + '-showingEntries').text(showingEntriesSearch(page, total, table_n));
    $('#' + table_n + '-paginate').append(paginateSearch(total, table_n, getUrl));

}
