$(document).ready(function () {
  let today = new Date();
  let currentMonth = today.getMonth();
  let currentYear = today.getFullYear();
  let selectedYear = null;
  let selectedMonthRange = [];
  let selectedDate = null;
  let selectedMonth = null;
  let lastSelectedValue = $('#combined-selected-box').val(); // เก็บค่าล่าสุดที่เลือก

  const monthNames = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
  ];

  // ฟังก์ชันสำหรับอัปเดตกล่องแสดงค่าที่เลือก
  function updateCombinedSelectedBox(value) {
    // ถ้า value เป็น null หรือ undefined ให้ใช้วันที่วันนี้เป็นค่าเริ่มต้น
    if (!value) {
      let today = new Date();
      // ฟอร์แมตวันที่เป็น 13 September 2024
      value = today.toLocaleDateString("en-GB", {
        day: "numeric",
        month: "long",
        year: "numeric",
      });
    }

    lastSelectedValue = value; // เก็บค่าที่เลือกล่าสุด
    // console.log("Updated lastSelectedValue:", lastSelectedValue); // ตรวจสอบว่าค่าถูกอัปเดต
    $("#combined-selected-box").val(`${value}`);
  }
  updateCombinedSelectedBox(lastSelectedValue); // ไม่มีการส่งค่า ให้แสดงวันที่วันนี้โดยอัตโนมัติ

  // ฟังก์ชันเมื่อกดปุ่มเลือกวันนี้
  $("#select-today-button").click(function () {
    selectToday(); // เรียกใช้ฟังก์ชันเลือกวันที่วันนี้
  });

  // ฟังก์ชันสำหรับเลือกวันที่วันนี้และส่งค่า
  function selectToday() {
    selectedDate = today.getDate();
    selectedMonth = today.getMonth();
    selectedYear = today.getFullYear();

    const todayString = `${selectedDate} ${monthNames[selectedMonth]} ${selectedYear}`;
    updateCombinedSelectedBox(todayString); // อัปเดตด้วยวันที่วันนี้
    alert(`Sending Today's Date: ${todayString}`);
  }

  // ฟังก์ชันสำหรับอัปเดตเดือนที่เลือก
  function updateSelectedMonthBox() {
    if (selectedMonthRange.length === 2) {
      // ตรวจสอบว่าเดือนแรกและเดือนสุดท้ายเหมือนกันหรือไม่
      if (selectedMonthRange[0] === selectedMonthRange[1]) {
        // ถ้าเลือกแค่เดือนเดียว (แม้จะใช้เป็น range) ให้แสดงแค่เดือนเดียว
        const monthString = `${monthNames[selectedMonthRange[0]]
          } ${selectedYear}`;
        $("#selected-month-box").text(`${monthString}`);
        updateCombinedSelectedBox(monthString);
      } else {
        // ถ้าเลือกสองเดือนที่ต่างกัน ให้แสดงเป็นช่วงของเดือน
        const monthRangeString = `${monthNames[selectedMonthRange[0]]} - ${monthNames[selectedMonthRange[1]]
          } ${selectedYear}`;
        $("#selected-month-box").text(`${monthRangeString}`);
        updateCombinedSelectedBox(monthRangeString);
      }
    } else if (selectedMonthRange.length === 1) {
      // เมื่อเลือกเดือนเดียว ให้แสดงเฉพาะเดือนนั้น
      const monthString = `${monthNames[selectedMonthRange[0]]
        } ${selectedYear}`;
      $("#selected-month-box").text(`${monthString}`);
      updateCombinedSelectedBox(monthString);
    } else {
      // ถ้ายังไม่ได้เลือกเดือน ให้แสดงข้อความนี้
      $("#selected-month-box").text("Selected Month: None");
    }
  }

  // ฟังก์ชันสำหรับอัปเดตปีที่เลือก
  function updateSelectedYearBox() {
    if (selectedYear !== null) {
      $("#selected-year-box").text(`${selectedYear}`);
      updateCombinedSelectedBox(selectedYear);
    } else {
      $("#selected-year-box").text("Selected Year: None");
    }
  }

  function generateMonthRangePicker(year) {
    $("#year").text(year);
    $(".months-grid").empty();

    // var filter_by = $('#filter-by').val();

    // if (filter_by != "month" && filter_by != "year") {
    //   localStorage.removeItem("selectedYear");
    //   localStorage.removeItem("selectedMonthRange");
    // }

    // Get the current month and year
    const currentMonth = new Date().getMonth();
    const currentYear = new Date().getFullYear();

    // โหลดค่าที่เลือกไว้ก่อนหน้านี้จาก localStorage
    const savedMonthRange = JSON.parse(localStorage.getItem("selectedMonthRange")) || [];
    const savedYear = parseInt(localStorage.getItem("selectedYear"), 10) || year;

    selectedMonthRange = savedMonthRange;
    selectedYear = savedYear;

    monthNames.forEach((month, index) => {
      let monthElement = $(`<div class="month no-select" data-month="${index}">${month}</div>`);
      monthElement.removeClass("current-month");

      // ตรวจสอบและเพิ่มคลาส selected-range หากตรงกับค่าที่เลือกก่อนหน้านี้
      if (selectedMonthRange.length === 2 && selectedYear === year && index >= selectedMonthRange[0] && index <= selectedMonthRange[1]) 
      {
        monthElement.addClass("selected-range");
      } else if (
        selectedMonthRange.length === 1 &&
        selectedYear === year &&
        index === selectedMonthRange[0]
      ) {
        monthElement.addClass("selected-range");
      }
      
      // Add the current-month class to the current month
      if (selectedMonthRange.length == 0 && index === currentMonth && year === currentYear) {
        monthElement.addClass("selected-range");
      }
      $(".months-grid").append(monthElement);

      // ฟังก์ชันเริ่มต้นการลากเลือก
      function startSelection() {
        startMonth = index;
        endMonth = index;
        $(".month").removeClass("current-month selected-range");
        monthElement.addClass("selected-range");
      }

      // ฟังก์ชันเลือกช่วงเดือน
      function moveSelection(event) {
        let targetMonth;
        if (event.type === "mousemove") {
          targetMonth = $(this).data("month");
        } else if (event.type === "touchmove") {
          event.preventDefault();
          let touch = event.originalEvent.touches[0];
          let targetElement = document.elementFromPoint(
            touch.clientX,
            touch.clientY
          );
          targetMonth = $(targetElement).data("month");
        }

        if (targetMonth !== undefined) {
          endMonth = targetMonth;
          $(".month").removeClass("current-month selected-range");
          if (startMonth <= endMonth) {
            for (let i = startMonth; i <= endMonth; i++) {
              $(`.month[data-month=${i}]`).addClass("selected-range");
            }
          } else {
            for (let i = endMonth; i <= startMonth; i++) {
              $(`.month[data-month=${i}]`).addClass("selected-range");
            }
          }
        }
      }

      // ฟังก์ชันสิ้นสุดการลากเลือก
      function endSelection() {
        selectedMonthRange = [
          Math.min(startMonth, endMonth),
          Math.max(startMonth, endMonth),
        ];
        selectedYear = year;

        // บันทึกค่าลงใน localStorage
        localStorage.setItem(
          "selectedMonthRange",
          JSON.stringify(selectedMonthRange)
        );
        localStorage.setItem("selectedYear", selectedYear);

        updateSelectedMonthBox(); // อัปเดตกล่องแสดงค่าที่เลือก
      }

      // Event สำหรับเมาส์และการสัมผัส
      monthElement.mousedown(function () {
        isMouseDown = true;
        startSelection();
      });

      monthElement.mousemove(function () {
        if (isMouseDown) {
          moveSelection.call(this, event);
        }
      });

      monthElement.mouseup(function () {
        isMouseDown = false;
        endSelection();
      });

      monthElement.on("touchstart", function () {
        isTouching = true;
        startSelection();
      });

      monthElement.on("touchmove", function (event) {
        if (isTouching) {
          moveSelection.call(this, event);
        }
      });

      monthElement.on("touchend", function () {
        isTouching = false;
        endSelection();
      });

      $(document).on("mouseup touchend", function () {
        isMouseDown = false;
        isTouching = false;
      });
    });
  }

  // ฟังก์ชันโหลดและคืนค่าจาก localStorage

  $(function () {
    const currentYearStored =
      parseInt(localStorage.getItem("selectedYear"), 10) ||
      new Date().getFullYear();
    currentYear = currentYearStored;
    generateMonthRangePicker(currentYear);
  });

  $("#prev-year").click(function () {
    currentYear--; // ลดปีลง 1
    localStorage.setItem("selectedYear", currentYear);
    generateMonthRangePicker(currentYear);
    $(".month").removeClass("selected-range");
  });

  $("#next-year").click(function () {
    currentYear++; // เพิ่มปีขึ้น 1
    localStorage.setItem("selectedYear", currentYear);
    generateMonthRangePicker(currentYear);
    $(".month").removeClass("selected-range");
  });

  function generateYearPicker(startYear, endYear) {
    $(".years-grid").empty();

    var filter_by = $('#filter-by').val();

    if (filter_by != "year") {
      localStorage.removeItem("selectedYear");
    }

    // โหลดปีที่เลือกไว้ก่อนหน้านี้จาก localStorage
    const currentYear = new Date().getFullYear();
    const savedYear = parseInt(localStorage.getItem("selectedYear"), 10) || currentYear;

    for (let year = startYear; year <= endYear; year++) {
      let yearElement = $(`<div class="year not-select">${year}</div>`);

      // ถ้ามี savedYear
      if (savedYear && year === savedYear) {
        yearElement.addClass("selected-range");
        selectedYear = savedYear;

        // ลบคลาส current-year จากปีปัจจุบัน
        $(".year").removeClass("current-year");
      }
      // ถ้าไม่มี savedYear ให้เน้นปีปัจจุบัน
      else if (!savedYear && year === currentYear) {
        yearElement.addClass("current-year");
        selectedYear = currentYear;

        // ลบคลาส selected-range จากปีอื่นๆ
        $(".year").removeClass("selected-range");
      }

      $(".years-grid").append(yearElement);

      yearElement.click(function () {
        $(".year").removeClass("current-year selected-range");
        $(this).addClass("selected-range");

        selectedYear = year;

        // บันทึกปีที่เลือกไว้ใน localStorage
        localStorage.setItem("selectedYear", selectedYear);

        updateSelectedYearBox(); // อัปเดตการแสดงผลปีที่เลือก
        generateMonthRangePicker(selectedYear); // สร้าง Month Picker สำหรับปีที่เลือก
      });
    }
  }

  // ฟังก์ชันสำหรับการนำทางระหว่างเดือนและปี
  $("#prev-month").click(function () {
    currentMonth = currentMonth === 0 ? 11 : currentMonth - 1;
    if (currentMonth === 11) currentYear--;
    generateDatePicker(currentMonth, currentYear);
  });

  $("#next-month").click(function () {
    currentMonth = currentMonth === 11 ? 0 : currentMonth + 1;
    if (currentMonth === 0) currentYear++;
    generateDatePicker(currentMonth, currentYear);
  });

  $("#prev-year").click(function () {
    currentYear--;
    generateMonthRangePicker(currentYear);
  });

  $("#next-year").click(function () {
    currentYear++;
    generateMonthRangePicker(currentYear);
  });

  // การสร้างปฏิทินเริ่มต้น
  generateMonthRangePicker(currentYear);
  generateYearPicker(2024, 2026);

  // ฟังก์ชันสำหรับการกรอง
  $("#filter-date").on("click", function () {
    $("#date-picker-wrapper").show();
    $("#month-picker-wrapper, #year-picker-wrapper").hide();
    // updateCombinedSelectedBox(null); // Reset to today's date
  });

  $("#filter-month").on("click", function () {
    $("#month-picker-wrapper").show();
    $("#date-picker-wrapper, #year-picker-wrapper").hide();
    updateSelectedMonthBox(); // Update combined box with latest month selection
  });

  $("#filter-year").on("click", function () {
    $("#year-picker-wrapper").show();
    $("#date-picker-wrapper, #month-picker-wrapper").hide();
    // updateSelectedYearBox(); // Update combined box with latest year selection
  });
});
