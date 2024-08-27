function getPage(page, perPage, table_n) {
    var table_name = table_n + 'Table';
    var getUrl = window.location.pathname;
    var type = $('#status').val();



    $('#currentPage-' + table_n).val(page);

    $('#' + table_name).DataTable().destroy();
    if (table_n == "guest") {
        console.log(table_name);
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
                { data: 'id', "render": function (data, type, row, meta) {
                 return meta.row + meta.settings._iDisplayStart + 1; } },
                { data: 'Profile_ID' },
                { data: 'name' },
                { data: 'Booking_Channel' },
                { data: 'status' },
                { data: 'btn_action' },
            ],

        });
    }

    // $('#' + table_n + '-paginate').children().remove().end();
    // $('#' + table_n + '-showingEntries').text(showingEntriesSearch(page, total, table_n));
    // $('#' + table_n + '-paginate').append(paginateSearch(total, table_n, getUrl));

}
