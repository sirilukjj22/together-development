$(document).ready(function () {
  $(".table-together").DataTable({
    searching: true,
    paging: true,
    info: true,
    order: true,
    serverSide: false,
    responsive: {
      details: {
        type: "column",
        target: "tr",
      },
    },
    initComplete: function () {
      $(".btn-dropdown-menu").dropdown(); // ทำให้ dropdown ทำงาน
      $('.dropdown-toggle').dropdown({
        appendTo: 'body'
    });
    },
    columnDefs: [
      {
        targets: "_all", // ใช้กับทุกคอลัมน์หรือกำหนดเป้าหมายตามต้องการ
        createdCell: function (td, cellData, rowData, row, col) {
          // ตรวจสอบว่าเซลล์มีคลาส target-class หรือไม่
          if ($(td).hasClass("target-class") && $.isNumeric(cellData)) {
            $(td).text(
              parseFloat(cellData).toLocaleString("en-US", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
              })
            );
          }
        },
      },
    ],
  });

  // Function to adjust DataTable
  function adjustDataTable() {
    $.fn.dataTable
      .tables({
        visible: true,
        api: true,
      })
      .columns.adjust()
      .responsive.recalc();
  }
  // $("#formModal").on("shown.bs.modal", adjustDataTable);
  $(window).on("resize", adjustDataTable);

  $('input[type="search"]').attr("placeholder", "Type to search...");
  $('label[for^="dt-length-"], label[for^="dt-search-"]').hide();
});

$(document).ready(function () {
  $(".format-number-table").each(function () {
    const rawValue = parseFloat($(this).text());
    // ตรวจสอบว่าเป็นตัวเลขหรือไม่
    if (!isNaN(rawValue)) {
      // ฟอร์แมตตัวเลขและใส่กลับไปใน <td>
      $(this).text(
        rawValue.toLocaleString("en-US", {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2,
        })
      );
    }
  });
});
