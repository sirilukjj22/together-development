
// Showing Entries
function showingEntriesSearch($total, $table_name) {
    var total = $total;
    var currentPage = 1;
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

    html += '<div class="pagination" style="white-space: nowrap;">' +
        '<a href="#" class="r-l-md" onclick="getPage(' + previousPageUrl + ', ' + perPage + ', ' + "'" + table + "'" + ')">&laquo;</a>';
    if (total > 0) {
        for ($i = 1; $i <= Math.ceil(total / perPage); $i++) {
            if (currentPage == $i) {
                html += '<a class="active" href="#" onclick="getPage(' + $i + ', ' + perPage + ', ' + "'" + table + "'" + ')">' + $i + '</a>';
            } else {
                html += '<a class="" href="#" onclick="getPage(' + $i + ', ' + perPage + ', ' + "'" + table + "'" + ')">' + $i + '</a>';
            }
        }
    }

    html += '<a href="#" class="r-r-md" onclick="getPage(' + nextPageUrl + ', ' + perPage + ', ' + "'" + table + "'" + ')">&raquo;</a>' +
        '</div>';

    return html;
}

function getPage(page, perPage, table_n) {
    var table_name = table_n + 'Table';
    var filter_by = $('#filter-by').val();
    var day = $('#input-search-day').val();
    var month = $('#input-search-month').val();
    var year = $('#input-search-year').val();
    var month_to = $('#input-search-month-to').val();
    var type = $('#status').val();
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
            url: 'sms-paginate-table',
            type: 'POST',
            dataType: "json",
            cache: false,
            data: {
                page: page,
                perPage: perPage,
                table_name: table_name,
                filter_by: filter_by,
                day: day,
                month: month,
                year: year,
                month_to: month_to,
                status: type,
                into_account: account
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        },
        columnDefs: [
            { targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], className: 'dt-center td-content-center' },
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
    $('#' + table_n + '-showingEntries').text(showingEntriesSearch(total, table_n));
    $('#' + table_n + '-paginate').append(paginateSearch(total, table_n, getUrl));

}