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
    var type = $('#status-'+table_n).val();
    var get_total = parseInt($('#get-total-' + table_n).val());
    var getUrl = window.location.pathname;
    var search_value = $('.search-data-agoda-'+table_n).val();

    $('#currentPage-' + table_n).val(page);

    $('#' + table_name).DataTable().destroy();
        // Agoda Revenue
        if (table_n == "agodaRevenue") {
            var year = $('#agodaRevenueYearFilter').val();
            var table = $('#' + table_name).DataTable({
                searching: false,
                paging: false,
                info: false,
                ajax: {
                    url: '/debtor-agoda-paginate-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        page: page,
                        perPage: perPage,
                        table_name: table_name,
                        year: year,
                        search_value: search_value,
                        status: type,
                    },
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    dataSrc: function (json) {
                        // เก็บค่า total จาก response
                        total = json.total;
                        totalAllReceive = json.totalAllReceive;
                        totalAllItem = json.totalAllItem;
                        return json.data; // ใช้ data สำหรับแสดงผลใน DataTable
                    }
                },
                initComplete: function (settings, json) {
                    $('#txt-total-revenue').text(total);
                    $('#txt-total-item').text(totalAllReceive+"/"+totalAllItem);

                    if (month == 'all') {
                        var count_total = get_total;
                    } else {
                        var count_total = totalList;
                    }

                    $('#' + table_n + '-paginate').children().remove().end();
                    $('#' + table_n + '-showingEntries').text(showingEntriesSearch(page, count_total, table_n));
                    $('#' + table_n + '-paginate').append(paginateSearch(count_total, table_n, getUrl));
                },
                columnDefs: [
                    { targets: [0, 3, 4, 5], className: 'dt-center td-content-center' },
                    { targets: [1], className: 'text-start' },
                    { targets: [2], className: 'text-end' },
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
                    { data: 'month' },
                    { data: 'agoda_paid' },
                    { data: 'item' },
                    { data: 'status' },
                    { data: 'btn_detail' },
                ],
                createdRow: function (row, data, dataIndex) {
                    // เพิ่ม attribute data-group ให้ tr
                    $(row).attr('class', 'parent-row');
                    $(row).attr('data-group', 'group' + data.id);
    
                    // ค้นหา parent row ที่เกี่ยวข้อง
                    var parentRow = $('tr.parent-row[data-group="group' + data.id + '"]');
                    
                    // สร้าง child row ใหม่
                    var childRow = `
                        <tr class="child-row" data-group="group${data.id}" style="display: none;">
                        </tr>`;
    
                    // เพิ่ม child row หลัง parent row
                    parentRow.after(childRow);
                }
            });
        } 

        // Agoda Outstanding
        if (table_n == "agodaOutstanding") {
            var year = $('#agodaOutstandingYearFilter').val();
            var month = $('#agodaOutstandingMonthFilter').val();

            var table = $('#' + table_name).DataTable({
                searching: false,
                paging: false,
                info: false,
                ajax: {
                    url: '/debtor-agoda-paginate-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        page: page,
                        perPage: perPage,
                        table_name: table_name,
                        year: year,
                        month: month,
                        search_value: search_value,
                        status: type,
                    },
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    dataSrc: function (json) {
                        // เก็บค่า total จาก response
                        total = json.total;
                        totalList = json.totalList;
                        return json.data; // ใช้ data สำหรับแสดงผลใน DataTable
                    }
                },
                initComplete: function (settings, json) {
                    // $('#txt-total-outstanding').text(total);
                    if (month == 'all') {
                        var count_total = get_total;
                    } else {
                        var count_total = totalList;
                    }

                    $('#' + table_n + '-paginate').children().remove().end();
                    $('#' + table_n + '-showingEntries').text(showingEntriesSearch(page, count_total, table_n));
                    $('#' + table_n + '-paginate').append(paginateSearch(count_total, table_n, getUrl));
                },
                columnDefs: [
                    { targets: [0, 1, 2, 3, 4, 6], className: 'dt-center td-content-center' },
                    { targets: [5], className: 'text-end' },
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
                    { data: 'booking' },
                    { data: 'check_in' },
                    { data: 'check_out' },
                    { data: 'amount' },
                    { data: 'status' },
                ],
            });
        } 

        // Agoda Debit
        if (table_n == "agodaDebit") {
            var year = $('#agodaDebitYearFilter').val();
            var month = $('#agodaDebitMonthFilter').val();

            var table = $('#' + table_name).DataTable({
                searching: false,
                paging: false,
                info: false,
                ajax: {
                    url: '/debtor-agoda-paginate-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        page: page,
                        perPage: perPage,
                        table_name: table_name,
                        year: year,
                        month: month,
                        search_value: search_value,
                        status: type,
                    },
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    dataSrc: function (json) {
                        // เก็บค่า total จาก response
                        total = json.total;
                        totalList = json.totalList;
                        return json.data; // ใช้ data สำหรับแสดงผลใน DataTable
                    }
                },
                initComplete: function (settings, json) {
                    // $('#txt-total-debit').text(total);
                    if (month == 'all') {
                        var count_total = get_total;
                    } else {
                        var count_total = totalList;
                    }

                    $('#' + table_n + '-paginate').children().remove().end();
                    $('#' + table_n + '-showingEntries').text(showingEntriesSearch(page, count_total, table_n));
                    $('#' + table_n + '-paginate').append(paginateSearch(count_total, table_n, getUrl));
                },
                columnDefs: [
                    { targets: [0, 1, 2, 3, 4, 6], className: 'dt-center td-content-center' },
                    { targets: [5], className: 'text-end' },
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
                    { data: 'booking' },
                    { data: 'check_in' },
                    { data: 'check_out' },
                    { data: 'amount' },
                    { data: 'status' },
                ],
            });
        } 

        // Agoda Debit (ชำระเเล้ว), (ตารางหน้า Create, Edit, Detail)
        if (table_n == "agodaDebitDetail") {
            var smsID = $('#sms-id').val();
            var table = $('#' + table_name).DataTable({
                searching: false,
                paging: false,
                info: false,
                ajax: {
                    url: '/debtor-agoda-paginate-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        page: page,
                        perPage: perPage,
                        table_name: table_name,
                        sms_id: smsID,
                        search_value: search_value,
                        status: type,
                    },
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    dataSrc: function (json) {
                        // เก็บค่า total จาก response
                        total = json.total;
                        totalList = json.totalList;
                        return json.data; // ใช้ data สำหรับแสดงผลใน DataTable
                    }
                },
                    initComplete: function (settings, json) {
                        // $('#txt-total-debit').text(total);
                        $('#' + table_n + '-paginate').children().remove().end();
                        $('#' + table_n + '-showingEntries').text(showingEntriesSearch(page, totalList, table_n));
                        $('#' + table_n + '-paginate').append(paginateSearch(totalList, table_n, getUrl));
                    },
                    columnDefs: [
                        { targets: [0, 1, 2, 3], className: 'dt-center td-content-center' },
                        { targets: [4], className: 'text-end' },
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
                        { data: 'booking' },
                        { data: 'check_in' },
                        { data: 'check_out' },
                        { data: 'amount' },
                    ],
                });
        }

        // Agoda Revenue (ด้านใน แสดงข้อมูลตามวัน)
        if (table_n == "agodaRevenueDay") {
            var year = $('#agodaRevenueDayYearFilter').val();
            var month = $('#agodaRevenueDayMonthFilter').val();
            var status_paid = $('#agodaRevenueDayStatusFilter').val();
            var table = $('#' + table_name).DataTable({
                searching: false,
                paging: false,
                info: false,
                ajax: {
                    url: '/debtor-agoda-paginate-table',
                    type: 'POST',
                    dataType: "json",
                    cache: false,
                    data: {
                        page: page,
                        perPage: perPage,
                        table_name: table_name,
                        status_paid: status_paid,
                        year: year,
                        month: month,
                        search_value: search_value,
                        status: type,
                    },
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    dataSrc: function (json) {
                        // เก็บค่า total จาก response
                        total = json.total;
                        totalList = json.totalList;
                        return json.data; // ใช้ data สำหรับแสดงผลใน DataTable
                    }
                },
                    initComplete: function (settings, json) {
                        // $('#txt-total-debit').text(total);
                        $('#' + table_n + '-paginate').children().remove().end();
                        $('#' + table_n + '-showingEntries').text(showingEntriesSearch(page, totalList, table_n));
                        $('#' + table_n + '-paginate').append(paginateSearch(totalList, table_n, getUrl));
                    },
                    columnDefs: [
                        { targets: [0, 1, 3, 4, 5], className: 'dt-center td-content-center' },
                        { targets: [2], className: 'text-end' },
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
                        { data: 'amount' },
                        { data: 'status' },
                        { data: 'lock_unlock' },
                        { data: 'btn_detail' },
                    ],
                });
        }
}