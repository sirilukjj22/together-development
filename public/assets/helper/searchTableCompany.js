// Showing Entries
function showingEntriesSearch($page, $total, $table_name)
{
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
function paginateSearch($total, $table, $link)
{
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

        for ($i = Math.max(1, currentPage - 2); $i <= Math.min(Math.ceil(total / perPage), currentPage + 2); $i++)
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

function getPage(page, perPage, table_n)
{
    var table_name = table_n + 'Table';
    var getUrl = window.location.pathname;
    var type = $('#status').val();
    var total = parseInt($('#get-total-' + table_n).val());

    $('#currentPage-' + table_n).val(page);

    $('#' + table_name).DataTable().destroy();
    if (table_n == "Company") {
        var table = $('#' + table_name).dataTable({
            searching: false,
            paging: false,
            info: false,
            ajax: {
                url: '/company-paginate-table',
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
                { targets: [0, 1, 3, 4, 5,6], className: 'dt-center td-content-center' },
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
                { data: 'Company_Name' },
                { data: 'Branch' },
                { data: 'Phone' },
                { data: 'Status' },
                { data: 'btn_action' },
            ],

        });
    }

    $('#' + table_n + '-paginate').children().remove().end();
    $('#' + table_n + '-showingEntries').text(showingEntriesSearch(page, total, table_n));
    $('#' + table_n + '-paginate').append(paginateSearch(total, table_n, getUrl));

}
///-------------------------------------------------------------
function showingEntriesSearchTax($page, $total, $table_name)
{
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
function paginateSearchTax($total, $table, $link)
{
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
    html += '<a href="#" class="r-l-md" onclick="getPageTax(' + previousPageUrl + ', ' + perPage + ', ' + "'" + table + "'" + ')">&laquo;</a>';
    if (total > 0) {
        if (currentPage > 3)
        {
            html += '<a class="" href="#" onclick="getPageTax(1, ' + perPage + ', ' + "'" + table + "'" + ')">1</a>';

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
                html += '<a class="active" href="#" onclick="getPageTax(' + $i + ', ' + perPage + ', ' + "'" + table + "'" + ')">' + $i + '</a>';
            } else {
                html += '<a class="" href="#" onclick="getPageTax(' + $i + ', ' + perPage + ', ' + "'" + table + "'" + ')">'+ $i +'</a>';
            }
        }

        if (currentPage < Math.ceil(total / perPage) - 2)
        {
            if (currentPage < Math.ceil(total / perPage) - 3)
            {
                html += '<a class="" href="#">...</a>';
            }
            html += '<a href="#" onclick="getPageTax(' + Math.ceil(total / perPage) + ', ' + perPage + ', ' + "'" + table + "'" + ')">'+ Math.ceil(total / perPage) +'</a>';
        }
    }
    html += '<a href="#" class="r-r-md" onclick="getPageTax(' + nextPageUrl + ', ' + perPage + ', ' + "'" + table + "'" + ')">&raquo;</a>';
    html += '</div>';

    return html;
}

function getPageTax(page, perPage, table_n)
{
    var table_name = table_n + 'Table';
    var getUrl = window.location.pathname;
    var type = $('#status').val();
    var total = parseInt($('#get-total-' + table_n).val());
    var guest_profile = $('#profile-'+ table_n).val();

    $('#currentPage-' + table_n).val(page);

    $('#' + table_name).DataTable().destroy();
    if (table_n == "company-Tax") {

        var table = $('#' + table_name).dataTable({
            searching: false,
            paging: false,
            info: false,
            ajax: {
                url: '/tax-company-paginate-table',
                type: 'POST',
                dataType: "json",
                cache: false,
                data: {
                    page: page,
                    perPage: perPage,
                    table_name: table_name,
                    status: type,
                    guest_profile:guest_profile,
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            },
            columnDefs: [
                { targets: [0, 1, 3,4,5], className: 'dt-center td-content-center' },
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
                { data: 'Profile_ID_TAX' },
                { data: 'Company/Individual' },
                { data: 'Branch' },
                { data: 'Status' },
                { data: 'Order' },
            ],

        });
    }
    $('#' + table_n + '-paginate').children().remove().end();
    $('#' + table_n + '-showingEntries').text(showingEntriesSearchTax(page, total, table_n));
    $('#' + table_n + '-paginate').append(paginateSearchTax(total, table_n, getUrl));

}
//---------visit------

function showingEntriesSearchVisit($page, $total, $table_name)
{
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
function paginateSearchVisit($total, $table, $link)
{
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
    html += '<a href="#" class="r-l-md" onclick="getPageVisit(' + previousPageUrl + ', ' + perPage + ', ' + "'" + table + "'" + ')">&laquo;</a>';
    if (total > 0) {
        if (currentPage > 3)
        {
            html += '<a class="" href="#" onclick="getPageVisit(1, ' + perPage + ', ' + "'" + table + "'" + ')">1</a>';

            // if (currentPage > 3)
            // {
                html += '<a class="" href="#">...</a>';
            // }
        }

        for ($i = Math.max(1, currentPage - 2); $i <= Math.min(Math.ceil(total / perPage), currentPage + 2); $i++)
        {
            console.log($i);

            if ($i == currentPage)
            {
                html += '<a class="active" href="#" onclick="getPageVisit(' + $i + ', ' + perPage + ', ' + "'" + table + "'" + ')">' + $i + '</a>';
            } else {
                html += '<a class="" href="#" onclick="getPageVisit(' + $i + ', ' + perPage + ', ' + "'" + table + "'" + ')">'+ $i +'</a>';
            }
        }

        if (currentPage < Math.ceil(total / perPage) - 2)
        {
            if (currentPage < Math.ceil(total / perPage) - 3)
            {
                html += '<a class="" href="#">...</a>';
            }
            html += '<a href="#" onclick="getPageVisit(' + Math.ceil(total / perPage) + ', ' + perPage + ', ' + "'" + table + "'" + ')">'+ Math.ceil(total / perPage) +'</a>';
        }
    }
    html += '<a href="#" class="r-r-md" onclick="getPageVisit(' + nextPageUrl + ', ' + perPage + ', ' + "'" + table + "'" + ')">&raquo;</a>';
    html += '</div>';

    return html;
}

function getPageVisit(page, perPage, table_n)
{
    var table_name = table_n + 'Table';
    var getUrl = window.location.pathname;
    var type = $('#status').val();
    var total = parseInt($('#get-total-' + table_n).val());
    var guest_profile = $('#profile-'+ table_n).val();


    $('#currentPage-' + table_n).val(page);

    $('#' + table_name).DataTable().destroy();
    if (table_n == "company-Visit") {
        console.log(table_name);
        var table = $('#' + table_name).dataTable({
            searching: false,
            paging: false,
            info: false,
            ajax: {
                url: '/Visit-company-paginate-table',
                type: 'POST',
                dataType: "json",
                cache: false,
                data: {
                    page: page,
                    perPage: perPage,
                    table_name: table_name,
                    status: type,
                    guest_profile:guest_profile,
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            },
            columnDefs: [
                { targets: [0, 1, 3, 4, 5, 6, 7, 8, 9,10], className: 'dt-center td-content-center' },
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
                { data: 'ID' },
                { data: 'Company' },
                { data: 'IssueDate' },
                { data: 'ExpirationDate' },
                { data: 'CheckIn' },
                { data: 'CheckOut' },
                { data: 'Discount' },
                { data: 'OperatedBy' },
                { data: 'Documentstatus' },
                { data: 'Order' },
            ],

        });
    }
    $('#' + table_n + '-paginate').children().remove().end();
    $('#' + table_n + '-showingEntries').text(showingEntriesSearchVisit(page, total, table_n));
    $('#' + table_n + '-paginate').append(paginateSearchVisit(total, table_n, getUrl));

}
//--------------------Contact
function showingEntriesSearchContact($page, $total, $table_name)
{
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
function paginateSearchContact($total, $table, $link)
{
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
    html += '<a href="#" class="r-l-md" onclick="getPageContact(' + previousPageUrl + ', ' + perPage + ', ' + "'" + table + "'" + ')">&laquo;</a>';
    if (total > 0) {
        if (currentPage > 3)
        {
            html += '<a class="" href="#" onclick="getPageContact(1, ' + perPage + ', ' + "'" + table + "'" + ')">1</a>';

            // if (currentPage > 3)
            // {
                html += '<a class="" href="#">...</a>';
            // }
        }

        for ($i = Math.max(1, currentPage - 2); $i <= Math.min(Math.ceil(total / perPage), currentPage + 2); $i++)
        {
            console.log($i);

            if ($i == currentPage)
            {
                html += '<a class="active" href="#" onclick="getPageContact(' + $i + ', ' + perPage + ', ' + "'" + table + "'" + ')">' + $i + '</a>';
            } else {
                html += '<a class="" href="#" onclick="getPageContact(' + $i + ', ' + perPage + ', ' + "'" + table + "'" + ')">'+ $i +'</a>';
            }
        }

        if (currentPage < Math.ceil(total / perPage) - 2)
        {
            if (currentPage < Math.ceil(total / perPage) - 3)
            {
                html += '<a class="" href="#">...</a>';
            }
            html += '<a href="#" onclick="getPageContact(' + Math.ceil(total / perPage) + ', ' + perPage + ', ' + "'" + table + "'" + ')">'+ Math.ceil(total / perPage) +'</a>';
        }
    }
    html += '<a href="#" class="r-r-md" onclick="getPageContact(' + nextPageUrl + ', ' + perPage + ', ' + "'" + table + "'" + ')">&raquo;</a>';
    html += '</div>';

    return html;
}

function getPageContact(page, perPage, table_n)
{
    var table_name = table_n + 'Table';
    var getUrl = window.location.pathname;
    var type = $('#status').val();
    var total = parseInt($('#get-total-' + table_n).val());
    var guest_profile = $('#profile-'+ table_n).val();

    $('#currentPage-' + table_n).val(page);

    $('#' + table_name).DataTable().destroy();
    if (table_n == "company-Contact") {

        var table = $('#' + table_name).dataTable({
            searching: false,
            paging: false,
            info: false,
            ajax: {
                url: '/Contact-company-paginate-table',
                type: 'POST',
                dataType: "json",
                cache: false,
                data: {
                    page: page,
                    perPage: perPage,
                    table_name: table_name,
                    status: type,
                    guest_profile:guest_profile,
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            },
            columnDefs: [
                { targets: [0, 1, 3,4,5,6], className: 'dt-center td-content-center' },
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
                { data: 'Profile_ID_TAX' },
                { data: 'Company' },
                { data: 'Branch' },
                { data: 'Name' },
                { data: 'Status' },
                { data: 'Order' },
            ],

        });
    }
    $('#' + table_n + '-paginate').children().remove().end();
    $('#' + table_n + '-showingEntries').text(showingEntriesSearchContact(page, total, table_n));
    $('#' + table_n + '-paginate').append(paginateSearchContact(total, table_n, getUrl));

}
//---------------------log-----------------
function showingEntriesSearchLog($page, $total, $table_name)
{
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
function paginateSearchLog($total, $table, $link)
{
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
    html += '<a href="#" class="r-l-md" onclick="getPageLog(' + previousPageUrl + ', ' + perPage + ', ' + "'" + table + "'" + ')">&laquo;</a>';
    if (total > 0) {
        if (currentPage > 3)
        {
            html += '<a class="" href="#" onclick="getPageLog(1, ' + perPage + ', ' + "'" + table + "'" + ')">1</a>';

            // if (currentPage > 3)
            // {
                html += '<a class="" href="#">...</a>';
            // }
        }

        for ($i = Math.max(1, currentPage - 2); $i <= Math.min(Math.ceil(total / perPage), currentPage + 2); $i++)
        {
            console.log($i);

            if ($i == currentPage)
            {
                html += '<a class="active" href="#" onclick="getPageLog(' + $i + ', ' + perPage + ', ' + "'" + table + "'" + ')">' + $i + '</a>';
            } else {
                html += '<a class="" href="#" onclick="getPageLog(' + $i + ', ' + perPage + ', ' + "'" + table + "'" + ')">'+ $i +'</a>';
            }
        }

        if (currentPage < Math.ceil(total / perPage) - 2)
        {
            if (currentPage < Math.ceil(total / perPage) - 3)
            {
                html += '<a class="" href="#">...</a>';
            }
            html += '<a href="#" onclick="getPageLog(' + Math.ceil(total / perPage) + ', ' + perPage + ', ' + "'" + table + "'" + ')">'+ Math.ceil(total / perPage) +'</a>';
        }
    }
    html += '<a href="#" class="r-r-md" onclick="getPageLog(' + nextPageUrl + ', ' + perPage + ', ' + "'" + table + "'" + ')">&raquo;</a>';
    html += '</div>';

    return html;
}

function getPageLog(page, perPage, table_n)
{
    var table_name = table_n + 'Table';
    var getUrl = window.location.pathname;
    var type = $('#status').val();
    var total = parseInt($('#get-total-' + table_n).val());
    var guest_profile = $('#profile-'+ table_n).val();

    $('#currentPage-' + table_n).val(page);

    $('#' + table_name).DataTable().destroy();
    if (table_n == "company-Log") {
        var table = $('#' + table_name).dataTable({
            searching: false,
            paging: false,
            info: false,
            ajax: {
                url: '/Log-company-paginate-table',
                type: 'POST',
                dataType: "json",
                cache: false,
                data: {
                    page: page,
                    perPage: perPage,
                    table_name: table_name,
                    status: type,
                    guest_profile:guest_profile,
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            },
            columnDefs: [
                { targets: [0, 2, 3, 4], className: 'dt-center td-content-center' },
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
                { data: 'Category' },
                { data: 'type' },
                { data: 'Created_by' },
                { data: 'created_at' },
                { data: 'Content' },
            ],

        });
    }

    $('#' + table_n + '-paginate').children().remove().end();
    $('#' + table_n + '-showingEntries').text(showingEntriesSearchLog(page, total, table_n));
    $('#' + table_n + '-paginate').append(paginateSearchLog(total, table_n, getUrl));

}
